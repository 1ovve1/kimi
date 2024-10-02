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
        Schema::create('openai_chat_memories', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('chat_message_id')->index();

            $table->foreign('chat_message_id')->references('id')->on('chat_messages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('openai_chat_memories');
    }
};