<!-- Modal -->
<div class="modal fade" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="modal-member" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title">Modal title</h5> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Pilih Member</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-member">
                    <thead>
                        <th width="5%">No.</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th width="15%" class="text-center"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($member as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td><span class="label label-default">{{ $item->kode_member }}</span></td>
                                <td>{{ $item->nama_member }}</td>
                                <td>{{ $item->telepon }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-primary" id="btn-member" onclick="pilihMember('{{ $item->id_member }}', '{{ $item->nama_member }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
