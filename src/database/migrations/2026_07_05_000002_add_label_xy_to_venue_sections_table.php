<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venue_sections', function (Blueprint $table) {
            $table->decimal('label_x', 5, 1)->nullable()->after('path_koordinat');
            $table->decimal('label_y', 5, 1)->nullable()->after('label_x');
        });
    }

    public function down(): void
    {
        Schema::table('venue_sections', function (Blueprint $table) {
            $table->dropColumn(['label_x', 'label_y']);
        });
    }
};
