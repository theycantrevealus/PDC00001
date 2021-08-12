<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white align-items-center">
                <div class="row info-kwitansi">
                    <div class="col-4">
                        <span class="nomor-faktur"></span>
                    </div>
                    <div class="col-4">
                        <span id="nama-pasien-faktur"></span>
                    </div>
                    <div class="col-4">
                        <span id="pegawai-faktur"></span>
                    </div>
                    <div class="col-12">
                        <br />
                    </div>
                    <div class="col-4">
                        <span id="tanggal-faktur"></span>
                    </div>
                    <div class="col-4">
                        <span id="poli"></span>
                    </div>
                    <div class="col-12">
                        <br />
                    </div>
                </div>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="biaya-terkini">
					<table class="table table-bordered table-striped largeDataType" id="invoice_detail_history">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content"></th>
								<th class="wrap_content">No</th>
								<th>Item</th>
								<th class="wrap_content">Jlh</th>
								<th class="number_style" style="max-width: 200px; width: 200px">Harga</th>
								<th class="number_style" style="max-width: 200px; width: 200px">Subtotal</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<td colspan="4" rowspan="2" id="keterangan-faktur">
								</td>
								<td class="text-right">
									Total
								</td>
								<td id="total-faktur" class="number_style">0.00</td>
							</tr>
							<!--tr>
								<td class="text-right">Diskon</td>
								<td id="diskon-faktur" class="text-right">
									
								</td>
							</tr-->
							<tr>
								<td class="text-right">
									Grand Total
								</td>
								<td id="grand-total-faktur" class="number_style">0.00</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>