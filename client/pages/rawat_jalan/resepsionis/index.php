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
	</div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
        	<div class="card">
				<div class="card-header card-header-large bg-white align-items-center">
					<div class="row">
						<div class="col-lg-6">
							<h4 class="card-header__title flex m-0">
								Antrian Kunjungan
							</h4>
						</div>
						<div class="col-lg-3">
							<select id="txt_loket" class="form-control"></select>
						</div>
						<div class="col-lg-3">
							<button class="btn btn-success" id="btnGunakanLoket">
								<i class="fa fa-check"></i> Gunakan
							</button>
							<button class="btn btn-danger" disabled="disabled" id="btnSelesaiGunakan">
								<i class="fa fa-ban"></i> Selesai
							</button>
						</div>

					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2" style="border-right: solid 1px #cccc;">
							<h1 class="text-center" id="txt_current_antrian">0</h1>
						</div>
						<div class="col-lg-10">
							<button class="btn btn-info" id="btnPanggil">
								<i class="fa fa-bullhorn"></i> PANGGIL
							</button>
							<button class="btn btn-warning" style="color: #fff;" id="btnNext">
								<i class="fa fa-caret-square-right"></i> NEXT
							</button>
							<button class="btn btn-success" id="btnTambahAntrian">
								<i class="fa fa-plus"></i> DAFTAR PASIEN
							</button>
						</div>
					</div>
				</div>
			</div>
        	<div class="card">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h5 class="card-header__title flex m-0">Antrian Poli</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive border-bottom">
						<table class="table table-bordered table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
							<thead>
								<tr>
									<th width="2%">No</th>
									<th>Waktu Masuk</th>
									<th>No. RM</th>
									<th>Pasien</th>
									<th>Departemen</th>
									<th>Dokter</th>
									<th>Penjamin</th>
									<th>Oleh</th>
									<!-- <th>Aksi</th> -->
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
            	</div>
            </div>
        </div>
    </div>
</div>
