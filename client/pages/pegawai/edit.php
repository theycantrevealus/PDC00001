<?php
	$targetID = __PAGES__[count(__PAGES__) - 1];
?>
<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/pegawai">Pegawai</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit - <?php echo $targetID; ?></li>
				</ol>
			</nav>
			<h1 class="m-0">Edit Data Pegawai</h1>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-body">
				<form>
					<div class="row">
						<div class="col-sm-3">
							<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/avatar/demi.png" class="rounded-circle" width="100" alt="Frontted" />
						</div>
						<div class="col-sm-9">
							<div class="form-group">
								<label for="txt_nama_pegawai">Email:</label>
								<input type="text" class="form-control" disabled="disabled" id="txt_email_pegawai" placeholder="Enter your email address ..">
							</div>
							<div class="form-group">
								<label for="txt_nama_pegawai">Nama:</label>
								<input type="text" class="form-control" id="txt_nama_pegawai" placeholder="Enter your email address ..">
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-body">
				<div class="row">
					<div class="col-sm-6">
						<h5>Module</h5>
						<table class="table table-bordered largeDataType" id="module-table">
							<thead>
								<tr>
									<th>Module</th>
									<th>Methods</th>
									<th>Access</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-sm-6">
						<h5>Access Manager</h5>
						<table class="table table-bordered largeDataType" id="access-table">
							<thead>
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