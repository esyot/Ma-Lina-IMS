<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        return inertia('Account', [
            'user' => Auth::user(),
        ]);
    }

    public function updateDetails(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('account.index')->with('success', 'Account details updated successfully.');
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|min:8',
        ]);

        $user = Auth::user();

        if (!\Hash::check($request->currentPassword, $user->password))
        {
            return back()->withErrors(['currentPassword' => 'Current password is incorrect.']);
        }

        if ($request->newPassword !== $request->confirmPassword)
        {
            return back()->withErrors(['confirmPassword' => 'New password and confirmation do not match.']);
        }

        $user->password = bcrypt($request->newPassword);
        $user->save();

        return redirect()->route('account.index')->with('success', 'Password updated successfully.');
    }
}
