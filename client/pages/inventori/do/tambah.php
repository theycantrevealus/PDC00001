<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="<?php echo __HOSTNAME__; ?>/inventori/do">Barang Masuk</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Barang Masuk</li>
				</ol>
			</nav>
			<h4>Tambah Barang Masuk</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="card card-body tab-content">
				<div class="row">
					<div class="col-lg col-md-12 col-lg-12">
						<div class="card">
							<div class="card-header card-header-large bg-white d-flex align-items-center">
								<h5 class="card-header__title flex m-0">Informasi Barang Masuk</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4 form-group">
										<label>Gudang:</label>
										<select class="form-control select2 informasi" id="gudang" name="gudang">
											<option value="none">Pilih Gudang</option>
										</select>
									</div>
									<div class="col-md-4 form-group">
										<label>Pemasok:</label>
										<select class="form-control select2 informasi" id="supplier" name="supplier">
											<option value="none">Pilih Pemasok</option>
										</select>
									</div>
									<div class="col-md-4 form-group">
										<label>No. Purchase Order:</label>
										<select class="form-control select2 informasi" id="po" name="po">
											<option value="none">Tidak PO</option>
										</select>
									</div>
									<div class="col-md-3 form-group">
										<label>No. Delivery Order:</label>
										<input type="text" name="no_do" id="no_do" class="form-control informasi" />
									</div>
									<div class="col-md-3 form-group">
										<label>Tanggal DO:</label>
										<input type="date" value="<?php echo date('Y-m-d'); ?>" name="tgl_dokumen" id="tgl_dokumen" class="form-control informasi" />
									</div>
									<div class="col-md-3 form-group">
										<label>No. Invoice:</label>
										<input type="text" name="no_invoice" id="no_invoice" class="form-control informasi" />
									</div>
									<div class="col-md-3 form-group">
										<label>Tanggal Invoice:</label>
										<input type="date" name="tgl_invoice" id="tgl_invoice" class="form-control informasi" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-12 col-lg-12">
						<div class="card">
							<div class="card-header card-header-large bg-white d-flex align-items-center">
								<h5 class="card-header__title flex m-0">Item Barang Masuk</h5>
							</div>
							<div class="card-body">
								<table class="table table-bordered table-striped largeDataType" id="table-item-do">
									<thead class="thead-dark">
										<tr>
											<th width="1%">No</th>
											<th width="20%">Item</th>
											<th width="10%">Kode Batch</th>
                                            <th width="10%">Belum Sampai</th>
											<th width="10%">Qty</th>
											<th width="2%">Satuan</th>
											<th width="12%">Keterangan</th>
											<th width="2%">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<!-- <tr class="last">
											<td class="no_urut">1</td>
											<td>
												<select class="form-control itemInputanSelect input-sm select2 items" id="barang_1" name="barang_1">
													<option value="">Pilih Item</option>
												</select>
												<div class="input-group">
													<div class="input-group-prepend">
													  <span class="input-group-text" id="kedaluarsa_label_1">Kedaluarsa</span>
													</div>
													<input type="date" name="kedaluarsa_1" id="kedaluarsa_1" class="form-control itemInputan items" placeholder="Kode Batch" aria-describedby="kedaluarsa_label">
												</div>
											</td>
											<td><input type="text" name="kode_batch_1" id="kode_batch_1" class="form-control itemInputan items" placeholder="Kode Batch">
											<td><input type="number" name="qty_1" id="qty_1" class="form-control itemInputan items" value="0"></td>
											<td>
												<span id="satuan_1">Satuan</span>
											</td>
											<td><textarea class="form-control itemInputan items" id="keterangan_1" nama="keterangan_1"></textarea></td>
											<td><button class="btn btn-sm btn-danger btn-hapus-item" data-toggle='tooltip' title='Hapus'><i class="fa fa-trash"></i></button></td>
										</tr> -->
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-12 col-lg-12">
						<div class="card">
							<div class="card-header card-header-large bg-white d-flex align-items-center">
								<h5 class="card-header__title flex m-0">Keterangan</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<textarea class="form-control informasi" placeholder="Keterangan Tambahan Barang Masuk" id="keterangan" name="keterangan"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg col-md-12 col-lg-12">
						<div class="card-body">
							 <div class="form-row">
								<button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> Simpan Data</button>
									&nbsp;
								<a href="<?php echo __HOSTNAME__; ?>/inventori/do" class="btn btn-danger"><i class="fa fa-ban"></i> Kembali</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

