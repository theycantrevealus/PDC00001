<div class="row">
	<div class="col-lg-12">
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
					<input type="date" class="form-control" id="txt_tanggal" />
				</div>
			</div>
			<div class="col-lg-12">
				<b>Detail Item</b>
				<table class="table table-bordered largeDataType" id="table-detail-po">
					<thead>
						<tr>
							<th style="width: 15px">No</th>
							<th>Item</th>
							<th style="width: 10%">Qty</th>
							<th style="width: 15%">Satuan</th>
							<th style="width: 15%">Harga</th>
							<th style="width: 15%">Disc</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="6" class="text-right">
								<b>Total</b>
							</td>
							<td id="allTotal">
								<h5 class="text-right">0.00</h5>
							</td>
						</tr>
						<tr>
							<td colspan="5"></td>
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
							<td colspan="6" class="text-right">
								<b>Grand Total</b>
							</td>
							<td id="grandTotal">
								<h5 class="text-right text-danger">0.00</h5>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="col-lg-12">
				<b>Keterangan Tambahan</b>
				<textarea class="form-control" id="txt_keterangan" placeholder="Tambahkan keterangan tambahan disini"></textarea>
			</div>
		</div>
	</div>
</div>