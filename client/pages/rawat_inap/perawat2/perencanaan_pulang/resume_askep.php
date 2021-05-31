<p><h4>Resume Asuhan Keperawatan</h4></p>
<br />


<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"><!-- <i class="material-icons mr-3">info_outline</i> --> Informasi Pasien</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
					<table class="table">
						<tbody>
							<tr>
								<td>No. Rekam Medis</td>
								<td> : </td>
								<td><b><span class="no_rm"></span></b></td>
								<td>Tanggal Lahir</td>
								<td> : </td>
								<td><b><span class="tanggal_lahir"></span></b></td>
							</tr>
							<tr>
								<td>Nama Pasien</td>
								<td> : </td>
								<td><b><span class="panggilan"></span> <span class="nama"></span> </b></td>
								<td>Jenis Kelamin</td>
								<td> : </td>
								<td><b><span class="jenkel"></span></b></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Subjektif</h5>
			</div>
			<div class="card-body row">
				<div class="col-md-8">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-5">
                            <label>Ruang / Kelas</label>
                        </div>
                        <div class="col-md-7">
                            <input disabled type="text" class="form-control" id="resume_askep_ruang_kelas">
                        </div>
					</div>
					<div class="col-md-12 row form-group">
                        <div class="col-md-5">
                            <label>Tanggal Masuk</label>
                        </div>
                        <div class="col-md-7">
                            <input disabled type="text" class="form-control" id="resume_askep_tanggal_masuk"> 
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-5">
                            <label>Tanggal Keluar</label>
                        </div>
                        <div class="col-md-7">
                            <input disabled type="text" class="form-control" id="resume_askep_tanggal_keluar"> 
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-5">
                            <label>Pasien dirujuk ke</label>
                        </div>
                        <div class="col-md-7">
                            <select name="resume_askep_dirujuk_ke" id="resume_askep_dirujuk_ke" class="form-control">
                                <option value="">Pilih</option>
                                <option value="Dokter pribadi">Dokter pribadi</option>
                                <option value="RS lain">RS lain</option>
                                <option value="Lainnya">Lainnya</option>
                            </select> 
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-5">
                            <label>Status pulang</label>
                        </div>
                        <div class="col-md-7">
                            <select name="resume_askep_status_pulang" id="resume_askep_status_pulang" class="form-control">
                                <option value="">Pilih</option>
                                <option value="Atas ijin Dokter">Atas ijin Dokter</option>
                                <option value="Melarikan diri">Melarikan diri</option>
                                <option value="Pulang atas permintaan sendiri">Pulang atas permintaan sendiri</option>
                                <option value="Meninggal">Meninggal</option>
                            </select> 
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-5">
                            <label>Materi penyuluhan tentang</label>
                        </div>
                        <div class="col-md-7">
                            <select name="resume_askep_materi_penyuluhan" id="resume_askep_materi_penyuluhan" class="form-control">
                                <option value="">Pilih</option>
                                <option value="Waktu kontrol">Waktu kontrol</option>
                                <option value="Diet">Diet</option>
                                <option value="Cara minum obat">Cara minum obat</option>
                                <option value="Pola hidup">Pola hidup</option>
                            </select> 
                        </div>
					</div>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Obat - obatan yang dibawa pulang</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <div id="resume_askep_obat_dibawa_pulang" class="resume_askep_obat_dibawa_pulang"></div>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-lg">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Catatan</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12"> 
                    <div id="resume_askep_catatan" class="resume_askep_catatan"></div>
                </div>
			</div>
		</div>
	</div>
</div>