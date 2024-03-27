@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'kategori')
@section('content_header_title', 'Kategori')
@section('content_header_subtitle', 'Create')

{{-- Content body: main page content --}}
@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Buat kategori baru</h3>
        </div>
    </div>

    <form method="post" action="../kategori">
        @csrf <!-- Tambahkan ini untuk menambahkan token CSRF -->
        <div class="card-body">
            <div class="form-group">
                <label for="kodeKategori">Kode Kategori</label>
                <input type="text" class="form-control" id="kodeKategori" name="kategori_kode" placeholder="">
            </div>
            <div class="form-group">
                <label for="namaKategori">Nama Kategori</label>
                <input type="text" class="form-control" id="namaKategori" name="kategori_nama" placeholder="">
            </div>
        </div>
    
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    
</div>
@endsection







