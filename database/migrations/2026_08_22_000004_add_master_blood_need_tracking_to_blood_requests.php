<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_requests', 'parent_request_id')) {
                $table->unsignedBigInteger('parent_request_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('blood_requests', 'units_fulfilled')) {
                $table->unsignedInteger('units_fulfilled')->default(0)->after('units');
            }
            if (!Schema::hasColumn('blood_requests', 'fulfilled_at')) {
                $table->timestamp('fulfilled_at')->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('blood_requests', 'closure_reason')) {
                $table->string('closure_reason')->nullable()->after('fulfilled_at');
            }
        });

        // Do not add a database-level self foreign key here. Existing local
        // BloodNexus databases can contain legacy rows and should migrate safely.
    }

    public function down(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            foreach (['closure_reason', 'fulfilled_at', 'units_fulfilled', 'parent_request_id'] as $column) {
                if (Schema::hasColumn('blood_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
