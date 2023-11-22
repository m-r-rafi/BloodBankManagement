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
        Schema::table('receive_bloods', function (Blueprint $table) {
            $table->unsignedBigInteger('BloodId');
            $table->foreign('BloodId')->references('id')->on('blood_banks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receive_bloods', function (Blueprint $table) {
            //
        });
    }
};
