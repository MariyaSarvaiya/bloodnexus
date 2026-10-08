<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'security_blocked')) {
                $table->boolean('security_blocked')->default(false)->after('locked_until');
            }
            if (!Schema::hasColumn('users', 'security_block_reason')) {
                $table->string('security_block_reason', 255)->nullable()->after('security_blocked');
            }
            if (!Schema::hasColumn('users', 'security_blocked_at')) {
                $table->timestamp('security_blocked_at')->nullable()->after('security_block_reason');
            }
            if (!Schema::hasColumn('users', 'last_login_warning_at')) {
                $table->timestamp('last_login_warning_at')->nullable()->after('security_blocked_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['last_login_warning_at', 'security_blocked_at', 'security_block_reason', 'security_blocked'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
