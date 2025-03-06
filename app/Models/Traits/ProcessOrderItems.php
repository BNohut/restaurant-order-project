<?php

namespace App\Models\Traits;

use App\Models\OrderItem;
use Illuminate\Support\Str;

trait ProcessOrderItems
{
  /**
   * Boot the trait.
   * Register model event hooks.
   */
  public static function bootProcessOrderItems()
  {
    static::created(function ($order) {
      $order->createOrderItems();
    });

    static::updated(function ($order) {
      if ($order->isDirty('items_snapshot')) {
        // If the items snapshot has changed, update the order items
        $order->syncOrderItems();
      }
    });
  }

  /**
   * Create order items from the snapshot.
   *
   * @return void
   */
  protected function createOrderItems()
  {
    if (empty($this->items_snapshot)) {
      return;
    }

    foreach ($this->items_snapshot as $item) {
      OrderItem::create([
        'uuid' => (string) Str::uuid(),
        'order_uuid' => $this->uuid,
        'product_uuid' => $item['product_uuid'],
        'quantity' => $item['quantity'],
        'unit_price' => $item['unit_price'],
        'subtotal' => $item['subtotal'],
        'special_instructions' => $item['special_instructions'] ?? null,
      ]);
    }
  }

  /**
   * Sync order items with the current snapshot.
   *
   * @return void
   */
  protected function syncOrderItems()
  {
    // Delete existing order items
    $this->items()->delete();

    // Create new order items from the updated snapshot
    $this->createOrderItems();
  }

  /**
   * Calculate order total from items
   *
   * @return float
   */
  public function calculateOrderTotal()
  {
    if (empty($this->items_snapshot)) {
      return 0;
    }

    return collect($this->items_snapshot)->sum('subtotal');
  }

  /**
   * Prepare an items snapshot from an array of product data
   *
   * @param array $items Array of items with product_uuid, quantity and optional special_instructions
   * @return array Processed items with calculated prices
   */
  public function prepareItemsSnapshot(array $items)
  {
    $snapshot = [];

    foreach ($items as $item) {
      // Validate required fields
      if (empty($item['product_uuid']) || empty($item['quantity'])) {
        continue;
      }

      // Find the product to get current pricing
      $product = app('App\Models\Product')->find($item['product_uuid']);
      if (!$product) {
        continue;
      }

      // Calculate subtotal
      $quantity = (int) $item['quantity'];
      $unitPrice = (float) $product->price;
      $subtotal = $quantity * $unitPrice;

      // Create snapshot item with complete data
      $snapshot[] = [
        'product_uuid' => $item['product_uuid'],
        'product_name' => $product->name,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'subtotal' => $subtotal,
        'special_instructions' => $item['special_instructions'] ?? null,
      ];
    }

    return $snapshot;
  }
}
