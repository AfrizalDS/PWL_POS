<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\m_barang;
use App\Models\UserModel;
use App\Models\PenjualanModel;
use App\Models\penjualanDetailModel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\StokModel;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan'],
        ];
        $page = (object) [
            'title' => 'Daftar Penjualan yang terdaftar dalam sistem',
        ];

        $activeMenu = 'penjualan';

        $barang = BarangModel::all();

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang]);
    }
    
    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')->with('user')
            ->withCount(['penjualan_detail as total_harga' => function ($query) {
                $query->select(DB::raw('SUM(harga) as total_harga'));
            }]);

        if ($request->barang_id) {
            $penjualans->whereHas('penjualan_detail', function ($query) use ($request) {
                $query->where('barang_id', $request->barang_id);
            });
        }

        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm mr-1">Detail</a>';
                $btn .= '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '" class="btn btn-warning btn-sm mr-1">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/penjualan/' . $penjualan->penjualan_id) . '">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm mr-1" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah Penjualan Baru'
        ];
    
        $barang = BarangModel::all(); 
        $user = UserModel::all(); 
    
        // Inisialisasi kode penjualan
        $kodePenjualan = $this->generateKodePenjualan();
    
        $activeMenu = 'penjualan';
        return view('penjualan.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'barang' => $barang, 
            'user' => $user, 
            'activeMenu' => $activeMenu,
            'kodePenjualan' => $kodePenjualan, // Kirim variabel kodePenjualan ke tampilan
        ]);
    }
    

    private function generateKodePenjualan()
    {
        // Mendapatkan jumlah penjualan saat ini
        $jumlahPenjualan = PenjualanModel::count();

        // Menambahkan 1 ke jumlah penjualan dan memformatnya dengan dua digit
        $nomorUrut = str_pad($jumlahPenjualan + 1, 2, '0', STR_PAD_LEFT);

        // Menghasilkan kode penjualan dengan format 'PJ' diikuti dua digit nomor urut
        $kodePenjualan = 'PJ' . $nomorUrut;

        return $kodePenjualan;
    }
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'bail|required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:100|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array',
            'total_harga' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        foreach ($request->barang_id as $key => $barang_id) {
            // Cek stok yang tersedia
            $stok = StokModel::where('barang_id', $barang_id)->value('stok_jumlah');
            $nama_barang = BarangModel::where('barang_id', $barang_id)->value('barang_nama');
            $requestedQuantity = $request->jumlah[$key];

            if ($stok < $requestedQuantity) {
                // Jika jumlah yang diminta melebihi stok yang tersedia, kembalikan pengguna ke halaman sebelumnya
                // (halaman tambah penjualan) dengan pesan kesalahan yang sesuai
                return redirect()->back()->withInput()->withErrors(['stok' => 'Stok ' . $nama_barang . ' tidak mencukupi untuk penjualan ini']);
            }
        }
        
        $penjualan = PenjualanModel::create([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_kode' => $request->penjualan_kode,
            'penjualan_tanggal' => $request->penjualan_tanggal
        ]);

        $jumlahs = $request->jumlah;
        foreach ($request->barang_id as $key => $barangId) {
            penjualanDetailModel::create([
                'penjualan_id' => $penjualan->penjualan_id,
                'barang_id' => $barangId,
                'harga' => $request->total_harga[$key],
                'jumlah' => $request->jumlah[$key]
            ]);
            // Dapatkan stok sebelum dikurangi
            $stok_sebelum = (StokModel::where('barang_id', $barang_id)->value('stok_jumlah'));
            // Kurangi stok
            $stok_sesudah = $stok_sebelum - $jumlahs[$key];
            // Perbarui stok dan informasi lainnya
            StokModel::where('barang_id', $barang_id)->update([
                'stok_jumlah' => $stok_sesudah,
                'stok_tanggal' => date('Y-m-d'),
                'user_id' => $request->user_id // Perbarui kembali user_id dengan nilai yang benar
            ]);
        }

        return redirect('/penjualan')->with('success', 'Data Penjualan berhasil disimpan');
    }


    public function getHarga($id)
    {
        $barang = BarangModel::findOrFail($id); 

        return response()->json([
            'harga_jual' => $barang->harga_jual,
        ]);
    }

    
    public function show(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $penjualanDetail = penjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->get();

        $breadcrumb = (object)[
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', ['penjualan' => $penjualan, 'penjualanDetail' => $penjualanDetail, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $barang = BarangModel::all();
        $user = UserModel::all();
        $penjualanDetail = penjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->get();

        $breadcrumb = (object)[
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'penjualan' => $penjualan, 'penjualanDetail' => $penjualanDetail, 'barang' => $barang, 'user' => $user]);
    }
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'bail|required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:100|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array',
            'total_harga' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        $penjualan = PenjualanModel::find($id);
        $penjualan->update([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_kode' => $request->penjualan_kode,
            'penjualan_tanggal' => $request->penjualan_tanggal
        ]);

        PenjualanDetailModel::where('penjualan_id', $id)->delete();

        foreach ($request->barang_id as $index => $barang_id) {
            penjualanDetailModel::create([
                'penjualan_id' => $id,
                'barang_id' => $barang_id,
                'jumlah' => $request->jumlah[$index],
                'harga' => $request->total_harga[$index],
            ]);
        }

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            $penjualanDetails = PenjualanDetailModel::where('penjualan_id', $check->penjualan_id)->get();

            foreach ($penjualanDetails as $detail) {
                $detail->delete();
            }

            PenjualanModel::destroy($id);

            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\illuminate\Database\QueryException $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    
}