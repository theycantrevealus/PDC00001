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
							<input type="" name="" id="pj_pasien" class="form-control" value="">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Informasi di Dapat Dari</label>
						</div>
						<div class="col-md-8">
							<input type="" name="" id="pj_pasien"  class="form-control" value="">
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
									<select class="form-control" id="kesadaran" name="kesadaran">
										<option value="">Compos Mentis</option>
										<option value="">Apatis</option>
									</select>
									<!-- <div class="row col-md-12" id="parent_jenkel">
										<div class="col-md-6">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="kesadaran" value="kesadaran_1" id="kesadaran_1">
												<label class='custom-control-label' for="kesadaran_1">Compos Mentis</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="kesadaran" value="kesadaran_2" id="kesadaran_2">
												<label class='custom-control-label' for="kesadaran_2">Apatis</label>
											</div>
										</div>
									</div> -->
			                    </div>
			                    <div class="form-group col-md-6">
									<label for="sikap_tubuh">Sikap Tubuh :</label>
									<select class="form-control" id="sikap_tubuh" name="sikap_tubuh">
										<option value="">Normal</option>
										<option value="">Lordosis</option>
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
												<input type="radio" class="custom-control-input" name="cara_masuk" value="cara_masuk_1" id="cara_masuk_1">
												<label class='custom-control-label' for="cara_masuk_1">Jalan</label>
											</div>
										</div>
										<div class="col-md-3">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="cara_masuk" value="cara_masuk_2" id="cara_masuk_2">
												<label class='custom-control-label' for="cara_masuk_2">Kursi Roda</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="custom-control custom-radio">
												<input type="radio" class="custom-control-input" name="cara_masuk" value="cara_masuk_3" id="cara_masuk_3">
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
												<input type="radio" class="custom-control-input" name="rujukan" value="rujukan_1" id="rujukan_1">
												<label class='custom-control-label' for="rujukan_1">Tidak</label>
											</div>
										</div>
										<div class="col-md-9 row">
											<div class="custom-control custom-radio col-md-1">
												<input type="radio" class="custom-control-input" name="rujukan" value="rujukank_2" id="rujukan_2">
												<label class='custom-control-label' for="rujukan_2">Ya</label>
											</div>
											<div class="col-md-10">
												<input type="text" class="form-control" id="rujukan_ya">
											</div>
										</div>
									</div>
			                    </div>
							</div>
						</div>
						<div class="col-md-12 row">
							<div class="form-group col-lg-12">
								<label for="diagnosa_rujukan">Diagnosa Rujukan: </label>
								<input type="text" name="diagnosa_rujukan" id="diagnosa_rujukan" class="form-control">
							</div>
						</div>
						<br/>
						<div class="row col-md-12">
							<div class="form-group col-lg-6">
								<label for="txt_tekanan_darah">Berat Badan</label>
								<div class="input-group input-group-merge">
									<input type="text" id="berat_badan" name="berat_badan" class="form-control form-control-appended" required="" placeholder="Berat Badan">
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
									<input type="text" id="tinggi_badan"  name="tinggi_badan" class="form-control form-control-appended" required="" placeholder="Tinggi Badan">
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
								<input type="text" id="vital_td" name="vital_td" class="form-control form-control-appended" placeholder="TD">
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
								<input type="text" name="vital_n" id="vital_n" class="form-control form-control-appended" placeholder="N">
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
								<input type="text" name="vital_s" id="vital_s" class="form-control form-control-appended" required="" placeholder="S">
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
								<input type="text" name="vital_rr" id="vital_rr" class="form-control form-control-appended" required="" placeholder="RR">
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
										<input type="radio" class="custom-control-input" name="sakit_sebelumnya" value="sakit_sebelumnya_1" id="sakit_sebelumnya_1">
										<label class='custom-control-label' for="sakit_sebelumnya_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="sakit_sebelumnya" value="sakit_sebelumnya_2" id="sakit_sebelumnya_2">
										<label class='custom-control-label' for="sakit_sebelumnya_2">Ya</label>
									</div>
									<div class="col-md-10">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Sakit</span>
												</span>
											</div>
											<input type="text" class="form-control" id="sakit_sebelumnya">
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
										<input type="radio" class="custom-control-input" name="riwayat_operasi" value="riwayat_operasi_1" id="riwayat_operasi_1">
										<label class='custom-control-label' for="riwayat_operasi_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_operasi" value="riwayat_operasi_2" id="riwayat_operasi_2">
										<label class='custom-control-label' for="riwayat_operasi_2">Ya</label>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Operasi</span>
												</span>
											</div>
											<input type="text" class="form-control" id="riwayat_operasi">
										</div>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Kapan</span>
												</span>
											</div>
											<input type="date" class="form-control" id="waktu_operasi">
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
										<input type="radio" class="custom-control-input" name="riwayat_dirawat" value="riwayat_dirawat_1" id="riwayat_dirawat_1">
										<label class='custom-control-label' for="riwayat_dirawat_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_dirawat" value="riwayat_dirawat_2" id="riwayat_dirawat_2">
										<label class='custom-control-label' for="riwayat_dirawat_2">Ya</label>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Kapan</span>
												</span>
											</div>
											<input type="date" class="form-control" id="waktu_dirawat" name="waktu_dirawat">
										</div>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Diagnosa</span>
												</span>
											</div>
											<input type="text" class="form-control" id="diagnosa_dirawat" name="diagnosa_dirawat">
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
										<input type="radio" class="custom-control-input" name="riwayat_pengobatan_dirumah" value="riwayat_pengobatan_dirumah_1" id="riwayat_pengobatan_dirumah_1">
										<label class='custom-control-label' for="riwayat_pengobatan_dirumah_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_pengobatan_dirumah" value="riwayat_pengobatan_dirumah_2" id="riwayat_pengobatan_dirumah_2">
										<label class='custom-control-label' for="riwayat_pengobatan_dirumah_2">Ya</label>
									</div>
									<div class="col-md-10">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Nama Obat</span>
												</span>
											</div>
											<input type="text" class="form-control" id="nama_obat_pengobatan_dirumah">
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
										<input type="radio" class="custom-control-input" name="riwayat_alergi" value="riwayat_alergi_1" id="riwayat_alergi_1">
										<label class='custom-control-label' for="riwayat_alergi_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_alergi" value="riwayat_alergi_2" id="riwayat_alergi_2">
										<label class='custom-control-label' for="riwayat_alergi_2">Ya</label>
									</div>
									<div class="col-md-10">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Alergi</span>
												</span>
											</div>
											<input type="text" class="form-control" id="alergi">
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
										<input type="radio" class="custom-control-input" name="riwayat_transfusi_darah" value="riwayat_transfusi_darah_1" id="riwayat_transfusi_darah_1">
										<label class='custom-control-label' for="riwayat_transfusi_darah_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_transfusi_darah" value="riwayat_transfusi_darah_2" id="riwayat_transfusi_darah_2">
										<label class='custom-control-label' for="riwayat_transfusi_darah_2">Ya</label>
									</div>
									<div class="col-md-5">
										<div class="input-group">
											<div class="input-group-prepended">
												<span class="input-group-text">
													<span>Gol. Darah</span>
												</span>
											</div>
											<input type="text" class="form-control" id="riwayat_transfusi_golongan_darah">
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
										<input type="radio" class="custom-control-input" name="riwayat_merokok" value="riwayat_merokok_1" id="riwayat_merokok_1">
										<label class='custom-control-label' for="riwayat_merokok_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_merokok" value="riwayat_merokok_2" id="riwayat_merokok_2">
										<label class='custom-control-label' for="riwayat_merokok_2">Ya</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control" id="keterangan_riwayat_merokok">
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
										<input type="text" class="form-control" id="keterangan_riwayat_miras">
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
										<input type="radio" class="custom-control-input" name="riwayat_obt_terlarang" value="riwayat_obt_terlarang_1" id="riwayat_obt_terlarang_1">
										<label class='custom-control-label' for="riwayat_obt_terlarang_1">Tidak</label>
									</div>
								</div>
								<div class="col-md-9 row">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="riwayat_obt_terlarang" value="riwayat_obt_terlarang_2" id="riwayat_obt_terlarang_2">
										<label class='custom-control-label' for="riwayat_obt_terlarang_2">Ya</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control" id="keterangan_riwayat_obt_terlarang">
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Imuniasasi: </label>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_1" value="riwayat_imunisasi_1" id="riwayat_imunisasi_1">
										<label for="riwayat_imunisasi_1">DPT I</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_2" value="riwayat_imunisasi_2" id="riwayat_imunisasi_2">
										<label for="riwayat_imunisasi_2">DPT I</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_3" value="riwayat_imunisasi_3" id="riwayat_imunisasi_3">
										<label for="riwayat_imunisasi_3">Campak</label>
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
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_1" value="riwayat_keluarga_1" id="riwayat_keluarga_1">
										<label for="riwayat_keluarga_1">Asma</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_2" value="riwayat_keluarga_2" id="riwayat_keluarga_2">
										<label for="riwayat_keluarga_2">Diabetes</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_3" value="riwayat_keluarga_3" id="riwayat_keluarga_3">
										<label for="riwayat_keluarga_3">Hipertensi</label>
									</div>
								</div>
							</div>
	                    </div>
	                </div>
	                <div class="form-group col-lg-6">
	                	<div class="col-12 col-md-12 mb-3">
		                	<label for="hub_keluarga">Hubungan Kelurga</label>
		                	<input type="text" name="hub_keluarga" id="hub_keluarga" class="form-control">
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
</div>
<div class="row" style="margin-top: 20px;">
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
										<input type="text" id="menarche_umur" name="menarche_umur" class="form-control form-control-appended" required="" >
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
										<input type="text" id="menarche_umur" name="menarche_umur" class="form-control form-control-appended" required="" >
										<div class="input-group-append">
											<div class="input-group-text">
												<span>Hari</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="menarche_stat" value="menarche_stat_1" id="menarche_stat_1">
										<label class='custom-control-label' for="menarche_stat_1">Teratur</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="menarche_stat" value="menarche_stat_2" id="menarche_stat_2">
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
										<input type="text" id="menarche_umur" name="menarche_umur" class="form-control form-control-appended" required="" >
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
										<input type="radio" class="custom-control-input" name="keluhan_haid" value="keluhan_haid_1" id="keluhan_haid_1">
										<label class='custom-control-label' for="keluhan_haid_1">Tidak ada</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="custom-control custom-radio col-md-1">
										<input type="radio" class="custom-control-input" name="keluhan_haid" value="keluhan_haid_2" id="keluhan_haid_2">
										<label class='custom-control-label' for="keluhan_haid_2">Ya</label>
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <div class="col-6 col-md-6 mb-3">
                		<div class="form-group col-md-12">
                			<label for="">HPHT: </label>
							<input type="text" id="hpht" name="hpht" class="form-control form-control-appended">
                		</div>
                	</div>
                	<div class="col-6 col-md-6 mb-3">
                		<div class="form-group col-md-12">
                			<label for="">Taksiran Persalinan: </label>
							<input type="text" id="taksiran_persalinan" name="taksiran_persalinan" class="form-control form-control-appended">
                		</div>
                	</div>
				</div>
			</div>
		</div>
	</div>
</div>

