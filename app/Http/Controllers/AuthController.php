<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\DaDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use MoveMoveIo\DaData\Facades\DaData;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{

    protected $dadataService;

    public function __construct(DaDataService $dadataService)
    {
        $this->dadataService = $dadataService;
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => ''], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = JWTAuth::user();

        if (!$user || $user->role !== 1) {
            return response()->json(['error' => 'Вы не администратор'], 401);
        }

        return response()->json(['access_token' => $token], 200);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^\d{11}$/',
        ], [
            'phone.regex' => 'The phone must be 11 digits long.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $phone = $request->input('phone');

        $user = User::where('phone', $phone)->first();

        if ($user && $user->last_code_request_at && Carbon::parse($user->last_code_request_at)->addMinute()->isFuture()) {
            return response()->json(['error' => 'Please wait before requesting a new code.'], 429);
        }

        $code = rand(10000, 99999);

        if (!$user) {
            $user = User::create([
                'phone' => $phone,
                'verification_code' => $code,
                'code_sent_at' => Carbon::now(),
                'verification_attempts' => 0,
                'last_code_request_at' => Carbon::now(),
                'email' => Str::random(10).'@example.com',
                'login' => Str::random(8),

            ]);
        } else {
            $user->verification_code = $code;
            $user->code_sent_at = Carbon::now();
            $user->verification_attempts = 0;
            $user->last_code_request_at = Carbon::now();
            $user->save();
        }

        Log::info('Created user for SMS:', ['user' => $user]);

        $systemCode = config('app.system_code');

        if (!$systemCode) {
            return response()->json(['error' => 'System code not configured.'], 500);
        }

        try {
            $client = new Client();
            $apiKey = config('services.smsru.api_key');
            $response = $client->request('GET', 'https://sms.ru/sms/send', [
                'query' => [
                    'api_id' => $apiKey,
                    'to' => $phone,
                    'msg' => "Ваш код подтверждения для регистрации: $code",
                    'json' => 1
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['status'] === 'OK') {
                return response()->json(['message' => 'Verification code sent.'], 200);
            } else {
                return response()->json(['error' => 'Failed to send verification code.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send verification code.'], 500);
        }
    }
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^\d{11}$/',
            'verification_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid verification code or phone number.'], 400);
        }

        $phone = $request->input('phone');
        $code = (int) $request->input('verification_code');

        $user = User::where('phone', $phone)->first();

        if (!$user || $user->verification_attempts >= 5 ||
            ($user->verification_code !== $code &&
                $code !== (int)config('app.system_code'))) {
            return response()->json(['error' => 'Invalid verification code or phone number.'], 400);
        }

        if (Carbon::parse($user->code_sent_at)->addMinutes(5)->isPast()) {
            return response()->json(['error' => 'Verification code expired.'], 400);
        }

        $user->verification_attempts = 0;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Verification code is valid.',
            'access_token' => $token
        ], 200);
    }
    public function completeRegistration(UserRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Invalid token. Please reverify your code.'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Формирование полного адреса без стандартизации через DaData
        $fullAddress = implode(' ', array_filter([
            $request->input('city'),
            $request->input('street'),
            $request->input('house_number'),
            $request->input('apartment_number'),
            $request->input('entrance'),
            $request->input('postal_code'),
        ]));

        // Сохраняем оригинальный адрес пользователя
        $user->fill($request->only([
            'login', 'first_name', 'last_name', 'gender', 'birthdate', 'app_name',
            'email', 'people_living_with_id', 'has_children_id', 'pets_id',
            'average_monthly_income_id', 'percentage_spent_on_cosmetics_id',
            'vk_profile', 'telegram_profile', 'city', 'street', 'house_number',
            'apartment_number', 'entrance', 'postal_code'
        ]));

        // Обработка значений accept_policy и want_advertising
        $user->accept_policy = $request->input('accept_policy', false) ? true : false;
        $user->want_advertising = $request->input('want_advertising', false) ? true : false;

        $user->full_address = $fullAddress;

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $path = $photo->store('profile_photos', 'public');

            if ($user->profile_photo && !filter_var($user->profile_photo, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = $path;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->verification_code = null;
        $user->code_sent_at = null;

        // Устанавливаем флаг завершения регистрации
        $user->is_registration_completed = true;

        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'message' => 'Registration completed successfully. User logged in.',
        ], 200);
    }

    public function sendVerificationCodeAuth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $code = rand(10000, 99999);
        $phone = $request->input('phone');

        $user = User::where('phone', $phone)->first();

        if ($user) {
            $user->verification_code = $code;
            $user->code_sent_at = Carbon::now();
            $user->save();
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $client = new Client();
            $apiKey = config('services.smsru.api_key');
            $response = $client->request('GET', 'https://sms.ru/sms/send', [
                'query' => [
                    'api_id' => $apiKey,
                    'to' => $phone,
                    'msg' => "Ваш код подтверждения для авторизации: $code",
                    'json' => 1
                ]
            ]);

            $systemCode = config('app.system_code');

            if (!$systemCode) {
                return response()->json(['error' => 'System code not configured.'], 500);
            }

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['status'] === 'OK') {
                return response()->json(['message' => 'Verification code sent.'], 200);
            } else {
                return response()->json(['error' => 'Failed to send verification code.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send verification code.'], 500);
        }
    }

    public function loginWithVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'verification_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid verification code or phone number.'], 400);
        }

        $phone = $request->input('phone');
        $code = $request->input('verification_code');

        $systemCode = config('app.system_code');

        if ($code !== $systemCode) {
            $user = User::where('phone', $phone)
                ->where('verification_code', $code)
                ->first();

            if (!$user) {
                return response()->json(['error' => 'Invalid verification code or phone number.'], 400);
            }
        } else {
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found.'], 400);
            }
        }

        if (Carbon::parse($user->code_sent_at)->addMinutes(5)->isPast()) {
            return response()->json(['error' => 'Verification code expired.'], 400);
        }

        $user->verification_code = null;
        $user->code_sent_at = null;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $token,
            'user' => new UserResource($user),
            'message' => 'User logged in successfully.'
        ], 200);
    }

    public function getTokenTTL()
    {
        $ttl = JWTAuth::factory()->getTTL();
        return response()->json(['token_ttl' => $ttl], 200);
    }

    public function getCurrentUser(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => new UserResource($user),
        ], 200);
    }
}
