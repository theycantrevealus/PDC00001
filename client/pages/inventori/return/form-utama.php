<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Informasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txt_po">Dokumen Transaksi:</label>
                            <select class="form-control" id="txt_po"></select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 id="nomor_po" class="text-info"></h6>
                        <h5 id="nama_supplier"></h5>
                        <b id="tanggal_po"></b>
                        <h5 id="no_invoice"></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Detail Item</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered largeDataType" id="table-detail-return">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Item</th>
                                <th class="wrap_content">Batch</th>
                                <th style="width: 10%">Qty</th>
                                <th class="wrap_content">Satuan</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <b>Keterangan Tambahan</b>
                        <textarea class="form-control" style="min-height: 300px" id="txt_keterangan" placeholder="Tambahkan keterangan tambahan disini"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>