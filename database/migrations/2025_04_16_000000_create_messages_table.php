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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade'); // A qué chat pertenece
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién envió el mensaje
            $table->text('content')->nullable(); // Contenido del mensaje (texto)
            $table->string('image_path')->nullable(); // Ruta de la imagen, si es una imagen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};