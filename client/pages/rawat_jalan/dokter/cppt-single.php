<p class="text-dark-gray d-flex align-items-center mt-3">
	<i class="material-icons icon-muted mr-2">event</i>
	<strong><?php echo date('d F Y', strtotime($_POST['setter']['created_at'])); ?></strong>
</p>





<div class="row projects-item mb-1">
	<div class="col-1">
		<br />
		<div class="text-dark-gray">Asesmen</div>
	</div>
	<div class="col-11">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<div class="row">
					<div class="col-12">
						<div class="segmen_keluhan_utama">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2">#Keluhan Utama</strong></a>
							</div>
							<p class="txt_keluhan_utama">
								<?php
									echo $_POST['setter']['asesmen_detail']['keluhan_utama'];
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<div class="row">
					<div class="col-12">
						<div class="segmen_keluhan_tambahan">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2">#Keluhan Tambahan</strong></a>
							</div>
							<p class="txt_keluhan_tambahan">
								<?php
									echo $_POST['setter']['asesmen_detail']['keluhan_tambahan'];
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<div class="row">
					<div class="col-12">
						<div class="segmen_diagnosa_utama">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2">#Diagnosa Kerja</strong></a>
							</div>
							<p class="txt_diagnosa_kerja">
								<?php
									echo $_POST['setter']['asesmen_detail']['diagnosa_kerja'];
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<div class="row">
					<div class="col-12">
						<div class="segmen_diagnosa_banding">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2">#Diagnosa Banding</strong></a>
							</div>
							<p class="txt_diagnosa_banding">
								<?php
									echo $_POST['setter']['asesmen_detail']['diagnosa_banding'];
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>













<div class="row projects-item mb-1">
	<div class="col-1">
		<br />
		<div class="text-dark-gray">Resep & Racikan</div>
	</div>
	<div class="col-11">
		<div class="card">
			<div class="card-header card-header-large bg-white">
				<div class="row">
					<div class="col-12">
						<div class="segmen_resep">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2">#Resep</strong></a>
							</div>
						</div>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>Obat</th>
									<th>Signa</th>
									<th>Jlh</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card-header card-header-large bg-white">
				<div class="row">
					<div class="col-12">
						<div class="segmen_racikan">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2">#Racikan</strong></a>
							</div>
						</div>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>Racikan</th>
									<th>Komposisi</th>
									<th>Signa</th>
									<th>Jlh</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>