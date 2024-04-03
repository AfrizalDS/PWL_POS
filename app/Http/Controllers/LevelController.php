<?php

namespace App\Http\Controllers;

use App\DataTables\LevelDataTable;
use App\Models\LevelModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index(LevelDataTable $dataTable)
    {
        return $dataTable->render('level.index');
    }

    public function create()
    {
        return view('level.create');
    }

    public function store(Request $request): RedirectResponse
    {   
        //dd($request->all());
        $validated = $request->validate([
            'kodeLevel' => 'bail|required|unique:level,level_kode',
            'namaLevel' => 'required',
        ]);

        LevelModel::create([
            'level_kode' => $validated['kodeLevel'],
            'level_nama' => $validated['namaLevel'],
        ]);

        return redirect('/level');
    }

    public function edit($id){
        $level = LevelModel::find($id);
        return view('level.edit', ['data' => $level]);
    }

    public function update(Request $request, $id){
        $level = LevelModel::find($id);
        $level->level_kode = $request->kodeLevel;
        $level->level_nama = $request->namaLevel;
        $level->save();
        return redirect('/level');    
    }

    public function destroy($id) {
        LevelModel::find($id)->delete();

        return redirect('/level');
    }
}