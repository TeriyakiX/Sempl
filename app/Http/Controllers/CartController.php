<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    public function addToCart(Request $request)
    {
        $user = $this->jwtAuth->parseToken()->authenticate();
        $product = $this->findProduct($request->input('product_id'));

        $this->validateCart($user, $product);

        $user->cart()->create(['product_id' => $product->id]);

        return response()->json(['message' => 'Продукт добавлен в корзину.'], 200);
    }

    public function removeFromCart($id)
    {
        $user = $this->jwtAuth->parseToken()->authenticate();
        $cartItem = $this->findCartItem($user, $id);

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart successfully.'], 200);
    }

    public function viewCart()
    {
        $user = $this->jwtAuth->parseToken()->authenticate();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Ваша корзина пуста.'], 200);
        }

        $result = $cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->product->id,
                'name' => $cartItem->product->name,
                'description' => $cartItem->product->description,
            ];
        });

        return response()->json($result, 200);
    }

    private function findProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            abort(404, 'Product not found.');
        }

        return $product;
    }

    private function validateCart($user, $product)
    {
        if ($user->cart()->where('product_id', $product->id)->exists()) {
            abort(400, 'Product is already in the cart.');
        }

        if ($user->cart()->count() >= 10) {
            abort(400, 'Cart limit reached. Please remove an item before adding a new one.');
        }
    }

    private function findCartItem($user, $cartItemId)
    {
        $cartItem = $user->cart()->find($cartItemId);

        if (!$cartItem) {
            abort(404, 'Cart item not found.');
        }

        return $cartItem;
    }

    public function checkout(Request $request)
    {
        $user = $this->jwtAuth->parseToken()->authenticate();

        $request->validate([
            'delivery_address' => 'required|string|max:255',
        ]);

        $cartItems = $user->cart;

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        // Создаем заказ
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'delivery_address' => $request->input('delivery_address'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'house_number' => $request->input('house_number'),
            'apartment_number' => $request->input('apartment_number'),
            'entrance' => $request->input('entrance'),
            'postal_code' => $request->input('postal_code'),
        ]);

        // Получаем идентификаторы всех товаров из корзины
        $productIds = $cartItems->pluck('product_id')->toArray();

        // Связываем товары с заказом
        $purchase->products()->attach($productIds);

        // Очищаем корзину пользователя
        $user->cart()->delete();

        // Обновляем статусы товаров в заказе на 'ожидает'
        Product::whereIn('id', $productIds)->update(['expected' => true]);

        return response()->json(['message' => 'Ваш заказ создан, ожидайте обратной связи.'], 200);
    }
}
