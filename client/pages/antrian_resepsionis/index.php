<style type="text/css">
	.tabelScroll {
		height: 500px;
		overflow-x: scroll;
		overflow: scroll;
	}
</style>

<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Antrian</li>
				</ol>
			</nav>
			<h4 class="m-0">Antrian</h4>
		</div>
		<button class="btn btn-info btn-sm ml-3" id="btnTambahAntrian" data-toggle="toastr" data-toastr-type="info" data-toastr-title="Pencarian Selesai" >
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<table class="table table-bordered" id="table-antrian-rawat-jalan" style="height: 100px; overflow: scroll;">
					<thead>
						<tr>
							<th style="width: 20px;">No</th>
							<th>Waktu Masuk</th>
							<th>No. RM</th>
							<th>Pasien</th>
							<th>Departemen</th>
							<th>Dokter</th>
							<th>Penjamin</th>
							<th>Oleh</th>
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