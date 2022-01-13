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
                            <h6 class="nama_pasien"></h6>
                            <h6 class="jk_pasien"></h6>
                            <h6 class="tanggal_lahir_pasien"></h6>
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

            <div class="card-group">
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-12">
                            <h5><i class="fa fa-list-alt text-info"></i> Diagnosa Utama</h5>
                            <br />
                            <ol type="1" id="icd_utama"></ol>
                            <br />
                            <p id="diagnosa_utama"></p>
                            <div class="no-data" id="no-data-diagnosa-utama">
                                <div>
                                    <i class="fa fa-clipboard"></i> Tidak ada Data
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-12">
                            <h5><i class="fa fa-clipboard text-info"></i> Diagnosa Banding</h5>
                            <br />
                            <ol type="1" id="icd_banding"></ol>
                            <br />
                            <p id="diagnosa_banding"></p>
                            <div class="no-data" id="no-data-diagnosa-banding">
                                <div>
                                    <i class="fa fa-clipboard"></i> Tidak ada Data
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fa fa-capsules text-warning"></i> Keterangan Alergi Obat</h5>
                            <br />
                            <p id="alergi_obat"></p>
                            <div class="no-data" id="no-data-alergi-obat">
                                <div>
                                    <i class="fa fa-clipboard"></i> Tidak ada Data
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 id="iter-identifier" class="resep_script"><i class="fa fa-receipt text-success"></i> Iter <span id="iterasi-resep"></span>&nbsp;&times;</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Resep</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav verifikator-tab" role="tablist">
                    <a href="#resep-biasa" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Resep Biasa <span id="identifier_jumlah_resep" style="position: absolute; top: -20px; right: -5px;" class="badge badge-info badge-custom-caption"></span></a>
                    <a href="#resep-racikan" data-toggle="tab" role="tab" aria-selected="false">Resep Racikan <span id="identifier_jumlah_racikan" style="position: absolute; top: -20px; right: -5px;" class="badge badge-info badge-custom-caption"></span></a>
                    <a href="#resep-kajian" data-toggle="tab" role="tab" aria-selected="false">Pengkajian Resep <span id="identifier_kajian" style="position: absolute; top: -20px; right: -5px;" class="badge badge-danger badge-custom-caption"></span></a>
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
                            </div>
                            <div class="col-md-12">
                                <table class="table largeDataType resepTable" id="table-resep">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat</th>
                                        <th colspan="3" style="width: 20%;">Signa/Hari</th>
                                        <th style="width: 10%">Jlh Obat</th>
                                        <th class="wrap_content">Satuan</th>
                                        <th style="width: 8%;">Harga</th>
                                        <th class="wrap_content">Copy Resep</th>
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
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="resep-racikan">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-soft-info card-margin" role="alert">
                                    <h6>
                                        <i class="fa fa-paperclip"></i> Keterangan Racikan
                                    </h6>
                                    <br />
                                    <div id="txt_keterangan_resep_racikan" style="color: #000 !important;"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table largeDataType resepTable" id="table-resep-racikan">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat</th>
                                        <th colspan="3" style="width: 20%;">Signa/Hari</th>
                                        <th style="width: 10%">Jlh Obat</th>
                                        <th style="width: 10%">Total Biaya</th>
                                        <th class="wrap_content">Copy Resep</th>
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
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="resep-kajian">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered largeDataType">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="2" style="width: 80%">Pilih Semua</th>
                                            <th>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="" type="radio" name="kajian_all" id="option1" value="y"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="" type="radio" name="kajian_all" id="option2" value="n"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="3" class="wrap_content">a.</td>
                                            <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                                                <b>Aspek Administrasi</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Resep Lengkap</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_resep_lengkap" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_resep_lengkap" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Pasien Sesuai</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_pasien_sesuai" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_pasien_sesuai" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>






                                        <tr>
                                            <td rowspan="3" class="wrap_content">b.</td>
                                            <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                                                <b>Aspek Farmasetik</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Benar Obat</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_obat" value="y" id="kajian_benar_obat_y"> Ya
                                                            </label>
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_obat" value="n" id="kajian_benar_obat_n"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Benar Bentuk/Kekuatan/Jumlah</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_bentuk" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_bentuk" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>











                                        <tr>
                                            <td rowspan="6" class="wrap_content">c.</td>
                                            <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                                                <b>Aspek Klinik</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Benar Dosis/Frekuensi/Aturan Pakai</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_dosis" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_dosis" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Benar Rute Pemberian</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_rute" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_benar_rute" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Tidak Ada Interaksi Obat</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_interaksi" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_interaksi" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Tidak Ada Duplikasi</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_duplikasi" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_duplikasi" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 30px">Tidak Alergi/Kontradiksi</td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-light">
                                                                <input class="kajian_sel" type="radio" name="kajian_alergi" value="y" id="option1"> Ya
                                                            </label>
                                                            <label class="btn btn-light active">
                                                                <input class="kajian_sel" type="radio" name="kajian_alergi" value="n" id="option2"> Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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