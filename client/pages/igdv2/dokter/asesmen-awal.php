<?php
if($_SESSION['poli']['response_data'][0]['poli']['response_data'][0]['uid'] === __UIDFISIOTERAPI__) {
    ?>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Anamnesa</h5>
                </div>
                <div class="card-body tab-content">
                    <div id="txt_terapis_anamnesa"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Subjective</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#keluhan-utama" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Keluhan Utama</a>
                    <a href="#keluhan-tambahan" data-toggle="tab" role="tab" aria-selected="false">Keluhan Tambahan</a>
                </div>
                <div class="card-body tab-content" style="min-height: 100px;">
                    <div class="tab-pane active show fade" id="keluhan-utama">
                        <div class="edit-switch-container" target="txt_keluhan_utama">
                            <i class="fa fa-pencil-alt"></i> Edit
                        </div>
                        <div id="txt_keluhan_utama"></div>
                    </div>
                    <div class="tab-pane show fade" id="keluhan-tambahan">
                        <div class="edit-switch-container" target="txt_keluhan_tambahan">
                            <i class="fa fa-pencil-alt"></i> Edit
                        </div>
                        <div id="txt_keluhan_tambahan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Objective</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6" style="min-height: 200px;">
                            Pemeriksaan Fisik
                            <div class="edit-switch-container" target="txt_pemeriksaan_fisik">
                                <i class="fa fa-pencil-alt"></i> Edit
                            </div>
                            <div class="special-tab-fisioterapi col-md-12">
                                <div class="form-group">
                                    <label for="txt_icd_10_kerja">ICD 9</label>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="input-group input-group-merge">
                                                <select id="txt_icd_9" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <button class="btn btn-info" id="btn_tambah_icd9">
                                                <i class="fa fa-plus"></i> Tambah ICD9
                                            </button>
                                        </div>
                                    </div>
                                    <br />
                                    <table class="table" id="txt_fisik_list">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>ICD</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="txt_pemeriksaan_fisik"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="txt_tekanan_darah">Tekanan Darah</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" value="0" id="txt_tanda_vital_td" class="form-control form-control-appended" placeholder="Tekanan Darah" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>mmHg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="txt_nadi">Nadi</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" value="0" id="txt_tanda_vital_n" class="form-control form-control-appended" placeholder="Nadi" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>x/menit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="txt_suhu">Suhu</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" value="0" id="txt_tanda_vital_s" class="form-control form-control-appended" placeholder="Suhu" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span><sup>o</sup>C</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="txt_pernafasan">Pernafasan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" value="0" id="txt_tanda_vital_rr" class="form-control form-control-appended" placeholder="Pernafasan" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>x/menit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="txt_berat_badan">Berat Badan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" value="0" id="txt_berat_badan" class="form-control form-control-appended" placeholder="Berat Badan" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="txt_tinggi_badan">Tinggi Badan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="txt_tinggi_badan" class="form-control form-control-appended" placeholder="Tinggi Badan" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>cm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-8">
                                    <label for="txt_lingkar_lengan">Lingkar Lengan Atas</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" value="0" id="txt_lingkar_lengan" class="form-control form-control-appended" placeholder="Lingkar Lengan Atas" disabled />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>cm</span>
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
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Assesmen</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#asesmen-kerja" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Diagnosa Kerja</a>
                    <a href="#asesmen-banding" data-toggle="tab" role="tab" aria-selected="false">Diagnosa Banding</a>
                </div>
                <div class="card-body tab-content" style="min-height: 200px">
                    <div class="tab-pane active show fade" id="asesmen-kerja">
                        <div class="edit-switch-container" target="txt_diagnosa_kerja">
                            <i class="fa fa-pencil-alt"></i> Edit
                        </div>
                        <div class="form-group">
                            <label for="txt_icd_10_kerja">ICD 10</label>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="input-group input-group-merge">
                                        <select id="txt_icd_10_kerja" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <button class="btn btn-info" id="btn_tambah_icd10_kerja">
                                        <i class="fa fa-plus"></i> Tambah ICD10
                                    </button>
                                </div>
                            </div>
                            <br />
                            <table class="table" id="txt_diagnosa_kerja_list">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>ICD</th>
                                    <th class="wrap_content">Aksi</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <br />
                            <div id="txt_diagnosa_kerja"></div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="asesmen-banding">
                        <div class="edit-switch-container" target="txt_diagnosa_banding">
                            <i class="fa fa-pencil-alt"></i> Edit
                        </div>
                        <div class="form-group">
                            <label for="txt_icd_10_banding">ICD 10</label>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="input-group input-group-merge">
                                        <select id="txt_icd_10_banding" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <button class="btn btn-info" id="btn_tambah_icd10_banding">
                                        <i class="fa fa-plus"></i> Tambah ICD10
                                    </button>
                                </div>
                            </div>
                            <br />
                            <table class="table" id="txt_diagnosa_banding_list">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>ICD</th>
                                    <th class="wrap_content">Aksi</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <br />
                            <div id="txt_diagnosa_banding"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Planning : Penatalaksanaan/Pengobatan/Rencana Tindakan/Konsultasi/Edukasi</h5>
                </div>
                <div class="card-body" style="min-height: 200px">
                    <div class="edit-switch-container" target="txt_planning">
                        <i class="fa fa-pencil-alt"></i> Edit
                    </div>
                    <div id="txt_planning"></div>
                </div>
            </div>
        </div>
    </div>
<?php
if($_SESSION['poli']['response_data'][0]['poli']['response_data'][0]['uid'] === '5787593b-f840-4622-84f0-ce6a29fa62b8') {
    ?>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">#Tata Laksana KFR(ICD 9CM)</h5>
                </div>
                <div class="card-body tab-content">
                    <div id="txt_terapis_tatalaksana"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">#Detail</h5>
                </div>
                <div class="card-body tab-content">
                    <div class="form-group col-lg-12">
                        <label for="txt_tekanan_darah">Anjuran Frekuensi</label>
                        <div class="input-group input-group-merge">
                            <input type="text" value="" id="txt_terapis_frekuensi_bulan" class="form-control form-control-appended" placeholder="Tekanan Darah">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/Bulan</span>
                                </div>
                            </div>
                            <input type="text" value="" id="txt_terapis_frekuensi_minggu" class="form-control form-control-appended" placeholder="Tekanan Darah">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/Minggu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="txt_tekanan_darah">Evaluasi</label>
                        <div id="txt_terapis_evaluasi"></div>
                    </div>
                    <div class="form-group col-lg-12">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="">Suspek Penyakit Akibat Kerja: </label>
                            <br />
                            <input type="radio" name="suspek_kerja" value="n" checked="checked" /> Tidak &nbsp;&nbsp;
                            <input type="radio" name="suspek_kerja" value="y" /> Ya
                            <br />
                            <input type="text" class="form-control" disabled="disabled" id="suspek_kerja" placeholder="Suspek" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">#Hasil yang di dapat</h5>
                </div>
                <div class="card-body tab-content">
                    <div id="txt_terapis_hasil"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">#Kesimpulan</h5>
                </div>
                <div class="card-body tab-content">
                    <div id="txt_terapis_kesimpulan"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">#Rekomendasi</h5>
                </div>
                <div class="card-body tab-content">
                    <div id="txt_terapis_rekomendasi"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>