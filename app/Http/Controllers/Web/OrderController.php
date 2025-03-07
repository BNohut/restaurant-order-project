<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     */
    public function index()
    {
        $orders = Order::with(['company', 'status', 'address'])
            ->where('user_uuid', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        return redirect()->route('cart.checkout');
    }

    /**
     * Store a newly created order in the database
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_uuid' => 'required|exists:addresses,uuid',
            'payment_method' => 'required|in:cash,credit_card,online',
            'notes' => 'sometimes|nullable|string|max:500'
        ]);

        // Get cart data
        $cart = session()->get('cart');

        if (empty($cart) || empty($cart['items'])) {
            return redirect()->route('cart.checkout')->with('error', 'Your cart is empty');
        }

        // Verify restaurant
        $restaurant = Company::find($cart['company_uuid']);
        if (!$restaurant || !$restaurant->is_active) {
            return redirect()->route('cart.index')->with('error', 'Restaurant is no longer available');
        }

        // Verify address belongs to user
        $address = Address::where('uuid', $request->address_uuid)
            ->where('user_uuid', Auth::id())
            ->first();

        if (!$address) {
            return redirect()->route('cart.checkout')->with('error', 'Invalid delivery address');
        }

        // Verify minimum order
        if ($cart['total'] < $restaurant->minimum_order) {
            return redirect()->route('cart.checkout')
                ->with('error', "Minimum order amount is ₺{$restaurant->minimum_order}");
        }

        // Get pending status
        $pendingStatus = Status::where('name', 'pending')->first();
        if (!$pendingStatus) {
            // Create pending status if it doesn't exist
            $pendingStatus = Status::create([
                'name' => 'pending',
                'description' => 'Order is pending confirmation'
            ]);
        }

        try {
            DB::beginTransaction();

            // Calculate tax (assuming 10% tax rate)
            $taxRate = 0.10;
            $taxAmount = $cart['total'] * $taxRate;

            // Calculate total with tax and delivery fee
            $totalWithTax = $cart['total'] + $taxAmount + $restaurant->delivery_fee;

            // Format cart items to match the expected structure for ProcessOrderItems trait
            $formattedItems = [];
            foreach ($cart['items'] as $item) {
                $formattedItems[] = [
                    'product_uuid' => $item['uuid'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'notes' => $item['notes'] ?? null,
                    'special_instructions' => $item['notes'] ?? null,
                    'name' => $item['name'],
                    'category' => $item['category'] ?? null,
                ];
            }

            // Create order
            $order = Order::create([
                'user_uuid' => Auth::id(),
                'company_uuid' => $restaurant->uuid,
                'address_uuid' => $address->uuid,
                'status_uuid' => $pendingStatus->uuid,
                'total_amount' => $totalWithTax,
                'delivery_fee' => $restaurant->delivery_fee,
                'tax_amount' => $taxAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'items_snapshot' => json_encode($formattedItems)
            ]);

            // Note: We're not manually creating order items here anymore
            // The ProcessOrderItems trait will handle this based on the items_snapshot

            DB::commit();

            // Clear cart after successful order
            session()->forget('cart');

            return redirect()->route('orders.show', $order->uuid)
                ->with('success', 'Your order has been placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('cart.checkout')
                ->with('error', 'There was a problem processing your order. Please try again.');
        }
    }

    /**
     * Display the specified order
     */
    public function show($uuid)
    {
        $order = Order::with(['company', 'status', 'address', 'items.product'])
            ->where('uuid', $uuid)
            ->where('user_uuid', Auth::id())
            ->firstOrFail();

        // Parse items snapshot
        $itemsSnapshot = json_decode($order->items_snapshot, true) ?? [];

        return view('orders.show', compact('order', 'itemsSnapshot'));
    }

    /**
     * Cancel the specified order
     */
    public function cancel($uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->where('user_uuid', Auth::id())
            ->firstOrFail();

        // Get canceled status
        $canceledStatus = Status::where('name', 'canceled')->first();
        if (!$canceledStatus) {
            // Create canceled status if it doesn't exist
            $canceledStatus = Status::create([
                'name' => 'canceled',
                'description' => 'Order has been canceled'
            ]);
        }

        // Only allow cancellation of pending orders
        $pendingStatus = Status::where('name', 'pending')->first();

        if ($order->status_uuid !== $pendingStatus->uuid) {
            return redirect()->route('orders.show', $order->uuid)
                ->with('error', 'Only pending orders can be canceled');
        }

        // Update order status to canceled
        $order->update([
            'status_uuid' => $canceledStatus->uuid
        ]);

        return redirect()->route('orders.show', $order->uuid)
            ->with('success', 'Your order has been canceled');
    }
}
