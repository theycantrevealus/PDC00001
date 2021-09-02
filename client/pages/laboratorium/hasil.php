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
								<td>No. Rekam Medis</td>
								<td class="wrap_content"> : </td>
								<td><b><span id="no_rm"></span></b></td>
								<td>Tanggal Lahir</td>
                                <td class="wrap_content"> : </td>
								<td><b><span id="tanggal_lahir"></span></b></td>
							</tr>
							<tr>
								<td>Nama Pasien</td>
                                <td class="wrap_content"> : </td>
								<td><b><span id="panggilan"></span> <span id="nama"></span> </b></td>
								<td>Jenis Kelamin</td>
                                <td class="wrap_content"> : </td>
								<td><b><span id="jenkel"></span></b></td>
							</tr>
                            <tr>
                                <td>Waktu Sampling</td>
                                <td class="wrap_content"> : </td>
                                <td>
                                    <input id="tanggal_sampling" type="time" class="form-control" />
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
        <div id="hasil_pemeriksaan" class="hasil_pemeriksaan">

        </div>
        <div class="tab-dokter row">
            <div class="col-md-12 form-group">
                <label>Kesan:</label>
                <textarea style="min-height: 200px" type="text" name="kesan" id="kesan" class="form-control informasi"></textarea>
            </div>
            <div class="col-md-12 form-group">
                <label>Anjuran:</label>
                <textarea style="min-height: 200px" type="text" name="anjuran" id="anjuran" class="form-control informasi"></textarea>
            </div>
        </div>
	</div>
</div>