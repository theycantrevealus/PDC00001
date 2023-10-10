<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS Rujukan</li>
                </ol>
            </nav>
            <h4 class="m-0">Permintaan Rujukan</h4>
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
                            List Rujukan Peserta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1">
                            <span class="nav-link__count">
                                02
                            </span>
                            List Rujukan Keluar RS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                03
                            </span>
                            List Rujukan Khusus
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                04
                            </span>
                            List Spesialistik Rujukan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-5" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                05
                            </span>
                            List Sarana Rujukan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="tab-poli-1">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Rujukan Peserta BPJS</h5>
                        </div>
                        <div class="card-header card-header-tabs-basic nav" role="tablist">
                            <a href="#search_list_rujukan_peserta_nokartu" class="active" data-toggle="tab" role="tab" aria-controls="search_list_rujukan_peserta_nokartu" aria-selected="true">Cari No. Kartu BPJS</a>
                            <a href="#search_rujukan_peserta_norujukan" data-toggle="tab" role="tab" aria-selected="false">Cari No. Rujukan</a>
                        </div>
                        <div class="card-body tab-content">
                            <div class="tab-pane active show fade" id="search_list_rujukan_peserta_nokartu">
                                <div class="card-group">
                                    <div class="card card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-md-5">
                                                Jenis Faskes
                                                <select class="form-control uppercase sep" id="faskes_rujukan_peserta_nokartu">
                                                    <option value="1">Faskes Tingkat 1</option>
                                                    <option value="2">Rumah Sakit</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                No. Kartu
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="nokartu_rujukan_peserta_nokartu">
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button class="btn btn-info" id="btn_search_rujukan_peserta_nokartu">
                                                    <i class="fa fa-search"></i> Cari Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="search_rujukan_peserta_norujukan">
                                <div class="card-group">
                                    <div class="card card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-md-5">
                                                Jenis Faskes
                                                <select class="form-control uppercase sep" id="faskes_rujukan_peserta_norujukan">
                                                    <option value="1">Faskes Tingkat 1</option>
                                                    <option value="2">Rumah Sakit</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                No. Rujukan
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="norujukan_faskes_rujukan_peserta_norujukan">
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button class="btn btn-info" id="btn_search_rujukan_peserta_norujukan">
                                                    <i class="fa fa-search"></i> Cari Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-rujukanpeserta-container">
                        <div class="alert alert-danger" id="alert-rujukanpeserta-list"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-rujukan-peserta">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No.Rujukan</th>
                                        <th>Nama - No. Kartu</th>
                                        <th>NIK</th>
                                        <th>Tgl.Rujukan</th>
                                        <th>PPK Perujuk</th>
                                        <th>Sub/Spesialis</th>
                                        <th class="text-center">Aksi</th>
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
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Rujukan Keluar RS</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    Tanggal Awal
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tglawal_listkeluarrujukan">
                                </div>
                                <div class="col-md-4">
                                    Tanggal Akhir
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tglakhir_listkeluarrujukan">
                                </div>
                                <div class="col-md-4">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_listkeluarrujukan">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-rujukanlist-container">
                        <div class="alert alert-danger" id="alert-rujukanlist"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-sm btn-info pull-right" id="btnTambahRujukan">
                                <i class="fa fa-plus"></i> Tambah Rujukan
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-rujukan">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No. Rujukan</th>
                                        <th>Nama - No. Kartu</th>
                                        <th>Tgl. Rujukan</th>
                                        <th>No. SEP</th>
                                        <th>Jenis Pelayanan</th>
                                        <th>PPK Dirujuk</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-3">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Rujukan Khusus</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    Bulan & Tahun
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_rujukankhususlist">
                                </div>
                                <div class="col-md-4">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_rujukankhususlist">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-rujukankhusus-container">
                        <div class="alert alert-danger" id="alert-rujukankhusus-list"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-sm btn-info pull-right" id="btnTambahRujukanKhusus">
                                <i class="fa fa-plus"></i> Tambah Rujukan Khusus
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-rujukan-khusus">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Id Rujukan</th>
                                        <th>No. Rujukan</th>
                                        <th>Tgl. Rujukan Awal</th>
                                        <th>Tgl. Rujukan Berakhir</th>
                                        <th>NOKAPST</th>
                                        <th>NMPST</th>
                                        <th>Diagnosa PPK</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-4">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Spesialistik rujukan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-2">
                                    Jenis Faskes
                                    <select class="form-control uppercase sep" id="jenis_faskes_ListSpesialistikRujukan">
                                        <option value="1">Faskes Tingkat 1</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="col_faskes_ListSpesialistikRujukan">
                                    Faskes
                                    <select class="form-control uppercase sep" id="faskes_ListSpesialistikRujukan"></select>
                                </div>
                                <div class="col-md-3">
                                    Tanggal Rujuk
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tglrujuk_ListSpesialistikRujukan">
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_ListSpesialistikRujukan">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-ListSpesialistikRujukan-container">
                        <div class="alert alert-danger" id="alert-ListSpesialistikRujukan"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-ListSpesialistikRujukan">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nama Spesialis</th>
                                        <th>Kode Spesialis</th>
                                        <th>Kapasitas</th>
                                        <th>Jumlah Rujukan</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-5">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Sarana rujukan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-2">
                                    Jenis Faskes
                                    <select class="form-control uppercase sep" id="jenis_faskes_ListSaranaRujukan">
                                        <option value="1">Faskes Tingkat 1</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="col_faskes_ListSaranaRujukan">
                                    Faskes
                                    <select class="form-control uppercase sep" id="faskes_ListSaranaRujukan"></select>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_ListSaranaRujukan">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-ListSaranaRujukan-container">
                        <div class="alert alert-danger" id="alert-ListSaranaRujukan"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-ListSaranaRujukan">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kode Sarana</th>
                                        <th>Nama Sarana</th>
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