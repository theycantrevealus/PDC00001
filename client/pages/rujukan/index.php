<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Rujukan</li>
                </ol>
            </nav>
            <h4 class="m-0">Permintaan Rujukan</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Rujukan Pasien</h5>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list-resep">
                        <table class="table table-bordered table-striped largeDataType" id="table-rujukan" style="font-size: 0.9rem;">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Penjamin</th>
                                <th class="wrap_content">No. RM</th>
                                <th>Pasien</th>
                                <th>Poli</th>
                                <th>Dokter</th>
                                <th>No. SEP</th>
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