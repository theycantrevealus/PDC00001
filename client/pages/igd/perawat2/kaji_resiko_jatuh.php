<p><h4>Pengkajian Resiko Jatuh</h4></p>
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
				<h5 class="card-header__title flex m-0">Pengkajian Resiko Jatuh Pasien Dewasa (Skala Morse)</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengkajian</th>
                                <th width="30%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <p>Riwayat Jatuh : Apakah pasien pernah jatuh dalam 3 bulan terakhir?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_dewasa_pernah_jatuh" id="resiko_jatuh_dewasa_pernah_jatuh" class="form-control table_resiko_jatuh_dewasa">
                                        <option value="0">Tidak (0)</option>
                                        <option value="25">Ya (25)</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>2</td>
                                <td>
                                    <p>Diagnosa sekunder lebih dari 1</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_dewasa_diagnosa_sekunder" id="resiko_jatuh_dewasa_diagnosa_sekunder" class="form-control table_resiko_jatuh_dewasa">
                                        <option value="0">Tidak (0)</option>
                                        <option value="15">Ya (15)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>
                                    <p>Alat bantu jalan</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_dewasa_alat_bantu_jalan" id="resiko_jatuh_dewasa_alat_bantu_jalan" class="form-control table_resiko_jatuh_dewasa">
                                        <option value="0">Mandiri (0)</option>
                                        <option value="15">Penopang tongkat / walker (15)</option>
                                        <option value="30">Tidak bisa jalan/ imobilisasi/ kursi roda (30)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>
                                    <p>Terapi intervena : apakah saat ini pasien terpasang infus?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_dewasa_infus" id="resiko_jatuh_dewasa_diagnosa_sekunder" class="form-control table_resiko_jatuh_dewasa">
                                        <option value="0">Tidak (0)</option>
                                        <option value="15">Ya (15)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>
                                    <p>Gaya berjalan / cara pindah</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_dewasa_gaya_jalan" id="resiko_jatuh_dewasa_gaya_jalan" class="form-control table_resiko_jatuh_dewasa">
                                        <option value="0">Normal (0)</option>
                                        <option value="10">Lemah / pincang (10)</option>
                                        <option value="20">Tidak bisa jalan (20)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>
                                    <p>Status mental</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_dewasa_status_mental" id="resiko_jatuh_dewasa_status_mental" class="form-control table_resiko_jatuh_dewasa">
                                        <option value="0">Orientasi sesuai kemampuan diri (0)</option>
                                        <option value="15">Lupa keterbatasan diri (15)</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <td colspan="2"><b>TOTAL SKOR</b></td>
                                <td>
                                    <span id="total_risiko_jatuh_skor_dewasa"></span>
                                </td>
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
				<h5 class="card-header__title flex m-0">Pengkajian Resiko Jatuh Pasien Anak (Skala Humpty Dumpty)</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengkajian</th>
                                <th width="30%">Keterangan (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <p>Usia</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_anak_usia" id="resiko_jatuh_anak_usia" class="form-control table_resiko_jatuh_anak">
                                        <option value="4">< 3 Tahun (4)</option>
                                        <option value="3">3 - 7 tahun (3)</option>
                                        <option value="2">7 - 13 tahun (2)</option>
                                        <option value="1">> 13 tahun (1)</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>2</td>
                                <td>
                                    <p>Jenis Kelamin</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_anak_jenkel" id="resiko_jatuh_anak_jenkel" class="form-control table_resiko_jatuh_anak">
                                        <option value="2">Laki-laki (2)</option>
                                        <option value="1">Perempuan (1)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>
                                    <p>Diagnosa</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_anak_diagnosa" id="resiko_jatuh_anak_diagnosa" class="form-control table_resiko_jatuh_anak">
                                        <option value="4">Diagnosa neurologi (4)</option>
                                        <option value="3">Perubahan oksigen (3)</option>
                                        <option value="2">Gangguan perilaku/ psikiatri (2)</option>
                                        <option value="1">Diagnosa lainnya (1)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>
                                    <p>Faktor Lingkungan</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_anak_faktor_lingkungan" id="resiko_jatuh_anak_faktor_lingkungan" class="form-control table_resiko_jatuh_anak">
                                        <option value="4">Riwayat jauh / bayi diletakkan ditempat tidur dewasa (4)</option>
                                        <option value="3">Pasien menggunakan alat bantu (3)</option>
                                        <option value="2">Pasien diletakkan di tempat tidur (2)</option>
                                        <option value="1">Area di luar rumah sakit (1)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>
                                    <p>Pembedahan/ Sadasi/ Anestesi</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_anak_pembedahan" id="resiko_jatuh_anak_pembedahan" class="form-control table_resiko_jatuh_anak">
                                        <option value="4">Dalam 24 jam (3)</option>
                                        <option value="2">Dalam 48 jam (2)</option>
                                        <option value="1"> > 48 jam (1)</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td>
                                    <p>Penggunaan Medikamentosa (sedatif, obat hipnosis, barbiturat, fenotiazin, antidepresan, pencahar, diuretik, narkose</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_anak_penggunaan_medikamentosa" id="resiko_jatuh_anak_penggunaan_medikamentosa" class="form-control table_resiko_jatuh_anak">
                                        <option value="3">Penggunaan multiple obat yg ada di keterangan di samping</option>
                                        <option value="2">Penggunaan salah satu obat disamping (2)</option>
                                        <option value="1">Penggunaan medikasi lainnya/ tidak ada medikasi (1)</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <td colspan="2"><b>TOTAL SKOR</b></td>
                                <td>
                                    <span id="total_risiko_jatuh_skor_anak"></span>
                                </td>
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
				<h5 class="card-header__title flex m-0">Pengkajian Resiko Jatuh Pasien Lansia (Sydney Scoring)</h5>
			</div>
			<div class="card-body ">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Parameter</th>
                                <th>Skrining</th>
                                <th width="20%">Keterangan</th>
                                <th width="10%">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="2">1</td>
                                <td rowspan="2">
                                    <p>Usia</p>
                                </td>
                                <td>
                                    <p>Apakah pasien datang kerumah sakit karena jatuh?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_datang_karna_jatuh" id="resiko_jatuh_lansia_datang_karna_jatuh" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="6">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Jika tidak, apakah pasien mengalami jatuh dalam 2 bulan terakhir?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pernah_jatuh" id="resiko_jatuh_lansia_pernah_jatuh" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="6">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            

                            <tr>
                                <td rowspan="3">2</td>
                                <td rowspan="3">
                                    <p>Status Mental</p>
                                </td>
                                <td>
                                    <p>Apakah pasien delirium? (tidak dapat membuat keputusan, pola pikir tidak tergorganisir, gangguan daya ingat)</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pasien_delirium" id="resiko_jatuh_lansia_pasien_delirium" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="14">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Apakah pasien disorientasi? (salah menyebutkan waktu, tempat atau orang)</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pasien_disorientasi" id="resiko_jatuh_lansia_pasien_disorientasi" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="14">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Apakah pasien mengalami agitasi? (ketakutan gelisah dan cemas)</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pasien_agitasi" id="resiko_jatuh_lansia_pasien_agitasi" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="14">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>


                            <tr>
                                <td rowspan="3">3</td>
                                <td rowspan="3">
                                    <p>Penglihatan</p>
                                </td>
                                <td>
                                    <p>Apakah pasien memakai kacamata?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pakai_kacamata" id="resiko_jatuh_lansia_pakai_kacamata" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Apakah pasien mengeluh ada penglihatan buram?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_penglihatan_buram" id="resiko_jatuh_lansia_penglihatan_buram" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Apakah pasien mempunyai glukoma, katarak, atau degenerasi makula?</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_glukoma_katarak" id="resiko_jatuh_lansia_glukoma_katarak" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>


                            <tr>
                                <td>4</td>
                                <td>
                                    <p>Kebiasaan berkemih</p>
                                </td>
                                <td>
                                    <p>Apakah terdapat perubahan perilaku berkemih? (frekuensi, urgensi, inkontinensia, nokturia)</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_perilaku_berkemih" id="resiko_jatuh_lansia_perilaku_berkemih" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Tidak</option>
                                        <option value="2">Ya</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>


                            <tr>
                                <td>5</td>
                                <td>
                                    <p>Transfer (dari tempat tidur ke kursi dan kembali ketempat tidur)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Skor adalah jumlah nilai transfer dan mobilitas</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pasien_transfer" id="resiko_jatuh_lansia_pasien_transfer" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Mandiri (boleh menggunakan alat bantu makan)</option>
                                        <option value="1">Memerlukan sedikit bantuan (1 orang) atau dalam pengawasan</option>
                                        <option value="2">Memerlukan bantuan yang nyata (2 orang)</option>
                                        <option value="3">Tidak dapat duduk dengan seimbang, perlu bantuan total</option>
                                    </select>
                                </td>
                                <td rowspan="2"></td>
                            </tr>
                            

                            <tr>
                                <td>6</td>
                                <td>
                                    <p>Mobilitas</p>
                                </td>
                                <td>
                                    <select name="resiko_jatuh_lansia_pasien_mobilitas" id="resiko_jatuh_lansia_pasien_mobilitas" class="form-control table_resiko_jatuh_lansia">
                                        <option value="0">Mandiri (boleh menggunakan alat bantu makan)</option>
                                        <option value="1">Berjalan dengan bantuan 1 orang (verbal/ fisik)</option>
                                        <option value="2">Menggunakan kursi roda</option>
                                        <option value="3">Immobilisasi</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <td colspan="4"><b>TOTAL SKOR</b></td>
                                <td>
                                    <span id="total_risiko_jatuh_skor_anak"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>