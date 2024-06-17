<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', function (Request $request) {
    return \App\Models\User::all();
});

Route::post('/login', function (Request $request) {
    $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required'],
        ]
    );
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json([
        "token" => $user->createToken($request->device_name)->plainTextToken
    ]);
});
