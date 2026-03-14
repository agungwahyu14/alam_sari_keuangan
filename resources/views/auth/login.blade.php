<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Keuangan Mancraft</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Define custom colors for Tailwind */
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
            /* Darker navy */
        }

        .text-brand {
            color: var(--brand-yellow);
        }

        .text-primary {
            color: var(--charcoal-gray);
        }

        .border-primary {
            border-color: var(--charcoal-gray);
        }

        .focus-ring-brand:focus {
            ring-color: var(--brand-yellow);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .status-message {
            color: #059669;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: rgba(5, 150, 105, 0.1);
            border-radius: 0.375rem;
        }

        /* Logo styling for login page */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-image {
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .logo-container:hover .logo-image {
            transform: scale(1.05);
        }

        /* Responsive logo sizing */
        @media (max-width: 640px) {
            .logo-image {
                width: 4rem;
                height: 4rem;
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">
    <div class="glass-effect p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <div class="logo-container mb-4">
                <img src="{{ asset('Logo_Mancraft.jpg') }}" alt="Mancraft Logo"
                    class="logo-image w-20 h-20 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-primary">Mancraft Finance</h2>
            <p class="text-primary opacity-75">Sistem Manajemen Keuangan</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-primary">Email</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-user text-primary opacity-50"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="pl-10 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200 text-primary @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email">
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-primary">Password</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-lock text-primary opacity-50"></i>
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="pl-10 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent transition duration-200 text-primary @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password">
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-brand focus:ring-brand border-primary rounded">
                    <label for="remember" class="ml-2 block text-sm text-primary">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm hover:opacity-75"
                        style="color: var(--navy-blue);">Lupa password?</a>
                @endif
            </div>

            <button type="submit"
                class="w-full btn-primary text-white py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-200 font-semibold">
                Masuk
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-primary opacity-50">
            &copy; 2025 Mancraft Barbershop. All rights reserved.
        </p>
    </div>

    <!-- Chatbot Widget -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-chatbot-widget />
</body>

</html>
