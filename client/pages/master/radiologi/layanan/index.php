<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Master Radiologi</li>
					<li class="breadcrumb-item active" aria-current="page">Layanan</li>
				</ol>
			</nav>
			<h4>Layanan Radiologi</h4>
		</div>
		<!-- <a href="<?php echo __HOSTNAME__; ?>/master/radiologi/layanan/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah
		</a> -->

	</div>
</div>

<div class="container-fluid page__container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Radiologi</h5>
                    <button class="btn btn-sm btn-info pull-right" id="importData" style="margin-right: 10px;">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <button class="btn btn-sm btn-info" id="btnTambahData">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list_master_lab">
                        <table class="table table-bordered" id="table-layanan-radiologi">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th width="50%">Nama</th>
                                    <th>Jenis</th>
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