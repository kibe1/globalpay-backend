<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        try {
            // Create a new user
            $user = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);

            return response()->json(['message' => 'Registration successful!'], 201);
        } catch (QueryException $e) {
            // Handle any query exceptions (e.g., database errors)
            return response()->json(['message' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Attempt to log the user in
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user, // You can return the entire user data if needed
            ], 200);
        }

        return response()->json(['message' => 'Invalid username or password'], 401);
    }

    // New method to get the authenticated user's data (username)
    public function getUser(Request $request)
    {
        // Use the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            return response()->json([
                'username' => $user->username, // Return the username
            ]);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
}

