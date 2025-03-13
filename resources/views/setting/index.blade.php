@extends('layouts.master')

@section('title')
    Pengaturan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Pengaturan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <form class="form-setting" action="{{ route('setting.update') }}" method="POST" data-toggle="validator" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body" style="padding-top: 30px">
                        <div class="alert alert-info alert-dismissible" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-check"> Perubahan Berhasil Disimpan!</i>
                        </div>
                        <div class="form-group row">
                            <label for="nama_perusahaan" class="col-lg-2 col-lg-offset-1 control-label">Nama
                                Perusahaan</label>
                            <div class="col-lg-3">
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control"
                                    required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat" class="col-lg-2 col-lg-offset-1 control-label">Alamat</label>
                            <div class="col-lg-6">
                                <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telepon" class="col-lg-2 col-lg-offset-1 control-label">Telepon</label>
                            <div class="col-lg-4">
                                <input type="number" name="telepon" id="telepon" class="form-control" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="path_logo" class="col-lg-2 col-lg-offset-1 control-label">Logo Perusahaan</label>
                            <div class="col-lg-4">
                                <input type="file" name="path_logo" id="path_logo" class="form-control"
                                    onchange="preview('.tampil-logo', this.files[0])">
                                <span class="help-block with-errors"></span>
                                <div class="tampil-logo"></div>
                                <br>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="path_kartu_member" class="col-lg-2 col-lg-offset-1 control-label">Tipe
                                Member</label>
                            <div class="col-lg-4">
                                <input type="file" name="path_kartu_member" id="path_kartu_member" class="form-control"
                                    onchange="preview('.tampil-kartu', this.files[0], 250)">
                                <span class="help-block with-errors"></span>
                                <div class="tampil-kartu"></div>
                                <br>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="diskon" class="col-lg-2 col-lg-offset-1 control-label">Diskon</label>
                            <div class="col-lg-2">
                                <input type="number" name="diskon" id="diskon" class="form-control" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tipe_nota" class="col-lg-2 col-lg-offset-1 control-label">Tipe Nota</label>
                            <div class="col-lg-2">
                                <select name="tipe_nota" id="tipe_nota" class="form-control" required>
                                    <option value="1">Nota Kecil</option>
                                    <option value="2">Nota Besar</option>
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        showData();
        $(function() {
            $('.form-setting').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-setting').attr('action'),
                            type: $('.form-setting').attr('method'),
                            data: new FormData($('.form-setting')[0]),
                            async: false,
                            processData: false,
                            contentType: false,
                        })
                        .done(response => {
                            showData();
                            $('.alert').fadeIn();
                            setTimeout(() => {
                                $('.alert').fadeOut();
                            }, 3000);
                        })
                        .fail(errors => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                }
            });
        });

        function showData() {
            $.get('{{ route('setting.show') }}')
                .done(response => {
                    $('[name = nama_perusahaan]').val(response.nama_perusahaan);
                    $('[name = telepon]').val(response.telepon);
                    $('[name = alamat]').val(response.alamat);
                    $('[name = diskon]').val(response.diskon);
                    $('[name = tipe_nota]').val(response.tipe_nota);
                    $('title').text(response.nama_perusahaan + ' | Pengaturan');

                    $('.tampil-logo').html(`<img src="${response.path_logo}" width="150">`);
                    $('.tampil-kartu').html(`<img src="${response.path_kartu_member}" width="250">`);
                    $('[rel=icon]').attr('href', `{{ url('/') }}/${response.path_logo}`);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
