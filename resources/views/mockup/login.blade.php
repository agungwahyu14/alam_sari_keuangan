<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Halaman Login & Autentikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-slate-900 to-indigo-900 p-8 text-white text-center">
                <div class="w-16 h-16 bg-amber-400 text-slate-900 rounded-2xl mx-auto flex items-center justify-center text-2xl font-bold mb-3 shadow-lg">
                    AS
                </div>
                <h2 class="text-2xl font-extrabold tracking-tight">Alam Sari Finance</h2>
                <p class="text-xs text-indigo-200 mt-1">Sistem Informasi Keuangan & Agen Properti</p>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                <div class="mb-6 text-center">
                    <h3 class="text-lg font-bold text-slate-800">Masuk ke Akun</h3>
                    <p class="text-xs text-slate-500">Silakan masukkan email dan kata sandi Anda</p>
                </div>

                <form onsubmit="event.preventDefault(); alert('Ini adalah tampilan mockup skripsi.');">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Pengguna</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" value="admin@alamsari.com" 
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition" placeholder="admin@alamsari.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" value="admin123" 
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs py-1">
                            <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                                <input type="checkbox" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span>Ingat Saya</span>
                            </label>
                            <a href="#" class="text-indigo-600 hover:underline font-semibold">Lupa Password?</a>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl text-sm shadow-md transition-all mt-2 flex items-center justify-center gap-2">
                            <span>Masuk ke Sistem</span>
                            <i class="fas fa-right-to-bracket text-xs"></i>
                        </button>
                    </div>
                </form>

                <!-- Demo accounts hint -->
                <div class="mt-6 pt-6 border-t border-slate-100 bg-slate-50 p-4 rounded-xl text-xs text-slate-600">
                    <p class="font-bold text-slate-700 mb-1">Akun Simulasi (Mockup Data):</p>
                    <p><span class="font-semibold text-slate-800">Admin:</span> admin@alamsari.com / admin123</p>
                    <p><span class="font-semibold text-slate-800">Agen:</span> agen@alamsari.com / agen123</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
