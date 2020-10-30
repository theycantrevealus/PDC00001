<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori">Inventori</a></li>
					<li class="breadcrumb-item active" aria-current="page">Batch</li>
				</ol>
			</nav>
			<h4>Inventori - Batch</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-body">
				<div class="d-flex flex-row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="txt_item">Item</label>
							<div class="input-group input-group-merge">
								<select id="txt_item" class="form-control"></select>
							</div>
							<br />
						</div>
					</div>
					<div class="col-md-4">
						<label>Informasi Obat:</label>
						<br />
					</div>
					<div class="col-md-4">
						<label>Kategori Obat:</label>
						<br />
						<span id="kategori_obat"></span>
					</div>
				</div>
			</div>
			<div class="card card-body">
				<table class="table table-bordered" id="table-po">
					<thead class="thead-dark">
						<tr>
							<th style="width: 20px;">No</th>
							<th>Batch</th>
							<th>Lokasi</th>
							<th>Stok</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>