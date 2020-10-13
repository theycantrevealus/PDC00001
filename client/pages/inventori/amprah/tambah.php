<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori/amprah">Amprah</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
				</ol>
			</nav>
			<h4>Amprah - Baru</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<form id="submitPO">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h5 class="card-header__title flex m-0">Amprah Baru</h5>
				</div>
				<!-- <div class="card-header card-header-tabs-basic nav" role="tablist">
					<a href="#info-dasar-1" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Umum</a>
					<a href="#info-dasar-2" data-toggle="tab" role="tab" aria-selected="false">Kategori Obat</a>
				</div> -->
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
													<label for="txt_no_ktp">Supplier:</label>
													<select class="form-control" id="txt_supplier"></select>
												</div>
											</div>
											<div class="col-md-4"></div>
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
												<table class="table table-bordered largeDataType" id="table-detail-po">
													<thead class="thead-dark">
														<tr>
															<th style="width: 15px">No</th>
															<th>Item</th>
															<th style="width: 15%">Qty</th>
															<th class="wrap_content">Satuan</th>
															<th style="width: 25%">Harga</th>
															<th style="width: 15%">Subtotal</th>
														</tr>
													</thead>
													<tbody></tbody>
													<tfoot>
														<tr>
															<td colspan="5" class="text-right">
																<b>Total</b>
															</td>
															<td id="allTotal">
																<h5 class="text-right">0.00</h5>
															</td>
														</tr>
														<tr>
															<td colspan="4">
																<b>Keterangan Tambahan</b>
																<textarea class="form-control" id="txt_keterangan" placeholder="Tambahkan keterangan tambahan disini"></textarea>
															</td>
															<td>
																<div class="form-group">
																	<label for="txt_jenis_diskon_all"><b class="text-right">Discount Type</b></label>
																	<select class="form-control" id="txt_jenis_diskon_all">
																		<option value="N">None</option>
																		<option value="P">Percent</option>
																		<option value="A">Amount</option>
																	</select>
																</div>
															</td>
															<td id="discountAll">
																<div class="form-group">
																	<label for="txt_jenis_diskon_all"><b class="text-right">Discount</b></label>
																	<input type="text" class="form-control" id="txt_diskon_all" placeholder="Nilai Diskon" />
																</div>
															</td>
														</tr>
														<tr>
															<td colspan="5" class="text-right">
																<b>Grand Total</b>
															</td>
															<td id="grandTotal">
																<h5 class="text-right text-danger">0.00</h5>
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
				<button class="btn btn-success" id="btnSubmitPO">
					<i class="fa fa-save"></i> Simpan
				</button>
				<a href="<?php echo __HOSTNAME__; ?>/inventori/po" class="btn btn-danger">
					<i class="fa fa-ban"></i> Kembali
				</a>
			</form>
		</div>
	</div>
</div>