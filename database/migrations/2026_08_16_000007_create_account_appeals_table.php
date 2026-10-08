<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('account_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('reason',120);
            $table->text('message')->nullable();
            $table->string('status',20)->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['status','created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('account_appeals'); }
};
