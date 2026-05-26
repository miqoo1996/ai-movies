<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->unsignedInteger('turkflix_id')->nullable()->unique()->after('external_id');
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->unsignedInteger('turkflix_item_id')->nullable()->unique()->after('external_id');
        });
    }

    public function down(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn('turkflix_id');
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->dropColumn('turkflix_item_id');
        });
    }
};
