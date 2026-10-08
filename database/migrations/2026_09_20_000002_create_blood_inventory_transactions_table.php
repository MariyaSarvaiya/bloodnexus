<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('blood_inventory_transactions')) {
            Schema::create('blood_inventory_transactions', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['received', 'issued']);
                $table->string('blood_group', 10);
                $table->unsignedInteger('units')->default(1);
                $table->foreignId('donor_id')->nullable()->constrained('donors')->nullOnDelete();
                $table->foreignId('blood_request_id')->nullable()->constrained('blood_requests')->nullOnDelete();
                $table->timestamp('transaction_at')->index();
                $table->string('source', 80)->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
                $table->index(['type', 'blood_group']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_inventory_transactions');
    }
};
