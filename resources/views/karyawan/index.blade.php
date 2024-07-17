@extends('layouts.main')

@section('judul')
    <h1>Data Karyawan</h1>
@endsection

@section('isi')
    <div class="container">
        <!-- Tombol Tambah Data -->
        <button class="btn btn-primary mb-3" onclick="addForm('{{ route('api_native_hrd.store') }}')">Tambah Karyawan</button>

        <!-- Tabel Data Karyawan -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Jabatan</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawan as $k)
                    <tr id="row-{{ $k['id'] }}" data-data="{{ json_encode($k) }}">
                        <td>{{ $k['id'] }}</td>
                        <td>{{ $k['id_jabatan'] }}</td>
                        <td>{{ $k['nama'] }}</td>
                        <td>{{ $k['jenis_kelamin'] }}</td>
                        <td>{{ $k['tanggal_lahir'] }}</td>
                        <td>{{ $k['telepon'] }}</td>
                        <td>{{ $k['alamat'] }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning" onclick="editForm('{{ $k['id'] }}')">Edit</button>

                            <!-- Form Delete -->
                            <form action="{{ route('api_native_hrd.destroy', $k['id']) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   
@endsection
@includeIf('karyawan.form')
@push('scripts')
    <script>
        // Fungsi untuk menampilkan modal tambah data
        function addForm(url) {
            $('#modalForm').modal('show');
            $('#modalFormLabel').text('Tambah Karyawan');
            $('#modalKaryawanForm').attr('action', url);
            $('#form_method').val('POST');
            $('#edit_id').val('');
            $('#edit_nama').val('');
            $('#edit_jenis_kelamin').val('laki-laki');
            $('#edit_tanggal_lahir').val('');
            $('#edit_telepon').val('');
            $('#edit_alamat').val('');
        }

        // Fungsi untuk menampilkan modal edit data
        function editForm(id) {
            // Mengambil data dari atribut data di baris tabel
            let rowData = $('#row-' + id).data('data');

            $('#modalForm').modal('show');
            $('#modalFormLabel').text('Edit Karyawan');

            // Mengisi formulir modal edit dengan data yang diperoleh
            $('#modalKaryawanForm').attr('action', "{{ route('api_native_hrd.update', ':id') }}".replace(':id', id));
            $('#form_method').val('PUT');
            $('#edit_id').val(rowData.id).prop('readonly', true);
            $('#edit_nama').val(rowData.nama);
            $('#edit_jenis_kelamin').val(rowData.jenis_kelamin);
            $('#edit_tanggal_lahir').val(rowData.tanggal_lahir);
            $('#edit_telepon').val(rowData.telepon);
            $('#edit_alamat').val(rowData.alamat);
        }
    </script>
@endpush
