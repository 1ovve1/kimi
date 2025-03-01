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
        Schema::table('openai_chat_memories', function (Blueprint $table) {
            $table->integer('tokens_count')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('openai_chat_memories', function (Blueprint $table) {
            $table->dropColumn('tokens_count');
        });
    }
};
