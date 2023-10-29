<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS SEP</li>
                </ol>
            </nav>
            <h4 class="m-0">Surat Eligibilitas Peserta (SEP)</h4>
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
                            Monitoring <br>
                            SEP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1">
                            <span class="nav-link__count">
                                02
                            </span>
                            SEP <br>
                            Induk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                03
                            </span>
                            SEP <br>
                            Internal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                04
                            </span>
                            Pengajuan <br>
                            Penjaminan SEP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-5" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                05
                            </span>
                            Update Tanggal<br>
                            Pulang SEP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-6" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                06
                            </span>
                            Suplesi <br>
                            Jasa Raharja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-7" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                07
                            </span>
                            Integrasi SEP<br>
                            dan Inacbg
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-8" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                08
                            </span>
                            Finger <br>
                            Print
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-9" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                            <span class="nav-link__count">
                                09
                            </span>
                            Random <br>
                            Question
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="tab-poli-1">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Monitoring Kunjungan SEP</h5>
                        </div>
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped largeDataType" id="table-monitoring-sep">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No. SEP</th>
                                        <th>Tgl. SEP</th>
                                        <th>Nama</th>
                                        <th>No. Kartu</th>
                                        <th>No. Rujukan</th>
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
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Cari SEP</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    Nomor SEP
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nosep_SepInduk">
                                </div>
                                <div class="col-md-4">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_SepInduk">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-SepInduk-container">
                        <div class="alert alert-danger" id="alert-SepInduk"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-SepInduk">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No. SEP</th>
                                            <th>Tgl. SEP</th>
                                            <th>Nama - No.Kartu</th>
                                            <th>No. Rujukan</th>
                                            <th>Layanan</th>
                                            <th>Poli</th>
                                            <th>Diagnosa</th>
                                            <th>Tujuan Kunjungan</th>
                                            <th class="wrap_content text-center">Aksi</th>
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
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> SEP Internal</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    Nomor SEP
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nosep_SepInternal">
                                </div>
                                <div class="col-md-4">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_SepInternal">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-SepInternal-container">
                        <div class="alert alert-danger" id="alert-SepInternal"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-SepInternal">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No.SEP</th>
                                            <th>No.SEP REF</th>
                                            <th>Tgl.SEP</th>
                                            <th>Tgl.Rujuk Internal</th>
                                            <th>No.Kartu Peserta</th>
                                            <th>No.Surat</th>
                                            <th>Poli Asal</th>
                                            <th>Poli Tujuan</th>
                                            <th>Diagnosa PPK</th>
                                            <th class="wrap_content text-center">Aksi</th>
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
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Data Persetujuan SEP</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-3">
                                    Bulan & Tahun
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_PersetujuanSep">
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_PersetujuanSep">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-info m-1" id="btnPengajuanSep">
                                PENGAJUAN SEP
                            </button>
                            <button class="btn btn-warning m-1" id="btnAprovalPengajuanSep">
                                APROVAL PENGAJUAN SEP
                            </button>
                        </div>
                    </div>
                    <div class="text-center" id="alert-PersetujuanSep-container">
                        <div class="alert alert-danger" id="alert-PersetujuanSep"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-PersetujuanSep">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No. Kartu</th>
                                            <th>Nama</th>
                                            <th>Tanggal SEP</th>
                                            <th>Jenis Pelayanan</th>
                                            <th>Persetujuan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-5">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Data Update Tanggal Pulang SEP</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-3">
                                    Bulan & Tahun
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_ListUpdateTglPlg">
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_ListUpdateTglPlg">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-ListUpdateTglPlg-container">
                        <div class="alert alert-danger" id="alert-ListUpdateTglPlg"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-info pull-right" id="btnUpdateTanggalPulangSep">
                                <i class="fa fa-plus"></i> Update Tanggal Pulang SEP
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-ListUpdateTglPlg">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No.Sep</th>
                                            <th>No.Sep Updating</th>
                                            <th>Nama - No.Kartu</th>
                                            <th>Tgl.Sep</th>
                                            <th>Tgl.Pulang</th>
                                            <th>Status</th>
                                            <th>PPK Tujuan</th>
                                            <th class="wrap_content text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-6">
                    <div class="card-header card-header-tabs-basic nav" role="tablist">
                        <a href="#tab_suplesi_jasaraharja" class="active" data-toggle="tab" role="tab" aria-controls="tab_suplesi_jasaraharja" aria-selected="true">Suplesi Jasa Raharja</a>
                        <a href="#tab_data_induk_kecelakaan" data-toggle="tab" role="tab" aria-selected="false">Data Induk Kecelakaan</a>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active show fade" id="tab_suplesi_jasaraharja">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Suplesi Jasa Raharja</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-3">
                                            No. Kartu
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="nokartu_SuplesiJasaRaharja">
                                        </div>
                                        <div class="col-md-3">
                                            Tgl.Pelayanan/SEP
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_SuplesiJasaRaharja">
                                        </div>
                                        <div class="col-md-3">
                                            <br>
                                            <button class="btn btn-info" id="btn_search_SuplesiJasaRaharja">
                                                <i class="fa fa-search"></i> Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" id="alert-SuplesiJasaRaharja-container">
                                <div class="alert alert-danger" id="alert-SuplesiJasaRaharja"></div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped largeDataType" id="table-SuplesiJasaRaharja">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>No.Register</th>
                                                    <th>No.Sep</th>
                                                    <th>No.Sep Awal</th>
                                                    <th>No.Surat Jaminan</th>
                                                    <th>Tgl.Kejadian</th>
                                                    <th>Tgl.Sep</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="tab_data_induk_kecelakaan">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Data Induk Kecelakaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-4">
                                            No. Kartu
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="nokartu_DataIndukKecelakaan">
                                        </div>
                                        <div class="col-md-3">
                                            <br>
                                            <button class="btn btn-info" id="btn_search_DataIndukKecelakaan">
                                                <i class="fa fa-search"></i> Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" id="alert-DataIndukKecelakaan-container">
                                <div class="alert alert-danger" id="alert-DataIndukKecelakaan"></div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped largeDataType" id="table-DataIndukKecelakaan">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>No.Sep</th>
                                                    <th>Tgl.Kejadian</th>
                                                    <th>Kode Prov</th>
                                                    <th>Kode Kec</th>
                                                    <th>Ket.Kejadian</th>
                                                    <th>No.SEP Suplesi</th>
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
                <div class="tab-pane show fade" id="tab-poli-7">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Integrasi SEP dan Inacbg</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    No. SEP
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nosep_IntegrasiSepInacbg">
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_IntegrasiSepInacbg">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-IntegrasiSepInacbg-container">
                        <div class="alert alert-danger" id="alert-IntegrasiSepInacbg"></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped largeDataType" id="table-IntegrasiSepInacbg">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nama</th>
                                            <th>No.Kartu</th>
                                            <th>tgl.Lahir</th>
                                            <th>No.Mr</th>
                                            <th>JK</th>
                                            <th>No.Rujukan</th>
                                            <th>Kls.Rawat</th>
                                            <th>Tgl.Pelayanan</th>
                                            <th>Tkt.Pelayanan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-8">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Finger Print</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-4">
                                    Tgl.Pelayanan/SEP
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_ListFingerPrint">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_ListFingerPrint">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-ListFingerPrint-container">
                        <div class="alert alert-danger" id="alert-ListFingerPrint"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-warning ml-3" id="btnGetFingerPrint">
                                Cek Finger Print
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-ListFingerPrint">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No.Kartu</th>
                                        <th>No.SEP</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-9">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Random Question</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <div class="col-md-3">
                                    No. Kartu
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nokartu_RandomQuestion">
                                </div>
                                <div class="col-md-3">
                                    Tgl.Pelayanan/SEP
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_RandomQuestion">
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button class="btn btn-info" id="btn_search_RandomQuestion">
                                        <i class="fa fa-search"></i> Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-RandomQuestion-container">
                        <div class="alert alert-danger" id="alert-RandomQuestion"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-warning pull-right mr-3" id="btnPostRandomQuestion">
                                <i class="fas fa-plus"></i> Post Random Answer
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-RandomQuestion">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
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