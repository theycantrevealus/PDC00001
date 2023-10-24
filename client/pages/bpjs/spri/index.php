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
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-referensi-bpjs">
                    <li class="nav-item">
                        <a href="#tab-poli-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1">
                            <span class="nav-link__count">
                                01
                            </span>
                            List Rencana Kontrol/Inap
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1">
                            <span class="nav-link__count">
                                02
                            </span>
                            Cari No.Surat Kontrol/Inap
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="tab-poli-1">
                    <div class="card">
                        <div class="card-header card-header-tabs-basic nav ml-3" role="tablist">
                            <a href="#list_kontrol" class="active" data-toggle="tab" role="tab" aria-controls="list_kontrol" aria-selected="true">List Rencana Kontrol/Inap</a>
                            <a href="#no_kartu" data-toggle="tab" role="tab" aria-selected="false">Cari No. Kartu BPJS</a>

                            <h5 class="card-header__title flex m-0">
                                <button class="btn btn-sm btn-info pull-right" id="btnTambahSPRI1">
                                    <i class="fa fa-plus"></i> Tambah SPRI / Rencana Kontrol
                                </button>
                            </h5>
                        </div>
                        <div class="card-body tab-content">
                            <div class="tab-pane active show fade" id="list_kontrol">
                                <div class="card-group">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Rencana Kunjungan Kontrol/Inap</h5>
                                        </div>
                                        <div class="card-body">
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
                                                <div class="col-md-2">
                                                    <br>
                                                    <button class="btn btn-info" id="btn_search_list_kontrol">
                                                        <i class="fa fa-search"></i> Cari Data
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="no_kartu">
                                <div class="card-group">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Rencana Kunjungan Kontrol/Inap</h5>
                                        </div>
                                        <div class="card-body">
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
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped largeDataType" id="table-spri">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>No.Surat</th>
                                                    <th>Jenis</th>
                                                    <th>Tgl.Kontrol</th>
                                                    <th>Tgl.Entri</th>
                                                    <th>Nama - No.Kartu</th>
                                                    <th>No.SEP Asal</th>
                                                    <th>Poli Asal</th>
                                                    <th>Poli Tuju</th>
                                                    <th>Nama DPJP</th>
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
                <div class="tab-pane show fade" id="tab-poli-2">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> No. Surat Rencana Kontrol/Inap</h5>
                            <h5 class="card-header__title flex m-0">
                                <button class="btn btn-sm btn-info pull-right" id="btnTambahSPRI2">
                                    <i class="fa fa-plus"></i> Tambah SPRI / Rencana Kontrol
                                </button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    No. Surat Kontrol
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nosurat_NoSuratKontrolInap">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_NoSuratKontrolInap">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-NoSuratKontrolInap-container">
                        <div class="alert alert-danger" id="alert-NoSuratKontrolInap"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-NoSuratKontrolInap">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No.Surat</th>
                                            <th>Jenis</th>
                                            <th>Tgl.Kontrol</th>
                                            <th>Tgl.Entri</th>
                                            <th>Nama - No.Kartu</th>
                                            <th>No.SEP Asal</th>
                                            <th>Poli Tuju</th>
                                            <th>Nama DPJP</th>
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