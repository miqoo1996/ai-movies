<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('show_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('department')->default('cast'); // cast | crew
            $table->string('role')->nullable();            // Director, Writer, etc. (crew only)
            $table->string('character_name')->nullable();  // cast only
            $table->string('character_photo')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['show_id', 'person_id', 'department', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('show_person');
    }
};
