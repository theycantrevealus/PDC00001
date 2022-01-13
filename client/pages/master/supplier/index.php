<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Master Supplier</li>
				</ol>
			</nav>
		</div>
		<a href="<?php echo __HOSTNAME__ ?>/master/supplier/tambah" class="btn btn-sm btn-info">
			<i class="fa fa-plus"></i> Tambah
		</a>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-supplier">
					<thead class="thead-dark">
						<tr>
							<th class="wrap_content">No</th>
							<th style="width: 30%">Nama</th>
							<th>Kontak</th>
							<th style="width: 15%">PO Link</th>
							<th class="wrap_content">Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>