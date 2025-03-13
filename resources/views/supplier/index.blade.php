@extends('layouts.master')

@section('title')
    Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('supplier.store') }}')" class="btn btn-success"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <div class="box-body table-responsive">
                    <form action="" class="form-supplier" method="POST">
                        @csrf
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <th width="5%">No.</th>
                                <th width="19%">Nama Suplier</th>
                                <th width="45%">Alamat</th>
                                <th>Telepon</th>
                                <th width="10%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @includeIf('supplier.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('supplier-data') }}',
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'nama_supplier'
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
                    targets: 4,
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

        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form').on('shown.bs.modal', function() {
                $('#nama_supplier').focus();
            })
        }

        function editForm(url){
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form').on('shown.bs.modal', function() {
                $('#nama_supplier').focus();
            })

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama_supplier]').val(response.nama_supplier);
                    $('#modal-form [name=alamat]').val(response.alamat);
                    $('#modal-form [name=telepon').val(response.telepon);
                })
                .fail((errors) => {
                    alert('Tidak dapat menyimpan data');
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

    </script>
@endpush
