@extends('layouts.master')

@section('no-sidebar')
@endsection

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background-color: #f0f0f0;
        }

        .table-pembelian tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    {{-- @parent --}}
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Supplier</td>
                            <td>: {{ $supplier->nama_supplier }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>: {{ $supplier->telepon }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $supplier->alamat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body table-responsive">

                    <form class="form-produk">
                        @csrf
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    {{-- <label for="kode_produk">Kode Produk</label> --}}
                                    <div class="input-group">
                                        <input type="hidden" name="id_pembelian" id="id_pembelian"
                                            value="{{ $id_pembelian }}">
                                        <input type="hidden" name="id_produk" id="id_produk">
                                        <input class="form-control" type="text" name="kode_produk" id="kode_produk"
                                            placeholder="Kode Produk" disabled required>
                                        <span class="input-group-btn">
                                            <button onclick="tampilProduk()" class="btn btn-info" type="button"><i
                                                    class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-hover table-pembelian">
                        <thead>
                            <th width="5%">No.</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Sub Total</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <br>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                                @csrf
                                <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="diskon" id="diskon" class="form-control"
                                            value="{{ $diskon }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-success pull-right btn-simpan"><i class="fa fa-floppy-o"></i>
                        Simpan Transaksi</button>
                </div>

            </div>
        </div>
    </div>

    @includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
    <script>
        $('body').addClass('sidebar-collapse');

        let table, table2;

        $(function() {
            table = $('.table-pembelian').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('pembelian_detail-data', $id_pembelian) }}',
                    },
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
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        }
                    ],
                    columnDefs: [{
                        targets: 6,
                        className: 'text-center'
                    }],
                    dom: 'Brt',
                    bSort: false
                })
                .on('draw.dt', function() {
                    load_form($('#diskon').val());
                });

            table2 = $('.table-produk').DataTable();

            $(document).on('input', '.edit-quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                if (jumlah > 10000) {
                    $(this).val(9999);
                    alert("Nilai tidak boleh lebih dari 10.000");
                    return;
                } else if (jumlah < 1) {
                    $(this).val(1);
                    alert("Nilai tidak boleh kurang dari 1");
                    return;
                }

                $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                        '_token': $('[name = csrf_token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => load_form($('#diskon').val()));
                        });
                    })
                    .fail(errors => {
                        alert("Tidak dapat menyimpan data");
                        return;
                    })
            });

            $('#diskon').on('input', function() {
                let diskon = parseInt($(this).val());

                if (diskon > 100) {
                    $(this).val(99);
                    alert("Nilai tidak boleh lebih dari 100");
                    return;
                } else if (diskon < 1) {
                    $(this).val(1);
                    alert("Nilai tidak boleh kurang dari 1");
                    return;
                }

                load_form($(this).val());
            });

            $('.btn-simpan').on('click', function(e) {
                var bayar = $('#bayar').val();

                if (parseInt(bayar) === 0 || bayar == '') {
                    e.preventDefault();
                    alert('Silahkan masukkan produk');
                } else {
                    $('.form-pembelian').submit();
                }
            });

        });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function tutupProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            tutupProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').focus();
                    table.ajax.reload(() => load_form($('#diskon').val()));
                })
                .fail(errors => {
                    alert("Tidak dapat menyimpan data");
                })
        }

        function deleteData(url) {

            $.post(url, {
                    '_token': $('[name = csrf_token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => load_form($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                })

        }

        function load_form(diskon = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/pembelian_detail/load_form') }}/${diskon}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('Rp ' + response.totalrp);
                    $('#bayarrp').val('Rp ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang)
                })
                .fail(errors => {
                    alert("Tidak dapat menampilkan data");
                    return;
                })
        }
    </script>
@endpush
