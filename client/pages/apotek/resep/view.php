<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek/resep/">Verifikator</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Verifikasi</li>
                </ol>
            </nav>
            <h4>Apotek - Verifikator</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
            <div class="card-group">
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-2">
                            <center>
                                <i class="material-icons icon-muted icon-30pt">account_circle</i>
                            </center>
                        </div>
                        <div class="col-md-10">
                            <b class="nama_pasien"></b>
                            <br />
                            <span class="jk_pasien"></span>
                            <br />
                            <span class="tanggal_lahir_pasien"></span>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-12">
                            <b>Penjamin</b>
                            <h5 class="penjamin_pasien text-success"></h5>

                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-12">
                            <h5 class="poliklinik text-info"></h5>
                            <h6 class="dokter"></h6>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-12">
                            <h5>
                                Total Biaya <br />
                                <b class="text-danger" id="total_biaya_obat"></b>
                            </h5>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Resep</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#resep-biasa" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Resep Biasa</a>
                    <a href="#resep-racikan" data-toggle="tab" role="tab" aria-selected="false">Resep Racikan</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="resep-biasa">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered largeDataType" id="table-resep">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat</th>
                                        <th colspan="3" style="width: 20%;">Signa/Hari</th>
                                        <th style="width: 10%">Jlh Obat</th>
                                        <th class="wrap_content">Satuan</th>
                                        <th style="width: 8%;">Harga</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7" class="text-right">TOTAL</td>
                                            <td id="total_resep_biasa" class="number_style"></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-12" style="margin-top: 20px;">
                                <b>Keterangan:</b>
                                <div id="txt_keterangan_resep"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="resep-racikan">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered largeDataType" id="table-resep-racikan">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat</th>
                                        <th colspan="3" style="width: 20%;">Signa/Hari</th>
                                        <th style="width: 10%">Jlh Obat</th>
                                        <th style="width: 10%">Total Biaya</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody class="racikan"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-right">TOTAL</td>
                                            <td id="total_resep_racikan" class="number_style"></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-12" style="margin-top: 20px;">
                                <b>Keterangan Resep Racikan:</b>
                                <div id="txt_keterangan_resep_racikan"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?php echo __HOSTNAME__; ?>/apotek/resep/" class="btn btn-danger">
                        <i class="fa fa-ban"></i> Kembali
                    </a>
                    <button type="button" class="btn btn-success pull-right" id="btnSelesai">
                        <i class="fa fa-check-circle"></i> Verifikasi
                    </button>
                    <button type="button" class="btn btn-info pull-right" id="btnCopyResep" style="margin-right: 20px;">
                        <i class="fa fa-print"></i> Copy Resep
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>