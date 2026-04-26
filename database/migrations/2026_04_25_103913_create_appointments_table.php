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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('email')->nullable();
            $table->date('date');
            $table->float('total')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('receipt')->nullable();
            $table->string('reference')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function(Blueprint $table){
            $table->unsignedBigInteger('appointment_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function(Blueprint $table){
            $table->dropColumn('appointment_id');
        });

        Schema::dropIfExists('appointments');
    }
};
