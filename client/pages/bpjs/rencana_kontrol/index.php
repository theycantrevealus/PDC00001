<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS Rencana Kontrol</li>
                </ol>
            </nav>
            <h4 class="m-0">Permintaan Rencana Kontrol</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                        Rencana Kontrol
                        <button class="btn btn-sm btn-info pull-right" id="btnTambahRK">
                            <i class="fa fa-plus"></i> Tambah Rencana Kontrol
                        </button>
                    </h5>
                </div>
                <div class="card-body tab-content">
                    <div class="card-group">
                        <div class="card card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    Tanggal Awal
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tglawal_rk">
                                </div>
                                <div class="col-md-4">
                                    Tanggal Akhir
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tglakhir_rk">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="btn_sync_bpjs">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-sm btn-warning pull-right" id="btnCetakRkTest">
                                        <i class="fa fa-print"></i> Test Cetak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body">
                        <table class="table table-bordered table-striped largeDataType" id="table-rk">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>No. Surat Kontrol</th>
                                    <th class="wrap_content">Tgl. Terbit Kontrol</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th class="wrap_content">Aksi</th>
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