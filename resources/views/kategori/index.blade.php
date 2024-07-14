@extends('layouts.main')

@section('judul')
    <h3>Daftar Produk</h3>
@endsection
@section('isi')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success btn-xs btn-flat" onclick="addForm('{{ route('kategori.store') }}')"><i
                    class="fa fa-plus-circle"></i>
                Tambah</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-md" id="mytable">
                    <thead>
                        <tr>
                            <th width= "5%">No</th>
                            <th>Kode Kategori</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@includeif('kategori.form')
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
                    url: '{{ route('kategori.data') }}',
                },
                columns: [{
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
                        data: 'keterangan'
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
            $('#modalForm .modal-title').text('Tambah Kategori');

            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('post');
            $('#modalForm [name=nama]').focus();
        }

        function editForm(url) {
            $('#modalForm').modal('show');
            $('#modalForm .modal-title').text('Edit Kategori');

            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('put');
            $('#modalForm [name=nama]').focus();

            $.get(url)
                .done((response) => {
                    $('#modalForm [name=nama]').val(response.nama);
                    $('#modalForm [name=keterangan]').val(response.keterangan);

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
