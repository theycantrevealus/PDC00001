<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Master Unit</li>
					<li class="breadcrumb-item active" aria-current="page">Unit Bed</li>
				</ol>
			</nav>
		</div>
		<!-- <a href="<?php echo __HOSTNAME__; ?>/master/unit/ruangan/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah Ruangan
		</a> -->
		<button class="btn btn-sm btn-info" id="tambah-bed">
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-bed">
					<thead class="thead-dark">
						<tr>
							<th class="wrap_content">No</th>
							<th>Nama Bed</th>
							<th>Ruangan</th>
							<th>Lantai</th>
                            <th>Tarif/Hari</th>
							<th class="wrap_content">Aksi</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>