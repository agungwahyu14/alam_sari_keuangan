<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Update role from 'karyawan' to 'agen'.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'karyawan')
            ->update(['role' => 'agen']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('role', 'agen')
            ->update(['role' => 'karyawan']);
    }
};
