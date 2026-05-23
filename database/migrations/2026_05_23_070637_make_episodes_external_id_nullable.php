<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->bigInteger('external_id')->unsigned()->nullable()->change();
            $table->string('hashid')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->bigInteger('external_id')->unsigned()->nullable(false)->change();
            $table->string('hashid')->nullable(false)->change();
        });
    }
};
