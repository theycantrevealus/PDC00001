<?php
	$day=new DateTime('last day of this month');
?>
<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Mutasi Stok</li>
				</ol>
			</nav>
			<h4><span id="nama-departemen"></span>Mutasi Stok</h4>
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
								<i class="fa fa-cubes"></i>
								<!-- <b class="inv-tab-status text-success" id="status-informasi"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Mutasi Stok
						</a>
					</li>
					<!--li class="nav-item">
						<a href="#tab-history" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-list"></i>
							</span>
							Riwayat Mutasi Stok
						</a>
					</li-->
				</ul>
				<div class="card card-body tab-content">
					<div class="tab-pane active show fade" id="tab-informasi">
						<div class="row">
							<div class="col-md-2 text-right" style="padding: 10px">
								Filter Tanggal:
							</div>
							<div class="col-md-6">
								<input id="range_amprah" type="text" class="form-control" placeholder="Filter Tanggal" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
							</div>
							<div class="col-md-4">
								<a href="<?php echo __HOSTNAME__; ?>/inventori/stok/mutasi/tambah" class="btn btn-info pull-right">
									<i class="fa fa-plus"></i> Mutasi Stok Baru
								</a>
							</div>
						</div>
						<hr />
						<div class="dt-responsive table-responsive">
							<table class="table table-bordered table-responsive table-striped display nowrap" id="table-list-amprah">
								<thead class="thead-dark">
									<tr>
										<th class="all wrap_content">No</th>
										<th class="all wrap_content">Aksi</th>
										<th class="all">Tanggal</th>
										<th class="all">Kode</th>
										<th class="all">Dari</th>
										<th class="all">Ke</th>
										<th class="all">Diproses Oleh</th>
										<th class="all">Status</th>
										<th class="none">Keperluan</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane show fade" id="tab-history">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
