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
        Schema::create('saveds', function (Blueprint $table) {
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('post_id')->constrained('posts');
            $table->timestamp('saved_at')->useCurrent();

            $table->primary(['usuario_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saveds');
    }
};
