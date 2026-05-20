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
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->string('hashid')->unique();
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('turkish_title')->nullable();
            $table->string('slug')->unique();
            $table->string('status')->nullable();
            $table->string('network')->nullable();
            $table->unsignedSmallInteger('runtime')->nullable();
            $table->date('premiered')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('poster')->nullable();
            $table->decimal('rating', 4, 2)->nullable();
            $table->unsignedInteger('subscribers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
