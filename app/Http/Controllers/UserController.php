<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\Status\AwaitingReviewOrdersResource;
use App\Http\Resources\Status\CompletedOrdersResource;
use App\Http\Resources\Status\PendingOrdersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\JWTAuth;


class UserController extends Controller
{

    protected $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }
    public function index()
    {
        $users = User::where('role', '0')->get();
        return UserResource::collection($users);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user = User::create($data);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function createAdmin(UserRequest $request)
    {
        $admin = new User();
        $admin->fill($request->validated());
        $admin->password = Hash::make($request->password);
        $admin->role = '1';
        $admin->save();

        return response()->json($admin, 201);
    }


    public function updateProfile(UpdateUserRequest $request)
    {
        // Получаем текущего пользователя через JWTAuth
        $user = $this->jwtAuth->parseToken()->authenticate();

        // Обновление полей профиля
        $user->fill($request->validated());

        // Обработка загрузки новой фотографии, если она есть
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $path = $photo->store('profile_photos', 'public'); // сохраняем фото в папку 'storage/app/public/profile_photos'

            // Удаляем старую фотографию, если она существует
            if ($user->profile_photo && $user->profile_photo != 'profile_photos/default.png') {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Сохраняем новый путь к фотографии в базе данных
            $user->profile_photo = $path;
        }

        $user->save();

        return new UserResource($user);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('photos', 'public');
            $url = Storage::url($path);

            return response()->json(['url' => $url], 200);
        }

        return response()->json(['error' => 'No photo uploaded'], 400);
    }

    public function UserOrders(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userOrders = $user->purchases()->with('products')->get();

        $pendingOrders = $userOrders->filter(function ($order) {
            return $order->isPending();
        });

        $awaitingReviewOrders = $userOrders->filter(function ($order) {
            return $order->isAwaitingReview();
        });

        $completedOrders = $userOrders->filter(function ($order) {
            return $order->isCompleted();
        });

        return response()->json([
            'pending_orders' => PendingOrdersResource::collection($pendingOrders),
            'awaiting_review_orders' => AwaitingReviewOrdersResource::collection($awaitingReviewOrders),
            'completed_orders' => CompletedOrdersResource::collection($completedOrders),
            'message' => 'User orders loaded successfully.'
        ], 200);
    }
    public function userOrderDetail(Request $request, $orderId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = $user->purchases()->with(['products', 'user'])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return new OrderDetailResource($order);
    }



}
