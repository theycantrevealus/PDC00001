<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap">Rawat Inap</a></li>
					<li class="breadcrumb-item active" aria-current="page"><b id="target_pasien"></b></li>
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
						<div class="col-md-12">
							<b id="nama_pasien"></b>
							<br />
							<span id="jenkel_pasien"></span>
							<br />
							<span id="tanggal_lahir_pasien"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">List Asesmen</h5>
                    <button class="btn btn-info pull-right" id="btnTambahAsesmen">
                        <i class="fa fa-plus"></i> Tambah Asesmen
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Tgl</th>
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