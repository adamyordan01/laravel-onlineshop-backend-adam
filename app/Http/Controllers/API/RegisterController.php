<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|min:8',
            'phone' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'email.max' => 'Email maksimal 255 karakter',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa string',
            'password.confirmed' => 'Password tidak sama dengan konfirmasi password',
            'password.min' => 'Password minimal 8 karakter',
            'password_confirmation.required' => 'Konfirmasi password harus diisi',
            'password_confirmation.string' => 'Konfirmasi password harus berupa string',
            'password_confirmation.min' => 'Konfirmasi password minimal 8 karakter',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.string' => 'Nomor telepon harus berupa string',
            'phone.max' => 'Nomor telepon maksimal 15 karakter',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken
        ], 200);
    }
}
