<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap/dokter">Rawat Inap</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><b id="target_pasien"></b></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card-group">
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-1">
                            <a href="<?php echo __HOSTNAME__; ?>/rawat_inap/perawat">
                                <span>
                                    <i class="fa fa-chevron-circle-left"></i> Kembali
                                </span>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <span id="rm_pasien" class="text-info"></span>
                            <br />
                            <b id="nama_pasien"></b>
                            <br />
                            <span id="tempat_lahir_pasien"></span>, <span id="tanggal_lahir_pasien"></span> (<span id="usia_pasien"></span> tahun)
                            <br />
                            <span id="jenkel_pasien"></span>
                            <br />
                            <span id="alamat_pasien"></span>
                        </div>
                        <div class="col-md-8">
                            <div class="form-row" data-toggle="dragula">
                                <div class="col-md col-lg-3 handy print_manager" id="gelang">
                                    <div class="card form-row__card text-white bg-primary">
                                        <div class="card-body">
                                            <h6 class="text-white"><i class="fa fa-band-aid"></i>&nbsp;&nbsp;&nbsp;Gelang Pasien</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md col-lg-3 handy print_manager" id="kartu">
                                    <div class="card form-row__card bg-success text-white">
                                        <div class="card-body">
                                            <h6 class="text-white"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Kartu Pasien</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md col-lg-3 handy print_manager" id="kartu">
                                    <div class="card form-row__card bg-purple text-white">
                                        <div class="card-body">
                                            <h6 class="text-white"><i class="fa fa-flask"></i>&nbsp;&nbsp;&nbsp;Label Lab Pasien</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                        Pelayanan
                        <button class="pull-right btn btn-success" id="btnPulangkanPasien">
                            <span>
                                <i class="fa fa-check-circle"></i> Pulangkan Pasien
                            </span>
                        </button>
                    </h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#dokter" class="" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Dokter</a>
                    <a href="#perawat" data-toggle="tab" role="tab" aria-selected="false">Perawat</a>
                    <a href="#obat" class="active" data-toggle="tab" role="tab" aria-selected="false">Obat dan BHP</a>
                    <a href="#biaya" data-toggle="tab" role="tab" aria-selected="false">Biaya</a>
                </div>
                <div class="card-body tab-content" style="min-height: 100px;">
                    <div class="tab-pane show fade" id="dokter">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Tgl</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="perawat">
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-info pull-right" id="btnTambahAsesmenRawat">
                                    <span>
                                        <i class="fa fa-plus-circle"></i> Tambah Asesmen
                                    </span>
                                </button>
                            </div>
                            <div class="col-lg-12">
                                <br />
                                <table class="table table-striped" id="table-antrian-rawat-jalan-perawat" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Tgl</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active show fade" id="obat">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Obat dan BHP</h5>
                            </div>
                            <div class="card-header card-header-tabs-basic nav" role="tablist">
                                <a href="#resep" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Resep dan Racikan</a>
                                <a href="#riwayat_obat" class="" data-toggle="tab" role="tab" aria-selected="false">Riwayat Pemberian Obat</a>
                            </div>
                            <div class="card-body tab-content" style="min-height: 100px;">
                                <div class="tab-pane active show fade" id="resep">
                                    <table class="table table-striped largeDataType" id="table-resep-inap">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th class="wrap_content">Tgl</th>
                                            <th>Dokter</th>
                                            <th>Resep</th>
                                            <th>Racikan</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane show fade" id="riwayat_obat">
                                    <table class="table table-bordered table-striped largeDataType" id="table-riwayat-obat-inap">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th class="wrap_content">Waktu</th>
                                            <th class="wrap_content">Kode Resep</th>
                                            <th class="wrap_content">Pasien</th>
                                            <th style="width: 30%">Obat</th>
                                            <th class="wrap_content">Jumlah</th>
                                            <th class="wrap_content">Oleh</th>
                                            <th>Keterangan</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="biaya">
                        <table class="table table-bordered table-striped largeDataType" id="biaya_pasien">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Item</th>
                                <th class="wrap_content">Jlh</th>
                                <th class="wrap_content">Harga</th>
                                <th class="wrap_content">Subtotal</th>
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