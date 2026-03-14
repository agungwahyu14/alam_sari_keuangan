<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the password reset link request.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi input email
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Handle response
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.',
                'alert_type' => 'success'
            ]);
        }

        // Jika gagal
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
