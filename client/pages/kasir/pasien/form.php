<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0" id="nama-pasien"></h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				<a href="#biaya-terkini" class="active" data-toggle="tab" role="tab" aria-controls="biaya-terkini" aria-selected="true">Tunggakan Tagihan</a>
				<a href="#biaya-history" data-toggle="tab" role="tab" aria-selected="false">History</a>
				<a href="#biaya-retur" data-toggle="tab" role="tab" aria-selected="false">Biaya Kembali (Retur)</a>
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane active show fade" id="biaya-terkini">
					<table class="table table-bordered table-striped largeDataType" id="invoice_detail_item">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content"><input type="checkbox" class="form-control" id="bulk-all" /></th>
								<th class="wrap_content">No</th>
								<th>Item</th>
								<th class="wrap_content">Jlh</th>
								<th>Harga</th>
								<th style="max-width: 200px; width: 200px">Subtotal</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<button style="float: right;" type="button" class="btn btn-info" id="btnBukaFaktur"><i class="fa fa-receipt"></i> Buka Faktur</button>
				</div>
				<div class="tab-pane show fade" id="biaya-history">
					<table class="table table-bordered table-striped largeDataType" id="payment_history">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>Kwitansi</th>
								<th>Tanggal Bayar</th>
								<th>Metode Bayar</th>
								<th>Petugas Kasir</th>
								<th>Total</th>
								<th>Rincian</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="tab-pane show fade" id="biaya-retur">
					<table class="table table-bordered table-striped largeDataType" id="payment_retur">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>Kwitansi</th>
								<th>Tanggal Bayar</th>
								<th>Metode Bayar</th>
								<th>Petugas Kasir</th>
								<th>Total</th>
								<th>Rincian</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>