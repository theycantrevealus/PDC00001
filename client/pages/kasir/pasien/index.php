<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Kasir</li>
				</ol>
			</nav>
			<h4 class="m-0">Tagihan Pasien</h4>
		</div>
		<!-- <button class="btn btn-info btn-sm ml-3" id="btnTambahAntrian">
			<i class="fa fa-plus"></i> Tambah
		</button> -->
	</div>
</div>

<?php
    $yesterday = new DateTime(date('Y-m-d')); // For today/now, don't pass an arg.
    $yesterday->modify("-1 day");

    $tomorrow = new DateTime(date('Y-m-d'));
    $tomorrow->modify("+1 day");
?>



<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
        	<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tutor_filter_type">
					<li class="nav-item">
						<a href="#tab-tagihan" class="active nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-tagihan" >
							<span class="nav-link__count">
								01
							</span>
							Tagihan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-kwitansi" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								02
							</span>
							Kwitansi
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane show fade active" id="tab-tagihan">
                    <div class="row">
                        <div class="col-lg">
                            <div class="z-0">
                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tutor_filter_jenis_pelayanan">
                                    <li class="nav-item">
                                        <a href="#tab-rawat_jalan" class="active nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-tagihan" >
                                            <span class="nav-link__count">
                                                01
                                            </span>
                                            Rawat Jalan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tab-rawat_inap" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                            <span class="nav-link__count">
                                                02
                                            </span>
                                            Rawat Inap
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tab-igd" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                            <span class="nav-link__count">
                                                03
                                            </span>
                                            IGD
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card card-body tab-content">
                                <div class="tab-pane show fade active" id="tab-rawat_jalan">
                                    <div class="row">
                                        <div class="col-lg">
                                            <input id="range_invoice_rajal" type="text" class="form-control range_invoice" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $yesterday->format("Y-m-d"); ?> to <?php echo $tomorrow->format("Y-m-d"); ?>" />
                                            <div class="row">
                                                <div class="col-lg" style="margin-top: 25px">
                                                    <table class="table table-padding table-striped largeDataType" id="table-biaya-pasien-rj" style="font-size: 0.9rem;">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th class="wrap_content">No. Tagihan</th>
                                                            <th class="wrap_content">Tanggal</th>
                                                            <th>Pasien</th>
                                                            <th>Poliklinik</th>
                                                            <th class="wrap_content">Staf Pendaftaran</th>
                                                            <th>Total Biaya</th>
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
                                <div class="tab-pane show fade" id="tab-rawat_inap">
                                    <div class="row">
                                        <div class="col-lg">
                                            <input id="range_invoice_ranap" type="text" class="form-control range_invoice" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $yesterday->format("Y-m-d"); ?> to <?php echo $tomorrow->format("Y-m-d"); ?>" />
                                            <div class="row">
                                                <div class="col-lg" style="margin-top: 25px">
                                                    <table class="table table-bordered table-striped largeDataType" id="table-biaya-pasien-ri" style="font-size: 0.9rem;">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th class="wrap_content">No. Tagihan</th>
                                                            <th class="wrap_content">Tanggal</th>
                                                            <th>Pasien</th>
                                                            <th>Poliklinik</th>
                                                            <th class="wrap_content">Staf Pendaftaran</th>
                                                            <th>Total Biaya</th>
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
                                <div class="tab-pane show fade" id="tab-igd">
                                    <div class="row">
                                        <div class="col-lg">
                                            <input id="range_invoice_igd" type="text" class="form-control range_invoice" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $yesterday->format("Y-m-d"); ?> to <?php echo $tomorrow->format("Y-m-d"); ?>" />
                                            <div class="row">
                                                <div class="col-lg" style="margin-top: 25px">
                                                    <table class="table table-bordered table-striped largeDataType" id="table-biaya-pasien-igd" style="font-size: 0.9rem;">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th class="wrap_content">No. Tagihan</th>
                                                            <th class="wrap_content">Tanggal</th>
                                                            <th>Pasien</th>
                                                            <th>Poliklinik</th>
                                                            <th class="wrap_content">Staf Pendaftaran</th>
                                                            <th>Total Biaya</th>
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



				<div class="tab-pane show fade" id="tab-kwitansi">
                    <div class="row">
						<div class="col-lg">
							<div class="row">
                                <div class="col-lg-6">
                                    <b>Filter Tanggal:</b>
                                    <input id="range_kwitansi" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                </div>
                                <div class="col-lg-3"></div>
                                <div class="col-lg-3">
                                    <b>Filter Jenis Pelayanan:</b>
                                    <select id="filter_kwitansi_item" class="form-control">
                                        <option value="*">Semua</option>
                                        <option value="RAJAL">Rawat Jalan</option>
                                        <option value="RANAP">Rawat Inap</option>
                                        <option value="IGD">IGD</option>
                                    </select>
                                    <br />
                                </div>
                                <div class="col-lg-12">
                                    <br />
                                    <table class="table table-bordered table-striped largeDataType" id="table-kwitansi">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Kwitansi</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Metode Bayar</th>
                                            <th>Petugas Kasir</th>
                                            <th>Total</th>
                                            <th>Rincian</th>
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
