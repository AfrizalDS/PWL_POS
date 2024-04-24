@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($penjualan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Kode</th>
                        <td>{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Petugas</th>
                        <td>{{ $penjualan->user->nama }}</td>
                    </tr>
                </table>
                <br><br><br>
                {{-- <h4 class="font-weight-bold text-uppercase mb-4">Detail Penjualan</h4> --}}
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Harga Barang</th>
                            <th>QTY</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalHarga = 0;
                        @endphp
                        @foreach ($penjualanDetail as $detail)
                            <tr>
                                <td>{{ $detail->detail_id }}</td>
                                <td>{{ $detail->barang->barang_nama }}</td>
                                <td>{{ $detail->harga }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->harga * $detail->jumlah }}</td>
                                @php
                                    $totalHarga += $detail->harga * $detail->jumlah;
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-center"><strong>Total</strong></td>
                            <td class="text-left font-weight-bold">{{ $totalHarga }}</td>
                        </tr>
                    </tbody>
                </table>
            @endempty
            <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush
@push('js')
@endpush
