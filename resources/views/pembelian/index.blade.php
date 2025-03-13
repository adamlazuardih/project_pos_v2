@extends('layouts.master')

@section('title')
    Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                            {{ session('success') }}
                        </div>
                    @endif
                    <button onclick="addForm()" class="btn btn-success"><i class="fa fa-plus-circle"></i> Transaksi
                        Baru</button>
                    @empty(!session('id_pembelian'))
                        <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info"><i class="fa fa-pencil"></i>
                            Transaksi Aktif</a>
                    @endempty
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered table-hover table-pembelian">
                        <thead>
                            <th width="5%">No.</th>
                            <th>Tanggal</th>
                            <th>Nama Suplier</th>
                            <th>Total Item</th>
                            <th>Total Harga</th>
                            <th>Diskon</th>
                            <th>Total Bayar</th>
                            <th width="10%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('pembelian.supplier')
    @includeIf('pembelian.detail')
@endsection

@push('scripts')
    <script>
        let table, table2, table3;

        $(function() {
            table = $('.table-pembelian').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('pembelian-data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'supplier'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'total_harga'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'bayar'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    }

                ],
                columnDefs: [{
                    targets: 7,
                    className: 'text-center'
                }]

            });

            table2 = $('.table-supplier').DataTable({
                processing: true,
                autoWidth: false,
                columnDefs: [{
                    targets: 4,
                    className: 'text-center',
                    sortable: false
                }]
            });

            table3 = $('.table-detail').DataTable({
                processing: true,
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'harga_beli'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'subtotal'
                    }
                ]

            });

            setTimeout(function() {
                $('#success-alert').fadeOut('slow');
            }, 5000);

        });

        function addForm() {
            $('#modal-supplier').modal('show');
        }

        function showDetail(url) {
            $('#modal-detail').modal('show');

            table3.ajax.url(url);
            table3.ajax.reload();
        }

        function deleteData(url) {
            if (confirm('Apakah anda ingin menghapus?')) {
                $.post(url, {
                        '_token': $('[name = csrf_token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus, cek Transaksi Aktif');
                        return;
                    })
            }
        }

        function cetakInvoice(url, title) {
            popupCenter(url, title, 900, 675);
        }

        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
            const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

            const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document
                .documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document
                .documentElement.clientHeight : screen.height;

            const systemZoom = width / window.screen.availWidth;
            const left = (width - w) / 2 / systemZoom + dualScreenLeft;
            const top = (height - h) / 2 / systemZoom + dualScreenTop;

            const newWindow = window.open(url, title,
                `scrollbars=yes, width=${w / systemZoom}, height=${h / systemZoom}, top=${top}, left=${left}`
            );

            if (newWindow && window.focus) newWindow.focus();
        }
    </script>
@endpush
