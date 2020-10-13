<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Inventori</li>
					<li class="breadcrumb-item" aria-current="page">Stok</li>
					<li class="breadcrumb-item active" aria-current="page">Stok Awal</li>
				</ol>
			</nav>
			<h4 class="m-0">Stok Awal</h4>
		</div>
		<button class="btn btn-sm btn-info" id="btnTambahStokAwal">
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-penjamin">
					<thead>
						<tr>
							<th style="width: 20px;">No</th>
							<th>Kode</th>
							<th>Item</th>
							<th>Batch</th>
							<th>Tanggal Expired</th>
							<th>Stok Awal</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>