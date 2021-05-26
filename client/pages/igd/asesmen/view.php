<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Rawat Inap</li>
					<li class="breadcrumb-item active" aria-current="page">Asesmen</li>
				</ol>
			</nav>
			<h4>- Asesmen Rawat Inap</h4>
		</div>
		<a href="<?php echo __HOSTNAME__; ?>/rawat_inap/asesmen" class="btn btn-danger btn-sm ml-3">
			<i class="fa fa-angle-left"></i> Kembali
		</a>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="card card-body tab-content">
				<div class="row">
					<div class="col-lg">
						<div class="card">
							<div class="card-header bg-white d-flex align-items-center">
								<h5 class="card-header__title flex m-0"><!-- <i class="material-icons mr-3">info_outline</i>  -->Informasi Pasien</h5>
							</div>
							<div class="card-body ">
								<div class="col-md-12">
									<table class="table">
										<tbody>
											<tr>
												<td width="40%">No. Rekam Medis</td>
												<td> : </td>
												<td><b><span id="no_rm">121-545-441</span></b></td>
											</tr>
											<tr>
												<td>Nama Pasien</td>
												<td> : </td>
												<td><b><span id="panggilan">Tn.</span> <span id="nama">MARCO DE GAMMA</span> </b></td>
											</tr>
											<tr>
												<td>Tanggal Lahir</td>
												<td> : </td>
												<td><b><span id="tanggal_lahir">29 Juni 1995</span></b></td>
											</tr>
											<tr>
												<td>Jenis Kelamin</td>
												<td> : </td>
												<td><b><span id="jenkel">Laki-laki</span></b></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="card">
							<div class="card-header d-flex align-items-center">
								<h5 class="card-header__title flex m-0">Tindakan</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 row">
										<div class="form-group col-lg-12">
											<table class="table table-bordered table-striped" id="list-tindakan" style='text-size: 0.8rem'>
												<thead>
													<th width="5%">No</th>
													<th width="50%">Tindakan</th>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>Cek Tekanan Darah</td>
													</tr>
													<tr>
														<td>2</td>
														<td>Ganti Botol Infus</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header d-flex align-items-center">
								<h5 class="card-header__title flex m-0">Obat</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 row">
										<div class="form-group col-lg-12">
											<table class="table table-bordered table-striped" id="list-tindakan" style='text-size: 0.8rem'>
												<thead>
													<th width="5%">No</th>
													<th width="50%">Obat</th>
													<th width="30%">Jumlah</th>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>Paracetamol</td>
														<td>2</td>
													</tr>
													<tr>
														<td>2</td>
														<td>Amoxilin</td>
														<td>1</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="card">
							<div class="card-header d-flex align-items-center">
								<h5 class="card-header__title flex m-0">Tanda-tanda Vital</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group col-lg-12">
											<label for="txt_td">TD</label>
											<div class="input-group input-group-merge">
												<input type="text" id="tanda_vital_td" name="tanda_vital_td" class="form-control form-control-appended inputan" placeholder="TD" value="120/80" disabled>
												<div class="input-group-append">
													<div class="input-group-text">
														<span>mmHg</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group col-lg-12">
											<label for="txt_n">N</label>
											<div class="input-group input-group-merge">
												<input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="N" value="80" disabled>
												<div class="input-group-append">
													<div class="input-group-text">
														<span>x/mnt</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group col-lg-12">
											<label for="txt_s">S</label>
											<div class="input-group input-group-merge">
												<input type="text" name="tanda_vital_s" id="tanda_vital_s" class="form-control form-control-appended inputan" placeholder="S" value="36" disabled>
												<div class="input-group-append">
													<div class="input-group-text">
														<span>x/mnt</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group col-lg-12">
											<label for="txt_rr">RR</label>
											<div class="input-group input-group-merge">
												<input type="text" name="tanda_vital_rr" id="tanda_vital_rr" class="form-control form-control-appended inputan" required="" placeholder="RR" value="120" disabled>
												<div class="input-group-append">
													<div class="input-group-text">
														<span>x/mnt</span>
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
			</div>
		</div>
	</div>
</div>