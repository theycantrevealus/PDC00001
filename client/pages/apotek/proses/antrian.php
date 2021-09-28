<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek/proses">Apotek</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Proses Resep</li>
                </ol>
            </nav>
            <h4>Apotek - Proses Resep</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-body tab-content">
                <div class="tab-pane active show fade" id="tab-po-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-group">
                                <div class="card card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-2 text-center">
                                            <i class="material-icons icon-muted icon-30pt">account_circle</i>
                                        </div>
                                        <div class="col-md-10">
                                            <b class="nama_pasien" id="nama-pasien"></b>
                                            <br />
                                            <span class="jk_pasien" id="jk-pasien"></span>
                                            <br />
                                            <span class="tanggal_lahir_pasien" id="tanggal-lahir-pasien"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-md-12">
                                            <b>Verifikator</b>
                                            <h5 class="verifikator text-info" id="verifikator"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">Resep</h5>
                                </div>
                                <div class="card-body tab-content">
                                    <div class="tab-pane active show fade" id="resep-biasa">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-soft-info card-margin" role="alert">
                                                    <h6>
                                                        <i class="fa fa-paperclip"></i> Keterangan Resep
                                                    </h6>
                                                    <br />
                                                    <div id="txt_keterangan_resep" style="color: #000 !important;"></div>
                                                </div>
                                                <table id="load-detail-resep" class="table table-bordered largeDataType">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                                        <th style="width: 40%;">Obat</th>
                                                        <th class="wrap_content">Signa</th>
                                                        <th width="15%">Jumlah</th>
                                                        <th width="20%">Keterangan</th>
                                                        <th width="20%">Alasan Ubah</th>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">Racikan</h5>
                                </div>
                                <div class="card-body tab-content">
                                    <div class="tab-pane active show fade" id="resep-racikan">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-soft-info card-margin" role="alert">
                                                    <h6>
                                                        <i class="fa fa-paperclip"></i> Keterangan Racikan
                                                    </h6>
                                                    <br />
                                                    <div id="txt_keterangan_racikan" style="color: #000 !important;"></div>
                                                </div>
                                                <table id="load-detail-racikan" class="table table-bordered largeDataType">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                                        <th width="20%;">Racikan</th>
                                                        <th class="wrap_content">Signa</th>
                                                        <th class="wrap_content">Jumlah</th>
                                                        <th width="30%;">Obat</th>
                                                        <th width="20%">Keterangan</th>
                                                        <th width="20%">Alasan Ubah</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-soft-danger card-margin" role="alert">
                                        <h6>
                                            <i class="fa fa-paperclip"></i> Alasan Ubah
                                        </h6>
                                        <br />
                                        <div id="txt_alasan_ubah" style="color: #000 !important;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-success" id="btnSelesai">
                                <i class="fa fa-check"></i> Selesai
                            </button>
                            <button class="btn btn-info btn-apotek-cetak">
                                <i class="fa fa-print"></i> Cetak
                            </button>
                            <a href="<?php echo __HOSTNAME__; ?>/apotek/proses" class="btn btn-danger">
                                <i class="fa fa-ban"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>