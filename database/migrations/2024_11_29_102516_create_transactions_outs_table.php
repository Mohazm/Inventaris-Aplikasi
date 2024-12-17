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
        Schema::create('transactions_outs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_keluar');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('tujuan_keluar');
            $table->string('jumlah');
            $table->string('kode_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_outs');
    }
};
