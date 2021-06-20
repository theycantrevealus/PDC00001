<?php require 'info-pasien.php'; ?>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pengkajian</h5>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active show fade" id="pengkajian">
                    <table class="table table-bordered largeDataType">
                        <thead class="thead-dark">
                        <tr>
                            <th style="width: 25%">Data Objektif</th>
                            <th style="width: 25%">Analisa Data</th>
                            <th style="width: 25%">Rencana & Tindakan</th>
                            <th style="width: 25%">Evaluasi (SOAP)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>HIS</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        Frekuensi
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <span>/dtk</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        Interval
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <span>/mnt</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        Kekuatan
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <span>/mmHg</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <ol type="1" class="form-list-item no-caption">
                                    <li>
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Nyeri melingkar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Teratur interval pendek
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Intensitas kuat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Berpengaruh pada cervic
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Aktual
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Risiko
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Potensial
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <ol type="1" class="form-list-item no-caption">
                                    <li>
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Monitor BJA
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                                <hr />
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Kolaborasi</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemberian Oksigen:
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <span>ml/mnt</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pasang Infus
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        USG
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        CTG
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tindakan operatif
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Therapy Obat:
                                                        <textarea class="form-control"></textarea>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td style="position:relative;">
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Evaluasi SOAP"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <h5>Perkusi</h5>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Reflek Patela</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Positif
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Negatif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                        </tr>






                        <tr>
                            <td>
                                <h5>Auskultasi</h5>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Bunyi Jantung Fetus</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Teratur/baik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak teratur/batas normal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Dua denyut jantung janin yang berbeda
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak terdengar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Frekuensi 120-140/m
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kurang dari 120/m
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Lebih dari 160/m
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bising tali pusat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Gerak anak
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Ibu</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bising rahim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bunyi aorta
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bising usus
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <ol type="1" class="form-list-item no-caption">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Gemeli
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        IUFD
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Fetal destress
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kelainan letak janin
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Presentase janin
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Anak hidup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Gawat janin
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Aspiksia
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Aktual
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Risiko
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Potensial
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <ol type="1" class="form-list-item no-caption">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Monitor BJA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Beri pernapasan intra uterine
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Beri nutrisi sesuai program
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Jaga keseimbangan cairan & elektrolit
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Mengatur posisi ibu ke arah punggung anak
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Beri lingkungan yg aman
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Aktifitas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Istirahat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Edukasi
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Kolaborasi</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemberian Oksigen
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="RR" style="padding: 1px">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <small>ml/mnt</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pasang Infus
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pasang kateter urine
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemeriksaan labor
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        USG
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        CTG
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tindakan operatif obstetri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Therapy Obat
                                                        <textarea class="form-control"></textarea>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td style="position:relative;">
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Evaluasi SOAP"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Porsio</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Konsisten
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Lunak
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kaku
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Pendaftaran</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        25%
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        50%
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        75%
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        100%
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        PEMBUKAAN:
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="RR" style="padding: 1px">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <small>cm</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Ketuban</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Utuh
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pecah spontan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Dipecahkan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Jernih
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Keruh
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Mekonium
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Darah
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Presentase</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Belakang kepala
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bokong
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kaki
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tali pusat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tangan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Puncak kepala
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Muka
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Dahi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Plasenta
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Sutura melebar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ubun-ubun luas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tulang kepala tipis

                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Lutut
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bahu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Punggung
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Sacrum
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Uterus
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <ol type="1" class="form-list-item no-caption">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        KPD
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kelainan letak
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Partus lama
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Partus tak maju
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tali pusat menumbung
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kelainan jalan lahir
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Masa panggul sempit
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Plasenta previa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Presentase rangkap
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Hydrocefalus
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Inversio uteri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        IUFD
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Anak hidup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Anak luar rahim
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Hamil
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak hamil
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Aktual
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Risiko
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Potensial
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                </ol>
                            </td>
                            <td>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Observasi</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Keadaan umum
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        TTV
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        BJA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        HIS
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tanda-tanda inpartu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tanda-tanda infeksi
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Batasi periksa dalam
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Edukasi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Aktivitas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bedres total
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Kolaborasi</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemberian Oksigen
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="RR" style="padding: 1px">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <small>ml/mnt</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pasang Infus
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Beri antibiotik profilaksis
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemeriksaan labor
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Rontgen
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        USG
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        CTG
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Diagnosa banding
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tindakan operatif obstetri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Therapy Obat
                                                        <textarea></textarea>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td style="position:relative;">
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Evaluasi SOAP"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>