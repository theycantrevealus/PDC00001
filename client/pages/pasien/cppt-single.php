<?php
$isVerifApotek = true;
$keteranganRacikan = '';
?>
<div class="row align-items-center projects-item mb-1 cppt-single">
    <div class="col-sm-auto mb-1 mb-sm-0">
        <div class="text-dark-gray"><?php echo $_POST['waktu_masuk']; ?></div>
    </div>
    <div class="col-sm">
        <div class="card m-0">
            <div class="card-header bg-white card-header-large d-flex align-items-center">
                <h4 class="flex m-0 cppt-poli"><?php echo $_POST['departemen']; ?> <?php echo ($_POST['__ME__'] === $_POST['dokter_uid']) ? '<span class=\'text-success\'><i class=\'material-icons text-success icon-20pt ml-2\'>verified_user</i> Asesmen Saya</span>' : ''; ?></h4>
                <div>
                    <!--a style="padding: 0 20px;" href="<?php echo $_POST['__HOSTNAME__']; ?>/resep/view/<?php echo (count($_POST['resep']) > 0) ? $_POST['resep'][0]['uid'] : 'none'; ?>/<?php echo $_POST['asesmen']['uid']; ?>/<?php echo $_POST['kunjungan']; ?>/<?php echo $_POST['antrian']; ?>/<?php echo $_POST['penjamin']; ?>/<?php echo $_POST['pasien']; ?>/?cppt=true">
                        <span>
                            <i class="fa fa-pencil-alt"></i> Edit Resep
                        </span>
                    </a-->
                    <span class="badge badge-outline-info badge-custom-caption">
                        <b class="text-info"><?php echo $_POST['penjamin_detail']['nama']; ?></b>
                    </span>
                </div>
            </div>
            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#asesmen_rawat_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" class="" data-toggle="tab" role="tab" aria-controls="activity_all" aria-selected="false">Asesmen Rawat</a>
                <a href="#asesmen_medis_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="active">Asesmen Medis</a>
                <a href="#tindakan_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Tindakan</a>
                <a href="#resep_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Resep</a>
                <a href="#racikan_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Racikan</a>
                <a href="#laboratorium_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Laboratorium</a>
                <a href="#radiologi_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Radiologi</a>
            </div>
            <div class="px-4 py-3">
                <div class="row align-items-center">
                    <div class="col" style="min-width: 300px">
                        <div class="tab-content">
                            <div class="tab-pane show fade" id="asesmen_rawat_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">

                            </div>
                            <div class="tab-pane active show fade" id="asesmen_medis_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Keluhan Utama</h5>
                                        </div>
                                        <?php
                                            if(!empty($_POST['keluhan_utama']) && $_POST['keluhan_utama'] !== '') {
                                        ?>
                                        <p><?php echo $_POST['keluhan_utama']; ?></p>
                                        <?php
                                            } else {
                                                ?>
                                                    <div class="panel-image">
                                                        <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                        <h3 class="text-center">Tidak Ada Data</h3>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Keluhan Tambahan</h5>
                                        </div>
                                        <?php
                                        if(!empty($_POST['keluhan_tambahan']) && $_POST['keluhan_tambahan'] !== '') {
                                            ?>
                                            <p><?php echo $_POST['keluhan_tambahan']; ?></p>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="panel-image">
                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                <h3 class="text-center">Tidak Ada Data</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Diagnosa Kerja</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Diagnosa Banding</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p>
                                            <ol type="1">
                                                <?php
                                                foreach ($_POST['icd10_kerja'] as $icd10KKey => $icd10KValue) {
                                                    if(isset($icd10KValue['nama']) && $icd10KValue['nama'] !== "") {
                                                        ?>
                                                        <li><b><?php echo $icd10KValue['kode']; ?></b> - <?php echo $icd10KValue['nama']; ?></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ol>
                                        </p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p>
                                        <ol type="1">
                                            <?php
                                            foreach ($_POST['icd10_banding'] as $icd10BKey => $icd10BValue) {
                                                if(isset($icd10BValue['nama']) && $icd10BValue['nama'] !== "") {
                                                    ?>
                                                    <li><b><?php echo $icd10BValue['kode']; ?></b> - <?php echo $icd10BValue['nama']; ?></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ol>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <?php
                                        if(!empty($_POST['diagnosa_kerja']) && $_POST['diagnosa_kerja'] !== '') {
                                            ?>
                                            <p><?php echo $_POST['diagnosa_kerja']; ?></p>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="panel-image">
                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                <h3 class="text-center">Tidak Ada Data</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <?php
                                        if(!empty($_POST['diagnosa_banding']) && $_POST['diagnosa_banding'] !== '') {
                                            ?>
                                            <p><?php echo $_POST['diagnosa_banding']; ?></p>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="panel-image">
                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                <h3 class="text-center">Tidak Ada Data</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Pemeriksaan Fisik</h5>
                                        </div>
                                        <?php
                                        if(!empty($_POST['pemeriksaan_fisik']) && $_POST['pemeriksaan_fisik'] !== '') {
                                            ?>
                                            <p><?php echo $_POST['pemeriksaan_fisik']; ?></p>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="panel-image">
                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                <h3 class="text-center">Tidak Ada Data</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Planning</h5>
                                        </div>
                                        <?php
                                        if(!empty($_POST['planning']) && $_POST['planning'] !== '') {
                                            ?>
                                            <p><?php echo $_POST['planning']; ?></p>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="panel-image">
                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                <h3 class="text-center">Tidak Ada Data</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="tindakan_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ol type="1">
                                            <?php
                                            if(count($_POST['tindakan']) > 0) {
                                                foreach ($_POST['tindakan'] as $tindKey => $tindValue) {
                                                    if(isset($tindValue['tindakan']['nama']) && $tindValue['tindakan']['nama'] !== "") {
                                                        ?>
                                                        <li><?php echo $tindValue['tindakan']['nama']; ?></li>
                                                        <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <div class="panel-image">
                                                    <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_No_data_re_kwbl.png" alt="no-data" />
                                                    <h3 class="text-center">Tidak Ada Data</h3>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="resep_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <?php
                                    $autoResep = 1;
                                    if(count($_POST['resep']) > 0 && count($_POST['resep'][0]['detail']) > 0) {
                                        foreach ($_POST['resep'] as $resKey => $resValue) {
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <?Php
                                                    if(isset($resValue['alasan_ubah']) && !empty($resValue['alasan_ubah']) && $resValue['alasan_ubah'] !== '') {
                                                        ?>
                                                        <div class="alert alert-soft-warning card-margin" role="alert">
                                                            <strong>Keterangan Alasan Ubah Keseluruhan:</strong><br />
                                                            <?php echo $resValue['alasan_ubah'] ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="alert alert-soft-info card-margin" role="alert">
                                                        <strong>Keterangan:</strong><br />
                                                        <?php echo $resValue['keterangan']; $keteranganRacikan = $resValue['keterangan_racikan']; ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-auto d-flex align-items-center">
                                                        <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Resep Dokter</h5>
                                                    </div>
                                                    <br />
                                                    <table class="table table-bordered largeDataType">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th>Obat</th>
                                                            <th class="wrap_content">Signa</th>
                                                            <th class="wrap_content">Jumlah</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td rowspan="<?php echo count($resValue['detail']); ?>"><?php echo $autoResep; ?></td>
                                                            <td>
                                                                <strong class="text-info">
                                                                    <?php echo $resValue['detail'][0]['obat']['nama']; ?>
                                                                </strong><br /><br /><b>Keterangan:</b><br />
                                                                <?php echo $resValue['detail'][0]['keterangan']; ?>
                                                            </td>
                                                            <td>
                                                                <span class="wrap_content"><?php echo $resValue['detail'][0]['signa_qty']; ?> &times; <?php echo $resValue['detail'][0]['signa_pakai']; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php echo $resValue['detail'][0]['qty']; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        for ($a = 1; $a < (count($resValue['detail'])); $a++) {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <strong class="text-info">
                                                                        <?php echo $resValue['detail'][$a]['obat']['nama']; ?>
                                                                    </strong><br /><br /><b>Keterangan:</b><br />
                                                                    <?php echo $resValue['detail'][$a]['keterangan']; ?>
                                                                </td>
                                                                <td>
                                                                    <span class="wrap_content"><?php echo $resValue['detail'][$a]['signa_qty']; ?> &times; <?php echo $resValue['detail'][$a]['signa_pakai']; ?></span>
                                                                </td>
                                                                <td>
                                                                    <?php echo $resValue['detail'][$a]['qty']; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php
                                                if($resValue['status_resep'] === 'N') {
                                                    $isVerifApotek = true;
                                                    ?>
                                                    <div class="col-lg-6">
                                                        <div class="col-auto d-flex align-items-center">
                                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Resep Apotek</h5>
                                                        </div>
                                                        <br />
                                                        <h3 class="text-center text-muted">
                                                            <br /><br />
                                                            Belum Verifikasi
                                                        </h3>
                                                    </div>

                                                    <?php
                                                } else {
                                                    $isVerifApotek = false;
                                                    ?>
                                                    <div class="col-lg-6">
                                                        <div class="col-auto d-flex align-items-center">
                                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Resep Apotek</h5>
                                                        </div>
                                                        <br />
                                                        <table class="table table-bordered largeDataType">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                <th class="wrap_content">No</th>
                                                                <th>Obat</th>
                                                                <th class="wrap_content">Signa</th>
                                                                <th class="wrap_content">Jumlah</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td rowspan="<?php echo count($resValue['detail']); ?>"><?php echo $autoResep; ?></td>
                                                                <td>
                                                                    <strong class="text-info">
                                                                        <?php echo $resValue['detail_apotek'][0]['item']['nama']; ?>
                                                                    </strong><br /><br /><b>Keterangan:</b><br />
                                                                    <?php echo $resValue['detail_apotek'][0]['keterangan']; ?>
                                                                    <br /><br />
                                                                    <?php
                                                                    if(isset($resValue['detail_apotek'][0]['alasan_ubah']) && !empty($resValue['detail_apotek'][0]['alasan_ubah']) && $resValue['detail_apotek'][0]['alasan_ubah'] !== '') {
                                                                        ?>
                                                                        <div class="alert alert-soft-danger card-margin" role="alert">
                                                                            <strong>Alasan Ubah:</strong><br />
                                                                            <?php echo $resValue['detail_apotek'][0]['alasan_ubah']; ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <span class="wrap_content"><?php echo $resValue['detail_apotek'][0]['signa_qty']; ?> &times; <?php echo $resValue['detail_apotek'][0]['signa_pakai']; ?></span>
                                                                </td>
                                                                <td>
                                                                    <?php echo $resValue['detail_apotek'][0]['qty']; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            for ($a = 1; $a < (count($resValue['detail_apotek'])); $a++) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <strong class="text-info">
                                                                            <?php echo $resValue['detail_apotek'][$a]['item']['nama']; ?>
                                                                        </strong><br /><br /><b>Keterangan:</b><br />
                                                                        <?php echo $resValue['detail_apotek'][$a]['keterangan']; ?>
                                                                        <br /><br />
                                                                        <?php
                                                                        if(isset($resValue['detail_apotek'][$a]['alasan_ubah']) && !empty($resValue['detail_apotek'][$a]['alasan_ubah']) && $resValue['detail_apotek'][$a]['alasan_ubah'] !== '') {
                                                                            ?>
                                                                            <div class="alert alert-soft-danger card-margin" role="alert">
                                                                                <strong>Alasan Ubah:</strong><br />
                                                                                <?php echo $resValue['detail_apotek'][$a]['alasan_ubah']; ?>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="wrap_content"><?php echo $resValue['detail_apotek'][$a]['signa_qty']; ?> &times; <?php echo $resValue['detail_apotek'][$a]['signa_pakai']; ?></span>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $resValue['detail_apotek'][$a]['qty']; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            $autoResep++;
                                        }
                                    } else {
                                        ?>
                                        <div class="panel-image">
                                            <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_medicine_b1ol.png" alt="no-data" />
                                            <h3 class="text-center">Tidak Ada Data</h3>
                                        </div>
                                        <?php
                                    }

                                ?>
                            </div>
                            <div class="tab-pane show fade" id="racikan_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <?Php
                                if(isset($resValue['alasan_ubah']) && !empty($resValue['alasan_ubah']) && $resValue['alasan_ubah'] !== '') {
                                    ?>
                                    <div class="alert alert-soft-warning card-margin" role="alert">
                                        <strong>Keterangan Alasan Ubah Keseluruhan:</strong><br />
                                        <?php echo $resValue['alasan_ubah'] ?>
                                    </div>
                                    <?php
                                }

                                if(count($_POST['racikan']) > 0) {
                                    ?>
                                    <div class="alert alert-soft-info card-margin" role="alert">
                                        <strong>Keterangan:</strong><br />
                                        <?php echo $keteranganRacikan; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="col-auto d-flex align-items-center">
                                                <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Racikan Dokter</h5>
                                            </div>
                                            <br />
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="col-auto d-flex align-items-center">
                                                <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Racikan Apotek</h5>
                                            </div>
                                            <br />
                                        </div>
                                    </div>
                                    <?php
                                    $autoRacikan = 1;
                                    foreach ($_POST['racikan'] as $racKey => $racValue) {
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <table class="table table-bordered largeDataType">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th>Racikan</th>
                                                        <th class="wrap_content">Signa</th>
                                                        <th class="wrap_content">Jumlah</th>
                                                        <th>Komposisi</th>
                                                        <th class="wrap_content">Kekuatan</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td rowspan="<?php echo count($racValue['detail']); ?>"><?php echo $autoRacikan; ?></td>
                                                        <td rowspan="<?php echo count($racValue['detail']); ?>">
                                                            <?php echo $racValue['kode']; ?>
                                                            <br /><br /><b>Keterangan:</b><br />
                                                            <?php echo $racValue['keterangan']; ?>
                                                        </td>
                                                        <td rowspan="<?php echo count($racValue['detail']); ?>">
                                                            <span class="wrap_content"><?php echo $racValue['signa_qty']; ?> &times; <?php echo $racValue['signa_pakai']; ?></span>
                                                        </td>
                                                        <td rowspan="<?php echo count($racValue['detail']); ?>">
                                                            <?php echo $racValue['qty']; ?>
                                                        </td>
                                                        <td>
                                                            <strong class="text-info">
                                                                <?php echo $racValue['detail'][0]['obat']['nama']; ?>
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            <?php echo $racValue['detail'][0]['kekuatan']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    for ($a = 1; $a < (count($racValue['detail'])); $a++) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <strong class="text-info">
                                                                    <?php echo $racValue['detail'][$a]['obat']['nama']; ?>
                                                                </strong>
                                                            </td>
                                                            <td>
                                                                <?php echo $racValue['detail'][$a]['kekuatan']; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php
                                            if($isVerifApotek) {
                                                ?>
                                                <div class="col-lg-6">
                                                    <h3 class="text-center text-muted">
                                                        <br /><br />
                                                        Belum Verifikasi
                                                    </h3>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-lg-6">
                                                    <table class="table table-bordered largeDataType">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th>Racikan</th>
                                                            <th class="wrap_content">Signa</th>
                                                            <th class="wrap_content">Jumlah</th>
                                                            <th>Komposisi</th>
                                                            <th class="wrap_content">Kekuatan</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td rowspan="<?php echo (count($racValue['racikan_apotek']) > 0) ? count($racValue['racikan_apotek'][0]['detail']) : count($racValue['detail']); ?>"><?php echo $autoRacikan; ?></td>
                                                            <td rowspan="<?php echo (count($racValue['racikan_apotek']) > 0) ? count($racValue['racikan_apotek'][0]['detail']) : count($racValue['detail']); ?>">
                                                                <?php echo $racValue['kode']; ?>
                                                                <br /><br /><b>Keterangan:</b><br />
                                                                <?php echo $racValue['keterangan']; ?>
                                                                <?php
                                                                if(isset($racValue['racikan_apotek'][0]['alasan_ubah']) && !empty($racValue['racikan_apotek'][0]['alasan_ubah']) && $racValue['racikan_apotek'][0]['alasan_ubah'] !== '') {
                                                                    ?>
                                                                    <div class="alert alert-soft-danger card-margin" role="alert">
                                                                        <strong>Alasan Ubah:</strong><br />
                                                                        <?php echo $racValue['racikan_apotek'][0]['alasan_ubah']; ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </td>
                                                            <td rowspan="<?php echo (count($racValue['racikan_apotek']) > 0) ? count($racValue['racikan_apotek'][0]['detail']) : count($racValue['detail']); ?>">
                                                                <?php
                                                                if(count($racValue['racikan_apotek']) > 0) {
                                                                    ?>
                                                                    <span class="wrap_content"><?php echo $racValue['racikan_apotek'][0]['signa_qty']; ?> &times; <?php echo $racValue['racikan_apotek'][0]['signa_pakai']; ?></span>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <span class="wrap_content"><?php echo $racValue['signa_qty']; ?> &times; <?php echo $racValue['signa_pakai']; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </td>
                                                            <td rowspan="<?php echo (count($racValue['racikan_apotek']) > 0) ? count($racValue['racikan_apotek'][0]['detail']) : count($racValue['detail']); ?>">
                                                                <?php
                                                                if(count($racValue['racikan_apotek']) > 0) {
                                                                    echo $racValue['racikan_apotek'][0]['jumlah'];
                                                                } else {
                                                                    echo $racValue['qty'];
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <strong class="text-info">
                                                                    <?php
                                                                    if(count($racValue['racikan_apotek']) > 0) {
                                                                        echo $racValue['racikan_apotek'][0]['detail'][0]['obat']['nama'];
                                                                    } else {
                                                                        echo $racValue['detail'][0]['obat']['nama'];
                                                                    }
                                                                    ?>
                                                                </strong>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if(count($racValue['racikan_apotek']) > 0) {
                                                                    echo $racValue['racikan_apotek'][0]['detail'][0]['kekuatan'];
                                                                } else {
                                                                    echo $racValue['detail'][0]['kekuatan'];
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        if(count($racValue['racikan_apotek']) > 0) {
                                                            for ($a = 1; $a < (count($racValue['racikan_apotek'][0]['detail'])); $a++) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <strong class="text-info">
                                                                            <?php echo $racValue['racikan_apotek'][0]['detail'][$a]['obat']['nama']; ?>
                                                                        </strong><br />
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $racValue['racikan_apotek'][0]['detail'][$a]['kekuatan']; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            for ($a = 1; $a < (count($racValue['detail'])); $a++) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <strong class="text-info">
                                                                            <?php echo $racValue['detail'][$a]['obat']['nama']; ?>
                                                                        </strong><br />
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $racValue['detail'][$a]['kekuatan']; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }

                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                        <?php
                                        $autoRacikan++;
                                    }
                                } else {
                                    ?>
                                    <div class="panel-image">
                                        <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_medicine_b1ol.png" alt="no-data" />
                                        <h3 class="text-center">Tidak Ada Data</h3>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="tab-pane show fade" id="laboratorium_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <div class="col-lg">
                                    <div class="z-0">
                                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a href="#hasil_lab_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" class="nav-link active" aria-controls="activity_all" aria-selected="false">
                                                    <span class="nav-link__count">01</span>
                                                    Hasil
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#dokumen_lab_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                                                    <span class="nav-link__count">02</span>
                                                    Dokumen
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="card">
                                            <div class="card-body tab-content">
                                                <div class="tab-pane show fade active" id="hasil_lab_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                                    <?php
                                                    if(count($_POST['laboratorium']) > 0) {
                                                        foreach ($_POST['laboratorium'] as $LabKey => $LabValue) {
                                                            ?>
                                                            <div class="card">
                                                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                                                    <h4 class="card-header__title flex m-0">
                                                                        <i class="fa fa-hashtag"></i> <?php echo $LabValue['no_order']; ?>
                                                                    </h4>
                                                                </div>
                                                                <div class="card-body">
                                                                    <?php
                                                                    foreach ($LabValue['detail'] as $LabDKey => $LabDValue) {
                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <div class="col-auto d-flex align-items-center">
                                                                                    <i class="material-icons text-warning icon-20pt ml-2">folder</i>
                                                                                    <h5 class="text-info">&nbsp;&nbsp;<?php echo $LabDValue['tindakan']['nama']; ?></h5>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="col-auto d-flex">
                                                                                    <i class="material-icons text-info icon-20pt ml-2">verified_users</i>
                                                                                    <h6 class="text-right ml-10"><span class="text-info"><?php echo $LabDValue['mitra']['nama']; ?></span></h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <br />
                                                                        <table class="table table-bordered largeDataType">
                                                                            <thead class="thead-dark">
                                                                            <tr>
                                                                                <th class="wrap_content">No</th>
                                                                                <th>Item</th>
                                                                                <th>Nilai</th>
                                                                                <th class="wrap_content">Satuan</th>
                                                                                <th class="wrap_content">Nilai Min</th>
                                                                                <th class="wrap_content">Nilai Max</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                            $autoLab = 1;
                                                                            foreach ($LabDValue['nilai'] as $LabDNKey => $LabDNValue) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $autoLab; ?></td>
                                                                                    <td><?php echo $LabDNValue['item_lab']['keterangan']; ?></td>
                                                                                    <td><?php echo (isset($LabDNValue['nilai']) && $LabDNValue['nilai'] !== '') ? $LabDNValue['nilai'] : '-'; ?></td>
                                                                                    <td><?php echo (isset($LabDNValue['item_lab']['satuan']) && $LabDNValue['item_lab']['satuan'] !== '') ? $LabDNValue['item_lab']['satuan'] : '-'; ?></td>
                                                                                    <td><?php echo (isset($LabDNValue['item_lab']['nilai_min']) && $LabDNValue['item_lab']['nilai_min'] !== '') ? $LabDNValue['item_lab']['nilai_min'] : '-'; ?></td>
                                                                                    <td><?php echo (isset($LabDNValue['item_lab']['nilai_maks']) && $LabDNValue['item_lab']['nilai_maks'] !== '') ? $LabDNValue['item_lab']['nilai_maks'] : '-'; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                                $autoLab++;
                                                                            }
                                                                            ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <br />
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <strong>Kesan:</strong>
                                                                                <br />
                                                                                <?php echo $LabValue['kesan']; ?>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <strong>Anjuran:</strong>
                                                                                <br />
                                                                                <?php echo $LabValue['anjuran']; ?>
                                                                            </div>
                                                                        </div>
                                                                        <hr /><br /><br /><br />
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <div class="panel-image">
                                                            <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_medicine_b1ol.png" alt="no-data" />
                                                            <h3 class="text-center">Tidak Ada Data</h3>
                                                        </div>
                                                        <?php
                                                    }

                                                    ?>
                                                </div>
                                                <div class="tab-pane show fade" id="dokumen_lab_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">

                                                        <?php
                                                        $autoLabDoc = 1;

                                                        $totalDoc = 0;
                                                        foreach ($_POST['laboratorium'] as $LabKey => $LabValue) {
                                                            foreach ($LabValue['dokumen'] as $LabDocKey => $LabDocValue) {
                                                                $totalDoc += 1;
                                                            }
                                                        }
                                                        if($totalDoc > 0) {
                                                            ?>
                                                    <table class="table table-bordered largeDataType">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th>Dokumen</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($_POST['laboratorium'] as $LabKey => $LabValue) {
                                                                foreach ($LabValue['dokumen'] as $LabDocKey => $LabDocValue) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $autoLabDoc; ?></td>
                                                                        <td>
                                                                            <a class="lampiran_view_trigger" href="#" target="<?php echo $_POST['__HOST__'] . '/document/laboratorium/' . $LabValue['uid'] . '/' . $LabDocValue['lampiran']; ?>">#Lampiran <?php echo $autoLabDoc; ?> [<?php echo $LabDocValue['lampiran']; ?>]</a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $autoLabDoc++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div class="panel-image">
                                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_pending_approval_xuu9.png" alt="no-data" />
                                                                <h3 class="text-center">Tidak Ada Data</h3>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="radiologi_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <div class="col-lg">
                                    <div class="z-0">
                                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a href="#hasil_rad_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" data-toggle="tab" role="tab" class="nav-link active" aria-controls="activity_all" aria-selected="false">
                                                    <span class="nav-link__count">01</span>
                                                    Hasil
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#dokumen_rad_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                                                    <span class="nav-link__count">02</span>
                                                    Dokumen
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="card">
                                            <div class="card-body tab-content">
                                                <div class="tab-pane show fade active" id="hasil_rad_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                                    <?php
                                                    if(count($_POST['radiologi']) > 0) {
                                                        foreach ($_POST['radiologi'] as $RadKey => $RadValue) {
                                                            ?>
                                                            <div class="card">
                                                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                                                    <h4 class="card-header__title flex m-0">
                                                                        <i class="fa fa-hashtag"></i> <?php echo $RadValue['no_order']; ?>
                                                                    </h4>
                                                                </div>
                                                                <div class="card-body">
                                                                    <?php
                                                                    foreach ($RadValue['detail'] as $RadDDKey => $RadDDValue) {
                                                                        ?>
                                                                        <div class="col-auto d-flex align-items-center">
                                                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;<?php echo $RadDDValue['tindakan']['nama']; ?> - <h5 class="text-success text-right"><i class="material-icons">verified_user</i> <?php echo $RadDDValue['mitra']['nama']; ?></h5></h5>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <strong>Keterangan:</strong>
                                                                                <br />
                                                                                <?php echo $RadDDValue['keterangan']; ?>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <strong>Kesimpulan:</strong>
                                                                                <br />
                                                                                <?php echo $RadDDValue['kesimpulan']; ?>
                                                                            </div>
                                                                        </div>
                                                                        <hr /><br /><br /><br />
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <div class="panel-image">
                                                            <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_medicine_b1ol.png" alt="no-data" />
                                                            <h3 class="text-center">Tidak Ada Data</h3>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tab-pane show fade" id="dokumen_rad_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">

                                                        <?php
                                                        $autoRadDoc = 1;
                                                        $totalDoc = 0;
                                                        foreach ($_POST['radiologi'] as $RadKey => $RadValue) {
                                                            foreach ($RadValue['dokumen'] as $RadDocKey => $RadDocValue) {
                                                                $totalDoc += 1;
                                                            }
                                                        }
                                                        if($totalDoc > 0) {
                                                            ?>
                                                    <table class="table table-bordered largeDataType">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th>Dokumen</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($_POST['radiologi'] as $RadKey => $RadValue) {
                                                                foreach ($RadValue['dokumen'] as $RadDocKey => $RadDocValue) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $autoRadDoc; ?></td>
                                                                        <td>
                                                                            <a class="lampiran_view_trigger" href="#" target="<?php echo $_POST['__HOST__'] . '/document/radiologi/' . $RadValue['uid'] . '/' . $RadDocValue['lampiran']; ?>">#Lampiran <?php echo $autoRadDoc; ?> [<?php echo $RadDocValue['lampiran']; ?>]</a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $autoRadDoc++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div class="panel-image">
                                                                <img src="<?php echo $_POST['__HOST__']; ?>/client/template/assets/images/illustration/undraw_pending_approval_xuu9.png" alt="no-data" />
                                                                <h3 class="text-center">Tidak Ada Data</h3>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="d-flex align-items-center">
                            <div class="col-auto d-flex align-items-center">
                                <a href="#" class="d-flex align-items-middle">
                                    <span class="avatar avatar-xxs avatar-online mr-2">
                                        <img src="<?php echo $_POST['dokter_pic'] ?>" alt="Avatar" class="avatar-img rounded-circle">
                                    </span>
                                    <?php echo $_POST['dokter'] ?>
                                </a>
                            </div>
                            <div class="col-auto d-flex align-items-center" style="min-width: 140px;">
                                <a href="#" class="text-dark-gray">DEPLOYED</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>