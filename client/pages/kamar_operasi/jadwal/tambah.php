<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item">Kamar Operasi</li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/kamar_operasi/jadwal">Jadwal Operasi</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Jadwal Operasi</li>
				</ol>
			</nav>
			<h4 class="m-0">Tambah Jadwal</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
<form id="form_add_jadwal">
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Informasi Pasien</strong></p>
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<div class="col-12 col-md-12 mb-3">
						<label for="pasien">Pasien <span class="red">*</span></label>
						<select name="pasien" id="pasien" required class="form-control pasien">

                        </select>
                    </div>
                    <div class="col-6 col-md-6 mb-3">
						<label for="no_rm_pasien">No. RM</label>
						<input disabled type="text" id="no_rm_pasien" class="form-control" value="">
					</div>
                    <div class="col-6 col-md-6 mb-3">
						<label for="nik_pasien">NIK</label>
						<input disabled type="text" id="nik_pasien" class="form-control" value="">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Informasi Jadwal Operasi</strong></p>
				<p class="text-muted">Mohon masukkan data dengan benar sesuai dengan KTP<br>* Wajib diisi</p>
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<div class="col-6 col-md-6 mb-3">
                        <label for="jenis_operasi">Jenis Operasi <span class="red">*</span></label>
                        <select required name="jenis_operasi" id="jenis_operasi" class="form-control">
                            <option value="" disabled selected>Pilih Jenis Operasi</option>
                        </select>
					</div>
					<div class="col-6 col-md-6 mb-3">
						<label for="tgl_operasi">Tanggal Operasi <span class="red">*</span></label>
						<input required type="date" class="form-control" placeholder="email" value="" name="tgl_operasi" id="tgl_operasi">
                    </div>
                    <div class="col-6 col-md-6 mb-3">
						<label for="jam_mulai">Jam Mulai Operasi <span class="red">*</span></label>
						<input required type="time" class="form-control" placeholder="" value="" name="jam_mulai" id="jam_mulai">
                    </div>
                    <div class="col-6 col-md-6 mb-3">
						<label for="jam_selesai">Jam Selesai Operasi <span class="red">*</span></label>
						<input required type="time" class="form-control" placeholder="email" value="" name="jam_selesai" id="jam_selesai">
					</div>
                    <div class="col-6 col-md-6 mb-3">
						<label for="ruang_operasi">Ruang Operasi <span class="red">*</span></label>
                        <select required name="ruang_operasi" id="ruang_operasi" class="form-control">
                            <option value="" disabled selected>Pilih Ruangan</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-6 mb-3">
						<label for="dokter">Dokter<span class="red">*</span></label>
						<select required name="dokter" id="dokter" class="form-control">
                            <option value="" disabled selected>Pilih Dokter</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="operasi">Operasi <span class="red">*</span></label>
                        <input required type="text" class="form-control" placeholder="" value="" name="operasi" id="operasi">
						<!-- <textarea required name="operasi" id="operasi" cols="30" rows="2" class="form-control">

                        </textarea> -->
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
						<a href="<?php echo __HOSTNAME__; ?>/kamar_operasi/jadwal" class="btn btn-danger">Batal</a>
				   <!--  </div> -->
				</div>
			</div>
		</div>
	</div>
</form>
</div>
