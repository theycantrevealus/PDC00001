<div class="row">
	<div class="col-md-12">
		<?php require 'info-pasien.php'; ?>
		<div class="card">
			<div class="card-header">
				<b>Pengkajian Medis (diisi oleh dokter)</b>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<b>Subjective(Anamnesa)</b>
						<div id="txt_subjective"></div>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-12">
						<b>Object(Anamnesa)</b>
						<div id="txt_objective"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<b>Status Lokalis</b>
						<div class="lokalis">
							<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/form/lokalis.png" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<ul class="selection-list table-child">
									<li>A: Abrasi</li>
									<li>C: Combustio</li>
									<li>V: Vulnus</li>
									<li>D: Deformitas</li>
								</ul>
							</div>
							<div class="col-md-6">
								<ul class="selection-list table-child">
									<li>U: Uikus</li>
									<li>H: Hematorna</li>
									<li>L: Lain-lain</li>
									<li>N: Nyeri</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-12">
						<b>Pemeriksaan Penunjang</b>
						<div class="form-group">
							<input type="checkbox" name="txt_info_anamnesa" /> EKG
							<br />
							<input type="checkbox" name="txt_info_anamnesa" /> Radiologi
							<br />
							<input type="checkbox" name="txt_info_anamnesa" /> Laboratorium
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<b>Assesment</b>
						<div class="form-group">
							<input type="checkbox" name=""> Diagnosa kerja
						</div>
					</div>
					<div class="col-md-6">
						ICD10
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<b>Assesment</b>
						<div class="form-group">
							<input type="checkbox" name=""> Diagnosa banding
						</div>
					</div>
					<div class="col-md-6">
						ICD10
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-12">
						<b>Planning: Penatalaksanaan/Pengobatan/Rencana Tindakan/Konsultasi</b>
						<div id="txt_planning"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>