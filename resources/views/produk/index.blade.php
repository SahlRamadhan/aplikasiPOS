@extends('layouts.main')

@section('judul')
    <h3>Daftar Produk</h3>
@endsection
@section('isi')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success btn-xs btn-flat" onclick="addForm('{{ route('produk.store') }}')"><i
                    class="fa fa-plus-circle"></i>
                Tambah</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-md" id="mytable">
                    <thead>
                        <tr>
                            <th width= "5%">No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@includeif('produk.form')
@push('scripts')
    <script>
        let mytable;

        $(function() {
            mytable = $('#mytable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('produk.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#modalForm').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modalForm form').attr('action'), $('#modalForm form').serialize())
                        .done((response) => {
                            $('#modalForm').modal('hide');
                            mytable.ajax.reload();
                            Swal.fire("Sukses!", "Data berhasil disimpan.", "success");
                        })
                        .fail((errors) => {
                            Swal.fire("Oops!", "Tidak dapat menyimpan data.", "error");
                            return;
                        });
                }
            });
        })

        function addForm(url) {
            $('#modalForm').modal('show');
            $('#modalForm .modal-title').text('Tambah Produk');

            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('post');
            $('#modalForm [name=nama_produk]').focus();
        }

        function editForm(url) {
            $('#modalForm').modal('show');
            $('#modalForm .modal-title').text('Edit Produk');

            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('put');
            $('#modalForm [name=nama_produk]').focus();

            $.get(url)
                .done((response) => {
                    $('#modalForm [name=nama_produk]').val(response.nama_produk);
                    $('#modalForm [name=harga_jual]').val(response.harga_jual);
                    $('#modalForm [name=stok]').val(response.stok);

                })
                .fail((errors) => {
                    Swal.fire("Oops!", "Tidak dapat menampilkan data!!", "error");
                    return;
                });
        }

        function deleteData(url) {
            // Menggantikan perintah confirm dengan SweetAlert
            Swal.fire({
                title: 'Yakin ingin menghapus data terpilih?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Melakukan penghapusan dengan token CSRF
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            // Refresh tabel setelah penghapusan berhasil
                            mytable.ajax.reload();
                            Swal.fire('Terhapus!', 'Data telah dihapus.', 'success');
                        })
                        .fail((errors) => {
                            Swal.fire('Error!', 'Tidak dapat menghapus data.', 'error');
                        });
                }
            });
        }
    </script>
@endpush
