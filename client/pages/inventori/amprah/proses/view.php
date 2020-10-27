<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori/amprah">Amprah</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">View</li>
				</ol>
			</nav>
			<h4>Amprah - Detail</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Detail Amprah</h5>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane active show fade">
					<div class="row">
						<div class="col-12">
							<h4 class="text-center">Proses Amprah</h4>
							<br />
						</div>
						<div class="col-6">
							<table class="table form-mode">
								<tr>
									<td class="wrap_content">Kode Amprah</td>
									<td class="wrap_content">:</td>
									<td>
										<b id="verif_kode"></b>
									</td>
								</tr>
								<tr>
									<td class="wrap_content">Nama Pengamprah</td>
									<td class="wrap_content">:</td>
									<td id="verif_nama"></td>
								</tr>
							</table>
						</div>
						<div class="col-6">
							<table class="table form-mode">
								<tr>
									<td class="wrap_content">Unit Pengamprah</td>
									<td class="wrap_content">:</td>
									<td id="verif_unit"></td>
								</tr>
								<tr>
									<td class="wrap_content">Tanggal Amprah</td>
									<td class="wrap_content">:</td>
									<td id="verif_tanggal"></td>
								</tr>
							</table>
						</div>
						<div class="col-12">
							<table id="table-verifikasi" class="table table-bordered largeDataType">
								<thead class="thead-dark">
									<tr>
										<th rowspan="2" class="wrap_content">No</th>
										<th rowspan="2" style="width: 20% !important">Item</th>
										<th rowspan="2" class="wrap_content">Satuan</th>
										<th rowspan="2" class="wrap_content">Permintaan</th>
										<th colspan="2">Disetujui</th>
									</tr>
									<tr>
										<th class="wrap_content">Jumlah</th>
										<th>Batch</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-12">
							<b>Keterangan</b>
							<br />
							<p id="verif_keterangan"></p>
						</div>
					</div>
				</div>
			</div>
			<button class="btn btn-success" id="btnSubmitProsesAmprah">
				<i class="fa fa-check"></i> Proses Amprah
			</button>
			<a href="<?php echo __HOSTNAME__; ?>/inventori/amprah/proses" class="btn btn-danger">
				<i class="fa fa-ban"></i> Kembali
			</a>
		</div>
	</div>
</div>