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
                            <td rowspan="2" style="position:relative;">
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Pengkajian Keperawatan"></textarea>
                            </td>
                            <td style="position:relative;">
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="Masalah Keperawatan"></textarea>
                            </td>
                            <td>
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
                                                        Terapy Antipiretik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemberian Infus
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
                                        </div>
                                    </li>
                                </ol>
                            </td>
                            <td style="position:relative;">
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="SOAP"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Gangguan Suhu</h6>
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
                                                        Mengobservasi Suhu Tubuh, Kaji TTV kesadaran,Saturasi Oksigen
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Membuka semua pakaian klien yang basah dengan menjaga privacy
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Melakukan penghangatan tubuh klien secara bertahap (1◦c/jam) dengan selimut tebal/warna blanket
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Mengkaji tanda-tanda cedera fisik akibat dingin:kulit melepuh, edema,timbulnya bulae vesikel,menggigil
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
                                                        Terapy Antipiretik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Pemberian Infus dengan air hangat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Menyiapkan alat intubasi jika diperlukan
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
                                <textarea class="form-control" style="position: absolute; left: 0; top: 0; height: 100%" placeholder="SOAP"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Psikososial</h5>
                                <ol type="1" class="form-list-item">
                                    <li>
                                        <h6>Hubungan dengan anggota keluarga</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Baik
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
                                        <h6>Status Psikologis</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Cemas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Takut
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Marah
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Sedih
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
                                        <h6>Gangguan Rasa Nyaman</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <textarea class="form-control" style="min-height: 200px"></textarea>
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
                                                        Kaji Perasaan Klien dan Keluarga
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Beri Empati
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Jelaskan ttg Kondisi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Klien, Rencana Perawatan & Prognosa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Libatkan Klien & Keluarga Dalam Pengambilan Keputusan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="simetris" value="s"/>
                                                    <label class="form-check-label">
                                                        Therapy Obat:
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
                            <td colspan="4">
                                <h5>Pemberian Obat / Infus</h5>
                                <table class="table table-bordered" id="autoInfusBiasa">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Pukul</th>
                                        <th style="width: 30%;">Nama Obat/Infus</th>
                                        <th style="width: 15%;">Dosis</th>
                                        <th style="width: 15%;">Rute</th>
                                        <th>Libatkan Klien & Keluarga Dalam Pengambilan Keputusan</th>
                                        <th style="width: 12%;">Diberikan Oleh</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>