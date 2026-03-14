@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="container mx-auto py-8">
	<h1 class="text-2xl font-bold mb-6">Manajemen Transaksi</h1>
	<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
		<!-- Left Column: Form -->
		<div class="bg-white rounded-lg shadow p-6">
			<form method="POST" action="{{ route('transaksi.store') }}" id="add-transaction-form">
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
							@foreach ($users ?? [] as $user)
								<option value="{{ $user->id }}">{{ $user->name }}</option>
							@endforeach
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
		<!-- Right Column: Table -->
		<div class="bg-white rounded-lg shadow p-6">
			<h2 class="text-lg font-semibold mb-4">Riwayat Transaksi</h2>
			<table class="min-w-full table-auto border">
				<thead class="bg-gray-100">
					<tr>
						<th class="px-4 py-2 border">Tanggal</th>
						<th class="px-4 py-2 border">Keterangan</th>
						<th class="px-4 py-2 border">Deskripsi</th>
						<th class="px-4 py-2 border">Karyawan</th>
						<th class="px-4 py-2 border">Jumlah</th>
						<th class="px-4 py-2 border">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($transactions as $transaction)
						<tr>
							<td class="px-4 py-2 border">{{ $transaction->transaction_date->format('d M Y') }}</td>
							<td class="px-4 py-2 border">{{ $transaction->service->name ?? ($transaction->type == 'expense' ? 'Pengeluaran' : 'Pemasukan') }}</td>
							<td class="px-4 py-2 border">{{ $transaction->description ?? '-' }}</td>
							<td class="px-4 py-2 border">{{ $transaction->user->name ?? '-' }}</td>
							<td class="px-4 py-2 border font-bold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
								Rp{{ number_format($transaction->amount, 0, ',', '.') }}
							</td>
							<td class="px-4 py-2 border">
								<div class="flex gap-2">
									<button onclick="openEditModal({{ $transaction->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 flex items-center">
										<i class="fas fa-edit"></i>
									</button>
									<form method="POST" action="{{ route('transaksi.destroy', $transaction->id) }}" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" class="inline">
										@csrf
										@method('DELETE')
										<button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 flex items-center">
											<i class="fas fa-trash"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
					@endforeach
</tbody>
</table>
</div>
</div>

<!-- Edit Transaction Modal -->
<div id="editTransactionModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
	<div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
		<button type="button" onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
		<h2 class="text-xl font-bold mb-4">Edit Transaksi</h2>
		<form id="edit-transaction-form" method="POST">
			@csrf
			@method('PUT')
			<div class="mb-4">
				<label class="block font-semibold mb-2">Jenis Transaksi</label>
				<div class="flex gap-6">
					<label class="flex items-center">
						<input type="radio" name="type" value="income" id="edit-type-income" class="mr-2">
						<span>Pemasukan</span>
					</label>
					<label class="flex items-center">
						<input type="radio" name="type" value="expense" id="edit-type-expense" class="mr-2">
						<span>Pengeluaran</span>
					</label>
				</div>
			</div>
			<div class="mb-4">
				<label for="edit-transaction-date" class="block font-semibold mb-2">Tanggal</label>
				<input type="date" name="transaction_date" id="edit-transaction-date" class="w-full border rounded px-3 py-2">
			</div>
			<div class="mb-4">
				<label for="edit-amount" class="block font-semibold mb-2">Jumlah (Rp)</label>
				<input type="number" name="amount" id="edit-amount" class="w-full border rounded px-3 py-2" min="1" required>
			</div>
			<!-- Income Fields -->
			<div id="edit-income-fields">
				<div class="mb-4">
					<label for="edit-service-id" class="block font-semibold mb-2">Layanan</label>
					<select name="service_id" id="edit-service-id" class="w-full border rounded px-3 py-2">
						<option value="">Pilih Layanan</option>
						@foreach ($services as $service)
							<option value="{{ $service->id }}">{{ $service->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-4">
					<label for="edit-user-id" class="block font-semibold mb-2">Karyawan</label>
					<select name="user_id" id="edit-user-id" class="w-full border rounded px-3 py-2">
						<option value="">Pilih Karyawan</option>
						@foreach ($users ?? [] as $user)
							<option value="{{ $user->id }}">{{ $user->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<!-- Expense Fields -->
			<div id="edit-expense-fields" style="display:none;">
				<div class="mb-4">
					<label for="edit-description" class="block font-semibold mb-2">Keterangan Pengeluaran</label>
					<input type="text" name="description" id="edit-description" class="w-full border rounded px-3 py-2">
				</div>
			</div>
			<div class="flex justify-end">
				<button type="submit" class="bg-brand-yellow text-white font-bold px-6 py-2 rounded shadow hover:bg-yellow-500 transition">Update Transaksi</button>
			</div>
		</form>
	</div>
</div>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	// Show toast function
	function showToast(message, type = 'success') {
		const toast = document.createElement('div');
		toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-semibold ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
		toast.textContent = message;
		document.body.appendChild(toast);
		
		setTimeout(() => {
			toast.remove();
		}, 3000);
	}

	// Toggle income/expense fields for add form
	document.getElementById('radio-income').addEventListener('change', function() {
		document.getElementById('income-fields').style.display = '';
		document.getElementById('expense-fields').style.display = 'none';
	});
	document.getElementById('radio-expense').addEventListener('change', function() {
		document.getElementById('income-fields').style.display = 'none';
		document.getElementById('expense-fields').style.display = '';
	});

	// Toggle income/expense fields for edit modal
	function toggleEditFields(type) {
		if (type === 'income') {
			document.getElementById('edit-income-fields').style.display = '';
			document.getElementById('edit-expense-fields').style.display = 'none';
		} else {
			document.getElementById('edit-income-fields').style.display = 'none';
			document.getElementById('edit-expense-fields').style.display = '';
		}
	}
	document.getElementById('edit-type-income').addEventListener('change', function() {
		toggleEditFields('income');
	});
	document.getElementById('edit-type-expense').addEventListener('change', function() {
		toggleEditFields('expense');
	});

	// Open Edit Modal and populate form
	window.openEditModal = function(transactionId) {
		fetch(`/transaksi/${transactionId}/edit`)
			.then(response => response.json())
			.then(data => {
				document.getElementById('edit-transaction-form').action = `/transaksi/${transactionId}`;
				document.getElementById('edit-amount').value = data.amount;
				document.getElementById('edit-transaction-date').value = data.transaction_date;
				document.getElementById('edit-type-income').checked = data.type === 'income';
				document.getElementById('edit-type-expense').checked = data.type === 'expense';
				document.getElementById('edit-service-id').value = data.service_id ?? '';
				document.getElementById('edit-user-id').value = data.user_id ?? '';
				document.getElementById('edit-description').value = data.description ?? '';
				toggleEditFields(data.type);
				document.getElementById('editTransactionModal').classList.remove('hidden');
			});
	};

	window.closeEditModal = function() {
		document.getElementById('editTransactionModal').classList.add('hidden');
	};

	// Handle edit form submission
	document.getElementById('edit-transaction-form').addEventListener('submit', function(e) {
		e.preventDefault();
		const form = e.target;
		const url = form.action;
		const formData = new FormData(form);
		fetch(url, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value
			},
			body: formData
		})
		.then(response => {
			if (response.ok) {
				return response.json();
			} else {
				return response.json().then(err => Promise.reject(err));
			}
		})
		.then(data => {
			closeEditModal();
			showToast(data.message || 'Transaksi berhasil diperbarui!', 'success');
			setTimeout(() => window.location.reload(), 1200);
		})
		.catch(error => {
			console.error('Error:', error);
			let errorMessage = 'Gagal memperbarui transaksi';
			if (error.errors) {
				const firstError = Object.values(error.errors)[0];
				if (firstError && firstError[0]) {
					errorMessage = firstError[0];
				}
			} else if (error.message) {
				errorMessage = error.message;
			}
			showToast(errorMessage, 'error');
		});
	});

	// Handle add form submission
	document.getElementById('add-transaction-form').addEventListener('submit', function(e) {
		e.preventDefault();
		const form = e.target;
		const formData = new FormData(form);
		
		// Debug: log form data
		console.log('Form data being sent:');
		for (let [key, value] of formData.entries()) {
			console.log(key, value);
		}
		
		fetch(form.action, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value
			},
			body: formData
		})
		.then(response => {
			if (response.ok) {
				return response.json();
			} else {
				return response.json().then(err => Promise.reject(err));
			}
		})
		.then(data => {
			showToast(data.message || 'Transaksi berhasil ditambahkan!', 'success');
			form.reset();
			// Reset radio button to income
			document.getElementById('radio-income').checked = true;
			document.getElementById('income-fields').style.display = '';
			document.getElementById('expense-fields').style.display = 'none';
			// Reload page after a short delay to show updated table
			setTimeout(() => window.location.reload(), 1200);
		})
		.catch(error => {
			console.error('Error:', error);
			let errorMessage = 'Gagal menambahkan transaksi';
			if (error.errors) {
				// Laravel validation errors
				const firstError = Object.values(error.errors)[0];
				if (firstError && firstError[0]) {
					errorMessage = firstError[0];
				}
			} else if (error.message) {
				errorMessage = error.message;
			}
			showToast(errorMessage, 'error');
		});
	});

	// Show toast for success message
	@if(session('success'))
		showToast('{{ session('success') }}', 'success');
	@endif
</script>
@endpush
