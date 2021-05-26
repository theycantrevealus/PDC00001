<div class="row">
	<div class="col-lg">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="card">
					<div class="card-body">
						<!-- <div class="badge badge-danger">OVERDUE</div> -->

						<div class="px-3">
							<div class="d-flex justify-content-center flex-column my-5 navbar-light" style="margin: 10px !important;">
								<div class="row" style="border-bottom: solid 3px rgba(17, 43, 74, 0.84);">
									<div class="col-1 text-right">
										<img class="img-responsive" src="<?php echo __HOSTNAME__; ?>/template/assets/images/logo.png" width="100" height="80" alt="SIMRS PETALA BUMI">
									</div>
									<div class="col-11 text-center">
										<h4>PEMERINTAH PROVINSI RIAU</h4>
										<div class="text-muted">
											<h4>RUMAH SAKIT UMUM DAERAH PETALA BUMI</h4>
											<small><b>Jl. Dr. Soetomo No. 65 Telp. (0761) 23024 Pekanbaru - Riau</b></small>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<h6 class="text-center">ASESMEN AWAL MEDIS PASIEN RAWAT JALAN</h6>
								<br />
								<div class="col-md-12">
									<b>Identitas Pasien</b>
									<table class="table">
										<tr>
											<td>Nomor Rekam Medis</td>
											<td>:</td>
											<td id="review-rm"></td>
										</tr>
										<tr>
											<td>Nama Lengkap</td>
											<td>:</td>
											<td id="review-nama-pasien"></td>
										</tr>
										<tr>
											<td>Tanggal Lahir</td>
											<td>:</td>
											<td id="review-ttl-pasien"></td>
										</tr>
										<tr>
											<td>Jenis Kelamin</td>
											<td>:</td>
											<td id="review-jk-pasien"></td>
										</tr>
									</table>
								</div>
								<div class="col-md-12">
									<b>Subjective</b>
									<p id="review-subjective-utama"></p>
									<p id="review-subjective-tambahan"></p>
								</div>
								<div class="row">
									<div class="col-md-6">
										<b>Objective</b>
										<table class="table">
											<tr>
												<td>Tekanan Darah</td>
												<td id="review-td"></td>
											</tr>
											<tr>
												<td>Nadi</td>
												<td id="review-nadi"></td>
											</tr>
											<tr>
												<td>Suhu</td>
												<td id="review-suhu"></td>
											</tr>
											<tr>
												<td>Pernafasan</td>
												<td id="review-nafas"></td>
											</tr>
											<tr>
												<td>Berat Badan</td>
												<td id="review-bb"></td>
											</tr>
											<tr>
												<td>Tinggi Badan</td>
												<td id="review-tb"></td>
											</tr>
											<tr>
												<td>Lingkar Lengan Atas</td>
												<td id="review-lla"></td>
											</tr>
										</table>
									</div>
									<div class="col-md-6">
										<b>Pemeriksaan Fisik</b>
										<p id="review-pemeriksaan-fisik"></p>
									</div>
								</div>
								<div class="col-md-12">
									<b>Assesment</b>
									<span id="review-icd10-kerja"></span>
									<p id="review-diagnosa-kerja"></p>
									<span id="review-icd10-banding"></span>
									<p id="review-diagnosa-banding"></p>
								</div>
								<div class="col-md-12">
									<b>Planning : Penatalaksanaan/Pengobatan/Rencana Tindakan/Konsultasi/Edukasi</b>
									<p id="review-planning"></p>
								</div>
								<div class="col-md-12">
									<b>Assesment</b>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>