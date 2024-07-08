<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalForm" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group ">
                        <label for="nama" class="form-label">Nama Kategori</label>
                        <input type="text" name="nama" id="nama" class="form-control" required
                            autofocus>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group ">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-flat btn-xs"
                        data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success btn-flat btn-xs">Simpan</button>
                </div>
        </form>
    </div>
</div>
</div>
