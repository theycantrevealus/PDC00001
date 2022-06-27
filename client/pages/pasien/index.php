<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Pasien</li>
				</ol>
			</nav>
			<h4 class="m-0">Data Pasien</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0">Data Pasien</h5>
                            <a href="<?php echo __HOSTNAME__; ?>/pasien/tambah" class="btn btn-info pull-right ml-3">
                                <i class="fa fa-plus"></i> Tambah Pasien
                            </a>
                        </div>
                        <div class="card-header align-items-center" role="tablist">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input id="range_invoice" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control" id="filter-kunjungan">
                                        <option value="all">Semua</option>
                                        <option value="data_kurang">Data Belum Lengkap</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button class="btn btn-info" id="btn-import">
                                        <i class="fa fa-download"></i> Import
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body tab-content">
                            <div class="row card-group-row">
                                <div class="col-lg-12 col-md-12 card-group-row__col">
                                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                                        <table class="table table-bordered table-striped largeDataType" id="table-pasien">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="wrap_content">No</th>
                                                    <th>No RM</th>
                                                    <th>Nama Pasien</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Tanggal Daftar</th>
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
</div>