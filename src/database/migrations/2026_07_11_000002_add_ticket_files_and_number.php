<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('ticket_number')->nullable()->unique()->after('ticket_code');
            $table->string('qr_path')->nullable()->after('qr_token');
            $table->string('pdf_path')->nullable()->after('qr_path');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['ticket_number', 'qr_path', 'pdf_path']);
        });
    }
};
