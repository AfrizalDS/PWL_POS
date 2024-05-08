@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori Kode</th>
                        <th>Kategori Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            var dataKategori = $('#table_kategori').DataTable({
                serverSide: true, // serverSide: true, jika ingin menggunakan server

                ajax: {
                    "url": "{{ url('kategori/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [{
                        data: "DT_RowIndex", // nomor urut dari laravel datatable
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori_kode",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa
                        searchable: true // searchable: true, jika ingin kolom ini bisa

                    },
                    {
                        data: "kategori_nama",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa
                        searchable: true // searchable: true, jika ingin kolom ini bisa

                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa
                        searchable: false // searchable: true, jika ingin kolom ini bisa  
                    }
                ]
            });
        });
    </script>
@endpush