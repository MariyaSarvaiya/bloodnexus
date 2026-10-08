<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('blood_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_requests', 'requester_type')) {
                $table->string('requester_type', 20)->default('relative')->after('patient_name');
            }
            if (!Schema::hasColumn('blood_requests', 'emergency_mode')) {
                $table->boolean('emergency_mode')->default(false)->after('urgency');
            }
        });
    }
    public function down(): void {
        Schema::table('blood_requests', function (Blueprint $table) {
            if (Schema::hasColumn('blood_requests', 'emergency_mode')) $table->dropColumn('emergency_mode');
            if (Schema::hasColumn('blood_requests', 'requester_type')) $table->dropColumn('requester_type');
        });
    }
};
