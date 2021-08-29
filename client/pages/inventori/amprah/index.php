<?php
	$day=new DateTime('last day of this month');
?>
<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Amprah</li>
				</ol>
			</nav>
			<h4><span id="nama-departemen"></span>Amprah</h4>
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
							Proses Amprah
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-history" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-list"></i>
								<!-- <b class="inv-tab-status text-success" id="status-satuan"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Riwayat Amprah Selesai
						</a>
					</li>
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
								<a href="<?php echo __HOSTNAME__; ?>/inventori/amprah/tambah" class="btn btn-info pull-right">
									<i class="fa fa-plus"></i> Amprah Baru
								</a>
							</div>
						</div>
						<hr />
						<table class="table table-bordered" id="table-list-amprah">
							<thead class="thead-dark">
								<tr>
									<th class="wrap_content">No</th>
									<th>Tanggal</th>
									<th>Kode Amprah</th>
									<th>Diminta Oleh</th>
									<th>Status</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
                    <div class="tab-pane show fade" id="tab-history">
                        <div class="row">
                            <div class="col-md-2 text-right" style="padding: 10px">
                                Filter Tanggal:
                            </div>
                            <div class="col-md-6">
                                <input id="range_amprah_selesai" type="text" class="form-control" placeholder="Filter Tanggal" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                            </div>
                            <div class="col-md-4">
                                <!--<a href="<?php /*echo __HOSTNAME__; */?>/inventori/amprah/tambah" class="btn btn-info pull-right">
									<i class="fa fa-plus"></i> Amprah Baru
								</a>-->
                            </div>
                        </div>
                        <hr />
                        <table class="table table-bordered" id="table-list-amprah-selesai">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Kode Amprah</th>
                                <th>Diminta Oleh</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
