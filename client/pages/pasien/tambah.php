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
						<label for="txt_no_rm">Nomor Rekam Medis</label>
						<input type="text" autocomplete="off" class="form-control uppercase inputan no_rm" id="no_rm" name="no_rm" placeholder="000-000" required>
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
                        <label>NIK <span class="red">*</span></label>
                        <input type="text" class="form-control text-uppercase inputan numberonly" maxlength="16" placeholder="NIK" value="" required name="nik" id="nik">
                        <span style="color: #dc3545; font-size: 0.8rem;" id="error-nik"></span>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
						<label for="txt_nama_pasien">Nama Pasien <span class="red">*</span></label>
						<input type="text" autocomplete="off" class="form-control uppercase inputan" id="nama" name="nama" placeholder="Nama Pasien" required>
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
						<input type="text" class="form-control uppercase inputan" name="tempat_lahir" placeholder="Tempat Lahir" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
						<label for="tanggal_lahir">Tanggal Lahir <span class="red">*</span></label>
						<input type="date" class="form-control inputan" name="tanggal_lahir" required>
                    </div>
                    <div class="col-12 col-md-2 mb-3">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
						<label for="jenkel">Jenis Kelamin <span class="red">*</span></label>
						<div class="row col-md-12" id="parent_jenkel">

						</div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Alias <span class="red">*</span></label>
                        <select name="panggilan" class="form-control inputan select2" id="panggilan" required>
                            <option value="">Pilih Alias</option>
                            
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
						<label for="alamat">Alamat <span >*</span></label>
						<textarea class="form-control uppercase inputan" rows="3" name="alamat" required></textarea>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                    	<div class="row">
							<div class="col-md-3">
								<label for="rt">RT</label>
								<input type="text" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rt" placeholder="000">
							</div>
							<div class="col-md-3">
								<label for="rw">RW</label>
								<input type="text" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rw" placeholder="000">
							</div>
						</div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
						<label for="alamat_provinsi">Provinsi <span class="red">*</span></label>
						<select class="form-control inputan select2" name="alamat_provinsi" id="alamat_provinsi" required>
							<option value="" disabled selected>Pilih Provinsi</option>
						</select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
						<label for="alamat_kabupaten">Kabupaten / Kota <span class="red">*</span></label>
						<select class="form-control inputan select2" name="alamat_kabupaten" id="alamat_kabupaten" required>
							<option value="" disabled selected>Pilih Kabupaten / Kota</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat_kecamatan">Kecamatan <span class="red">*</span></label>
						<select class="form-control inputan select2" name="alamat_kecamatan" id="alamat_kecamatan" required>
							<option value="" disabled selected>Pilih Kecamatan</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="alamat_kelurahan">Kelurahan <span class="red">*</span></label>
						<select class="form-control inputan select2" name="alamat_kelurahan" id="alamat_kelurahan" required>
							<option value="" disabled selected>Pilih Kelurahan</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="nama_ayah">Nama Ayah</label>
						<input type="text" class="form-control uppercase inputan" name="nama_ayah" placeholder="Nama Ayah">
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="nama_ibu">Nama Ibu</label>
						<input type="text" class="form-control uppercase inputan" name="nama_ibu" placeholder="Nama Ibu">
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
						<input type="text" autocomplete="off" maxlength="14" class="form-control numberonly inputan" name="no_telp" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                   		<label for="goldar">Golongan Darah <span class="red">*</span></label>	
						<!-- <div class="row col-md-9" id="parent_goldar">

						</div> -->
						<select class="form-control inputan select2" name="goldar" id="goldar" required>
							<option value="" disabled selected>Pilih Golongan Darah</option>
						</select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                    	<label for="agama">Agama <span class="red">*</span></label>
                    	<select class="form-control inputan select2" id="agama" name="agama" required>
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
						<select class="form-control inputan select2" name="pendidikan" id="pendidikan" required>
							<option value="" disabled selected>Pilih Pendidikan</option>
						</select>
					</div>
					<div class="col-12 col-md-6 mb-3">
						<label for="pekerjaan">Pekerjaan <span class="red">*</span></label>
						<select class="form-control inputan select2" name="pekerjaan" id="pekerjaan" required>
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
                    	<a href="<?php echo __HOSTNAME__; ?>/pasien" class="btn btn-danger">Batal</a>
                   <!--  </div> -->
                </div>
            </div>
        </div>
    </div>
	<!-- <div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-body">
				<button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
				<a href="<?php echo __HOSTNAME__; ?>/master/pasien" class="btn btn-danger">Batal</a>
			</div>
		</div>
	</div> -->
</form>
</div>
