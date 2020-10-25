<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/igd">IGD</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Asesmen Awal</li>
				</ol>
			</nav>
			<h4>IGD - Asesmen Awal</h4>
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
								<b class="inv-tab-status text-success" id="status-informasi"><i class="fa fa-check-circle"></i></b>
							</span>
							Halaman
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-awal-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<b class="inv-tab-status text-success" id="status-keperawatan"><i class="fa fa-check-circle"></i></b>
							</span>
							Halaman
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane active show fade" id="tab-awal-1">
					<?php require 'asesmen-awal-1.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-awal-2">
					<?php require 'asesmen-awal-2.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-tindakan">
					<div class="row">
						<div class="col-lg-12">
							<div class="card-group">
								<div class="card card-body">
									<div class="d-flex flex-row">
										<div class="col-md-2">
											<i class="material-icons icon-muted icon-30pt">account_circle</i>
										</div>
										<div class="col-md-10">
											<b>101-02-11</b><br />
											John Doe
										</div>
									</div>
								</div>
								<div class="card card-body">
									<div class="d-flex flex-row">
										<div class="col-md-12">
											<table class="table table-bordered">
												<tr>
													<td>
														Dokter
													</td>
													<td>
														Dr. John Doe
													</td>
												</tr>
												<tr>
													<td>
														Jam Triase
													</td>
													<td>
														Dr. John Doe
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane show fade" id="tab-resep">
					<div class="row">
						<div class="col-lg-12">
							<div class="card-group">
								<div class="card card-body">
									<div class="d-flex flex-row">
										<div class="col-md-2">
											<i class="material-icons icon-muted icon-30pt">account_circle</i>
										</div>
										<div class="col-md-10">
											<b>101-02-11</b><br />
											John Doe
										</div>
									</div>
								</div>
								<div class="card card-body">
									<div class="d-flex flex-row">
										<div class="col-md-12">
											<table class="table table-bordered">
												<tr>
													<td>
														Dokter
													</td>
													<td>
														Dr. John Doe
													</td>
												</tr>
												<tr>
													<td>
														Jam Triase
													</td>
													<td>
														Dr. John Doe
													</td>
												</tr>
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
	</div>
	<div class="row">
		<div class="col-md-12 text-right">
			<a  href="<?php echo __HOSTNAME__; ?>/igd" class="btn btn-danger">Kembali</a>
			<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
		</div>
	</div>
</div>