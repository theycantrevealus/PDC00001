<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS SPRI / Rencana Kontrol</li>
                </ol>
            </nav>
            <h4 class="m-0">Permintaan SPRI / Rencana Kontrol</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                        SPRI / Rencana Kontrol
                        <button class="btn btn-sm btn-info pull-right" id="btnTambahSPRI">
                            <i class="fa fa-plus"></i> Tambah SPRI / Rencana Kontrol
                        </button>
                    </h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#surat_kontrol" class="active" data-toggle="tab" role="tab" aria-controls="surat_kontrol" aria-selected="true">Cari No. Surat Kontrol</a>
                    <a href="#list_kontrol" data-toggle="tab" role="tab" aria-selected="false">Cari List Kontrol</a>
                    <a href="#no_kartu" data-toggle="tab" role="tab" aria-selected="false">Cari No. Kartu</a>
                </div>
                <div class="card-body tab-content">
                    <div class="card-body tab-content">
                        <div class="tab-pane active show fade" id="surat_kontrol">
                            <div class="card-group">
                                <div class="card card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-10">
                                            No. Surat Kontrol
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="search_no_surat">
                                        </div>
                                        <div class="col-md-2">
                                            <br>
                                            <button class="btn btn-info" id="btn_search_no_surat">
                                                <i class="fa fa-search"></i> Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="list_kontrol">
                            <div class="card-group">
                                <div class="card card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-3">
                                            Tanggal Awal
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="tglawal_list_kontrol">
                                        </div>
                                        <div class="col-md-3">
                                            Tanggal Akhir
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="tglakhir_list_kontrol">
                                        </div>
                                        <div class="col-md-3">
                                            Filter
                                            <select class="form-control" id="filter_list_kontrol">
                                                <option value="1">Tanggal Entri</option>
                                                <option value="2">Tanggal Rencana Kontrol</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <br>
                                            <button class="btn btn-info" id="btn_search_list_kontrol">
                                                <i class="fa fa-search"></i> Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="no_kartu">
                            <div class="card-group">
                                <div class="card card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-3">
                                            No. Kartu
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="search_no_kartu">
                                        </div>
                                        <div class="col-md-3">
                                            Tanggal
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="search_tgl_no_kartu">
                                        </div>
                                        <div class="col-md-3">
                                            Filter
                                            <select class="form-control" id="search_filter_no_kartu">
                                                <option value="1">Tanggal Entri</option>
                                                <option value="2">Tanggal Rencana Kontrol</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <br>
                                            <button class="btn btn-info" id="btn_search_no_kartu">
                                                <i class="fa fa-search"></i> Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-sprirk-container">
                        <div class="alert alert-danger" id="alert-sprirk"></div>
                    </div>
                    <div class="card card-body">
                        <table class="table table-bordered table-striped largeDataType" id="table-spri">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th class="wrap_content">Tanggal</th>
                                    <th class="wrap_content">Jenis Layanan</th>
                                    <th class="wrap_content">No. RK / SPRI</th>
                                    <th>Pasien</th>
                                    <th>Poli Tujuan</th>
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