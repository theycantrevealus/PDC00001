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
					<!--<div class="col-12 col-md-6 mb-3">
						<label for="">Gudang</label>
						<select disabled class="form-control" id="txt_gudang"></select>
					</div>-->
					<div class="col-12 col-md-6 mb-3" style="padding-top: 22.5px;">
						<button class="btn btn-warning" id="tambahStokAwal">
							<i class="fa fa-clipboard-check"></i> Mulai Penyesuaian Stok
						</button>
                        <button class="btn btn-success" id="tambahAktifkanGudang">
                            <i class="fa fa-check-circle"></i> Selesai Penyesuaian
                        </button>
					</div>
                    <!--<div class="col-12 col-md-6 mb-3" style="padding-top: 22.5px;">
                        <div class="col-lg-12" id="allow_transact_opname">
                            <button class="btn btn-info" id="prosesStrategi">Proses Strategi</button>
                        </div>
                    </div>-->
				</div>
			</div>
		</div>
	</div>
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                    <li class="nav-item">
                        <a href="#tab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-antrian-1" >
							<span class="nav-link__count">
								01
							</span>
                            Temporary Transact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" >
							<span class="nav-link__count">
								02
							</span>
                            Riwayat Penyesuaian
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane show fade active" id="tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                                            <h5 class="card-header__title flex m-0">Temporary Transact</h5>
                                        </div>
                                        <div class="card-body" id="intro_list_temporary_transact">
                                            <div class="tab-pane active show fade">
                                                <table class="table table-bordered largeDataType" id="table-temp">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th class="text-right">Gudang Asal</th>
                                                        <th>Gudang Tujuan</th>
                                                        <th style="width: 50%;">Item</th>
                                                        <th class="wrap_content">Jumlah</th>
                                                        <th class="wrap_content">Satuan</th>
                                                        <th class="wrap_content">Strategi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card" id="intro_transact_detail_remark">
                                                <div class="card-body">
                                                    <ol>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-lg-1">
                                                                    <span class="badge badge-custom-caption badge-outline-info">AMPRAH</span>
                                                                </div>
                                                                <div class="col-lg-11">
                                                                    <p>
                                                                        Sistem mendeteksi stok pada gudang ini <b class="text-danger">habis</b> dan setelah penyesuaian, gudang akan membuat amprah untuk menutupi kebutuhan transaksi.<br />
                                                                        <b class="text-info">Kemungkinan Setelah Opname:</b> Jika stok tersedia, sistem akan mengamprah jumlah kekurangan saja.
                                                                    </p>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <br />
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-lg-1">
                                                                    <span class="badge badge-custom-caption badge-outline-info">GENERAL</span>
                                                                </div>
                                                                <div class="col-lg-11">
                                                                    <p>
                                                                        Sistem mendeteksi stok pada gudang ini <b class="text-success">tersedia</b> dan setelah penyesuaian, sistem akan memotong stok seperti biasa.<br />
                                                                        <b class="text-info">Kemungkinan Setelah Opname:</b> Jika stok tidak tersedia, sistem akan mengamprah jumlah kekurangan.
                                                                    </p>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <br />
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-lg-1">
                                                                    <span class="badge badge-custom-caption badge-outline-info">MUTASi</span>
                                                                </div>
                                                                <div class="col-lg-11">
                                                                    <p>
                                                                        Sistem <b class="text-danger">tidak menemukan</b> stok pada gudang utama (amprah) maupun gudang terkait. Sistem merekomendasikan gudang asal yang memiliki stok. Gudang tujuan harus melakukan mutasi untuk memenuhi kebutuhan stok gudang asal.
                                                                    </p>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <br />
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-2">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-stok-opname" style="font-size: 0.9rem;">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>Dari</th>
                                    <th>Sampai</th>
                                    <th>Gudang</th>
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
    </div>
</div>