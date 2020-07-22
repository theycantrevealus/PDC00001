<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori">Inventori</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori/po">PO</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
				</ol>
			</nav>
			<h4>Inventori - PO Baru</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-po-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-po-1" >
							<span class="nav-link__count">
								01
								<b class="inv-tab-status text-success" id="status-utama"><i class="fa fa-check-circle"></i></b>
							</span>
							Utama
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-po-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<b class="inv-tab-status text-success" id="status-dokumen"><i class="fa fa-check-circle"></i></b>
							</span>
							Dokumen Lampiran
						</a>
					</li>
				</ul>
			</div>
			<form id="submitPO">
				<div class="card card-body tab-content">
					<div class="tab-pane active show fade" id="tab-po-1">
						<?php
							require 'form-po-utama.php';
						?>
					</div>
					<div class="tab-pane show fade" id="tab-po-2">
						<?php
							require 'form-po-lampiran.php';
						?>
					</div>
				</div>
				<button class="btn btn-success" id="btnSubmitPO">
					<i class="fa fa-save"></i> Simpan
				</button>
				<a href="<?php echo __HOSTNAME__; ?>/inventori/po" class="btn btn-danger">
					<i class="fa fa-ban"></i> Kembali
				</a>
			</form>
		</div>
	</div>
</div>