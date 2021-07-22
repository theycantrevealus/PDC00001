<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/igd">IGD</a></li>
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
						<div class="col-md-3">
                            <span id="rm_pasien" class="text-info"></span>
                            <br />
                            <b id="nama_pasien"></b>
                            <br />
                            <span id="tempat_lahir_pasien"></span>, <span id="tanggal_lahir_pasien"></span> (<span id="usia_pasien"></span> tahun)
							<br />
							<span id="jenkel_pasien"></span>
							<br />
							<span id="alamat_pasien"></span>
						</div>
                        <div class="col-md-9">
                            <div class="form-row" data-toggle="dragula">
                                <div class="col-md col-lg-3 handy print_manager" id="gelang">
                                    <div class="card form-row__card text-white bg-primary">
                                        <div class="card-body">
                                            <h6 class="text-white"><i class="fa fa-band-aid"></i>&nbsp;&nbsp;&nbsp;Gelang Pasien</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md col-lg-3 handy print_manager" id="kartu">
                                    <div class="card form-row__card bg-success text-white">
                                        <div class="card-body">
                                            <h6 class="text-white"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Kartu Pasien</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-purple" id="btnInap">
                                        <i class="fa fa-bed"></i> Pindah Rawat Inap
                                    </button>
                                </div>
                                <!--divclass="col-md col-lg-3 handy print_manager" id="kartu">
                                    <div class="card form-row__card bg-purple text-white">
                                        <div class="card-body">
                                            <h6 class="text-white"><i class="fa fa-flask"></i>&nbsp;&nbsp;&nbsp;Label Lab Pasien</h6>
                                        </div>
                                    </div>
                                </div-->
                            </div>
                        </div>
					</div>
				</div>
			</div>
			<div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Rekam Medis</h5>
                    <button class="btn btn-info pull-right" id="btnTambahAsesmen">
                        <i class="fa fa-plus"></i> Tambah Asesmen
                    </button>
                </div>
                <div class="card-body">
                    <!--<table class="table table-bordered table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Tgl</th>
                            <th>Dokter</th>
                            <th class="wrap_content">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>-->
                    <div id="cppt_loader">

                    </div>
                </div>
			</div>
		</div>
	</div>
</div>