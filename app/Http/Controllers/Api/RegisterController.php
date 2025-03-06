<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
  /**
   * Handle user registration
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'phone' => 'nullable|string|max:20',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ], 422);
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'phone' => $request->phone,
      'email_verified_at' => now(), // Auto-verify for now, can implement email verification later
    ]);

    // Assign the client role to newly registered users
    $user->assignRole('client');

    // Create a token for the user
    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json([
      'success' => true,
      'message' => 'User registered successfully',
      'data' => [
        'user' => [
          'uuid' => $user->uuid,
          'name' => $user->name,
          'email' => $user->email,
        ],
        'roles' => ['client'],
        'token' => $token,
      ]
    ], 201);
  }
}
