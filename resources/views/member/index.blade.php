@extends('layouts.master')

@section('title')
    Member
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Member</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                    <button onclick="cetakMember('{{ route('member.cetak-member') }}')" class="btn btn-info"><i
                            class="fa fa-id-card"></i> Cetak Kartu Member</button>
                </div>
                <div class="box-body table-responsive">
                    <form action="" class="form-member" method="POST">
                        @csrf
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <th width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">No.</th>
                                <th width="20%">Kode Member</th>
                                <th width="18%">Nama Member</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th width="10%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @includeIf('member.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('member-data') }}',
                },
                columns: [
                    {
                        data: 'select_all',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'kode_member'
                    },
                    {
                        data: 'nama_member'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'telepon'
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
                }]

            });

            $('#modal-form').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('#modal-form form').attr('action'),
                            type: 'post',
                            data: $('#modal-form form').serialize(),
                        })
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                }
            });

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            })
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form').on('shown.bs.modal', function() {
                $('#nama_member').focus();
            })
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form').on('shown.bs.modal', function() {
                $('#nama_member').focus();
            })

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama_member]').val(response.nama_member);
                    $('#modal-form [name=telepon]').val(response.telepon);
                    $('#modal-form [name=alamat]').val(response.alamat);
                })
                .error((response) => {
                    alert("Tidak dapat menampilkan data");
                    return;
                });
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
                        alert('Tidak dapat menghapus data');
                        return;
                    })
            }
        }

        function cetakMember(url) {
            if ($('input:checked').length < 1) {
                alert('Pilih data yang ingin di cetak');
                return;
            }  else {
                $('.form-member').attr('target', '_blank').attr('action', url).submit();
            }
        }
    </script>
@endpush
