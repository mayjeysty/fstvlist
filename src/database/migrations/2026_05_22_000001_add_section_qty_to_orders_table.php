<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('event_id')->constrained('venue_sections')->nullOnDelete();
            $table->unsignedInteger('qty')->default(1)->after('section_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\VenueSection::class, 'section_id');
            $table->dropColumn(['section_id', 'qty']);
        });
    }
};
