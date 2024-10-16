<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap">Rawat Inap</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visite Dokter</li>
                </ol>
            </nav>
            <h4 class="m-0">Visite Dokter</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Visite Dokter</h5>
                    <button class="btn btn-info" id="btnTambahVisit">
                        <span>
                            <i class="fa fa-plus"></i> Tambah Visite Dokter
                        </span>
                    </button>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#visite" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama"
                        aria-selected="true">Visite Dokter</a>
                    <a href="#konsultasi" data-toggle="tab" role="tab" aria-selected="false">Konsultasi</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="visite">
                        <table class="table table-bordered table-striped largeDataType" id="table-visit-dokter">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width:10px" class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Dokter Konsultasi</th>
                                <th>Jenis Pelayanan</th>
                                <th>Penjamin</th>
                                <th>Keterangan</th>
                                

                                <!-- <th>Poliklinik</th>
                                
                                <th>Dokter</th>
                                <th>Penjamin</th> -->
                                <!-- <th class="wrap_content">Aksi</th> -->
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="tab-pane show fade" id="konsultasi">
                    <table class="table table-bordered table-striped largeDataType" id="table-konsultasi-dokter">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width:10px" class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Jenis Pelayanan</th>
                                <th>Penjamin</th>
                                <th>Keterangan</th>
                                <th>#</th>
                                

                                <!-- <th>Poliklinik</th>
                                
                                <th>Dokter</th>
                                <th>Penjamin</th> -->
                                <!-- <th class="wrap_content">Aksi</th> -->
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