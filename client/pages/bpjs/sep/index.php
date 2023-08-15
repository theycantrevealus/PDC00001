<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">SEP</li>
                </ol>
            </nav>
            <h4 class="m-0">SEP</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">History Kunjungan</h5>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list-resep">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-5">
                                        Jenis Pelayanan
                                        <select class="form-control" id="jenis_pelayanan_dt_kunjungan">
                                            <option value="1">Rawat Inap</option>
                                            <option value="2">Rawat Jalan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        Tanggal SEP
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_sep_dt_kunjungan">
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <button class="btn btn-info" id="btn_search_dt_kunjungan">
                                            <i class="fa fa-search"></i> Cari Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="alert-sep-dt-kunjungan-container">
                            <div class="alert alert-danger" id="alert-sep-dt-kunjungan"></div>
                        </div>
                        <div class="card card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-sep">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No. SEP</th>
                                        <th>Tgl. SEP</th>
                                        <th>Nama</th>
                                        <th>No. Kartu</th>
                                        <th>No. Rujukan</th>
                                        <th>Layanan</th>
                                        <th>Poli</th>
                                        <th>Diagnosa</th>
                                        <th>Tgl. Plg SEP</th>
                                        <th class="wrap_content text-center">Aksi</th>
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