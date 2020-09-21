<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_jalan">Rawat Jalan</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/dokter">Antrian Poliklinik</a></li>
					<li class="breadcrumb-item active" aria-current="page">Pemeriksaan Medis</li>
				</ol>
			</nav>
			<h4><span id="nama-departemen"></span> - Pemeriksaan</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-poli-1" class="active nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								01
								<b class="inv-tab-status text-success" id="status-rawat"><i class="fa fa-check-circle"></i></b>
							</span>
							Asesmen Rawat
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								02
								<b class="inv-tab-status text-success" id="status-2"><i class="fa fa-check-circle"></i></b>
							</span>
							Asesmen Medis
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
								<b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b>
							</span>
							Tindakan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								04
								<b class="inv-tab-status text-success" id="status-4"><i class="fa fa-check-circle"></i></b>
							</span>
							Resep
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-5" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								05
								<b class="inv-tab-status text-success" id="status-5"><i class="fa fa-check-circle"></i></b>
							</span>
							Laboratorium
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-6" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								06
								<b class="inv-tab-status text-success" id="status-6"><i class="fa fa-check-circle"></i></b>
							</span>
							Radiologi
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-7" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								07
								<b class="inv-tab-status text-success" id="status-7"><i class="fa fa-check-circle"></i></b>
							</span>
							CPPT
						</a>
					</li>
					<!-- <li class="nav-item">
						<a href="#tab-poli-8" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								08
								<b class="inv-tab-status text-success" id="status-8"><i class="fa fa-check-circle"></i></b>
							</span>
							Review
						</a>
					</li> -->
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane show fade active perawat" id="tab-poli-1">
					<?php require 'info-pasien.php'; ?>
					<?php require 'perawat/form.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-2">
					<?php require 'info-pasien.php'; ?>
					<?php require 'asesmen-awal.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-3">
					<?php require 'info-pasien.php'; ?>
					<?php require 'tindakan.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-4">
					<?php require 'info-pasien.php'; ?>
					<?php require 'resep.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-5">
					<?php require 'info-pasien.php'; ?>
					<?php require 'laboratorium.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-6">
					<?php require 'info-pasien.php'; ?>
					<?php require 'radiologi.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-7">
					<?php require 'info-pasien.php'; ?>
					<?php require 'cppt.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<!-- <div class="tab-pane show fade" id="tab-poli-8">
					<?php
						/*require 'info-pasien.php';
						require 'review.php';
						require 'action-panel.php';*/
					?>
				</div> -->
			</div>
		</div>
	</div>
</div>