<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | USER RELATIONSHIP
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DONOR INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('email');

            $table->string('phone');

            $table->string('blood_group', 10);

            $table->string('city');

            $table->text('address')->nullable();

            $table->date('last_donation_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | AVAILABILITY
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_available')
                ->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ONE USER = ONE DONOR PROFILE
            |--------------------------------------------------------------------------
            */

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};