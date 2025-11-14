<?php

namespace App\Http\Controllers;

use App\Sistemas_IT\Models\BlockedEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de registro
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'unique:blocked_emails,email',
            ],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/',
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo no es válido.',
            'email.unique' => 'Este correo ya está registrado o bloqueado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede exceder 16 caracteres.',
            'password.regex' => 'La contraseña debe incluir al menos un número y un símbolo.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => User::STATUS_PENDING,
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Tu solicitud de registro fue enviada exitosamente. Un administrador revisará tu cuenta y te notificará cuando esté aprobada. Puedes usar cualquier correo electrónico para registrarte.');
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesar login de usuario
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (BlockedEmail::where('email', $credentials['email'])->exists()) {
            return back()->withErrors([
                'email' => 'Este correo ha sido bloqueado. Contacta al administrador si crees que es un error.',
            ])->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status === User::STATUS_PENDING) {
            return back()->withErrors([
                'email' => 'Tu cuenta está en revisión. Un administrador debe aprobar tu acceso.',
            ])->onlyInput('email');
        }

        if ($user && $user->status === User::STATUS_REJECTED) {
            return back()->withErrors([
                'email' => 'Tu solicitud de acceso fue rechazada. Contacta al administrador si necesitas más información.',
            ])->onlyInput('email');
        }

        if ($user && Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('welcome')->with('success', '¡Bienvenido de vuelta!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Sesión cerrada correctamente.');
    }
}
  