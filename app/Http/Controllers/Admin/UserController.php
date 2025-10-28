<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return inertia('Admin/Users');
    }

    public function list(Request $request)
    {
        return response()->json(
            User::select('id', 'name', 'email', 'is_admin')
                ->orderBy('id')
                ->get()
        );
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);

        $currentUser = $request->user();

        // Prevent the current admin from removing their own admin rights
        if ($currentUser && $currentUser->id === $user->id && $validated['is_admin'] === false) {
            return response()->json(['message' => 'Cannot remove your own admin rights'], 403);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated']);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = $request->user();

        // Prevent self-deletion
        if ($currentUser && $currentUser->id === $user->id) {
            return response()->json(['message' => 'Cannot delete yourself'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
