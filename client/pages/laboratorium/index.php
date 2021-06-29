<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item">Laboratorium</li>
					<li class="breadcrumb-item active" aria-current="page">Antrian Laboratorium</li>
				</ol>
			</nav>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="card-group">
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-2">
							<i class="material-icons icon-muted icon-30pt">account_circle</i>
						</div>
						<div class="col-md-10">
							<b><?php echo $_SESSION['nama']; ?></b>
							<br />
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b>Antrian</b>
							<h5 class="text-info handy" id="current-poli">
								<small><i class="fa fa-sync text-success" id="change-poli"></i></small>
							</h5>
							<b id="jlh-antrian">0</b> antrian
						</div>
					</div>
				</div>
			</div>
			<div class="card card-body">
                <div class="row card-group-row">
                    <div class="col-lg-12 col-md-12">
                        <div class="z-0">
                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a href="#tab-verifikasi" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-4" >
                                        <span class="nav-link__count">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        Verifikasi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tab-laboratorium" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                        <span class="nav-link__count">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        Permintaan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tab-manajemen" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-2" >
                                        <span class="nav-link__count">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        Manajemen Order
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tab-history" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-3" >
                                        <span class="nav-link__count">
                                            <i class="fa fa-hourglass"></i>
                                        </span>
                                        History
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tab-reagen" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-4" >
                                        <span class="nav-link__count">
                                            <i class="fa fa-check-square"></i>
                                        </span>
                                        Reagen
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card card-body tab-content">
                            <div class="tab-pane show fade" id="tab-laboratorium">
                                <table class="table table-bordered table-striped" id="table-antrian-labor" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Waktu Order</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Poliklinik</th>
                                        <th>Dokter</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade" id="tab-manajemen">
                                <table class="table table-bordered table-striped largeDataType" id="service_labor">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Laboratorium</th>
                                            <th>Pemeriksaan</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade" id="tab-history">
                                <input id="range_history" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                <table class="table table-bordered table-striped" id="table-history-labor" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Waktu Order</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Poliklinik</th>
                                        <th>Dokter</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade active" id="tab-verifikasi">
                                <table class="table table-bordered table-striped" id="table-verifikasi-labor" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Waktu Order</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Poliklinik</th>
                                        <th>Dokter</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade" id="tab-reagen">
                                <div class="card">
                                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                                        <h5 class="card-header__title flex m-0">Ketersediaan Reagen</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered largeDataType" id="table-lab">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content">No</th>
                                                <th class="wrap_content">Kode</th>
                                                <th>Nama</th>
                                                <th>Spesimen</th>
                                                <th class="wrap_content">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
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
</div>