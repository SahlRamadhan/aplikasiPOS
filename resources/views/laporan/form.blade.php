<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form id="filter-form" action="{{ route('laporan.index') }}" method="get" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Periode Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal_awal" class="col-lg-2 col-form-label">Tanggal Awal</label>
                        <div class="col-lg-10">
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required autofocus
                                value="{{ request('tanggal_awal') }}"
                                style="border-radius: 0 !important;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_akhir" class="col-lg-2 col-form-label">Tanggal Akhir</label>
                        <div class="col-lg-10">
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required
                                value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}"
                                style="border-radius: 0 !important;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="filter_type" class="col-lg-2 col-form-label">Filter Type</label>
                        <div class="col-lg-10">
                            <select name="filter_type" id="filter_type" class="form-control" required>
                                <option value="tanggal" {{ request('filter_type') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                                <option value="penjualan" {{ request('filter_type') == 'penjualan' ? 'selected' : '' }}>Penjualan Detail</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
