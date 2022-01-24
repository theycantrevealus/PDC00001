<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/setting">Setting</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/setting/inventori">Inventori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stock Opname</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                        Stok Opname
                        <div class="pull-right">
                            <form id="upload_csv" method="post" enctype="multipart/form-data">
                                <input type="file" name="csv_file" id="csv_file" accept=".csv" />
                                <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />
                            </form>
                        </div>
                    </h5>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list-stok-log">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-2">
                                <input class="form-control" id="filter_tanggal" type="date" />
                            </div>
                            <div class="col-2">
                                <input class="form-control" id="filter_jam" type="time" value="00:00" />
                            </div>
                            <div class="col-2"></div>
                            <div class="col-4">
                                <select class="form-control" id="filter_gudang">
                                    <option value="all">Semua</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <br />
                                <table class="table table-striped table-bordered largeDataType" id="log-loader">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 20%;">Gudang</th>
                                            <th style="width: 10%;">Transaksi</th>
                                            <th style="width: 25%;">Barang</th>
                                            <th class="wrap_content">Out</th>
                                            <th class="wrap_content">In</th>
                                            <th class="wrap_content">Saldo</th>
                                            <th>Strategy</th>
                                            <th>Saldo SO</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active show fade" id="list-revisi">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>