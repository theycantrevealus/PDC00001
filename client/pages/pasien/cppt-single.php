<div class="row align-items-center projects-item mb-1 cppt-single">
    <div class="col-sm-auto mb-1 mb-sm-0">
        <div class="text-dark-gray"><?php echo $_POST['waktu_masuk']; ?></div>
    </div>
    <div class="col-sm">
        <div class="card m-0">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h4 class="card-header__title flex m-0"><?php echo $_POST['departemen']; ?></h4>
                <div>
                    <span class="badge badge-info badge-custom-caption">
                        <h6 style="color: #fff !important;">UMUM</h6>
                    </span>
                </div>
            </div>
            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#asesmen_rawat_<?php echo $_POST['group_tanggal_name']; ?>" class="" data-toggle="tab" role="tab" aria-controls="activity_all" aria-selected="false">Asesmen Rawat</a>
                <a href="#asesmen_medis_<?php echo $_POST['group_tanggal_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="active">Asesmen Medis</a>
                <a href="#tindakan_<?php echo $_POST['group_tanggal_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Tindakan</a>
                <a href="#resep_<?php echo $_POST['group_tanggal_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Resep</a>
                <a href="#racikan_<?php echo $_POST['group_tanggal_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Racikan</a>
                <a href="#laboratorium_<?php echo $_POST['group_tanggal_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Laboratorium</a>
                <a href="#radiologi_<?php echo $_POST['group_tanggal_name']; ?>" data-toggle="tab" role="tab" aria-selected="false" class="">Radiologi</a>
            </div>
            <div class="px-4 py-3">
                <div class="row align-items-center">
                    <div class="col" style="min-width: 300px">
                        <div class="tab-content">
                            <div class="tab-pane active show fade" id="asesmen_rawat_<?php echo $_POST['group_tanggal_name']; ?>">

                            </div>
                            <div class="tab-pane active show fade" id="asesmen_medis_<?php echo $_POST['group_tanggal_name']; ?>">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5><i class="material-icons icon-muted icon-20pt ml-2">folder</i> Keluhan Utama</h5>
                                        <p><?php echo $_POST['keluhan_utama']; ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h5><i class="material-icons icon-muted icon-20pt ml-2">folder</i> Keluhan Tambahan</h5>
                                        <p><?php echo $_POST['keluhan_tambahan']; ?></p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5><i class="material-icons icon-muted icon-20pt ml-2">folder</i> Diagnosa Kerja</h5>
                                    </div>
                                    <div class="col-lg-6">
                                        <h5><i class="material-icons icon-muted icon-20pt ml-2">folder</i> Diagnosa Banding</h5>
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
                                                        <li><?php echo $icd10KValue['kode']; ?> - <?php echo $icd10KValue['nama']; ?></li>
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
                                                    <li><?php echo $icd10BValue['kode']; ?> - <?php echo $icd10BValue['nama']; ?></li>
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
                                        <h5><i class="material-icons icon-muted icon-20pt ml-2">folder</i> Pemeriksaan Fisik</h5>
                                        <p><?php echo $_POST['pemeriksaan_fisik']; ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h5><i class="material-icons icon-muted icon-20pt ml-2">folder</i> Planning</h5>
                                        <p><?php echo $_POST['planning']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane active show fade" id="tindakan_<?php echo $_POST['group_tanggal_name']; ?>">

                            </div>
                            <div class="tab-pane active show fade" id="resep_<?php echo $_POST['group_tanggal_name']; ?>">

                            </div>
                            <div class="tab-pane active show fade" id="racikan_<?php echo $_POST['group_tanggal_name']; ?>">

                            </div>
                            <div class="tab-pane active show fade" id="laboratorium_<?php echo $_POST['group_tanggal_name']; ?>">

                            </div>
                            <div class="tab-pane active show fade" id="radiologi_<?php echo $_POST['group_tanggal_name']; ?>">

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