<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"><i class="material-icons mr-3">info_outline</i> Informasi Pasien</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
					<table class="table">
						<tbody>
							<tr>
								<td>No. Rekam Medis</td>
								<td> : </td>
								<td><span id="no_rm"><b>11-11-11</b></span></td>
								<td>Tanggal Lahir</td>
								<td> : </td>
								<td><span id="tanggal_lahir"><b>22 Januari 1998</b></span></td>
							</tr>
							<tr>
								<td>Nama Pasien</td>
								<td> : </td>
								<td><span id="panggilan"><b>Tn.</b></span> <span id="nama"><b>John Doe</b></span></td>
								<td>Jenis Kelamin</td>
								<td> : </td>
								<td><span id="jenkel"><b>Laki-laki</b></span></td>
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
				<h5 class="card-header__title flex m-0">Informasi Pendaftaran</h5>
			</div>
			<div class="card-body ">
				<div class="row">
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Pendaftaran</label>
						</div>
						<div class="col-md-8">
							<input type="" name="" id="waktu_masuk" disabled class="form-control" value="12 Juli 2020 10:31:36">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Cara Pembayaran</label>
						</div>
						<div class="col-md-8">
							<input type="" name="" id="penjamin" disabled class="form-control" value="BPJS KESEHATAN">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Penanggung Jawab Pasien</label>
						</div>
						<div class="col-md-8">
							<input type="" name="pj_pasien" id="pj_pasien" class="form-control inputan" value="">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Informasi di Dapat Dari</label>
						</div>
						<div class="col-md-8">
							<input type="" name="info_dari" id="info_dari"  class="form-control" value="">
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top: 20px;">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Pengkajian Keperawatan</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-md-12 row">
								 <div class="form-group col-md-6">
									<label for="kesadaran">Kesadaran :</label>
									<select class="form-control inputan" id="kesadaran" name="kesadaran">
										<option value="">Pilih</option>
										<option value="Compos Mentis">Compos Mentis</option>
										<option value="Apatis">Apatis</option>
									</select>
			                    </div>
			                    <div class="form-group col-md-6">
									<label for="sikap_tubuh">Sikap Tubuh :</label>
									<select class="form-control inputan" id="sikap_tubuh" name="sikap_tubuh">
										<option value="">Pilih</option>
										<option value="Normal">Normal</option>
										<option value="Lordosis">Lordosis</option>
										<option value="Kifosis">Kifosis</option>
										<option value="Skoliosis">Skoliosis</option>
										<option value="Cacat">Cacat</option>
									</select>
								</div>
							</div>
							<!-- <div class="form-group col-lg-12">
								 	<label for="sikap_tubuh">Sikap Tubuh :</label>
									<select class="form-control" id="sikap_tubuh">
										<option value="">Normal</option>
										<option value="">Lordosis</option>
									</select>

									<div class="row col-md-12" id="sikap_tubuh">
										<div class="col-md-2">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="sikap_tubuh" value="sikap_tubuh_1" id="sikap_tubuh_1">
												<label class='custom-control-label' for="sikap_tubuh_1">Normal</label>
											</div>
										</div>
										&nbsp; &nbsp;&nbsp;&nbsp;
										<div class="col-md-2">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="sikap_tubuh" value="sikap_tubuh_2" id="sikap_tubuh_2">
												<label class='custom-control-label' for="sikap_tubuh_2">Lordosis</label>
											</div>
										</div>
										&nbsp; &nbsp;&nbsp;&nbsp;
										<div class="col-md-2">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="sikap_tubuh" value="sikap_tubuh_3" id="sikap_tubuh_3">
												<label class='custom-control-label' for="sikap_tubuh_3">Kifosis</label>
											</div>
										</div>
										&nbsp; &nbsp;&nbsp;&nbsp;
										<div class="col-md-2">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="sikap_tubuh" value="sikap_tubuh_4" id="sikap_tubuh_4">
												<label class='custom-control-label' for="sikap_tubuh_4">Skoliosis</label>
											</div>
										</div>
										&nbsp; &nbsp;&nbsp;&nbsp;
										<div class="col-md-2">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="sikap_tubuh" value="sikap_tubuh_5" id="sikap_tubuh_5">
												<label class='custom-control-label' for="sikap_tubuh_5">Cacat</label>
											</div>
										</div>
									</div> 
			                    </div>
							</div>-->
							<div class="form-group col-lg-12">
								 <div class="col-12 col-md-12 mb-3">
									<label for="cara_masuk">Cara Masuk :</label>
									<div class="row col-md-12" id="cara_masuk">
										<div class="col-md-3">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="cara_masuk" value="Jalan" id="cara_masuk_1">
												<label class='custom-control-label' for="cara_masuk_1">Jalan</label>
											</div>
										</div>
										<div class="col-md-3">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="cara_masuk" value="Kursi Roda" id="cara_masuk_2">
												<label class='custom-control-label' for="cara_masuk_2">Kursi Roda</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="cara_masuk" value="Lainnya" id="cara_masuk_3">
												<label class='custom-control-label' for="cara_masuk_3">Lainnya:</label>
												<input type="text" class="form-control" name="cara_masuk_lainnya">
											</div>
										</div>
									</div>
			                    </div>
							</div>
							<div class="form-group col-lg-12">
								 <div class="col-12 col-md-12 mb-3">
									<label for="rujukan">Rujukan :</label>
									<div class="row col-md-12" id="rujukan">
										<div class="col-md-3">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="rujukan" value="0" id="rujukan_1">
												<label class='custom-control-label' for="rujukan_1">Tidak</label>
											</div>
										</div>
										<div class="col-md-9 row">
											<div class="custom-control custom-radio col-md-1">
												<input type="radio" class="custom-control-input" name="rujukan" value="1" id="rujukan_2">
												<label class='custom-control-label' for="rujukan_2">Ya</label>
											</div>
											<div class="col-md-10">
												<input type="text" class="form-control inputan" id="rujukan_ket">
											</div>
										</div>
									</div>
			                    </div>
							</div>
						</div>
						<div class="col-md-12 row">
							<div class="form-group col-lg-12">
								<label for="diagnosa_rujukan">Diagnosa Rujukan: </label>
								<input type="text" name="rujukan_diagnosa" id="rujukan_diagnosa" class="form-control inputan">
							</div>
						</div>
						<br/>
						<div class="row col-md-12">
							<div class="form-group col-lg-6">
								<label for="txt_tekanan_darah">Berat Badan</label>
								<div class="input-group input-group-merge">
									<input type="text" id="berat_badan" name="berat_badan" class="form-control form-control-appended inputan" required="" placeholder="Berat Badan">
									<div class="input-group-append">
										<div class="input-group-text">
											<span>kg</span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-lg-6">
								<label for="txt_tekanan_darah">Tinggi Badan</label>
								<div class="input-group input-group-merge">
									<input type="text" id="tinggi_badan"  name="tinggi_badan" class="form-control form-control-appended inputan" required="" placeholder="Tinggi Badan">
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
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Tanda-tanda Vital</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group col-lg-12">
							<label for="txt_td">TD</label>
							<div class="input-group input-group-merge">
								<input type="text" id="tanda_vital_td" name="tanda_vital_td" class="form-control form-control-appended inputan" placeholder="TD">
								<div class="input-group-append">
									<div class="input-group-text">
										<span>mmHg</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-12">
							<label for="txt_n">N</label>
							<div class="input-group input-group-merge">
								<input type="text" name="tanda_vital_n" id="tanda_vital_n" class="form-control form-control-appended inputan" placeholder="N">
								<div class="input-group-append">
									<div class="input-group-text">
										<span>x/mnt</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-12">
							<label for="txt_s">S</label>
							<div class="input-group input-group-merge">
								<input type="text" name="tanda_vital_s" id="tanda_vital_s" class="form-control form-control-appended inputan" placeholder="S">
								<div class="input-group-append">
									<div class="input-group-text">
										<span>x/mnt</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-12">
							<label for="txt_rr">RR</label>
							<div class="input-group input-group-merge">
								<input type="text" name="tanda_vital_rr" id="tanda_vital_rr" class="form-control form-control-appended inputan" required="" placeholder="RR">
								<div class="input-group-append">
									<div class="input-group-text">
										<span>x/mnt</span>
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
<div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Kesehatan</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="rujukan">Riwayat Penyakit Sebelumnya :</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_sakit_sebelumnya" value="0" id="riwayat_sakit_sebelumnya_0">
										<label class='custom-control-label' for="riwayat_sakit_sebelumnya_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_sakit_sebelumnya" value="1" id="riwayat_sakit_sebelumnya_1">
										<label class='custom-control-label' for="riwayat_sakit_sebelumnya_1">Ya</label>
									</div>
									<div class="col-md-10">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Sakit</span>
												</span>
											</div>
											<input type="text" class="form-control inputan" id="riwayat_sakit_sebelumnya_ket">
										</div>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Operasi:</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_operasi" value="0" id="riwayat_operasi_0">
										<label class='custom-control-label' for="riwayat_operasi_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_operasi" value="1" id="riwayat_operasi_1">
										<label class='custom-control-label' for="riwayat_operasi_1">Ya</label>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Operasi</span>
												</span>
											</div>
											<input type="text" class="form-control inputan" id="riwayat_operasi_ket">
										</div>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Kapan</span>
												</span>
											</div>
											<input type="date" class="form-control inputan" id="riwayat_waktu_operasi">
										</div>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Dirawat:</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_dirawat" value="0" id="riwayat_dirawat_0">
										<label class='custom-control-label' for="riwayat_dirawat_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_dirawat" value="1" id="riwayat_dirawat_1">
										<label class='custom-control-label' for="riwayat_dirawat_1">Ya</label>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Kapan</span>
												</span>
											</div>
											<input type="date" class="form-control inputan" id="riwayat_waktu_dirawat" name="riwayat_waktu_dirawat">
										</div>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Diagnosa</span>
												</span>
											</div>
											<input type="text" class="form-control inputan" id="riwayat_diagnosa_dirawat" name="riwayat_diagnosa_dirawat">
										</div>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Pengobatan Dirumah:</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_pengobatan_dirumah" value="0" id="riwayat_pengobatan_dirumah_0">
										<label class='custom-control-label' for="riwayat_pengobatan_dirumah_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_pengobatan_dirumah" value="1" id="riwayat_pengobatan_dirumah_1">
										<label class='custom-control-label' for="riwayat_pengobatan_dirumah_1">Ya</label>
									</div>
									<div class="col-md-10">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Nama Obat</span>
												</span>
											</div>
											<input type="text" class="form-control inputan" id="riwayat_pengobatan_dirumah_nama_obat">
										</div>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Alergi:</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_alergi" value="0" id="riwayat_alergi_0">
										<label class='custom-control-label' for="riwayat_alergi_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_alergi" value="0" id="riwayat_alergi_1">
										<label class='custom-control-label' for="riwayat_alergi_1">Ya</label>
									</div>
									<div class="col-md-10">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Alergi</span>
												</span>
											</div>
											<input type="text" class="form-control inputan" id="riwayat_alergi_ket">
										</div>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Transfusi Darah: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_transfusi_darah" value="0" id="riwayat_transfusi_darah_1">
										<label class='custom-control-label' for="riwayat_transfusi_darah_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_transfusi_darah" value="1" id="riwayat_transfusi_darah_2">
										<label class='custom-control-label' for="riwayat_transfusi_darah_2">Ya</label>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Gol. Darah</span>
												</span>
											</div>
											<input type="text" class="form-control inputan" id="riwayat_transfusi_golongan_darah">
										</div>
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Merokok: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_merokok" value="0" id="riwayat_merokok_1">
										<label class='custom-control-label' for="riwayat_merokok_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_merokok" value="1" id="riwayat_merokok_2">
										<label class='custom-control-label' for="riwayat_merokok_2">Ya</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control inputan" id="riwayat_merokok_ket">
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Minuman Keras: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_miras" value="riwayat_miras_1" id="riwayat_miras_1">
										<label class='custom-control-label' for="riwayat_miras_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_miras" value="riwayat_miras_2" id="riwayat_miras_2">
										<label class='custom-control-label' for="riwayat_miras_2">Ya</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control inputan" id="riwayat_miras_ket">
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Obat Terlarang: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="riwayat_obt_terlarang" value="0" id="riwayat_obt_terlarang_1">
										<label class='custom-control-label' for="riwayat_obt_terlarang_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_obt_terlarang" value="1" id="riwayat_obt_terlarang_2">
										<label class='custom-control-label' for="riwayat_obt_terlarang_2">Ya</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control inputan" id="riwayat_obt_terlarang_ket">
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Imunisasi: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_dpt_1" value="1" id="riwayat_imunisasi_dpt_1">
										<label for="riwayat_imunisasi_dpt_1">DPT I</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_dpt_2" value="1" id="riwayat_imunisasi_dpt_2">
										<label for="riwayat_imunisasi_dpt_2">DPT II</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_dpt_3" value="1" id="riwayat_imunisasi_dpt_3">
										<label for="riwayat_imunisasi_dpt_3">DPT III</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_campak" value="1" id="riwayat_imunisasi_campak">
										<label for="riwayat_imunisasi_campak">Campak</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_bcg" value="1" id="riwayat_imunisasi_bcg">
										<label for="riwayat_imunisasi_bcg">BCG</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_polio_1" value="1" id="riwayat_imunisasi_polio_1">
										<label for="riwayat_imunisasi_polio_1">Polio I</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_polio_2" value="1" id="riwayat_imunisasi_polio_2">
										<label for="riwayat_imunisasi_polio_2">Polio II</label>
									</div>
								</div>
								<!-- <div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_poli_3" value="1" id="riwayat_imunisasi_poli_3">
										<label for="riwayat_imunisasi_poli_3">DPT II</label>
									</div>
								</div> -->
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_hepatitis" value="1" id="riwayat_imunisasi_hepatitis">
										<label for="riwayat_imunisasi_hepatitis">Hepatitis</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_mmr" value="1" id="riwayat_imunisasi_mmr">
										<label for="riwayat_imunisasi_mmr">MMR</label>
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
<div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Keluarga</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Penyakit dari Keluarga: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_asma" value="1" id="riwayat_keluarga_asma">
										<label for="riwayat_keluarga_asma">Asma</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_diabetes" value="1" id="riwayat_keluarga_diabetes">
										<label for="riwayat_keluarga_diabetes">Diabetes</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="
										riwayat_keluarga_hipertensi" value="1" id="riwayat_keluarga_hipertensi">
										<label for="riwayat_keluarga_hipertensi">Hipertensi</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_cancer" value="1" id="riwayat_keluarga_cancer">
										<label for="riwayat_keluarga_cancer">Cancer</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_anemia" value="1" id="riwayat_keluarga_anemia">
										<label for="riwayat_keluarga_anemia">Anemia</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_jantung" value="1" id="riwayat_keluarga_jantung">
										<label for="riwayat_keluarga_jantung">Jantung</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_lainnya" value="1" id="riwayat_keluarga_lainnya">
										<label for="riwayat_keluarga_lainnya">Lainnya</label>
										<input type="text" name="riwayat_keluarga_lainnya_ket" id="riwayat_keluarga_lainnya_ket" class="form-control inputan">
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-6">
	                	<div class="col-12 col-md-12 mb-3">
		                	<label for="hub_keluarga">Hubungan Keluarga</label>
		                	<input type="text" name="riwayat_hub_keluarga" id="riwayat_hub_keluarga" class="form-control inputan">
	                	</div>
	                </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Pernikahan</h5>
			</div>
			<div class="card-body">
				<div class="row">
					 <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Status Pernikahan: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-4">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="status_nikah" value="status_nikah_1" id="status_nikah_1">
										<label class='custom-control-label' for="status_nikah_1">Belum Menikah</label>
									</div>
								</div>
								<div class="col-md-8 row">
									<div class="custom-control custom-radio col-md-3">
										<input type="radio" class="custom-control-input" name="status_nikah" value="status_nikah_2" id="status_nikah_2">
										<label class='custom-control-label' for="status_nikah_2">Menikah</label>
									</div>
									<div class="col-md-3">
										<div class="input-group input-group-merge">
											<input type="text" id="jumlah_nikah" name="jumlah_nikah" class="form-control form-control-appended" required="" >
											<div class="input-group-append">
												<div class="input-group-text">
													<span>kali</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="status_nikah" value="status_nikah_3" id="status_nikah_3">
										<label class='custom-control-label' for="status_nikah_3">Bercerai</label>
									</div>
								</div>
								<div class="col-md-8 row">
									<div class="custom-control custom-radio col-md-2">
										<input type="radio" class="custom-control-input" name="status_nikah" value="status_nikah_4" id="status_nikah_4">
										<label class='custom-control-label' for="status_nikah_4">Janda/Duda</label>
									</div>
								</div>
							</div>
	                    </div>
	                </div>
				</div>
			</div>
		</div>
	</div>
</div> -->


<div class="row wanita" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Menstruasi (Kebidanan):</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Menarche Umur: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="input-group input-group-merge">
										<input type="text" id="menarche_umur" name="menarche_umur" class="form-control form-control-appended inputan">
										<div class="input-group-append">
											<div class="input-group-text">
												<span>Tahun</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="input-group input-group-merge">
										<div class="input-group-prepended">
											<span class="input-group-text">
												<span>Siklus</span>
											</span>
										</div>
										<input type="text" id="menarche_siklus" name="menarche_siklus" class="form-control form-control-appended inputan" required="" >
										<div class="input-group-append">
											<div class="input-group-text">
												<span>Hari</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="menarche_stat" value="Teratur" id="menarche_stat_1">
										<label class='custom-control-label' for="menarche_stat_1">Teratur</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="menarche_stat" value="Tidak Teratur" id="menarche_stat_2">
										<label class='custom-control-label' for="menarche_stat_2">Tidak Teratur</label>
									</div>
								</div>
							</div>
	                    </div>
	                </div>
                	<div class="col-6 col-md-6 mb-3">
                		<div class="form-group col-md-12">
                			<label for="">Lama Siklus: </label>
							<div class="row col-md-7" id="">
								<div class="col-md-12">
									<div class="input-group input-group-merge">
										<input type="text" id="menarche_lama_siklus" name="menarche_lama_siklus" class="form-control form-control-appended inputan">
										<div class="input-group-append">
											<div class="input-group-text">
												<span>Hari</span>
											</div>
										</div>
									</div>
								</div>
							</div>
                		</div>
                	</div>
					<div class="col-6 col-md-6 mb-3">
						<div class="form-group col-md-12">
							<label for="">Keluhan Saat Haid: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-6">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="keluhan_haid" value="0" id="keluhan_haid_1">
										<label class='custom-control-label' for="keluhan_haid_1">Tidak ada</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="keluhan_haid" value="1" id="keluhan_haid_2">
										<label class='custom-control-label' for="keluhan_haid_2">Ya</label>
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <div class="col-6 col-md-6 mb-3">
                		<div class="form-group col-md-12">
                			<label for="">HPHT: </label>
							<input type="text" id="hpht" name="hpht" class="form-control form-control-appended inputan">
                		</div>
                	</div>
                	<div class="col-6 col-md-6 mb-3">
                		<div class="form-group col-md-12">
                			<label for="">Taksiran Persalinan: </label>
							<input type="text" id="taksiran_persalinan" name="taksiran_persalinan" class="form-control form-control-appended inputan">
                		</div>
                	</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Reproduksi:</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-12 wanita">
						 <div class="col-12 col-md-12 mb-3">
							<label for="rujukan">Wanita: Hamil</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="wanita_hamil" value="Tidak" id="wanita_hamil_0">
										<label class='custom-control-label' for="wanita_hamil_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="wanita_hamil" value="Ya" id="wanita_hamil_1">
										<label class='custom-control-label' for="wanita_hamil_1">Ya</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="wanita_hamil" value="Tidak Tahu" id="wanita_hamil_2">
										<label class='custom-control-label' for="wanita_hamil_2">Tidak Tahu</label>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12 pria">
						 <div class="col-12 col-md-12 mb-3">
							<label for="rujukan">Pria: Masalah Prostat</label>
							<div class="row col-md-12">
								<div class="col-md-3 ">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="pria_prostat" value="Tidak" id="pria_prostat_0">
										<label class='custom-control-label' for="pria_prostat_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="pria_prostat" value="Ya" id="pria_prostat_1">
										<label class='custom-control-label' for="pria_prostat_1">Ya</label>
									</div>
								</div>
								<div class="col-md-6 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="pria_prostat" value="Tidak Tahu" id="pria_prostat_2">
										<label class='custom-control-label' for="pria_prostat_2">Tidak Tahu</label>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12 row">
						<div class="col-6 col-md-6 mb-3">
							<label for="program_kb">Keikutsertaan Program KB:</label>
							<div class="col-md-6">
								<select class="form-control inputan" id="program_kb">
									<option value="0">Tidak</option>
									<option value="1">Ya</option>
								</select>
							</div>
						</div>
						<div class="col-6 col-md-6 mb-3">
							<label for="k">Sebutkan:</label>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_iud" value="1" id="program_kb_iud">
										<label for="program_kb_iud">IUD</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_susuk" value="1" id="program_kb_susuk">
										<label for="program_kb_susuk">Susuk</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_suntik" value="1" id="program_kb_suntik">
										<label for="program_kb_suntik">Suntik</label>
									</div>
								</div>
							</div>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_pil" value="1" id="program_kb_pil">
										<label for="program_kb_pil">PIL</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_steril" value="1" id="program_kb_steril">
										<label for="program_kb_steril">Steril</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_vasectomi" value="1" id="program_kb_vasectomi">
										<label for="program_kb_vasectomi">Vasectomi</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6 col-md-6 mb-3">
	                		<div class="form-group col-md-12">
	                			<label for="">Lama Pemakaian: </label>
								<input type="text" id="program_kb_lama_pemakaian" name="program_kb_lama_pemakaian" class="form-control form-control-appended inputan">
	                		</div>
	                	</div>
	                	<div class="col-6 col-md-6 mb-3">
	                		<div class="form-group col-md-12">
	                			<label for="">Keluhan: </label>
								<input type="text" id="program_kb_keluhan" name="program_kb_keluhan" class="form-control form-control-appended inputan">
	                		</div>
	                	</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 
<div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Reproduksi:</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="rujukan">Wanita: Hamil</label>
							<div class="row col-md-12" id="">
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="wanita_hamil" value="Tidak" id="wanita_hamil_0">
										<label class='custom-control-label' for="wanita_hamil_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="wanita_hamil" value="Ya" id="wanita_hamil_1">
										<label class='custom-control-label' for="wanita_hamil_1">Ya</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="wanita_hamil" value="Tidak Tahu" id="wanita_hamil_2">
										<label class='custom-control-label' for="wanita_hamil_2">Tidak Tahu</label>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12 pria">
						 <div class="col-12 col-md-12 mb-3">
							<label for="rujukan">Pria: Masalah Prostat</label>
							<div class="row col-md-12">
								<div class="col-md-3 ">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="pria_prostat" value="Tidak" id="pria_prostat_0">
										<label class='custom-control-label' for="pria_prostat_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="pria_prostat" value="Ya" id="pria_prostat_1">
										<label class='custom-control-label' for="pria_prostat_1">Ya</label>
									</div>
								</div>
								<div class="col-md-6 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="pria_prostat" value="Tidak Tahu" id="pria_prostat_2">
										<label class='custom-control-label' for="pria_prostat_2">Tidak Tahu</label>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12 row">
						<div class="col-6 col-md-6 mb-3">
							<label for="program_kb">Keikutsertaan Program KB:</label>
							<div class="col-md-6">
								<select class="form-control" id="program_kb">
									<option value="tidak">Tidak</option>
									<option value="ya">Ya</option>
								</select>
							</div>
						</div>
						<div class="col-6 col-md-6 mb-3">
							<label for="k">Sebutkan:</label>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_1" value="program_kb_1" id="program_kb_1">
										<label for="program_kb_1">IUD</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_2" value="program_kb_2" id="program_kb_2">
										<label for="program_kb_2">Susuk</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_3" value="program_kb_3" id="program_kb_3">
										<label for="program_kb_3">Suntik</label>
									</div>
								</div>
							</div>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_4" value="program_kb_4" id="program_kb_1">
										<label for="program_kb_4">PIL</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_5" value="program_kb_5" id="program_kb_6">
										<label for="program_kb_6">Steril</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="program_kb_6" value="program_kb_6" id="program_kb_6">
										<label for="program_kb_6">Vasectomi</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6 col-md-6 mb-3">
	                		<div class="form-group col-md-12">
	                			<label for="">Lama Pemakaian: </label>
								<input type="text" id="lama_pemakaian" name="lama_pemakaian" class="form-control form-control-appended">
	                		</div>
	                	</div>
	                	<div class="col-6 col-md-6 mb-3">
	                		<div class="form-group col-md-12">
	                			<label for="">Keluhan: </label>
								<input type="text" id="lama_pemakaian" name="lama_pemakaian" class="form-control form-control-appended">
	                		</div>
	                	</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 -->

<div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Penyakit Ginekologi (Kebidanan):</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-2">
						<select class="form-control inputan" id="ginekologi_status">
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<select class="form-control inputan" id="ginekologi">
							<option value="Infertilitas">Infertilitas</option>
							<option value="1nfeksi Virus">Infeksi Virus</option>
							<option value="PMS">PMS</option>
							<option value="Endometriosis">Endometriosis</option>
							<option value="Mioma">Mioma</option>
							<option value="Polyp Cervix">Polyp Cervix</option>
							<option value="Kanker">Kanker</option>
							<option value="0">Lainnya</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" class="form-control inputan" id="ginekologi_lainnya" name="ginekologi_lainnya">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row wanita" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Kehamilan, Persalinan dan Nifas Yang Lalu (Kebidanan):</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-3">
						<label>Tanggal Partus: </label>
						<input type="date" name="tgl_partus" id="tgl_partus" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Usia Kehamilan: </label>
						<input type="text" name="usia_hamil" id="usia_hamil" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Tempat Partus: </label>
						<input type="text" name="tempat_partus" id="tempat_partus" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Jenis Partus: </label>
						<select class="form-control inputan" id="jenis_partus" name="jenis_partus">
							<option value="">Pilih</option>
							<option value="Normal">Normal</option>
							<option value="Cesar">Cesar</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Penolong: </label>
						<input type="text" name="penolong_partus" id="penolong_partus" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Nifas: </label>
						<input type="text" name="nifas" id="nifas" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Jenis Kelamin Anak: </label>
						<input type="text" name="jenkel_anak" id="jenkel_anak" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Berat Badan Anak: </label>
						<input type="text" name="bb_anak" id="bb_anak" class="form-control inputan">
					</div>
					<div class="form-group col-md-6">
						<label>Keadaan Sekarang: </label>
						<input type="text" name="keadaan_anak" id="keadaan_anak" class="form-control inputan">
					</div>
					<div class="form-group col-md-6">
						<label>Keterangan: </label>
						<input type="text" name="keterangan_anak" id="keterangan_anak" class="form-control inputan">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Skala Nyeri:</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-3">
						<label>Nyeri: </label>
						<select class="form-control inputan" id="nyeri" name="nyeri">
							<option value="">Pilih</option>
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Lokasi: </label>
						<input type="text" name="nyeri_" id="nyeri_lokasi" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Frekuensi: </label>
						<select class="form-control inputan" name="nyeri_frekuensi" id="nyeri_frekuensi">
							<option value="">Pilih</option>
							<option value="Sering">Sering</option>
							<option value="Kadang">Kadang</option>
							<option value="Jarang">Jarang</option>
						</select>
					</div>
					<div class="col-6 col-md-6 mb-3">
						<label for="k">Karakteristik Nyeri:</label>
						<div class="row col-md-12" id="">
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_terbakar" value="Terbakar" id="nyeri_terbakar">
									<label for="nyeri_terbakar">Terbakar</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_tertindih" value="Tertindih" id="nyeri_tertindih">
									<label for="nyeri_tertindih">Tertindih</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_menyebar" value="Menyebar" id="nyeri_menyebar">
									<label for="nyeri_menyebar">Menyebar</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_tajam" value="Tajam" id="nyeri_tajam">
									<label for="nyeri_tajam">Tajam</label>
								</div>
							</div>
						</div>
						<div class="row col-md-12" id="">
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_tumpul" value="Tumpul" id="nyeri_tumpul">
									<label for="nyeri_tumpul">Tumpul</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_denyut" value="Berdenyut" id="nyeri_denyut">
									<label for="nyeri_denyut">Berdenyut</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_lainnya" value="Lainnya" id="nyeri_lainnya">
									<label for="nyeri_lainnya">Lainnya</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-3" id="nyeri_lainnya_ket">
						<label>Nyeri Lainnya: </label>
						<input type="text" name="nyeri_lainnya_ket" id="nyeri_lainnya_ket" class="form-control inputan">
					</div>
					<div class="form-group col-md-12">
						<label>Skala Nyeri NRS ( &gt; 5th - Dewasa)</label>
						<input type="text" id="nyeri_skala" name="nyeri_skala" />
					</div>
					<div class="form-group col-md-3">
						<label>Total Skor: </label>
						<input type="text" name="nyeri_total_skor" id="nyeri_total_skor" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Tipe: </label>
						<select class="form-control inputan" name="nyeri_tipe" id="nyeri_tipe">
							<option value="">Pilih</option>
							<option value="Ringan">Ringan</option>
							<option value="Sedang">Sedang</option>
							<option value="Berat">Berat</option>
							<option value="Berat Sekali">Berat Sekali</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var sliderNyeri = new rSlider({
        target: '#skala_nyeri',
        values: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        range: false,
        set: [0],
        tooltip: false,
        onChange: function (vals) {
            console.log(vals);
        }
    });
</script>