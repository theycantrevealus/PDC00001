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
<form id="form-add-pasien">
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Informasi Rekam Medis</strong></p>
				<!-- <p class="text-muted">Mohon masukkan data dengan benar <br>* Wajib diisi</p> -->
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<div class="col-12 col-md-4 mb-3">
						<label for="no_rm">Nomor Rekam Medis <span >*</span></label>
						<input type="text" autocomplete="off" class="inputan form-control uppercase no_rm required" id="no_rm" name="no_rm" placeholder="00-00-00">
						<span style="color: #dc3545; font-size: 0.8rem;" id="error-no-rm"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Informasi Utama</strong></p>
				<p class="text-muted">Mohon masukkan data dengan benar sesuai dengan KTP<br>* Wajib diisi</p>
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<div class="col-12 col-md-6 mb-3">
						<label for="nik">NIK <span class="red">*</span></label>
						<input type="text" class="form-control text-uppercase inputan numberonly required" maxlength="16" placeholder="NIK" value="" name="nik" id="nik">
						<span style="color: #dc3545; font-size: 0.8rem;" id="error-nik"></span>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="email">Email <span class="red">*</span></label>
						<input type="email" class="form-control inputan required" placeholder="email" value="" name="email" id="email">
						<span style="color: #dc3545; font-size: 0.8rem;" id="error-email"></span>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="nama">Nama Pasien <span class="red">*</span></label>
						<input type="text" autocomplete="off" class="form-control uppercase inputan required" id="nama" name="nama" placeholder="Nama Pasien">
						<div class="row col-md-9">
							<p class="col-md-6"></p>
							<!-- <p class="col-md-3">
								<select class="form-control inputan" name="panggilan" id="panggilan">
									<option value="" selected disabled>Panggilan</option>
								</select>
							</p> -->
						</div>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="tempat_lahir">Tempat Lahir <span class="red">*</span></label>
						<input id="tempat_lahir" type="text" class="form-control uppercase inputan required" name="tempat_lahir" placeholder="Tempat Lahir" autocomplete="off">
					</div>
					<div class="col-12 col-md-4 mb-3">
						<label for="tanggal_lahir">Tanggal Lahir <span class="red">*</span></label>
						<input type="date" class="form-control inputan required" name="tanggal_lahir" id="tanggal_lahir">
					</div>
					<div class="col-12 col-md-2 mb-3">
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="jenkel">Jenis Kelamin <span class="red">*</span></label>
						<div class="row col-md-12" id="parent_jenkel">

						</div>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="panggilan">Alias <span class="red">*</span></label>
						<select name="panggilan" class="form-control inputan select2 required" id="panggilan">
							<option value="">Pilih Alias</option>
							
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat">Alamat <span >*</span></label>
						<textarea class="form-control uppercase inputan required" rows="3" name="alamat" id="alamat"></textarea>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<div class="row">
							<div class="col-md-6">
								<label for="rt">RT</label>
								<input type="text" id="rt" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rt" placeholder="000">
							</div>
							<div class="col-md-6">
								<label for="rw">RW</label>
								<input type="text" id="rw" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rw" placeholder="000">
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat_provinsi">Provinsi <span class="red">*</span></label>
						<select class="form-control inputan select2 required" name="alamat_provinsi" id="alamat_provinsi">
							<option value="" disabled selected>Pilih Provinsi</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat_kabupaten">Kabupaten / Kota <span class="red">*</span></label>
						<select class="form-control inputan select2 required" name="alamat_kabupaten" id="alamat_kabupaten">
							<option value="" disabled selected>Pilih Kabupaten / Kota</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat_kecamatan">Kecamatan <span class="red">*</span></label>
						<select class="form-control inputan select2 required" name="alamat_kecamatan" id="alamat_kecamatan">
							<option value="" disabled selected>Pilih Kecamatan</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat_kelurahan">Kelurahan <span class="red">*</span></label>
						<select class="form-control inputan select2 required" name="alamat_kelurahan" id="alamat_kelurahan">
							<option value="" disabled selected>Pilih Kelurahan</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="nama_ayah">Nama Ayah</label>
						<input type="text" class="form-control uppercase inputan" name="nama_ayah" placeholder="Nama Ayah">
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="nama_ibu">Nama Ibu <span >*</span></label>
						<input type="text" class="form-control uppercase inputan required" name="nama_ibu" placeholder="Nama Ibu" id="nama_ibu">
					</div> 
					<div class="col-12 col-md-6 mb-3">
						<label for="nama_suami_istri">Nama Suami / Istri</label>
						<input type="text" autocomplete="off" class="form-control uppercase inputan" name="nama_suami_istri" placeholder="Nama Suami / Istri">
					</div> 
					<div class="col-12 col-md-6 mb-3">
						<label for="status_suami_istri">Status Hubungan</label>
						<select class="form-control inputan select2" name="status_suami_istri" id="status_suami_istri">
							<option value="" selected disabled>Status</option>
						</select>
					</div>   
				</div>
			</div>
		</div>
	</div>
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Informasi Umum</strong></p>
				<p class="text-muted">Mohon masukkan data dengan benar <br>* Wajib diisi</p>
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<div class="col-12 col-md-6 mb-3">
						<label for="no_telp">No. Telp <span class="red">*</span></label>
						<input id="no_telp" type="text" autocomplete="off" maxlength="14" class="form-control numberonly inputan required" name="no_telp" placeholder="08xxxxxxxxxx">
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="goldar">Golongan Darah</label>	
						<!-- <div class="row col-md-9" id="parent_goldar">

						</div> -->
						<select class="form-control inputan select2" name="goldar" id="goldar">
							<option value="" disabled selected>-</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="agama">Agama <span class="red">*</span></label>
						<select class="form-control inputan select2 required" id="agama" name="agama">
							<option value="" selected disabled>Pilih Agama</option>
						</select>	
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="warganegara">Kewarganegaraan </label>
						<select class="form-control inputan select2" id="warganegara" name="warganegara">
							<option value="" selected disabled>Pilih Kewarganegaraan</option>
						</select>	
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="suku">Suku / Bangsa</label>
						<select class="form-control inputan select2" name="suku" id="suku">
							<option value="" disabled selected>Pilih Suku / Bangsa</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="pendidikan">Pendidikan <span class="red">*</span></label>
						<select class="form-control inputan select2 required" name="pendidikan" id="pendidikan">
							<option value="" disabled selected>Pilih Pendidikan</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="pekerjaan">Pekerjaan <span class="red">*</span></label>
						<select class="form-control inputan select2 required" name="pekerjaan" id="pekerjaan">
							<option value="" disabled selected>Pilih Pekerjaan</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Konfirmasi</strong></p>
				<p class="text-muted">Harap konfirmasi kembali data yang telah di masukkan</p>
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<!-- <div class="col-12 col-md-4 mb-3"> -->
						<button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
						&nbsp;
					<!-- </div>
					<div class="col-12 col-md-4 mb-3"> -->
						<a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/antrian_online" class="btn btn-danger">Batal</a>
				   <!--  </div> -->
				</div>
			</div>
		</div>
	</div>
</form>
</div>
