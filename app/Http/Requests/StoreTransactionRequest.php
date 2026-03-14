<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'service_id' => 'required_if:type,income|nullable|exists:services,id',
            'description' => 'required_if:type,expense|nullable|string',
        ];

        // For admin users, user_id is required for income transactions
        // For employees, user_id will be automatically set to their own ID
        if (auth()->user() && auth()->user()->role === 'admin') {
            $rules['user_id'] = 'required_if:type,income|nullable|exists:users,id';
        } else {
            // For employees, user_id is optional in validation since it's auto-set
            $rules['user_id'] = 'nullable|exists:users,id';
        }

        return $rules;
    }

    /**
     * Get custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Jenis transaksi harus dipilih.',
            'type.in' => 'Jenis transaksi tidak valid.',
            'amount.required' => 'Jumlah transaksi harus diisi.',
            'amount.numeric' => 'Jumlah transaksi harus berupa angka.',
            'amount.min' => 'Jumlah transaksi minimal Rp 1.',
            'transaction_date.required' => 'Tanggal transaksi harus diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
            'user_id.required_if' => 'Karyawan harus dipilih untuk transaksi pemasukan.',
            'user_id.exists' => 'Karyawan yang dipilih tidak valid.',
            'service_id.required_if' => 'Layanan harus dipilih untuk transaksi pemasukan.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'description.required_if' => 'Keterangan harus diisi untuk transaksi pengeluaran.',
            'description.string' => 'Keterangan harus berupa teks.',
        ];
    }
}
