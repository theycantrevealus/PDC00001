<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<div class="col-lg-4">
					<h5 class="card-header__title flex m-0" id="nama-pasien-faktur"></h5>
				</div>
				<div class="col-lg-4">
					<h5 class="card-header__title flex m-0 text-right" id="pegawai-faktur"></h5>
				</div>
				<div class="col-lg-4">
					<h5 class="card-header__title flex m-0 text-right" id="tanggal-faktur"></h5>
				</div>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="biaya-terkini">
					<table class="table table-bordered table-striped largeDataType" id="invoice_detail_history">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>Item</th>
								<th class="wrap_content">Jlh</th>
								<th style="max-width: 200px; width: 200px">Harga</th>
								<th style="max-width: 200px; width: 200px">Subtotal</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<td colspan="3" rowspan="3" id="keterangan-faktur">
								</td>
								<td class="text-right">
									Total
								</td>
								<td id="total-faktur" class="text-right">0.00</td>
							</tr>
							<tr>
								<td class="text-right">Diskon</td>
								<td id="diskon-faktur" class="text-right">
									
								</td>
							</tr>
							<tr>
								<td class="text-right">
									Grand Total
								</td>
								<td id="grand-total-faktur" class="text-right">0.00</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>