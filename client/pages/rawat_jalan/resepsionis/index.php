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
							<center>Antrian terkini</center>
							<h1 class="text-center" id="txt_current_antrian">0</h1>
						</div>
						<div class="col-lg-3" style="border-right: solid 1px #cccc;">
                            <div class="col-lg-12">
                                <br />
                            </div>
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
						<div class="col-lg-2" style="border-right: solid 1px #cccc;">
                            <center>
                                <span class="text-secondary">Sisa Antrian</span><br />
                                <h5><b id="sisa_antrian">0</b></h5>
                            </center>
						</div>
                        <div class="col-lg-3">
                            <span class="text-secondary">Antrian Terlewat</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <select id="antrian_terlewat" class="form-control"></select>
                                </div>
                                <div class="col-lg-6">
                                    <button class="btn btn-purple" id="btnSetLewat">
                                        <i class="fa fa-check"></i> AKTIFKAN
                                    </button>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
            <div class="row card-group-row">
                <div class="col-lg-12 col-md-12">
                    <div class="z-0">
                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                            <li class="nav-item">
                                <a href="#tab-antrian-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-antrian-1" >
							<span class="nav-link__count">
								01
							</span>
                                    Antrian Rawat Jalan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-antrian-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" >
							<span class="nav-link__count">
								02
							</span>
                                    Antrian Rawat Inap
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-antrian-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" >
							<span class="nav-link__count">
								03
							</span>
                                    Pasien IGD
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card card-body tab-content">
                        <div class="tab-pane show fade active" id="tab-antrian-1">
                            <div class="card">
                                <div class="card-header card-header-large bg-white">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <h5 class="card-header__title flex m-0">Antrian Poli</h5>
                                        </div>
                                        <div class="col-lg-3">
                                            <select class="form-control col-lg-4 pull-right" id="filter_poli">
                                                <option value="all">Semua Poli</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-bordered table-striped largeDataType" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th class="wrap_content">No</th>
                                                    <th>Waktu Masuk</th>
                                                    <th class="wrap_content">No. RM</th>
                                                    <th>Pasien</th>
                                                    <th>Poliklinik</th>
                                                    <th>Dokter</th>
                                                    <th>Penjamin</th>
                                                    <th>Oleh</th>
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
                        </div>
                        <div class="tab-pane show fade" id="tab-antrian-2">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">Rawat Inap</h5>
                                    <!--button class="btn btn-info" id="btnTambahRI">
                                        <i class="fa fa-plus"></i> Tambah Pasien
                                    </button-->
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped largeDataType" id="table-antrian-RI" style="font-size: 0.9rem;">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Waktu Masuk</th>
                                            <th class="wrap_content">No. RM</th>
                                            <th>Pasien</th>
                                            <th>Dokter</th>
                                            <th>Penjamin</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="tab-antrian-3">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">IGD</h5>
                                    <button class="btn btn-info" id="btnTambahIGD">
                                        <i class="fa fa-plus"></i> Tambah Pasien
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped largeDataType" id="table-antrian-IGD" style="font-size: 0.9rem;">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Waktu Masuk</th>
                                            <th class="wrap_content">No. RM</th>
                                            <th>Pasien</th>
                                            <th>Dokter</th>
                                            <th>Penjamin</th>
                                            <th>Oleh</th>
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
                </div>
            </div>
        </div>
    </div>
</div>
