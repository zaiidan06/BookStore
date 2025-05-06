<?php
namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        // if (Auth::check()) {
        //     return redirect(Auth::user()->role === 'admin' ? '/admin' : '/');
        // }

        return view('auth.signin');
    }

    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 4 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'balance' => 1000000,
        ]);

        Auth::login($user);

        return redirect()->intended('/auth/signin')->with('toast', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 4 characters.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            Auth::login($user);

            if ($user->role === 'admin') {
                Auth::logout();
                Session::flush();
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/')->with('toast', 'Logout successfully!');
    }
}
