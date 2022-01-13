<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/pasien">Pasien</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit Data Pasien</li>
				</ol>
			</nav>
			<h4 class="m-0">Edit Pasien</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                    <li class="nav-item">
                        <a href="#tab-info" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-info" >
							<span class="nav-link__count">
								01
								<!--<b class="inv-tab-status text-success" id="status-rawat"><i class="fa fa-check-circle"></i></b>-->
							</span>
                            Informasi pasien
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-penjamin" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-penjamin" >
							<span class="nav-link__count">
								02
                                <!--<b class="inv-tab-status text-success" id="status-rawat"><i class="fa fa-check-circle"></i></b>-->
							</span>
                            Penjamin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-cppt" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-penjamin" >
							<span class="nav-link__count">
								03
							</span>
                            CPPT
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">

                <div class="tab-pane show fade active" id="tab-info">
                    <form>
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
                                            <input readonly type="text" autocomplete="off" class="form-control uppercase inputan no_rm required" id="no_rm" name="no_rm" placeholder="00-00-00" required>
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
                                    <?php require 'kartu-pasien.php'; ?>
                                </div>
                                <div class="col-lg-8 card-form__body card-body">
                                    <div class="form-row">
                                        <div class="col-12 mb-3">
                                            <label for="warganegara">Kewarganegaraan <span class="red">*</span></label>
                                            <select class="form-control inputan select2" id="warganegara" name="warganegara" required>
                                            </select>
                                            <span style="color: #dc3545; font-size: 0.8rem;" id="error-wn"></span>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3 loader-wni">
                                            <label for="nik">NIK <span class="red">*</span></label>
                                            <input type="text" class="form-control inputan numberonly" maxlength="16" placeholder="NIK" value="" id="nik" name="nik">
                                            <span style="color: #dc3545; font-size: 0.8rem;" id="error-nik"></span>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3 loader-wni">
                                            <label for="driving_license">No. SIM</label>
                                            <input type="text" class="form-control inputan numberonly" maxlength="16" placeholder="No SIM" value="" id="driving_license" name="driving_license">
                                            <span style="color: #dc3545; font-size: 0.8rem;" id="error-sim"></span>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3 loader-wna">
                                            <label for="no_passport">No Passport <span class="red">*</span></label>
                                            <input type="text" class="form-control inputan numberonly" maxlength="16" placeholder="No Passport" value="" id="no_passport" name="no_passport">
                                            <span style="color: #dc3545; font-size: 0.8rem;" id="error-passport"></span>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="txt_nama_pasien">Nama Pasien <span class="red">*</span></label>
                                            <input type="text" autocomplete="off" class="form-control uppercase inputan required" id="nama" name="nama" placeholder="Nama Pasien" required>
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
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control inputan" placeholder="email" value="" name="email" id="email">
                                            <span style="color: #dc3545; font-size: 0.8rem;" id="error-email"></span>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="tempat_lahir">Tempat Lahir <span class="red">*</span></label>
                                            <input type="text" class="form-control uppercase inputan required" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" autocomplete="off" required>
                                        </div>
                                        <div class="col-12 col-md-4 mb-3">
                                            <label for="tanggal_lahir">Tanggal Lahir <span class="red">*</span></label>
                                            <input type="date" class="form-control inputan required" name="tanggal_lahir" id="tanggal_lahir" required>
                                        </div>
                                        <div class="col-12 col-md-2 mb-3">
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="parent_jenkel">Jenis Kelamin <span class="red">*</span></label>
                                            <div class="row col-md-12 required" id="parent_jenkel">

                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="panggilan">Alias <span class="red">*</span></label>
                                            <select name="panggilan" class="form-control inputan select2 required" id="panggilan" required>
                                                <option value="">Pilih Alias</option>

                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="alamat">Alamat <span class="red">*</span></label>
                                            <textarea class="form-control uppercase inputan required" rows="3" name="alamat" id="alamat"></textarea>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="rt">RT</label>
                                                    <input type="text" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rt" name="alamat_rt" placeholder="000">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="rw">RW</label>
                                                    <input type="text" autocomplete="off" maxlength="3" class="form-control uppercase inputan" name="alamat_rw" id="alamat_rw" placeholder="000">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="alamat_provinsi">Provinsi <span class="red">*</span></label>
                                            <select class="form-control inputan select2 required" name="alamat_provinsi" id="alamat_provinsi" required>
                                                <option value="" disabled selected>Pilih Provinsi</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="alamat_kabupaten">Kabupaten / Kota <span class="red">*</span></label>
                                            <select class="form-control inputan select2 required" name="alamat_kabupaten" id="alamat_kabupaten" required>
                                                <option value="" disabled selected>Pilih Kabupaten / Kota</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="alamat_kecamatan">Kecamatan <span class="red">*</span></label>
                                            <select class="form-control inputan select2 required" name="alamat_kecamatan" id="alamat_kecamatan" required>
                                                <option value="" disabled selected>Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="alamat_kelurahan">Kelurahan <span class="red">*</span></label>
                                            <select class="form-control inputan select2 required" name="alamat_kelurahan" id="alamat_kelurahan" required>
                                                <option value="" disabled selected>Pilih Kelurahan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="nama_ayah">Nama Ayah</label>
                                            <input type="text" class="form-control uppercase inputan" name="nama_ayah" id="nama_ayah" placeholder="Nama Ayah">
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="nama_ibu">Nama Ibu <span class="red">*</span></label>
                                            <input type="text" class="form-control uppercase inputan required" name="nama_ibu" id="nama_ibu" placeholder="Nama Ibu">
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="nama_suami_istri">Nama Suami / Istri</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase inputan" name="nama_suami_istri" id="nama_suami_istri" placeholder="Nama Suami / Istri">
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
                                            <input type="text" autocomplete="off" maxlength="14" class="form-control numberonly inputan required" name="no_telp" id="no_telp" placeholder="08xxxxxxxxxx">
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="goldar">Golongan Darah</label>
                                            <!-- <div class="row col-md-9" id="parent_goldar">

                                            </div> -->
                                            <select class="form-control inputan select2" name="goldar" id="goldar" required>
                                                <option value="" disabled selected>Pilih Golongan Darah</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="agama">Agama <span class="red">*</span></label>
                                            <select class="form-control inputan select2 required" id="agama" name="agama" required>
                                                <option value="" selected disabled>Pilih Agama</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="suku">Suku</label>
                                            <select class="form-control inputan select2" name="suku" id="suku" required="">
                                                <option value="" disabled selected>Pilih Suku</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="pendidikan">Pendidikan</label>
                                            <select class="form-control inputan select2" name="pendidikan" id="pendidikan" required>
                                                <option value="" disabled selected>Pilih Pendidikan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="pekerjaan">Pekerjaan</label>
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
                    </form>
                </div>
                <div class="tab-pane show fade" id="tab-penjamin">
                    <table class="table largeDataType table-bordered" id="penjamin_pasien">
                        <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Penjamin</th>
                                <th>Digunakan sejak</th>
                                <th>Tanggal Berlaku</th>
                                <th>Tanggal Berakhir</th>
                                <th>Bridging</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="tab-pane show fade" id="tab-cppt">
                    <div class="row">
                        <div class="col-lg">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">CPPT</h5>
                                </div>
                                <div class="card-body tab-content">
                                    <div class="tab-pane active show fade" id="resep-biasa">
                                        <div class="row">
                                            <div class="col-md-12">


                                                <div class="card card-form d-flex flex-column flex-sm-row">
                                                    <div class="card-form__body card-body-form-group flex">
                                                        <div class="row">
                                                            <div class="col-sm-auto">
                                                                <div class="form-group">
                                                                    <label for="filter_name">Search</label>
                                                                    <input id="filter_name" type="text" class="form-control" placeholder="Enter keyword">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-auto">
                                                                <div class="form-group">
                                                                    <label for="filter_poli">Poliklinik</label><br>
                                                                    <select id="filter_poli" class="form-control" style="width: 300px;"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-auto">
                                                                <div class="form-group">
                                                                    <label for="filter_dokter">Dokter</label><br>
                                                                    <select id="filter_dokter" class="form-control" style="width: 350px;"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-auto">
                                                                <div class="form-group" style="width: 200px;">
                                                                    <label for="filter_date">Dari - Sampai</label>
                                                                    <input id="filter_date" type="text" class="form-control" placeholder="Select date ..." value="13/03/2018 to 20/03/2018" data-toggle="flatpickr" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary">refresh</i></button>
                                                </div>
                                                <div id="cppt_pagination"></div>
                                                <div id="cppt_loader"></div>
                                                <div class="mt-4">
                                                    <ul class="pagination justify-content-center" id="pagin">

                                                    </ul>
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
