<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_requests', 'donation_date')) {
                $table->date('donation_date')->nullable();
            }
            if (!Schema::hasColumn('blood_requests', 'donation_time')) {
                $table->time('donation_time')->nullable();
            }
            if (!Schema::hasColumn('blood_requests', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            foreach (['donation_date', 'donation_time', 'completed_at'] as $column) {
                if (Schema::hasColumn('blood_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
