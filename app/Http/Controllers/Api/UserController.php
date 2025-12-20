<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of admin users.
     * GET /api/users (admin only)
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->paginate(15);

        return response()->json($users);
    }

    /**
     * Display a specific user.
     * GET /api/users/{user}
     */
    public function show(User $user)
    {
        return response()->json([
            'data' => $user->only('id', 'name', 'email', 'role', 'created_at', 'updated_at')
        ]);
    }

    /**
     * Store a newly created user.
     * POST /api/users
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,editor',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        return response()->json([
            'data' => $user->only('id', 'name', 'email', 'role', 'created_at')
        ], 201);
    }

    /**
     * Update a user.
     * PUT /api/users/{user}
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,editor',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'data' => $user->only('id', 'name', 'email', 'role', 'created_at', 'updated_at')
        ]);
    }

    /**
     * Delete a user.
     * DELETE /api/users/{user}
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
