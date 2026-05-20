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
        Schema::create('show_streaming_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('external_id')->unique();
            $table->string('hashid');
            $table->string('type')->nullable();
            $table->json('lang')->nullable();
            $table->string('url');
            $table->string('source_name')->nullable();
            $table->string('source_image')->nullable();
            $table->string('source_slug')->nullable();
            $table->boolean('source_premium')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('show_streaming_sources');
    }
};
