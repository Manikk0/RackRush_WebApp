<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Customer authentication (login/register/logout).
class AuthController extends Controller
{
    // Validate credentials and sign user in.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $mergedCart = CartService::mergeSessionIntoUserAndStore(
                (int) Auth::id(),
                $request->session()->get('cart', [])
            );
            $request->session()->put('cart', $mergedCart);

            return redirect()->intended(route('index'))->with('success', 'Boli ste úspešne prihlásený.');
        }

        return back()->withErrors([
            'email' => 'Zadané údaje sa nezhodujú s našimi záznamami.',
        ])->onlyInput('email');
    }

    // Validate input, create account, then auto-login.
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $mergedCart = CartService::mergeSessionIntoUserAndStore(
            (int) $user->id,
            $request->session()->get('cart', [])
        );
        $request->session()->put('cart', $mergedCart);

        return redirect(route('index'))->with('success', 'Váš účet bol úspešne vytvorený.');
    }

    // Log current user out and reset session.
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('logout_success', true);
    }
}
