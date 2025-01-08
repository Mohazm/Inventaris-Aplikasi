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
        Schema::create('loan_item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_item_id')->constrained('loans_items')->onDelete('cascade'); // Relasi ke tabel loans_items
            $table->string('kode_barang');  // Misalnya KR001, KR002, dll.
            $table->string('nama_barang');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_item_details');
    }
};
