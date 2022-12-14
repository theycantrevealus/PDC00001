<?php
$isVerifApotek = true;
$keteranganRacikan = '';
?>
<div class="row align-items-center projects-item mb-1 cppt-single" id="dataset__<?php echo $_POST['uid']; ?>">
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
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <b>Tanda-tanda Vital</b>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                GCS
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div class="input-group input-group-merge">
                                                                        <input type="text" class="form-control form-control-prepended" id="igd_gcs_e" />
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                E
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div class="input-group input-group-merge">
                                                                        <input type="text" class="form-control form-control-prepended" id="igd_gcs_v" />
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                V
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div class="input-group input-group-merge">
                                                                        <input type="text" class="form-control form-control-prepended" id="igd_gcs_m" />
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                M
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div class="input-group input-group-merge">
                                                                        <input type="text" class="form-control form-control-prepended" id="igd_gcs_tot" />
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                Total
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                Tekanan Darah:
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text" class="form-control form-control-appended" id="igd_tekanan_darah">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            mmHg
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                Nadi:
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text" class="form-control form-control-appended" id="igd_nadi">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            X/i
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="txt_pupil">Pupil:</label>
                                                                    <br />
                                                                    <input type="radio" name="igd_pupil" value="isokor" /> Isokor
                                                                    <br />
                                                                    <input type="radio" name="igd_pupil" value="anisokor" /> Anisokor
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="txt_no_ktp">Refleks Cahaya:</label>
                                                                    <input class="form-control" id="igd_refleks_cahaya" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                RR:
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text" class="form-control form-control-appended" id="igd_rr">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            X/m
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                Suhu:
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text" class="form-control form-control-appended" id="igd_suhu">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            <sup>o</sup>C
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <b>Status Alergi</b>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="radio" name="igd_status_alergi" value="y" /> Ya
                                                                    <input type="text" class="form-control uppercase" id="igd_status_alergi_text" placeholder="Sebutkan">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="radio" name="igd_status_alergi" value="n" /> Tidak
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <b>Gangguan Perilaku</b>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <input type="radio" name="igd_gangguan_perilaku" value="tidak_terganggu" /> Tidak Terganggu
                                                                    <br />
                                                                    <input type="radio" name="igd_gangguan_perilaku" value="terganggu" /> Terganggu
                                                                    <ul class="selection-list">
                                                                        <li>
                                                                            <input type="radio" name="igd_gangguan_terganggu" value="terganggu_tidak_bahaya" /> Tidak Membahayakan
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" name="igd_gangguan_terganggu" value="terganggu_bahaya" /> Membahayakan Diri Sendiri / Orang Lain
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header bg-white">
                                                        <b>Skala Nyeri</b>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row" style="margin-bottom: 20px;">
                                                            <div class="col-md-3">
                                                                Nyeri
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_skala_nyeri" value="y" /> Ya
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_skala_nyeri" value="n" /> Tidak
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                Lokasi
                                                            </div>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="igd_lokasi" placeholder="Lokasi Nyeri" />
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                Frekuensi
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_frekuensi" value="sering" /> Sering
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_frekuensi" value="kadang" /> Kadang
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_frekuensi" value="jarang" /> Jarang
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <b>Karakteristik Nyeri</b>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="terbakar" /> Terbakar
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="tertindih" /> Tertindih
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="menyebar" /> Menyebar
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="tajam" /> Tajam
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="tumpul" /> Tumpul
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="berdenyut" /> Berdenyut
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="radio" name="igd_karakter_nyeri" value="lainnya" /> Lainnya
                                                                        <input type="text" class="form-control" id="igd_karakter_nyeri_text"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <b>Skala Nyeri NRS(>=6th - Dewasa)</b>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <b>Total Skor</b>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="igd_skor_nyeri" />
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <b>Tipe</b>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_tipe_nyeri" value="ringan" /> Ringan
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_tipe_nyeri" value="sedang" /> Sedang
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_tipe_nyeri" value="berat" /> Berat
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="radio" name="igd_tipe_nyeri" value="berat_sekali" /> Berat Sekali
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="row" style="margin-top: 50px; padding: 0px 100px;">
                                                            <div class="col-md-12 scale-loader-image" id="scale-loader-image"></div>
                                                            <div class="col-md-12" id="scale-loader-define"></div>
                                                            <div class="col-md-12 scale-loader" id="scale-loader"></div>
                                                            <div class="col-md-12">
                                                                <input type="text" id="txt_nrs" class="slider">
                                                            </div>
                                                        </div> -->
                                                        <div class="row mt-4">
                                                            <div class="col-md-12">
                                                                <h5 class="text-center">Autralasian Triage Scale ( ATS )</h5>
                                                                <table class="table table-bordered ats-table">
                                                                    <tr>
                                                                        <td rowspan="2" class="vert-write">
                                                                            <span>
                                                                                DESKRIPSI <KLINIS></KLINIS>
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 20%; background: red; color: #fff">ATS 1</td>
                                                                        <td style="width: 20%; background: red; color: #fff">ATS 2</td>
                                                                        <td style="width: 20%; background: #ffc100; color: #fff">ATS 3</td>
                                                                        <td style="width: 20%; background: #ffc100; color: #fff">ATS 4</td>
                                                                        <td style="width: 20%; background: #24b400; color: #fff">ATS 5</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="ats-item" style="background: #ffb3b3;">
                                                                            <ul class="selection-list table-child">
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_1" /> Henti Jantung
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_2" /> Henti Nafas
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_3" /> Sumbatan Jalan Nafas
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_4" /> Respirasi < 10 x/menit
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_5" /> Gangguan Pernafasan Ekstrim
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_6" /> Tekanan darah < 80(dewasa) shok berat pada anak/bayi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_7" /> GCS < 9
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_8" /> Kejang berkepanjangan(lebih dari 10 menit/tidak berhenti)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_9" /> Henti Jantung
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="1_10" /> Henti Jantung
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">
                                                                            <ul class="selection-list table-child">
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_1"> Distres pernafasan/sesak nafas berat RR >= 35 x/menit
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_2"> Kurangnya perfusi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_3"> HR < 50 atau > 150(dewasa)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_4"> Hipotensi ringan systole < 90mmHg
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_5"> Kehilangan darah parah
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_6"> Nyeri dada karena jantung
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_7"> Nyeri parah oleh sebab apapun
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_8"> Mengantuk, penurunan respon oleh sebab apapun(GCS < 13)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_9"> BSL < 3mmol (GDS < 50 mg/dl)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_10"> Hemiparse acut / dysphasia
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_11"> Demam dengan tanga-tanda kelesuan
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_12"> Percikan asam / basa pada mata
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_12"> Multi trauma yang membutuhkan respon tim terorganisir
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_13"> Patah tulang besar, amputasi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_14"> Riwayat resiko tinggi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_15"> Keracunan sedatif atau tertelan racun
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="2_16"> Nyeri berat kehamilan ektopik (KET)
                                                                                </li>
                                                                                <li>
                                                                                    Perilaku Psikiatri
                                                                                    <ul class="selection-list table-child">
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="2_17">Kekesarasan/agresif
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox"  name="ats_check" value="2_18">Ancaman langsung terhadap diri sendiri dan orang lain
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="2_19">Memerlukan restrain
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="2_20">Agitasi berat
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">
                                                                            <ul class="selection-list table-child">
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_1"> Hipertensi berat(systole >= 180mmHg atau diastole >= 110mmHg)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_2"> Kehilangan darah cukup parah sebab apapun
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_3"> Sesak nafas sedang RR >= 26x/mnt
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_4"> SPO 90-95%
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_5"> BSL > 16mmol/GDS > 228mg/dl
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_6"> Kejang (saat ini kejang) < 10 menit
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_7"> Muntah terus menerus
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_8"> Dehidrasi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_9"> Cedera kepala dengan penurunan kesadaran
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_10"> Reaksi alergi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_11"> Nyeri berat
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_12"> Nyeri non jantung
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_13"> Pasien usia > 65 tahun
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_14"> Cedera sedang pada ekstremitas, deformitas, lecet dan hancur
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_15"> Cedera dengan mati rasa dan pulsasi menurun
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_16"> Neonatus stabil
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="3_17"> Anak dalam resiko
                                                                                </li>
                                                                                <li>
                                                                                    Perilaku Psikiatri
                                                                                    <ul class="selection-list table-child">
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_18">Sangat tertekan, resiko menyakiti diri
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_19">Acut psikotik, atau gangguan pola pikir
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_20">Kritis situsional, sengaja menyakiti diri
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_21">Gelisah, menarik diri
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_22">Berpotensi agresif
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_23">Luka robek memerlukan jahitan
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="3_24">Lecet parah
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">
                                                                            <ul class="selection-list table-child">
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_1"> Pendarahan ringan
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_2"> Hipertensi sedang (systole >= 160 mmHg atau diastole >= 100mmHg)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_3"> Cedera dada tanpa nyeri tulang rusuk, atau kesulitan bernafas
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_4"> Aspirasi benda asing tanpa gangguan pernafasan
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_5"> Kesulitan menelan, tidak ada gangguan pernafasan.
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_6"> Cedera kepala ringan, tidak ada kehilangan kesadaran
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_7"> Muntah atau diare tanpa dehidrasi
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_8"> Nyeri sedang
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_9"> Radang mata atau benda asing, penglihatan normal
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_10"> Terkilir pergelangan kaki/tangan, kemungkinan fraktur, vital sign normal, nyeri sedikit/sedang
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_19"> Sakit perut non spesifik
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="4_20"> Bengkak dan panas pada sendi
                                                                                </li>
                                                                                <li>
                                                                                    Perilaki Psikiatri
                                                                                    <ul class="selection-list table-child">
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="4_21">Masalah kesehatan, mental semi mendesak, resiko melukai diri sendiri atau orang lain
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                        <td class="ats-item" style="background: #ccffb3;">
                                                                            <ul class="selection-list table-child">
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="5_1"> Nyeri minimal tanpa resiko
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="5_2"> Hipertensi ringan (systole >= 150mmHg diatole >= 90mmHg)
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="5_3"> Luka ringan, lecet kecil, luka robek tidak memerlukan jahitan
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="5_4"> Kontrol luka
                                                                                </li>
                                                                                <li>
                                                                                    <input type="checkbox" name="ats_check" value="5_5"> Imunisasi
                                                                                </li>
                                                                                <li>
                                                                                    Perilaku Psikiatri
                                                                                    <ul class="selection-list table-child">
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="5_7">Pasien dengan gejala kronis. Krisis sosial secara klinis baik
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="5_8">Tidak ada riwayat sebelumnya atau asimtomatik
                                                                                        </li>
                                                                                        <li>
                                                                                            <input type="checkbox" name="ats_check" value="5_9">Gejala minor
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="lower-data">SKALA</td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">
                                                                            <input type="radio" value="skala_1" name="igd_skala_selected" /> SKALA 1</td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">
                                                                            <input type="radio" value="skala_2" name="igd_skala_selected" /> SKALA 2</td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">
                                                                            <input type="radio" value="skala_3" name="igd_skala_selected" /> SKALA 3</td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">
                                                                            <input type="radio" value="skala_4" name="igd_skala_selected" /> SKALA 4</td>
                                                                        <td class="ats-item" style="background: #ccffb3;">
                                                                            <input type="radio" value="skala_5" name="igd_skala_selected" /> SKALA 5</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="lower-data">SITUASI URGENSI</td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">RESUSITANSI</td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">EMERGENCY</td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">URGENT/DARURAT</td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">SEMI DARURAT</td>
                                                                        <td class="ats-item" style="background: #ccffb3;">TIDAK DARURAT</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="lower-data">RESPONSE TIME</td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">SEGERA</td>
                                                                        <td class="ats-item" style="background: #ffb3b3;">10 MENIT</td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">30 MENIT</td>
                                                                        <td class="ats-item" style="background: #ffe6b3;">60 MENIT</td>
                                                                        <td class="ats-item" style="background: #ccffb3;">120 MENIT</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <b>Pengkajian Medis (diisi oleh dokter)</b>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <b>Status Lokalis</b>
                                                                <div class="lokalis" style="position: relative">
                                                                    <div class="row">
                                                                        <div class="col-md-6" style="padding: 20px;">
                                                                            <img src="<?php echo $_POST['__HOSTNAME__']; ?>/template/assets/images/form/lokalis.png" width="550" height="500" style="opacity: .5" />
                                                                            <canvas style="position: absolute; border: solid 1px #808080; left: 0; left: 20px; top: 20px;" id="myCanvas_<?php echo $_POST['uid']; ?>" width="550" height="500"></canvas>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <table class="table table-bordered largeDataType" id="lokalis_value">
                                                                                <thead class="thead-dark">
                                                                                <tr>
                                                                                    <th class="wrap_content">No</th>
                                                                                    <th>Keterangan</th>
                                                                                    <th class="wrap_content">Aksi</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <ul class="selection-list table-child">
                                                                            <li>A: Abrasi</li>
                                                                            <li>C: Combustio</li>
                                                                            <li>V: Vulnus</li>
                                                                            <li>D: Deformitas</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <ul class="selection-list table-child">
                                                                            <li>U: Uikus</li>
                                                                            <li>H: Hematorna</li>
                                                                            <li>L: Lain-lain</li>
                                                                            <li>N: Nyeri</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <b>Pemeriksaan Penunjang</b>
                                                                <div class="form-group">
                                                                    EKG
                                                                    <textarea type="text" class="form-control" id="igd_ekg"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    Radiologi
                                                                    <textarea type="text" class="form-control" id="igd_radiologi"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    Laboratorium
                                                                    <textarea type="text" class="form-control" id="igd_radiologi"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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