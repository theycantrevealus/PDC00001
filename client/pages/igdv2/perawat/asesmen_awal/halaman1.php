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
							<input type="" name="info_didapat_dari" id="info_didapat_dari" disabled class="form-control " value="">
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
                                    <h6>Cara Masuk</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Jalan Tanpa Bantuan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Tempat Tidur Dorong
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Jalan Dengan Bantuan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
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
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Non Rujukan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Rujukan Dari
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <h6>Transportasi Waktu Datang</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Ambulance RSPB
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Ambulance Lain
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
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
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Tidak Ada
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Ada, Sakit
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </li>



                                <li>
                                    <h6>Riwayat Operasi</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Tidak Ada
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Ada, Operasi
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </li>


                                <li>
                                    <h6>Riwayat Pengobatan</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Tidak Ada
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Ada, Obat
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <h6>Status Kehamilan</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Tidak Hamil
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    Gravida
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    Abortus
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Tidak Hamil
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    Para
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    HPHT
                                                </label>
                                                <input type="text" class="form-control" />
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

                        </div>
                    </div>



                    <div class="card">
                        <div class="card-header card-header-large bg-white">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Diagnosa Medis</h5>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header card-header-large bg-white">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Skala Nyeri</h5>
                        </div>
                        <div class="card-body">
                            <ol type="1" class="form-list-item">
                                <li>
                                    <h6>Nyeri</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Ya, Lokasi
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </li>




                                <li>
                                    <h6>Frekuensi</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Sering
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Kadang
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Jarang
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </li>




                                <li>
                                    <h6>Karakteristik Nyeri</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Terbakar
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Tertindih
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Menyebar
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Tajam
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Tumpul
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Berdenyut
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
                                                <label class="form-check-label">
                                                    Lainnya
                                                </label>
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>Skala Nyeri NRS(>=6th - Dewasa)</b>
                                        </div>
                                    </div>
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
                                    <br />
                                    <div class="row">
                                        <div class="col-md-3">
                                            <b>NRS</b>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="igd_nrs" />
                                        </div>
                                    </div>
                                    <!-- <div class="row" style="margin-top: 50px; padding: 0px 50px; position: relative" id="nrs_1">
                                        <div class="col-md-12 scale-loader-image" id="scale-loader-image"></div>
                                        <div class="col-md-12" id="scale-loader-define"></div>
                                        <div class="col-md-12 scale-loader" id="scale-loader"></div>
                                        <div class="col-md-12">
                                            <input type="text" id="txt_nrs" class="slider">
                                        </div>
                                    </div> -->
                                </li>
                            </ol>
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
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="s" checked/>
                                                <label class="form-check-label">
                                                    Mandiri
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="simetris" value="a" />
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
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pengkajian Resiko Pasien Jatuh</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6">
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
                                            <td>0 - 5</td>
                                            <td>Resiko Tinggi</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            Total Skor
                                        </label>
                                        <input class="form-control" type="text" name="simetris" value="" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
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
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            Total Skor
                                        </label>
                                        <input class="form-control" type="text" name="simetris" value="" />
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