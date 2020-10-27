<p><h4>Rekonsiliasi Obat</h4></p>
<p><i><h7>(akan diinformasikan kepada Farmasi sebelum peresepan obat pertama, informasi harus diberikan kepada Farmasi saat pembuatan resep pertama untuk pasien)</h7></i></p>
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
							<label>Penggunaan obat sebelum dirawat:</label>
						</div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="rekon_obat_penggunaan_obat" value="Jalan" id="rekon_obat_penggunaan_obat_1">
                                    <label class='custom-control-label' for="rekon_obat_penggunaan_obat_1">Tidak menggunakan obat sebelum dirawat
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="rekon_obat_penggunaan_obat" value="Lainnya" id="rekon_obat_penggunaan_obat_2">
                                    <label class='custom-control-label' for="rekon_obat_penggunaan_obat_2">Ya, menggunakan obat sebagai berikut</label>
                                    <textarea disabled rows="3" class="form-control" name="rekon_obat_penggunaan_obat_list" id="rekon_obat_penggunaan_obat_list"></textarea>
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
			<div class="card-header bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Daftar Riwayat Alergi</h5>
			</div>
			<div class="card-body ">
				<div class="col-md-12">
                    <p><button type="button" class='btn btn-primary btn-sm' id="btnTambahRiwayatObat"><i class="fa fa-plus"></i> Tambah Riwayat Obat</button></p>
                   
					<table class="table table-bordered" id="list-rekon-obat-alergi-obat">
						<thead align="center" class="thead-dark">
							<tr>
								<th width="5%" rowspan="2">No.</th>
								<th width="35%" rowspan="2">Nama Obat Yang Menimbulkan Alergi</th>
								<th  width="15%">Derajat Alergi</th>
                                <th width="40%" rowspan="2">Reaksi Alergi</td>
                                <th>Aksi</th>
							</tr>
                            <!-- <tr>
                                <th>Ringan (R)</th>
                                <th>Sedang (S)</th>
                                <th>Berat (B)</th>
                            </tr> -->
						</thead>
						<tbody>
							
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
				<h5 class="card-header__title flex m-0">Daftar Obat yang masih Dipergunakan</h5>
			</div>
            
			<div class="card-body ">
                <div class="col-md-12" align="center">
                    <p><i>Daftar obat dibawah ini meliputi resep dan non resep yang dipergunakan sebulan terakhir dan masih dipakai saat masuk rumah sakit
                    <br />
                    Instruksi obat baru dituliskan pada rencana perawatan
                    <br />
                    Review kembali saat pasien pulang</i></p>
                </div>
				<div class="col-md-12">
                    <p><button type="button" class='btn btn-primary btn-sm' id="btnTambahObatDigunakan"><i class="fa fa-plus"></i> Tambah Obat yang Dipergunakan</button></p>
                   
					<table class="table table-bordered" id="list-rekon-obat-dipergunakan">
						<thead align="center" class="thead-dark">
							<tr>
								<th width="2%" rowspan="2">No.</th>
								<th>Nama Obat/ Dosis/ Bentuk Sediaan</th>
								<th>Frekuensi</th>
                                <th>Waktu Pemberian Terakhir</th>
                                <th width="26%">Tindak Lanjut</th>
                                <th>Perubahan Aturan Pakai</th>
                                <th>Aksi</th>
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