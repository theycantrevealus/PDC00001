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
                            <th style="width: 25%">Pengkajian Keperawatan</th>
                            <th style="width: 25%">Masalah Keperawatan</th>
                            <th style="width: 25%">Tindakan Keperawatan</th>
                            <th style="width: 25%">Evaluasi (SOAP)</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <ol type="1" class="form-list-item">
                                        <li>
                                            <h6>Pendarahan</h6>
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
                                    </ol>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <small>Lokasi</small>
                                            </div>
                                        </div>
                                        <input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-prepended inputan" placeholder="Suhu" style="padding: 1px">
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <small>Luka Bakar</small>
                                            </div>
                                        </div>
                                        <input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="Suhu" style="padding: 1px">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <small>%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="Suhu" style="padding: 1px">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <small><sup>o</sup>C</small>
                                            </div>
                                        </div>
                                    </div>
                                    <ol type="1" class="form-list-item">
                                        <li>
                                            <h6>Kelembaban Kulit</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Lembab
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Kering
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <h6>Turgor Kulit</h6>
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
                                                            Kurang
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </td>
                                <td style="position:relative;">
                                    <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Masalah Keperawatan"></textarea>
                                </td>
                                <td style="position:relative;">
                                    <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Tindakan Keperawatan"></textarea>
                                </td>
                                <td style="position:relative;">
                                    <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Evaluasi SOAP"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <ol type="1" class="form-list-item">
                                        <li>
                                            <h6>Disability Kesadaran</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            CM
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Somnolen
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Delerium
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Apatis
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Soporokoma
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Koma
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
                                            <h6>Gangguan Perfusi Jar. Cerebral</h6>
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
                                            <h6>Mengobservasi</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Tingkat Kesadaran
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
                                                            GCS
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Head Up
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Hindari peningkaatan TIK
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
                                                            Rontgen/CT Scan
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
                                            <h6>Eksposure</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Vulnus
                                                            <div class="input-group input-group-merge">
                                                                <input type="text" id="berat_badanzzs" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Kedalaman Luka
                                                            <div class="row">
                                                                <div class="form-inline">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="exampleInputName2" style="width: 60px" />
                                                                        <label for="exampleInputEmail2">&times;</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="exampleInputName2" style="width: 60px" />
                                                                        <label for="exampleInputEmail3">&times;</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="exampleInputName2" style="width: 60px" />
                                                                        <label for="exampleInputEmail4">cm</label>
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
                                                            Ekskoriasi
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Ptekie
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Ptekie
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Hematoma
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Fraktur
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Dislokasi
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Abses
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Nyeri Andomen
                                                            <br />
                                                            Lokasi:
                                                            <textarea class="form-control"></textarea>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Masalah BAK
                                                            <textarea class="form-control"></textarea>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Masalah BAB
                                                            <textarea class="form-control"></textarea>
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
                                                            Potensial
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                    <hr />
                                    <h5>Gangguan Mobilitas</h5>
                                    <ol type="1" class="form-list-item">
                                        <li>
                                            <h6>Fisik</h6>
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
                                            <h6>Infeksi</h6>
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
                                                            Observasi Tingkat Nyeri
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Relaksasi
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Distraksi
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Batasi Aktifitas Fisik
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Pasang Bidai
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Cek PMS (Pulse Motorik Sensorik)
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Obserbasi Tanda Infeksi
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Teknik Septik Aseptik
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Rawat Luka(WT)+Heacting
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
                                                            Clysma/ Laksatif
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
                                                            Rontgen
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
                                <td>
                                    <ol type="1" class="form-list-item">
                                        <li>
                                            <h6>Fahrenheit (Suhu Tubuh)</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Suhu
                                                            <div class="input-group input-group-merge">
                                                                <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" />
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <span><sup>o</sup>C</span>
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
                                                            Lama Terpapar Suhu Panas/Dingin
                                                            <div class="input-group input-group-merge">
                                                                <input type="text" id="berat_badanzz" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" />
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <span>jam</span>
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
                                                            Riwayat Pemakaian Obat
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Pemberian Cairan Infuse yang terlalu dingin
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
                                            <h6>Gangguan Suhu Tubuh Hyperthermi</h6>
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
                                                            Mengobservasi Suhu Tubuh, Kaji TTV Kesadaran, Saturasi Oksigen, Irama Jantung
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Membuka Pakaian dengan menjaga Privacy
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Melakukan Kompres hangat/evaporasi/dingin
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                        <label class="form-check-label">
                                                            Mencukupi kebutuhan cairan per oral
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