<p><h4>Pemeriksaan Tanda-tanda Vital</h4></p>
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
				<div class="col-md-6">
                    <div class="col-md-12 row form-group">
                        <div class="col-md-4">
                            <label>Kesadaran</label>
                        </div>
                        <div class="col-md-8">
							<select name="vital_kesadaran" id="vital_kesadaran" class="form-control">
                                <option value="Compos Mentis">Compos Mentis</option>
                                <option value="Delirium">Delirium</option>
                                <option value="Somnolen">Somnolen</option>
                                <option value="Sopor">Sopor</option>
                                <option value="Koma">Koma</option>
                            </select>
                        </div>
					</div>

                    <div class="col-md-12 row form-group">
						<div class="col-md-4">
							<label>Keadaan Umum</label>
						</div>
						<div class="col-md-8">
							<select name="vital_keadaan_umum" id="vital_keadaan_umum" class="form-control">
                                <option value="Baik">Baik</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Kurang">Kurang</option>
                            </select>
						</div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Tekanan Darah</label>
                        </div>
                        <div class="col-md-8 input-group input-group-merge">
                            <input type="text" id="vital_tekanan_darah" name="vital_tekanan_darah" class="form-control form-control-appended inputan" placeholder="000/000">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>mmhg</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Nadi</label>
                        </div>
                        <div class="col-md-8 input-group input-group-merge">
                            <input type="text" id="vital_nadi" name="vital_nadi" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>x/mnt</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Suhu Tubuh</label>
                        </div>
                        <div class="col-md-8 input-group input-group-merge">
                            <input type="text" id="vital_suhu_tubuh" name="vital_suhu_tubuh" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>* C</span>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
                
                <div class="col-md-6">
                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Pernafasan</label>
                        </div>
                        <div class="col-md-8 input-group input-group-merge">
                            <input type="text" id="vital_pernafasan" name="vital_pernafasan" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>x/mnt</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Berat Badan</label>
                        </div>
                        <div class="col-md-8 input-group input-group-merge">
                            <input type="text" id="vital_berat_badan" name="vital_berat_badan" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>kg</span>
                                </div>
                            </div>
                        </div>
					</div>

                    <div class="col-lg-12 row form-group">
                        <div class="col-md-4">
                            <label for="">Tinggi Badan</label>
                        </div>
                        <div class="col-md-8 input-group input-group-merge">
                            <input type="text" id="vital_tinggi_badan" name="vital_tinggi_badan" class="form-control form-control-appended inputan" placeholder="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>cm</span>
                                </div>
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
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Alergi/ Reaksi</h5>
			</div>
            <div class="card-body">
                <div class="col-md-12 row form-group">
                    <div class="col-md-4">
                        <label>Alergi</label>
                    </div>
                    <div class="row col-md-8">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="vital_status_alergi" value="1" id="vital_status_alergi_1">
                                <label class='form-check-label' for="vital_status_alergi_1">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="vital_status_alergi" value="0" id="vital_status_alergi_0">
                                <label class='form-check-label' for="vital_status_alergi_0">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


			<div class="card-header card-header-tabs-basic nav tab_alergi" role="tablist" hidden>
				<a href="#vital-alergi-obat" class="active" data-toggle="tab" role="tab" aria-controls="vital-alergi-obat" aria-selected="true">Alergi Obat</a>
				<a href="#vital-alergi-makanan" data-toggle="tab" role="tab" aria-selected="false">Alergi Makanan</a>
                <a href="#vital-alergi-lainnya" data-toggle="tab" role="tab" aria-selected="false">Alergi Lainnya</a>
			</div>
			<div class="card-body tab-content tab_alergi" hidden>
				<div class="tab-pane active show fade" id="vital-alergi-obat">
					<div id="txt_vital_alergi_obat" class="txt_vital_alergi_obat"></div>
				</div>
				<div class="tab-pane show fade" id="vital-alergi-makanan">
					<div id="txt_vital_alergi_makanan" class="txt_vital_alergi_makanan"></div>
				</div>
                <div class="tab-pane show fade" id="vital-alergi-lainnya">
					<div id="txt_vital_alergi_lainnya" class="txt_vital_alergi_lainnya"></div>
				</div>
			</div>

            <div class="card-body">
                <div class="col-md-12 form-group">
                    <label for="">Diberitahukan ke Dokter/ Farmasi (apoteker)/ Dietisen:</label>
                    <div class="row col-md-12">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="vital_alergi_jam_ke_dokter_apoteker_dietisen" value="0" id="vital_alergi_jam_ke_dokter_apoteker_dietisen_0">
                                <label class='form-check-label' for="vital_alergi_jam_ke_dokter_apoteker_dietisen_0">Tidak</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="vital_alergi_jam_ke_dokter_apoteker_dietisen" value="1" id="vital_alergi_jam_ke_dokter_apoteker_dietisen_1">
                                <label class='form-check-label' for="vital_alergi_jam_ke_dokter_apoteker_dietisen_1">Ya, Jam : </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input disabled type="time" name="vital_alergi_jam_ke_dokter_apoteker_dietisen" id="vital_alergi_jam_ke_dokter_apoteker_dietisen" class="form-control vital_alergi_jam_ke_dokter_apoteker_dietisen">
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
				<h5 class="card-header__title flex m-0">Skrinning Gizi</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Parameter</th>
                                <th width="30%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?
                                </td>
                                <td>
                                    <select name="vital_gizi_turun_bb" id="vital_gizi_turun_bb" class="form-control table_vital_gizi">
                                        <option value="0">Tidak Ada Penurunan Berat Badan (0)</option>
                                        <option value="2">Tidak Yakin/ Tidak tahu/ Terasa baju lebih longgar (2)</option>
                                        <option value="1">Penurunan 1-5 kg  (1)</option>
                                        <option value="2">Penurunan 6-10 kg (2)</option>
                                        <option value="3">Penurunan 11-15 kg  (3)</option>
                                        <option value="4">Penurunan > 15 kg (4)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>
                                    Apakah asupan makan berkurang karena tidak nafsu makan?
                                </td>
                                <td>
                                    <select name="kulit_persepsi_sensori" id="kulit_persepsi_sensori" class="form-control table_kaji_kulit">
                                        <option value="0">Tidak (0)</option>
                                        <option value="1">Ya (1)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>
                                    <p>Pasien dengan diagnosa khusus</p>
                                    <p style="font-size:10pt;">(fraktur tulang panggul, sirosis hati, PPOK, hemodialisis, diabetes, kanker, bedah, digestive, stroke, pneunomia berat, cedera kepala, transplantasi, luka bakar, usia lanjut, psikiatri, mendapat kemoterapi atau radiasi, imunisasi rendah/ HIV-AIDS, penyakit kronis lain)</p>
                                </td>
                                <td>
                                    <select name="kulit_persepsi_sensori" id="kulit_persepsi_sensori" class="form-control table_kaji_kulit">
                                        <option value="0">Tidak (0)</option>
                                        <option value="1">Ya (1)</option>
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
                <br />
                <div class="col-md-12 row form-group">
                    <div class="col-md-6">
                        <label>Terlampir formulir pemeriksaan kelompok khusus</label>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_lampiran_formulir_pemeriksaan" value="1" id="kulit_lampiran_formulir_pemeriksaan_1">
                                <label class='form-check-label' for="kulit_lampiran_formulir_pemeriksaan_1">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="kulit_lampiran_formulir_pemeriksaan" value="0" id="kulit_lampiran_formulir_pemeriksaan_0">
                                <label class='form-check-label' for="kulit_lampiran_formulir_pemeriksaan_0">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>