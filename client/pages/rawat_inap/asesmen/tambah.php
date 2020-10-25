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
											<div class="form-group col-lg-10">
												<label for="jumlah">Jumlah</label>
												<div class="input-group">
													<select id="tindakan" class="form-control select2">
														<option value="">Pilih</option>
														<option value="1">Cek Tekanan Darah</option>
														<option value="2">Ganti Infus</option>
													</select>
												</div>
											</div>
											<div class="form-group col-lg-2">
												<div style="margin-top: 50%;">
													<button id="btnSimpanTindakan" type="button" class="btn btn-sm btn-primary">Tambah Tindakan</button>
												</div>
											</div>
										</div>
									<!-- </form> -->
									<div class="col-lg-12 row">
										<div class="form-group col-lg-12">
											<table class="table table-bordered table-striped" id="list-tindakan" style='text-size: 0.8rem'>
												<thead>
													<!-- <th width="5%">No</th> -->
													<th width="50%">Tindakan</th>
													<th width="5%">Aksi</th>
												</thead>
												<tbody>
													
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
									<!-- <form id="form-tambah-obat"> -->
										<div class="col-lg-12 row">
											<div class="form-group col-lg-12">
												<label for="obat">Obat</label>
												<div class="input-group input-group-merge">
													<select id="obat" class="form-control select2">
														<option value="">Pilih</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-lg-12 row">
											<div class="form-group col-lg-9">
												<label for="jumlah">Jumlah</label>
												<div class="input-group">
													<input type="text" name="jumlah" id="jumlah" class="form-control" placeholder="0">
												</div>
											</div>
											<div class="form-group col-lg-3">
												<div style="margin-top: 30%;">
													<button type="button" id="btnSimpanObat" class="btn btn-sm btn-primary">Tambah Obat</button>
												</div>
											</div>
										</div>
									<!-- </form> -->
									<div class="col-lg-12 row">
										<div class="form-group col-lg-12">
											<table class="table table-bordered table-striped" id="list-obat" style='text-size: 0.8rem'>
												<thead>
													<!-- <th width="5%">No</th> -->
													<th width="50%">Obat</th>
													<th width="5%">Jumlah</th>
													<th width="5%">Aksi</th>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--  -->
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
												<input type="text" id="tanda_vital_td" name="tanda_vital_td" class="form-control form-control-appended inputan" placeholder="TD">
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
												<input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="N">
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
												<input type="text" name="tanda_vital_s" id="tanda_vital_s" class="form-control form-control-appended inputan" placeholder="S">
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
												<input type="text" name="tanda_vital_rr" id="tanda_vital_rr" class="form-control form-control-appended inputan" required="" placeholder="RR">
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
					<div class="col-lg-8 card-body">
	                    <div class="form-row">
	                        <a href="<?php echo __HOSTNAME__; ?>/rawat_inap/asesmen" class="btn btn-success">Simpan Data</a>
	                        &nbsp;
	                        <a href="<?php echo __HOSTNAME__; ?>/rawat_inap/asesmen" class="btn btn-danger">Batal</a>
	                    </div>
	                </div>
	            </div>
			</div>
		</div>
		
	</div>
</div>