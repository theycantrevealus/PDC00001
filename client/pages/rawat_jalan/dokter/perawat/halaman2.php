<!-- <div class="row">
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
 -->

<!-- <div class="row" style="margin-top: 20px;">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Pengkajian Risiko Jatuh/ Up and Go:</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-3">
						<label>Tanggal Partus: </label>
						<input type="date" name="tgl_partus" id="tgl_partus" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Usia Kehamilan: </label>
						<input type="text" name="usia_hamil" id="usia_hamil" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Tempat Partus: </label>
						<input type="text" name="tempat_partus" id="tempat_partus" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Jenis Partus: </label>
						<input type="text" name="jenis_partus" id="jenis_partus" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Penolong: </label>
						<input type="text" name="penolong_partus" id="penolong_partus" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Nifas: </label>
						<input type="text" name="nifas" id="nifas" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Jenis Kelamin Anak: </label>
						<input type="text" name="jenkel_anak" id="jenkel_anak" class="form-control">
					</div>
					<div class="form-group col-md-3">
						<label>Berat Badan Anak: </label>
						<input type="text" name="bb_anak" id="bb_anak" class="form-control">
					</div>
					<div class="form-group col-md-6">
						<label>Keadaan Sekarang: </label>
						<input type="text" name="keadaan_anak" id="keadaan_anak" class="form-control">
					</div>
					<div class="form-group col-md-6">
						<label>Keterangan: </label>
						<input type="text" name="keterangan_anak" id="keterangan_anak" class="form-control">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 -->

<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Psikososial	:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Psikososial:</label>
                        <select class="form-control inputan select2" id="psikososial" name="psikososial">
                            <option value="">Pilih</option>
                            <option value="Tenang">Tenang</option>
                            <option value="Takut">Takut</option>
                            <option value="Marah">Marah</option>
                            <option value="Sedih">Sedih</option>
                            <option value="Cemas">Cemas</option>
                            <option value="Menangis">Menangis</option>
                            <option value="Gelisah">Gelisah</option>
                            <option value="Mudah Tersinggung">Mudah Tersinggung</option>
                            <option value="Membahayakan">Membahayakan diri sendiri / orang lain</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Hubungan pasien keluarga:</label>
                        <select class="form-control inputan select2" id="psikososial_hub_keluarga" name="psikososial_hub_keluarga">
                            <option value="">Pilih</option>
                            <option value="Baik">Baik</option>
                            <option value="Tidak Baik">Tidak Baik</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Aktifitas Sosial:</label>
                        <select class="form-control inputan select2" id="psikososial_aktifitas_sosial" name="psikososial_aktifitas_sosial">
                            <option value="">Pilih</option>
                            <option value="0">Tidak Ada</option>
                            <option value="1">Ada</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><i>Care Giver </i> (Pelaku Rawat)</label>
                        <select class="form-control inputan select2" id="psikososial_pelaku_rawat" name="psikososial_pelaku_rawat">
                            <option value="">Pilih</option>
                            <option value="Sendiri">Sendiri</option>
                            <option value="Keluarga">Keluarga</option>
                            <option value="Perawat Khusus">Perawat Khusus</option>
                            <option value="Pekerja Sosial">Pekerja Sosial</option>
                            <option value="Panti Asuhan/Jompo">Panti Asuhan/Jompo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Eliminasi:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="">BAB:</label>
                        <select id="eliminasi_bab" nama="eliminasi_bab" class="form-control inputan select2">
                            <option value="">Pilih</option>
                            <option value="Normal">Normal</option>
                            <option value="Gangguang Bicara">Diare</option>
                            <option value="Konstipasi">Konstipasi</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Frekuensi BAB / hari:</label>
                        <input type="text" class="form-control inputan" name="eliminasi_frekuensi_bab" id="eliminasi_frekuensi_bab" placeholder="-">
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="eliminasi_colostomy" value="1" id="eliminasi_colostomy">
                            <label class="form-check-label" for="eliminasi_colostomy">
                                Colostomy
                            </label>
                        </div>
                        <!--<div class="form-check">
                            <input type="checkbox" class="form-check-input" name="eliminasi_colostomy" value="1" id="eliminasi_colostomy">
                            <label for="eliminasi_colostomy">Colostomy</label>
                        </div>-->
                    </div>
                    <br />
                    <br />
                    <div class="col-md-6 form-group">
                        <label for="">BAK:</label>
                        <select id="eliminasi_bak" name="eliminasi_bak" class="form-control inputan select2">
                            <option value="">Pilih</option>
                            <option value="Normal">Normal</option>
                            <option value="Inkontineisia">Inkontinensia</option>
                            <option value="Disuria">Disuria</option>
                            <option value="Retensia">Retensia</option>
                            <option value="Poliuria">Poloiuria</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Lainnya:</label>
                        <input type="text" disabled class="form-control inputan eliminasi_bak_lainnya" name="eliminasi_bak_lainnya" id="eliminasi_bak_lainnya" placeholder="-">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Skrining Gizi:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>1. Penurunan selera makan</label>
                        <select class="form-control inputan select2" id="skrining_selera_makan">
                            <option value="">Pilih</option>
                            <option value="0">Tidak Ada</option>
                            <option value="1">Ya / Ada penurunan</option>
                        </select>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>2. Mengalami penurunan berat badan yang tidak diinginkan dalam 3 bulan terakhir</label>
                        <select class="form-control inputan select2" id="skrining_turun_berat">
                            <option value="">Pilih</option>
                            <option value="0">Tidak Ada</option>
                            <option value="1">Ya / Ada penurunan</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Penurunan berat badan</label>
                        <select class="form-control inputan select2" id="skrining_nilai_turun_berat">
                            <option value="">Pilih</option>
                            <option value="1">1 - 5 kg</option>
                            <option value="2">6 - 10 kg</option>
                            <option value="3">11 - 15 kg</option>
                            <option value="4"> > 6kg</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Kebutuhan Komunikasi/ Pendidikan dan Pengajaran:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="">Bicara:</label>
                        <select id="komunikasi_bicara" class="form-control inputan select2">
                            <option value="">Pilih</option>
                            <option value="Normal">Normal</option>
                            <option value="Gangguang Bicara">Gangguan Bicara</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Lainnya:</label>
                        <input type="text" class="form-control inputan komunikasi_bicara_lainnya" name="komunikasi_bicara_lainnya" id="komunikasi_bicara_lainnya" disabled placeholder="-">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Hambatan Belajar:</label>
                        <select id="komunikasi_hambatan" name="komunikasi_hambatan" class="form-control inputan select2">
                            <option value="">Pilih</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Pendengaran">Pendengaran</option>
                            <option value="Cemas">Cemas</option>
                            <option value="Motivasi Memburuk">Motivasi Memburuk</option>
                            <option value="Bahasa">Bahasa</option>
                            <option value="Tidak">Tidak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Lainnya:</label>
                        <input type="text" class="form-control inputan komunikasi_hambatan_lainnya" name="komunikasi_hambatan_lainnya" id="komunikasi_hambatan_lainnya" disabled placeholder="-">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Potensi Kebutuhan Belajar:</label>
                        <select id="komunikasi_kebutuhan_belajar" name="komunikasi_kebutuhan_belajar" class="form-control inputan select2">
                            <option value="">Pilih</option>
                            <option value="Proses Penyakit">Proses Penyakit</option>
                            <option value="Pengobatan">Pengobatan</option>
                            <option value="Nutrisi">Nutrisi</option>
                            <option value="Tindakan">Tindakan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Lainnya:</label>
                        <input disabled type="text" class="form-control inputan komunikasi_kebutuhan_belajar_lainnya" name="komunikasi_kebutuhan_belajar_lainnya" id="komunikasi_kebutuhan_belajar_lainnya" placeholder="-">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Pengkajian Risiko Jatuh/Up and Go:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">1. Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak tidak seimbang (sempoyongan) ?</label>
                        <div class="row col-md-12" id="cara_masuk">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_resiko_sempoyongan" value="0" id="kaji_resiko_sempoyongan_0">
                                    <label class='form-check-label' for="kaji_resiko_sempoyongan_0">Tidak</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_resiko_sempoyongan" value="1" id="kaji_resiko_sempoyongan_1">
                                    <label class='form-check-label' for="kaji_resiko_sempoyongan_1">Ya</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="">2. Apakah pasien memegang pinggiran kursi/meja/benda lain sebagai penopang saat akan duduk ?</label>
                        <div class="row col-md-12" id="cara_masuk">
                            <div class="col-md-3	">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_resiko_penopang" value="0" id="kaji_resiko_penopang_0">
                                    <label class='form-check-label' for="kaji_resiko_penopang_0">Tidak</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_resiko_penopang" value="1" id="kaji_resiko_penopang_1">
                                    <label class='form-check-label' for="kaji_resiko_penopang_1">Ya</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="">Diberitahukan ke Dokter:</label>
                        <div class="row col-md-12" id="cara_masuk">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_resiko_ke_dokter" value="0" id="kaji_resiko_ke_dokter_0">
                                    <label class='form-check-label' for="kaji_resiko_ke_dokter_0">Tidak</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="kaji_resiko_ke_dokter" value="1" id="kaji_resiko_ke_dokter_1">
                                    <label class='form-check-label' for="kaji_resiko_ke_dokter_1">Ya, Jam : </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input disabled type="time" name="kaji_resiko_jam_dokter" id="kaji_resiko_jam_dokter" class="form-control inputan kaji_resiko_jam_dokter">
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
                <h5 class="card-header__title flex m-0">Diagnosa Keperawatan:</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item" style="list-style-type: none">
                    <li class="wrapped">
                        <label>&nbsp;</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_nyeri" value="1" id="diagnosa_nyeri">
                                    <label class="form-check-label" for="diagnosa_nyeri">
                                        Nyeri
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_kulit" value="1" id="diagnosa_kulit">
                                    <label class="form-check-label" for="diagnosa_kulit">
                                        Integritas Kulit
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_cairan" value="1" id="diagnosa_cairan">
                                    <label class="form-check-label" for="diagnosa_cairan">
                                        Keseimbangan Cairan & Elektrolit
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_pola_tidur" value="1" id="diagnosa_pola_tidur">
                                    <label class="form-check-label" for="diagnosa_pola_tidur">
                                        Pola Tidur
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_suhu" value="1" id="diagnosa_suhu">
                                    <label class="form-check-label" for="diagnosa_suhu">
                                        Suhu Tubuh
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_perifer" value="1" id="diagnosa_perifer">
                                    <label class="form-check-label" for="diagnosa_perifer">
                                        Perfusi Jaringan Perifer/Cerebral
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_mobilitas" value="1" id="diagnosa_mobilitas">
                                    <label class="form-check-label" for="diagnosa_mobilitas">
                                        Mobilitas / Aktivitas
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_eliminasi" value="1" id="diagnosa_eliminasi">
                                    <label class="form-check-label" for="diagnosa_eliminasi">
                                        Eliminasi
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_nafas" value="1" id="diagnosa_nafas">
                                    <label class="form-check-label" for="diagnosa_nafas">
                                        Jalan nafas/pertukaran gas/ Bersihan Jalan Nafas
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_rawat_diri" value="1" id="diagnosa_rawat_diri">
                                    <label class="form-check-label" for="diagnosa_rawat_diri">
                                        Perawatan Diri
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="diagnosa_nutrisi" value="1" id="diagnosa_nutrisi">
                                    <label class="form-check-label" for="diagnosa_nutrisi">
                                        Nutrisi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ol>
                <!--<div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_nyeri" value="1" id="diagnosa_nyeri">
                                <label for="diagnosa_nyeri">Nyeri</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_pola_tidur" value="1" id="diagnosa_pola_tidur">
                                <label for="diagnosa_pola_tidur">Pola Tidur</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_mobilitas" value="1" id="diagnosa_mobilitas">
                                <label for="diagnosa_mobilitas">Mobilitas / Aktivitas</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_cedera" value="1" id="diagnosa_cedera">
                                <label for="diagnosa_cedera">Risiko Cidera / Jatuh</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_rawat_diri" value="1" id="diagnosa_rawat_diri">
                                <label for="diagnosa_rawat_diri">Perawatan Diri</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_kulit" value="1" id="diagnosa_kulit">
                                <label for="diagnosa_kulit">Integritas Kulit</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_suhu" value="1" id="diagnosa_suhu">
                                <label for="diagnosa_suhu">Suhu Tubuh</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_eliminasi" value="1" id="diagnosa_eliminasi">
                                <label for="diagnosa_eliminasi">Eliminasi</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_pengetahuan" value="1" id="diagnosa_pengetahuan">
                                <label for="diagnosa_pengetahuan">Pengetahuan/Komunikasi</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_nutrisi" value="1" id="diagnosa_nutrisi">
                                <label for="diagnosa_nutrisi">Nutrisi</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_cairan" value="1" id="diagnosa_cairan">
                                <label for="diagnosa_cairan">Keseimbangan Cairan & Elektrolit</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_perifer" value="1" id="diagnosa_perifer">
                                <label for="diagnosa_perifer">Perfusi Jaringan Perifer/Cerebral</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_nafas" value="1" id="diagnosa_nafas">
                                <label for="diagnosa_nafas">Jalan nafas/pertukaran gas/ Bersihan Jalan Nafas</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="diagnosa_infeksi" value="1" id="diagnosa_infeksi">
                                <label for="diagnosa_infeksi">Infeksi</label>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Penatalaksanaan:</h5>
            </div>
            <div class="card-body">
                <ol type="1" class="form-list-item" style="list-style-type: none">
                    <li class="wrapped">
                        <label>&nbsp;</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_hub_baik" value="1" id="tatalaksana_hub_baik">
                                    <label class="form-check-label" for="tatalaksana_hub_baik">
                                        Membina hubungan baik dengan pasien dan keluarga
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_timbang" value="1" id="tatalaksana_timbang">
                                    <label class="form-check-label" for="tatalaksana_timbang">
                                        Menimbang Berat Badan
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_bersih_luka" value="1" id="tatalaksana_bersih_luka">
                                    <label class="form-check-label" for="tatalaksana_bersih_luka">
                                        Membersihkan Luka
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_terapeutik" value="1" id="tatalaksana_terapeutik">
                                    <label class="form-check-label" for="tatalaksana_terapeutik">
                                        Komunikasi terapeutik
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_ukur_tinggi" value="1" id="tatalaksana_ukur_tinggi">
                                    <label class="form-check-label" for="tatalaksana_ukur_tinggi">
                                        Mengukur Tinggi Badan
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_buka_jahit" value="1" id="tatalaksana_buka_jahit">
                                    <label class="form-check-label" for="tatalaksana_buka_jahit">
                                        Membuka jahitan
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_lingkungan" value="1" id="tatalaksana_lingkungan">
                                    <label class="form-check-label" for="tatalaksana_lingkungan">
                                        Meningkatkan lingkungan yang nyaman
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_kaji_vital" value="1" id="tatalaksana_kaji_vital">
                                    <label class="form-check-label" for="tatalaksana_kaji_vital">
                                        Mengkaji Tanda-tanda Vital
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_suction" value="1" id="tatalaksana_suction">
                                    <label class="form-check-label" for="tatalaksana_suction">
                                        Melakukan Suction
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_oral" value="1" id="tatalaksana_oral">
                                    <label class="form-check-label" for="tatalaksana_oral">
                                        Melakukan Oral Higiene
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="tatalaksana_insisi" value="1" id="tatalaksana_insisi">
                                    <label class="form-check-label" for="tatalaksana_insisi">
                                        Melakukan Insisi
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input onclick='disableCheckboxChild(this, "tatalaksana_siapkan_obat_ket")' type="checkbox" class="form-check-input" name="tatalaksana_siapkan_obat" value="1" id="tatalaksana_siapkan_obat">
                                    <label class="form-check-label" for="tatalaksana_siapkan_obat">
                                        Menyiapkan Obat
                                    </label>
                                </div>
                                <input type="text" disabled name="tatalaksana_siapkan_obat_ket" id="tatalaksana_siapkan_obat_ket" class="form-control inputan tatalaksana_siapkan_obat_ket" placeholder="-">
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" onclick='disableCheckboxChild(this, "tatalaksana_beri_obat_ket")' class="form-check-input" name="tatalaksana_beri_obat" value="1" id="tatalaksana_beri_obat">
                                    <label class="form-check-label" for="tatalaksana_beri_obat">
                                        Memberikan Obat
                                    </label>
                                </div>
                                <input disabled type="text" name="tatalaksana_beri_obat_ket" id="tatalaksana_beri_obat_ket" class="form-control inputan tatalaksana_beri_obat_ket" placeholder="-">
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" onclick='disableCheckboxChild(this, "tatalaksana_konsul_ket")' class="form-check-input" name="tatalaksana_konsul" value="1" id="tatalaksana_konsul">
                                    <label class="form-check-label" for="tatalaksana_konsul">
                                        Konsultasi
                                    </label>
                                </div>
                                <input disabled type="text" name="tatalaksana_konsul_ket" id="tatalaksana_konsul_ket" class="form-control inputan tatalaksana_konsul_ket" placeholder="-">
                            </div>
                        </div>
                    </li>
                </ol>



                <!--<div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_hub_baik" value="1" id="tatalaksana_hub_baik">
                                <label for="tatalaksana_hub_baik">Membina hubungan baik dengan pasien dan keluarga</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_terapeutik" value="1" id="tatalaksana_terapeutik">
                                <label for="tatalaksana_terapeutik">Komunikasi terapeutik</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_lingkungan" value="1" id="tatalaksana_lingkungan">
                                <label for="tatalaksana_lingkungan">Meningkatkan lingkungan yang nyaman</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_timbang" value="1" id="tatalaksana_timbang">
                                <label for="tatalaksana_timbang">Menimbang Berat Badan</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_ukur_tinggi" value="1" id="tatalaksana_ukur_tinggi">
                                <label for="tatalaksana_ukur_tinggi">Mengukur Tinggi Badan</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_kaji_vital" value="1" id="tatalaksana_kaji_vital">
                                <label for="tatalaksana_kaji_vital">Mengkaji Tanda-tanda Vital</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_oral" value="1" id="tatalaksana_oral">
                                <label for="tatalaksana_oral">Melakukan Oral Higiene</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_bersih_luka" value="1" id="tatalaksana_bersih_luka">
                                <label for="tatalaksana_bersih_luka">Membersihkan Luka</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_buka_jahit" value="1" id="tatalaksana_buka_jahit">
                                <label for="tatalaksana_buka_jahit">Membuka jahitan</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_suction" value="1" id="tatalaksana_suction">
                                <label for="tatalaksana_suction">Melakukan Suction</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tatalaksana_insisi" value="1" id="tatalaksana_insisi">
                                <label for="tatalaksana_insisi">Melakukan Insisi</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input onclick='disableCheckboxChild(this, "tatalaksana_siapkan_obat_ket")' type="checkbox" class="form-check-input" name="tatalaksana_siapkan_obat" value="1" id="tatalaksana_siapkan_obat">
                                <label for="tatalaksana_siapkan_obat">Menyiapkan Obat</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="text" disabled name="tatalaksana_siapkan_obat_ket" id="tatalaksana_siapkan_obat_ket" class="form-control inputan tatalaksana_siapkan_obat_ket" placeholder="-">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" onclick='disableCheckboxChild(this, "tatalaksana_beri_obat_ket")' class="form-check-input" name="tatalaksana_beri_obat" value="1" id="tatalaksana_beri_obat">
                                <label for="tatalaksana_beri_obat">Memberikan Obat</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input disabled type="text" name="tatalaksana_beri_obat_ket" id="tatalaksana_beri_obat_ket" class="form-control inputan tatalaksana_beri_obat_ket" placeholder="-">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" onclick='disableCheckboxChild(this, "tatalaksana_konsul_ket")' class="form-check-input" name="tatalaksana_konsul" value="1" id="tatalaksana_konsul">
                                <label for="tatalaksana_konsul">Konsultasi: </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input disabled type="text" name="tatalaksana_konsul_ket" id="tatalaksana_konsul_ket" class="form-control inputan tatalaksana_konsul_ket" placeholder="-">
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>


<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Tindak Lanjut:</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Rencana Tindak Lanjut</label>
                        <select class="form-control inputan select2" id="tindak_lanjut" name="tindak_lanjut">
                            <option value="">Pilih</option>
                            <option value="Rawat Inap">Rawat Inap</option>
                            <option value="Rujuk">Rujuk</option>
                            <option value="Pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="col-md-8 form-group">
                        <label>Keterangan Tindak Lanjut:</label>
                        <textarea rows="2" name="tindak_lanjut_ket" id="tindak_lanjut_ket" class="form-control inputan"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>