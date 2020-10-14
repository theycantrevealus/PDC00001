<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap">Rawat Inap</a></li>
					<li class="breadcrumb-item active" aria-current="page">Asesmen</li>
				</ol>
			</nav>
			<h4 class="m-0">Asesmen Rawat Inap</h4>
		</div>
		<a href="<?php echo __HOSTNAME__; ?>/rawat_inap/asesmen/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah Asesmen
		</a>
	</div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
        	<div class="card">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h5 class="card-header__title flex m-0">Rekam Rawat Inap</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive border-bottom">
						<table class="table table-bordered table-striped largeDataType" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
							<thead class="thead-dark">
								<tr>
									<th width="2%">No</th>
									<th>Waktu Input</th>
									<th>No. RM</th>
									<th>Pasien</th>
									<th>Perawat</th>
									<th>Oleh</th>
									<th width='10%'>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>25 July 2020 - [02:19]</td>
									<td>121-545-441</td>
									<td>MARCO DE GAMMA</td>
									<td>Ns. Sulastria</td>
									<td>Admin Loket 1</td>
									<td><a href='<?= __HOSTNAME__; ?>/rawat_inap/asesmen/view' class='btn btn-sm btn-info'><i class='fa fa-eye' data-toggle='tooltip' title='Asesmen Pasien'></i></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<br />
					<a href="<?php echo __HOSTNAME__; ?>/rawat_inap" class="btn btn-danger btn-sm ml-3">
						Kembali
					</a>
            	</div>
            </div>
        </div>
    </div>
</div>