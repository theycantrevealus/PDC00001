<p><h4>Pengkajian Kulit</h4></p>
<!-- <p><i><h6>(wajib dilengkapi dalam 24 jam pertama pasien masuk ruang rawat)</h6></i></p> -->
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
				<h5 class="card-header__title flex m-0"></h5>
			</div>
			<div class="card-body ">
				<div class="row">
					<div class="col-md-12 row form-group">
						<div class="col-md-4">
							<label>Skor Braden</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="kulit_skor_braden" id="kulit_skor_braden" class="form-control " value="">
						</div>
					</div>
					<div class="col-md-12 row form-group">
						<div class="col-md-4">
							<label>Risiko dekubitus</label>
						</div>
						<div class="row col-md-8">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kulit_risiko_dekubitus" value="1" id="kulit_risiko_dekubitus_1">
                                    <label class='form-check-label' for="kulit_risiko_dekubitus_1">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kulit_risiko_dekubitus" value="0" id="kulit_risiko_dekubitus_0">
                                    <label class='form-check-label' for="kulit_risiko_dekubitus_0">Tidak</label>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="col-md-12 row form-group">
						<div class="col-md-4">
							<label>Terdapat luka</label>
						</div>
						<div class="row col-md-8">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kulit_terdapat_luka" value="1" id="kulit_terdapat_luka_1">
                                    <label class='form-check-label' for="kulit_terdapat_luka_1">Ya</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kulit_terdapat_luka" value="0" id="kulit_terdapat_luka_0">
                                    <label class='form-check-label' for="kulit_terdapat_luka_0">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8" id="lokasi_luka" hidden>
                            <label>Lokasi luka</label>
                            <textarea name="kulit_terdapat_luka_keterangan" class="form-control" id="kulit_terdapat_luka_keterangan" cols="30" rows="5"></textarea>
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
				<h5 class="card-header__title flex m-0"></h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Parameter</th>
                                <th width="30%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <p><b>PERSEPSI SENSORI</b></p>
                                    <p>Kemampuan untuk merespon ketidaknyamanan tekanan</p>
                                </td>
                                <td>
                                    <select name="kulit_persepsi_sensori" id="kulit_persepsi_sensori" class="form-control table_kaji_kulit">
                                        <option value="1">Tidak Berespon (1)</option>
                                        <option value="2">Sangat Terbatas (2)</option>
                                        <option value="3">Sedikit Terbatas (3)</option>
                                        <option value="4">Tidak Ada Gangguan (4)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <p><b>KELEMBAPAN</b></p>
                                    <p>Sejauh mana kulit terpapar kelembapan</p>
                                </td>
                                <td>
                                    <select name="kulit_kelembapan" id="kulit_kelembapan" class="form-control table_kaji_kulit">
                                        <option value="1">Kelembapan Konstan (1)</option>
                                        <option value="2">Sering Lembab (2)</option>
                                        <option value="3">Kadang Lembab (3)</option>
                                        <option value="4">Jarang Lembab (4)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <p><b>AKTIVITAS</b></p>
                                    <p>Tingkat aktifitas fisik</p>
                                </td>
                                <td>
                                    <select name="kulit_aktifitas" id="kulit_aktifitas" class="form-control table_kaji_kulit">
                                        <option value="1">Tergeletak di Tempat Tidur (1)</option>
                                        <option value="2">Tidak Bisa Berjalan (2)</option>
                                        <option value="3">Berjalan pada Jarak Terbatas (3)</option>
                                        <option value="4">Berjalan disekitar Ruangan (4)/option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>
                                    <p><b>MOBILITAS</b></p>
                                    <p>Kemampuan bergerak</p>
                                </td>
                                <td>
                                    <select name="kulit_mobilitas" id="kulit_mobilitas" class="form-control table_kaji_kulit">
                                        <option value="1">Tidak Bisa Bergerak (1)</option>
                                        <option value="2">Sangat Terbatas (2)</option>
                                        <option value="3">Sedikit Terbatas (3)</option>
                                        <option value="4">Tidak Ada Batasan (4)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <p><b>NUTRISI</b></p>
                                    <p>Pola asupan makanan</p>
                                </td>
                                <td>
                                    <select name="kulit_nutrisi" id="kulit_nutrisi" class="form-control table_kaji_kulit">
                                        <option value="1">Sangat Buruk (1)</option>
                                        <option value="2">Kurang Adekuat (2)</option>
                                        <option value="3">Adekuat (3)</option>
                                        <option value="4">Sangat Baik (4)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <p><b>FRIKSI DAN GESEKAN</b></p>
                                </td>
                                <td>
                                    <select name="kulit_friksi_gesekan" id="kulit_friksi_gesekan" class="form-control table_kaji_kulit">
                                        <option value="1">Masalah (1)</option>
                                        <option value="2">Potensi Masalah (2)</option>
                                        <option value="3">Tidak Ada Masalah (3)</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <td colspan="2"><b>TOTAL PARAMETER</b></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br />
                <div class="col-md-12 row form-group">
                    <div class="col-md-6">
                        <label>Terlampir formulir pemeriksaan kelompok khusus</label>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_lampiran_formulir_pemeriksaan" value="1" id="kulit_lampiran_formulir_pemeriksaan_1">
                                <label class='form-check-label' for="kulit_lampiran_formulir_pemeriksaan_1">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_lampiran_formulir_pemeriksaan" value="0" id="kulit_lampiran_formulir_pemeriksaan_0">
                                <label class='form-check-label' for="kulit_lampiran_formulir_pemeriksaan_0">Tidak</label>
                            </div>
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
				<h5 class="card-header__title flex m-0">Pengkajian Kebutuhan Informasi dan Edukasi</h5>
			</div>
			<div class="card-body ">
                <p><h5><u>Persiapan</u></h5></p>
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label>Bahasa</label>
                    </div>
                    <div class="row col-md-9">
						<div class="col-md-3">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_bahasa_indonesia" value="Indonesia" id="kulit_bahasa_indonesia">
								<label for="kulit_bahasa_indonesia">Indonesia</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_bahasa_inggris" value="Inggris" id="kulit_bahasa_inggris">
								<label for="kulit_bahasa_inggris">Inggris</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_bahasa_daerah" value="Daerah" id="kulit_bahasa_daerah">
								<label for="kulit_bahasa_daerah">Daerah</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_bahasa_lainnya" value="Lainnya" id="kulit_bahasa_lainnya">
								<label for="kulit_bahasa_lainnya">Lain-lain</label>
							</div>
						</div>
                    </div>
				</div>
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label>Kebutuhan penterjemah</label>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_kebutuhan_penterjemah" value="1" id="kulit_kebutuhan_penterjemah_1">
                                <label class='form-check-label' for="kulit_kebutuhan_penterjemah_1">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_kebutuhan_penterjemah" value="0" id="kulit_kebutuhan_penterjemah_0">
                                <label class='form-check-label' for="kulit_kebutuhan_penterjemah_0">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label>Pendidikan Pasien</label>
                    </div>
                    <div class="row col-md-9">
						<div class="col-md-2">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pendidikan_pasien_sd" value="SD" id="kulit_pendidikan_pasien_sd">
								<label for="kulit_pendidikan_pasien_sd">SD</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pendidikan_pasien_smp" value="SMP" id="kulit_pendidikan_pasien_smp">
								<label for="kulit_pendidikan_pasien_smp">SMP</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pendidikan_pasien_sma" value="SMA" id="kulit_pendidikan_pasien_sma">
								<label for="kulit_pendidikan_pasien_sma">SMA</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pendidikan_pasien_s1" value="S1" id="kulit_pendidikan_pasien_s1">
								<label for="kulit_pendidikan_pasien_s1">S1</label>
							</div>
						</div>
                        <div class="col-md-3">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pendidikan_pasien_lainnya" value="S1" id="kulit_pendidikan_pasien_lainnya">
								<label for="kulit_pendidikan_pasien_lainnya">Lain-lain</label>
							</div>
						</div>
                    </div>
				</div>
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label>Baca tulis</label>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_baca_tulis" value="Baik" id="kulit_baca_tulis_baik">
                                <label class='form-check-label' for="kulit_baca_tulis_baik">Baik</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_baca_tulis" value="Kurang" id="kulit_baca_tulis_kurang">
                                <label class='form-check-label' for="kulit_baca_tulis_kurang">Kurang</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label>Pilihan cara belajar</label>
                    </div>
                    <div class="row col-md-9">
						<div class="col-md-2">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pilihan_cara_belajar_verbal" value="Verbal" id="kulit_pilihan_cara_belajar_verbal">
								<label for="kulit_pilihan_cara_belajar_verbal">Verbal</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_pilihan_cara_belajar_tulisan" value="Tulisan" id="kulit_pilihan_cara_belajar_tulisan">
								<label for="kulit_pilihan_cara_belajar_tulisan">Tulisan</label>
							</div>
						</div>
                    </div>
				</div>
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label>Budaya/ Suku/ Etnis</label>
                    </div>
                    <div class="row col-md-9">
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_budaya_suku_etnis" value="1" id="kulit_budaya_suku_etnis">
								<input disabled type="text" class="form-control" id="kulit_budaya_suku_etnis_ket" name="kulit_budaya_suku_etnis_ket">
							</div>
						</div>
                    </div>
				</div>
                <hr />

                <p><h5><u>Hambatan</u></h5></p>
                <div class="row">
					<div class="col-md-4">
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_tidak_ada" value="Tidak Ada" id="kulit_hambatan_tidak_ada">
								<label for="kulit_hambatan_tidak_ada">Tidak Ada</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_budaya" value="Budaya/agama/spiritual" id="kulit_hambatan_budaya">
								<label for="kulit_hambatan_budaya">Budaya/ Agama/ Spiritual</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_gangguan_bicara" value="Gangguan Bicara" id="kulit_hambatan_gangguan_bicara">
								<label for="kulit_hambatan_gangguan_bicara">Gangguan Bicara</label>
							</div>
						</div>
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_bahasa" value="Bahasa" id="kulit_hambatan_bahasa">
								<label for="kulit_hambatan_bahasa">Bahasa</label>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_emosional" value="Emosional" id="kulit_hambatan_emosional">
								<label for="kulit_hambatan_emosional">Emosional</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_motivasi" value="Motivasi Kurang" id="kulit_hambatan_motivasi">
								<label for="kulit_hambatan_motivasi">Motivasi Kurang</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_kognitif_terbatas" value="Kognitif Terbatas" id="kulit_hambatan_kognitif_terbatas">
								<label for="kulit_hambatan_kognitif_terbatas">Kognitif Terbatas</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_keyakinan" value="Keyakinan" id="kulit_hambatan_keyakinan">
								<label for="kulit_hambatan_keyakinan">Keyakinan</label>
							</div>
						</div>
					</div>
					<div class="col-md-4">
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_pendengaran" value="Pendengaran Terganggu" id="kulit_hambatan_pendengaran">
								<label for="kulit_hambatan_pendengaran">Pendengaran Terganggu</label>
							</div>
						</div>
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_penglihatan" value="Pendengaran Terganggu" id="kulit_hambatan_penglihatan">
								<label for="kulit_hambatan_penglihatan">Penglihatan Terganggu</label>
							</div>
						</div>
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_fisik_lemah" value="Fisik Lemah" id="kulit_hambatan_fisik_lemah">
								<label for="kulit_hambatan_fisik_lemah">Fisik Lemah</label>
							</div>
						</div>
						<!-- <div class="col-md-12">	
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_hambatan_fisik_lainnya" value="1" id="kulit_hambatan_fisik_lainnya">
								<label for="kulit_hambatan_fisik_lainnya">Lain-lain</label>
							</div>
						</div> -->
					</div>
				</div>

                <p><h5><u>Kebutuhan</u></h5></p>
                <p>(pilih topik pembelajaran pada kotak yang tersedia)</p>

                <div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_proses_penyakit" value="Proses Penyakit" id="kulit_kebutuhan_proses_penyakit">
								<label for="kulit_kebutuhan_proses_penyakit">Proses Penyakit</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_cegah_faktor_risiko" value="Pencegahan Faktor Risiko" id="kulit_kebutuhan_cegah_faktor_risiko">
								<label for="kulit_kebutuhan_cegah_faktor_risiko">Pencegahan Faktor Risiko</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_lingkungan" value="Lingkungan yang perlu dipersiapkan pasca rawat" id="kulit_kebutuhan_lingkungan">
								<label for="kulit_kebutuhan_lingkungan">Lingkungan yang perlu dipersiapkan pasca rawat</label>
							</div>
						</div>
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_privasi" value="Kebutuhan Privasi Tambahan" id="kulit_kebutuhan_privasi">
								<label for="kulit_kebutuhan_privasi">Kebutuhan Privasi Tambahan</label>
							</div>
						</div>
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_obat" value="Obat-obatan" id="kulit_kebutuhan_obat">
								<label for="kulit_kebutuhan_obat">Obat-obatan</label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_manajemen_nyeri" value="Manajemen Nyeri" id="kulit_kebutuhan_manajemen_nyeri">
								<label for="kulit_kebutuhan_manajemen_nyeri">Manajemen Nyeri</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_prosedur" value="Prosedur (contoh: cara perawatan luka)" id="kulit_kebutuhan_prosedur">
								<label for="kulit_kebutuhan_prosedur">Prosedur (contoh: cara perawatan luka)</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_diet_nutrisi" value="Diet dan Nutrisi" id="kulit_kebutuhan_diet_nutrisi">
								<label for="kulit_kebutuhan_diet_nutrisi">Diet dan Nutrisi</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_rehabilitasi" value="Rehabilitasi" id="kulit_kebutuhan_rehabilitasi">
								<label for="kulit_kebutuhan_rehabilitasi">Rehabilitasi</label>
							</div>
						</div>
                        <div class="col-md-12">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="kulit_kebutuhan_lainnya" value="Lainnya" id="kulit_kebutuhan_lainnya">
								<label for="kulit_kebutuhan_rehabilitasi">Lainnya</label>
							</div>
						</div>
					</div>
				</div>

                <hr />
                    <p><h6><u>Kesediaan pasien dan/atau keluarga menerima informasi dan edukasi</u></h6></p>

                    <div class="row col-md-6">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_kesediaan_pasien_info" value="1" id="kulit_kesediaan_pasien_info_1">
                                <label class='form-check-label' for="kulit_kesediaan_pasien_info_1">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_kesediaan_pasien_info" value="0" id="kulit_kesediaan_pasien_info_0">
                                <label class='form-check-label' for="kulit_kesediaan_pasien_info_0">Tidak</label>
                            </div>
                        </div>
                    </div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Daftar Diagnosis Keperawatan</h5>
			</div>
			<div id="txt_kaji_kulit_daftar_diagnosis_perawat" class="txt_kaji_kulit_daftar_diagnosis_perawat"></div>
		</div>
	</div>
</div>
