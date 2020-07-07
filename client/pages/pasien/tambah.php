<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/pasien">Pasien</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Pasien Baru</li>
				</ol>
			</nav>
			<h4 class="m-0">Tambah Pasien</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-body">
				<form>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="txt_no_rm">Nomor Rekam Medis</label>
								</div>
								<div class="row col-md-6">
									<p class="col-md-3"><input type="text" autocomplete="off" maxlength="3" class="form-control uppercase no_rm" id="rm_sub_1" placeholder="000" required></p>
									<p class="col-md-3"><input type="text" autocomplete="off" maxlength="3" class="form-control uppercase no_rm" id="rm_sub_2" placeholder="000" required></p>
									<p class="col-md-3"><input type="text" autocomplete="off" maxlength="3" class="form-control uppercase no_rm" id="rm_sub_3" placeholder="000" required></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="txt_nama_pasien">Nama Pasien</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6"><input type="text" autocomplete="off" class="form-control uppercase inputan" id="nama" name="nama" placeholder="Nama Pasien" required></p>
									<p class="col-md-3">
										<select class="form-control inputan" name="panggilan" id="panggilan">
											<option value="" selected disabled>Panggilan</option>
										</select>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="tgl_lahir">Tanggal Lahir</label>
								</div>
								<div class="row col-md-6">
									<p class="col-md-6"><input type="date" class="form-control inputan" name="tanggal_lahir" required></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="tempat_lahir">Tempat Lahir</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6"><input type="text" class="form-control uppercase inputan" name="tempat_lahir" placeholder="Tempat Lahir" autocomplete="off"></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="jenkel">Jenis Kelamin</label>
								</div>
								<div class="row col-md-9" id="parent_jenkel">

								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="agama">Agama</label>
								</div>
								<div class="row col-md-10">
									<div class="row" id="parent_agama">
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="suku">Suku / Bangsa</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6">
										<select class="form-control inputan" name="suku" id="suku">
											<option value="" disabled selected>Pilih Suku / Bangsa</option>
										</select>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="pendidikan">Pendidikan</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6">
										<select class="form-control inputan" name="pendidikan" id="pendidikan">
											<option value="" disabled selected>Pilih Pendidikan</option>
										</select>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="goldar">Golongan Darah</label>
								</div>
								<div class="row col-md-9" id="parent_goldar">

								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="pekerjaan">Pekerjaan</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6">
										<select class="form-control inputan" name="pekerjaan" id="pekerjaan">
											<option value="" disabled selected>Pilih Pekerjaan</option>
										</select>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="nama_ayah">Nama Ayah</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6"><input type="text" class="form-control uppercase inputan" name="nama_ayah" placeholder="Nama Ayah"></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="nama_ibu">Nama Ibu</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6"><input type="text" class="form-control uppercase inputan" name="nama_ibu" placeholder="Nama Ibu"></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="nama_suami_istri">Nama Suami / Istri</label>
								</div>
								<div class="row col-md-9">
									<p class="col-md-6"><input type="text" autocomplete="off" class="form-control uppercase inputan" name="nama_suami_istri" placeholder="Nama Suami / Istri"></p>
									<p class="col-md-3">
										<select class="form-control inputan" name="status_suami_istri" id="status_suami_istri">
											<option value="" selected disabled>Status</option>
										</select>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="alamat">Alamat</label>
								</div>
								<div class="row col-md-10">
									<p class="col-md-6"><textarea class="form-control uppercase inputan" rows="3" name="alamat"></textarea></p>
									<div class="row col-md-4">
										<div class="col-md-5">
											<label for="rt">RT</label>
											<input type="text" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rt" placeholder="000">
										</div>
										<div class="col-md-5">
											<label for="rw">RW</label>
											<input type="text" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rw" placeholder="000">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<div class="col-md-4">	
										<label for="alamat_kecamatan">Kecamatan</label>
									</div>
									<div class="col-md-6">
										<select class="form-control inputan" name="alamat_kecamatan" id="alamat_kecamatan">
											<option value="" disabled selected>Pilih Kecamatan</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<div class="col-md-4">	
										<label for="alamat_kelurahan">Kelurahan</label>
									</div>
									<div class="col-md-6">
										<select class="form-control inputan" name="alamat_kelurahan" id="alamat_kelurahan">
											<option value="" disabled selected>Pilih Kelurahan</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">	
									<label for="no_telp">No. Telp</label>
								</div>
								<div class="row col-md-5">
									<p class="col-md-6">
										<input type="text" autocomplete="off" maxlength="15" class="form-control numberonly inputan" name="no_telp" placeholder="08xxxxxxxxxx">
									</p>
								</div>
							</div>
						</div>
					</div>
					<hr />
					<button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
					<a href="<?php echo __HOSTNAME__; ?>/master/pasien" class="btn btn-danger">Batal</a>
				</form>
			</div>
		</div>
	</div>
</div>
