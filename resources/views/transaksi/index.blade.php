@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

<style>
    /* --- Custom Animations & Styles --- */
    .fade-in-up {
        animation: fadeInUp 0.7s cubic-bezier(.39, .575, .565, 1) both;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
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
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
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
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.15);
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

    input:focus,
    select:focus {
        outline: none;
        box-shadow: 0 0 0 2px #22c55e;
        border-color: #22c55e;
        transition: box-shadow 0.2s;
    }
</style>

@section('content')
    <div class="container mx-auto py-8 fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Manajemen Transaksi</h1>
            <button id="add-transaction-btn" class="btn-cta">Tambah Transaksi</button>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-lg p-4 mb-6" style="box-shadow: 0 4px 24px rgba(34,197,94,0.08);">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="month-filter" class="font-semibold text-gray-700">Filter Bulan:</label>
                    <input type="month" id="month-filter"
                        class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        value="{{ date('Y-m') }}">
                </div>
                <button id="filter-btn" class="btn-cta px-4 py-2">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <button id="clear-filter-btn" class="btn-secondary px-4 py-2">
                    <i class="fas fa-times mr-2"></i>Hapus Filter
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6" style="box-shadow: 0 4px 24px rgba(34,197,94,0.08);">
            <div class="overflow-x-auto">
                <table id="transactions-table" class="min-w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Keterangan</th>
                            <th class="px-4 py-2 border">Deskripsi</th>
                            <th class="px-4 py-2 border">Karyawan</th>
                            <th class="px-4 py-2 border">Jumlah</th>
                            @if (auth()->user()->role === 'admin')
                                <th class="px-4 py-2 border">Aksi</th>
                            @else
                                <th class="px-4 py-2 border">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Modal Structure -->
        <div id="transaction-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop Element -->
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Tambah Transaksi
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-white hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                onclick="closeModal()">
                                <span class="sr-only">Close</span>
                                <!-- Heroicon name: outline/x -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <form id="transaction-form" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">Jenis Transaksi</label>
                            <div class="flex gap-6">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="income" checked class="mr-2"
                                        id="modal-radio-income">
                                    <span>Pemasukan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="expense" class="mr-2"
                                        id="modal-radio-expense">
                                    <span>Pengeluaran</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="modal-transaction-date" class="block font-semibold mb-2">Tanggal</label>
                            <input type="date" name="transaction_date" id="modal-transaction-date"
                                class="w-full border rounded px-3 py-2" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="modal-amount" class="block font-semibold mb-2">Jumlah (Rp)</label>
                            <input type="number" name="amount" id="modal-amount"
                                class="w-full border rounded px-3 py-2" min="1"
                                required>
                            <small class="text-gray-500 text-sm">Akan terisi otomatis saat memilih layanan</small>
                        </div>

                        <!-- Income Fields -->
                        <div id="modal-income-fields">
                            <div class="mb-4">
                                <label for="modal-service-id" class="block font-semibold mb-2">Layanan</label>
                                <select name="service_id" id="modal-service-id" class="w-full border rounded px-3 py-2">
                                    <option value="">Pilih Layanan</option>
                                    @foreach ($services ?? [] as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="modal-user-id" class="block font-semibold mb-2">Karyawan</label>
                                @if (auth()->user()->role === 'admin')
                                    <select name="user_id" id="modal-user-id" class="w-full border rounded px-3 py-2">
                                        <option value="">Pilih Karyawan</option>
                                        @foreach ($users ?? [] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <!-- For employees, show their name as read-only and use hidden input -->
                                    <input type="text" value="{{ auth()->user()->name }}"
                                        class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                                    <input type="hidden" name="user_id" id="modal-user-id" value="{{ auth()->id() }}">
                                @endif
                            </div>
                            
                        </div>
                        
                        <!-- Expense Fields -->
                        <div id="modal-expense-fields" style="display:none;">
                            <div class="mb-4">
                                <label for="modal-description" class="block font-semibold mb-2">Keterangan
                                    Pengeluaran</label>
                                <input type="text" name="description" id="modal-description"
                                    class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                    </form>

                    <!-- Modal Footer -->
                    <!-- Modal Footer -->
                    <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <!-- Simpan Button -->
                        <button type="submit" form="transaction-form"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                        <!-- Batal Button -->
                        <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
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
        // Store service prices in JavaScript object
        const servicePrices = {
            @foreach ($services ?? [] as $service)
                '{{ $service->id }}': {{ $service->price }},
            @endforeach
        };

        // --- DataTables Initialization ---
        let table;
        $(document).ready(function() {
            table = $('#transactions-table').DataTable({
                ajax: {
                    url: '{{ route('transaksi.data') }}',
                    dataSrc: 'data',
                    data: function(d) {
                        d.month = $('#month-filter').val();
                    }
                },
                order: [
                    [0, 'desc']
                ], // Sort by first column (date) in descending order (newest first)
                columns: [{
                        data: 'transaction_date',
                        render: function(data) {
                            if (!data) return '-';
                            const d = new Date(data);
                            return d.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });
                        }
                    },
                    {
                        data: null,
                        render: function(row) {
                            return row.service ? row.service.name : (row.type === 'expense' ?
                                'Pengeluaran' : 'Pemasukan');
                        }
                    },
                    {
                        data: 'description',
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'user',
                        render: function(user) {
                            return user ? user.name : '-';
                        }
                    },
                    {
                        data: 'amount',
                        render: function(data, type, row) {
                            const color = row.type === 'income' ? 'text-green-600' : 'text-red-600';
                            return `<span class="font-bold ${color}">Rp${parseInt(data).toLocaleString('id-ID')}</span>`;
                        }
                    }, {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(id, type, row) {
                            @if (auth()->user()->role === 'admin')
                                return `
								<button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2" onclick="openModal('edit', ${id})">
									<i class='fas fa-edit'></i>
								</button>
								<button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="deleteTransaction(${id})">
									<i class='fas fa-trash'></i>
								</button>
							`;
                            @else
                                // For employees, only show edit/delete for their own transactions
                                if (row.user && row.user.id === {{ auth()->id() }}) {
                                    return `
									<button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2" onclick="openModal('edit', ${id})">
										<i class='fas fa-edit'></i>
									</button>
									<button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="deleteTransaction(${id})">
										<i class='fas fa-trash'></i>
									</button>
								`;
                                } else {
                                    return '<span class="text-gray-400">-</span>';
                                }
                            @endif
                        }
                    }
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

            // --- Add Transaction Button ---
            $('#add-transaction-btn').on('click', function() {
                openModal('create');
            });

            // --- Filter Button ---
            $('#filter-btn').on('click', function() {
                table.ajax.reload();
            });

            // --- Clear Filter Button ---
            $('#clear-filter-btn').on('click', function() {
                $('#month-filter').val('{{ date('Y-m') }}');
                table.ajax.reload();
            });

            // --- Filter on Enter Key ---
            $('#month-filter').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    table.ajax.reload();
                }
            });

            // --- Modal and Form Logic ---
            $('#modal-radio-income').on('change', function() {
                $('#modal-income-fields').show();
                $('#modal-expense-fields').hide();
                // Auto-fill amount when switching back to income if service is selected
                const serviceId = $('#modal-service-id').val();
                const amountInput = $('#modal-amount');

                if (serviceId && servicePrices[serviceId]) {
                    amountInput.val(servicePrices[serviceId]).addClass('bg-gray-100');
                }
            });
            $('#modal-radio-expense').on('change', function() {
                $('#modal-income-fields').hide();
                $('#modal-expense-fields').show();
                // Clear amount and enable manual input when switching to expense type
                $('#modal-amount').val('').removeClass('bg-gray-100');
            });

            // Auto-fill amount when service is selected
            $('#modal-service-id').on('change', function() {
                const serviceId = $(this).val();
                const amountInput = $('#modal-amount');

                if (serviceId && servicePrices[serviceId]) {
                    amountInput.val(servicePrices[serviceId]).addClass('bg-gray-100');
                } else {
                    amountInput.val('').removeClass('bg-gray-100');
                }
            });

            // Allow manual editing of amount field when user focuses on it
            $('#modal-amount').on('focus', function() {
                $(this).removeClass('bg-gray-100');
            });

            // --- Form Submit Handler ---
            $('#transaction-form').on('submit', function(e) {
                e.preventDefault();
                const mode = $('#_method').val() === 'PUT' ? 'edit' : 'create';
                let url = mode === 'create' ?
                    '{{ route('transaksi.store') }}' :
                    $('#transaction-form').data('action');
                let method = mode === 'create' ? 'POST' : 'PUT';
                const formData = $(this).serialize();

                // Clear previous error messages
                $('.error-message').remove();
                $('.border-red-500').removeClass('border-red-500');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(res) {
                        $('#transaction-modal').addClass('hidden');
                        showNotification(res.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
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
                                const errorDiv =
                                    `<div class="error-message text-red-500 text-sm mt-1">${errorText}</div>`;
                                inputElement.closest('.mb-4').append(errorDiv);
                            }
                            showNotification('Mohon periksa input Anda.', 'error');
                        } else {
                            showNotification('Gagal menyimpan transaksi', 'error');
                        }
                    }
                });
            });
        });

        // --- Modal Functions ---
        function openModal(mode, id = null) {
            resetForm();
            if (mode === 'create') {
                $('#modal-title').text('Tambah Transaksi');
                $('#_method').val('POST');
                $('#transaction-form').removeData('action');
            } else {
                $('#modal-title').text('Edit Transaksi');
                $('#_method').val('PUT');
                $('#transaction-form').data('action', `/transaksi/${id}`);
                fetchTransactionData(id);
            }
            $('#transaction-modal').removeClass('hidden');
        }

        function closeModal() {
            $('#transaction-modal').addClass('hidden');
        }

        function resetForm() {
            $('#transaction-form')[0].reset();
            $('#modal-income-fields').show();
            $('#modal-expense-fields').hide();
            // Clear error messages and styling
            $('.error-message').remove();
            $('.border-red-500').removeClass('border-red-500');
            // Remove gray background from amount field
            $('#modal-amount').removeClass('bg-gray-100');
            // Clear email verified date field
            $('#modal-email-verified-at').val('');
            // Reset date to today
            $('#modal-transaction-date').val('{{ date('Y-m-d') }}');

            @if (auth()->user()->role === 'karyawan')
                // For employees, always set their user_id
                $('#modal-user-id').val({{ auth()->id() }});
            @endif
        }

        // --- Fetch Transaction Data for Edit ---
        function fetchTransactionData(id) {
            $.get(`{{ route('transaksi.show', ':id') }}`.replace(':id', id))
                .done(function(data) {
                    console.log('Transaction data received:', data); // Debug log
                    $('#modal-amount').val(data.amount || '');
                    $('#modal-transaction-date').val(data.transaction_date || '{{ date('Y-m-d') }}');
                    if (data.type === 'income') {
                        $('#modal-radio-income').prop('checked', true).trigger('change');
                    } else {
                        $('#modal-radio-expense').prop('checked', true).trigger('change');
                    }
                    $('#modal-service-id').val(data.service_id || '');

                    @if (auth()->user()->role === 'admin')
                    $('#modal-user-id').val(data.user_id || '');
                    @else
                        // For employees, user_id is already set to their ID
                        $('#modal-user-id').val({{ auth()->id() }});
                    @endif

                    $('#modal-description').val(data.description || '');
                })
                .fail(function(xhr, status, error) {
                    console.error('Error fetching transaction data:', xhr.responseText);
                    alert('Error loading transaction data: ' + error);
                });
        }

        // --- Delete Transaction Handler ---
        function deleteTransaction(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus transaksi ini?',
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
                        url: `/transaksi/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            showNotification(res.message, 'success');
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            showNotification('Gagal menghapus transaksi', 'error');
                        }
                    });
                }
            });
        }

        // --- Laravel Flash Message Toast ---
        @if (session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
    </script>
@endpush
