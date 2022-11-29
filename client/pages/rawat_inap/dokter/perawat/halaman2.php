<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Riwayat Kesehatan</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item">
                    <li>
                        <h6>Diagnosa Masuk :</h6>
                        <div class="row">
                            <div class="col-lg-12">
                                <textarea rows="4" name="diagnosa_masuk" id="diagnosa_masuk"
                                    class="form-control inputan"></textarea>
                            </div>
                        </div>
                    </li>
                    <li>
                        <h6>Riwayat Kesehatan Sekarang (Alasan masuk RS/ keluhan utama) :</h6>
                        <div class="row">
                            <div class="col-lg-12">
                                <textarea rows="4" name="keluhan_utama" id="keluhan_utama"
                                    class="form-control inputan"></textarea>
                            </div>
                        </div>
                    </li>

                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Riwayat Penyakit</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item">
                    <li>
                        <h6>Riwayat Penyakit</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_penyakit"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_penyakit" value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Sebutkan:
                                    </label>
                                    <input id='riwayat_penyakit_ket' type="text" name='riwayat_penyakit_ket'
                                        class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Pernah Dirawat</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_rawat"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_rawat" value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Kapan:
                                    </label>
                                    <input id='riwayat_rawat_ket' type="text" name='riwayat_rawat_ket'
                                        class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Ketergantungan</h6>
                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <select class="form-control inputan select2" id="riwayat_ketergantungan">
                                        <option value="">Pilih</option>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <label for="">Berupa:</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="riwayat_ketergantungan_obat" value="1"
                                                id="riwayat_ketergantungan_obat">
                                            <label class="form-check-label" for="riwayat_ketergantungan_obat">
                                                Obat-obatan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="riwayat_ketergantungan_rokok" value="1"
                                                id="riwayat_ketergantungan_rokok">
                                            <label class="form-check-label" for="riwayat_ketergantungan_rokok">
                                                Rokok
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="riwayat_ketergantungan_alkohol" value="1"
                                                id="riwayat_ketergantungan_alkohol">
                                            <label class="form-check-label" for="riwayat_ketergantungan_alkohol">
                                                Alkohol
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Operasi</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_operasi"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_operasi" value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Sebutkan:
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-inline">
                                                <label for="riwayat_operasi_ket_0" class="mr-2">Kapan</label>
                                                <input id='riwayat_operasi_ket_0' type="text"
                                                    name='riwayat_operasi_ket_0' class="form-control inputan" />
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-inline">
                                                <label for="riwayat_operasi_ket_1" class="mr-2">Operasi</label>
                                                <input id='riwayat_operasi_ket_1' type="text"
                                                    name='riwayat_operasi_ket_1' class="form-control inputan" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Pernah Ada Masalah Dengan Operasi/ Pembiusan Pasien :</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_masalah_operasi"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_masalah_operasi"
                                        value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Sebutkan:
                                    </label>
                                    <input id='riwayat_masalah_operasi_ket' type="text"
                                        name='riwayat_masalah_operasi_ket' class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Kecelakaan :</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_kecelakaan"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_kecelakaan"
                                        value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Kapan:
                                    </label>
                                    <input id='riwayat_kecelakaan_ket' type="text" name='riwayat_kecelakaan_ket'
                                        class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Kemoterapi :</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_kemoterapi"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_kemoterapi"
                                        value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Kapan:
                                    </label>
                                    <input id='riwayat_kemoterapi_ket' type="text" name='riwayat_kemoterapi_ket'
                                        class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Radioterapi :</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_radioterapi"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_radioterapi"
                                        value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Kapan:
                                    </label>
                                    <input id='riwayat_radioterapi_ket' type="text" name='riwayat_radioterapi_ket'
                                        class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Obat Dari Rumah :</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_bawa_obat"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_bawa_obat" value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Diserahkan ke Farmasi:
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Apakah Anda Pernah Mendapatkan Obat Pengencer Darah (Aspirin, Warpirin, Plavix) ?</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_obat_pengencer"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_obat_pengencer"
                                        value="Ya" />
                                    <label class="form-check-label">
                                        Ya, Sebutkan:
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-inline">
                                                <input id='riwayat_obat_pengencer_ket_0' type="text"
                                                    name='riwayat_obat_pengencer_ket_0' class="form-control inputan" />
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-inline">
                                                <label for="riwayat_obat_pengencer_ket_1" class="mr-2">Kapan
                                                    dihentikan</label>
                                                <input id='riwayat_obat_pengencer_ket_1' type="text"
                                                    name='riwayat_obat_pengencer_ket_1' class="form-control inputan" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Imunisasi :</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_imunisasi"
                                        value="Tidak Ada" />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_imunisasi" value="Ada" />
                                    <label class="form-check-label">
                                        Ada, Kapan:
                                    </label>
                                    <input id='riwayat_imunisasi_ket' type="text" name='riwayat_imunisasi_ket'
                                        class="form-control inputan" />
                                </div>
                            </div>
                        </div>
                    </li>


                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pemeriksaan Fisik</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item">
                    <li>
                        <h6>Tanda Vital :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label for="txt_td">TD</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="tanda_vital_td" name="tanda_vital_td"
                                        class="form-control form-control-appended inputan" placeholder="TD">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>mmHg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="txt_n">N</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="tanda_vital_n" id="tanda_vital_n"
                                        class="form-control form-control-appended inputan" placeholder="N">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>x/mnt</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="txt_s">S</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="tanda_vital_s" id="tanda_vital_s"
                                        class="form-control form-control-appended inputan" placeholder="S">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>Celcius</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="txt_rr">RR</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="tanda_vital_rr" id="tanda_vital_rr"
                                        class="form-control form-control-appended inputan" placeholder="RR">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>x/mnt</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="txt_bb">BB</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="tanda_vital_bb" id="tanda_vital_bb"
                                        class="form-control form-control-appended inputan" placeholder="BB">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="txt_bb">TB</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="tanda_vital_tb" id="tanda_vital_tb"
                                        class="form-control form-control-appended inputan" placeholder="TB">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Kesadaran :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label for="">GCS</label>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label class="mr-2" for="kesadaran_gcs_e">E:</label>
                                        <input type="text" name="kesadaran_gcs_e" class="form-control inputan"
                                            id="kesadaran_gcs_e" style="width: 60px" placeholder="E">
                                    </div>
                                    <div class="form-group">
                                        <label class="mr-2 ml-2" for="kesadaran_gcs_m">M:</label>
                                        <input type="text" name="kesadaran_gcs_m" class="form-control inputan"
                                            id="kesadaran_gcs_m" style="width: 60px" placeholder="M">
                                    </div>
                                    <div class="form-group">
                                        <label class="mr-2 ml-2" for="kesadaran_gcs_v">V:</label>
                                        <input type="text" name="kesadaran_gcs_v" class="form-control inputan"
                                            id="kesadaran_gcs_v" style="width: 60px" placeholder="V">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Refleks Cahaya</label>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <input type="text" name="kesadaran_cahaya_ka" class="form-control inputan"
                                            id="kesadaran_cahaya_ka" style="width: 60px" placeholder="Ka">
                                    </div>
                                    <div class="form-group">
                                        <label class="mx-2">/</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="kesadaran_cahaya_ki" class="form-control inputan"
                                            id="kesadaran_cahaya_ki" style="width: 60px" placeholder="Ki">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="">Ukuran Pupil</label>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <input type="text" name="kesadaran_pupil_ka" class="form-control inputan"
                                            id="kesadaran_pupil_ka" style="width: 60px" placeholder="mm">
                                    </div>
                                    <div class="form-group">
                                        <label class="mx-2">/</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="kesadaran_pupil_ki" class="form-control inputan"
                                            id="kesadaran_pupil_ki" style="width: 60px" placeholder="mm">
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </li>

                    <li>
                        <h6>Rambut Kepala :</h6>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rambut_bersih" id="rambut_bersih"
                                        value="1" />
                                    <label for="rambut_bersih" class="form-check-label">
                                        Bersih
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rambut_kotor" value="1" id="rambut_kotor"/>
                                    <label for="rambut_kotor" class="form-check-label">
                                        Kotor
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rambut_kusam" value="1" id="rambut_kusam" />
                                    <label for="rambut_kusam" class="form-check-label">
                                        Kusam
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rambut_rontok" value="1" id="rambut_rontok"/>
                                    <label for="rambut_rontok" class="form-check-label">
                                        Rontok
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Mata :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mata_normal"
                                        value="1" id="mata_normal" />
                                    <label for="mata_normal" class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mata_sklera" value="1" id="mata_sklera" />
                                    <label for="mata_sklera" class="form-check-label">
                                        Sklera Ikterik
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mata_bersekret" value="1" id="mata_bersekret" />
                                    <label for="mata_bersekret" class="form-check-label">
                                        Bersekret
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mata_kacamata" value="1" id="mata_kacamata" />
                                    <label for="mata_kacamata" class="form-check-label">
                                        Kacamata
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mata_katarak" value="1" id="mata_katarak" />
                                    <label for="mata_katarak" class="form-check-label">
                                        Katarak
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mata_konjungtivita" value="1" id="mata_konjungtivita" />
                                    <label for="mata_konjungtivita" class="form-check-label">
                                        Konjungtivita Anemis
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                   <li>
                        <h6>Hidung :</h6>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hidung_normal"
                                        value="1" id="hidung_normal"/>
                                    <label for="hidung_normal" class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hidung_tersumbat" value="1" id="hidung_tersumbat" />
                                    <label for="hidung_tersumbat" class="form-check-label">
                                        Tersumbat
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hidung_sekret" value="1" id="hidung_sekret" />
                                    <label for="hidung_sekret" class="form-check-label">
                                        Sekret (+)
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hidung_epistaksis" value="1" id="hidung_epistaksis"/>
                                    <label for="hidung_epistaksis" class="form-check-label">
                                        Epistaksis
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Mulut :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulut_bersih"
                                        value="1" id="mulut_bersih"/>
                                    <label for="mulut_bersih" class="form-check-label">
                                        Bersih
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulut_kotor" value="1" id="mulut_kotor"/>
                                    <label for="mulut_kotor" class="form-check-label">
                                        Kotor
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulut_bau" value="1" id="mulut_bau" />
                                    <label for="mulut_bau" class="form-check-label">
                                        Berbau
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulut_mukosa_kering" value="1" id="mulut_mukosa_kering" />
                                    <label for="mulut_mukosa_kering" class="form-check-label">
                                        Mukosa Kering
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mulut_stomatitis" value="1" id="mulut_stomatitis" />
                                    <label for="mulut_stomatitis" class="form-check-label">
                                        Stomatitis
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <h6>Bibir :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bibir_normal"
                                        value="1" id="bibir_normal"/>
                                    <label for="bibir_normal" class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bibir_kering" value="1" id="bibir_kering" />
                                    <label for="bibir_kering" class="form-check-label">
                                        Kering
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bibir_sumbing" value="1" id="bibir_sumbing" />
                                    <label for="bibir_sumbing" class="form-check-label">
                                        Sumbing
                                    </label>
                                </div>
                            </div>
                        </div>

                        <br/>
                        <h6>Lidah :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="lidah_bersih"
                                        value="1" id="lidah_bersih" />
                                    <label for="lidah_bersih" class="form-check-label">
                                        Bersih
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="lidah_kotor" value="1" id="lidah_kotor" />
                                    <label for="lidah_kotor" class="form-check-label">
                                        Kotor
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="lidah_iperemik" value="1" id="lidah_iperemik"/>
                                    <label for="lidah_iperemik" class="form-check-label">
                                        Iperemik
                                    </label>
                                </div>
                            </div>
                        </div>

                        <br/>
                        <h6>Gigi :</h6>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gigi_bersih"
                                        value="1" id="gigi_bersih" />
                                    <label for="gigi_bersih" class="form-check-label">
                                        Bersih
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gigi_kotor" value="1" id="gigi_kotor" />
                                    <label for="gigi_kotor" class="form-check-label">
                                        Kotor
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gigi_ompong" value="1" id="gigi_ompong" />
                                    <label for="gigi_ompong" class="form-check-label">
                                        Ompong
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gigi_kawat" value="1" id="gigi_kawat"/>
                                    <label for="gigi_kawat" class="form-check-label">
                                        Kawat Gigi
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gigi_palsu" value="1" id="gigi_palsu"/>
                                    <label for="gigi_palsu" class="form-check-label">
                                        Gigi Palsu
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                    <h6>Telinga :</h6>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="telinga_bersih"
                                        value="1" id="telinga_bersih" />
                                    <label for="telinga_bersih" class="form-check-label">
                                        Bersih
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="telinga_kotor" value="1" id="telinga_kotor"/>
                                    <label for="telinga_kotor" class="form-check-label">
                                        Kotor
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="telinga_tuli" value="1" id="telinga_tuli"/>
                                    <label for="telinga_tuli" class="form-check-label">
                                        Tuli Kanan/Kiri
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="telinga_alat_bantu" value="1" id="telinga_alat_bantu" />
                                    <label for="telinga_alat_bantu" class="form-check-label">
                                        Alat Bantu Dengar Kanan/Kiri
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                    <h6>Leher :</h6>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="leher_normal"
                                        value="1" id="leher_normal" />
                                    <label for="leher_normal" class="form-check-label">
                                        Normal
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="leher_benjolan" value="1" id="leher_benjolan"/>
                                    <label for="leher_benjolan" class="form-check-label">
                                        Ada Benjolan
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="leher_kaku" value="1" id="leher_kaku" />
                                    <label for="leher_kaku" class="form-check-label">
                                        Kaku Kuduk
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="leher_tracheostomi" value="1" id="leher_tracheostomi"/>
                                    <label for="leher_tracheostomi" class="form-check-label">
                                        Tracheostomi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>


                </ol>
            </div>
        </div>
    </div>
</div>