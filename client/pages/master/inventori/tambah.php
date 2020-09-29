<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/inventori">Master Item</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
				</ol>
			</nav>
			<h4><span id="nama-departemen"></span>Tambah Inventori</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-informasi" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-informasi" >
							<span class="nav-link__count">
								<i class="fa fa-info-circle"></i>
								<b class="inv-tab-status text-success" id="status-informasi"><i class="fa fa-check-circle"></i></b>
							</span>
							Informasi Dasar
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-satuan" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-cubes"></i>
								<b class="inv-tab-status text-success" id="status-satuan"><i class="fa fa-check-circle"></i></b>
							</span>
							Satuan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-penjamin" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-cash-register"></i>
								<b class="inv-tab-status text-success" id="status-penjamin"><i class="fa fa-check-circle"></i></b>
							</span>
							Harga Penjamin
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-lokasi" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-clipboard-list"></i>
								<b class="inv-tab-status text-success" id="status-lokasi"><i class="fa fa-check-circle"></i></b>
							</span>
							Lokasi Simpan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-monitoring" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-eye"></i>
								<b class="inv-tab-status text-success" id="status-monitoring"><i class="fa fa-check-circle"></i></b>
							</span>
							Monitoring
						</a>
					</li>
				</ul>
				<div class="card card-body tab-content">
					<div class="tab-pane active show fade" id="tab-informasi">
						<?php require 'form-dasar.php'; ?>
					</div>
					<div class="tab-pane show fade" id="tab-satuan">
						<?php require 'form-satuan.php'; ?>
					</div>
					<div class="tab-pane show fade" id="tab-penjamin">
						<?php require 'form-penjamin.php'; ?>
					</div>
					<div class="tab-pane show fade" id="tab-lokasi">
						<?php require 'form-lokasi.php'; ?>
					</div>
					<div class="tab-pane show fade" id="tab-monitoring">
						<?php require 'form-monitoring.php'; ?>
					</div>
					<div class="col-md-12">
						<button type="submit" id="btn_save_data" class="btn btn-success"><i class="fa fa-save"></i> Simpan & Keluar</button>
						<button type="submit" id="btn_save_data_stay" class="btn btn-info"><i class="fa fa-save"></i> Simpan & Tetap Disini</button>
						<a href="<?php echo __HOSTNAME__; ?>/master/inventori" class="btn btn-danger"><i class="fa fa-ban"></i> Kembali</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
