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
        Schema::table('shows', function (Blueprint $table) {
            $table->string('seo_title', 255)->nullable()->after('slug');
            $table->string('seo_description', 320)->nullable()->after('seo_title');
            $table->boolean('noindex')->default(false)->after('seo_description');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('seo_title', 255)->nullable()->after('title');
            $table->string('seo_description', 320)->nullable()->after('seo_title');
            $table->boolean('noindex')->default(false)->after('seo_description');
        });
    }

    public function down(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'noindex']);
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'noindex']);
        });
    }
};
