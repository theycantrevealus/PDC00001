<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/pegawai">Pegawai</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah</li>
				</ol>
			</nav>
			<h1 class="m-0">Tambah Data Pegawai</h1>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<form>
					<div class="form-group">
						<label for="txt_nama_pegawai">Nama:</label>
						<input type="text" class="form-control" id="txt_nama_pegawai" placeholder="Enter your email address ..">
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>