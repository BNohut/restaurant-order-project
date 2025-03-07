<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = session()->get('cart', [
            'items' => [],
            'company_uuid' => null,
            'total' => 0,
            'count' => 0
        ]);

        // Get company details if we have a company in the cart
        $restaurant = null;
        if ($cart['company_uuid']) {
            $restaurant = Company::with('address')->where('uuid', $cart['company_uuid'])->first();
        }

        return view('cart.index', compact('cart', 'restaurant'));
    }

    /**
     * Add a product to the cart
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'product_uuid' => 'required|exists:products,uuid',
            'quantity' => 'required|integer|min:1',
            'notes' => 'sometimes|nullable|string|max:255'
        ]);

        $product = Product::with('company')->findOrFail($request->product_uuid);

        // Initialize cart if it doesn't exist
        if (!session()->has('cart')) {
            session()->put('cart', [
                'items' => [],
                'company_uuid' => null,
                'total' => 0,
                'count' => 0
            ]);
        }

        $cart = session()->get('cart');

        // Check if the item is from the same restaurant
        if ($cart['company_uuid'] && $cart['company_uuid'] !== $product->company_uuid) {
            return response()->json([
                'success' => false,
                'message' => 'You can only order from one restaurant at a time. Would you like to clear your current cart?',
                'currentRestaurant' => $cart['company_uuid']
            ]);
        }

        // Set the company UUID if the cart is new
        if (!$cart['company_uuid']) {
            $cart['company_uuid'] = $product->company_uuid;
        }

        // Check if item already exists in the cart
        $cartItemKey = array_search($request->product_uuid, array_column($cart['items'], 'uuid'));

        if ($cartItemKey !== false) {
            // Update quantity if item already exists
            $cart['items'][$cartItemKey]['quantity'] += $request->quantity;
            $cart['items'][$cartItemKey]['subtotal'] = $cart['items'][$cartItemKey]['price'] * $cart['items'][$cartItemKey]['quantity'];
            if ($request->has('notes')) {
                $cart['items'][$cartItemKey]['notes'] = $request->notes;
            }
        } else {
            // Add new item to cart
            $cart['items'][] = [
                'uuid' => $product->uuid,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $request->quantity,
                'subtotal' => (float) $product->price * $request->quantity,
                'notes' => $request->notes ?? '',
                'category' => $product->category,
                'company_name' => $product->company->name
            ];
        }

        // Update cart totals
        $this->updateCartTotals($cart);

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'cart' => $cart
        ]);
    }

    /**
     * Update item quantity in the cart
     */
    public function updateItem(Request $request)
    {
        $request->validate([
            'product_uuid' => 'required|exists:products,uuid',
            'quantity' => 'required|integer|min:1',
            'notes' => 'sometimes|nullable|string|max:255'
        ]);

        $cart = session()->get('cart');
        $cartItemKey = array_search($request->product_uuid, array_column($cart['items'], 'uuid'));

        if ($cartItemKey !== false) {
            $cart['items'][$cartItemKey]['quantity'] = $request->quantity;
            $cart['items'][$cartItemKey]['subtotal'] = $cart['items'][$cartItemKey]['price'] * $request->quantity;

            if ($request->has('notes')) {
                $cart['items'][$cartItemKey]['notes'] = $request->notes;
            }

            $this->updateCartTotals($cart);

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'cart' => $cart
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem(Request $request)
    {
        $request->validate([
            'product_uuid' => 'required|exists:products,uuid'
        ]);

        $cart = session()->get('cart');
        $cartItemKey = array_search($request->product_uuid, array_column($cart['items'], 'uuid'));

        if ($cartItemKey !== false) {
            array_splice($cart['items'], $cartItemKey, 1);

            // If cart is empty, reset company_uuid
            if (empty($cart['items'])) {
                $cart['company_uuid'] = null;
            }

            $this->updateCartTotals($cart);

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart' => $cart
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }
    }

    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }

    /**
     * Proceed to checkout
     */
    public function checkout()
    {
        $cart = session()->get('cart');

        if (empty($cart) || empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $restaurant = Company::with('address')->where('uuid', $cart['company_uuid'])->first();

        // Check if restaurant still exists and is active
        if (!$restaurant || !$restaurant->is_active) {
            return redirect()->route('cart.index')->with('error', 'This restaurant is no longer available');
        }

        // Get user addresses
        $addresses = Auth::user()->addresses;

        return view('cart.checkout', compact('cart', 'restaurant', 'addresses'));
    }

    /**
     * Helper method to update cart totals
     */
    private function updateCartTotals(&$cart)
    {
        $cart['total'] = 0;
        $cart['count'] = 0;

        foreach ($cart['items'] as $item) {
            $cart['total'] += $item['subtotal'];
            $cart['count'] += $item['quantity'];
        }
    }
}
