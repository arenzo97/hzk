<?php

use App\Models\User;
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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete()
                ->comment('Author of the page');
            $table->string('title')
                ->comment('Title of the page');
            $table->string('slug')
                ->unique()
                ->comment('URL-friendly slug');
            $table->longText('content')
                ->comment('Main rich text content of the page')
                ->nullable();
            $table->boolean('published')
                ->default(false)
                ->comment('Whether the page is publicly visible');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
