<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS LPK</li>
                </ol>
            </nav>
            <h4 class="m-0">Lembar Pengajuan Klaim</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                        BPJS Lembar Pengajuan Klaim
                    </h5>

                    <!-- <button class="btn btn-warning pull-right mr-1" id="btnTestEdit">
                        Test Edit
                    </button> -->

                    <button class="btn btn-sm btn-info pull-right" id="btnTambahLPK">
                        <i class="fa fa-plus"></i> Tambah LPK
                    </button>
                </div>
                <div class="card-body tab-content">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Data LPK</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-3">
                                    Jenis Pelayanan
                                    <select class="form-control" id="text_search_lpk_jnslayanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    Tanggal Masuk
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="text_search_lpk_tglmasuk">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_lpk">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-lpk-container">
                        <div class="alert alert-danger" id="alert-lpk"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-lpk">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No. SEP</th>
                                            <th>No. Kartu</th>
                                            <th>Peserta</th>
                                            <th>Tgl. Masuk</th>
                                            <th>Tgl. Keluar</th>
                                            <th>Jns. Pelayanan</th>
                                            <th>Poli</th>
                                            <th>DPJP</th>
                                            <th class="text-center">Aksi</th>
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
    </div>
</div>