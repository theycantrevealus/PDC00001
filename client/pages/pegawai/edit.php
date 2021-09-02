<?php
	$targetID = __PAGES__[count(__PAGES__) - 1];
?>
<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/pegawai">Pengguna</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit - <?php echo $targetID; ?></li>
				</ol>
			</nav>
			<h4 class="m-0">Edit Data Pengguna</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-awal-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-informasi" >
							<span class="nav-link__count">
								01
							</span>
							Data Diri
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-awal-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<!-- <b class="inv-tab-status text-success" id="status-keperawatan"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Akses
						</a>
					</li>
				</ul>
			</div>
			<div class="tab-content">
				<div class="tab-pane active show fade" id="tab-awal-1">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Informasi Pengguna</h5>
						</div>
						<div class="card-body tab-content">
							<form>
								<div class="row">
									<div class="col-sm-4">
										<div class="profile_photo"></div>
										
									</div>
									<div class="col-sm-8">
										<div class="form-group">
											<label for="txt_nama_pegawai">Email:</label>
											<input type="text" class="form-control" id="txt_email_pegawai" placeholder="Enter your email address ..">
										</div>
										<div class="form-group">
											<label for="txt_nama_pegawai">Nama:</label>
											<input type="text" class="form-control" id="txt_nama_pegawai" placeholder="Nama Pegawai">
										</div>
										<div class="form-group">
											<label for="txt_jabatan">Unit:</label>
											<select class="form-control" id="txt_unit"></select>
										</div>
										<div class="form-group">
											<label for="txt_jabatan">Jabatan:</label>
											<select class="form-control" id="txt_jabatan"></select>
										</div>
										<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
											<a href="<?php echo __HOSTNAME__; ?>/pegawai" class="btn btn-danger"><i class="fa fa-ban"></i> Kembali</a>
									</div>
								</div>
								<div class="row" style="margin-top: 50px;">
									<div class="col-sm-4">
										<center>
											<div class="btn btn-info upload-container">
												<input type="file" class="customUpload" id="uploadImage" />
												Upload
											</div>
										</center>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane show fade" id="tab-awal-2">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Akses Pengguna</h5>
						</div>
						<div class="card-header card-header-tabs-basic nav" role="tablist">
							<a href="#pegawai-modul" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Halaman</a>
							<a href="#pegawai-akses" data-toggle="tab" role="tab" aria-selected="false">Akses</a>
						</div>
						<div class="card-body tab-content">
							<div class="tab-pane active show fade" id="pegawai-modul">
								<h5>Module</h5>
								<table class="table table-bordered largeDataType" id="module-table">
									<thead class="thead-dark">
										<tr>
											<th>Module</th>
											<th>Pages</th>
											<th>Access</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="tab-pane show fade" id="pegawai-akses">
								<h5>Access Manager</h5>
								<table class="table table-bordered largeDataType" id="access-table">
									<thead class="thead-dark">
										<tr>
											<th>Class</th>
											<th>Methods</th>
											<th>Access</th>
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
	</div>
</div>