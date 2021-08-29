<div class="row">
	<div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Alergi Obat</h5>
            </div>
            <div class="card-body">
                <textarea placeholder="Keterangan Alergi Obat" id="alergi_obat" class="form-control"></textarea>
            </div>
        </div>
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Resep</h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				<a href="#resep-biasa" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Resep Biasa</a>
				<a href="#resep-racikan" data-toggle="tab" role="tab" aria-selected="false">Resep Racikan</a>
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="resep-biasa">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered largeDataType" id="table-resep">
								<thead class="thead-dark">
									<tr>
										<th class="wrap_content">No</th>
										<th>Obat</th>
										<th colspan="3" style="width: 20%;">Signa/Hari</th>
										<th style="width: 10%">Jlh Obat</th>
										<th class="wrap_content">Satuan</th>
										<th class="wrap_content">Aksi</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="col-md-12" style="margin-top: 20px; min-height: 200px">
							<b>Keterangan:</b>
                            <div class="edit-switch-container" target="txt_keterangan_resep">
                                <i class="fa fa-pencil-alt"></i> Edit
                            </div>
							<div id="txt_keterangan_resep"></div>
						</div>
					</div>
				</div>
				<div class="tab-pane show fade" id="resep-racikan">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered largeDataType" id="table-resep-racikan">
								<thead class="thead-dark">
									<tr>
										<th class="wrap_content">No</th>
										<th>Obat</th>
										<th colspan="3" style="width: 20%;">Signa/Hari</th>
										<th style="width: 10%">Jlh Obat</th>
										<th class="wrap_content">Aksi</th>
									</tr>
								</thead>
								<tbody class="racikan"></tbody>
							</table>
						</div>
						<div class="col-md-12" style="margin-top: 20px; min-height: 200px">
							<b>Keterangan Resep Racikan:</b>
                            <div class="edit-switch-container" target="txt_keterangan_resep_racikan">
                                <i class="fa fa-pencil-alt"></i> Edit
                            </div>
							<div id="txt_keterangan_resep_racikan"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>