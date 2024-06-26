@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form action="{{ url('penjualan') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Pembeli</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="pembeli" name="pembeli"
                            value="{{ old('pembeli') }}" required>
                        @error('pembeli')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Kode Penjualan</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode"
                            value="{{ $kodePenjualan }}" required readonly>
                        @error('penjualan_kode')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Tanggal Penjualan</label>
                    <div class="col-11">
                        <input type="date" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal"
                            value="{{ date('Y-m-d') }}" required>
                        @error('penjualan_tanggal')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Petugas</label>
                    <div class="col-11">
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">- Pilih Petugas -</option>
                            @foreach ($user as $item)
                                <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Input untuk detail penjualan -->
                <div class="form-group">
                    <label for="detail">Detail Penjualan:</label>
                    <table class="table table-bordered" id="detail">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                                <th>QTY</th>
                                <th>Jumlah</th>
                                <th>Aksi</th> <!-- Tambah kolom untuk tombol Hapus Barang -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="barang_id[]" class="form-control barang" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach ($barang as $item)
                                            <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control harga" name="harga[]" readonly></td>
                                <td><input type="number" class="form-control jumlah" name="jumlah[]" required></td>
                                <td><input type="number" class="form-control total_harga" name="total_harga[]" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm hapusBarang">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" id="tambahBarang">Tambah Barang</button>
                </div>
                <div class="form-group row">
                    <div class="col-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default ml-1">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Ketika tombol "Tambah Barang" diklik
            $('#tambahBarang').click(function() {
                // Tambahkan baris baru ke tabel detail penjualan
                $('#detail tbody').append('<tr>' +
                    '<td><select name="barang_id[]" class="form-control barang" required><option value="">Pilih Barang</option>@foreach ($barang as $item)<option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>@endforeach</select></td>' +
                    '<td><input type="text" class="form-control harga" name="harga[]" readonly></td>' +
                    '<td><input type="number" class="form-control jumlah" name="jumlah[]" required></td>' +
                    '<td><input type="number" class="form-control total_harga" name="total_harga[]" readonly></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm hapusBarang">Hapus</button></td>' +
                    '</tr>');
            });

            // Ketika tombol "Hapus Barang" diklik
            $('body').on('click', '.hapusBarang', function() {
                $(this).closest('tr').remove(); // Hapus baris
                updateTotalHarga(); // Perbarui total harga setelah menghapus
                resetIndexes(); // Atur ulang indeks setelah menghapus baris
            });

            // Ketika dropdown barang dipilih, perbarui harga secara otomatis
            $('body').on('change', 'select[name="barang_id[]"]', function() {
                var selectedId = $(this).val();
                var hargaInput = $(this).closest('tr').find('.harga');
                // Lakukan AJAX request untuk mendapatkan harga barang berdasarkan ID yang dipilih
                $.ajax({
                    url: '{{ url('penjualan/get-harga') }}/' + selectedId,
                    type: 'GET',
                    success: function(response) {
                        hargaInput.val(response.harga_jual);
                    },
                    error: function() {
                        hargaInput.val('');
                    }
                });
            });

            $('body').on('input', 'input[name="jumlah[]"]', function() {
                updateTotalHarga();
            });

            // Fungsi untuk memperbarui total harga
            function updateTotalHarga() {
                $('tbody tr').each(function() {
                    var hargaPerUnit = parseFloat($(this).find('.harga').val());
                    var jumlah = parseFloat($(this).find('.jumlah').val());
                    var totalHarga = hargaPerUnit * jumlah;
                    $(this).find('.total_harga').val(totalHarga.toFixed(2));
                });
            }

            // Fungsi untuk mengatur ulang indeks baris setelah penghapusan
            function resetIndexes() {
                $('tbody tr').each(function(index) {
                    $(this).find('.barang').attr('name', 'barang_id[' + index + ']');
                    $(this).find('.harga').attr('name', 'harga[' + index + ']');
                    $(this).find('.jumlah').attr('name', 'jumlah[' + index + ']');
                    $(this).find('.total_harga').attr('name', 'total_harga[' + index + ']');
                });
            }
        });
    </script>
@endpush
