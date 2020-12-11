<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Antrian Radiologi</li>
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
							Petugas Radiologi
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b>Antrian</b>
							<br />
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
                                    <a href="#tab-order" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                        <span class="nav-link__count">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        Order
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card card-body tab-content">
                            <div class="tab-pane show fade active" id="tab-verifikasi">
                                <table class="table table-bordered table-striped" id="table-verifikasi-radiologi" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Waktu Order</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Poliklinik</th>
                                        <th>Dokter</th>
                                        <!-- <th>Penjamin</th>
                                        <th>Oleh</th> -->
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade" id="tab-order">
                                <table class="table table-bordered table-striped" id="table-antrian-radiologi" style="font-size: 0.9rem;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Waktu Order</th>
                                            <th>No. RM</th>
                                            <th>Pasien</th>
                                            <th>Poliklinik</th>
                                            <th>Dokter</th>
                                            <!-- <th>Penjamin</th>
                                            <th>Oleh</th> -->
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