<p><h4>Asesmen Awal Case Manager</h4></p>
<!-- <p><i><h6>(wajib dilengkapi dalam 24 jam pertama pasien masuk ruang rawat)</h6></i></p> -->
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
				<h5 class="card-header__title flex m-0">1. Skrining awal dan asesmen untuk manajemen pelayanan pasien</h5>
			</div>
			<div class="card-body row">
				<div class="col-md-10">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-4">
                            <label>Usia diatas 65 tahun</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_diatas_65" id="case_manager_diatas_65" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-md-12 row form-group">
						<div class="col-md-4">
							<label>Pendidikan</label>
						</div>
						<div class="col-md-8">
							<select name="case_manager_pendidikan" id="case_manager_pendidikan" class="form-control">
                                <option value="1">Sekolah</option>
                                <option value="0">Tidak Sekolah</option>
                            </select>
						</div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Kendala Bahasa</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_kendala_bahasa" id="case_manager_kendala_bahasa" class="form-control">
                                <option value="0">Tidak Ada</option>
                                <option value="1">Ada</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Pasien Resiko Tinggi</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_pasien_resiko_tinggi" id="case_manager_pasien_resiko_tinggi" class="form-control">
                                <option value="">Pilih</option>
                                <option value="DM Tidak terkontrol">DM Tidak terkontrol</option>
                                <option value="HT Tidak terkontrol">HT Tidak terkontrol</option>
                                <option value="Jauh dari faskes">Jauh dari faskes</option>
                                <option value="Dirumah tidak ada yang menunggu">Dirumah tidak ada yang menunggu</option>
                                <option value="Gangguan penglihatan dan pandangan">Gangguan penglihatan dan pandangan</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Potensi Komplain Tinggi</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_potensi_komplain_tinggi" id="case_manager_potensi_komplain_tinggi" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Pasien Penyakit Kronik</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_pasien_resiko_tinggi" id="case_manager_pasien_resiko_tinggi" class="form-control">
                                <option value="">Pilih</option>
                                <option value="CKD dgn HD">CKD dgn HD</option>
                                <option value="Terminal">Terminal</option>
                                <option value="DM yang membutuhkan obat terus menerus">DM yang membutuhkan obat terus menerus</option>
                                <option value="Cancer dengan kemoterapi">Cancer dengan kemoterapi</option>
                                <option value="Serangan jantung">Serangan jantung</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Status Fungsional</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_status_fungsional" id="case_manager_status_fungsional" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Pasien dengan riwayat penggunaan alat medis masa lalu</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_riwayat_penggunaan_alat_medis" id="case_manager_riwayat_penggunaan_alat_medis" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Riwayat gangguan mental</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_riwayat_gangguan_mental" id="case_manager_riwayat_gangguan_mental" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Readmisi</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_readmisi" id="case_manager_readmisi" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Biaya Tinggi</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_biaya_tinggi" id="case_manager_biaya_tinggi" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Masalah Finansial</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_readmisi" id="case_manager_readmisi" class="form-control">
                                <option value="Asuransi Bermasalah">Asuransi Bermasalah</option>
                                <option value="Tidak Ada Asuransi">Tidak Ada Asuransi</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Hari Rawat Panjang</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_hari_rawat_panjang" id="case_manager_hari_rawat_panjang" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Rencana Pemulangan Beresiko</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_pemulangan_beresiko" id="case_manager_pemulangan_beresiko" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
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
				<h5 class="card-header__title flex m-0">2. Identifikasi Masalah dan Kesempatan</h5>
			</div>
			<div class="card-body row">
				<div class="col-md-12">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-4">
                            <label>Pilih Identifikasi Masalah dan Kesempatan</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_identifikasi_masalah" id="case_manager_identifikasi_masalah" class="form-control">
                                <option value="1">Pasien dan keluarga belum mengerti tentang penyakit pasien dan tatalaksana yang akan dilakukan</option>
                                <option value="2">Ketidakpatuhan pasien</option>
                                <option value="3">Kurangnya dukungan keluarga</option>
                                <option value="4">Tingkat keparahan/ komplikasi meningkat</option>
                                <option value="5">Pemulangan: tempat jauh, butuh rehabilitasi</option>
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
				<h5 class="card-header__title flex m-0">3. Perencanaan Manajemen</h5>
			</div>
			<div class="card-body row">
				<div class="col-md-12">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-4">
                            <label>Pilih Perencanaan Manajemen</label>
                        </div>
                        <div class="col-md-8">
                            <select name="case_manager_rencana_manajemen" id="case_manager_rencana_manajemen" class="form-control">
                                <option value="1">Case manajer berkolaborasi dengan PPA</option>
                                <option value="2">Case manajer menyarankan kepada PPA untuk penatalaksanaan sesuai dengan standar BPJS</option>
                                <option value="3">Case manajer memberikan edukasi dan advokasi kepada keluarga untuk dapat dengan percaya diri mengambil keputusan</option>
                            </select>
                        </div>
					</div>
                    
                </div>
				
			</div>
		</div>
	</div>
</div>