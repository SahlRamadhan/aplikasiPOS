 <!-- Modal Form Karyawan -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Tambah Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalKaryawanForm" action="{{ route('api_native_hrd.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <div class="form-group">
                            <label for="edit_id">ID </label>
                            <input type="text" class="form-control" id="edit_id" name="id" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_nama">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_telepon">Telepon</label>
                            <input type="text" class="form-control" id="edit_telepon" name="telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_alamat">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>