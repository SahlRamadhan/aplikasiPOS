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
                <table class="table table-striped " id="mytable">
                    <thead>
                        <tr>
                            <th>No</th>
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
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menyimpan data');
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
    </script>
@endpush
