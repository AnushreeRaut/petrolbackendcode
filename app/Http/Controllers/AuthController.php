<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 2, // Default role set to Employee (ID 2)
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

// Login Method
// public function login(Request $request)
// {
//     // Validate the incoming request
//     $validator = Validator::make($request->all(), [
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     // Check if user exists
//     $user = User::where('email', $request->email)->first();

//     if (!$user || !Hash::check($request->password, $user->password)) {
//         throw ValidationException::withMessages([
//             'email' => ['The provided credentials are incorrect.'],
//         ]);
//     }

//     // Generate the token (This is the key part of Sanctum)
//     $token = $user->createToken('YourAppName')->plainTextToken;

//     // Return the token to the client
//     return response()->json(['token' => $token]);
// }

  // Login Method
  public function login(Request $request)
  {
      // Validate the incoming request
      $validator = Validator::make($request->all(), [
          'email' => 'required|email',
          'password' => 'required',
      ]);

      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 422);
      }

      // Check if user exists
      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
          throw ValidationException::withMessages([
              'email' => ['The provided credentials are incorrect.'],
          ]);
      }

      // Generate the token (This is the key part of Sanctum)
      $token = $user->createToken('YourAppName')->plainTextToken;

      // Return the token to the client
      return response()->json(['token' => $token, 'message' => 'Login successful']);
  }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }


      // Handle forgot password request
      public function forgotPassword(Request $request)
      {
          $validated = $request->validate([
              'email' => 'required|email|exists:users,email',
          ]);

          // Send a password reset token (we will skip email sending for simplicity)
          return response()->json(['message' => 'Password reset instructions sent to your email.']);
      }

      // Update the password
      public function updatePassword(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users,email',
              'new_password' => 'required|string|min:8|confirmed',
          ]);

          $user = User::where('email', $request->email)->first();
          $user->password = Hash::make($request->new_password);
          $user->save();

          return response()->json(['message' => 'Password updated successfully.']);
      }

}
