<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek/resep">Verifikator</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Verifikasi</li>
                </ol>
            </nav>
            <h4>Apotek - Verifikator</h4>
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
                                                <table class="table largeDataType table-bordered" id="table-resep">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th class="wrap_content">No</th>
                                                            <th>Obat</th>
                                                            <th class="wrap_content">Signa</th>
                                                            <th class="wrap_content">Jumlah</th>
                                                            <th class="wrap_content">Harga</th>
                                                            <th class="wrap_content">Total</th>
                                                            <th class="wrap_content"></th>
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
                            <button class="btn btn-success" id="btnVerifikasi">
                                <i class="fa fa-check"></i> Verifikasi
                            </button>
                            <a href="<?php echo __HOSTNAME__; ?>/apotek/resep" class="btn btn-danger">
                                <i class="fa fa-ban"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>