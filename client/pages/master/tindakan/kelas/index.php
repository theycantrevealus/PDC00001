<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Master Perobatan</li>
					<li class="breadcrumb-item active" aria-current="page">Kelas Tindakan</li>
				</ol>
			</nav>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-rj" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-rj" >
							<span class="nav-link__count">
								01
							</span>
							Rawat Jalan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-ri" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<!-- <b class="inv-tab-status text-success" id="status-keperawatan"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Rawat Inap
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-lab" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
							</span>
							Laboratorium
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-radio" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								04
							</span>
							Radiologi
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-fis" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								05
							</span>
							Fisioterapi
						</a>
					</li>
				</ul>
			</div>

			<div class="card card-body tab-content">
				<div class="tab-pane active show fade" id="tab-rj">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Kelas Rawat Jalan</h5>
						</div>
						<div class="card-body tab-content">
							<div class="row">
								<div class="col-md-9">
								</div>
								<div class="col-md-3">
									<button class="btn btn-sm btn-info tambah-tindakan" cat="rj">
										<i class="fa fa-plus"></i> Tambah Kelas Tindakan Rawat Jalan
									</button>
								</div>
								<div class="col-md-12">
									<br />
									<table class="table table-bordered" id="table-kelas-rj-tindakan">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th style="width: 50%;">Nama Kelas</th>
												<th class="wrap_content">Aksi</th>
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
				<div class="tab-pane show fade" id="tab-ri">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Kelas Rawat Inap</h5>
						</div>
						<div class="card-body tab-content">
							<div class="row">
								<div class="col-md-9">
								</div>
								<div class="col-md-3">
									<button class="btn btn-sm btn-info tambah-tindakan" cat="ri">
										<i class="fa fa-plus"></i> Tambah Kelas Tindakan Rawat Inap
									</button>
								</div>
								<div class="col-md-12">
									<br />
									<table class="table table-bordered" id="table-kelas-ri-tindakan">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th style="width: 50%;">Nama Kelas</th>
												<th class="wrap_content">Aksi</th>
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
				<div class="tab-pane show fade" id="tab-lab">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Kelas Laboratorium</h5>
						</div>
						<div class="card-body tab-content">
							<div class="row">
								<div class="col-md-9">
								</div>
								<div class="col-md-3">
									<button class="btn btn-sm btn-info tambah-tindakan" cat="lab">
										<i class="fa fa-plus"></i> Tambah Kelas Tindakan Laboratorium
									</button>
								</div>
								<div class="col-md-12">
									<br />
									<table class="table table-bordered" id="table-kelas-lab-tindakan">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th style="width: 50%;">Nama Kelas</th>
												<th class="wrap_content">Aksi</th>
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
				<div class="tab-pane show fade" id="tab-radio">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Kelas Radiologi</h5>
						</div>
						<div class="card-body tab-content">
							<div class="row">
								<div class="col-md-9">
								</div>
								<div class="col-md-3">
									<button class="btn btn-sm btn-info tambah-tindakan" cat="rad">
										<i class="fa fa-plus"></i> Tambah Kelas Tindakan Radiologi
									</button>
								</div>
								<div class="col-md-12">
									<br />
									<table class="table table-bordered" id="table-kelas-radio-tindakan">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th style="width: 50%;">Nama Kelas</th>
												<th class="wrap_content">Aksi</th>
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
				<div class="tab-pane show fade" id="tab-fis">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Kelas Laboratorium</h5>
						</div>
						<div class="card-body tab-content">
							<div class="row">
								<div class="col-md-9">
								</div>
								<div class="col-md-3">
									<button class="btn btn-sm btn-info tambah-tindakan" cat="fis">
										<i class="fa fa-plus"></i> Tambah Kelas Tindakan Fisioterapi
									</button>
								</div>
								<div class="col-md-12">
									<br />
									<table class="table table-bordered" id="table-kelas-fis-tindakan">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th style="width: 50%;">Nama Kelas</th>
												<th class="wrap_content">Aksi</th>
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
</div>