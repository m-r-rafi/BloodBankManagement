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
        Schema::table('donate_bloods', function (Blueprint $table) {
            $table->unsignedBigInteger('StatusId');
            $table->foreign('StatusId')->references('id')->on('status_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donate_bloods', function (Blueprint $table) {
            //
        });
    }
};