<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/inventori">Master Inventori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gudang</li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Master Inventori</h4>
        </div>
        <!-- <a href="<?php echo __HOSTNAME__; ?>/master/inventori/gudang/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah Gudang
		</a> -->

    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Kartu Stok</h5>
                </div>
                <div class="row card-body">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table form-mode">
                                            <tr>
                                                <td>Nama Barang</td>
                                                <td>:</td>
                                                <td id="nama_barang"></td>
                                            </tr>
                                            <tr>
                                                <td>Kemasan</td>
                                                <td>:</td>
                                                <td id="kemasan_barang"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-pane active show fade">
                                    <table class="table table-bordered largeDataType" id="table-item">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">Tanggal</th>
                                            <th style="width: 10%;">Dokumen</th>
                                            <th style="wrap_content">Uraian</th>
                                            <th class="wrap_content">Masuk</th>
                                            <th class="wrap_content">Keluar</th>
                                            <th class="wrap_content">Saldo</th>
                                            <th>Keterangan</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>