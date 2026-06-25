<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->integer('gas_value');       // nilai raw ADC dari MQ2
            $table->float('gas_ppm')->nullable(); // konversi ke PPM (opsional)
            $table->string('status');            // AMAN / WASPADA / BAHAYA
            $table->boolean('apar_aktif')->default(false);
            $table->boolean('buzzer_aktif')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
