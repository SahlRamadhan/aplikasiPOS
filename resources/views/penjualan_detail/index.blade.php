@extends('layouts.main')

@section('judul')
    Transaksi Penjualan
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-penjualan tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush


@section('isi')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <form class="form-produk">
                        @csrf
                        <div class="form-group row">
                            <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                    <input type="hidden" name="id_produkjadi" id="id_produkjadi">
                                    <input type="text" class="form-control" name="id" id="id">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i
                                            class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-penjualan">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="tampil-bayar bg-info"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                                @csrf
                                <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">
                                <input type="hidden" name="id_pelanggan" id="id_pelanggan">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode_pelanggan" class="col-lg-2 control-label">Pelanggan</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_pelanggan">
                                            <button onclick="tampilpelanggan()" class="btn btn-info btn-flat"
                                                type="button"><i class="fa fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="diskon" id="diskon" class="form-control"
                                            value="{{  $diskon = 0 }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="diterima" class="form-control" name="diterima"
                                            value="{{ $penjualan->diterima ?? 0 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="kembali" name="kembali" class="form-control"
                                            value="0" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-simpan"><i class="fa fa-floppy-o"></i> Simpan
                        Transaksi</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@includeIf('penjualan_detail.produk')
@includeIf('penjualan_detail.pelanggan')

@push('scripts')
    <script>
        let table, table2;

        $(function() {
            // Inisialisasi DataTable untuk menampilkan data penjualan
            table = $('.table-penjualan').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transaksi.data', $id_penjualan) }}', // URL untuk mengambil function data di controller penjualanDetail
                    },
                    columns: [{ // Konfigurasi kolom-kolom tabel
                            data: 'DT_RowIndex',
                            searchable: false,
                            sortable: false
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'harga_jual'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'subtotal'
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm($('#diskon').val()); // Memuat form dengan diskon yang diatur
                    setTimeout(() => {
                        $('#diterima').trigger(
                        'input'); // Memuat form dengan jumlah diterima yang diatur
                    }, 300);
                });
            table2 = $('.table-produk').DataTable(); // Inisialisasi DataTable untuk menampilkan data produk

            // Mengatur event saat input jumlah produk diubah
            $(document).on('input', '.quantity', function() {
                // Mendapatkan ID produk dan jumlah dari input
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                // Memastikan jumlah produk tidak kurang dari 1 atau lebih dari 10000
                if (jumlah < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (jumlah > 10000) {
                    $(this).val(10000);
                    alert('Jumlah tidak boleh lebih dari 10000');
                    return;
                }

                // Mengirim permintaan AJAX untuk mengupdate jumlah produk
                $.post(`{{ url('/transaksi') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        });
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            });

            // Mengatur event saat input diskon diubah
            $(document).on('input', '#diskon', function() {
                // Memuat form dengan diskon yang diatur
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            // Mengatur event saat input jumlah diterima diubah
            $('#diterima').on('input', function() {
                // Memuat form dengan jumlah diterima yang diatur
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($('#diskon').val(), $(this).val());
            }).focus(function() {
                $(this).select();
            });

            // Mengatur event saat tombol simpan ditekan
            $('.btn-simpan').on('click', function() {
                $('.form-penjualan').submit();
            });
        });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, kode) {
            $('#id_produkjadi').val(id);
            $('#id').val(kode);
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            // Mendapatkan ID produk dari input tersembunyi
            let id_produk = $('#id_produkjadi').val();

            // Mengirimkan permintaan AJAX untuk mendapatkan data stok produk
            $.get(`/aplikasi-penjualan/public/produk/${id_produk}`)
                .done(response => {
                    // Memeriksa stok produk
                    if (response.stok <= 10) {
                        // Menampilkan notifikasi jika stok kosong
                        Swal.fire("Oops!", "Maaf, Stok Produk ini habis", "warning");
                    } else {
                        // Jika stok tersedia, lanjutkan dengan menambahkan produk ke transaksi
                        $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
                            .done(response => {
                                $('#kode_produk').focus();
                                table.ajax.reload(() => loadForm($('#diskon').val()));
                            })
                            .fail(errors => {
                                Swal.fire("Error", "Tidak dapat menyimpan data", "error");
                                return;
                            });
                    }
                })
                .fail(errors => {
                    Swal.fire("Error", "Gagal memuat data stok produk", "error");
                    return;
                });
        }

        function tampilpelanggan() {
            $('#modal-pelanggan').modal('show');
        }

        function hidepelanggan() {
            $('#modal-pelanggan').modal('hide');
        }

        function pilihpelanggan(id, kode) {
            $('#id_pelanggan').val(id);
            $('#kode_pelanggan').val(kode);
            hidepelanggan();
        }


        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }

        function loadForm(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            var subtotal = parseInt($('.total').text());
            // Cek jika subtotal melebihi 1000000, atur nilai diskon menjadi 10%
            if (subtotal > 1000000) {
                diskon = 10;
            }

            // Tampilkan nilai diskon pada input #diskon
            $('#diskon').val(diskon);

            $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('Rp.' + response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
