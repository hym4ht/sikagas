<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apar_commands', function (Blueprint $table) {
            $table->id();
            $table->enum('command', ['ON', 'OFF'])->default('OFF');
            $table->string('source')->default('web'); // 'web' atau 'sensor'
            $table->timestamps();
        });

        // Insert default command = OFF
        DB::table('apar_commands')->insert([
            'command'    => 'OFF',
            'source'     => 'system',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('apar_commands');
    }
};
