<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('laporan.index') }}" method="GET" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Periode Pendapatan</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal_awal" class="col-md-2 col-md-offset-1 control-label">Tanggal Awal</label>
                        <div class="col-md-6">
                            <input name="tanggal_awal" id="tanggal_awal" rows="3" class="form-control datepicker" value="{{ request('tanggal_awal') }}" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal_akhir" class="col-md-2 col-md-offset-1 control-label">Tanggal Akhir</label>
                        <div class="col-md-6">
                            <input name="tanggal_akhir" id="tanggal_akhir" rows="3" class="form-control datepicker" value="{{ request('tanggal_akhir') }}" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
