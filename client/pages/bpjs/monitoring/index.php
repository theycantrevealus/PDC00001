<?php
$day = new DateTime('last day of this month');
$yesterday = new DateTime(date('Y-m-d')); // For today/now, don't pass an arg.
$yesterday->modify("-1 day");

$tomorrow = new DateTime(date('Y-m-d'));
$tomorrow->modify("+1 day");
?>
<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS - MONITORING</li>
                </ol>
            </nav>
            <h4>BPJS - MONITORING</h4>
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
                            Data Kunjungan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1">
                            <span class="nav-link__count">
                                02
                            </span>
                            Data History Pelayanan Peserta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                03
                            </span>
                            Data Klaim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                04
                            </span>
                            Data Klaim Jasa Raharja
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="tab-poli-1">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Data Kunjungan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row mb-5">
                                <div class="col-md-5">
                                    Jenis Pelayanan
                                    <select id="datakunjungan_text_search_jns" class="form-control">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    Tgl.Pelayanan/SEP
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="datakunjungan_text_search_tgl">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="datakunjungan_btn_search">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                            <div class="text-center" id="alert-datakunjungan-container">
                                <div class="alert alert-danger" id="alert-datakunjungan"></div>
                            </div>
                            <table class="table table-bordered largeDataType" id="bpjs_table_datakunjungan">
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
                <div class="tab-pane show fade" id="tab-poli-2">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Data History Pelayanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row mb-5">
                                <div class="col-md-3">
                                    Tgl. Mulai
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="datahistorypelayanan_text_search_tgl_mulai">
                                </div>
                                <div class="col-md-3">
                                    Tgl. Akhir
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="datahistorypelayanan_text_search_tgl_akhir">
                                </div>
                                <div class="col-md-3">
                                    No. Kartu
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="datahistorypelayanan_text_search_no_kartu">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="datahistorypelayanan_btn_search">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                            <div class="text-center" id="alert-datahistorypelayanan-container">
                                <div class="alert alert-danger" id="alert-datahistorypelayanan"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered largeDataType" id="bpjs_table_datahistorypelayanan">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No. SEP</th>
                                            <th>Tgl. SEP</th>
                                            <th>Nama</th>
                                            <th>No. Kartu</th>
                                            <th>No. Rujukan</th>
                                            <th>Layanan</th>
                                            <th>Kelas Rawat</th>
                                            <th>Poli</th>
                                            <th>Diagnosa</th>
                                            <th>Tgl. Plg SEP</th>
                                            <th>PPK Pelayanan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-3">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Data Klaim</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row mb-5">
                                <div class="col-md-3">
                                    Jenis Pelayanan
                                    <select id="dataklaim_text_search_jns" class="form-control">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    Tgl. Pulang
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="dataklaim_text_search_tgl">
                                </div>
                                <div class="col-md-3">
                                    Status Klaim
                                    <select id="dataklaim_text_search_status" class="form-control">
                                        <option value="1">Proses Verifikasi</option>
                                        <option value="2">Pending Verifikasi</option>
                                        <option value="3">Klaim</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="dataklaim_btn_search">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                            <div class="text-center" id="alert-dataklaim-container">
                                <div class="alert alert-danger" id="alert-dataklaim"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered largeDataType" id="bpjs_table_dataklaim">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No. SEP</th>
                                            <th>Nama - No.Kartu</th>
                                            <th>Tgl. SEP</th>
                                            <th>Tgl. Pulang</th>
                                            <th>Kls. Rawat</th>
                                            <th>INACBG</th>
                                            <th>Poli</th>
                                            <th>Status Klaim</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-4">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Data Klaim Jasa Raharja</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row mb-5">
                                <div class="col-md-3">
                                    Jenis Pelayanan
                                    <select id="dataklaimjasaraharja_text_search_jns" class="form-control">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    Tgl. Mulai
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="dataklaimjasaraharja_text_search_tgl_mulai">
                                </div>
                                <div class="col-md-3">
                                    Tgl. Akhir
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="dataklaimjasaraharja_text_search_tgl_akhir">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="dataklaimjasaraharja_btn_search">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                            <div class="text-center" id="alert-dataklaimjasaraharja-container">
                                <div class="alert alert-danger" id="alert-dataklaimjasaraharja"></div>
                            </div>
                            <table class="table table-bordered largeDataType" id="bpjs_table_dataklaimjasaraharja">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No.SEP</th>
                                        <th>Nama - No.Kartu</th>
                                        <th>Tgl.SEP</th>
                                        <th>Tgl.Plg SEP</th>
                                        <th>Poli</th>
                                        <th>Diagnosa</th>
                                        <th>No.Register</th>
                                        <th>Tgl. Kejadian</th>
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