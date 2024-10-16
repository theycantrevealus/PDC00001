<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rekam Medis</li>
                </ol>
            </nav>
            <h4 class="m-0">Data Rekam Medis</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0">Riwayat Perobatan Pasien Terintegrasi</h5>
                        </div>
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label for="">Nama Pasien</label>
                                            <select id="cari_pasien" class="form-control"></select>
                                        </div>
                                        <div class="col-3">
                                            <label for="">Obat/BHP</label>
                                            <select id="cari_obat" class="form-control"></select>
                                        </div>
                                        <div class="col-3">
                                            <br />
                                            <button id="btn_clear_filter" class="btn btn-sm btn-info">
                                                <i class="fa fa-filter"></i> Clear Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body tab-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <br />
                                    <table class="table table-bordered largeDataType" id="table-pasien">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="all wrap_content">No</th>
                                            <th class="all wrap_content">Aksi</th>
                                            <th class="all wrap_content">Tanggal</th>
                                            <th class="all wrap_content">Poliklinik</th>
                                            <th class="all wrap_content">Dokter</th>
                                            <th class="all">Pasien</th>
                                            <th class="none"></th>
                                            <th class="none"></th>
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
    </div>
</div>