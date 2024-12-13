<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang'); // Nama barang
            $table->foreignId('categories_id')->constrained()->onDelete('restrict'); // Relasi ke kategori
            $table->integer('stock')->default(0); // Stok barang, default 0
            $table->enum('status_pinjaman', ['bisa di pinjam', 'tidak bisa di pinjam']); // Status
            $table->string('photo_barang')->nullable(); // Foto barang (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
