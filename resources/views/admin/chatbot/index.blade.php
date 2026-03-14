@extends('layouts.app')

@section('title', 'Kelola Chatbot FAQ')

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
	.btn-cta {
		/* background: linear-gradient(to right, #22c55e, #16a34a); */
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
	.badge-active {
		background: #d1fae5;
		color: #065f46;
		padding: 0.25rem 0.75rem;
		border-radius: 9999px;
		font-size: 0.75rem;
		font-weight: 600;
	}
	.badge-inactive {
		background: #fee2e2;
		color: #991b1b;
		padding: 0.25rem 0.75rem;
		border-radius: 9999px;
		font-size: 0.75rem;
		font-weight: 600;
	}
	input:focus, textarea:focus, select:focus {
		outline: none;
		box-shadow: 0 0 0 2px #22c55e;
		border-color: #22c55e;
		transition: box-shadow 0.2s;
	}
</style>
@endpush

@section('content')
<div class="container mx-auto py-8 fade-in-up">
	<div class="flex items-center justify-between mb-6">
		<h1 class="text-2xl font-bold">Kelola Chatbot FAQ</h1>
		<button id="add-faq-btn" class="btn-cta" onclick="openModal('createModal')">Tambah FAQ</button>
	</div>

	<div class="bg-white rounded-lg shadow-lg p-6" style="box-shadow: 0 4px 24px rgba(34,197,94,0.08);">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 border text-left" width="5%">#</th>
                            <th class="px-4 py-3 border text-left" width="30%">Pertanyaan</th>
                            <th class="px-4 py-3 border text-left" width="45%">Jawaban</th>
                            <th class="px-4 py-3 border text-center" width="10%">Status</th>
                            <th class="px-4 py-3 border text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                            <tr class="table-row-hover border-b">
                                <td class="px-4 py-3 border">
                                    {{ $loop->iteration + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                                <td class="px-4 py-3 border">
                                    <div class="font-semibold text-gray-800">{{ Str::limit($faq->question, 50) }}</div>
                                </td>
                                <td class="px-4 py-3 border">
                                    <div class="text-gray-600 text-sm">{{ Str::limit($faq->answer, 100) }}</div>
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <span class="badge-{{ $faq->is_active ? 'active' : 'inactive' }}">
                                        {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <button
                                        onclick="editFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}', {{ $faq->is_active ? 'true' : 'false' }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-1 transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.chatbot.destroy', $faq) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data FAQ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop Element -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('createModal')"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Tambah FAQ Baru
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-white hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="closeModal('createModal')">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="createForm" action="{{ route('admin.chatbot.store') }}" method="POST"
                    class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="question"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                rows="3" required placeholder="Contoh: Bagaimana cara membuat transaksi baru?"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jawaban <span class="text-red-500">*</span>
                            </label>
                            <textarea name="answer"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                rows="5" required placeholder="Tulis jawaban lengkap di sini..."></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active_create" value="1" checked
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="is_active_create" class="ml-2 text-sm text-gray-700">Aktifkan FAQ ini</label>
                        </div>
                    </div>
                </form>

                <!-- Modal Footer -->
                <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <!-- Simpan Button -->
                    <button type="submit" form="createForm"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                    <!-- Batal Button -->
                    <button type="button" onclick="closeModal('createModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop Element -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('editModal')"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-edit">
                            Edit FAQ
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-white hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="closeModal('editModal')">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="editForm" method="POST" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="question" id="edit_question"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                rows="3" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jawaban <span class="text-red-500">*</span>
                            </label>
                            <textarea name="answer" id="edit_answer"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                rows="5" required></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Aktifkan FAQ ini</label>
                        </div>
                    </div>
                </form>

                <!-- Modal Footer -->
                <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <!-- Update Button -->
                    <button type="submit" form="editForm"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>
                    <!-- Batal Button -->
                    <button type="button" onclick="closeModal('editModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert untuk success message
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#22c55e',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        @endif
    });

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function editFaq(id, question, answer, isActive) {
        document.getElementById('editForm').action = '/admin/chatbot/' + id;
        document.getElementById('edit_question').value = question;
        document.getElementById('edit_answer').value = answer;
        document.getElementById('edit_is_active').checked = isActive;
        openModal('editModal');
    }

    // SweetAlert konfirmasi delete
    function confirmDelete(button) {
        Swal.fire({
            title: 'Hapus FAQ?',
            text: 'Data FAQ ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form
                button.closest('form').submit();
            }
        });
    }
</script>
@endpush
