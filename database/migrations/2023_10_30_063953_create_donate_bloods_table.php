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
        Schema::create('donate_bloods', function (Blueprint $table) {
            $table->id();
            $table->integer('Quantity');
            $table->dateTime('DonatedOn');
//            $table->unsignedBigInteger('UserId');
//            $table->foreign('UserId')->references('id')->on('users');
//            $table->unsignedBigInteger('BloodBankId');
//            $table->foreign('BloodBankId')->references('id')->on('blood_banks');
//            $table->unsignedBigInteger('StatusId');
//            $table->foreign('StatusId')->references('id')->on('status_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donate_bloods');
    }
};
