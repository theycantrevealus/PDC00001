<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS JKN Mobile</li>
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
                    <h5 class="card-header__title flex m-0">JKN Mobile</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#tracked" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Akun JKN</a>
                    <a href="#untracked" data-toggle="tab" role="tab" aria-selected="false">Rekap Antrian</a>
                    <a href="#untracked" data-toggle="tab" role="tab" aria-selected="false">Rekap Kode Booking Operasi</a>
                    <a href="#untracked" data-toggle="tab" role="tab" aria-selected="false">Rekap Jadwal Operasi</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="tracked">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Daftar Akses Akun JKN Mobile</h5>
                                <button class="btn btn-info bn-sm" id="btnTambahAkun">
                                    <i class="fa fa-plus"></i> Tambah Akun
                                </button>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade" id="list-resep">
                                    <table class="table table-bordered table-striped largeDataType" id="table-akun">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 25%;">Username</th>
                                            <th class="wrap_content">Password</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="untracked">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">SEP Untracked</h5>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade" id="list-resep">

                                    <table class="table table-bordered table-striped largeDataType" id="table-sep-untrack">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>No. RM</th>
                                            <th>Pasien</th>
                                            <th>No. SEP</th>
                                            <th>Poli</th>
                                            <th>Aksi</th>
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