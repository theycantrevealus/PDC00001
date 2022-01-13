<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori">Inventori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Peminjaman Barang</li>
                </ol>
            </nav>
            <h4>Inventori - Peminjaman Barang</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-header__title flex m-0">
            Peminjaman Barang
                <a href="<?php echo __HOSTNAME__; ?>/inventori/pinjam/tambah" class="btn btn-info pull-right" id="tambah-gudang">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </h5>
        </div>
        <div class="card-body">
            <div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li class="nav-item">
						<a href="#tab-active" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-do" >
							<span class="nav-link__count">
								01
								<!-- <b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Pengajuan Baru
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-his" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<!-- <b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b> -->
							</span>
							Riwayat Pengajuan
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane show fade active" id="tab-active">
                    <table class="table table-bordered" id="table-pinjam">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Tanggal</th>
                            <th>Diajukan Oleh</th>
                            <th class="wrap_content">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="tab-pane show fade" id="tab-his">
                    <table class="table table-bordered" id="table-his">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Tanggal</th>
                            <th>Diajukan Oleh</th>
                            <th>Disetujui Oleh</th>
                            <th class="wrap_content">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>