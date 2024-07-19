@extends('layouts.main')

@section('judul')
    Laporan Pendapatan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@section('isi')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i>
                        Ubah Periode</button>
                    <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}?filter_type={{ request('filter_type') }}"
                        target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export
                        PDF</a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-laporan">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Penjualan</th>
                            <th>Pendapatan</th>
                            <th width="15%">Aksi</th>
                        </thead>
                        <tfoot class="total-pendapatan">
                            <tr>
                                <td></td>
                                <td>
                                    <h4>Total Pendapatan</h4>
                                </td>
                                <td></td>
                                <td>{{ format_uang($total_pendapatan) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@includeIf('laporan.form')
@includeIf('laporan.detailform')
@push('scripts')
    <script>
        let table, tableDetail;

        $(function() {
            table = $('.table-laporan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir, $filterType]) }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'penjualan'
                    },
                    {
                        data: 'pendapatan'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    }
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false,
            });

            tableDetail = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
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
                        data: 'harga'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'subtotal'
                    },
                ]
            });
        });

        function updatePeriode() {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Periode Laporan');
        }

        $('#filter_type').on('change', function() {
            if ($(this).val() === 'penjualan') {
                $('#tanggal-range').hide();
            } else {
                $('#tanggal-range').show();
            }
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            var filterType = $('#filter_type').val();
            var tanggalAwal = $('#tanggal_awal').val();
            var tanggalAkhir = $('#tanggal_akhir').val();

            if (filterType === 'tanggal') {
                window.location.href = '{{ route('laporan.index') }}' + '?tanggal_awal=' + tanggalAwal +
                    '&tanggal_akhir=' + tanggalAkhir + '&filter_type=tanggal';
            } else if (filterType === 'penjualan') {
                window.location.href = '{{ route('laporan.index') }}' + '?tanggal_awal=' + tanggalAwal +
                    '&tanggal_akhir=' + tanggalAkhir + '&filter_type=penjualan';
            }
        });

        function showDetail(url) {
            $('#modal-detail').modal('show');
            tableDetail.ajax.url(url);
            tableDetail.ajax.reload();
        }
    </script>
@endpush
