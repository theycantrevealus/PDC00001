<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/inventori">Master Inventori</a></li>
					<li class="breadcrumb-item active" aria-current="page">Kategori</li>
				</ol>
			</nav>
		</div>
		<!-- <a href="<?php echo __HOSTNAME__; ?>/master/inventori/kategori/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah Kategori
		</a> -->
		<button class="btn btn-sm btn-info" id="tambah-kategori">
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-kategori">
					<thead>
						<tr>
							<th style="width: 20px;">No</th>
							<th>Nama</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>