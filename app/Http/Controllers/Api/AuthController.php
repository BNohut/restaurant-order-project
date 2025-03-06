<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  /**
   * Handle user login
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ], 422);
    }

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid login credentials'
      ], 401);
    }

    $user = User::where('email', $request->email)->first();
    $token = $user->createToken('authToken')->plainTextToken;

    // Include user's roles and permissions in the response
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');

    return response()->json([
      'success' => true,
      'message' => 'Login successful',
      'data' => [
        'user' => [
          'uuid' => $user->uuid,
          'name' => $user->name,
          'email' => $user->email,
        ],
        'roles' => $roles,
        'permissions' => $permissions,
        'token' => $token,
      ]
    ], 200);
  }

  /**
   * Handle user logout
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'success' => true,
      'message' => 'Successfully logged out'
    ]);
  }

  /**
   * Get authenticated user details
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function me(Request $request)
  {
    $user = $request->user();

    // Include user's roles and permissions in the response
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');

    return response()->json([
      'success' => true,
      'data' => [
        'user' => [
          'uuid' => $user->uuid,
          'name' => $user->name,
          'email' => $user->email,
        ],
        'roles' => $roles,
        'permissions' => $permissions,
      ]
    ]);
  }
}
