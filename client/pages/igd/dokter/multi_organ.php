<p><h4>Pengkajian Multi Organ</h4></p>
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
	<div class="col-md-12">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Kepala dan Leher:</h5>
			</div>
			<div id="txt_multi_organ_kepala_leher" class="txt_multi_organ_kepala_leher"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Dada dan Punggung</h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				<a href="#multi-organ-paru" class="active" data-toggle="tab" role="tab" aria-controls="multi-organ-paru" aria-selected="true">Paru</a>
				<a href="#multi-organ-jantung" data-toggle="tab" role="tab" aria-selected="false">Jantung</a>
			</div>
			<div class="card-body tab-content">
                <p style="color:#ff1d1d; text-size: 10pt;">(inspeksi, palpasi, perkusi, auskultasi)</p>
				<div class="tab-pane active show fade" id="multi-organ-paru">
					<div id="txt_multi_organ_paru" class="txt_multi_organ_paru"></div>
				</div>
				<div class="tab-pane show fade" id="multi-organ-jantung">
					<div id="txt_multi_organ_jantung" class="txt_multi_organ_jantung"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Perut dan Pinggang:</h5>
			</div>
			<div id="txt_perut_multi_organ_perut_pinggang" class="txt_perut_multi_organ_perut_pinggang"></div>
		</div>
	</div>

    <div class="col-md-12">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white align-items-center">
				<h5 class="card-header__title flex m-0">Anggota Gerak:</h5>
                <p style="color:#8d8d8d; text-size: 10pt;">(termasuk sendi dan kuku)</p>
			</div>
            <div>               
			    <div id="txt_multi_organ_anggota_gerak" class="txt_multi_organ_anggota_gerak"></div>
            </div>
		</div>
	</div>

    <div class="col-md-12">
		<div class="card" style="padding:0 2% 2% 2%;">
			<div class="card-header card-header-large bg-white align-items-center">
				<h5 class="card-header__title flex m-0">Genitalia dan Anus:</h5>
                <p style="color:#8d8d8d; text-size: 10pt;">(diperiksa bila ada indikasi)</p>
			</div>
			<div id="txt_multi_organ_genitalia_anus" class="txt_multi_organ_genitalia_anus"></div>
		</div>
	</div>
</div>