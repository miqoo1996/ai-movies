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
        Schema::table('show_images', function (Blueprint $table) {
            $table->string('local_path')->nullable()->after('url');
            $table->string('local_thumb')->nullable()->after('thumb');
        });
    }

    public function down(): void
    {
        Schema::table('show_images', function (Blueprint $table) {
            $table->dropColumn(['local_path', 'local_thumb']);
        });
    }
};
