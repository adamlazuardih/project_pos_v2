@extends('layouts.master')

@section('title')
    Pendapatan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Laporan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updateForm()" class="btn btn-info"><i class="fa fa-calendar-plus-o"></i> Ubah
                        Periode</button>
                    <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank"
                        class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export PDF</a>
                </div>
                <div class="box-body table-responsive">
                    <form action="" class="form-pengeluaran" method="POST">
                        @csrf
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <th width="5%">No.</th>
                                <th>Tanggal</th>
                                <th>Penjualan</th>
                                <th>Pembelian</th>
                                <th>Pengeluaran</th>
                                <th>Pendapatan</th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @includeIf('laporan.form')
@endsection

@push('scripts')

    <script>
        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('laporan-data', [$tanggalAwal, $tanggalAkhir]) }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'penjualan'
                    },
                    {
                        data: 'pembelian'
                    },
                    {
                        data: 'pengeluaran'
                    },
                    {
                        data: 'pendapatan'
                    }
                ]
                // columnDefs: [{
                //     targets: 4,
                //     className: 'text-center'
                // }]

            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

        });

        function updateForm() {
            $('#modal-form').modal('show');
        }

    </script>
@endpush
