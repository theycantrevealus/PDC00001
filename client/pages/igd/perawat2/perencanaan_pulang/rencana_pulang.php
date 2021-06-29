<p><h4>Perencanaan Pulang Pasien</h4></p>
<br />


<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"><!-- <i class="material-icons mr-3">info_outline</i> --> Informasi Pasien</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
					<table class="table">
						<tbody>
							<tr>
								<td>No. Rekam Medis</td>
								<td> : </td>
								<td><b><span class="no_rm"></span></b></td>
								<td>Tanggal Lahir</td>
								<td> : </td>
								<td><b><span class="tanggal_lahir"></span></b></td>
							</tr>
							<tr>
								<td>Nama Pasien</td>
								<td> : </td>
								<td><b><span class="panggilan"></span> <span class="nama"></span> </b></td>
								<td>Jenis Kelamin</td>
								<td> : </td>
								<td><b><span class="jenkel"></span></b></td>
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
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Subjektif</h5>
			</div>
			<div class="card-body row">
				<div class="col-md-12">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-6">
                            <label>Rencana Tanggal Pulang</label>
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="rencana_pulang_tanggal">
                        </div>
					</div>
					<div class="col-md-12 row form-group">
                        <div class="col-md-6">
                            <label>Rencana Jam Pulang</label>
                        </div>
                        <div class="col-md-6">
                            <input type="time" class="form-control" id="rencana_pulang_jam">
                        </div>
					</div>

					<div class="col-md-12 row form-group">
						<div class="col-md-6">
							<label>Usia 60 Tahun</label>
						</div>
						<div class="row col-md-6">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_usia_60" value="1" id="rencana_pulang_usia_60_1">
                                    <label class='form-check-label' for="rencana_pulang_usia_60_1">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_usia_60" value="0" id="krencana_pulang_usia_60_0">
                                    <label class='form-check-label' for="krencana_pulang_usia_60_0">Tidak</label>
                                </div>
                            </div>
                        </div>
					</div>

					<div class="col-md-12 row form-group">
						<div class="col-md-6">
							<label>Hambatan Mobilisasi</label>
						</div>
						<div class="row col-md-6">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_hambatan_mobilisasi" value="1" id="rencana_pulang_hambatan_mobilisasi_1">
                                    <label class='form-check-label' for="rencana_pulang_hambatan_mobilisasi_1">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_hambatan_mobilisasi" value="0" id="rencana_pulang_hambatan_mobilisasi_0">
                                    <label class='form-check-label' for="rencana_pulang_hambatan_mobilisasi_0">Tidak</label>
                                </div>
                            </div>
                        </div>
					</div>

					<div class="col-md-12 row form-group">
						<div class="col-md-6">
							<label>Butuh Pelayanan Medis dan Perawatan Berkelanjutan</label>
						</div>
						<div class="row col-md-6">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_butuh_layanan_medis" value="1" id="rencana_pulang_butuh_layanan_medis_1">
                                    <label class='form-check-label' for="rencana_pulang_butuh_layanan_medis_1">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_butuh_layanan_medis" value="0" id="rencana_pulang_butuh_layanan_medis_0">
                                    <label class='form-check-label' for="rencana_pulang_butuh_layanan_medis_0">Tidak</label>
                                </div>
                            </div>
                        </div>
					</div>

					<div class="col-md-12 row form-group">
						<div class="col-md-6">
							<label>Tergantung dengan orang lain dalam aktifitas harian</label>
						</div>
						<div class="row col-md-6">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_tergantung_orang_lain" value="1" id="rencana_pulang_tergantung_orang_lain_1">
                                    <label class='form-check-label' for="rencana_pulang_tergantung_orang_lain_1">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="rencana_pulang_tergantung_orang_lain" value="0" id="rencana_pulang_tergantung_orang_lain_0">
                                    <label class='form-check-label' for="rencana_pulang_tergantung_orang_lain_0">Tidak</label>
                                </div>
                            </div>
                        </div>
					</div>

					<div class="col-md-12 row form-group">
                        <div class="col-md-6">
                            <label>Transportasi Pulang</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="rencana_pulang_transportasi">
                        </div>
					</div>
					<div class="col-md-12 row form-group">
                        <div class="col-md-6">
                            <label>Orang yang mendampingin dan merawat pasien dirumah</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="rencana_pulang_pendamping_dirumah">
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
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Pengobatan yang dilanjutkan dirumah</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered" id="list_obat_dilanjutkan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Jumlah/ Dosis</th>
                                <th>Jam Pemberian</th>
								<th>Instruksi Khusus</th>
								<th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Kelanjutan Pemakaian Alat Medis/ Alat Bantu untuk di Rumah</h5>
			</div>
			<div class="card-body row">
                <div class="col-md-6">
					<p><b>Perawatan/ peralatan medis yang dilanjutkan di rumah</b></p>
                    <table class="table table-bordered" id="alat_medis_dilanjutkan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Alat Medis</th>
								<th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
				<div class="col-md-6">
					<p><b>Alat bantu yang dipakai di rumah</b></p>
                    <table class="table table-bordered" id="alat_bantu_dipakai_dirumah">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Alat Bantu</th>
								<th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Pendidikan Kesehatan untuk di rumah</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
					<div id="rencana_pulang_pendidikan_kesehatan" class="rencana_pulang_pendidikan_kesehatan"></div>
                </div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-5">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Diberikan kepada pasien/ keluarga</h5>
			</div>
			<div class="card-body">
				<div class="col-md-12">
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="diberikan_kepasien_obat" value="1" id="diberikan_kepasien_obat">
							<label for="diberikan_kepasien_obat">Obat - obatan</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="diberikan_kepasien_barang_pribadi" value="1" id="diberikan_kepasien_barang_pribadi">
							<label for="diberikan_kepasien_barang_pribadi">Peralatan/ barang pribadi</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="diberikan_kepasien_resep_obat" value="1" id="diberikan_kepasien_resep_obat">
							<label for="diberikan_kepasien_resep_obat">Resep obat</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="diberikan_kepasien_hasil_pemeriksaan_penunjang" value="1" id="diberikan_kepasien_hasil_pemeriksaan_penunjang">
							<label for="diberikan_kepasien_hasil_pemeriksaan_penunjang">Hasil pemeriksaan penunjang</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Jadwal kontrol berikutnya</h5>
			</div>
			<div class="card-body">
				<div class="col-md-12 row form-group">
					<div class="col-md-6">
						<label>Nama Dokter</label>
					</div>
					<div class="col-md-6">
						<select name="" id="jadwal_kontrol_nama_dokter" class="form-control">
							<option value="">Pilih dokter</option>
						</select>
					</div>
				</div>

				<div class="col-md-12 row form-group">
					<div class="col-md-6">
						<label>Tanggal <i>appointment</i></label>
					</div>
					<div class="col-md-6">
						<input type="date" class="form-control" id="jadwal_kontrol_tanggal">
					</div>
				</div>

				<div class="col-md-12 row form-group">
					<div class="col-md-6">
						<label>Jam <i>appointment</i></label>
					</div>
					<div class="col-md-6">
						<input type="time" class="form-control" id="jadwal_kontrol_jam">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Instruksi diberikan kepada</h5>
			</div>
			<div class="card-body">
				<div class="col-md-12">
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" dname="instruksi_diberikan_ke_pasien" value="1" id="instruksi_diberikan_ke_pasien">
							<label for="instruksi_diberikan_ke_pasien">Pasien</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="instruksi_diberikan_ke_keluarga" value="1" id="instruksi_diberikan_ke_keluarga">
							<label for="instruksi_diberikan_ke_keluarga">Keluarga</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="instruksi_diberikan_ke_orang_dekat" value="1" id="instruksi_diberikan_ke_orang_dekat">
							<label for="instruksi_diberikan_ke_orang_dekat">Orang Terdekat</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="instruksi_diberikan_ke_lainnya" value="1" id="instruksi_diberikan_ke_lainnya">
							<label for="instruksi_diberikan_ke_lainnya">Lainnya</label>
						</div>
					</div>
					<div class="col-md-5">
						<input type="text" disabled name="instruksi_diberikan_ke_lainnya_ket" id="instruksi_diberikan_ke_lainnya_ket" class="form-control inputan instruksi_diberikan_ke_lainnya_ket" placeholder="-">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>