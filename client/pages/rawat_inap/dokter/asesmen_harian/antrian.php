<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Rawat Inap</li>
					<li class="breadcrumb-item active" aria-current="page">Antrian Rawat Inap</li>
				</ol>
			</nav>
			<h4>Asesmen Harian - Dokter</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-1" >
							<span class="nav-link__count">
								01
								<b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b>
							</span>
							Terapi Cairan Infus
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane show fade active" id="tab-1">
					<?php require 'terapi_infus.php'; ?>
				</div>
			</div>
			<div class="card card-footer">
				<div class="row">
					<div class="col-md-12">
						<button type="button" class="btn btn-success" id="btnSelesai">
								<i class="fa fa-check-circle"></i> Selesai
						</button>
						<a href="<?php echo __HOSTNAME__; ?>/rawat_inap/dokter/asesmen_harian" class="btn btn-danger">
							<i class="fa fa-ban"></i> Kembali
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
