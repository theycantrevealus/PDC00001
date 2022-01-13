<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Informasi</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_no_ktp">Supplier:</label>
							<select class="form-control" id="txt_supplier"></select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_kategori">Tanggal:</label>
							<input autocomplete="off" class="form-control txt_tanggal" id="txt_tanggal" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_no_ktp">Sumber Dana:</label>
							<select class="form-control" id="txt_sumber_dana"></select>
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