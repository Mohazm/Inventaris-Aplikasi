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
        Schema::create('returns_items', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('loans_item_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_pengembalian');
            $table->string('kondisi_barang');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns_items');
    }
};
