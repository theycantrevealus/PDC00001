<p><h4>Pengkajian Nyeri</h4></p>
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
				<h5 class="card-header__title flex m-0">Subjektif</h5>
			</div>
			<div class="card-body row">
				<div class="col-md-12 row">
					<div class="col-md-6 row form-group">
                        <div class="col-md-5">
                            <label>Tanggal Pengkajian</label>
                        </div>
                        <div class="col-md-7">
                            <input type="date" class="form-control" id="kaji_nyeri_tgl_pengkajian"> 
                        </div>
					</div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-5 row form-group">
                        <div class="col-md-4">
                            <label>Pukul</label>
                        </div>
                        <div class="col-md-8">
                            <input type="time" class="form-control" id="kaji_nyeri_pukul_pengkajian"> 
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-2">
                            <label>Diagnosa</label>
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-12">
                            <div id="kaji_nyeri_diagnosa" class="kaji_nyeri_diagnosa">
                            
                            </div>
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
				<h5 class="card-header__title flex m-0">Skala Nilai sesuai dengan usia pasien</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Skala Nyeri Cries</th>
                                <th width="60%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <p>Menangis</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_menangis" id="kaji_nyeri_menangis" class="form-control table_kaji_nyeri_skala_nyeri">
                                        <option value="0">Tidak menangis atau menangis tanpa intonasi tinggi (melengking) (0)</option>
                                        <option value="1">Menangis dengan intonasi tinggi namun bayi mudah ditenangkan (1)</option>
                                        <option value="2">Menangis dengan intonasi tinggi yang tidak dapat ditenangkan (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>
                                    <p>Kebutuhan O2 untuk SaO2 < 95%</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_kebutuhan_o2" id="kaji_nyeri_kebutuhan_o2" class="form-control table_kaji_nyeri_skala_nyeri">
                                        <option value="0">Tidak memerlukan oksigen (0)</option>
                                        <option value="1">Oksigen yang diperlukan < 30% (1)</option>
                                        <option value="2">Oksigen yang diperlukan > 30% (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>
                                    <p>Peningkatan tanda-tanda vital Bp dan HR</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_peningkatan_tanda_vital" id="kaji_nyeri_peningkatan_tanda_vital" class="form-control table_kaji_nyeri_skala_nyeri">
                                        <option value="0">Baik nadi dan tekanan darah tak berubah atau dibawah nilai normal (0)</option>
                                        <option value="1">Nadi atau tekanan darah meningkat namun masih dibawah < 20% nilai dasar (1)</option>
                                        <option value="2">Nadi atau tekanan darah meningkat di atas > 20% nilai dasar (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>
                                    <p>Ekspresi Wajah</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_ekspresi_wajah" id="kaji_nyeri_ekspresi_wajah" class="form-control table_kaji_nyeri_skala_nyeri">
                                        <option value="0">Tidak ada ekspresi wajah menangis (0)</option>
                                        <option value="1">Wajah meringis (1)</option>
                                        <option value="2">Wajah meringis, menangis tanpa bersuara (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>
                                    <p>Tidur</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_tidur" id="kaji_nyeri_tidur" class="form-control table_kaji_nyeri_skala_nyeri">
                                        <option value="0">Bayi selama ini tidur nyenyak (0)</option>
                                        <option value="1">Bayi terkadang terbangun (1)</option>
                                        <option value="2">Bayi seringkali terbangun (2)</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <td colspan="2"><b>TOTAL SKOR</b></td>
                                <td></td>
                            </tr>
                        </tfoot>
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
				<h5 class="card-header__title flex m-0">Skala Flacc untuk anak usia < 3 tahun</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Skala Flacc</th>
                                <th width="60%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <p>Wajah</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_skala_flacc_wajah" id="kaji_nyeri_skala_flacc_wajah" class="form-control table_kaji_nyeri_skala_flacc">
                                        <option value="0">Ekspresi wajah normal (0)</option>
                                        <option value="1">Ekspresi wajah, kadang meringis menahan sakit (1)</option>
                                        <option value="2">Sering meringis, menggertakkan giti menahan sakit (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>
                                    <p>Kaki</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_skala_flacc_kaki" id="kaji_nyeri_skala_flacc_kaki" class="form-control table_kaji_nyeri_skala_flacc">
                                        <option value="0">Posisi kaki normal atau rileks (0)</option>
                                        <option value="1">KAki kaku, gelisah (1)</option>
                                        <option value="2">Kaki menendang-nendang (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>
                                    <p>Aktivitas</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_skala_flacc_aktifitas" id="kaji_nyeri_skala_flacc_aktifitas" class="form-control table_kaji_nyeri_skala_flacc">
                                        <option value="0">Berbaring tenang, posisi normal, gerakan normal (0)</option>
                                        <option value="1">Gelisah, berguling-guling (1)</option>
                                        <option value="2">Kaku, gerakan abnormal (posisi tubuh melengkung atau gerakan menyentak) (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>
                                    <p>Menangis</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_skala_flacc_menangis" id="kaji_nyeri_skala_flacc_menangis" class="form-control table_kaji_nyeri_skala_flacc">
                                        <option value="0">Tidak menangis (0)</option>
                                        <option value="1">Mengerang atau merengek, kadang-kadang mengeluh (1)</option>
                                        <option value="2">Menangis terus menerus, menjerit, sering kali mengeluh (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>
                                    <p>Bicara atau bersuara</p>
                                </td>
                                <td>
                                    <select name="kaji_nyeri_skala_flacc_bicara" id="kaji_nyeri_skala_flacc_bicara" class="form-control table_kaji_nyeri_skala_flacc">
                                        <option value="0">Bicar atau bersuara normal, sesuai usia (0)</option>
                                        <option value="1">Tenang setelah dipegang, dipeluk, digendong atau diajak bicara (1)</option>
                                        <option value="2">Sulit ditenangkan dengan kata-kata atau pelukan (2)</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <td colspan="2"><b>TOTAL SKOR</b></td>
                                <td></td>
                            </tr>
                        </tfoot>
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
                        <div class="col-md-3">
                            <label>Deskripsi Nyeri</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="kaji_nyeri_deskripsi_nyeri"> 
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-3">
                            <label>Frekuensi Nyeri</label>
                        </div>
                        <div class="row col-md-9">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_nyeri_frekuensi_nyeri" value="Jarang" id="kaji_nyeri_frekuensi_nyeri_jarang">
                                    <label class='form-check-label' for="kaji_nyeri_frekuensi_nyeri_jarang">Jarang</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_nyeri_frekuensi_nyeri" value="Hilang Timbul" id="kaji_nyeri_frekuensi_nyeri_hilang_timbul">
                                    <label class='form-check-label' for="kaji_nyeri_frekuensi_nyeri_hilang_timbul">Hilang Timbul</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_nyeri_frekuensi_nyeri" value="Terus-menerus" id="kaji_nyeri_frekuensi_nyeri_terus_menerus">
                                    <label class='form-check-label' for="kaji_nyeri_frekuensi_nyeri_terus_menerus">Terus-menerus</label>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="col-md-12 row form-group">
                        <div class="col-md-3">
                            <label>Lama Nyeri</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="kaji_nyeri_lama_nyeri"> 
                        </div>
					</div>
                    
                    <div class="col-md-12 row form-group">
                        <div class="col-md-3">
                            <label>Tindakan Lanjut</label>
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="kaji_nyeri_tindakan_lanjut_edukasi" value="Edukasi" id="kaji_nyeri_tindakan_lanjut_edukasi">
                                        <label for="kaji_nyeri_tindakan_lanjut_edukasi">Edukasi</label>
                                    </div>
                                </div>
                            </div> 
                            <br />
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                    <div class="form-check">
                                        <input type="checkbox" onclick='disableCheckboxChild(this, "kaji_nyeri_tindakan_lanjut_intervensi_obat_ket")' class="form-check-input" name="kaji_nyeri_tindakan_lanjut_intervensi_obat" value="1" id="kaji_nyeri_tindakan_lanjut_intervensi_obat">
                                        <label for="kaji_nyeri_tindakan_lanjut_intervensi_obat">Intervensi dengan Obat</label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input disabled type="text" name="kaji_nyeri_tindakan_lanjut_intervensi_obat_ket" id="kaji_nyeri_tindakan_lanjut_intervensi_obat_ket" class="form-control inputan kaji_nyeri_tindakan_lanjut_intervensi_obat_ket" placeholder="-">
                                </div>
                            </div>
                            <br />
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                    <div class="form-check">
                                        <input type="checkbox" onclick='disableCheckboxChild(this, "kaji_nyeri_tindakan_lanjut_lainnya_ket")' class="form-check-input" name="kaji_nyeri_tindakan_lanjut_lainnya" value="1" id="kaji_nyeri_tindakan_lanjut_lainnya">
                                        <label for="kaji_nyeri_tindakan_lanjut_lainnya">Lain-lainnya</label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input disabled type="text" name="kaji_nyeri_tindakan_lanjut_lainnya_ket" id="kaji_nyeri_tindakan_lanjut_lainnya_ket" class="form-control inputan kaji_nyeri_tindakan_lanjut_lainnya_ket" placeholder="-">
                                </div>
                            </div> 
                        </div>
					</div>

                    <div class="col-md-12 row form-group">
                        <div class="col-md-3">
                            <label>Evaluasi</label>
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-12 row form-group">
                                <div class="col-md-4">
                                    <label>Jam</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="time" class="form-control" id="kaji_nyeri_evaluasi_jam"> 
                                </div>
                            </div> 
                            <div class="col-md-12 row form-group">
                                <div class="col-md-4">
                                    <label>Skor Nyeri</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="kaji_nyeri_evaluasi_skor"> 
                                </div>
                            </div> 
                        </div>
					</div>
                </div>
			</div>
		</div>
	</div>
</div>
