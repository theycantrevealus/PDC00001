<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0"> Informasi Pasien</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
					<table class="table form-mode">
						<tbody>
							<tr>
								<td class="wrap_content">No. Rekam Medis</td>
								<td class="wrap_content"> : </td>
								<td><b><span id="no_rm"></span></b></td>
								<td class="wrap_content">Tanggal Lahir</td>
								<td class="wrap_content"> : </td>
								<td><b><span id="tanggal_lahir"></span></b></td>
							</tr>
							<tr>
								<td class="wrap_content">Nama Pasien</td>
								<td class="wrap_content"> : </td>
								<td><b><span id="panggilan"></span> <span id="nama"></span> </b></td>
								<td class="wrap_content">Jenis Kelamin</td>
								<td class="wrap_content"> : </td>
								<td><b><span id="jenkel"></span></b></td>
							</tr>
							<!-- <tr>
								<td>Pemeriksaan</td>
								<td> : </td>
								<td colspan="4"><b><span id="pemeriksaan"></span> <span id="nama"></span> </b></td>
							</tr> -->
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
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Daftar Tindakan</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
					<table class="table table-bordered" id="list-tindakan-radiologi">
						<thead class="thead-dark">
							<tr>
								<th width="5%">No.</th>
								<th>Tindakan</th>
								<th>Penjamin</th>
                                <th>Mitra</th>
								<th class="wrap_content"></th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 
<div class="row">
	<div class="col-lg">
		<p><h4><span id="judul_pemeriksaan">Thorax PA</span></h4></p>
	</div>
</div>
 -->
<div class="row" id="panel-hasil">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Keterangan Pemeriksaan : <span class="title-pemeriksaan"></span></h5>
			</div>
			<div class="card-body">
				<div id="txt_keterangan_pemeriksaan" class="txt_keterangan_pemeriksaan"></div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Kesimpulan Pemeriksaan : <span class="title-pemeriksaan"></span></h5>
			</div>
			<div class="card-body">
				<div id="txt_kesimpulan_pemeriksaan" class="txt_kesimpulan_pemeriksaan"></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	
</div>