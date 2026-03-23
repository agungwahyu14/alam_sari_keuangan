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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('user_id');
            $table->string('agent_name')->nullable()->after('agent_id');
            $table->decimal('agent_commission', 15, 2)->default(0)->after('agent_name');
            $table->decimal('commission_rate', 5, 2)->default(5.00)->after('agent_commission')->comment('Komisi agen dalam persen (default 5%)');
            
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn(['agent_id', 'agent_name', 'agent_commission', 'commission_rate']);
        });
    }
};
