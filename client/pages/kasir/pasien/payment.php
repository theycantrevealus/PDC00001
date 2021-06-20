<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0" id="faktur-nama-pasien"></h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="biaya-terkini">
					<table class="table table-bordered table-striped largeDataType" id="fatur_detail_item">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>Item</th>
								<th class="wrap_content">Jlh</th>
								<th>Harga</th>
								<th style="max-width: 200px; width: 200px">Subtotal</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<td colspan="2" rowspan="3">
									<small>Keterangan</small>
									<textarea class="form-control" id="keterangan-faktur" style="min-height: 200px;" placeholder="Keterangan Faktur"></textarea>
								</td>
								<td colspan="2" class="text-right">
									Total
								</td>
								<td id="text-total" class="text-right">0.00</td>
							</tr>
							<!--tr>
								<td class="text-right">Diskon</td>
								<td>
									<select class="form-control" id="txt_diskon_type_all">
										<option value="N">None</option>
										<option value="P">Percent</option>
										<option value="A">Amount</option>
									</select>
								</td>
								<td id="text-diskon">
									<input id="txt_diskon_all" autocomplete="off" type="text" class="form-control" />
								</td>
							</tr-->
							<tr>
								<td colspan="2" class="text-right">
									Grand Total
								</td>
								<td id="text-grand-total" class="text-right">0.00</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>