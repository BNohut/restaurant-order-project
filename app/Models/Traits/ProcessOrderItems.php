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
      if (empty($order->items_snapshot)) {
        return;
      }

      $items = json_decode($order->items_snapshot, true);
      if (!is_array($items)) {
        return;
      }

      foreach ($items as $item) {
        OrderItem::create([
          'order_uuid' => $order->uuid,
          'product_uuid' => $item['product_uuid'] ?? $item['uuid'] ?? null, // Support both formats
          'quantity' => $item['quantity'],
          'unit_price' => $item['unit_price'] ?? $item['price'] ?? 0, // Support both formats
          'subtotal' => $item['subtotal'],
          'special_instructions' => $item['special_instructions'] ?? $item['notes'] ?? null, // Support both formats
        ]);
      }
    });

    static::updated(function ($order) {
      if ($order->isDirty('items_snapshot')) {
        // If the items snapshot has changed, update the order items
        $order->syncOrderItems();
      }
    });
  }

  /**
   * Sync order items with the current items snapshot.
   *
   * @return void
   */
  protected function syncOrderItems()
  {
    // Delete existing order items
    $this->items()->delete();

    // Create new order items from the updated snapshot
    $items = json_decode($this->items_snapshot, true);
    if (is_array($items)) {
      foreach ($items as $item) {
        $this->items()->create([
          'product_uuid' => $item['product_uuid'] ?? $item['uuid'] ?? null,
          'quantity' => $item['quantity'],
          'unit_price' => $item['unit_price'] ?? $item['price'] ?? 0,
          'subtotal' => $item['subtotal'],
          'special_instructions' => $item['special_instructions'] ?? $item['notes'] ?? null,
        ]);
      }
    }
  }

  /**
   * Calculate the order total based on items.
   *
   * @return float
   */
  public function calculateOrderTotal()
  {
    $subtotal = 0;
    $items = json_decode($this->items_snapshot, true) ?? [];

    foreach ($items as $item) {
      $subtotal += $item['subtotal'] ?? 0;
    }

    // Add delivery fee and tax
    return $subtotal + $this->delivery_fee + $this->tax_amount;
  }

  /**
   * Prepare items snapshot from array of items.
   *
   * @param array $items
   * @return string
   */
  public function prepareItemsSnapshot(array $items)
  {
    // Ensure all required fields are present
    $formattedItems = [];

    foreach ($items as $item) {
      $formattedItems[] = [
        'product_uuid' => $item['product_uuid'] ?? $item['uuid'] ?? null,
        'quantity' => $item['quantity'] ?? 1,
        'unit_price' => $item['unit_price'] ?? $item['price'] ?? 0,
        'subtotal' => $item['subtotal'] ?? ($item['unit_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1),
        'special_instructions' => $item['special_instructions'] ?? $item['notes'] ?? null,
        'name' => $item['name'] ?? 'Unknown Item',
        'category' => $item['category'] ?? null,
      ];
    }

    return json_encode($formattedItems);
  }
}
