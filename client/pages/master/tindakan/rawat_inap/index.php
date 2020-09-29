<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Master Perobatan</li>
					<li class="breadcrumb-item" aria-current="page">Tindakan</li>
					<li class="breadcrumb-item active" aria-current="page">Tindakan Rawat Inap</li>
				</ol>
			</nav>
		</div>
		<button class="btn btn-sm btn-info" id="tambah-tindakan">
			<i class="fa fa-plus"></i> Tambah Tindakan
		</button>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="row">
				<div class="col-lg">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Harga Tindakan</h5>
						</div>
						<div class="card-header card-header-tabs-basic nav" role="tablist">
							<a style="width: 400px;">
								<select class="form-control" id="filter-penjamin">
									<option>Pilih Penjamin</option>
								</select>
							</a>
						</div>
						<div class="card-body tab-content">
							<div class="tab-pane active show fade" id="resep-biasa">
								<table class="table table-bordered" id="table-tindakan">
									<thead class="thead-dark">
										<tr>
											<th class="wrap_content">No</th>
											<th style="width: 50%;">Nama Tindakan</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>