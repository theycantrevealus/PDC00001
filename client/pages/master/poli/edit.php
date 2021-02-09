<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item">Master Poliklinik</li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/poli">Poliklinik</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
    <div class="col-lg">
        <div class="z-0">
            <ul class="nav nav-tabs nav-tabs-custom tabList" role="tablist">
                <li class="nav-item">
                    <a href="#tab-1" id="nav-tab-1" class="nav-link active navTabs" data-toggle="tab" role="tab" aria-controls="tab-1" aria-selected="true">
                        Informasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-2" id="nav-tab-2" class="nav-link navTabs" data-toggle="tab" role="tab" aria-selected="false">
                        Tindakan Poliklinik
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-3" id="nav-tab-3" class="nav-link navTabs" data-toggle="tab" role="tab" aria-selected="false">
                        Dokter
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-4" id="nav-tab-4" class="nav-link navTabs" data-toggle="tab" role="tab" aria-selected="false">
                        Perawat / Terapis
                    </a>
                </li>
            </ul>
            <div class="card">
            	<div class="card-body tab-content">
                    <div class="tab-pane active show fade tabsContent" id="tab-1">
                    	<div class="row">
							<div class="col-lg">
								<div class="card">
									<div class="card-header card-header-large bg-white d-flex align-items-center">
										<h5 class="card-header__title flex m-0">Informasi Poli</h5>
									</div>
									<div class="card-body tab-content">
				                        <div class="col-md-6">
											<div class="form-group">
												<label for="txt_nama">Nama Poliklinik:</label>
												<div class="search-form">
													<input type="text" class="form-control" id="txt_nama" placeholder="Nama Poli" required>
												</div>
											</div>
											<div class="form-group">
												<label for="txt_nama">Tindakan Konsultasi:</label>
												<div class="search-form">
													<select class="form-control" id="tindakan_konsultasi">
														<option value="" disabled>Pilih Tindakan</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
                                <div class="card">
                                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                                        <h5 class="card-header__title flex m-0">Integrasi BPJS</h5>
                                    </div>
                                    <div class="card-body tab-content">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_bpjs_poli"><i class="fa fa-link"></i> Link Poliklinik:</label>
                                                <div class="search-form">
                                                    <select type="text" class="form-control" id="txt_bpjs_poli" placeholder="Target Poli"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
                    </div>

                    <div class="tab-pane fade tabsContent" id="tab-2">
                    	<div class="row">
							<div class="col-lg">
								<div class="card">
									<div class="card-header card-header-large bg-white align-items-center">
										<div class="form-group">
											<label for="txt_nama">Tindakan:</label>
											<select class="form-control tindakan" id="tindakan">
												 <option value="" disabled selected>Pilih Tindakan</option>
											</select>
										</div>
									</div>
									<div class="card-body tab-content">
		                    			<table class="table table-bordered" id="table-tindakan">
											<thead class="thead-dark">
												<tr>
													<th class="wrap_content">No</th>
													<th>Tindakan</th>
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
                    <div class="tab-pane fade tabsContent" id="tab-3">
                    	<div class="row">
                    		<div class="col-md-12">
                    			<select class="form-control" id="txt_set_dokter"></select>
                    		</div>
                    	</div>
                    	<br />
                    	<div class="row">
	                    	<div class="col-md-12">
								<table class="table-bordered table" id="poli-list-dokter">
									<thead class="thead-dark">
										<tr>
											<th class="wrap_content">No</th>
											<th>Nama</th>
											<th class="wrap_content">Aksi</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
                    </div>
                    <div class="tab-pane fade tabsContent" id="tab-4">
                    	<div class="row">
                    		<div class="col-md-12">
                    			<select class="form-control" id="txt_set_perawat">
                    				<option value="">Pilih</option>
                    			</select>
                    		</div>
                    	</div>
                    	<br />
                    	<div class="row">
	                    	<div class="col-md-12">
								<table class="table-bordered table" id="poli-list-perawat">
									<thead class="thead-dark">
										<tr>
											<th class="wrap_content">No</th>
											<th>Nama</th>
											<th class="wrap_content">Aksi</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
                    </div>
                    <div class="col-md-12 text-right">
	                    <button class="btn btn-success" id="btnSubmit">
	                    	<i class="fa fa-check"></i> Submit
	                    </button>
	                    <a class="btn btn-danger" href="<?php echo __HOSTNAME__; ?>/master/poli">
	                    	<i class="fa fa-ban"></i> Kembali
	                    </a>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>
