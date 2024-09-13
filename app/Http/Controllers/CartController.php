<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            // Аутентификация пользователя
            $user = $this->jwtAuth->parseToken()->authenticate();
            if (!$user) {
                Log::info('Unauthorized request.');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Проверка существования продукта
            $product = $this->findProduct($request->input('product_id'));
            if (!$product) {
                Log::info('Product not found.', ['product_id' => $request->input('product_id')]);
                return response()->json(['error' => 'Product not found.'], 404);
            }

            // Проверка, является ли продукт специальным
            if ($product->is_special && $user->has_ordered_special_product) {
                Log::info('User has already ordered a special product.');
                return response()->json(['error' => 'Вы уже заказывали специальный продукт, он доступен только 1 раз.'], 403);
            }

            // Добавление продукта в корзину
            $user->cart()->create(['product_id' => $product->id]);

            // Отметка, что пользователь заказал специальный продукт
            if ($product->is_special) {
                $user->has_ordered_special_product = true;
                $user->save();
            }

            return response()->json(['message' => 'Продукт добавлен в корзину.'], 200);
        } catch (\Exception $e) {
            Log::error('Error adding product to cart:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        // Получаем текущего пользователя
        $user = $this->jwtAuth->parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Валидация входных данных
        $request->validate([
            'delivery_address' => 'required|string|max:255',
            'city' => 'required|string',
            'street' => 'required|string',
            'house_number' => 'required|string',
            'apartment_number' => 'nullable|string',
            'entrance' => 'nullable|string',
            'postal_code' => 'required|string',
        ]);

        // Получаем элементы корзины пользователя
        $cartItems = $user->cart;

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        // Проверяем, есть ли в корзине секретные продукты
        $secretProductIds = $cartItems->filter(function ($item) {
            return $item->product->is_secret;
        })->pluck('product_id')->toArray();

        // Если в корзине есть секретные продукты, проверяем, заказывал ли пользователь уже секретный продукт
        if (!empty($secretProductIds)) {
            if ($user->has_ordered_secret_product) {
                return response()->json(['error' => 'You have already ordered a secret product.'], 403);
            }
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

        // Если в корзине были секретные продукты, обновляем статус пользователя
        if (!empty($secretProductIds)) {
            $user->has_ordered_secret_product = true;
            $user->save();
        }

        return response()->json(['message' => 'Ваш заказ создан, ожидайте обратной связи.'], 200);
    }

}
