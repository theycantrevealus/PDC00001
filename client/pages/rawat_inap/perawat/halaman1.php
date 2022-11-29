<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
            </div>
            <div class="card-body ">
                <div class="col-md-12">
                    <table class="table form-mode">
                        <tbody>
                            <tr>
                                <td>No. Rekam Medis</td>
                                <td> : </td>
                                <td><b><span id="no_rm"></span></b></td>
                                <td>Tanggal Lahir</td>
                                <td> : </td>
                                <td><b><span id="tanggal_lahir"></span></b></td>
                            </tr>
                            <tr>
                                <td>Nama Pasien</td>
                                <td> : </td>
                                <td><b><span id="panggilan"></span> <span id="nama"></span> </b></td>
                                <td>Jenis Kelamin</td>
                                <td> : </td>
                                <td><b><span id="jenkel"></span></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex align-items-center bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Informasi Pendaftaran</h5>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="col-md-6 row form-group">
                        <div class="col-md-4">
                            <label>Pendaftaran</label>
                        </div>
                        <div class="col-md-8">
                            <input type="" name="" id="waktu_masuk" disabled class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-6 row form-group">
                        <div class="col-md-4">
                            <label>Cara Pembayaran</label>
                        </div>
                        <div class="col-md-8">
                            <input type="" name="" id="nama_penjamin" disabled class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 row form-group">
                        <div class="col-md-4">
                            <label>Penanggung Jawab Pasien</label>
                        </div>
                        <div class="col-md-8">
                            <input type="" name="pj_pasien" id="pj_pasien" disabled class="form-control " value="">
                        </div>
                    </div>
                    <div class="col-md-6 row form-group">
                        <div class="col-md-4">
                            <label>Informasi di Dapat Dari</label>
                        </div>
                        <div class="col-md-8">
                            <input type="" name="info_didapat_dari" id="info_didapat_dari" disabled
                                class="form-control " value="">
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
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Informasi Awal</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item">
                    <li>
                        <h6>Informasi didapat dari</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="info_dari_0" class="form-check-input" type="radio" name="info_dari"
                                        value="Langsung" />
                                    <label for="info_dari_0" class="form-check-label">
                                        Auto Anamnesa/Langsung
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="info_dari_1" class="form-check-input" type="radio" name="info_dari"
                                        value="Tidak Langsung" />
                                    <label for="info_dari_1" class="form-check-label">
                                        Allow Anamnesa/Tidak Langsung
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <h6></h6>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="info_dari_nama">Nama Pengantar</label>
                                <input id="info_dari_nama" type="text" name="info_dari_nama" class="form-control inputan">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="info_dari_hub">Hubungan</label>
                                <input id="info_dari_hub" type="text" name="info_dari_hub" class="form-control inputan">
                            </div>
                        </div>
                    </li>
                    <li>
                        <h6>Cara Masuk</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="cara_masuk_0" class="form-check-input" type="radio" name="cara_masuk"
                                        value="Jalan Tanpa Bantuan" />
                                    <label class="form-check-label">
                                        Jalan Tanpa Bantuan
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="cara_masuk_1" class="form-check-input" type="radio" name="cara_masuk"
                                        value="Tempat Tidur Dorong" />
                                    <label class="form-check-label">
                                        Tempat Tidur Dorong
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="cara_masuk_2" class="form-check-input" type="radio" name="cara_masuk"
                                        value="Jalan Dengan Bantuan" />
                                    <label class="form-check-label">
                                        Jalan Dengan Bantuan
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="cara_masuk_3" class="form-check-input" type="radio" name="cara_masuk"
                                        value="Kursi Roda" />
                                    <label class="form-check-label">
                                        Kursi Roda
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>



                    <li>
                        <h6>Asal Masuk</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="asal_masuk_option" value="n"
                                        checked />
                                    <label class="form-check-label">
                                        Non Rujukan
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="asal_masuk_option" value="y" />
                                    <label class="form-check-label">
                                        Rujukan Dari
                                    </label>
                                    <input id='asal_masuk' type="text" name='asal_masuk'
                                        class="form-control inputan asal_masuk" />
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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Psikososial :</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Psikososial:</label>
                        <select class="form-control inputan select2" id="psikososial" name="psikososial">
                            <option value="">Pilih</option>
                            <option value="Tenang">Tenang</option>
                            <option value="Takut">Takut</option>
                            <option value="Marah">Marah</option>
                            <option value="Sedih">Sedih</option>
                            <option value="Cemas">Cemas</option>
                            <option value="Menangis">Menangis</option>
                            <option value="Gelisah">Gelisah</option>
                            <option value="Mudah Tersinggung">Mudah Tersinggung</option>
                            <option value="Membahayakan">Membahayakan diri sendiri / orang lain</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Hubungan pasien keluarga:</label>
                        <select class="form-control inputan select2" id="psikososial_hub_keluarga"
                            name="psikososial_hub_keluarga">
                            <option value="">Pilih</option>
                            <option value="Baik">Baik</option>
                            <option value="Tidak Baik">Tidak Baik</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Aktifitas Sosial:</label>
                        <select class="form-control inputan select2" id="psikososial_aktifitas_sosial"
                            name="psikososial_aktifitas_sosial">
                            <option value="">Pilih</option>
                            <option value="0">Tidak Ada</option>
                            <option value="1">Ada</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><i>Care Giver </i> (Pelaku Rawat)</label>
                        <select class="form-control inputan select2" id="psikososial_pelaku_rawat"
                            name="psikososial_pelaku_rawat">
                            <option value="">Pilih</option>
                            <option value="Sendiri">Sendiri</option>
                            <option value="Keluarga">Keluarga</option>
                            <option value="Perawat Khusus">Perawat Khusus</option>
                            <option value="Pekerja Sosial">Pekerja Sosial</option>
                            <option value="Panti Asuhan/Jompo">Panti Asuhan/Jompo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Orientasi Pada Pasien/Keluarga :</h5>
            </div>
            <div class="card-body">
                <label>Orientasi pada pasien/keluarga:</label>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien"
                                value="Ruangan/Kamar" id="orientasi_pd_pasien_0" />
                            <label for="orientasi_pd_pasien_0" class="form-check-label">
                                Ruangan/Kamar
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien"
                                value="Sistem Bel" id="orientasi_pd_pasien_1" />
                            <label for="orientasi_pd_pasien_1" class="form-check-label">
                                Sistem Bel
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien"
                                value="WC/Kamar Mandi" id="orientasi_pd_pasien_2"/>
                            <label for="orientasi_pd_pasien_2" class="form-check-label">
                                WC/Kamar Mandi
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien"
                                value="Pengatur Tempat Tidur" id="orientasi_pd_pasien_3"/>
                            <label for="orientasi_pd_pasien_3" class="form-check-label">
                                Pengatur Tempat Tidur
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien"
                                value="Pengaman Tempat Tidur" id="orientasi_pd_pasien_4" />
                            <label for="orientasi_pd_pasien_4" class="form-check-label">
                                Pengaman Tempat Tidur
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien"
                                value="TV & Remote Control" id="orientasi_pd_pasien_5" />
                            <label for="orientasi_pd_pasien_5" class="form-check-label">
                                TV & Remote Control
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orientasi_pd_pasien" value="Lainnya" id="orientasi_pd_pasien_6"/>
                            <label for="orientasi_pd_pasien_6" class="form-check-label">
                                Lainnya
                            </label>
                            <input id='orientasi_pd_pasien_ket' type="text" name='orientasi_pd_pasien_ket'
                                class="form-control inputan" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Barang Berharga :</h5>
            </div>
            <div class="card-body">
                <label>Barang berharga:</label>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="barang_berharga"
                                value="Kumpulkan dan Simpan ke Petugas RS" id="barang_berharga_0"/>
                            <label for="barang_berharga_0" class="form-check-label">
                                Kumpulkan dan Simpan ke Petugas RS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="barang_berharga"
                                value="Simpan Sendiri" id="barang_berharga_1"/>
                            <label for="barang_berharga_1" class="form-check-label">
                                Simpan Sendiri
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Harapan Privasi dan Kerohanian :</h5>
            </div>
            <div class="card-body">
                
                <div class="row mb-4">
                    <label class="col-lg-12">Permintaan privasi terhadap kunjungan umum:</label>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_kunjungan"
                                value="Ya" id="privasi_kunjungan_0"/>
                            <label for="privasi_kunjungan_0" class="form-check-label">
                                Ya
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_kunjungan"
                                value="Tidak" id="privasi_kunjungan_1" />
                            <label for="privasi_kunjungan_1" class="form-check-label">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-12">Permintaan privasi saat wawancara klinis:</label>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_wawancara"
                                value="Ya" id="privasi_wawancara_0"/>
                            <label for="privasi_wawancara_0" class="form-check-label">
                                Ya
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_wawancara"
                                value="Tidak" id="privasi_wawancara_1" />
                            <label for="privasi_wawancara_1" class="form-check-label">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>


                <div class="row mb-4">
                    <label class="col-lg-12">Permintaan privasi dan hasil saat pemeriksaan:</label>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_permeriksaan"
                                value="Ya" id="privasi_permeriksaan_0"/>
                            <label for="privasi_permeriksaan_0" class="form-check-label">
                                Ya
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_permeriksaan"
                                value="Tidak" id="privasi_permeriksaan_1" />
                            <label for="privasi_permeriksaan_1" class="form-check-label">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-12">Permintaan privasi saat prosedur/pengobatan:</label>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_pengobatan"
                                value="Ya" id="privasi_pengobatan_0" />
                            <label for="privasi_pengobatan_0" class="form-check-label">
                                Ya
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privasi_pengobatan"
                                value="Tidak" id="privasi_pengobatan_1" />
                            <label for="privasi_pengobatan_1" class="form-check-label">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-12">Permintaan informasi adanya pelayanan rohani:</label>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="permintaan_pelayanan_rohani"
                                value="Ya" id="permintaan_pelayanan_rohani_0" />
                            <label for="permintaan_pelayanan_rohani_0" class="form-check-label">
                                Ya
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="permintaan_pelayanan_rohani"
                                value="Tidak" id="permintaan_pelayanan_rohani_1"/>
                            <label for="permintaan_pelayanan_rohani_1" class="form-check-label">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="beri_pelayanan_rohani"
                                value="1" id="beri_pelayanan_rohani"/>
                            <label for="beri_pelayanan_rohani" class="form-check-label">
                                Bila Ya berikan informasi pelayanan rohani sesuai agama/ kepercayaan pasien
                            </label>
                        </div>
                    </div>
                </div>

                 <div class="row mb-4">
                    <label class="col-lg-12">ORANG YANG BERHAK MENDAPATKAN INFORMASI KONDISI PENYAKIT:</label>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mengetahui_kondisi"
                                value="Keluarga Terdekat" id="mengetahui_kondisi_0" />
                            <label for="mengetahui_kondisi_0" class="form-check-label">
                                Orang Tua
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mengetahui_kondisi"
                                value="Keluarga Terdekat" id="mengetahui_kondisi_1"/>
                            <label for="mengetahui_kondisi_1" class="form-check-label">
                                Keluarga Terdekat
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mengetahui_kondisi"
                                value="Lain" id="mengetahui_kondisi_2"/>
                            <label for="mengetahui_kondisi_2" class="form-check-label">
                                Hubungan
                                
                            </label>
                            <input id="mengetahui_kondisi_lain" type="text" name="mengetahui_kondisi_lain" class="form-control inputan">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>