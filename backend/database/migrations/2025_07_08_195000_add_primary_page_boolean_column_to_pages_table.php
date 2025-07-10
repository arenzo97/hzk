<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('homepage')
                ->default(false)
                ->after('slug');
        });

        // Partial unique index: allow only one homepage=true
        DB::statement('CREATE UNIQUE INDEX unique_homepage_page_only_one_true ON pages (homepage) WHERE homepage = true');
    }

    public function down(): void
    {
        // More resilient (esp. in local dev)
        DB::statement('DROP INDEX IF EXISTS unique_homepage_page_only_one_true');

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('homepage');
        });
    }
};
