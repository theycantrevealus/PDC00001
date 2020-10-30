<style type="text/css">
	
	.table-not-active {
		opacity: 50%;
		pointer-events: none;
		cursor: default;
		text-decoration: none;
		color: black;
	}

	.tableClass {
		font-size: 0.8rem;
	}
</style>

<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item">Master Unit</li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/unit/ruangan">Unit Ruangan</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-body">
            	<form>
            		<br />
					<div class="form-group offset-md-4 col-md-4">
						<select class="form-control" id="lantai">
							<option value="" disabled selected>Pilih Lantai</option>
						</select>
					</div>
					<br />
					<div class="row">
						<div class="col-md-6">
							<p align="center"><b>Ruangan Lama</b></p>
							<table class="table table-bordered" id="table-ruangan-lama">
								<thead>
									<tr>
										<th style="width: 20px;">No</th>
										<th>Nama Ruangan</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>

						<div class="col-md-6">
							<p align="center"><b>Ruangan Baru</b></p>
							<table class="table table-bordered tableClass" id="table-ruangan-baru">
								<thead>
									<tr>
										<th style="width: 20px;">No</th>
										<th>Nama Ruangan</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
					
					<hr />
					<button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Data</button>
					<a href="<?php echo __HOSTNAME__; ?>/master/unit/ruangan" class="btn btn-danger">Batal</a>
				</form>
            </div>
        </div>
    </div>
</div>
