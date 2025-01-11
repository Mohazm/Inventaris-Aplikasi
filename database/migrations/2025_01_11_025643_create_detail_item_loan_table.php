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
        Schema::create('detail_item_loan', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel loans_items
            $table->foreignId('loans_item_id')
                ->constrained('loans_items')
                ->onDelete('cascade');

            // Foreign key ke tabel detail_items
            $table->foreignId('detail_item_id')
                ->constrained('detail_items')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_item_loan');
    }
};
