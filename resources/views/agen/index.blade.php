@extends('layouts.app')

@section('title', 'Kelola Agen')

@push('styles')
<style>
	/* --- Custom Animations & Styles --- */
	.fade-in-up {
		animation: fadeInUp 0.7s cubic-bezier(.39,.575,.565,1) both;
	}
	@keyframes fadeInUp {
		0% { opacity: 0; transform: translateY(40px); }
		100% { opacity: 1; transform: translateY(0); }
	}
	.table-row-hover {
		transition: background 0.2s, border-left-color 0.2s;
	}
	.table-row-hover:hover {
		background: #f0fdf4;
		border-left: 4px solid #22c55e;
	}
	.modal-content-enhanced {
		border: 0;
		box-shadow: 0 8px 32px rgba(0,0,0,0.18);
		border-radius: 1rem;
		padding: 0;
	}
	.modal-header {
		padding: 1.25rem 1.5rem 1rem 1.5rem;
		border-bottom: 1px solid #f3f4f6;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.modal-title {
		font-size: 1.25rem;
		font-weight: 700;
	}
	.btn-close {
		background: none;
		border: none;
		font-size: 1.5rem;
		color: #888;
		cursor: pointer;
		transition: color 0.2s;
	}
	.btn-close:hover {
		color: #222;
	}
	.modal-body {
		padding: 1.5rem;
	}
	.modal-footer {
		padding: 1rem 1.5rem;
		border-top: 1px solid #f3f4f6;
		display: flex;
		justify-content: flex-end;
		gap: 0.75rem;
	}
	.btn-cta {
		background: linear-gradient(to right, #22c55e, #16a34a);
		color: #fff;
		font-weight: bold;
		box-shadow: 0 2px 8px rgba(34,197,94,0.15);
		border-radius: 0.5rem;
		padding: 0.5rem 1.5rem;
		transition: background 0.2s;
	}
	.btn-cta:hover {
		background: linear-gradient(to right, #16a34a, #166534);
	}
	.btn-secondary {
		background: #f3f4f6;
		color: #222;
		border-radius: 0.5rem;
		font-weight: 500;
		padding: 0.5rem 1.5rem;
		transition: background 0.2s;
	}
	.btn-secondary:hover {
		background: #e5e7eb;
	}
	input:focus, select:focus {
		outline: none;
		box-shadow: 0 0 0 2px #22c55e;
		border-color: #22c55e;
		transition: box-shadow 0.2s;
	}
</style>

@section('content')
<div class="container mx-auto py-8 fade-in-up">
	<div class="flex items-center justify-between mb-6">
		<h1 class="text-2xl font-bold">Kelola Agen</h1>
		@if(auth()->user()->role === 'admin')
			<button id="add-employee-btn" class="btn-cta" onclick="openModal('create')">Tambah Agen</button>
		@endif
	</div>
	<div class="bg-white rounded-lg shadow-lg p-6" style="box-shadow: 0 4px 24px rgba(34,197,94,0.08);">
		<div class="overflow-x-auto">
			<table id="employees-table" class="min-w-full table-auto border">
				<thead class="bg-gray-100">
					<tr>
						<th class="px-4 py-2 border">Nama</th>
						<th class="px-4 py-2 border">Email</th>
						<th class="px-4 py-2 border">Nomor Rekening</th>
						@if(auth()->user()->role === 'admin')
							<th class="px-4 py-2 border">Aksi</th>
						@endif
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>

	<!-- Modal Structure -->
	<div id="employee-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
		<!-- Backdrop -->
		<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
			<!-- Backdrop Element -->
			<div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

			<!-- This element is to trick the browser into centering the modal contents. -->
			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

			<!-- Modal Panel -->
			<div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
				<!-- Modal Header -->
				<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
					<div class="flex items-center justify-between">
						<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
							Tambah Agen
						</h3>
						<button type="button" class="text-gray-400 bg-white hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeModal()">
							<span class="sr-only">Close</span>
							<!-- Heroicon name: outline/x -->
							<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
							</svg>
						</button>
					</div>
				</div>

				<!-- Modal Body -->
				<form id="employee-form" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
					@csrf
					<input type="hidden" name="_method" id="_method" value="POST">
					
					<div class="mb-4">
						<label for="modal-name" class="block font-semibold mb-2">Nama <span class="text-red-500">*</span></label>
						<input type="text" name="name" id="modal-name" class="w-full border rounded px-3 py-2" required>
					</div>
					
					<div class="mb-4">
						<label for="modal-email" class="block font-semibold mb-2">Email <span class="text-red-500">*</span></label>
						<input type="email" name="email" id="modal-email" class="w-full border rounded px-3 py-2" required>
					</div>
					
					<div class="mb-4">
						<label for="modal-email-verified-at" class="block font-semibold mb-2">Tanggal Verifikasi Email</label>
						<input type="date" name="email_verified_at" id="modal-email-verified-at" class="w-full border rounded px-3 py-2">
						<small class="text-gray-500 text-sm">Tanggal verifikasi email agen</small>
					</div>
					
					<div class="mb-4">
						<label for="modal-password" class="block font-semibold mb-2">Password <span class="text-red-500" id="password-required">*</span></label>
						<input type="password" name="password" id="modal-password" class="w-full border rounded px-3 py-2" placeholder="Masukkan password">
					</div>
					
					<div class="mb-4">
						<label for="modal-bank-account" class="block font-semibold mb-2">Nomor Rekening</label>
						<input type="text" name="bank_account" id="modal-bank-account" class="w-full border rounded px-3 py-2" placeholder="Masukkan nomor rekening">
					</div>
				</form>

				<!-- Modal Footer -->
				<div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
					<!-- Simpan Button -->
					<button type="submit" form="employee-form" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
						<i class="fas fa-save mr-2"></i>
						Simpan
					</button>
					<!-- Batal Button -->
					<button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
						Batal
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert Helper -->
<script src="{{ asset('js/sweetalert-helper.js') }}"></script>
<!-- jQuery & DataTables CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script>
	// --- DataTables Initialization ---
	let table;
	$(document).ready(function() {
		table = $('#employees-table').DataTable({
			ajax: {
				url: '{{ route('agen.data') }}',
				dataSrc: 'data'
			},
			columns: [
				   { data: 'name', name: 'name' },
				   { data: 'email', name: 'email' },
				   { 
					   data: 'bank_account', 
					   name: 'bank_account',
					   render: function(data, type, row) {
						   return data ? data : '-';
					   }
				   }
				   @if(auth()->user()->role === 'admin')
				   ,{
					   data: 'id',
					   orderable: false,
					   searchable: false,
					   render: function(id) {
						   return `
							   <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2" onclick="openModal('edit', ${id})">
								   <i class='fas fa-edit'></i>
							   </button>
							   <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="deleteEmployee(${id})">
								   <i class='fas fa-trash'></i>
							   </button>
						   `;
					   }
				   }
				   @endif
			],
			createdRow: function(row) {
				$(row).addClass('table-row-hover');
			},
			language: {
				processing: "Sedang memproses...",
				search: "Cari:",
				lengthMenu: "Tampilkan _MENU_ entri",
				info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
				infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
				infoFiltered: "(disaring dari _MAX_ total entri)",
				infoPostFix: "",
				loadingRecords: "Sedang memuat...",
				zeroRecords: "Tidak ada data yang cocok ditemukan",
				emptyTable: "Tidak ada data yang tersedia pada tabel",
				paginate: {
					first: "Pertama",
					previous: "Sebelumnya",
					next: "Selanjutnya",
					last: "Terakhir"
				},
				aria: {
					sortAscending: ": aktifkan untuk mengurutkan kolom secara ascending",
					sortDescending: ": aktifkan untuk mengurutkan kolom secara descending"
				}
			}
		});

		// --- Add Employee Button ---
		$('#add-employee-btn').on('click', function() {
			openModal('create');
		});

	// --- Form Submit Handler ---
	$('#employee-form').on('submit', function(e) {
		e.preventDefault();
		const mode = $('#_method').val() === 'PUT' ? 'edit' : 'create';
		let url = mode === 'create'
			? '{{ route('agen.store') }}'
			: $('#employee-form').attr('action') || $('#employee-form').data('action');
		let method = 'POST'; // Always use POST with Laravel
		const formData = $(this).serialize();
		
		console.log('Form submission - Mode:', mode, 'URL:', url, 'Method:', method); // Debug log
		
		// Clear previous error messages
		$('.error-message').remove();
		$('.border-red-500').removeClass('border-red-500');
		
		$.ajax({
			url: url,
			type: method,
			data: formData,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(res) {
				console.log('Success response:', res); // Debug log
				$('#employee-modal').addClass('hidden');
				showNotification(res.message, 'success');
				table.ajax.reload();
			},
			error: function(xhr) {
				console.log('Error response:', xhr); // Debug log
				if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
					// Display validation errors
					const errors = xhr.responseJSON.errors;
					for (const field in errors) {
						const errorMessages = errors[field];
						const inputElement = $(`[name="${field}"]`);
						
						// Add red border to input
						inputElement.addClass('border-red-500');
						
						// Add error message
						const errorText = errorMessages[0]; // Show first error message
						const errorDiv = `<div class="error-message text-red-500 text-sm mt-1">${errorText}</div>`;
						inputElement.closest('.mb-4').append(errorDiv);
					}
					showNotification('Mohon periksa input Anda.', 'error');
				} else {
					showNotification('Gagal menyimpan data agen', 'error');
				}
			}
		});
	});
	});

	// --- Modal Functions ---
	function openModal(mode, id = null) {
		resetForm();
		if (mode === 'create') {
			$('#modal-title').text('Tambah Agen');
			$('#_method').val('POST');
			$('#employee-form').removeAttr('action');
			$('#employee-form').removeData('action');
			$('#modal-password').prop('required', true);
			$('#password-required').show();
			$('#modal-password').attr('placeholder', 'Masukkan password');
		} else {
			$('#modal-title').text('Edit Agen');
			$('#_method').val('PUT');
			const updateUrl = `{{ route('agen.update', ':id') }}`.replace(':id', id);
			$('#employee-form').attr('action', updateUrl);
			$('#employee-form').data('action', updateUrl);
			console.log('Edit mode - URL set to:', updateUrl); // Debug log
			$('#modal-password').prop('required', false);
			$('#password-required').hide();
			$('#modal-password').attr('placeholder', 'Kosongkan jika tidak ingin mengubah password');
			fetchEmployeeData(id);
		}
		$('#employee-modal').removeClass('hidden');
	}

	function closeModal() {
		$('#employee-modal').addClass('hidden');
	}

	function resetForm() {
		$('#employee-form')[0].reset();
		// Clear error messages and styling
		$('.error-message').remove();
		$('.border-red-500').removeClass('border-red-500');
	}

	// --- Fetch Employee Data for Edit ---
	function fetchEmployeeData(id) {
		const fetchUrl = `{{ route('agen.show', ':id') }}`.replace(':id', id);
		console.log('Fetching employee data from:', fetchUrl); // Debug log
		
		$.get(fetchUrl)
			.done(function(data) {
				console.log('Employee data received:', data); // Debug log
				$('#modal-name').val(data.name || '');
				$('#modal-email').val(data.email || '');
				$('#modal-bank-account').val(data.bank_account || '');
				// Set email verified date if available
				if (data.email_verified_at) {
					const emailVerifiedDate = new Date(data.email_verified_at);
					$('#modal-email-verified-at').val(emailVerifiedDate.toISOString().split('T')[0]);
				} else {
					$('#modal-email-verified-at').val('');
				}
				$('#modal-password').val(''); // Always clear password field
			})
			.fail(function(xhr, status, error) {
				console.error('Error fetching employee data:', xhr.responseText);
				showNotification('Error loading employee data: ' + error, 'error');
			});
	}

	// --- Delete Employee Handler ---
	function deleteEmployee(id) {
		Swal.fire({
			title: 'Yakin ingin menghapus agen ini?',
			text: 'Tindakan ini tidak dapat dibatalkan.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#22c55e',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: `{{ route('agen.destroy', ':id') }}`.replace(':id', id),
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						_method: 'DELETE'
					},
					success: function(res) {
						showNotification(res.message, 'success');
						table.ajax.reload();
					},
					error: function(xhr) {
						showNotification('Gagal menghapus agen', 'error');
					}
				});
			}
		});
	}

	// --- Laravel Flash Message Toast ---
	@if(session('success'))
		showNotification('{{ session('success') }}', 'success');
	@endif
</script>
@endpush
