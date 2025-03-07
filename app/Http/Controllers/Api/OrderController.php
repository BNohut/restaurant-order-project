<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
  /**
   * Store a newly created order in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    try {
      // Validate the incoming request
      $validated = $request->validate([
        'company_uuid' => 'required|uuid|exists:companies,uuid',
        'address_uuid' => 'required|uuid|exists:addresses,uuid',
        'payment_method' => 'required|string|in:card,cash,online',
        'notes' => 'nullable|string|max:500',
        'delivery_time' => 'nullable|date',
        'items' => 'required|array|min:1',
        'items.*.product_uuid' => 'required|uuid|exists:products,uuid',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.special_instructions' => 'nullable|string|max:255',
      ]);

      // Begin transaction
      return DB::transaction(function () use ($request, $validated) {
        // Get the pending status
        $pendingStatus = Status::where('name', 'pending')->first();

        if (!$pendingStatus) {
          throw ValidationException::withMessages([
            'status' => ['Status "pending" not found in the system.'],
          ]);
        }

        // Prepare items for the snapshot with product details
        $items = [];
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
          // Get product details
          $product = Product::findOrFail($item['product_uuid']);
          $itemSubtotal = $product->price * $item['quantity'];
          $subtotal += $itemSubtotal;

          $items[] = [
            'product_uuid' => $item['product_uuid'],
            'quantity' => $item['quantity'],
            'unit_price' => $product->price,
            'subtotal' => $itemSubtotal,
            'special_instructions' => $item['special_instructions'] ?? null,
            'name' => $product->name,
            'category' => $product->category,
          ];
        }

        // Calculate totals
        $taxRate = 0.08; // 8% tax
        $deliveryFee = 5.00;
        $taxAmount = round($subtotal * $taxRate, 2);
        $totalAmount = $subtotal + $taxAmount + $deliveryFee;

        // Create the order with items snapshot and calculated values
        $order = Order::create([
          'user_uuid' => auth()->id(),
          'company_uuid' => $validated['company_uuid'],
          'address_uuid' => $validated['address_uuid'],
          'status_uuid' => $pendingStatus->uuid,
          'payment_method' => $validated['payment_method'],
          'payment_status' => 'pending',
          'notes' => $validated['notes'] ?? null,
          'delivery_time' => $validated['delivery_time'] ?? null,
          'items_snapshot' => json_encode($items),
          'total_amount' => $totalAmount,
          'delivery_fee' => $deliveryFee,
          'tax_amount' => $taxAmount,
        ]);

        // The ProcessOrderItems trait will automatically create OrderItems from the items_snapshot

        // Return the created order with its items
        return response()->json([
          'message' => 'Order created successfully',
          'order' => $order->load('items', 'status', 'company', 'address'),
        ], 201);
      });
    } catch (ValidationException $e) {
      return response()->json([
        'message' => 'Validation error',
        'errors' => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to create order',
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ], 500);
    }
  }
}
