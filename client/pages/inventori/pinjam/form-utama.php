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
                            <label for="txt_supplier">Dipinjamkan Kepada:</label>
                            <select class="form-control" id="txt_supplier"></select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 id="nomor_po" class="text-info"></h6>
                        <h5 id="nama_supplier"></h5>
                        <b id="tanggal_po"></b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="txt_tujuan">Untuk Keperluan:</label>
                            <textarea id="txt_tujuan" class="form-control" placeholder="Keterangan Keperluan Peminjaman"></textarea>
                        </div>
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
                        <table class="table table-bordered largeDataType" id="table-detail-pinjam">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 50%;">Item</th>
                                    <th>Batch</th>
                                    <th style="width: 10%">Qty</th>
                                    <th class="wrap_content">Satuan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>