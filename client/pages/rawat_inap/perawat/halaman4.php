<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pemeriksaan Fisik</h5>
            </div>
            <div class="card-body">
                <ol type="1" start="5" class="form-list-item">
                    <li>
                        <h6>Skala Nyeri</h6>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Nyeri: </label>
                                <select class="form-control inputan select2" id="nyeri" name="nyeri">
                                    <option value="">Pilih</option>
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Lokasi: </label>
                                <input type="text" name="nyeri_lokasi" id="nyeri_lokasi" class="form-control inputan"
                                    placeholder="-">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Frekuensi: </label>
                                <select class="form-control inputan select2" name="nyeri_frekuensi"
                                    id="nyeri_frekuensi">
                                    <option value="">Pilih</option>
                                    <option value="Sering">Sering</option>
                                    <option value="Kadang">Kadang</option>
                                    <option value="Jarang">Jarang</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label for="k">Karakteristik Nyeri:</label>

                                <div class="row">
                                    <div class="col-md-3">
                                        <ol type="1" class="form-list-item" style="list-style-type: none">
                                            <li>
                                                <h6></h6>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_terbakar" value="1" id="nyeri_terbakar">
                                                            <label class="form-check-label" for="nyeri_terbakar">
                                                                Terbakar
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_tumpul" value="1" id="nyeri_tumpul">
                                                            <label class="form-check-label" for="nyeri_tumpul">
                                                                Tumpul
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col-md-3">
                                        <ol type="1" class="form-list-item" style="list-style-type: none">
                                            <li>
                                                <h6></h6>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_tertindih" value="1" id="nyeri_tertindih">
                                                            <label class="form-check-label" for="nyeri_tertindih">
                                                                Tertindih
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_denyut" value="1" id="nyeri_denyut">
                                                            <label class="form-check-label" for="nyeri_denyut">
                                                                Berdenyut
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col-md-3">
                                        <ol type="1" class="form-list-item" style="list-style-type: none">
                                            <li>
                                                <h6></h6>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_menyebar" value="1" id="nyeri_menyebar">
                                                            <label class="form-check-label" for="nyeri_menyebar">
                                                                Menyebar
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_tajam" value="1" id="nyeri_tajam">
                                                            <label class="form-check-label" for="nyeri_tajam">
                                                                Tajam
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col-md-3">
                                        <ol type="1" class="form-list-item" style="list-style-type: none">
                                            <li>
                                                <h6></h6>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="nyeri_lainnya" value="1" id="nyeri_lainnya"
                                                                onclick='disableCheckboxChild(this, "nyeri_lainnya_ket")'>
                                                            <label class="form-check-label" for="nyeri_lainnya">
                                                                Lainnya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Nyeri Lainnya: </label>
                                <input disabled type="text" name="nyeri_lainnya_ket" id="nyeri_lainnya_ket"
                                    class="form-control inputan nyeri_lainnya_ket" placeholder="-">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Skala Nyeri NRS ( &gt; 5th - Dewasa)</label>
                                <input placeholder="-" type="text" id="nyeri_skala" name="nyeri_skala"
                                    class="form-control inputan" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Total Skor: </label>
                                <input type="text" placeholder="-" name="nyeri_total_skor" id="nyeri_total_skor"
                                    class="form-control inputan">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tipe: </label>
                                <select class="form-control inputan select2" name="nyeri_tipe" id="nyeri_tipe">
                                    <option value="">Pilih</option>
                                    <option value="Ringan">Ringan</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Berat">Berat</option>
                                    <option value="Berat Sekali">Berat Sekali</option>
                                </select>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Skrining Gizi</h6>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Parameter</th>
                                            <th style="width:100px ;">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><strong>Mengalami penurunan berat badan yang tidak
                                                    diinginkan dalam 3 bulan terakhir ?</strong></td>
                                        </tr>
                                        <tr>
                                            <td>- Tidak ada penurunan berat badan</td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td>- Ya/ Tidak Yakin/ Tidak Tahu*</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Jika ya, berapa penurunan berat badan tersebut
                                                    ?</strong></td>
                                        </tr>
                                        <tr>
                                            <td>1 - 5 kg</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>6- 10 kg</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>11 - 15 kg</td>
                                            <td>3</td>
                                        </tr>
                                        <tr>
                                            <td>> 16 kg</td>
                                            <td>4</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td>
                                                <input type="text" placeholder="-" name="gizi_total_skor"
                                                    id="gizi_total_skor" class="form-control inputan">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Bila skor >2, dilakukan pengkajian lebih lanjut oleh ahli gizi</p>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Pengkajian Risiko Dekubitus (Skala Norton)</h6>
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Skor</th>
                                            <th>Tingkat Resiko</th>
                                            <th>Checklist</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>16 - 20</td>
                                            <td>Sangat Berisiko</td>
                                            <td>
                                                <input type="radio" name="kaji_risiko_dekunitus" value="1" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>11 - 15</td>
                                            <td>Risiko Tinggi</td>
                                            <td>
                                                <input type="radio" name="kaji_risiko_dekunitus" value="2" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6 - 11</td>
                                            <td>Risiko Sedang</td>
                                            <td>
                                                <input type="radio" name="kaji_risiko_dekunitus" value="3" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                < 5</td>
                                            <td>Tidak Risiko</td>
                                            <td>
                                                <input type="radio" name="kaji_risiko_dekunitus" value="4" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Pengkajian Luka</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kaji_luka" value="Tidak"
                                        id="kaji_luka_0" />
                                    <label for="kaji_luka_0" class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-inline">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="kaji_luka" value="Ya"
                                            id="kaji_luka_1" />
                                        <label for="kaji_luka_1" class="form-check-label">
                                            Ya, Lanjutkan di Form Pengkajian Luka
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Pengkajian Restrain</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kaji_restrain" value="Tidak"
                                        id="kaji_restrain_0" />
                                    <label for="kaji_restrain_0" class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-inline">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="kaji_restrain" value="Ya"
                                            id="kaji_restrain_1" />
                                        <label for="kaji_restrain_1" class="form-check-label">
                                            Ya, Lanjutkan di Form Pengkajian Luka
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6> Kebutuhan Pendidikan/ Komunikasi</h6>
                        <ol type="A">
                            <li>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="">Berbicara:</label>
                                        <select id="komunikasi_bicara" class="form-control inputan select2">
                                            <option value="">Pilih</option>
                                            <option value="Normal">Normal</option>
                                            <option value="Gangguang Bicara">Gangguan Bicara</option>
                                        </select>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="">Hambatan Belajar:</label>
                                        <select id="komunikasi_hambatan" name="komunikasi_hambatan"
                                            class="form-control inputan select2">
                                            <option value="">Pilih</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
                                            <option value="Bahasa">Bahasa</option>
                                            <option value="Kognitif">Kognitif</option>
                                            <option value="Hilang Memori">Hilang Memori</option>
                                            <option value="Motivasi Memburuk">Motivasi Memburuk</option>
                                            <option value="Faktor Budaya">Faktor Budaya</option>
                                            <option value="Tidak Percaya Diri">Tidak Percaya Diri</option>
                                            <option value="Emosi">Emosi</option>
                                            <option value="Masalah Penglihatan">Masalah Penglihatan</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="">Lainnya:</label>
                                        <input type="text" class="form-control inputan komunikasi_hambatan_lainnya"
                                            name="komunikasi_hambatan_lainnya" id="komunikasi_hambatan_lainnya" disabled
                                            placeholder="-">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="">Pasien/Keluarga menginginkan informasi tentang :</label>
                                        <select id="komunikasi_kebutuhan_belajar" name="komunikasi_kebutuhan_belajar"
                                            class="form-control inputan select2">
                                            <option value="">Pilih</option>
                                            <option value="Proses Penyakit">Proses Penyakit</option>
                                            <option value="Terapi/Obat">Terapi/Obat</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="">Lainnya:</label>
                                        <input disabled type="text"
                                            class="form-control inputan komunikasi_kebutuhan_belajar_lainnya"
                                            name="komunikasi_kebutuhan_belajar_lainnya"
                                            id="komunikasi_kebutuhan_belajar_lainnya" placeholder="-">
                                    </div>
                                </div>
                            </li>
                        </ol>

                    </li>

                    <li>
                        <h6>Penggunaan Alat Medis</h6>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for=""></label>
                                <select id="alat_medis" class="form-control inputan select2">
                                    <option value="">Pilih</option>
                                    <option value="Tidak">Tidak</option>
                                    <option value="Ya">Ya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2  form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_medis_kateter" value="1"
                                        id="alat_medis_kateter" />
                                    <label for="alat_medis_kateter" class="form-check-label">
                                        Kateter,
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-10 form-group">
                                <div class="form-inline">
                                    <label for="alat_medis_kateter_tgl" class="mr-2">Tgl.Pasang</label>
                                    <input id="alat_medis_kateter_tgl" type="date" name="alat_medis_kateter_tgl"
                                        class="form-control inputan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_medis_ngt" value="1"
                                        id="alat_medis_ngt" />
                                    <label for="alat_medis_ngt" class="form-check-label">
                                        NGT,
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-10 form-group">
                                <div class="form-inline">
                                    <label for="alat_medis_ngt_tgl" class="mr-2">Tgl.Pasang</label>
                                    <input id="alat_medis_ngt_tgl" type="date" name="alat_medis_ngt_tgl"
                                        class="form-control inputan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_medis_ivplug" value="1"
                                        id="alat_medis_ivplug" />
                                    <label for="alat_medis_ivplug" class="form-check-label">
                                        IV Plug,
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-10 form-group">
                                <div class="form-inline">
                                    <label for="alat_medis_ivplug_tgl" class="mr-2">Tgl.Pasang</label>
                                    <input id="alat_medis_ivplug_tgl" type="date" name="alat_medis_ivplug_tgl"
                                        class="form-control inputan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_medis_infus" value="1"
                                        id="alat_medis_infus" />
                                    <label for="alat_medis_infus" class="form-check-label">
                                        Infus,
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-10 form-group">
                                <div class="form-inline">
                                    <label for="alat_medis_infus_tgl" class="mr-2">Tgl.Pasang</label>
                                    <input id="alat_medis_infus_tgl" type="date" name="alat_medis_infus_tgl"
                                        class="form-control inputan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_medis_drain" value="1"
                                        id="alat_medis_drain" />
                                    <label for="alat_medis_drain" class="form-check-label">
                                        Drain,
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-10 form-group">
                                <div class="form-inline">
                                    <label for="alat_medis_drain_tgl" class="mr-2">Tgl.Pasang</label>
                                    <input id="alat_medis_drain_tgl" type="date" name="alat_medis_drain_tgl"
                                        class="form-control inputan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_medis_lain" value="1"
                                        id="alat_medis_lain" />
                                    <label for="alat_medis_lain" class="form-check-label">
                                        Lain-lain,
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-10 form-group">
                                <div class="form-inline">
                                    <label for="alat_medis_lain_tgl" class="mr-2">Tgl.Pasang</label>
                                    <input id="alat_medis_lain_tgl" type="date" name="alat_medis_lain_tgl"
                                        class="form-control inputan">
                                </div>
                            </div>
                        </div>

                    </li>

                    <li>
                        <h6> Pemeriksaan Penunjang Yang Dibawa</h6>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered" id="autoPeriksaPenunjang">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Jenis Pemeriksaan</th>
                                            <th>Asal Pemeriksaan</th>
                                            <th>Jumlah</th>
                                            <th>Penerima</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </li>


                </ol>
            </div>
        </div>
    </div>
</div>