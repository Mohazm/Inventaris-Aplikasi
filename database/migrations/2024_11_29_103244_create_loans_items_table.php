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
        Schema::create('loans_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->foreignId('tendik_id')->constrained()->onDelete('restrict');
            $table->integer('jumlah_pinjam');
            $table->string('tujuan_peminjaman'); 
            $table->enum('status',['loading','dipakai','selesai','ditolak']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans_items');
    }
};
