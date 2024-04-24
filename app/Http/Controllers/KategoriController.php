<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\KategoriDataTable;
use App\Models\KategoriModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StorePostRequest;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
  //Jobsheet 7
  public function index()
  {
      $breadcrumb = (object) [
          'title' => 'Daftar Kategori',
          'list' => ['Home', 'Kategori']
      ];

      $page = (object) [
          'title' => 'Daftar Kategori yang terdaftar dalam sistem'
      ];

      $activeMenu = 'kategori'; // set menu yang sedang aktif
     

      return view('kategori.index2', [
          'breadcrumb' => $breadcrumb,
          'page' => $page,
          'activeMenu' => $activeMenu,
      ]);
  } 
  
  public function list(Request $request)
  {
      $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

      return DataTables::of($kategoris)
          ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
          ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
              $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
              $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
              $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">'
                  . csrf_field() . method_field('DELETE') .
                  '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
              return $btn;
          })
          ->rawColumns(['aksi']) 
          ->make(true);
  }
  
  public function create()
  {
      $breadcrumb = (object) [
          'title' => 'Tambah Kategori',
          'list'  => ['Home', 'Kategori', 'Tambah']
      ];

      $page = (object) [
          'title' => 'Tambah kategori baru'
      ];

      $activeMenu = 'kategori'; // set menu yang sedang aktif

      return view('kategori.create2', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
  }
  
  public function store(Request $request)
  {
      $request->validate([
          'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
          'kategori_nama' => 'required|string|max:100',
      ]);

      
      KategoriModel::create([
          'kategori_kode' => $request->kategori_kode,
          'kategori_nama' => $request->kategori_nama,
      ]);
      return redirect('/kategori')->with('success', 'Data user berhasil disimpan');
  }

  public function show(String $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail kategori',
            'list'  => ['Home', 'kategori', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori';
        return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' =>  $kategori, 'activeMenu' => $activeMenu]);
    }

    
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list'  => ['Home', 'Kategori', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Kategori',
        ];
        $activeMenu = 'kategori'; // set menu yang sedang aktif
        return view('kategori.edit2', ['breadcrumb' => $breadcrumb, 'page' => $page,  'kategori' =>  $kategori, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode,'. $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100',          // kategori_id harus diisi dan berupa angka
        ]);

        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }
    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {     
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id);  

            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdadpat tabel lain yang terkait data ini');
        }
    }

  

}



//Jobsheet 6

// public function index(KategoriDataTable $dataTable)
// {
//    return $dataTable->render('kategori.index'); 
    
// }

// public  function create()
// {
//     return view('kategori.create');
// }


// public function store(StorePostRequest $request): RedirectResponse
// {
//     $validated = $request->validated();

//     $validated = $request->safe()->only(['kategori_kode', 'kategori_nama']);
//     $validated = $request->safe()->except(['kategori_kode', 'kategori_nama']);

//     KategoriModel::create([
//         'kategori_kode' => $request->kodeKategori,
//         'kategori_nama' => $request->namaKategori,
//     ]);
//     return redirect('/kategori');
// }



// public function edit($id){
//     $kategori = KategoriModel::find($id);
//     return view('kategori.edit', ['data' => $kategori]);
// }

// public function update(Request $request, $id){
//     $kategori = KategoriModel::find($id);
//     $kategori->kategori_kode = $request->kategori_kode;
//     $kategori->kategori_nama = $request->kategori_nama;
//     $kategori->save();
//     return redirect('/kategori');    
// }

// public function delete($id)
// {
//     KategoriModel::find($id)->delete();
//     return redirect('/kategori');
// }


// $data = [
        //     'kategori_kode' => 'SNK',
        //     'kategori_nama' => 'Snack\Makanan Ringan',
        //     'created_at' => now()
        // ];
        // DB::table('m_kategori')->insert($data);
        // return 'Insert data baru berhasil';

        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
        // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row.' baris';

        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        // return 'Delete data berhasil. Jumlah data yang dihapus: ' .$row.'baris';

        // $data = DB::table('m_kategori')->get();
        // return view('kategori', ['data' => $data]);