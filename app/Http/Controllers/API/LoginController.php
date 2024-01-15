<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized'
            ], 401);
        }

        $device = substr($request->userAgent() ?? '', 0, 255);
        $expires_at = $request->remember ? null : now()->addMinutes(config('session.lifetime'));

        return response()->json([
            'access_token' => $user->createToken($device, expiresAt: $expires_at)->plainTextToken
        ], 200);
    }
}
