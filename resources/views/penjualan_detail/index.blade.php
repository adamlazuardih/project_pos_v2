@extends('layouts.master')

@section('no-sidebar')
@endsection

@section('title')
    Penjualan
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

        .table-penjualan tbody tr:last-child {
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
    <li class="active">Transaksi Penjualan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-body table-responsive">
                    <form class="form-produk">
                        @csrf
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    {{-- <label for="kode_produk">Kode Produk</label> --}}
                                    <div class="input-group">
                                        <input type="hidden" name="id_penjualan" id="id_penjualan"
                                            value="{{ $id_penjualan }}">
                                        <input type="hidden" name="id_produk" id="id_produk">
                                        <input class="form-control" type="text" name="kode_produk" id="kode_produk"
                                            placeholder="Pilih Produk" readonly>
                                        <span class="input-group-btn">
                                            <button onclick="tampilProduk()" class="btn btn-info" type="button"><i
                                                    class="fa fa-cart-arrow-down"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-hover table-penjualan">
                        <thead>
                            <th width="5%">No.</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Diskon</th>
                            <th>Sub Total</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <br>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="tampil-bayar bg-success"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                                @csrf
                                <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">
                                <input type="hidden" name="id_member" id="id_member" value="{{ $memberTerpilih->id_member }}">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_member" class="col-lg-2 control-label">Member</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input class="form-control" type="text" id="nama_member"
                                                placeholder="Pilih Member" value="{{ $memberTerpilih->nama_member }}" readonly>
                                            <span class="input-group-btn">
                                                <button onclick="tampilMember()" class="btn btn-info" type="button"><i
                                                        class="fa fa-id-card"></i></button>
                                        </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="diskon" id="diskon" class="form-control"
                                            value="{{ ! empty($memberTerpilih->id_member) ? $diskon : 0 }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="diterima" name="diterima" value="{{ $penjualan->diterima ?? 0 }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kembali" class="col-lg-2 control-label">Kembalian</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
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

    @includeIf('penjualan_detail.produk')
    @includeIf('penjualan_detail.member')
@endsection

@push('scripts')
    <script>
        $('body').addClass('sidebar-collapse');

        let table, table2, table3;

        $(function() {
            table = $('.table-penjualan').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transaksi-data', $id_penjualan) }}',
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
                            data: 'harga_jual'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'diskon'
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
                        targets: 7,
                        className: 'text-center'
                    }],
                    dom: 'Brt',
                    bSort: false
                })
                .on('draw.dt', function() {
                    load_form($('#diskon').val());
                    setTimeout(() => {
                        $('#diterima').trigger('input');
                    }, 300);
                });

            table2 = $('.table-produk').DataTable();
            table3 = $('.table-member').DataTable();

            $(document).on('input', '.edit-quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());
                let stok = parseInt($(this).data('stok'));

                if(jumlah > stok){
                    $(this).val(stok);
                    alert("Jumlah tidak boleh melebihi stok: " + stok);
                    return;
                } else if (jumlah < 1){
                    $(this).val(1);
                    alert("Jumlah tidak boleh kurang dari 1");
                    return;
                }

                if (jumlah > 10000) {
                    $(this).val(9999);
                    alert("Nilai tidak boleh lebih dari 10.000");
                    return;
                } else if (jumlah < 1) {
                    $(this).val(1);
                    alert("Nilai tidak boleh kurang dari 1");
                    return;
                }

                $.post(`{{ url('/transaksi') }}/${id}`, {
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

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                load_form($(this).val());
            });

            $('#diterima').on('input', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                load_form($('#diskon').val(), $(this).val());
            }).focus(function() {
                $(this).select();
            });

            $('.btn-simpan').on('click', function(e) {
                var bayar = $('#bayar').val();
                var diterima = $('#diterima').val();

                if (diterima == '' || parseInt(diterima) < parseInt(bayar)) {
                    e.preventDefault;
                    alert('Tidak boleh kosong dan harus lebih dari total bayar')
                } else if (parseInt(bayar) === 0 || bayar == '') {
                    e.preventDefault();
                    alert('Silahkan masukkan produk');
                } else {
                    $('.form-penjualan').submit();
                }
            });

        });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            tutupProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').focus();
                    table.ajax.reload(() => load_form($('#diskon').val()));
                })
                .fail(errors => {
                    alert("Tidak dapat menyimpan data");
                })
        }

        function tutupProduk() {
            $('#modal-produk').modal('hide');
        }

        function tampilMember() {
            $('#modal-member').modal('show');
        }

        function pilihMember(id, nama) {
            $('#id_member').val(id);
            $('#nama_member').val(nama);
            $('#diskon').val('{{ $diskon }}');
            load_form($('#diskon').val());

            $('#diterima').val(0).focus().select();
            tutupMember();
        }

        function tutupMember() {
            $('#modal-member').modal('hide');
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

        function load_form(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaksi/load_form') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp ' + response.totalrp);
                    $('#bayarrp').val('Rp ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang)

                    $('#kembali').val('Rp. ' + response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang)
                    }
                })
                .fail(errors => {
                    alert("Tidak dapat menampilkan data");
                    return;
                })
        }
    </script>
@endpush
