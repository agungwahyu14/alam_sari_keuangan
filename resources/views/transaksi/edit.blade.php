@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Edit Transaksi</h1>
        <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
            <form method="POST" action="{{ route('transaksi.update', $transaksi->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Jenis Transaksi</label>
                    <div class="flex gap-6">
                        <label class="flex items-center">
                            <input type="radio" name="type" value="income"
                                {{ old('type', $transaksi->type) == 'income' ? 'checked' : '' }} class="mr-2"
                                id="radio-income">
                            <span>Pemasukan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="expense"
                                {{ old('type', $transaksi->type) == 'expense' ? 'checked' : '' }} class="mr-2"
                                id="radio-expense">
                            <span>Pengeluaran</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="transaction_date" class="block font-semibold mb-2">Tanggal</label>
                    <input type="date" name="transaction_date" id="transaction_date"
                        class="w-full border rounded px-3 py-2 @error('transaction_date') border-red-500 @enderror"
                        value="{{ old('transaction_date', $transaksi->transaction_date->format('Y-m-d')) }}" required>
                    @error('transaction_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="amount" class="block font-semibold mb-2">Jumlah (Rp)</label>
                    <input type="number" name="amount" id="amount"
                        class="w-full border rounded px-3 py-2 @error('amount') border-red-500 @enderror" min="1"
                        value="{{ old('amount', $transaksi->amount) }}" required>
                    <small class="text-gray-500 text-sm">Akan terisi otomatis saat memilih layanan</small>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Income Fields -->
                <div id="income-fields" style="{{ old('type', $transaksi->type) == 'income' ? '' : 'display:none;' }}">
                    <div class="mb-4">
                        <label for="service_id" class="block font-semibold mb-2">Layanan</label>
                        <select name="service_id" id="service_id"
                            class="w-full border rounded px-3 py-2 @error('service_id') border-red-500 @enderror">
                            <option value="">Pilih Layanan</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ old('service_id', $transaksi->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="user_id" class="block font-semibold mb-2">Karyawan</label>
                        <select name="user_id" id="user_id"
                            class="w-full border rounded px-3 py-2 @error('user_id') border-red-500 @enderror">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $transaksi->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email_verified_at" class="block font-semibold mb-2">Tanggal Verifikasi Email</label>
                        <input type="date" name="email_verified_at" id="email_verified_at"
                            class="w-full border rounded px-3 py-2 @error('email_verified_at') border-red-500 @enderror"
                            value="{{ old('email_verified_at', $transaksi->user ? $transaksi->user->email_verified_at?->format('Y-m-d') : '') }}">
                        <small class="text-gray-500 text-sm">Tanggal verifikasi email karyawan</small>
                        @error('email_verified_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Expense Fields -->
                <div id="expense-fields" style="{{ old('type', $transaction->type) == 'expense' ? '' : 'display:none;' }}">
                    <div class="mb-4">
                        <label for="description" class="block font-semibold mb-2">Keterangan Pengeluaran</label>
                        <input type="text" name="description" id="description"
                            class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror"
                            value="{{ old('description', $transaction->description) }}">
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="bg-brand-yellow text-white font-bold px-6 py-2 rounded shadow hover:bg-yellow-500 transition">
                        Update Transaksi
                    </button>
                    <a href="{{ route('transaksi.index') }}"
                        class="bg-gray-500 text-white font-bold px-6 py-2 rounded shadow hover:bg-gray-600 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Store service prices in JavaScript object
        const servicePrices = {
            @foreach ($services as $service)
                '{{ $service->id }}': {{ $service->price }},
            @endforeach
        };

        document.getElementById('radio-income').addEventListener('change', function() {
            document.getElementById('income-fields').style.display = '';
            document.getElementById('expense-fields').style.display = 'none';
        });

        document.getElementById('radio-expense').addEventListener('change', function() {
            document.getElementById('income-fields').style.display = 'none';
            document.getElementById('expense-fields').style.display = '';
        });

        // Auto-fill amount when service is selected
        document.getElementById('service_id').addEventListener('change', function() {
            const serviceId = this.value;
            const amountInput = document.getElementById('amount');

            if (serviceId && servicePrices[serviceId]) {
                amountInput.value = servicePrices[serviceId];
                amountInput.classList.add('bg-gray-100');
            } else {
                amountInput.value = '';
                amountInput.classList.remove('bg-gray-100');
            }
        });

        // Clear amount when switching to expense type
        document.getElementById('radio-expense').addEventListener('change', function() {
            document.getElementById('amount').value = '';
            document.getElementById('amount').classList.remove('bg-gray-100');
        });

        // Auto-fill amount when switching back to income if service is selected
        document.getElementById('radio-income').addEventListener('change', function() {
            const serviceId = document.getElementById('service_id').value;
            const amountInput = document.getElementById('amount');

            if (serviceId && servicePrices[serviceId]) {
                amountInput.value = servicePrices[serviceId];
                amountInput.classList.add('bg-gray-100');
            }
        });

        // Allow manual editing of amount field when user focuses on it
        document.getElementById('amount').addEventListener('focus', function() {
            this.classList.remove('bg-gray-100');
        });
    </script>
@endpush
