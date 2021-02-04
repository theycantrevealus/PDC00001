<p><h4>Pengkajian Awal Pasien Terintegrasi Rawat Inap</h4></p>
<p><i><h6>(wajib dilengkapi dalam 24 jam pertama pasien masuk ruang rawat)</h6></i></p>
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
				<h5 class="card-header__title flex m-0">Informasi Rawat Inap</h5>
			</div>
			<div class="card-body ">
				<div class="row">
					<div class="col-md-12 row form-group">
						<div class="col-md-5">
							<label>Dokter Penanggung Jawab Pasien</label>
						</div>
						<div class="col-md-7">
							<input type="" name="pj_pasien" id="pj_pasien" disabled class="form-control " value="">
						</div>
					</div>
					<div class="col-md-12 row form-group">
						<div class="col-md-5">
							<label>Data diperoleh dari pasien/orang lain, hubungan dengan pasien</label>
						</div>
						<div class="col-md-7">
							<input type="" name="info_didapat_dari" id="info_didapat_dari" class="form-control " value="">
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Keluhan Utama:</h5>
			</div>
			<div id="txt_keluhan_utama" class="txt_keluhan_utama"></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Riwayat Penyakit Sekarang:</span></h5>
			</div>
			<div id="txt_riwayat_sekarang" class="txt_riwayat_sekarang"></div>
		</div>
	</div>
</div>
<hr />

<div class="row">
	<div class="col-lg">
		<div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"></h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				<a href="#riwayat-penyakit-dahulu" class="active" data-toggle="tab" role="tab" aria-controls="riwayat-penyakit-dahulu" aria-selected="true">Riwayat Penyakit Terdahulu</a>
				<a href="#riwayat-pengobatan" data-toggle="tab" role="tab" aria-selected="false">Riwayat Pengobatan</a>
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="riwayat-penyakit-dahulu">
                    <p style="color:#ff1d1d; text-size: 10pt;">* termasuk riwayat rawat inap/ riwayat operasi</p>
					<div id="txt_riwayat_sakit_terdahulu" class="txt_riwayat_sakit_terdahulu"></div>
				</div>
				<div class="tab-pane show fade" id="riwayat-pengobatan">
					<div id="txt_riwayat_pengobatan" class="txt_riwayat_pengobatan"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"></h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				<a href="#riwayat-sakit-keluarga" class="active" data-toggle="tab" role="tab" aria-controls="riwayat-sakit-keluarga" aria-selected="true">Riwayat Penyakit Dalam Keluarga</a>
				<a href="#riwayat-pekerjaan-sosial-ekonomi" data-toggle="tab" role="tab" aria-selected="false">Riwayat Pekerjaan, Sosial Ekonomi, Kejiwaan, dan Kebiasaan</a>
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="riwayat-sakit-keluarga">
                    <p style="color:#ff1d1d; text-size: 8pt;">(termasuk penyakit keturunan, penyakit menular dalam keluarga)</p>
					<div id="txt_riwayat_sakit_keluarga" class="txt_riwayat_sakit_keluarga"></div>
				</div>
				<div class="tab-pane show fade" id="riwayat-pekerjaan-sosial-ekonomi">
                    <p style="color:#ff1d1d; text-size: 8pt;">(termasuk riwayat perkawinan, obstetri, imunisasi dan tumbuh kembang)</p>
					<div id="txt_riwayat_pekerjaan_sosial_ekonomi" class="txt_riwayat_pekerjaan_sosial_ekonomi"></div>
				</div>
			</div>
		</div>
	</div>
</div>