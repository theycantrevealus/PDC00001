<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/laboratorium">Master Laboratorium</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Item Laboratorium</li>
				</ol>
			</nav>
			<h4><span id="nama-departemen"></span>Tambah Laboratorium</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-lab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-lab-1" >
							<span class="nav-link__count">
								01
								<b class="lab-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b>
							</span>
							Informasi
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-lab-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-lab-1" >
							<span class="nav-link__count">
								02
								<b class="lab-tab-status text-success" id="status-2"><i class="fa fa-check-circle"></i></b>
							</span>
							Kategori
						</a>
					</li>
					<!-- <li class="nav-item">
						<a href="#tab-lab-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
								<b class="lab-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b>
							</span>
							Lokasi
						</a>
					</li> -->
					<li class="nav-item">
						<a href="#tab-lab-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								04
								<b class="lab-tab-status text-success" id="status-4"><i class="fa fa-check-circle"></i></b>
							</span>
                            Format Hasil Laboratorium
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane active show fade" id="tab-lab-1">
					<?php require 'info-item.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-lab-2">
					<?php require 'kategori-item.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<!-- <div class="tab-pane show fade" id="tab-lab-3">
					<?php require 'lokasi-item.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div> -->
				<div class="tab-pane show fade" id="tab-lab-4">
					<?php require 'nilai-item.php'; ?>
					<?php require 'action-panel.php'; ?>
				</div>
				<!-- <div class="tab-pane show fade" id="tab-lab-5">
					<?php /*require 'penjamin-item.php'; ?>
					<?php require 'action-panel.php';*/ ?>
				</div> -->
			</div>
		</div>
	</div>
</div>