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
		<div class="card">
			<div class="card-header">
				<b>Diagnosa Medis</b>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div id="txt_diagnosa"></div>
					</div>
				</div>
			</div>
		</div>
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
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<b>Pengkajian Fungsi Aktifitas Sehari-hari</b>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<input type="radio" name="txt_info_anamnesa" /> Mandiri
					</div>
					<div class="col-md-6">
						<input type="radio" name="txt_info_anamnesa" /> Dengan Bantuan
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<b>Pengkajian Resiko Pasien Jatuh</b>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<b>Skala Jatuh Dewasa (MORSE)</b>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Skor</th>
									<th>Tingkat Resiko</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>0 - 5</td>
									<td>Resiko Rendah</td>
								</tr>
								<tr>
									<td>6 - 13</td>
									<td>Resiko Sedang</td>
								</tr>
								<tr>
									<td>>= 14</td>
									<td>Resiko Tinggi</td>
								</tr>
							</tbody>
						</table>
						Total Skor:
						<input type="text" class="form-control" />
					</div>
					<div class="col-md-6">
						<b>Skala Jatuh Anak (Humpty Dumpty)</b>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Skor</th>
									<th>Tingkat Resiko</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>7 - 15</td>
									<td>Resiko Rendah</td>
								</tr>
								<tr>
									<td>>= 12</td>
									<td>Resiko Sedang</td>
								</tr>
							</tbody>
						</table>
						Total Skor:
						<input type="text" class="form-control" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>