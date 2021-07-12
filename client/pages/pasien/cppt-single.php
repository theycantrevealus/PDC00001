<div class="row align-items-center projects-item mb-1 cppt-single">
    <div class="col-sm-auto mb-1 mb-sm-0">
        <div class="text-dark-gray"><?php echo $_POST['waktu_masuk']; ?></div>
    </div>
    <div class="col-sm">
        <div class="card m-0" style="margin-bottom: 20px !important; <?php echo ($_POST['__ME__'] === $_POST['dokter_uid']) ? 'background: #F1FFD7; box-shadow: 0 10px 25px 0 rgb(241 255 215 / 7%), 0 5px 15px 0 rgb(0 0 0 / 7%);' : ''; ?>">
            <div class="card-header card-header-large d-flex align-items-center" style="<?php echo ($_POST['__ME__'] === $_POST['dokter_uid']) ? 'background: #F7FFE9' : ''; ?>">
                <h4 class="card-header__title flex m-0"><?php echo $_POST['departemen']; ?> <?php echo ($_POST['__ME__'] === $_POST['dokter_uid']) ? '<span class=\'text-success\'><i class=\'material-icons text-success icon-20pt ml-2\'>verified_user</i> Asesmen Saya</span>' : ''; ?></h4>
                <div>
                    <span class="badge badge-info badge-custom-caption">
                        <h6 style="color: #fff !important;">UMUM</h6>
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
                                        <p><?php echo $_POST['keluhan_utama']; ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Keluhan Tambahan</h5>
                                        </div>
                                        <p><?php echo $_POST['keluhan_tambahan']; ?></p>
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
                                        <p><?php echo $_POST['diagnosa_kerja']; ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><?php echo $_POST['diagnosa_banding']; ?></p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Pemeriksaan Fisik</h5>
                                        </div>
                                        <p><?php echo $_POST['pemeriksaan_fisik']; ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-auto d-flex align-items-center">
                                            <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Planning</h5>
                                        </div>
                                        <p><?php echo $_POST['planning']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="tindakan_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ol type="1">
                                            <?php
                                            foreach ($_POST['tindakan'] as $tindKey => $tindValue) {
                                                if(isset($tindValue['tindakan']['nama']) && $tindValue['tindakan']['nama'] !== "") {
                                                    ?>
                                                    <li><?php echo $tindValue['tindakan']['nama']; ?></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="resep_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <?php
                                    $autoResep = 1;
                                    foreach ($_POST['resep'] as $resKey => $resValue) {
                                ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-soft-info card-margin" role="alert">
                                            <strong>Keterangan:</strong><br />
                                            <?php echo $resValue['keterangan'] ?>
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
                                                        <span class="wrap_content"><?php echo $resValue['detail'][0]['signa_pakai']; ?> &times; <?php echo $resValue['detail'][0]['signa_qty']; ?></span>
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
                                                            <span class="wrap_content"><?php echo $resValue['detail'][$a]['signa_pakai']; ?> &times; <?php echo $resValue['detail'][$a]['signa_qty']; ?></span>
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
                                                </td>
                                                <td>
                                                    <span class="wrap_content"><?php echo $resValue['detail_apotek'][0]['signa_pakai']; ?> &times; <?php echo $resValue['detail_apotek'][0]['signa_qty']; ?></span>
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
                                                    </td>
                                                    <td>
                                                        <span class="wrap_content"><?php echo $resValue['detail_apotek'][$a]['signa_pakai']; ?> &times; <?php echo $resValue['detail_apotek'][$a]['signa_qty']; ?></span>
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
                                </div>
                                        <?php
                                        $autoResep++;
                                    }
                                ?>
                            </div>
                            <div class="tab-pane show fade" id="racikan_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                <?php
                                $autoRacikan = 1;
                                foreach ($_POST['racikan'] as $racKey => $racValue) {
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-soft-info card-margin" role="alert">
                                                <strong>Keterangan:</strong><br />
                                                <?php echo $racValue['keterangan'] ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="col-auto d-flex align-items-center">
                                                <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Racikan Dokter</h5>
                                            </div>
                                            <br />
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
                                                            <span class="wrap_content"><?php echo $racValue['signa_pakai']; ?> &times; <?php echo $racValue['signa_qty']; ?></span>
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
                                        <div class="col-lg-6">
                                            <div class="col-auto d-flex align-items-center">
                                                <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;Racikan Apotek</h5>
                                            </div>
                                            <br />
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
                                                        <span class="wrap_content"><?php echo $racValue['racikan_apotek'][$racKey]['signa_pakai']; ?> &times; <?php echo $racValue['racikan_apotek'][$racKey]['signa_qty']; ?></span>
                                                    </td>
                                                    <td rowspan="<?php echo count($racValue['detail']); ?>">
                                                        <?php echo $racValue['racikan_apotek'][$racKey]['jumlah']; ?>
                                                    </td>
                                                    <td>
                                                        <strong class="text-info">
                                                            <?php echo $racValue['racikan_apotek'][$racKey]['detail'][0]['obat']['nama']; ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <strong class="text-info">
                                                            <?php echo $racValue['racikan_apotek'][$racKey]['detail'][0]['kekuatan']; ?>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <?php
                                                for ($a = 1; $a < (count($racValue['racikan_apotek'][$racKey]['detail'])); $a++) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <strong class="text-info">
                                                                <?php echo $racValue['racikan_apotek'][$racKey]['detail'][$a]['obat']['nama']; ?>
                                                            </strong><br />
                                                        </td>
                                                        <td>
                                                            <?php echo $racValue['racikan_apotek'][$racKey]['detail'][$a]['kekuatan']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                    $autoRacikan++;
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
                                                                    <div class="col-auto d-flex align-items-center">
                                                                        <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;<?php echo $LabDValue['tindakan']['nama']; ?></h5>
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
                                                    ?>
                                                </div>
                                                <div class="tab-pane show fade" id="dokumen_lab_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                                    <table class="table table-bordered largeDataType">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th class="wrap_content">No</th>
                                                                <th>Dokumen</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $autoLabDoc = 1;
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
                                                                foreach ($RadValue['detail'] as $RadDKey => $RadDValue) {
                                                                    ?>
                                                                    <div class="col-auto d-flex align-items-center">
                                                                        <i class="material-icons text-warning icon-20pt ml-2">folder</i><h5 class="text-info">&nbsp;&nbsp;<?php echo $RadDValue['tindakan']['nama']; ?></h5>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <strong>Keterangan:</strong>
                                                                            <br />
                                                                            <?php echo $RadDValue['keterangan']; ?>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <strong>Kesimpulan:</strong>
                                                                            <br />
                                                                            <?php echo $RadDValue['kesimpulan']; ?>
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
                                                    ?>
                                                </div>
                                                <div class="tab-pane show fade" id="dokumen_rad_<?php echo $_POST['group_tanggal_name']; ?>_<?php echo $_POST['waktu_masuk_name']; ?>">
                                                    <table class="table table-bordered largeDataType">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th>Dokumen</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $autoRadDoc = 1;
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