<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Log;

class AuthViewController extends Controller
{
    // Метод для отображения страницы логина
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('products.index');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request, AuthController $authController)
    {
        $response = $authController->login($request);

        if ($response->status() === 200) {
            $responseData = $response->getData();

            if (isset($responseData->access_token)) {
                session(['jwt_token' => $responseData->access_token]);

                return redirect()->route('products.index')->with('success', 'Успешный вход');
            } else {
                return back()->withErrors(['login' => 'Ошибка авторизации: токен не получен.'])->withInput();
            }
        }

        return back()->withErrors(['login' => 'Неверный логин или пароль'])->withInput();
    }
}
