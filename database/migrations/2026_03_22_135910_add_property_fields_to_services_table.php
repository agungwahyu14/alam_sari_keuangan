<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('property_type', ['rumah', 'tanah', 'ruko', 'apartemen', 'villa', 'gudang'])->default('rumah')->after('name');
            $table->string('location')->nullable()->after('property_type');
            $table->enum('status', ['available', 'sold', 'pending'])->default('available')->after('price');
            $table->text('description')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['property_type', 'location', 'status', 'description']);
        });
    }
};
