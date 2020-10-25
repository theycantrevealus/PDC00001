<div class="row">
	<div class="col-lg-12">
		<div class="card-group">
			<div class="card card-body">
				<div class="d-flex flex-row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Supplier:</label>
							<br />
							<b id="supplier_name"></b>
							<small id="supplier_info"></small>
						</div>
					</div>
					<div class="col-md-4">
						<label>Dibuat Oleh:</label>
						<br />
						<b id="pegawai_name"></b>
					</div>
					<div class="col-md-4">
						<label>Tanggal PO:</label>
						<br />
						<b id="tanggal_po"></b>
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
									<td id="allTotal" class="text-right">
										<b id="total_all">0.00</b>
									</td>
								</tr>
								<tr>
									<td colspan="4">
										<b>Keterangan Tambahan</b>
										<br />
										<small id="keterangan-po"></small>
									</td>
									<td class="text-right">
										Diskon
									</td>
									<td class="text-right">
										<b id="disc_all">-</b>
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