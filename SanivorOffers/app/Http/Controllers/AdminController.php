<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function getAllUsers(): View
    {
        $users = User::orderBy('id', 'DESC')->paginate(20);
        $adminCount = User::where('role', 'admin')->count();

        return view('admin.index', compact('users', 'adminCount'));
    }

    public function edit(User $user): View
    {
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:seller,admin'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('user.index')->withErrors([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('user.index')->withErrors([
                'user' => 'Cannot delete the last admin account.',
            ]);
        }

        $user->delete();

        return redirect()->route('user.index')->with('status', 'User deleted successfully.');
    }
}
