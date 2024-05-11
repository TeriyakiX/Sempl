<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '0')->get();
        return UserResource::collection($users);
    }

    public function create(UserRequest $request)
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = Hash::make($request->password);
        $user->role = '0';
        $user->save();

        return new UserResource($user);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        $user->password = Hash::make($request->password);
        $user->save();

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
}
