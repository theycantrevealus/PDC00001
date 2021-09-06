<?php
	$day=new DateTime('last day of this month');
?>
<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Penyesuaian Stok</li>
				</ol>
			</nav>
			<h4 class="m-0">Penyesuaian Stok</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
   <div class="card card-form">
		<div class="row no-gutters">
			<div class="col-lg-4 card-body">
				<p><strong class="headings-color">Informasi Gudang</strong></p>
                <div id="opname_running_status">
                    <div class="alert alert-soft-warning d-flex align-items-center card-margin custom">
                        <i class="material-icons mr-3 alert-sider">error_outline</i>
                        <div class="text-body"><strong>Status Gudang.</strong> <p>Gudang ini sedang dalam masa <code>penyesuaian stok</code>. Segala aktifitas barang masuk dan keluar dihentikan</p></div>
                    </div>
                </div>
                <div id="opname_ready_status">
                    <div class="alert alert-soft-success d-flex align-items-center card-margin custom">
                        <i class="material-icons mr-3 alert-sider">check_circle</i>
                        <div class="text-body"><strong>Status Gudang.</strong> <p>Gudang aktif</p></div>
                    </div>
                </div>
			</div>
			<div class="col-lg-8 card-form__body card-body">
				<div class="form-row">
					<div class="col-12 col-md-6 mb-3">
						<label for="">Gudang</label>
						<select disabled class="form-control" id="txt_gudang"></select>
					</div>
					<div class="col-12 col-md-6 mb-3" style="padding-top: 22.5px;">
						<button class="btn btn-warning" id="tambahStokAwal">
							<i class="fa fa-clipboard-check"></i> Penyesuaian Stok
						</button>
                        <button class="btn btn-success" id="tambahAktifkanGudang">
                            <i class="fa fa-check-circle"></i> Aktifkan Gudang
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
					<table class="table table-bordered table-striped largeDataType" id="table-stok-opname" style="font-size: 0.9rem;">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>Dari</th>
								<th>Sampai</th>
								<th>Pegawai</th>
								<th>Tanggal Pengerjaan</th>
								<th class="wrap_content">Aksi</th>
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