<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->foreignId('participante_1')->constrained('users');
            $table->foreignId('participante_2')->constrained('users');
            $table->timestamps();

            $table->primary(['participante_1', 'participante_2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
