<div class="row">
	<div class="col-lg-12">
		<?php require 'info-pasien.php'; ?>
		<div class="card">
			<div class="card-header">
				<b>Keluhan Utama</b>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div id="txt_keluhan"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<div class="card">
					<div class="card-header">
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
										<input type="text" class="form-control form-control-prepended" required="" />
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
										<input type="text" class="form-control form-control-prepended" required="" />
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
										<input type="text" class="form-control form-control-prepended" required="" />
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
										<input type="text" class="form-control form-control-prepended" required="" />
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
									<input type="text" class="form-control form-control-appended" required="">
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
									<input type="text" class="form-control form-control-appended" required="">
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
									<input type="radio" name="txt_pupil" /> Isokor
									<br />
									<input type="radio" name="txt_pupil" /> Anisokor
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="txt_no_ktp">Refleks Cahaya:</label>
									<input class="form-control" id="txt_refleks_cahaya" required />
								</div>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-md-6">
								RR:
								<div class="input-group input-group-merge">
									<input type="text" class="form-control form-control-appended" required="">
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
									<input type="text" class="form-control form-control-appended" required="">
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
					<div class="card-header">
						<b>Status Alergi</b>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<input type="radio" name="txt_info_anamnesa" /> Ya
									<br />
									<input type="radio" name="txt_info_anamnesa" /> Tidak
									<input type="text" class="form-control uppercase" id="txt_pengantar" placeholder="Sebutkan" required>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<b>Gangguan Perilaku</b>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="radio" name="txt_info_anamnesa" /> Tidak Terganggu
									<br />
									<input type="radio" name="txt_info_anamnesa" /> Terganggu
									<ul class="selection-list">
										<li>
											<input type="radio" name="txt_info_anamnesa" /> Tidak Membahayakan
										</li>
										<li>
											<input type="radio" name="txt_info_anamnesa" /> Membahayakan Diri Sendiri / Orang Lain
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
					<div class="card-header">
						<b>Skala Nyeri</b>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-3">
								Nyeri
							</div>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Ya
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Tidak
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Lokasi
							</div>
							<div class="col-md-9">
								<input type="text" class="form-control" name="" placeholder="Lokasi Nyeri" />
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
										<input type="radio" name="txt_info_anamnesa" /> Sering
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Kadang
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Jarang
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
										<input type="radio" name="txt_info_anamnesa" /> Terbakar
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Tertindih
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Menyebar
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Tajam
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Tumpul
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Berdenyut
									</div>
									<div class="col-md-6">
										<input type="radio" name="txt_info_anamnesa" /> Lainnya
										<input type="text" class="form-control" name="" />
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
								<input type="text" class="form-control" name="" />
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
										<input type="radio" name="txt_info_anamnesa" /> Ringan
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Sedang
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Berat
									</div>
									<div class="col-md-3">
										<input type="radio" name="txt_info_anamnesa" /> Berat Sekali
									</div>
								</div>
							</div>
						</div>
						<div class="row" style="margin-top: 50px; padding: 0px;">
							<div class="col-md-12" id="scale-loader-define"></div>
							<div class="col-md-12" id="scale-loader"></div>
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
												DESKRIPSI KLINIS
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
													<input type="checkbox" /> Henti Jantung
												</li>
												<li>
													<input type="checkbox" /> Henti Nafas
												</li>
												<li>
													<input type="checkbox" /> Sumbatan Jalan Nafas
												</li>
												<li>
													<input type="checkbox" /> Respirasi < 10 x/menit
												</li>
												<li>
													<input type="checkbox" /> Gangguan Pernafasan Ekstrim
												</li>
												<li>
													<input type="checkbox" /> Tekanan darah < 80(dewasa) shok berat pada anak/bayi
												</li>
												<li>
													<input type="checkbox" /> GCS < 9
												</li>
												<li>
													<input type="checkbox" /> Kejang berkepanjangan(lebih dari 10 menit/tidak berhenti)
												</li>
												<li>
													<input type="checkbox" /> Henti Jantung
												</li>
												<li>
													<input type="checkbox" /> Henti Jantung
												</li>
											</ul>
										</td>
										<td class="ats-item" style="background: #ffb3b3;">
											<ul class="selection-list table-child">
												<li>
													<input type="checkbox" name=""> Distres pernafasan/sesak nafas berat RR >= 35 x/menit
												</li>
												<li>
													<input type="checkbox" name=""> Kurangnya perfusi
												</li>
												<li>
													<input type="checkbox" name=""> HR < 50 atau > 150(dewasa)
												</li>
												<li>
													<input type="checkbox" name=""> Hipotensi ringan systole < 90mmHg
												</li>
												<li>
													<input type="checkbox" name=""> Kehilangan darah parah
												</li>
												<li>
													<input type="checkbox" name=""> Nyeri dada karena jantung
												</li>
												<li>
													<input type="checkbox" name=""> Nyeri parah oleh sebab apapun
												</li>
												<li>
													<input type="checkbox" name=""> Mengantuk, penurunan respon oleh sebab apapun(GCS < 13)
												</li>
												<li>
													<input type="checkbox" name=""> BSL < 3mmol (GDS < 50 mg/dl)
												</li>
												<li>
													<input type="checkbox" name=""> Hemiparse acut / dysphasia
												</li>
												<li>
													<input type="checkbox" name=""> Demam dengan tanga-tanda kelesuan
												</li>
												<li>
													<input type="checkbox" name=""> Percikan asam / basa pada mata
												</li>
												<li>
													<input type="checkbox" name=""> Multi trauma yang membutuhkan respon tim terorganisir
												</li>
												<li>
													<input type="checkbox" name=""> Patah tulang besar, amputasi
												</li>
												<li>
													<input type="checkbox" name=""> Riwayat resiko tinggi
												</li>
												<li>
													<input type="checkbox" name=""> Keracunan sedatif atau tertelan racun
												</li>
												<li>
													<input type="checkbox" name=""> Nyeri berat kehamilan ektopik (KET)
												</li>
												<li>
													<input type="checkbox" name=""> Perilaku Psikiatri
													<ul class="selection-list table-child">
														<li>
															<input type="checkbox" name="">Kekesarasan/agresif
														</li>
														<li>
															<input type="checkbox" name="">Ancaman langsung terhadap diri sendiri dan orang lain
														</li>
														<li>
															<input type="checkbox" name="">Memerlukan restrain
														</li>
														<li>
															<input type="checkbox" name="">Agitasi berat
														</li>
													</ul>
												</li>
											</ul>
										</td>
										<td class="ats-item" style="background: #ffe6b3;">
											<ul class="selection-list table-child">
												<li>
													<input type="checkbox" name=""> Hipertensi berat(systole >= 180mmHg atau diastole >= 110mmHg)
												</li>
												<li>
													<input type="checkbox" name=""> Kehilangan darah cukup parah sebab apapun
												</li>
												<li>
													<input type="checkbox" name=""> Sesak nafas sedang RR >= 26x/mnt
												</li>
												<li>
													<input type="checkbox" name=""> SPO 90-95%
												</li>
												<li>
													<input type="checkbox" name=""> BSL > 16mmol/GDS > 228mg/dl
												</li>
												<li>
													<input type="checkbox" name=""> Kejang (saat ini kejang) < 10 menit
												</li>
												<li>
													<input type="checkbox" name=""> Muntah terus menerus
												</li>
												<li>
													<input type="checkbox" name=""> Dehidrasi
												</li>
												<li>
													<input type="checkbox" name=""> Cedera kepala dengan penurunan kesadaran
												</li>
												<li>
													<input type="checkbox" name=""> Reaksi alergi
												</li>
												<li>
													<input type="checkbox" name=""> Nyeri berat
												</li>
												<li>
													<input type="checkbox" name=""> Nyeri non jantung
												</li>
												<li>
													<input type="checkbox" name=""> Pasien usia > 65 tahun
												</li>
												<li>
													<input type="checkbox" name=""> Cedera sedang pada ekstremitas, deformitas, lecet dan hancur
												</li>
												<li>
													<input type="checkbox" name=""> Cedera dengan mati rasa dan pulsasi menurun
												</li>
												<li>
													<input type="checkbox" name=""> Neonatus stabil
												</li>
												<li>
													<input type="checkbox" name=""> Anak dalam resiko
												</li>
												<li>
													<input type="checkbox" name=""> Perilaku Psikiatri
													<ul class="selection-list table-child">
														<li>
															<input type="checkbox" name="">Sangat tertekan, resiko menyakiti diri
														</li>
														<li>
															<input type="checkbox" name="">Acut psikotik, atau gangguan pola pikir
														</li>
														<li>
															<input type="checkbox" name="">Kritis situsional, sengaja menyakiti diri
														</li>
														<li>
															<input type="checkbox" name="">Gelisah, menarik diri
														</li>
														<li>
															<input type="checkbox" name="">Berpotensi agresif
														</li>
														<li>
															<input type="checkbox" name="">Luka robek memerlukan jahitan
														</li>
														<li>
															<input type="checkbox" name="">Lecet parah
														</li>
													</ul>
												</li>
											</ul>
										</td>
										<td class="ats-item" style="background: #ffe6b3;">
											<ul class="selection-list table-child">
												<li>
													<input type="checkbox" name=""> Pendarahan ringan
												</li>
												<li>
													<input type="checkbox" name=""> Hipertensi sedang (systole >= 160 mmHg atau diastole >= 100mmHg)
												</li>
												<li>
													<input type="checkbox" name=""> Cedera dada tanpa nyeri tulang rusuk, atau kesulitan bernafas
												</li>
												<li>
													<input type="checkbox" name=""> Aspirasi benda asing tanpa gangguan pernafasan
												</li>
												<li>
													<input type="checkbox" name=""> Kesulitan menelan, tidak ada gangguan pernafasan.
												</li>
												<li>
													<input type="checkbox" name=""> Cedera kepala ringan, tidak ada kehilangan kesadaran
												</li>
												<li>
													<input type="checkbox" name=""> Muntah atau diare tanpa dehidrasi
												</li>
												<li>
													<input type="checkbox" name=""> Nyeri sedang
												</li>
												<li>
													<input type="checkbox" name=""> Radang mata atau benda asing, penglihatan normal
												</li>
												<li>
													<input type="checkbox" name=""> Terkilir pergelangan kaki/tangan, kemungkinan fraktur, vital sign normal, nyeri sedikit/sedang
												</li>
												<li>
													<input type="checkbox" name=""> Sakit perut non spesifik
												</li>
												<li>
													<input type="checkbox" name=""> Bengkak dan panas pada sendi
												</li>
												<li>
													<input type="checkbox" name=""> Perilaki Psikiatri
													<ul class="selection-list table-child">
														<li>
															<input type="checkbox" name="">Masalah kesehatan, mental semi mendesak, resiko melukai diri sendiri atau orang lain
														</li>
													</ul>
												</li>
											</ul>
										</td>
										<td class="ats-item" style="background: #ccffb3;">
											<ul class="selection-list table-child">
												<li>
													<input type="checkbox" name=""> Nyeri minimal tanpa resiko
												</li>
												<li>
													<input type="checkbox" name=""> Hipertensi ringan (systole >= 150mmHg diatole >= 90mmHg)
												</li>
												<li>
													<input type="checkbox" name=""> Luka ringan, lecet kecil, luka robek tidak memerlukan jahitan
												</li>
												<li>
													<input type="checkbox" name=""> Kontrol luka
												</li>
												<li>
													<input type="checkbox" name=""> Imunisasi
												</li>
												<li>
													<input type="checkbox" name=""> Perilaku Psikiatri
													<ul class="selection-list table-child">
														<li>
															<input type="checkbox" name="">Pasien dengan gejala kronis. Krisis sosial secara klinis baik
														</li>
														<li>
															<input type="checkbox" name="">Tidak ada riwayat sebelumnya atau asimtomatik
														</li>
														<li>
															<input type="checkbox" name="">Gejala minor
														</li>
													</ul>
												</li>
											</ul>
										</td>
									</tr>
									<tr>
										<td class="lower-data">SKALA</td>
										<td class="ats-item" style="background: #ffb3b3;">SKALA 1</td>
										<td class="ats-item" style="background: #ffb3b3;">SKALA 2</td>
										<td class="ats-item" style="background: #ffe6b3;">SKALA 3</td>
										<td class="ats-item" style="background: #ffe6b3;">SKALA 4</td>
										<td class="ats-item" style="background: #ccffb3;">SKALA 5</td>
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
										<td class="ats-item" style="background: #ffb3b3;">SGERA</td>
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