<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
  /**
   * Show the login form
   *
   * @return \Illuminate\View\View
   */
  public function showLoginForm()
  {
    return view('auth.login');
  }

  /**
   * Handle the login request
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      return redirect()->intended('dashboard');
    }

    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
  }

  /**
   * Handle the logout request
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }

  /**
   * Show the client registration form
   *
   * @return \Illuminate\View\View
   */
  public function showRegistrationForm()
  {
    return view('auth.register', ['role' => 'client']);
  }

  /**
   * Show the manager registration form
   *
   * @return \Illuminate\View\View
   */
  public function showManagerRegistrationForm()
  {
    return view('auth.register', ['role' => 'manager']);
  }

  /**
   * Show the courier registration form
   *
   * @return \Illuminate\View\View
   */
  public function showCourierRegistrationForm()
  {
    return view('auth.register', ['role' => 'courier']);
  }

  /**
   * Handle a registration request for any role
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function register(Request $request)
  {
    $validation = [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'phone' => ['nullable', 'string', 'max:20'],
    ];

    // Add role-specific validation
    if ($request->role === 'manager' || $request->role === 'courier') {
      $validation['identification'] = ['required', 'string', 'max:255'];
      $validation['identification_type'] = ['required', 'string', 'max:50'];
    }

    $validated = $request->validate($validation);

    $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
      'phone' => $validated['phone'] ?? null,
      'identification' => $validated['identification'] ?? null,
      'identification_type' => $validated['identification_type'] ?? null,
      'email_verified_at' => now(),
    ]);

    // Determine which role to assign
    $role = $request->role ?? 'client';
    if (!in_array($role, ['client', 'manager', 'courier'])) {
      $role = 'client';
    }

    // Assign the role
    $user->assignRole($role);

    event(new Registered($user));

    Auth::login($user);

    return redirect()->route('dashboard')->with('status', "Your account has been registered as $role");
  }

  /**
   * Handle a manager registration request
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function registerManager(Request $request)
  {
    $request->merge(['role' => 'manager']);
    return $this->register($request);
  }

  /**
   * Handle a courier registration request
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function registerCourier(Request $request)
  {
    $request->merge(['role' => 'courier']);
    return $this->register($request);
  }
}
