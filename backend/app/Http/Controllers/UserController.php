<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Seznam userjev (ne vračamo password)
        return response()->json(
            User::select('id', 'name', 'email', 'is_admin', 'created_at')->get()
        );
    }

    public function store(Request $request)
    {
        // Ustvari navadnega userja
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // varnost: ne dovolimo, da se is_admin nastavlja iz API-ja
            'is_admin' => false,
        ]);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => (bool) $user->is_admin,
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json(
            $user->only(['id','name','email','is_admin','created_at'])
        );
    }

    public function update(Request $request, User $user)
    {
        // BLOK: admina se ne sme spreminjati
        if ($user->is_admin) {
            return response()->json(['message' => 'Admin user cannot be modified.'], 403);
        }

        $data = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'email' => ['sometimes','email','max:255','unique:users,email,'.$user->id],
            'password' => ['sometimes','string','min:8'],
        ]);

        if (array_key_exists('password', $data)) {
        $data['password'] = Hash::make($data['password']);
        $user->update($data);

        return response()->json(['message' => 'Password updated'], 200);
        }

        // varnost: nikoli ne dovolimo is_admin prek update
        unset($data['is_admin']);

        $user->update($data);

        return response()->json(
            $user->only(['id','name','email','is_admin','created_at'])
        );
    }

    public function destroy(User $user)
    {
        // BLOK: admina se ne sme brisati
        if ($user->is_admin) {
            return response()->json(['message' => 'Admin user cannot be deleted.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Deleted']);
    }
}