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
			<h4>Asesmen Harian - Perawat</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-1" class="nav-link " data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								01
								<b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b>
							</span>
							Pengkajian 
							<br />
							Kulit
							<br />
							<br />
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-2" class="nav-link " data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<b class="inv-tab-status text-success" id="status-2"><i class="fa fa-check-circle"></i></b>
							</span>
							Pemeriksaan 
							<br />
							Tanda-tanda 
							<br />
							Vital
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-3" class="nav-link " data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
								<b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b>
							</span>
							Skiring 
							<br />
							Status 
							<br />
							Fungsional
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-4" class="nav-link " data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								05
								<b class="inv-tab-status text-success" id="status-4"><i class="fa fa-check-circle"></i></b>
							</span>
							Pengkajian 
							<br />
							Resiko 
							<br />
							Jatuh
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-5" class="nav-link active" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								05								
								<b class="inv-tab-status text-success" id="status-5"><i class="fa fa-check-circle"></i></b>
							</span>
							Pengkajian 
							<br />
							Nyeri 
							<br />
							<br />
						</a>
					</li>
				</ul>
			</div> 
			<div class="card card-body tab-content">
				<div class="tab-pane show fade " id="tab-1">
					<?php require 'kaji_kulit.php'; ?>
				</div>
				<div class="tab-pane show fade " id="tab-2">
					<?php require 'tanda_vital.php'; ?>
				</div>
				<div class="tab-pane show fade " id="tab-3">
					<?php require 'skrining_fungsional.php'; ?>
				</div>
				<div class="tab-pane show fade " id="tab-4">
					<?php require 'kaji_resiko_jatuh.php'; ?>
				</div>
				<div class="tab-pane show fade active" id="tab-5">
					<?php require 'kaji_nyeri.php'; ?>
				</div>
			</div>
			<div class="card card-footer">
				<div class="row">
					<div class="col-md-12">
						<button type="button" class="btn btn-success" id="btnSelesai">
								<i class="fa fa-check-circle"></i> Selesai
						</button>
						<a href="<?php echo __HOSTNAME__; ?>/rawat_inap/perawat/asesmen_harian" class="btn btn-danger">
							<i class="fa fa-ban"></i> Kembali
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
