<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori">Inventori</a></li>
					<li class="breadcrumb-item active" aria-current="page">Barang Masuk</li>
				</ol>
			</nav>
			<h4>Inventori - Barang Masuk</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-do" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-do" >
							<span class="nav-link__count">
								01
								<!-- <b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Terima barang
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-do-history" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<!-- <b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Riwayat Terima Barang
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane show fade active" id="tab-do">
					<table class="table table-bordered" id="table-do" style="font-size: 0.9rem;">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>PO</th>
                                <th>Tanggal</th>
								<th>Pemasok</th>
								<th>Dibuat Oleh</th>
								<th class="wrap_content">Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="tab-pane show fade " id="tab-do-history">
					<table class="table table-bordered" id="table-do-his" style="font-size: 0.9rem;">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>Tanggal</th>
								<th>No. DO</th>
								<!-- <th>No. Dokumen</th> -->
								<th>Pemasok</th>
								<th>Invoice</th>
								<th>Pegawai</th>
								<!--<th>Status</th>-->
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