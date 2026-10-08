<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('donors', 'medical_history')) {
            Schema::table('donors', function (Blueprint $table) {
                $table->text('medical_history')->nullable()->after('address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('donors', 'medical_history')) {
            Schema::table('donors', function (Blueprint $table) { $table->dropColumn('medical_history'); });
        }
    }
};
