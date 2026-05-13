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
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('exclusive')->default(false)->after('date');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('exclusive')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('exclusive');
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('exclusive');
        });
    }
};
