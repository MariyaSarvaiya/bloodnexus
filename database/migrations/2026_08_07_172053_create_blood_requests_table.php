<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | BLOOD SEEKER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | ASSIGNED DONOR
            |--------------------------------------------------------------------------
            */

            $table->foreignId('donor_id')
                ->nullable()
                ->constrained('donors')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PATIENT DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string('patient_name');

            $table->string('blood_group');

            $table->string('city');

            $table->string('hospital');

            $table->string('contact');

            $table->string('contact_phone')->nullable();

            /*
            |--------------------------------------------------------------------------
            | BLOOD REQUIREMENT
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('units')
                ->default(1);

            /*
            |--------------------------------------------------------------------------
            | URGENCY
            |--------------------------------------------------------------------------
            */

            $table->enum('urgency', [
                'normal',
                'urgent',
                'critical'
            ])->default('normal');

            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL MESSAGE
            |--------------------------------------------------------------------------
            */

            $table->text('message')
                ->nullable();

            $table->text('reason')->nullable();

            /* Donation audit fields */
            $table->date('donation_date')->nullable();
            $table->time('donation_time')->nullable();
            $table->timestamp('completed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | REQUEST STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'matched',
                'accepted',
                'rejected',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | SEARCH INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('blood_group');
            $table->index('city');
            $table->index('status');
            $table->index('urgency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};