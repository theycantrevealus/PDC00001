<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
            </div>
            <div class="card-body ">
                <div class="col-lg-12">
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
                    <div class="col-lg-6 row form-group">
                        <div class="col-lg-4">
                            <label>Pendaftaran</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="" name="" id="waktu_masuk" disabled class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-lg-6 row form-group">
                        <div class="col-lg-4">
                            <label>Cara Pembayaran</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="" name="" id="nama_penjamin" disabled class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6 row form-group">
                        <div class="col-lg-4">
                            <label>Penanggung Jawab Pasien</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="" name="pj_pasien" id="pj_pasien" disabled class="form-control " value="">
                        </div>
                    </div>
                    <div class="col-lg-6 row form-group">
                        <div class="col-lg-4">
                            <label>Informasi di Dapat Dari</label>
                        </div>
                        <div class="col-lg-8">
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
                                    <input id="cara_masuk_3" class="form-check-input" type="radio" name="cara_masuk" value="Kursi Roda" />
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
                                    <input class="form-check-input" type="radio" name="asal_masuk_option" value="n" checked />
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
                                    <input id='asal_masuk' type="text" name='asal_masuk' class="form-control inputan asal_masuk" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Transportasi Waktu Datang</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="transport_datang_0" class="form-check-input" type="radio" name="transport_datang" value="Ambulance RSPB"/>
                                    <label class="form-check-label">
                                        Ambulance RSPB
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="transport_datang_1" class="form-check-input" type="radio" name="transport_datang" value="Ambulance Lain" />
                                    <label class="form-check-label">
                                        Ambulance Lain
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="transport_datang_2" class="form-check-input" type="radio" name="transport_datang" value="Kendaraan Lain" />
                                    <label class="form-check-label">
                                        Kendaraan Lain
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Riwayat Penyakit Dulu</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_penyakit_option" value="n" checked />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_penyakit_option" value="y" />
                                    <label class="form-check-label">
                                        Ada, Sakit
                                    </label>
                                    <input id='riwayat_penyakit' type="text" name="riwayat_penyakit" class="form-control inputan riwayat_penyakit"/>
                                </div>
                            </div>
                        </div>
                    </li>



                    <li>
                        <h6>Riwayat Operasi</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_operasi_option" value="n" checked />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_operasi_option" value="y" />
                                    <label class="form-check-label">
                                        Ada, Operasi
                                    </label>
                                    <input type="text" id="riwayat_operasi" name='riwayat_operasi' class="form-control inputan riwayat_operasi" />
                                </div>
                            </div>
                        </div>
                    </li>


                    <li>
                        <h6>Riwayat Pengobatan</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_pengobatan_option" value="n" checked />
                                    <label class="form-check-label">
                                        Tidak Ada
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="riwayat_pengobatan_option" value="y" />
                                    <label class="form-check-label">
                                        Ada, Obat
                                    </label>
                                    <input type="text" id="riwayat_pengobatan" name="riwayat_pengobatan" class="form-control inputan riwayat_pengobatan" />
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <h6>Status Kehamilan</h6>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_hamil_option" value="n" checked />
                                    <label class="form-check-label">
                                        Tidak Hamil
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        Gravida
                                    </label>
                                    <input type="text" id="status_hamil_g" name="status_hamil_g" class="form-control inputan status_hamil_g" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        Abortus
                                    </label>
                                    <input type="text" id="status_hamil_a" name="status_hamil_a" class="form-control inputan status_hamil_a" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_hamil_option" value="y" />
                                    <label class="form-check-label">
                                       Hamil
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        Para
                                    </label>
                                    <input type="text" id="status_hamil_p" name="status_hamil_p" class="form-control inputan status_hamil_p"/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        HPHT
                                    </label>
                                    <input type="text" id="status_hamil_h" name="status_hamil_h" class="form-control inputan status_hamil_h" />
                                </div>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
        </div>



        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Keluhan Utama</h5>
            </div>
            <div class="card-body">
                <div class="row col-lg-12" id="">
					<textarea class="form-control inputan keluhan_utama" id="keluhan_utama" placeholder="-"></textarea>
                </div>
            </div>
        </div>



        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Diagnosa Medis</h5>
            </div>
            <div class="card-body">
                <div class="row col-lg-12" id="">
					<textarea class="form-control inputan diagnosa_medis" id="diagnosa_medis" placeholder="-"></textarea>
                </div>
            </div>
        </div>

        <div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Skala Nyeri:</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-3">
						<label>Nyeri: </label>
						<select class="form-control inputan select2" id="nyeri" name="nyeri">
							<option value="" disabled selected>Pilih</option>
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group col-lg-3">
						<label>Lokasi: </label>
						<input type="text" name="nyeri_lokasi" id="nyeri_lokasi" class="form-control inputan" placeholder="-">
					</div>
					<div class="form-group col-lg-3">
						<label>Frekuensi: </label>
						<select class="form-control inputan select2" name="nyeri_frekuensi" id="nyeri_frekuensi">
							<option value="" disabled selected>Pilih</option>
							<option value="Sering">Sering</option>
							<option value="Kadang">Kadang</option>
							<option value="Jarang">Jarang</option>
						</select>
					</div>
					<div class="col-6 col-lg-6 mb-3">
						<label for="k">Karakteristik Nyeri:</label>

                        <div class="row">
                            <div class="col-lg-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_terbakar" value="1" id="nyeri_terbakar">
                                                    <label class="form-check-label" for="nyeri_terbakar">
                                                        Terbakar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_tumpul" value="1" id="nyeri_tumpul">
                                                    <label class="form-check-label" for="nyeri_tumpul">
                                                        Tumpul
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-lg-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_tertindih" value="1" id="nyeri_tertindih">
                                                    <label class="form-check-label" for="nyeri_tertindih">
                                                        Tertindih
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_denyut" value="1" id="nyeri_denyut">
                                                    <label class="form-check-label" for="nyeri_denyut">
                                                        Berdenyut
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-lg-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_menyebar" value="1" id="nyeri_menyebar">
                                                    <label class="form-check-label" for="nyeri_menyebar">
                                                        Menyebar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_tajam" value="1" id="nyeri_tajam">
                                                    <label class="form-check-label" for="nyeri_tajam">
                                                        Tajam
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-lg-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_lainnya" value="1" id="nyeri_lainnya" onclick='disableCheckboxChild(this, "nyeri_lainnya_ket")'>
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
					<div class="form-group col-lg-3">
						<label>Nyeri Lainnya: </label>
						<input disabled type="text" name="nyeri_lainnya_ket" id="nyeri_lainnya_ket" class="form-control inputan nyeri_lainnya_ket" placeholder="-">
					</div>
					<div class="form-group col-lg-4">
						<label>Skala Nyeri NRS ( &gt; 6th - Dewasa)</label>
						<input placeholder="-" type="text" id="nyeri_skala" name="nyeri_skala" class="form-control inputan" />
					</div>
					<div class="form-group col-lg-3">
						<label>Total Skor: </label>
						<input type="text" placeholder="-" name="nyeri_total_skor" id="nyeri_total_skor" class="form-control inputan">
					</div>
					<div class="form-group col-lg-3">
						<label>Tipe: </label>
						<select class="form-control inputan select2" name="nyeri_tipe" id="nyeri_tipe">
							<option value="" disabled selected>Pilih</option>
							<option value="Ringan">Ringan</option>
							<option value="Sedang">Sedang</option>
							<option value="Berat">Berat</option>
							<option value="Berat Sekali">Berat Sekali</option>
						</select>
					</div>
				</div>
			</div>
		</div>

        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pengkajian Fungsi</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item">
                    <li>
                        <h6>Aktifitas Sehari-hari</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="kaji_aktifitas_0" class="form-check-input" type="radio" name="kaji_aktifitas" value="Mandiri"/>
                                    <label class="form-check-label">
                                        Mandiri
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-check">
                                    <input id="kaji_aktifitas_1" class="form-check-input" type="radio" name="kaji_aktifitas" value="Dengan Bantuan" />
                                    <label class="form-check-label">
                                        Dengan Bantuan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
        </div>



        <div class="card">
            <div class="card-header card-header-large bg-white">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pengkajian Resiko Pasien Jatuh
                </h5>
            </div>
            <div class="card-body row">
                <div class="col-lg-6">
                    <h5>Skala Jatuh Dewasa(MORSE)</h5>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Skor</th>
                                <th>Tingkat Resiko</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0 - 5</td>
                                <td>Resiko Sedang</td>
                            </tr>
                            <tr>
                                <td>6 - 13</td>
                                <td>Resiko Rendah</td>
                            </tr>
                            <tr>
                                <td>>= 14</td>
                                <td>Resiko Tinggi</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <label class="form-check-label">
                                Total Skor
                            </label>
                            <input id="kaji_resiko_jatuh_dewasa" class="form-control inputan kaji_resiko_jatuh_dewasa" type="text" name="kaji_resiko_jatuh_dewasa" value="" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h5>Skala Jatuh Anak(Humpty Dumpty)</h5>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Skor</th>
                                <th>Tingkat Resiko</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>7 - 11</td>
                                <td>Resiko Rendah</td>
                            </tr>
                            <tr>
                                <td>>=12</td>
                                <td>Resiko Sedang</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-lg-3">
                        <div class="form-check">
                            <label class="form-check-label">
                                Total Skor
                            </label>
                            <input id="kaji_resiko_jatuh_anak" class="form-control inputan kaji_resiko_jatuh_anak" type="text" name="kaji_resiko_jatuh_anak" value="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script type="text/javascript">
	var sliderNyeri = new rSlider({
        target: '#skala_nyeri',
        values: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        range: false,
        set: [0],
        tooltip: false,
        onChange: function (vals) {
            console.log(vals);
        }
    });
</script> -->