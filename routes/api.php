<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResearchController;
use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Vse rute v tej datoteki imajo avtomatski prefix /api
| Primer: Route::get('/test') => GET /api/test
*/

// Testna ruta (hitro preverjanje, ali API sploh dela)
Route::get('/test', function () {
    return response()->json(['ok' => true]);
});

// CRUD API za research (index, store, show, update, destroy)
Route::apiResource('research', ResearchController::class);

/*
|--------------------------------------------------------------------------
| Admin bootstrap (DEV only)
|--------------------------------------------------------------------------
| Namen: ustvariti admin uporabnika preko Postmana.
| Varnost:
| - dela samo v local okolju
| - zahteva header X-Bootstrap-Secret, ki mora biti enak APP_KEY
|
| URL: POST /api/admin/bootstrap
| Headers:
| - Content-Type: application/json
| - X-Bootstrap-Secret: <APP_KEY iz .env>
| Body (JSON):
| {
|   "name": "admin",
|   "email": "admin@qai-portal.test",
|   "password": "Admin12345"
| }
*/
Route::post('/admin/bootstrap', function (Request $request) {

    // Dovolimo samo v local okolju (da tega ne “pozabiš” v produkciji)
    if (! app()->environment('local')) {
        return response()->json(['message' => 'Disabled'], 403);
    }

    // Minimalna zaščita: zahteva APP_KEY v headerju
    $secret = $request->header('X-Bootstrap-Secret');
    if (! $secret || $secret !== config('app.key')) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Validacija (JSON body)
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    // Ustvarimo admin uporabnika (geslo je HASHirano)
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'is_admin' => true,
    ]);

    // Minimalen odgovor (da ne vračamo nepotrebnih podatkov)
    return response()->json([
        'id' => $user->id,
        'email' => $user->email,
        'is_admin' => (bool) $user->is_admin,
    ], 201);

});
// Ustvarjanje navadnih uporabnikov
Route::post('/users', [UserController::class, 'store']);