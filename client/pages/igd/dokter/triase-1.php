<div class="row">
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
                                    <input type="text" class="form-control uppercase" id="igd_status_alergi_text" placeholder="Sebutkan" disabled>
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
                                        <input type="text" class="form-control" id="igd_karakter_nyeri_text" disabled />
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
                        <div class="row" style="margin-top: 50px; padding: 0px 100px;">
                            <div class="col-md-12 scale-loader-image" id="scale-loader-image"></div>
                            <div class="col-md-12" id="scale-loader-define"></div>
                            <div class="col-md-12 scale-loader" id="scale-loader"></div>
                            <div class="col-md-12">
                                <input type="text" id="txt_nrs" class="slider">
                            </div>
                        </div>
                        <div class="row">
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
    </div>
</div>