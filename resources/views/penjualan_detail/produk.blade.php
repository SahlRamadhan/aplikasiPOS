<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PIlih Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-produk">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td><span class="badge badge-success">{{ $item->id }}</span></td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->harga }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihProduk('{{ $item->id }}', '{{ $item->id }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
