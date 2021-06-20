<?php require 'info-pasien.php'; ?>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pengkajian</h5>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active show fade" id="pengkajian">
                    <div class="row">
                        <div class="col-md-12" style="padding-bottom: 20px;">
                            <h5>Partus (diisi jika pasien partus di IGD)</h5>
                            <table class="form-mode" style="width: 100%;">
                                <tr>
                                    <td>Partus Tanggal</td>
                                    <td class="wrap_content">:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input class="form-control" type="text" />
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span>Jam</span>
                                                        </div>
                                                    </div>
                                                    <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span>WIB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bayi</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Positif
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Negatif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span>BB</span>
                                                        </div>
                                                    </div>
                                                    <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span>Gram</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span>PB</span>
                                                        </div>
                                                    </div>
                                                    <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span>Gram</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Anus</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ada
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak Ada
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cacat Bawaan</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ada
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak Ada
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span>Scoring Apgar</span>
                                                        </div>
                                                    </div>
                                                    <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-prepended inputan numberonly" required="" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <table class="table table-bordered largeDataType">
                        <thead class="thead-dark">
                        <tr>
                            <th style="width: 25%">Pengkajian Kebidanan</th>
                            <th style="width: 25%">Masalah Kebidanan</th>
                            <th style="width: 25%">Tindakan Kebidanan</th>
                            <th style="width: 25%">Evaluasi (SOAP)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Airway</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bebas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Hidung/Mulut Kotor
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Sputum
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
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Benda Padat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Spasme
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pangkal Lidah Jatuh
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Suara Napas</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Normal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Stridor
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Snoring
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Gurgling
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Gasping
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak Ada Suara Nafas
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
                                        <h6>Gangguan Bersihan Jalan Nafas</h6>
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
                                                        Head Tilt
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Chin Lift
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Jaw Trust
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Oro Faringeal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Naso Faringeal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Melakukan Suction
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Hidrasi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pasang Collar Neck
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Batuk Efektif
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Fowler/Semi Fowler
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Posisi Miring
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Posisi Mantap PS Tidak Sadar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Auskultasi Paru Secara Periodik
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
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span>Breathing RR</span>
                                        </div>
                                    </div>
                                    <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>x/m</span>
                                        </div>
                                    </div>
                                </div>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Irama Nafas</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Teratur
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak Teratur
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Bunyi Nafas</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Vesikuler
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Wheezing
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ronchi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Crakhels
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Penggunaan Otot Bantu Pernafasan</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Retraksi Dada
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Cuping Hidung
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Pola Nafas</h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Apneu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Dipsneu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Bradipneu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tachipneu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Orthopneu
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </li>
                                    <li>
                                        <h6>Jalan Pernafasan</h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pernafasan Dada
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pernafasan Perut
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
                                        <h6>Pola Nafas Tidak Efektif</h6>
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
                                                        Memonitor RR, Irama, Kedalaman Nafas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Memonitor Penggunaan Otot Bantu Nafas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ajarkan Teknik Nafas Dalam
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Mengatur Posisi Semi Fowler Jika Tidak Ada Kontra Indikasi
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
                                                        Inhalasi
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
                                                        Pemeriksaan Darah
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        (AGD)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Theraphy Obat:
                                                        <textarea class="form-control" style="min-height: 200px;"></textarea>
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
                                        <h6>Circulation Pucat</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ya
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Pengisian Kapiler</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        < 2 detik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        > 2 detik
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>
                                            <div class="input-group input-group-merge">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <span>Nadi</span>
                                                    </div>
                                                </div>
                                                <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span>x/m</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Teraba
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak Teraba
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Akral</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Hangat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Dingin
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Edema
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <h6>Sianosis</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Ya
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Tidak
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <div class="input-group input-group-merge">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <span>TD</span>
                                                                </div>
                                                            </div>
                                                            <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <span>mmHg</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Riwayat Kehilangan Cairan</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Muntah
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Diare
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <h5>Gangguan Rasa</h5>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Nyaman Nyeri</h6>
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
                                                        <textarea class="form-control"></textarea>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                                <hr />
                                <h5>Gangguan Keseimbangan</h5>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Cairan dan Elektrolit</h6>
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
                                <hr />
                                <h5>Gangguan Perfusi</h5>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Jaringan Perifer</h6>
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
                                    <li>
                                        <h6>Diare</h6>
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
                                        <h6>Mengukur</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Status Dehidrasi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kekuatan Nadi Perifer
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Intake Output
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Balance Cairan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        CPV
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Perubahan Turgor, Membran Mukosa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Kapiler Refill
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
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Memasang Infus
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Memasang NGT
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Memasang Kateter Urine
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemeriksaan Labor
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
                                                        Tranfusi Darah
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>