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
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->id('detail_id');
            $table->unsignedBigInteger('penjualan_id')->index();//indexing untuk foreignkey
            $table->unsignedBigInteger('barang_id')->index();//indexing untuk foreignkey
            $table->integer('harga');
            $table->integer('jumlah');
            $table->timestamps();
            
            //mendefinisikan Foreign key pada kolom level_id mengacu pada kolom level_id di tabel m_level
            $table->foreign('penjualan_id')->references('penjualan_id')->on('t_penjualan');
            $table->foreign('barang_id')->references('barang_id')->on('m_barang');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_penjualan_detail', function (Blueprint $table) {
            //
        });
    }
};