<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/igd">IGD</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Asesmen Keperawatan</li>
				</ol>
			</nav>
			<h4>IGD - Asesmen Keperawatan</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-keperawatan-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-keperawatan-1" >
							<span class="nav-link__count">
								01
								<b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b>
							</span>
							Halaman
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-keperawatan-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<b class="inv-tab-status text-success" id="status-2"><i class="fa fa-check-circle"></i></b>
							</span>
							Halaman
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-keperawatan-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
								<b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b>
							</span>
							Halaman
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-keperawatan-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								04
								<b class="inv-tab-status text-success" id="status-4"><i class="fa fa-check-circle"></i></b>
							</span>
							Halaman
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane active show fade" id="tab-keperawatan-1">
					<?php require 'asesmen-keperawatan-1.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-keperawatan-2">
					<?php require 'asesmen-keperawatan-2.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-keperawatan-3">
					<?php require 'asesmen-keperawatan-3.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-keperawatan-4">
					<?php require 'asesmen-keperawatan-4.php'; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-right">
			<a  href="<?php echo __HOSTNAME__; ?>/igd" class="btn btn-danger">Kembali</a>
			<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
		</div>
	</div>
</div>