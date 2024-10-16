<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
					<table class="table form-mode">
						<tbody>
							<tr>
								<td>No. Rekam Medis</td>
								<td> : </td>
								<td><b><span id="no_rm"></span></b></td>
								<td>Tanggal Lahir</td>
								<td> : </td>
								<td><b><span id="tanggal_lahir"></span></b></td>
							</tr>
							<tr>
								<td>Nama Pasien</td>
								<td> : </td>
								<td><b><span id="panggilan"></span> <span id="nama"></span> </b></td>
								<td>Jenis Kelamin</td>
								<td> : </td>
								<td><b><span id="jenkel"></span></b></td>
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
			<div class="card-header d-flex align-items-center bg-white">
				<h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Informasi Pendaftaran</h5>
			</div>
			<div class="card-body ">
				<div class="row">
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Pendaftaran</label>
						</div>
						<div class="col-md-8">
							<input type="" name="" id="waktu_masuk" disabled class="form-control" value="">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Cara Pembayaran</label>
						</div>
						<div class="col-md-8">
							<input type="" name="" id="nama_penjamin" disabled class="form-control">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Penanggung Jawab Pasien</label>
						</div>
						<div class="col-md-8">
							<input type="" name="pj_pasien" id="pj_pasien" disabled class="form-control " value="">
						</div>
					</div>
					<div class="col-md-6 row form-group">
						<div class="col-md-4">
							<label>Informasi di Dapat Dari</label>
						</div>
						<div class="col-md-8">
							<input type="" name="info_didapat_dari" id="info_didapat_dari" disabled class="form-control " value="">
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header d-flex align-items-center bg-white">
				<h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pengkajian Keperawatan</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-md-12 row">
								 <div class="form-group col-md-6">
									<label for="kesadaran">Kesadaran :</label>
									<select class="form-control inputan select2" id="kesadaran" name="kesadaran">
										<option value="">Pilih</option>
										<option value="Compos Mentis">Compos Mentis</option>
										<option value="Apatis">Apatis</option>
									</select>
			                    </div>
			                    <div class="form-group col-md-6">
									<label for="sikap_tubuh">Sikap Tubuh :</label>
									<select class="form-control inputan select2" id="sikap_tubuh" name="sikap_tubuh">
										<option value="Normal">Normal</option>
										<option value="Lordosis">Lordosis</option>
										<option value="Kifosis">Kifosis</option>
										<option value="Skoliosis">Skoliosis</option>
										<option value="Cacat">Cacat</option>
									</select>
								</div>
							</div>
							<div class="form-group col-lg-12">
								 <div class="col-12 col-md-12 mb-3">
									<label for="cara_masuk">Cara Masuk :</label>
									<div class="row col-md-12" id="cara_masuk">
										<div class="col-md-3">
											<div class="form-check">
												<input type="radio" class="form-check-input" name="cara_masuk" value="Jalan" id="cara_masuk_1">
												<label class='form-check-label' for="cara_masuk_1">Jalan</label>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-check">
												<input type="radio" class="form-check-input" name="cara_masuk" value="Kursi Roda" id="cara_masuk_2">
												<label class='form-check-label' for="cara_masuk_2">Kursi Roda</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check">
												<input type="radio" class="form-check-input" name="cara_masuk" value="Lainnya" id="cara_masuk_3">
												<label class='form-check-label' for="cara_masuk_3">Lainnya:</label>
												<input type="text" disabled="disabled" class="form-control cara_masuk_lainnya inputan" id="cara_masuk_lainnya" name="cara_masuk_lainnya">
											</div>
										</div>
									</div>
			                    </div>
							</div>
							<div class="form-group col-lg-12 rujukan-bpjs">
								 <div class="col-12 col-md-12 mb-3">
									<label for="rujukan">Rujukan :</label>
									<div class="row col-md-12" id="rujukan">
										<div class="col-md-3">
											<div class="form-check">
												<input type="radio" class="form-check-input" name="rujukan" value="0" id="rujukan_1">
												<label class='form-check-label' for="rujukan_1">Tidak</label>
											</div>
										</div>
										<div class="col-md-9 row">
											<div class="form-check col-md-1">
												<input type="radio" class="form-check-input" name="rujukan" value="1" id="rujukan_2">
												<label class='form-check-label' for="rujukan_2">Ya</label>
											</div>
											<div class="col-md-10">
												<input type="text" class="form-control inputan ket_rujukan" id="rujukan_ket" placeholder="-">
											</div>
										</div>
									</div>
			                    </div>
							</div>
						</div>
						<div class="col-md-12 row rujukan-bpjs">
							<div class="form-group col-lg-12">
								<label for="diagnosa_rujukan">Diagnosa Rujukan: </label>
								<input type="text" name="rujukan_diagnosa" id="rujukan_diagnosa" class="form-control inputan ket_rujukan">
							</div>
						</div>
						<br/>
						<div class="row col-md-12">
							<div class="form-group col-lg-6">
								<label for="txt_tekanan_darah">Berat Badan</label>
								<div class="input-group input-group-merge">
									<input type="text" id="berat_badan" name="berat_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
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
									<input type="text" id="tinggi_badan"  name="tinggi_badan" class="form-control form-control-appended inputan numberonly" required="" placeholder="0">
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
			<div class="card-header d-flex align-items-center bg-white">
				<h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Tanda-tanda Vital</h5>
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
										<span>Celcius</span>
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
<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Riwayat Kesehatan</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-12">
						<div class="col-md-10">
							<label for="rujukan">Riwayat Penyakit Sebelumnya :</label>
							<div class="input-group">
								<input type="text" class="form-control inputan" id="riwayat_sakit_sebelumnya" placeholder="-">
							</div>
						</div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<div class="row col-md-12" id="">
								<label for="">Riwayat Operasi:</label>
								<textarea class="form-control inputan" id="riwayat_operasi" placeholder="-"></textarea>
								<div class="input-group">
									<div class="input-group-prepended">
										<span class="input-group-text">
											<span>Kapan</span>
										</span>
									</div>
									<input type="date" class="form-control inputan form-control-special" id="riwayat_waktu_operasi">
								</div>
								<div class="col-md-5">
									<!-- <div class="input-group">
										<div class="input-group-prepended">
											<span class="input-group-text">
												<span>Operasi</span>		
											</span>
										</div>
										<input type="text" class="form-control inputan" id="riwayat_operasi" placeholder="-">
									</div> -->
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Dirawat:</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<span>Kapan</span>
										</span>
									</div>
									<input type="date" class="input-group-append form-control inputan form-control-special" id="riwayat_waktu_dirawat" name="riwayat_waktu_dirawat">
								</div>
								<br />
								<span>Diagnosa:</span>
								<textarea class="form-control inputan" id="riwayat_diagnosa_dirawat" name="riwayat_diagnosa_dirawat" placeholder="-"></textarea>
							<div class="row col-md-12" id="">
								<!-- <div class="col-md-5">
									<div class="input-group">
										<div class="input-group-prepended">
											<span class="input-group-text">
												<span>Diagnosa</span>
											</span>
										</div>
										<input type="text" class="form-control inputan" id="riwayat_diagnosa_dirawat" name="riwayat_diagnosa_dirawat" placeholder="-">
									</div>
								</div> -->
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<b for="">Riwayat Pengobatan Dirumah:</b>
							<div class="row col-md-12" id="">
								<!-- <div class="col-md-10">
									<div class="input-group">
										<div class="input-group-prepended">
											<span class="input-group-text">
												<span>Nama Obat</span>
											</span>
										</div>
										<input type="text" class="form-control inputan" id="riwayat_pengobatan_dirumah_nama_obat" placeholder="-">
									</div>
								</div> -->
								<span>Nama Obat:</span>
								<textarea class="form-control inputan" id="riwayat_pengobatan_dirumah_nama_obat" placeholder="-"></textarea>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Alergi:</label>
							<!-- <div class="input-group">
								<div class="input-group-prepended">
									<span class="input-group-text">
										<span>Alergi</span>
									</span>
								</div>
								<input type="text" class="form-control inputan" id="riwayat_alergi" placeholder="-">
							</div> -->
							<textarea class="form-control inputan" id="riwayat_alergi" placeholder="-"></textarea>
	                    </div>
					</div>
					<div class="form-group col-lg-12">
						<div class="col-6">
							<label for="">Riwayat Transfusi Darah: </label>
							<select  class="form-control inputan select2" id="riwayat_transfusi_golongan_darah" name="riwayat_transfusi_golongan_darah">
								<option value="">Pilih</option>
							</select>
	                    </div>
	                </div>
                    <div class="col-12 col-md-12 mb-3">
                        <ol type="1" class="form-list-item">
                            <li>
                                <label>Riwayat Merokok: </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="radio" name="riwayat_merokok_option" value="n" checked="checked" />
                                            <label class="form-check-label">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="radio" name="riwayat_merokok_option" value="y" />
                                            <label class="form-check-label">
                                                Ya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control inputan riwayat_merokok" id="riwayat_merokok" placeholder="Riwayat Merokok" disabled />
                                    </div>
                                </div>
                            </li>

                        </ol>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <ol type="1" class="form-list-item">
                            <li>
                                <label for="">Riwayat Minuman Keras: </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="radio" name="riwayat_miras_option" value="n" checked="checked" />
                                            <label class="form-check-label">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="radio" name="riwayat_miras_option" value="y" />
                                            <label class="form-check-label">
                                                Ya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" disabled="disabled" class="form-control inputan riwayat_miras" id="riwayat_miras" placeholder="Riwayat Minuman Keras" />
                                    </div>
                                </div>
                            </li>

                        </ol>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <ol type="1" class="form-list-item">
                            <li>
                                <label for="">Riwayat Obat Terlarang: </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="radio" name="riwayat_obt_terlarang_option" value="n" checked="checked" />
                                            <label class="form-check-label">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="radio" name="riwayat_obt_terlarang_option" value="y" />
                                            <label class="form-check-label">
                                                Ya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" disabled="disabled" class="form-control inputan riwayat_obt_terlarang" id="riwayat_obt_terlarang" placeholder="Riwayat Obat Terlarang" />
                                    </div>
                                </div>
                            </li>

                        </ol>
                    </div>
	                <!--<div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Minuman Keras: </label>
							<br />
							<input type="radio" name="riwayat_miras_option" value="n" checked="checked" /> Tidak &nbsp;&nbsp;
							<input type="radio" name="riwayat_miras_option" value="y" /> Ya
							<br />
							<input type="text" disabled="disabled" class="form-control inputan riwayat_miras" id="riwayat_miras" placeholder="Riwayat Minuman Keras" />
	                    </div>
	                </div>
	                <div class="form-group col-lg-12">
						<div class="col-12 col-md-12 mb-3">
							<label for="">Riwayat Obat Terlarang: </label>
							<br />
							<input type="radio" name="riwayat_obt_terlarang_option" value="n" checked="checked" /> Tidak &nbsp;&nbsp;
							<input type="radio" name="riwayat_obt_terlarang_option" value="y" /> Ya
							<br />
							<input type="text" disabled="disabled" class="form-control inputan riwayat_obt_terlarang" id="riwayat_obt_terlarang" placeholder="Riwayat Obat Terlarang" />
	                    </div>
	                </div>-->


	                <div class="form-group col-lg-12">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="">Riwayat Imunisasi: </label>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_dpt_1" value="1" id="riwayat_imunisasi_dpt_1">
                                                    <label class="form-check-label" for="riwayat_imunisasi_dpt_1">
                                                        DPT 1
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_polio_2" value="1" id="riwayat_imunisasi_polio_2">
                                                    <label class="form-check-label" for="riwayat_imunisasi_polio_2">
                                                        Polio II
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-2">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_dpt_2" value="1" id="riwayat_imunisasi_dpt_2">
                                                    <label class="form-check-label" for="riwayat_imunisasi_dpt_2">
                                                        DPT II
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_hepatitis" value="1" id="riwayat_imunisasi_hepatitis">
                                                    <label class="form-check-label" for="riwayat_imunisasi_hepatitis">
                                                        Hepatitis
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-2">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_dpt_3" value="1" id="riwayat_imunisasi_dpt_3">
                                                    <label class="form-check-label" for="riwayat_imunisasi_dpt_3">
                                                        DPT III
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_mmr" value="1" id="riwayat_imunisasi_mmr">
                                                    <label class="form-check-label" for="riwayat_imunisasi_mmr">
                                                        MMR
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-2">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_campak" value="1" id="riwayat_imunisasi_campak">
                                                    <label class="form-check-label" for="riwayat_imunisasi_campak">
                                                        Campak
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-2">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_bcg" value="1" id="riwayat_imunisasi_bcg">
                                                    <label class="form-check-label" for="riwayat_imunisasi_bcg">
                                                        BCG
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-2">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="riwayat_imunisasi_polio_1" value="1" id="riwayat_imunisasi_polio_1">
                                                    <label class="form-check-label" for="riwayat_imunisasi_polio_1">
                                                        Polio I
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
						<!--<div class="col-12 col-md-12 mb-3">
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
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="riwayat_imunisasi_polio_3" value="1" id="riwayat_imunisasi_polio_3">
										<label for="riwayat_imunisasi_polio_3">Polio III</label>
									</div>
								</div>
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
	                    </div>-->
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
				<h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Riwayat Keluarga</h5>
			</div>
			<div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <label for="">Riwayat Penyakit dari Keluarga: </label>
                    </div>
                    <div class="col-md-2">
                        <ol type="1" class="form-list-item" style="list-style-type: none">
                            <li class="wrapped">
                                <h6>&nbsp;</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_asma" value="1" id="riwayat_keluarga_asma">
                                            <label class="form-check-label" for="riwayat_keluarga_asma">
                                                Asma
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_lainnya" value="1" id="riwayat_keluarga_lainnya" onclick='disableCheckboxChild(this, "riwayat_keluarga_lainnya_ket")'>
                                            <label class="form-check-label" for="riwayat_keluarga_lainnya">
                                                Lainnya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="riwayat_keluarga_lainnya_ket" id="riwayat_keluarga_lainnya_ket" class="form-control inputan riwayat_keluarga_lainnya_ket" disabled>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-2">
                        <ol type="1" class="form-list-item" style="list-style-type: none">
                            <li class="wrapped">
                                <h6>&nbsp;</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_diabetes" value="1" id="riwayat_keluarga_diabetes">
                                            <label class="form-check-label" for="riwayat_keluarga_diabetes">
                                                Diabetes
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-2">
                        <ol type="1" class="form-list-item" style="list-style-type: none">
                            <li class="wrapped">
                                <h6>&nbsp;</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_hipertensi" value="1" id="riwayat_keluarga_hipertensi">
                                            <label class="form-check-label" for="riwayat_keluarga_hipertensi">
                                                Hipertensi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-2">
                        <ol type="1" class="form-list-item" style="list-style-type: none">
                            <li class="wrapped">
                                <h6>&nbsp;</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_cancer" value="1" id="riwayat_keluarga_cancer">
                                            <label class="form-check-label" for="riwayat_keluarga_cancer">
                                                Cancer
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-2">
                        <ol type="1" class="form-list-item" style="list-style-type: none">
                            <li class="wrapped">
                                <h6>&nbsp;</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_anemia" value="1" id="riwayat_keluarga_anemia">
                                            <label class="form-check-label" for="riwayat_keluarga_anemia">
                                                Anemia
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-2">
                        <ol type="1" class="form-list-item" style="list-style-type: none">
                            <li class="wrapped">
                                <h6>&nbsp;</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="riwayat_keluarga_jantung" value="1" id="riwayat_keluarga_jantung">
                                            <label class="form-check-label" for="riwayat_keluarga_jantung">
                                                Jantung
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-12">
                        <label for="hub_keluarga">Hubungan Keluarga</label>
                        <input type="text" name="riwayat_hub_keluarga" id="riwayat_hub_keluarga" class="form-control inputan" placeholder="-">
                    </div>





					<!--<div class="form-group col-lg-12">
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
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_hipertensi" value="1" id="riwayat_keluarga_hipertensi">
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
										<input type="checkbox" class="form-check-input" name="riwayat_keluarga_lainnya" value="1" id="riwayat_keluarga_lainnya" onclick='disableCheckboxChild(this, "riwayat_keluarga_lainnya_ket")'>
										<label for="riwayat_keluarga_lainnya">Lainnya</label>
										<input type="text" name="riwayat_keluarga_lainnya_ket" id="riwayat_keluarga_lainnya_ket" class="form-control inputan riwayat_keluarga_lainnya_ket" disabled>
									</div>
								</div>
							</div>
	                    </div>
	                </div>-->
				</div>
			</div>
		</div>
	</div>
</div>




<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Riwayat Pernikahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <div class="row">
                            <div class="col-12">
                                <label for="">Status Pernikahan</label>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="status_pernikahan" value="1" id="riwayat_keluarga_belum">
                                                    <label class="form-check-label" for="riwayat_keluarga_belum">
                                                        Belum Menikah
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="status_pernikahan" value="2" id="riwayat_keluarga_sudah">
                                                    <label class="form-check-label" for="riwayat_keluarga_sudah">
                                                        Menikah
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group input-group-merge">
                                                        <input type="text" id="kali_nikah" name="kali_nikah" class="form-control form-control-appended inputan">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span>kali</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="status_pernikahan" value="3" id="riwayat_keluarga_bercerai">
                                                    <label class="form-check-label" for="riwayat_keluarga_bercerai">
                                                        Bercerai
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li class="wrapped">
                                        <h6>&nbsp;</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="status_pernikahan" value="4" id="riwayat_keluarga_janda_duda">
                                                    <label class="form-check-label" for="riwayat_keluarga_janda_duda">
                                                        Janda/Duda
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="umur_nikah">Umur Waktu Pertama Menikah</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="umur_nikah" name="umur_nikah" class="form-control form-control-appended inputan">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span>tahun</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!--div class="col-12 col-md-12 mb-3">
                            <label for="">Status Pernikahan: </label>
                            <div class="row col-md-12" id="">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="status_pernikahan" value="1" id="riwayat_keluarga_belum">
                                        <label for="riwayat_keluarga_belum">Belum Menikah</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="status_pernikahan" value="2" id="riwayat_keluarga_sudah">
                                        <label for="riwayat_keluarga_menikah">Menikah</label>
                                        <div class="form-group col-lg-12">
                                            <div class="input-group input-group-merge">
                                                <input type="text" id="kali_nikah" name="kali_nikah" class="form-control form-control-appended inputan">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span>kali</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="status_pernikahan" value="3" id="riwayat_keluarga_bercerai">
                                        <label for="riwayat_keluarga_bercerai">Bercerai</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="status_pernikahan" value="4" id="riwayat_keluarga_janda_duda">
                                        <label for="riwayat_keluarga_janda_duda">Janda/Duda</label>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="umur_nikah">Umur Waktu Pertama Menikah</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="umur_nikah" name="umur_nikah" class="form-control form-control-appended inputan">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span>tahun</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="col-12 col-md-12 mb-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row wanita">
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
										<div class="input-group-prepend">
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
									<div class="form-check">
										<input type="radio" class="form-check-input" name="menarche_stat" value="Teratur" id="menarche_stat_1">
										<label class='form-check-label' for="menarche_stat_1">Teratur</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-check">
										<input type="radio" class="form-check-input" name="menarche_stat" value="Tidak Teratur" id="menarche_stat_2">
										<label class='form-check-label' for="menarche_stat_2">Tidak Teratur</label>
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
									<div class="form-check">
										<input type="radio" class="form-check-input" name="keluhan_haid" value="0" id="keluhan_haid_1">
										<label class='form-check-label' for="keluhan_haid_1">Tidak ada</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-check col-md-1">
										<input type="radio" class="form-check-input" name="keluhan_haid" value="1" id="keluhan_haid_2">
										<label class='form-check-label' for="keluhan_haid_2">Ya</label>
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

<div class="row">
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
									<div class="form-check">
										<input type="radio" class="form-check-input" name="wanita_hamil" value="Tidak" id="wanita_hamil_0">
										<label class='form-check-label' for="wanita_hamil_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="form-check">
										<input type="radio" class="form-check-input" name="wanita_hamil" value="Ya" id="wanita_hamil_1">
										<label class='form-check-label' for="wanita_hamil_1">Ya</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="form-check">
										<input type="radio" class="form-check-input" name="wanita_hamil" value="Tidak Tahu" id="wanita_hamil_2">
										<label class='form-check-label' for="wanita_hamil_2">Tidak Tahu</label>
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
									<div class="form-check">
										<input type="radio" class="form-check-input" name="pria_prostat" value="Tidak" id="pria_prostat_0">
										<label class='form-check-label' for="pria_prostat_0">Tidak</label>
									</div>
								</div>
								<div class="col-md-3 row">
									<div class="form-check">
										<input type="radio" class="form-check-input" name="pria_prostat" value="Ya" id="pria_prostat_1">
										<label class='form-check-label' for="pria_prostat_1">Ya</label>
									</div>
								</div>
								<div class="col-md-6 row">
									<div class="form-check">
										<input type="radio" class="form-check-input" name="pria_prostat" value="Tidak Tahu" id="pria_prostat_2">
										<label class='form-check-label' for="pria_prostat_2">Tidak Tahu</label>
									</div>
								</div>
							</div>
	                    </div>
					</div>
					<div class="form-group col-lg-12 row">
						<div class="col-6 col-md-6 mb-3">
							<label for="program_kb">Keikutsertaan Program KB:</label>
							<div class="col-md-6">
								<select class="form-control inputan select2" id="program_kb">
									<option value="">Pilih</option>
									<option value="0">Tidak</option>
									<option value="1">Ya</option>
								</select>
							</div>
						</div>
						<div class="col-6 col-md-6 mb-3">
							<label for="k">Sebutkan:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <ol type="1" class="form-list-item" style="list-style-type: none">
                                        <li>
                                            <h6></h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input jenis-kb" name="program_kb_iud" value="1" id="program_kb_iud">
                                                        <label class="form-check-label" for="program_kb_iud">
                                                            IUD
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input jenis-kb" name="program_kb_pil" value="1" id="program_kb_pil">
                                                        <label class="form-check-label" for="program_kb_pil">
                                                            PIL
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-4">
                                    <ol type="1" class="form-list-item" style="list-style-type: none">
                                        <li>
                                            <h6></h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input jenis-kb" name="program_kb_susuk" value="1" id="program_kb_susuk">
                                                        <label class="form-check-label" for="program_kb_susuk">
                                                            Susuk
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input jenis-kb" name="program_kb_steril" value="1" id="program_kb_steril">
                                                        <label class="form-check-label" for="program_kb_steril">
                                                            Steril
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-4">
                                    <ol type="1" class="form-list-item" style="list-style-type: none">
                                        <li>
                                            <h6></h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input jenis-kb" name="program_kb_suntik" value="1" id="program_kb_suntik">
                                                        <label class="form-check-label" for="program_kb_suntik">
                                                            Suntik
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input jenis-kb" name="program_kb_vasectomi" value="1" id="program_kb_vasectomi">
                                                        <label class="form-check-label" for="program_kb_vasectomi">
                                                            Vasektomi
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>

							<!--<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input jenis-kb" name="program_kb_iud" value="1" id="program_kb_iud">
										<label for="program_kb_iud">IUD</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input jenis-kb" name="program_kb_susuk" value="1" id="program_kb_susuk">
										<label for="program_kb_susuk">Susuk</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input jenis-kb" name="program_kb_suntik" value="1" id="program_kb_suntik">
										<label for="program_kb_suntik">Suntik</label>
									</div>
								</div>
							</div>
							<div class="row col-md-12" id="">
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input jenis-kb" name="program_kb_pil" value="1" id="program_kb_pil">
										<label for="program_kb_pil">PIL</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input jenis-kb" name="program_kb_steril" value="1" id="program_kb_steril">
										<label for="program_kb_steril">Steril</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-check">
										<input type="checkbox" class="form-check-input jenis-kb" name="program_kb_vasectomi" value="1" id="program_kb_vasectomi">
										<label for="program_kb_vasectomi">Vasectomi</label>
									</div>
								</div>
							</div>-->
						</div>
						<div class="col-6 col-md-6 mb-3">
	                		<div class="form-group col-md-12">
	                			<label for="">Lama Pemakaian: </label>
								<input type="text" id="program_kb_lama_pemakaian" name="program_kb_lama_pemakaian" class="form-control form-control-appended inputan jenis-kb" placeholder="-">
	                		</div>
	                	</div>
	                	<div class="col-6 col-md-6 mb-3">
	                		<div class="form-group col-md-12">
	                			<label for="">Keluhan: </label>
								<input type="text" id="program_kb_keluhan" name="program_kb_keluhan" class="form-control form-control-appended inputan jenis-kb" placeholder="-">
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

<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Penyakit Ginekologi (Kebidanan):</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-2">
						<select class="form-control inputan select2" id="ginekologi_status">
							<option value="">Pilih</option>
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<select class="form-control inputan select2 ginekologi" id="ginekologi">
							<option value="">Pilih</option>
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
						<input type="text" class="form-control inputan ginekologi" disabled id="ginekologi_lainnya" name="ginekologi_lainnya" placeholder="-">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row wanita">
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
						<select class="form-control inputan select2" id="jenis_partus" name="jenis_partus">
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
						<select name="jenkel_anak" id="jenkel_anak" class="form-control inputan select2">
							<option value="">Pilih</option>
							<option value="Laki-laki">Laki-laki</option>
							<option value="Perempuan">Perempuan</option>
						</select>
						<!-- <input type="text" name="jenkel_anak" id="jenkel_anak" class="form-control inputan"> -->
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
                    <div class="col-md-12">
                        <button class="btn btn-info" id="btn_riwayat_hamil">
                            <i class="fa fa-plus"></i> Tambah Riwayat
                        </button>
                        <table class="table table-bordered" id="riwayat_hamil" style="margin-top: 10px;">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>Tgl Partus</th>
                                    <th>Usia Kehamilan</th>
                                    <th>Tempat Partus</th>
                                    <th>Jenis Partus</th>
                                    <th>Penolong</th>
                                    <th>Nifas</th>
                                    <th>J.Kelamin Anak</th>
                                    <th>Berat Badan</th>
                                    <th>Keadaan Sekarang</th>
                                    <th>Keterangan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
				<h5 class="card-header__title flex m-0">Skala Nyeri:</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-3">
						<label>Nyeri: </label>
						<select class="form-control inputan select2" id="nyeri" name="nyeri">
							<option value="">Pilih</option>
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Lokasi: </label>
						<input type="text" name="nyeri_lokasi" id="nyeri_lokasi" class="form-control inputan" placeholder="-">
					</div>
					<div class="form-group col-md-3">
						<label>Frekuensi: </label>
						<select class="form-control inputan select2" name="nyeri_frekuensi" id="nyeri_frekuensi">
							<option value="">Pilih</option>
							<option value="Sering">Sering</option>
							<option value="Kadang">Kadang</option>
							<option value="Jarang">Jarang</option>
						</select>
					</div>
					<div class="col-6 col-md-6 mb-3">
						<label for="k">Karakteristik Nyeri:</label>

                        <div class="row">
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_terbakar" value="1" id="nyeri_terbakar">
                                                    <label class="form-check-label" for="nyeri_terbakar">
                                                        Terbakar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_tumpul" value="1" id="nyeri_tumpul">
                                                    <label class="form-check-label" for="nyeri_tumpul">
                                                        Tumpul
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_tertindih" value="1" id="nyeri_tertindih">
                                                    <label class="form-check-label" for="nyeri_tertindih">
                                                        Tertindih
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_denyut" value="1" id="nyeri_denyut">
                                                    <label class="form-check-label" for="nyeri_denyut">
                                                        Berdenyut
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_menyebar" value="1" id="nyeri_menyebar">
                                                    <label class="form-check-label" for="nyeri_menyebar">
                                                        Menyebar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_tajam" value="1" id="nyeri_tajam">
                                                    <label class="form-check-label" for="nyeri_tajam">
                                                        Tajam
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-3">
                                <ol type="1" class="form-list-item" style="list-style-type: none">
                                    <li>
                                        <h6></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="nyeri_lainnya" value="1" id="nyeri_lainnya" onclick='disableCheckboxChild(this, "nyeri_lainnya_ket")'>
                                                    <label class="form-check-label" for="nyeri_lainnya">
                                                        Lainnya
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
						<!--<div class="row col-md-12" id="">
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_terbakar" value="1" id="nyeri_terbakar">
									<label for="nyeri_terbakar">Terbakar</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_tertindih" value="1" id="nyeri_tertindih">
									<label for="nyeri_tertindih">Tertindih</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_menyebar" value="1" id="nyeri_menyebar">
									<label for="nyeri_menyebar">Menyebar</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_tajam" value="1" id="nyeri_tajam">
									<label for="nyeri_tajam">Tajam</label>
								</div>
							</div>
						</div>
						<div class="row col-md-12" id="">
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_tumpul" value="1" id="nyeri_tumpul">
									<label for="nyeri_tumpul">Tumpul</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_denyut" value="1" id="nyeri_denyut">
									<label for="nyeri_denyut">Berdenyut</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="nyeri_lainnya" value="1" id="nyeri_lainnya" onclick='disableCheckboxChild(this, "nyeri_lainnya_ket")'>
									<label for="nyeri_lainnya">Lainnya</label>
								</div>
							</div>
						</div>-->
					</div>
					<div class="form-group col-md-3">
						<label>Nyeri Lainnya: </label>
						<input disabled type="text" name="nyeri_lainnya_ket" id="nyeri_lainnya_ket" class="form-control inputan nyeri_lainnya_ket" placeholder="-">
					</div>
					<div class="form-group col-md-4">
						<label>Skala Nyeri NRS ( &gt; 5th - Dewasa)</label>
						<input placeholder="-" type="text" id="nyeri_skala" name="nyeri_skala" class="form-control inputan" />
					</div>
					<div class="form-group col-md-3">
						<label>Total Skor: </label>
						<input type="text" placeholder="-" name="nyeri_total_skor" id="nyeri_total_skor" class="form-control inputan">
					</div>
					<div class="form-group col-md-3">
						<label>Tipe: </label>
						<select class="form-control inputan select2" name="nyeri_tipe" id="nyeri_tipe">
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

<!-- <script type="text/javascript">
	/*var sliderNyeri = new rSlider({
        target: '#skala_nyeri',
        values: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        range: false,
        set: [0],
        tooltip: false,
        onChange: function (vals) {
            console.log(vals);
        }
    });*/
</script> -->