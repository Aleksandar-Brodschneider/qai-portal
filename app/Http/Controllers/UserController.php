<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Ustvari NAVADNEGA uporabnika.
     * Ta endpoint bo uporabljen preko Postmana.
     * Admin pravic se tu NE da nastaviti.
     */
    public function store(Request $request)
    {
        // 1. Validacija vhodnih podatkov
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // 2. Ustvarimo uporabnika
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            // is_admin ostane false (default)
        ]);

        // 3. Vrnemo minimalen odgovor
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ], 201);
    }
}
