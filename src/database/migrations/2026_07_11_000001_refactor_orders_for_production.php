<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");
        DB::table('orders')->update(['status' => 'pending']);
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','paid','cancelled','expired') DEFAULT 'pending'");

        DB::table('orders')->whereNotNull('booking_deadline')->update(['booking_deadline' => null]);
        DB::table('orders')->whereNotNull('waiting_number')->update(['waiting_number' => null]);

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('gross_amount')->nullable()->after('total_price');
            $table->dateTime('settlement_time')->nullable()->after('gross_amount');
            $table->string('fraud_status')->nullable()->after('settlement_time');
            $table->string('transaction_status')->nullable()->after('fraud_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['gross_amount', 'settlement_time', 'fraud_status', 'transaction_status']);
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(255) DEFAULT 'waiting'");
        DB::table('orders')->update(['status' => 'waiting']);
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('waiting','reserved','waiting_payment','paid','cancelled','expired') DEFAULT 'waiting'");
    }
};
