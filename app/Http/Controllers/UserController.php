<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
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

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($data);

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
        try {
            $user = $this->jwtAuth->parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not authenticate user'], 401);
        }

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        } elseif (empty($data['profile_photo'])) {
            $data['profile_photo'] = 'profile_photos/default.png';
        }

        $user->update($data);

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
}
