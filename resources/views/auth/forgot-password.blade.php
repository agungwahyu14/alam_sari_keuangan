<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Keuangan Alam Sari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --brand-yellow: #F9C74F;
            --charcoal-gray: #343A40;
            --off-white: #F8F9FA;
            --navy-blue: #0A2463;
        }

        body {
            background-color: var(--off-white);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(52, 58, 64, 0.1);
        }

        .btn-primary {
            background-color: var(--navy-blue);
        }

        .btn-primary:hover {
            background-color: #061a4a;
        }

        .text-primary {
            color: var(--charcoal-gray);
        }

        .focus\:ring-brand:focus {
            --tw-ring-color: var(--brand-yellow);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0"
            style="background: linear-gradient(135deg, var(--navy-blue) 0%, var(--charcoal-gray) 100%); opacity: 0.05;">
        </div>
    </div>

    <div class="glass-effect rounded-2xl shadow-2xl w-full max-w-md p-8 relative z-10 animate-fade-in">
        <div class="text-center mb-8">
            <img src="{{ asset('AlamSari.png') }}" alt="Alam Sari Logo"
                class="w-20 h-20 mx-auto mb-4 rounded-full shadow-lg">
            <h1 class="text-3xl font-bold mb-2" style="color: var(--navy-blue);">Lupa Password?</h1>
            <p class="text-sm opacity-75 text-primary">
                Tidak masalah! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-primary">Email</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-envelope text-primary opacity-50"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="pl-10 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200 text-primary @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda">
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="w-full btn-primary text-white py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-200 font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>
                Kirim Link Reset Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm hover:opacity-75 inline-flex items-center"
                style="color: var(--navy-blue);">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke halaman login
            </a>
        </div>

        <p class="mt-6 text-center text-sm text-primary opacity-50">
            &copy; 2025 Alam Sari Properti. All rights reserved.
        </p>
    </div>

    @if (session('status'))
        <script>
            Swal.fire({
                title: 'Email Terkirim!',
                text: '{{ session('status') }}',
                icon: 'success',
                confirmButtonColor: '#0A2463',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>

</html>
