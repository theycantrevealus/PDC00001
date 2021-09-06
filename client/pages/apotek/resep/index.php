<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek">Apotek</a></li>
					<li class="breadcrumb-item active" aria-current="page">Resep</li>
				</ol>
			</nav>
			<h4 class="m-0">Resep</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<!--div class="card-group">
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-2">
							<i class="material-icons icon-muted icon-30pt">insert_chart</i>
						</div>
						<div class="col-md-10">
							<b>Status Ketersediaan Obat</b>
							<br />
							<span>Apotek</span>
							<br />
							<span>
								<b class="text-warning" id="warning_hampir">0 item</b> (hampir habis) <br />
								<b class="text-danger" id="warning_habis">0 item</b> (habis)
							</span>
							<h6 class="text-right">
								<small><a href="<?php echo __HOSTNAME__; ?>/inventori/amprah/tambah"><i class="fa fa-cubes"></i> Amprah</a></small>
							</h6>
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b>Kebutuhan Obat Terkini</b>
							<ol id="required_item_list">
							</ol>
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b></b>
						</div>
					</div>
				</div>
			</div-->
			<div class="card">
				<div class="card-header card-header-large d-flex align-items-center">
					<h5 class="card-header__title flex m-0">
                        Apotek
                        <span class="pull-right" id="status_gudang_apotek"></span>
                    </h5>
				</div>
				<div class="card-body tab-content">
					<div class="tab-pane active show fade" id="list-resep">
						<table class="table table-bordered table-striped largeDataType" id="table-resep">
							<thead class="thead-dark">
								<tr>
									<th class="wrap_content">No</th>
                                    <th style="width: 10%">Tanggal</th>
									<th style="width: 20%">Poliklinik</th>
									<th>Pasien</th>
									<th>Dokter</th>
									<th>Penjamin</th>
									<th class="wrap_content">Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="tab-pane active show fade" id="list-revisi">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>