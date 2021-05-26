<div class="row">
	<div class="col-lg-12">
		<?php require 'info-pasien.php'; ?>
		<div class="card">
			<div class="card-body">
				<table class="table table-bordered ats-table">
					<thead>
						<tr>
							<th colspan="2">Jam</th>
							<th style="width: 22%;">Pengkajian<br />Keperawatan</th>
							<th style="width: 22%;">Masalah<br />Keperawatan</th>
							<th style="width: 22%;">Tindakan<br />Keperawatan</th>
							<th style="width: 20%;">Evaluasi (SOAP)</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>D</td>
							<td>
								
							</td>
							<td>
								<b>DISABILITY KESADARAN</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> CM
									</li>
									<li>
										<input type="checkbox" /> Somnolen
									</li>
									<li>
										<input type="checkbox" /> Delerium
									</li>
									<li>
										<input type="checkbox" /> Apatis
									</li>
									<li>
										<input type="checkbox" /> Soporokoma
									</li>
									<li>
										<input type="checkbox" /> Koma
									</li>
								</ul>
								<br />
								<b>GCS</b>
								<ul class="selection-list table-child">
									<li>
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
									</li>
									<li>
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
									</li>
									<li>
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
									</li>
								</ul>
								<br />
								<div class="form-group">
									<label for="txt_no_ktp">PUPIL: Diameter Pupil:</label>
									<input class="form-control" id="txt_refleks_cahaya" placeholder="__/__" required />
								</div>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Isokor
									</li>
									<li>
										<input type="checkbox" /> Miosis
									</li>
									<li>
										<input type="checkbox" /> Anisokor
									</li>
									<li>
										<input type="checkbox" /> Midriasis
									</li>
								</ul>
								<br />
								<b>REFLEKS CAHAYA:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Negatif
									</li>
									<li>
										<input type="checkbox" /> Positif
									</li>
								</ul>
								<br />
								<b>KEKUATAN OTOT:</b>
								<ul class="selection-list table-child">
									<li>
										<div class="form-group">
											<div class="input-group input-group-merge">
												<input type="text" class="form-control form-control-prepended" required="" />
												<div class="input-group-prepend">
													<div class="input-group-text">
														Ext Atas:
													</div>
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="form-group">
											<div class="input-group input-group-merge">
												<input type="text" class="form-control form-control-prepended" required="" />
												<div class="input-group-prepend">
													<div class="input-group-text">
														Ext Bawah:
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Kejang
									</li>
									<li>
										<input type="checkbox" /> Muntah
									</li>
								</ul>
							</td>
							<td>
								<b>GANGGUAN PERFUSI JAR. CEREBRAL:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Aktual
									</li>
									<li>
										<input type="checkbox" /> Risiko
									</li>
									<li>
										<input type="checkbox" /> Potensial
									</li>
								</ul>
							</td>
							<td>
								<b>MENGOBSERVASI:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Tingkat Kesadaran
									</li>
									<li>
										<input type="checkbox" /> TTV
									</li>
									<li>
										<input type="checkbox" /> GCS
									</li>
									<li>
										<input type="checkbox" /> Head Up
									</li>
									<li>
										<input type="checkbox" /> Hindari Peningkatan
									</li>
								</ul>
								<h6 class="text-center">KOLABORASI</h6>
								<ul class="selection-list table-child">
									<li>
										Pemberian Oksigen (ml/mnt):
										<div class="input-group input-group-merge">
											<input type="text" class="form-control" required="">
										</div>
									</li>
									<li>
										<input type="checkbox" /> Memasang Infus
									</li>
									<li>
										<input type="checkbox" /> Memasang NGT
									</li>
									<li>
										<input type="checkbox" /> Memasang Kateter Urine
									</li>
									<li>
										<input type="checkbox" /> Pemeriksaan Labor
									</li>
									<li>
										<input type="checkbox" /> Rontgen/CT Scan
									</li>
									<li>
										Theraphy Obat:
										<div class="input-group input-group-merge">
											<textarea class="form-control" required=""></textarea>
										</div>
									</li>
								</ul>
							</td>
							<td>
								<textarea class="form-control"></textarea>
							</td>
						</tr>




						<tr>
							<td>E</td>
							<td>
								
							</td>
							<td>
								EKSPOSURE:
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Vulnus
										<input type="text" class="form-control" />
									</li>
								</ul>
								<br />
								<b>BUNYI NAFAS</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Vesikuler
									</li>
									<li>
										<input type="checkbox" /> Wheezing
									</li>
									<li>
										<input type="checkbox" /> Ronchi
									</li>
									<li>
										<input type="checkbox" /> Crakhels
									</li>
								</ul>
								<br />
								<b>PENGGUNAAN OTOT BANTU PERNAFASAN:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Retraksi Dada
									</li>
									<li>
										<input type="checkbox" /> Cuping Hidung
									</li>
								</ul>
								<br />
								<b>POLA NAFAS:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Apneu
									</li>
									<li>
										<input type="checkbox" /> Dipsneu
									</li>
									<li>
										<input type="checkbox" /> Bradipneu
									</li>
									<li>
										<input type="checkbox" /> Tachipneu
									</li>
									<li>
										<input type="checkbox" /> Orthopneu
									</li>
								</ul>
								<br />
								<b>JALAN PERNAFASAN:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Pernafasan Dada
									</li>
									<li>
										<input type="checkbox" /> Pernafasan Perut
									</li>
								</ul>
							</td>
							<td>
								<b>POLA NAFAS TIDAK</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Aktual
									</li>
									<li>
										<input type="checkbox" /> Risiko
									</li>
									<li>
										<input type="checkbox" /> Potensial
									</li>
								</ul>
							</td>
							<td>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Memonitor RR, Irama, Kedalaman Nafas
									</li>
									<li>
										<input type="checkbox" /> Memonitor Penggunaan Otot Bantu Nafas
									</li>
									<li>
										<input type="checkbox" /> Ajarkan Teknik Nafas Dalam
									</li>
									<li>
										<input type="checkbox" /> Mengatur Posisi Semi Fowler Jika Tidak Ada Kontra Indikasi
									</li>
								</ul>
								<br />
								<h6 class="text-center">KOLABORASI</h6>
								<ul class="selection-list table-child">
									<li>
										Pemberian Oksigen (ml/mnt):
										<div class="input-group input-group-merge">
											<input type="text" class="form-control" required="">
										</div>
									</li>
									<li>
										<input type="checkbox" /> Inhalasi
									</li>
									<li>
										<input type="checkbox" /> Rontgen
									</li>
									<li>
										<input type="checkbox" /> Pemeriksaan Darah
									</li>
									<li>
										<input type="checkbox" /> (AGD)
									</li>
									<li>
										Theraphy Obat:
										<div class="input-group input-group-merge">
											<textarea class="form-control" required=""></textarea>
										</div>
									</li>
								</ul>
							</td>
							<td>
								<textarea class="form-control"></textarea>
							</td>
						</tr>





						<tr>
							<td>C</td>
							<td>
								
							</td>
							<td>
								<b>CIRCULATION PUCAT</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Ya
									</li>
									<li>
										<input type="checkbox" /> Tidak
									</li>
								</ul>
								<br />
								<b>PENGISIAN KAPILER</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> < 2 detik
									</li>
									<li>
										<input type="checkbox" /> > 2 detik
									</li>
								</ul>
								<br />
								NADI (x/m):
								<div class="input-group input-group-merge">
									<input type="text" class="form-control" required="">
								</div>
								<br />
								<b>AKRAL</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Hangat
									</li>
									<li>
										<input type="checkbox" /> Dingin
									</li>
									<li>
										<input type="checkbox" /> Edema
									</li>
								</ul>
								<br />
								<b>SIANOSIS</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Ya
									</li>
									<li>
										<input type="checkbox" /> Tidak
									</li>
								</ul>
								TD (mmHg):
								<div class="input-group input-group-merge">
									<input type="text" class="form-control" required="">
								</div>
								<br />
								<b>RIWAYAT KEHILANGAN CAIRAN</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Muntah
									</li>
									<li>
										<input type="checkbox" /> Diare
									</li>
									<li>
										<input type="text" class="form-control" placeholder="Lain" />
									</li>
								</ul>
							</td>
							<td>
								<b>GANGGUAN KESEIMBANGAN CAIRAN DAN ELEKTROLIT:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Aktual
									</li>
									<li>
										<input type="checkbox" /> Risiko
									</li>
									<li>
										<input type="checkbox" /> Potensial
									</li>
								</ul>
								<br />
								<b>GANGGUAN PERFUSI JARINGAN PERIFER:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Aktual
									</li>
									<li>
										<input type="checkbox" /> Risiko
									</li>
									<li>
										<input type="checkbox" /> Potensial
									</li>
								</ul>
								<br />
								<b>DIARE:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Aktual
									</li>
									<li>
										<input type="checkbox" /> Risiko
									</li>
									<li>
										<input type="checkbox" /> Potensial
									</li>
								</ul>
							</td>
							<td>
								<b>MENGUKUR:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Status Dehidrasi
									</li>
									<li>
										<input type="checkbox" /> Kekuatan Nadi Perifer
									</li>
									<li>
										<input type="checkbox" /> Intake Output
									</li>
									<li>
										<input type="checkbox" /> Balance Cairan
									</li>
									<li>
										<input type="checkbox" /> CPV
									</li>
									<li>
										<input type="checkbox" /> Perubahan Turgor, Membran Mukosa
									</li>
									<li>
										<input type="checkbox" /> Kapiler Refill
									</li>
								</ul>
								<br />
								<h6 class="text-center">KOLABORASI</h6>
								<ul class="selection-list table-child">
									<li>
										Pemberian Oksigen (ml/mnt):
										<div class="input-group input-group-merge">
											<input type="text" class="form-control" required="">
										</div>
									</li>
									<li>
										<input type="checkbox" /> Memasang Infus
									</li>
									<li>
										<input type="checkbox" /> Memasang NGT
									</li>
									<li>
										<input type="checkbox" /> Memasang Kateter Urine
									</li>
									<li>
										<input type="checkbox" /> Pemeriksaan Labor
									</li>
									<li>
										<input type="checkbox" /> Rontgen
									</li>
									<li>
										<input type="checkbox" /> Tranfusi Darah
									</li>
									<li>
										Theraphy Obat:
										<div class="input-group input-group-merge">
											<textarea class="form-control" required=""></textarea>
										</div>
									</li>
								</ul>
							</td>
							<td>
								<textarea class="form-control"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>