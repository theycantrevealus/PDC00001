<p><h4>Pemberian Terapi Cairan/Infus</h4></p>
    
<div style="text-align: right;"><button class="btn btn-primary">List Pemberian Infus</button></div>
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
				<div class="col-md-12">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-4">
                            <label>Jenis Cairan</label>
                        </div>
                        <div class="col-md-4">
							<select name="infus_jenis_cairan" id="infus_jenis_cairan" class="form-control">
                                <option value="">Pilih</option>
                            </select>
                        </div>
					</div>

                    <div class="col-md-12 row form-group">
						<div class="col-md-4">
							<label>Jalur Selang</label>
						</div>
						<div class="col-md-4">
							<select name="infus_jalur_selang" id="infus_jalur_selang" class="form-control">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
						</div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Volume</label>
                        </div>
                        <div class="col-md-4 input-group input-group-merge">
                            <input type="text" id="infus_volume" name="infus_volume" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>ml</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Tetesan</label>
                        </div>
                        <div class="col-md-4 input-group input-group-merge">
                            <input type="text" id="infus_tetesan" name="infus_tetesan" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>tetesan/menit</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Lama Terapi Cairan/Infus diberikan</label>
                        </div>
                        <div class="col-md-4 input-group input-group-merge">
                            <input type="text" id="infus_lama_waktu_diberikan" name="infus_lama_waktu_diberikan" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>hari</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Keterangan</label>
                        </div>
                        <div class="col-md-4 ">
                            <textarea class="form-control" name="infus_keterangan" id="infus_keterangan" rows="5"></textarea>
                        </div>
					</div>
                </div>

			</div>
		</div>
	</div>
</div>
