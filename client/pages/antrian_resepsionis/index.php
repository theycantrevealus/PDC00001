<style type="text/css">
	.tabelScroll {
		height: 500px;
		overflow-x: scroll;
		overflow: scroll;
	}
</style>

<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Antrian</li>
				</ol>
			</nav>
			<h4 class="m-0">Antrian</h4>
		</div>
		<button class="btn btn-info btn-sm ml-3" id="btnTambahAntrian">
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
            <div class="card">
            	<div class="card-body">
            		<!-- <div class="col-md-12 row float-center">
						<div class="col-md-2">
							<label>Departemen <span class="red"></span></label>
						</div>
						<div class="col-md-4">
							<select class="form-control" id="select_departemen">
								<option value="">Semua</option>
							</select>
						</div>
	                </div> -->
	                <hr />
					<div class="table-responsive border-bottom">
						<table class="table table-bordered table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
							<thead>
								<tr>
									<th width="2%">No</th>
									<th>Waktu Masuk</th>
									<th>No. RM</th>
									<th>Pasien</th>
									<th>Departemen</th>
									<th>Dokter</th>
									<th>Penjamin</th>
									<th>Oleh</th>
									<!-- <th>Aksi</th> -->
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
