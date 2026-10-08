<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_requests', 'request_for')) {
                $table->string('request_for', 20)->default('relative')->after('requester_type');
            }
            if (!Schema::hasColumn('blood_requests', 'area')) {
                $table->string('area', 120)->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            if (Schema::hasColumn('blood_requests', 'request_for')) $table->dropColumn('request_for');
            if (Schema::hasColumn('blood_requests', 'area')) $table->dropColumn('area');
        });
    }
};
