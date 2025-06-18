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
        Schema::create('detail_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Relasi ke tabel items
            $table->string('kode_barang')->unique(); // Kode barang unik
            $table->enum('status_pinjaman', ['Tersedia','Tidak Tersedia'])->default('Tersedia'); // Status pinjaman, default belum di pinjam
            $table->enum('kondisi_barang', ['Normal', 'Rusak'])->default('Normal'); // Kondisi barang, default Normal
            $table->text('deskripsi')->nullable(); // Deskripsi barang (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_items');
    }
};
