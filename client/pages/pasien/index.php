<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Pasien</li>
				</ol>
			</nav>
			<h4 class="m-0">Data Pasien</h4>
		</div>
		<a href="<?php echo __HOSTNAME__; ?>/pasien/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah Pasien
		</a>
		<!-- <button class="btn btn-sm btn-info" id="tambah-pasien">
			<i class="fa fa-plus"></i> Tambah
		</button> -->
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-pasien">
					<thead class="thead-dark">
						<tr>
							<th style="width: 20px;">No</th>
							<th>No RM</th>
							<th>Nama Pasien</th>
							<th>Tanggal Lahir</th>
							<th>Jenis Kelamin</th>
							<th>Tanggal Daftar</th>
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