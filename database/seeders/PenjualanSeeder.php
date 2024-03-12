<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'user_id' => 3, 'pembeli' => 'Rizal', 'penjualan_kode' => 'PJ01', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 2, 'user_id' => 3, 'pembeli' => 'Indra', 'penjualan_kode' => 'PJ02', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Avis', 'penjualan_kode' => 'PJ03', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Tyan', 'penjualan_kode' => 'PJ04', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 5, 'user_id' => 3, 'pembeli' => 'Bima', 'penjualan_kode' => 'PJ05', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Galang', 'penjualan_kode' => 'PJ06', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Dwi', 'penjualan_kode' => 'PJ07', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 8, 'user_id' => 3, 'pembeli' => 'Ani', 'penjualan_kode' => 'PJ08', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 9, 'user_id' => 3, 'pembeli' => 'Koko', 'penjualan_kode' => 'PJ09', 'penjualan_tanggal' => '2024-03-10'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Ardi', 'penjualan_kode' => 'PJ10', 'penjualan_tanggal' => '2024-03-10'],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}