<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/apotek">Apotek</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resep Dokter</li>
                </ol>
            </nav>
            <h4 class="m-0">Resep Dokter</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Resep Dokter</h5>
                    <button class="btn btn-info" id="btnTambahResep">
                        <span>
                            <i class="fa fa-plus"></i> Tambah Resep
                        </span>
                    </button>
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
                    <div class="tab-pane active show fade" id="list-revisi">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>