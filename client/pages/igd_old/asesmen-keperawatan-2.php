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
							<td>A</td>
							<td>
								
							</td>
							<td>
								<b>AIRWAY</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Bebas
									</li>
									<li>
										<input type="checkbox" /> Hidung / Mulut Kotor
									</li>
									<li>
										<input type="checkbox" /> Sputum
									</li>
									<li>
										<input type="checkbox" /> Darah
									</li>
									<li>
										<input type="checkbox" /> Benda Padat
									</li>
									<li>
										<input type="checkbox" /> Spasme
									</li>
									<li>
										<input type="checkbox" /> Pangkal Lidah Jatuh
									</li>
								</ul>
								<br />
								<b>SUARA NAFAS</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Normal
									</li>
									<li>
										<input type="checkbox" /> Stridor
									</li>
									<li>
										<input type="checkbox" /> Snoring
									</li>
									<li>
										<input type="checkbox" /> Gurgling
									</li>
									<li>
										<input type="checkbox" /> Gasping
									</li>
									<li>
										<input type="checkbox" /> Tidak ada suara nafas
									</li>
								</ul>
							</td>
							<td>
								<b>GANGGUAN BERSIHAN JALAN NAFAS</b>
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
										<input type="checkbox" /> Head Tilt
									</li>
									<li>
										<input type="checkbox" /> Chin Lift
									</li>
									<li>
										<input type="checkbox" /> Jaw Trust
									</li>
									<li>
										<input type="checkbox" /> Oro Faringeal
									</li>
									<li>
										<input type="checkbox" /> Naso Faringeal
									</li>
									<li>
										<input type="checkbox" /> Melakukan Suction
									</li>
									<li>
										<input type="checkbox" /> Hidrasi
									</li>
									<li>
										<input type="checkbox" /> Pasang Collar Neck
									</li>
									<li>
										<input type="checkbox" /> Batuk Efektif
									</li>
									<li>
										<input type="checkbox" /> Fowler/Semi Fowler
									</li>
									<li>
										<input type="checkbox" /> Posisi Miring
									</li>
									<li>
										<input type="checkbox" /> Posisi Mantap PS
									</li>
									<li>
										<input type="checkbox" /> Tidak Sadar
									</li>
									<li>
										<input type="checkbox" /> Auskultasi Paru
									</li>
									<li>
										<input type="checkbox" /> Secara Periodik
									</li>
								</ul>
							</td>
							<td>
								<textarea class="form-control"></textarea>
							</td>
						</tr>




						<tr>
							<td>B</td>
							<td>
								
							</td>
							<td>
								BREATHING RR (x/m):
								<div class="input-group input-group-merge">
									<input type="text" class="form-control" required="">
								</div>
								<br />
								<b>IRAMA NAFAS</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Teratur
									</li>
									<li>
										<input type="checkbox" /> Tidak Teratur
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
								<br />
								<b>PERDARAHAN:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Ya
									</li>
									<li>
										<input type="checkbox" /> Tidak
									</li>
									<li>
										<input type="text" class="form-control" placeholder="Lain" />
									</li>
								</ul>
								<br />
								LOKASI:
								<div class="input-group input-group-merge">
									<input type="text" class="form-control" required="">
								</div>
								<br />
								LUKA BAKAR:
								<div class="input-group input-group-merge">
									<input type="text" class="form-control form-control-appended" required="">
									<div class="input-group-append">
										<div class="input-group-text">
											%
										</div>
									</div>
								</div>
								<br />
								GRADE:
								<div class="input-group input-group-merge">
									<input type="text" class="form-control" placeholder="" required="">
								</div>
								<br />
								SUHU:
								<div class="input-group input-group-merge">
									<input type="text" class="form-control form-control-appended" required="">
									<div class="input-group-append">
										<div class="input-group-text">
											<sup>o</sup>C
										</div>
									</div>
								</div>
								<br />
								<b>KELEMBABAN KULIT:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Lembab
									</li>
									<li>
										<input type="checkbox" /> Kering
									</li>
								</ul>
								<br />
								<b>TURGOR KULIR:</b>
								<ul class="selection-list table-child">
									<li>
										<input type="checkbox" /> Normal
									</li>
									<li>
										<input type="checkbox" /> Kurang
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