<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPeminjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans_items')->onDelete('cascade'); // Relasi ke loans_items
            $table->foreignId('detail_item_id')->constrained('detail_items')->onDelete('cascade'); // Relasi ke detail_items
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Relasi ke items
            $table->string('kondisi_barang')->default('Normal'); // Kondisi barang saat dipinjam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_peminjaman');
    }
}
