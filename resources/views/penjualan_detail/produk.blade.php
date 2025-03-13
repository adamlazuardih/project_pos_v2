<!-- Modal -->
<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title">Modal title</h5> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-produk">
                    <thead>
                        <th width="5%">No.</th>
                        <th>Kode Produk</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th>Stok</th>
                        <th width="15%" class="text-center"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td width="15%" class="text-center"><span class="label label-default">{{ $item->kode_produk }}</span></td>
                                <td>{{ $item->nama_produk }}</td>
                                <td width="15%" class="text-right">Rp. {{ format_uang($item->harga_jual) }}</td>
                                <td width="10%">{{ $item->stok }}</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-primary {{ $item->stok <= 0 ? 'disabled' : '' }}" onclick="{{ $item->stok > 0 ? "pilihProduk('{$item->id_produk}', '{$item->kode_produk}')" : '' }}">
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
