<?php require 'form-header.php'; ?>
<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Konversi Satuan</h5>
			</div>
			<div class="card-body tab-content">
				<div class="form-group">
					<label>Satuan Terkecil:</label>
					<select class="form-control" id="txt_satuan_terkecil"></select>
				</div>
				<table class="table table-bordered table-data" id="table-konversi-satuan">
					<thead class="thead-dark">
						<tr>
							<th class="wrap_content">No</th>
							<th>Dari</th>
							<th>Ke Satuan Terkecil</th>
							<th style="width: 200px;">Rasio</th>
							<th class="wrap_content">Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- <div class="col-md-12" style="margin-top: 50px;">
	<div class="form-group">
		<label>Varian Kemasan:</label>
		<table class="table table-bordered table-data" id="table-varian">
			<thead>
				<tr>
					<th style="width: 50px;">No</th>
					<th style="width: 50%;">Satuan</th>
					<th>Kemasan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div> -->