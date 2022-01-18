<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek">Apotek</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resep</li>
                </ol>
            </nav>
            <h4 class="m-0">Resep</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Apotek</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#list-resep" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Poli</a>
                    <a href="#list-resep-2" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">IGD</a>
                    <a href="#list-resep-3" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Inap</a>
                    <a href="#list-resep-riwayat" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Riwayat</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list-resep">
                        <table class="table table-bordered table-striped largeDataType" id="table-resep">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Poliklinik</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Penjamin</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane show fade" id="list-resep-2">
                        <table class="table table-bordered table-striped largeDataType" id="table-resep-2">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Poliklinik</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Penjamin</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane show fade" id="list-resep-3">
                        <table class="table table-bordered table-striped largeDataType" id="table-resep-3">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Poliklinik</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Penjamin</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane show fade" id="list-resep-riwayat">
                        <div class="row">
                            <div class="col-lg-6">
                                <select class="form-control" id="filter_departemen_riwayat">
                                    <option value="all">Semua</option>
                                </select>  
                            </div>
                            <div class="col-lg-6">
                            </div>
                            <div class="col-lg-12">
                                <br />
                            </div>
                        </div>
                        <table class="table table-bordered table-striped largeDataType" id="table-resep-history">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Poliklinik</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Penjamin</th>
                                <th>Response Time</th>
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
</div>