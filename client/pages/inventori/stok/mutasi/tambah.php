<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori/amprah">Mutasi Stok</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
				</ol>
			</nav>
			<h4>Proses Mutasi Stok - Baru</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
            <div class="disable-panel-opname">
                <div>
                    <h3 class="text-center" id="opname_notif_amprah"></h3>
                    <p class="text-center">Gudang ini sedang menjalankan prosedur opname.<br />Sementara tidak dapat melakukan transaksi stok. <a href="<?php echo __HOSTNAME__; ?>/inventori/amprah">Kembali</a></p>
                </div>
            </div>
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Proses Mutasi Stok Baru</h5>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane active show fade" id="tab-po-1">
					<div class="row">
						<div class="col-lg-12">
							<div class="card">
								<div class="card-header card-header-large bg-white d-flex align-items-center">
									<h5 class="card-header__title flex m-0">Informasi</h5>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="txt_no_ktp">Unit Asal:</label>
												<select type="text" id="txt_unit_asal" class="form-control"></select>
                                                <span id="opname_notif_mutasi"></span>
											</div>
										</div>
										<div class="col-md-3"></div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="txt_kategori">Unit Tujuan:</label>
												<select type="text" id="txt_unit_tujuan" class="form-control"></select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="txt_no_ktp">Diproses Oleh:</label>
												<input type="text" id="txt_nama" class="form-control" readonly />
											</div>
										</div>
										<div class="col-md-3"></div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="txt_kategori">Tanggal:</label>
												<input type="text" class="form-control txt_tanggal" id="txt_tanggal" />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header card-header-large bg-white d-flex align-items-center">
									<h5 class="card-header__title flex m-0">Detail Item</h5>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-12">
											<table class="table table-bordered largeDataType" id="table-detail-mutasi">
												<thead class="thead-dark">
													<tr>
														<th class="wrap_content">No</th>
														<th style="width: 30%">Item</th>
														<th>Batch</th>
														<th class="wrap_content">Sisa Stok</th>
														<th class="wrap_content">Satuan</th>
														<th>Jumlah</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody></tbody>
												<tfoot>
													<tr>
														<td colspan="7">
                                                            <br />
															<b>Keterangan <strong class="text-danger">*wajib</strong></b>
															<textarea class="form-control" style="min-height: 200px;" id="txt_keterangan" placeholder="Tambahkan keterangan tambahan disini"></textarea>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<button class="btn btn-success" id="btnSubmitAmprah">
				<i class="fa fa-cubes"></i> Mutasi
			</button>
			<a href="<?php echo __HOSTNAME__; ?>/inventori/stok/mutasi" class="btn btn-danger">
				<i class="fa fa-ban"></i> Batal
			</a>
		</div>
	</div>
</div>