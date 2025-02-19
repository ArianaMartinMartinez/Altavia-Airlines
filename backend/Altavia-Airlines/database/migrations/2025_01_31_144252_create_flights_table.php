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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->float('price');
            $table->unsignedBigInteger('airplane_id');
            $table->unsignedBigInteger('departure_id');
            $table->unsignedBigInteger('arrival_id');
            $table->timestamps();

            $table->foreign('airplane_id')->references('id')->on('airplanes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('departure_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('arrival_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
