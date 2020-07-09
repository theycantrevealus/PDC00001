<style type="text/css">
	
	.not-active {
		pointer-events: none;
		cursor: default;
		text-decoration: none;
		color: black;
	}

	.table-not-active {
		opacity: 50%;
		pointer-events: none;
		cursor: default;
		text-decoration: none;
		color: black;
	}

</style>

<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item">Master Poli</li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/poli/poli">Poli</a></li>
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
                    <a href="#" id="nav-tab-1" class="nav-link active navTabs not-active" data-toggle="tab" role="tab" aria-controls="tab-1" aria-selected="true">
                        Informasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="nav-tab-2" class="nav-link navTabs not-active" data-toggle="tab" role="tab" aria-selected="false">
                        Tindakan Poli
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="nav-tab-3" class="nav-link navTabs not-active" data-toggle="tab" role="tab" aria-selected="false">
                        Dokter
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="#" id="nav-tab-4" class="nav-link navTabs not-active" data-toggle="tab" role="tab" aria-selected="false">
                        Konfirmasi Data
                    </a>
                </li>
            </ul>
            <div class="card">
            	<form>
	                <div class="card-body tab-content">
	                    <div class="tab-pane active show fade tabsContent" id="tab-1">
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="txt_nama">Nama Poli:</label>
									<div class="search-form">
										<span style="margin-left: 4%;" class="text-center bg-light">Poli</span>
										<input type="text" class="form-control uppercase" id="txt_nama" placeholder="Nama Poli" required>
									</div>
								</div>
							</div>
							<hr />
		                    <ul class="list-inline float-right">
	                            <li><button type="button" class="btn btn-primary next-step btnNext btnNextInfo">Simpan dan lanjut</button></li>
	                        </ul>
	                    </div>

	                    <div class="tab-pane fade tabsContent" id="tab-2">
	                    	<div class="row">
		                    	<div class="col-md-6">
									<div class="col-md-12">
										<div class="form-group">
											<label for="txt_nama">Tindakan:</label>
											<!-- <input type="text" class="form-control uppercase" id="txt_nama" placeholder="Nama Poli" required> -->
											<select class="form-control tindakan" id="tindakan">
												 <option value="" disabled selected>Pilih Tindakan</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<table class="table table-bordered" id="table-tindakan">
											<thead>
												<tr>
													<th style="width: 20px;">No</th>
													<th>Tindakan</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-12">
										<label for="">Harga <span id="title-tindakan-penjamin"></span>:</label>
										<table class="table table-bordered table-not-active" id="table-penjamin">
											<thead>
												<tr>
													<th style="width: 10px;">No</th>
													<th>Penjamin</th>
													<th width="50%">Harga</th>
												</tr>
											</thead>
											<tbody style="font-size: 0.8rem;">

											</tbody>
										</table>
									</div>
								</div>
							</div>
							<hr />
	                        <ul class="list-inline ">
	                            <li><button type="button" class="btn btn-warning prev-step btnPrev float-left">Kembali</button></li>
	                            <li><button type="button" class="btn btn-primary next-step btnNext btnNextTindakan float-right"  >Simpan dan lanjut</button></li>
	                        </ul>
	                    </div>
	                    <div class="tab-pane fade tabsContent" id="tab-3">
	                    	<div class="row">
		                    	<div class="col-md-6">
									<div class="col-md-12">
										<table class="table-bordered table" id="poli-list-dokter">
											<thead>
												<tr>
													<th>No</th>
													<th>Nama</th>
													<th class="wrap_content">Aksi</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
							<hr />
	                        <ul class="list-inline ">
	                            <li><button type="button" class="btn btn-warning prev-step btnPrev float-left">Kembali</button></li>
	                            <li><button type="button" class="btn btn-primary next-step btnNext btnNextTindakan float-right"  >Simpan dan lanjut</button></li>
	                        </ul>
	                    </div>
	                    <div class="tab-pane fade tabsContent" id="tab-4">
	                    	<div class="row">
		                    	<div class="col-md-12">
									<p>Nama Poli: &nbsp; <b><span id="title-konfirmasi-poli" style="color: #4a90e2; font-size: 1.5rem;"></span></b></p>
									<hr />
									<span style="text-align: center;"><p>Tabel tindakan</p></span>
								</div>
								<br />
								<div class="col-md-12">
									<table class='table table-bordered table-striped' id="table-konfirmasi">
                        				<thead style="text-transform: uppercase;">
                        					
                        				</thead>
                        				<tbody>
                        					
                        				</tbody>
                    				</table>
								</div>
							</div>
							<hr />
	                        <ul class="list-inline">
	                        	<li><button type="button" class="btn btn-warning prev-step btnPrev float-left">Kembali</button></li>
	                            <li><button type="button" class="btn btn-success next-step float-right" id="btnSubmit">Simpan Data</button></li>
	                        </ul>
	                    </div>

	                </div>
	            </form>
            </div>
        </div>
    </div>
</div>
