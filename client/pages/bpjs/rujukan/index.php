<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS Rujukan</li>
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
                    <h5 class="card-header__title flex m-0">
                        Rujukan BPJS
                    </h5>
                </div>

                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#rs_ini" class="active" data-toggle="tab" role="tab" aria-controls="rs_ini" aria-selected="true">Dari <?php echo __PC_CUSTOMER__; ?></a>
                    <a href="#tab_rujukan_khusus" data-toggle="tab" role="tab" aria-selected="false">Rujukan Khusus</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="rs_ini">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-4">
                                        Tanggal Awal
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tglawal_listkeluarrujukan">
                                    </div>
                                    <div class="col-md-4">
                                        Tanggal Akhir
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tglakhir_listkeluarrujukan">
                                    </div>
                                    <div class="col-md-4">
                                        <br>
                                        <button class="btn btn-info" id="btn_search_listkeluarrujukan">
                                            <i class="fa fa-search"></i> Cari Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="alert-rujukanlist-container">
                            <div class="alert alert-danger" id="alert-rujukanlist"></div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-sm btn-info pull-right" id="btnTambahRujukan">
                                    <i class="fa fa-plus"></i> Tambah Rujukan
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped largeDataType" id="table-rujukan">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Pasien - No. Kartu</th>
                                            <th>No. Rujukan</th>
                                            <th>Tgl. Rujukan</th>
                                            <th>No. SEP</th>
                                            <th>Jenis Pelayanan</th>
                                            <th>Faskes Dirujuk</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="tab_rujukan_khusus">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-4">
                                        Bulan & Tahun
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tgl_rujukankhususlist">
                                    </div>
                                    <div class="col-md-4">
                                        <br>
                                        <button class="btn btn-info" id="btn_search_rujukankhususlist">
                                            <i class="fa fa-search"></i> Cari Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="alert-rujukankhusus-container">
                            <div class="alert alert-danger" id="alert-rujukankhusus-list"></div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-sm btn-info pull-right" id="btnTambahRujukanKhusus">
                                    <i class="fa fa-plus"></i> Tambah Rujukan Khusus
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped largeDataType" id="table-rujukan-khusus">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Id Rujukan</th>
                                            <th>No. Rujukan</th>
                                            <th>Tgl. Rujukan Awal</th>
                                            <th>Tgl. Rujukan Berakhir</th>
                                            <th>NOKAPST</th>
                                            <th>NMPST</th>
                                            <th>Diagnosa PPK</th>
                                            <th class="text-center">Aksi</th>
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