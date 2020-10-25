<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek/proses">Apotek</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Proses Resep</li>
                </ol>
            </nav>
            <h4>Apotek - Proses Resep</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-body tab-content">
                <div class="tab-pane active show fade" id="tab-po-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">Resep</h5>
                                </div>
                                <div class="card-body tab-content">
                                    <div class="tab-pane active show fade" id="resep-biasa">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="load-detail-resep" class="table table-bordered table-striped largeDataType">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th style="width: 40%;">Obat</th>
                                                        <th width="15%">Signa</th>
                                                        <th width="15%">Jumlah</th>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0">Racikan</h5>
                                </div>
                                <div class="card-body tab-content">
                                    <div class="tab-pane active show fade" id="resep-biasa">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="load-detail-racikan" class="table table-bordered largeDataType">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th style="width: 15%;">Racikan</th>
                                                        <th>Signa</th>
                                                        <th>Obat</th>
                                                        <th>Jumlah</th>
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
                        <div class="col-md-12">
                            <button class="btn btn-success" id="btnSelesai">
                                <i class="fa fa-check"></i> Selesai
                            </button>
                            <a href="<?php echo __HOSTNAME__; ?>/apotek/proses" class="btn btn-danger">
                                <i class="fa fa-ban"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>