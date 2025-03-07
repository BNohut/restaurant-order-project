<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

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
        $pendingStatus = Status::where('name', 'Pending')->first();
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
        $order = Order::with(['status', 'company', 'address', 'items.product', 'courier'])
            ->where('uuid', $uuid)
            ->where(function ($query) {
                $user = Auth::user();
                if ($user->hasRole('client')) {
                    $query->where('user_uuid', $user->uuid);
                } elseif ($user->hasRole('manager')) {
                    $query->whereHas('company', function ($q) use ($user) {
                        $q->where('owner_uuid', $user->uuid);
                    });
                } elseif ($user->hasRole('courier')) {
                    $query->where('courier_uuid', $user->uuid)
                        ->orWhereNull('courier_uuid');
                }
                // Admin can see all orders
            })
            ->firstOrFail();

        return view('orders.show', compact('order'));
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
        $canceledStatus = Status::where('name', 'Canceled')->first();
        if (!$canceledStatus) {
            // Create canceled status if it doesn't exist
            $canceledStatus = Status::create([
                'name' => 'canceled',
                'description' => 'Order has been canceled'
            ]);
        }

        // Only allow cancellation of pending orders
        $pendingStatus = Status::where('name', 'Pending')->first();

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

    /**
     * Display a listing of the orders for the manager's restaurants.
     */
    public function manageOrders()
    {
        $user = Auth::user();
        if (!$user->hasRole('manager')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $orders = Order::with(['status', 'company', 'customer', 'courier'])
            ->whereHas('company', function ($query) use ($user) {
                $query->where('owner_uuid', $user->uuid);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $couriers = User::role('courier')->get();

        return view('orders.manage', compact('orders', 'couriers'));
    }

    /**
     * Approve the specified order.
     */
    public function approveOrder($uuid)
    {
        $user = Auth::user();

        if (!$user->hasRole('manager')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $order = Order::where('uuid', $uuid)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('owner_uuid', $user->uuid);
            })
            ->firstOrFail();

        // Check if order can be approved (only pending orders)
        $pendingStatus = Status::where('name', 'Pending')->first();
        if ($order->status_uuid !== $pendingStatus->uuid) {
            return redirect()->back()
                ->with('error', 'Only pending orders can be approved.');
        }

        // Update order status to confirmed
        $confirmedStatus = Status::where('name', 'Approved')->first();
        $order->update(['status_uuid' => $confirmedStatus->uuid]);

        return redirect()->route('orders.manage')
            ->with('success', 'Order approved successfully.');
    }

    /**
     * Reject the specified order.
     */
    public function rejectOrder($uuid)
    {
        $user = Auth::user();

        if (!$user->hasRole('manager')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $order = Order::where('uuid', $uuid)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('owner_uuid', $user->uuid);
            })
            ->firstOrFail();

        // Check if order can be rejected (only pending orders)
        $pendingStatus = Status::where('name', 'pending')->first();
        if ($order->status_uuid !== $pendingStatus->uuid) {
            return redirect()->back()
                ->with('error', 'Only pending orders can be rejected.');
        }

        // Update order status to rejected
        $rejectedStatus = Status::where('name', 'Rejected')->first();
        $order->update(['status_uuid' => $rejectedStatus->uuid]);

        return redirect()->route('orders.manage')
            ->with('success', 'Order rejected successfully.');
    }

    /**
     * Assign a courier to the specified order.
     */
    public function assignCourier(Request $request, $uuid)
    {
        $user = Auth::user();

        if (!$user->hasRole('manager')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $validator = Validator::make($request->all(), [
            'courier_uuid' => 'required|exists:users,uuid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order = Order::where('uuid', $uuid)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('owner_uuid', $user->uuid);
            })
            ->firstOrFail();

        // Check if order is in a status that allows courier assignment
        $confirmedStatus = Status::where('name', 'Approved')->first();
        if ($order->status_uuid !== $confirmedStatus->uuid) {
            return redirect()->back()
                ->with('error', 'Only confirmed orders can be assigned to couriers.');
        }

        // Verify the courier exists and has the courier role
        $courier = User::where('uuid', $request->courier_uuid)
            ->role('courier')
            ->first();

        if (!$courier) {
            return redirect()->back()
                ->with('error', 'Selected courier not found.');
        }

        // Update order with courier and change status to preparing
        $preparingStatus = Status::where('name', 'Preparing')->first();
        $order->update([
            'courier_uuid' => $courier->uuid,
            'status_uuid' => $preparingStatus->uuid
        ]);

        return redirect()->route('orders.manage')
            ->with('success', 'Courier assigned successfully.');
    }

    /**
     * Display a listing of the orders assigned to the courier.
     */
    public function courierOrders()
    {
        $user = Auth::user();

        if (!$user->hasRole('courier')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $orders = Order::with(['status', 'company', 'address', 'customer'])
            ->where('courier_uuid', $user->uuid)
            ->whereHas('status', function ($query) {
                $query->whereIn('name', ['Preparing', 'Ready for pickup', 'On the way', 'Delivered']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.courier', compact('orders'));
    }

    /**
     * Update the order status to "on the way".
     */
    public function markOnTheWay($uuid)
    {
        $user = Auth::user();

        if (!$user->hasRole('courier')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $order = Order::where('uuid', $uuid)
            ->where('courier_uuid', $user->uuid)
            ->firstOrFail();

        // Check if order can be marked as on the way (only ready orders)
        $readyStatus = Status::where('name', 'Ready for pickup')->first();
        if ($order->status_uuid !== $readyStatus->uuid) {
            return redirect()->back()
                ->with('error', 'Only ready orders can be marked as on the way.');
        }

        // Update order status to on the way
        $onTheWayStatus = Status::where('name', 'On the way')->first();
        $order->update(['status_uuid' => $onTheWayStatus->uuid]);

        return redirect()->route('orders.courier')
            ->with('success', 'Order marked as on the way.');
    }

    /**
     * Update the order status to "delivered".
     */
    public function markDelivered($uuid)
    {
        $user = Auth::user();

        if (!$user->hasRole('courier')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $order = Order::where('uuid', $uuid)
            ->where('courier_uuid', $user->uuid)
            ->firstOrFail();

        // Check if order can be marked as delivered (only on the way orders)
        $onTheWayStatus = Status::where('name', 'On the way')->first();
        if ($order->status_uuid !== $onTheWayStatus->uuid) {
            return redirect()->back()
                ->with('error', 'Only orders that are on the way can be marked as delivered.');
        }

        // Update order status to delivered
        $deliveredStatus = Status::where('name', 'Delivered')->first();
        $order->update([
            'status_uuid' => $deliveredStatus->uuid,
            'payment_status' => 'paid' // If cash on delivery, mark as paid when delivered
        ]);

        return redirect()->route('orders.courier')
            ->with('success', 'Order marked as delivered.');
    }

    /**
     * Mark an order as ready for pickup by courier.
     */
    public function markReady($uuid)
    {
        $user = Auth::user();

        if (!$user->hasRole('manager')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $order = Order::where('uuid', $uuid)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('owner_uuid', $user->uuid);
            })
            ->firstOrFail();

        // Check if order can be marked as ready (only preparing orders)
        $preparingStatus = Status::where('name', 'Preparing')->first();
        if ($order->status_uuid !== $preparingStatus->uuid) {
            return redirect()->back()
                ->with('error', 'Only preparing orders can be marked as ready.');
        }

        // Check if courier is assigned
        if (!$order->courier_uuid) {
            return redirect()->back()
                ->with('error', 'A courier must be assigned before marking the order as ready.');
        }

        // Update order status to ready
        $readyStatus = Status::where('name', 'Ready for pickup')->first();
        $order->update(['status_uuid' => $readyStatus->uuid]);

        return redirect()->route('orders.manage')
            ->with('success', 'Order marked as ready for pickup.');
    }
}
