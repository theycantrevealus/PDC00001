<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek">Apotek</a></li>
					<li class="breadcrumb-item active" aria-current="page">Resep</li>
				</ol>
			</nav>
			<h4 class="m-0">Resep</h4>
		</div>
		<button class="btn btn-sm btn-info" id="tambah-satuan">
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="card-group">
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-2">
							<i class="material-icons icon-muted icon-30pt">insert_chart</i>
						</div>
						<div class="col-md-10">
							<b>Status Ketersediaan Obat</b>
							<br />
							<span>Apotek</span>
							<br />
							<span>
								<b class="text-warning">10 item</b> (hampir habis) <br />
								<b class="text-danger">2 item</b> (habis)
							</span>
							<h6 class="text-right">
								<small><a href="<?php echo __HOSTNAME__; ?>"><i class="fa fa-cubes"></i> Amprah</a></small>
							</h6>
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b>Kebutuhan Obat Terkini</b>
							<ol>
								<li>PARACETAMOL <b class="text-danger">50</b> Tablet</li>
								<li>FENTANIL <b class="text-danger">33</b> Tablet</li>
								<li>KALNEX 200MG <b class="text-danger">21</b> Tablet</li>
							</ol>
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b>Perubahan Resep</b>
						</div>
					</div>
				</div>
			</div>
			<div class="card card-body">
				<table class="table table-bordered table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
					<thead class="thead-dark">
						<tr>
							<th width="2%">No</th>
							<th>Poliklinik</th>
							<th>Pasien</th>
							<th>Dokter</th>
							<th>Perawat</th>
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