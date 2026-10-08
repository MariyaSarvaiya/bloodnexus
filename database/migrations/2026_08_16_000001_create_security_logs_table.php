<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('incident_id', 40)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_type', 80);
            $table->string('severity', 20)->default('info');
            $table->unsignedInteger('risk_score')->default(0);
            $table->string('ip_address', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('route', 190)->nullable();
            $table->string('method', 12)->nullable();
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['severity', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['event_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
