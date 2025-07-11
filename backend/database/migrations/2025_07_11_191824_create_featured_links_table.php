<?php

use App\Models\Page;
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
        Schema::create('featured_links', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Page::class)
                ->constrained()
                ->cascadeOnDelete()
                ->comment('Foreign key to the pages table, linking featured links to specific pages. Deletes featured links if the associated page is deleted.');
            $table->string('name')
                ->comment('Internal name for administrators to identify the featured link quickly.');
            $table->text('label')
                ->comment('The public-facing text or title displayed for the featured link on the frontend.');
            $table->text('url')
                ->comment('The URL that the featured link points to.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_links');
    }
};
