<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'suspended_until')) {
                $table->timestamp('suspended_until')->nullable()->after('is_suspended');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suspended_until')) $table->dropColumn('suspended_until');
            if (Schema::hasColumn('users', 'is_suspended')) $table->dropColumn('is_suspended');
        });
    }
};
