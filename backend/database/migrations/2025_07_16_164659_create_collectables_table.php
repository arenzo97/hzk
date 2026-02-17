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
        Schema::create('collectables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')
                  ->constrained('collections')
                  ->onDelete('cascade');
            $table->morphs('collectable');
            $table->unique(['collection_id', 'collectable_id', 'collectable_type'], 'collectable_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectables');
    }
};
