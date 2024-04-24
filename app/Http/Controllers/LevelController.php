<?php

namespace App\Http\Controllers;

use App\DataTables\LevelDataTable;
use App\Models\LevelModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Level;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    //JOBSHEET 7
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif
       

        return view('level.index2', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($levels)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list'  => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level baru'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.create2', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100',
        ]);

        
        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        return redirect('/level')->with('success', 'Data user berhasil disimpan');
    }

    public function show(String $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list'  => ['Home', 'Level', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail Level'
        ];

        $activeMenu = 'level';
        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' =>  $level, 'activeMenu' => $activeMenu]);
    }
    
    public function edit(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit level',
            'list'  => ['Home', 'Level', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Level',
        ];
        $activeMenu = 'level'; // set menu yang sedang aktif
        return view('level.edit2', ['breadcrumb' => $breadcrumb, 'page' => $page,  'level' =>  $level, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|min:3|unique:m_level,level_kode,'. $id . ',level_id',
            'level_nama' => 'required|string|max:100',          // level_id harus diisi dan berupa angka
        ]);

        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }
    
    public function destroy(string $id)
    {
        $check = LevelModel::find($id);
        if (!$check) {     
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            LevelModel::destroy($id);  

            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdadpat tabel lain yang terkait data ini');
        }
    }

    

   
}


//JOBSHEET 6

 // public function index(LevelDataTable $dataTable)
    // {
    //     return $dataTable->render('level.index');
    // }

    // public function create()
    // {
    //     return view('level.create');
    // }

    // public function store(Request $request): RedirectResponse
    // {   
    //     //dd($request->all());
    //     $validated = $request->validate([
    //         'kodeLevel' => 'bail|required|unique:level,level_kode',
    //         'namaLevel' => 'required',
    //     ]);

    //     LevelModel::create([
    //         'level_kode' => $validated['kodeLevel'],
    //         'level_nama' => $validated['namaLevel'],
    //     ]);

    //     return redirect('/level');
    // }

    // public function edit($id){
    //     $level = LevelModel::find($id);
    //     return view('level.edit', ['data' => $level]);
    // }

    // public function update(Request $request, $id){
    //     $level = LevelModel::find($id);
    //     $level->level_kode = $request->kodeLevel;
    //     $level->level_nama = $request->namaLevel;
    //     $level->save();
    //     return redirect('/level');    
    // }

    // public function destroy($id) {
    //     LevelModel::find($id)->delete();

    //     return redirect('/level');
    // }