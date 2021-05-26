<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Master Poli</li>
				</ol>
			</nav>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header card-header-large bg-white d-flex align-items-center">
            <h5 class="card-header__title flex m-0">Master Poliklinik</h5>
            <a href="<?php echo __HOSTNAME__; ?>/master/poli/tambah" class="btn btn-info ml-3 pull-right">
                <i class="fa fa-plus"></i> Tambah Poli
            </a>
        </div>
        <div class="card-body">
            <div class="row card-group-row">
                <div class="col-lg-12 col-md-12 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <table class="table table-bordered" id="table-poli">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Nama Poli</th>
                                <th>Tindakan Konsultasi</th>
                                <th>Kode BPJS</th>
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
