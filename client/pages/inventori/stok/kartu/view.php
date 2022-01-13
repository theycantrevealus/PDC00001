<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori/stok/kartu">Kartu Stok</a></li>
                    <li class="breadcrumb-item active" id="item_name" aria-current="page"></li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Kartu Stok</h4>
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
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table form-mode">
                                            <tr>
                                                <td>Nama Barang</td>
                                                <td class="wrap_content">:</td>
                                                <td id="nama_barang"></td>

                                                <td>Periode</td>
                                                <td class="wrap_content">:</td>
                                                <td style="width: 30%">
                                                    <input id="range_stok" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kemasan</td>
                                                <td>:</td>
                                                <td id="kemasan_barang"></td>

                                                <td>Total</td>
                                                <td>:</td>
                                                <td id="total_all">0</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header">
                                <canvas id="currentStokGraph" width="400" height="50"></canvas>
                            </div>
                            <div class="card-body">
                                <div class="tab-pane active show fade" id="loadResult">
                                    <!--<table class="table largeDataType" id="table-item-log">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th width="10%">Tanggal</th>
                                            <th class="wrap_content">Dokumen</th>
                                            <th style="wrap_content">Batch</th>
                                            <th class="wrap_content">Masuk</th>
                                            <th class="wrap_content">Keluar</th>
                                            <th class="wrap_content">Saldo</th>
                                            <th>Keterangan</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>