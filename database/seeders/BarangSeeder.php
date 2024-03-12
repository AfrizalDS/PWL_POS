<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'barang_kode' => 'BRG01', 'kategori_id' => 1, 'barang_nama' => 'Alat Pel', 'harga_beli' => 40000, 'harga_jual' => 42000],
            ['barang_id' => 2, 'barang_kode' => 'BRG02', 'kategori_id' => 1, 'barang_nama' => 'Sikat Lantai', 'harga_beli' => 6000, 'harga_jual' => 10000],
            ['barang_id' => 3, 'barang_kode' => 'BRG03', 'kategori_id' => 1, 'barang_nama' => 'Kemoceng', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['barang_id' => 4, 'barang_kode' => 'BRG04', 'kategori_id' => 2, 'barang_nama' => 'Piatoz', 'harga_beli' => 8000, 'harga_jual' => 12000],
            ['barang_id' => 5, 'barang_kode' => 'BRG05', 'kategori_id' => 2, 'barang_nama' => 'Kanzler Chicken Nugget', 'harga_beli' => 35000, 'harga_jual' => 45000],
            ['barang_id' => 6, 'barang_kode' => 'BRG06', 'kategori_id' => 3, 'barang_nama' => 'Fanta 500 ml', 'harga_beli' => 5000, 'harga_jual' => 7000],
            ['barang_id' => 7, 'barang_kode' => 'BRG07', 'kategori_id' => 3, 'barang_nama' => 'Teh Pucuk 250 ml', 'harga_beli' => 2300, 'harga_jual' => 3000],
            ['barang_id' => 8, 'barang_kode' => 'BRG08', 'kategori_id' => 3, 'barang_nama' => 'Tebs', 'harga_beli' => 6000, 'harga_jual' => 9000],
            ['barang_id' => 9, 'barang_kode' => 'BRG09', 'kategori_id' => 4, 'barang_nama' => 'Sabun Cusson Baby', 'harga_beli' => 2500, 'harga_jual' => 3500],
            ['barang_id' => 10, 'barang_kode' => 'BRG10', 'kategori_id' => 5, 'barang_nama' => 'Lipstick make over', 'harga_beli' => 50000, 'harga_jual' => 70000],
        ];

        DB::table('m_barang')->insert($data);
    }
}