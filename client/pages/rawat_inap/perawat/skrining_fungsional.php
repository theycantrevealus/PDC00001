<p><h4>Skrinning Status Fungsional</h4></p>
<p><i><h6>Isilah dan lengkapilah penilaian Barthel Index dan tentukan tingkat ketergantungan pasien berdasarkan skor</h6></i></p>
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
			<div class="card-body">
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="skrining_subjektif_nilai" value="20" id="skrining_subjektif_nilai_1">
                            <label class='form-check-label' for="skrining_subjektif_nilai_1">Mandiri</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="skrining_subjektif_nilai" value="16" id="skrining_subjektif_nilai_2">
                            <label class='form-check-label' for="skrining_subjektif_nilai_2">Perlu bantuan ringan</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="skrining_subjektif_nilai" value="10" id="skrining_subjektif_nilai_3">
                            <label class='form-check-label' for="skrining_subjektif_nilai_3">Perlu bantuan sedang</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="skrining_subjektif_nilai" value="7" id="skrining_subjektif_nilai_4">
                            <label class='form-check-label' for="skrining_subjektif_nilai_4">Perlu bantuan berat</label>
                        </div>
                    </div>
                </div>
                <hr />

                <div class="col-md-12 form-group">
                    <label for="">Ketergantungan total, dilaporkan ke dokter</label>
                    <div class="row col-md-12">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="skrining_tergantung_total" value="0" id="skrining_tergantung_total_0">
                                <label class='form-check-label' for="skrining_tergantung_total_0">Tidak</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="skrining_tergantung_total" value="1" id="skrining_tergantung_total_1">
                                <label class='form-check-label' for="skrining_tergantung_total_1">Ya, Pukul : </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input disabled type="time" name="skrining_tergantung_total_ke_dokter" id="skrining_tergantung_total_ke_dokter" class="form-control skrining_tergantung_total_ke_dokter">
                        </div>
                    </div>
                </div>
                
                <hr />

                <div class="col-md-12">
                    <h6><b>BARTHEL INDEKS</b></h6>
                    <br />
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Indikator</th>
                                <th width="40%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    Mengendalikan rangsangan buang air besar (BAB)
                                </td>
                                <td>
                                    <select name="skrining_kendali_bab_skor" id="skrining_kendali_bab_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tidak terkendali/ tidak teratur / perlu pencahar (0)</option>
                                        <option value="1">kadang-kadang tidak terkendali / satukali per-minggu (1)</option>
                                        <option value="2">mandiri/ mampu mengendalikan (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>
                                    Mengendalikan rangsangan buang air kecil (BAK)
                                </td>
                                <td>
                                    <select name="skrining_kendali_bak_skor" id="skrining_kendali_bak_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tidak terkendali atau pakai kateter dan tidak mampu mengendalikan (0)</option>
                                        <option value="1">kadang-kadang tidak terkendali / 1x dalam 24 jam (1)</option>
                                        <option value="2">mandiri (2)</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>3</td>
                                <td>
                                    Membersihkan diri (cuci muka, sisir rambut, sikat gigi)
                                </td>
                                <td>
                                    <select name="skrining_bersih_diri_skor" id="skrining_bersih_diri_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">butuh bantuan orang lain (0)</option>
                                        <option value="1">mandiri (1)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>
                                   Penggunaan toilet masuk dan keluar (melepas dan memakai celana, membersihkan dan menyiram)
                                </td>
                                <td>
                                    <select name="skrining_penggunaan_toilet_skor" id="skrining_penggunaan_toilet_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tergantung pertolongan orang lain / perlu pertolongan pada beberapa (0)</option>
                                        <option value="1">kegiatan tetapi dapat mengerjakan sendiri kegiatan yang lain (1)</option>
                                        <option value="2">mandiri / masuk dan keluar, berpakaian, dan membersihkan diri (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>
                                   Makan
                                </td>
                                <td>
                                    <select name="skrining_makan_skor" id="skrining_makan_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tidak mampu (0)</option>
                                        <option value="1">perlu ditolong memotong makanan (1)</option>
                                        <option value="2">mandiri (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td>
                                   Berubah sikap dari berbaring ke duduk
                                </td>
                                <td>
                                    <select name="skrining_ubah_sikap_skor" id="skrining_ubah_sikap_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tidak mampu duduk seimbang (0)</option>
                                        <option value="1">perlu banyak bantuan untuk bisa duduk / dibantu 2 orang (1)</option>
                                        <option value="2">bantuan sedikit / bantuan verbal dan fisik (2)</option>
                                        <option value="3">mandiri (3)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>7</td>
                                <td>
                                   Berpindah/ berjalan
                                </td>
                                <td>
                                    <select name="skrining_pindah_jalan_skor" id="skrining_pindah_jalan_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tidak mampu duduk seimbang (0)</option>
                                        <option value="1">bisa / pindah dengan kursi roda (1)</option>
                                        <option value="2">berjalan dengan bantuan 1 orang (2)</option>
                                        <option value="3">mandiri (3)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>
                                   Memakai baju
                                </td>
                                <td>
                                    <select name="skrining_pakai_baju_skor" id="skrining_pakai_baju_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tergantung orang lain (0)</option>
                                        <option value="1">sebagian dibantu misal: mengancing baju (1)</option>
                                        <option value="2">mandiri (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>9</td>
                                <td>
                                    Naik turun tangga
                                </td>
                                <td>
                                    <select name="skrining_naik_turun_tangga_skor" id="skrining_naik_turun_tangga_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tidak mampu (0)</option>
                                        <option value="1">butuh pertolongan (1)</option>
                                        <option value="2">mandiri (2)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>
                                    Mandi
                                </td>
                                <td>
                                    <select name="skrining_naik_turun_tangga_skor" id="skrining_naik_turun_tangga_skor" class="form-control table_skrining_barthiel_index">
                                        <option value="0">tergantung orang lain (0)</option>
                                        <option value="1">mandiri (1)</option>
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