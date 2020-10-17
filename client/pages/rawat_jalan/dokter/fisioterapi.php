<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Terapi</h5>
            </div>
            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#terapis_asesmen" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Asesmen</a>
                <a href="#terapis_kontrol" data-toggle="tab" role="tab" aria-selected="false">Kontrol Program</a>
                <a href="#terapis_hasil" data-toggle="tab" role="tab" aria-selected="false">Hasil Terapis</a>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active show fade" id="terapis_asesmen">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0">#Anamnesa</h5>
                                        </div>
                                        <div class="card-body tab-content">
                                            <div id="txt_terapis_anamnesa"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0">#Pemeriksaan Fisik dan Uji Fungsi</h5>
                                        </div>
                                        <div class="card-body tab-content">
                                            <div id="txt_terapis_periksa_fisik"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0">#Diagnosa</h5>
                                        </div>
                                        <div class="card-header card-header-tabs-basic nav" role="tablist">
                                            <a href="#terapis_diagnosa_medis" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Diagnosa Medis</a>
                                            <a href="#terapis_diagnosa_fungsi" data-toggle="tab" role="tab" aria-selected="false">Diagnosa Fungsi</a>
                                        </div>
                                        <div class="card-body tab-content">
                                            <div class="tab-pane active show fade" id="terapis_diagnosa_medis">
                                                <div id="txt_terapis_diagnosa_medis"></div>
                                            </div>
                                            <div class="tab-pane show fade" id="terapis_diagnosa_fungsi">
                                                <div id="txt_terapis_diagnosa_fungsi"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
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
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0">#Detail</h5>
                                        </div>
                                        <div class="card-body tab-content">
                                            <div class="form-group col-lg-12">
                                                <label for="txt_tekanan_darah">#Anjuran Frekuensi</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" value="0" id="txt_terapis_frekuensi_bulan" class="form-control form-control-appended" placeholder="Tekanan Darah">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span>/Bulan</span>
                                                        </div>
                                                    </div>
                                                    <input type="text" value="0" id="txt_terapis_frekuensi_minggu" class="form-control form-control-appended" placeholder="Tekanan Darah">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span>/Minggu</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="txt_tekanan_darah">#Evaluasi</label>
                                                <div id="txt_terapis_evaluasi"></div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="txt_tekanan_darah">#Suspek Penyakit Akibat Kerja</label>
                                                <div id="terapis_evaluasi"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="terapis_kontrol">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">

                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="terapis_hasil">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>