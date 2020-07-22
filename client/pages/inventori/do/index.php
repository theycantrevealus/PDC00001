<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori">Inventori</a></li>
					<li class="breadcrumb-item active" aria-current="page">Barang Masuk</li>
				</ol>
			</nav>
			<h4>Inventori - Barang Masuk</h4>
		</div>
		<a href="<?php echo __HOSTNAME__; ?>/inventori/do/tambah" class="btn btn-sm btn-info" id="btnTambahDo">
			<i class="fa fa-plus"></i> Tambah
		</a>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-do" style="font-size: 0.9rem;">
					<thead>
						<tr>
							<th style="width: 20px;">No</th>
							<th>Waktu Input</th>
							<!-- <th>No. Dokumen</th> -->
							<th>Tgl. Dokumen</th>
							<th>Pemasok</th>
							<th>No. DO</th>
							<th>No. Invoice</th>
							<th>Tgl. Invoice</th>
							<th>Status</th>
							<th>Pegawai</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>