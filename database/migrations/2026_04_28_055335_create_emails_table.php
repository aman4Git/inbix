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
        Schema::create('emails', function (Blueprint $table)
        {
            $table->id();
            $table->string('from_email')->nullable();
            $table->string('subject');
            $table->text('body');

            $table->text('ai_response')->nullable();

            $table->enum('status', [
                'received',
                'processing',
                'responded',
                'failed'
            ])->default('received');

            $table->string('provider')->nullable();
            $table->integer('tokens_used')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
