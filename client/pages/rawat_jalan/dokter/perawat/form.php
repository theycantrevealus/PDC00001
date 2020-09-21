<div class="row card-group-row">
	<div class="col-lg-12 col-md-12">
		<div class="z-0">
			<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
				<li class="nav-item">
					<a href="#tab-assesment-awal-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
						<span class="nav-link__count">
							01
							<b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b>
						</span>
						Halaman 1
					</a>
				</li>
				<li class="nav-item">
					<a href="#tab-assesment-awal-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
						<span class="nav-link__count">
							02
							<b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b>
						</span>
						Halaman 2
					</a>
				</li>
			</ul>
		</div>
		<div class="card card-body tab-content">
			<div class="tab-pane show fade active" id="tab-assesment-awal-1">
				<?php require 'halaman1.php'; ?>
				<?php //require 'action_panel.php'; ?>
			</div>
			<div class="tab-pane show fade " id="tab-assesment-awal-2">
				<?php require 'halaman2.php'; ?>
				<?php //require 'action_panel.php'; ?>
			</div>
		</div>
		<!-- <div class="card card-footer">
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-success" id="btnSelesai">
						<i class="fa fa-check-circle"></i> Selesai
					</button>
					<a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/perawat" class="btn btn-danger">
						<i class="fa fa-ban"></i> Kembali
					</a>
				</div>
			</div>
		</div> -->
	</div>
</div>