<?php require 'form-header.php'; ?>
<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Monitoring Stok</h5>
			</div>
			<div class="card-body tab-content">
				<table class="table table-bordered" id="table-monitoring">
					<thead class="thead-dark">
						<tr>
							<th rowspan="2" class="wrap_content">No</th>
							<th class="col-md-4" rowspan="2">Gudang</th>
							<th colspan="3">Jumlah</th>
						</tr>
						<tr>
							<th class="col-md-2">Minimum</th>
							<th class="col-md-2">Maksimum</th>
							<th class="col-md-2">Satuan Terkecil</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>