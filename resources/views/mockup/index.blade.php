<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup Antarmuka Skripsi - Alam Sari Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand-yellow: #F9C74F;
            --charcoal-gray: #343A40;
            --navy-blue: #0A2463;
        }
        body {
            background-color: #F8F9FA;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 flex flex-col">

    <!-- Header Banner -->
    <header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-10 px-6 shadow-xl border-b border-indigo-500/20">
        <div class="max-w-7xl mx-mx auto text-center">
            <div class="inline-flex items-center gap-2 bg-indigo-500/20 border border-indigo-400/30 px-4 py-1.5 rounded-full text-indigo-300 text-sm font-medium mb-4">
                <span>Dokumentasi Skripsi Subbab 4.2 & 4.4</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-3">
                Hub Mockup Antarmuka Sistem Keuangan
            </h1>
            <p class="text-slate-300 text-base md:text-lg max-w-3xl mx-auto leading-relaxed">
                Pilih salah satu halaman di bawah ini untuk melihat pratinjau rancangan wireframe dan implementasi antarmuka lengkap dengan data simulasi (mockup data).
            </p>
        </div>
    </header>

    <!-- Content Grid -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($mockupPages as $page)
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/80 hover:shadow-2xl hover:border-indigo-500/50 transition-all duration-300 flex flex-col overflow-hidden group">
                <div class="p-6 flex-grow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ $page['badge'] }}
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-400">
                            Subbab {{ $page['id'] }}
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">
                        {{ $page['title'] }}
                    </h2>

                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        {{ $page['description'] }}
                    </p>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 mt-auto">
                    <a href="{{ $page['route'] }}" 
                       class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-5 rounded-xl transition-all duration-200 shadow-md shadow-indigo-200 hover:shadow-indigo-300">
                        <span>Buka Mockup Halaman</span>
                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-sm text-slate-500">
        <p>Sistem Informasi Keuangan Alam Sari - Antarmuka Mockup Skripsi &copy; {{ date('Y') }}</p>
    </footer>

</body>
</html>
