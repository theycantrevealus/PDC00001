<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Stok Awal</li>
				</ol>
			</nav>
			<h4 class="m-0">Stok Awal</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
   <div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-2 card-body">
				<p><strong class="headings-color">Informasi Gudang</strong></p>
                <button class="btn btn-info" id="tambahStokAwal">
                    <i class="fa fa-plus"></i> Tambah Stok Awal
                </button>
				<p style="font-size: 0.9rem;" class="text-muted"></p>
			</div>
			<div class="col-lg-4 card-body">
				<div class="form-row">
					<div class="col-12 col-md-12">
						<label for="">Gudang</label>
						<select class="form-control" id="txt_gudang"></select>
					</div>
				</div>
			</div>
            <div class="col-lg-4 card-body">
                <div class="form-row">
                    <div class="col-12 col-md-12">
                        <label for="">Import Stock</label>
                        <br />
                        <button class="btn btn-info" id="importStokAwal">
                            <i class="fa fa-plus"></i> Import Stok Awal
                        </button>
                    </div>
                </div>
            </div>
		</div>
	</div>
	<div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-12 card-body">
			   <div class="table-responsive border-bottom">
					<table class="table table-bordered table-striped largeDataType" id="table-stok-awal" style="font-size: 0.9rem;">
						<thead class="thead-dark">
							<tr>
								<th style="width: 20px;">No</th>
								<th>Item</th>
								<th>Batch</th>
								<th>Tanggal Expired</th>
								<th>Masuk</th>
								<th>Keluar</th>
								<th>Jlh Akhir (Saldo)</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>