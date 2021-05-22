<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Rawat Inap</li>
				</ol>
			</nav>
			<h4 class="m-0">Perencanaan Pulang - Perawat</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
        	<div class="card">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h5 class="card-header__title flex m-0">Antrian Rawat Inap</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive border-bottom">
						<table class="table table-bordered table-striped largeDataType" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
							<thead class="thead-dark">
								<tr>
									<th width="2%">No</th>
									<th>Waktu Masuk</th>
									<th>No. RM</th>
									<th>Pasien</th>
									<!-- <th>Departemen</th> -->
									<th>Dokter</th>
									<th>Penjamin</th>
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
									<td>dr. Yolazenia, M. Biomed, Sp.THT-KL</td>
									<td>BPJS KESEHATAN</td>
									<td>Admin Loket 1</td>
									<td>
										<a href='<?= __HOSTNAME__; ?>/rawat_inap/perawat/perencanaan_pulang/antrian' class='btn btn-sm btn-warning'><i class='fa fa-address-card' data-toggle='tooltip' title='Asesmen Awal'></i></a>
									
										<!-- <a href='<?= __HOSTNAME__; ?>/rawat_inap/perawat/asesmen_harian' class='btn btn-sm btn-info'><i class='fa fa-address-card' data-toggle='tooltip' title='Asesmen Harian'></i></a>  -->
										
										<button type='button' class='btn btn-sm btn-success'><i class='fa fa-check' data-toggle='tooltip' title='Pulang'></i></button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
            	</div>
            </div>
        </div>
    </div>
</div>