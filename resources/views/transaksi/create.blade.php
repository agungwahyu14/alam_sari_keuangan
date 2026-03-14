
@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="container mx-auto py-8">
	<h1 class="text-2xl font-bold mb-6">Tambah Transaksi Baru</h1>
	<div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
		<form method="POST" action="{{ route('transaksi.store') }}">
			@csrf
			<div class="mb-4">
				<label class="block font-semibold mb-2">Jenis Transaksi</label>
				<div class="flex gap-6">
					<label class="flex items-center">
						<input type="radio" name="type" value="income" checked class="mr-2" id="radio-income">
						<span>Pemasukan</span>
					</label>
					<label class="flex items-center">
						<input type="radio" name="type" value="expense" class="mr-2" id="radio-expense">
						<span>Pengeluaran</span>
					</label>
				</div>
			</div>
			<div class="mb-4">
				<label for="transaction_date" class="block font-semibold mb-2">Tanggal</label>
				<input type="date" name="transaction_date" id="transaction_date" class="w-full border rounded px-3 py-2" value="{{ date('Y-m-d') }}">
			</div>
			<div class="mb-4">
				<label for="amount" class="block font-semibold mb-2">Jumlah (Rp)</label>
				<input type="number" name="amount" id="amount" class="w-full border rounded px-3 py-2" min="1" required>
				<small class="text-gray-500 text-sm">Akan terisi otomatis saat memilih layanan</small>
			</div>
			<!-- Income Fields -->
			<div id="income-fields">
				<div class="mb-4">
					<label for="service_id" class="block font-semibold mb-2">Layanan</label>
					<select name="service_id" id="service_id" class="w-full border rounded px-3 py-2">
						<option value="">Pilih Layanan</option>
						@foreach ($services as $service)
							<option value="{{ $service->id }}">{{ $service->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-4">
					<label for="user_id" class="block font-semibold mb-2">Karyawan</label>
					<select name="user_id" id="user_id" class="w-full border rounded px-3 py-2">
						<option value="">Pilih Karyawan</option>
						<option value="1">Andi</option>
						<option value="2">Sutrisno</option>
						<option value="3">Admin</option>
					</select>
				</div>
			</div>
			<!-- Expense Fields -->
			<div id="expense-fields" style="display:none;">
				<div class="mb-4">
					<label for="description" class="block font-semibold mb-2">Keterangan Pengeluaran</label>
					<input type="text" name="description" id="description" class="w-full border rounded px-3 py-2">
				</div>
			</div>
			<button type="submit" class="bg-brand-yellow text-white font-bold px-6 py-2 rounded shadow hover:bg-yellow-500 transition">Simpan Transaksi</button>
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
