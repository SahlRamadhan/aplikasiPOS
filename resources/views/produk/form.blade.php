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
                        <label for="kode_Produk" class="form-label">Nama Produk</label>
                        <input type="text" name="kode_Produk" id="kode_Produk" class="form-control" required
                            autofocus>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group ">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" required
                            autofocus>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group ">
                        <label for="harga_jual" class="form-label">Harga</label>
                        <input type="number" name="harga_jual" id="harga_jual" class="form-control" required>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group ">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" name="stok" id="stok" class="form-control" required
                            value="0">
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
