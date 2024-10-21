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
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id(); // Esto creará la columna `id` como AUTO_INCREMENT
            $table->unsignedBigInteger('request_id')->nullable(); // Se usa unsignedBigInteger para id relacionados
            $table->string('status', 20)->nullable(); // Columna `status` con un límite de 20 caracteres
            $table->integer('http_code')->nullable(); // Columna `http_code`
            $table->text('response')->nullable(); // Columna `response`
            $table->timestamp('execution_time')->nullable()->default(DB::raw('CURRENT_TIMESTAMP')); // Columna `execution_time` con valor por defecto de CURRENT_TIMESTAMP
            $table->timestamps(); // Agrega created_at y updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
