@extends('layouts.master')

@section('title')
    Edit Profil
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Edit Profil</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <form class="form-profil" action="{{ route('user.update_profil') }}" method="POST" data-toggle="validator" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="alert alert-info alert-dismissible" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-check"> Perubahan Berhasil Disimpan!</i>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                            <div class="col-lg-3">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $profil->name }}"
                                    required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="foto" class="col-lg-2 col-lg-offset-1 control-label">Foto</label>
                            <div class="col-lg-4">
                                <input type="file" name="foto" id="foto" class="form-control"
                                    onchange="preview('.tampil-foto', this.files[0])">
                                <span class="help-block with-errors"></span>
                                <div class="tampil-foto">
                                    <img src="{{ url($profil->foto ?? '/') }}" width="150">
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="old_password" class="col-lg-2 col-lg-offset-1 control-label">Password Lama</label>
                            <div class="col-lg-2">
                                <input type="password" name="old_password" id="old_password" class="form-control" minlength="6">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-lg-2 col-lg-offset-1 control-label">Password Baru</label>
                            <div class="col-lg-2">
                                <input type="password" name="password" id="password" class="form-control" minlength="6">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-lg-2 col-lg-offset-1 control-label">Ulang Password</label>
                            <div class="col-lg-2">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-match="#password">
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
        $('#password').on('keyup', function(){
            if($(this).val() != ""){
                $('#password', '#password_confirmation').attr('required', true);
            } else {
                $('#password', '#password_confirmation').attr('required', false);
            }
        })

        $(function() {
            $('.form-profil').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-profil').attr('action'),
                            type: $('.form-profil').attr('method'),
                            data: new FormData($('.form-profil')[0]),
                            async: false,
                            processData: false,
                            contentType: false,
                        })
                        .done(response => {
                            $('[name=name]').val(response.name);
                            $('.tampil-foto').html(`<img src="{{ url('/') }}${response.foto}" width="150">`);
                            $('.img-profil').attr('src', `{{ url('/') }}/${response.foto}`);

                            $('.alert').fadeIn();
                            setTimeout(() => {
                                $('.alert').fadeOut();
                            }, 3000);
                        })
                        .fail(errors => {
                            if(errors.status == 422){
                                alert(errors.responseJSON);
                            } else {
                                alert('Tidak dapat menyimpan data');
                            }
                            return;
                        });
                }
            });
        });

    </script>
@endpush
