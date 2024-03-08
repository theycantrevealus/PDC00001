<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS Program Rujuk Balik</li>
                </ol>
            </nav>
            <h4 class="m-0">Program Rujuk Balik</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                        Program Rujuk Balik
                        <button class="btn btn-sm btn-info pull-right" id="btnTambahPRB">
                            <i class="fa fa-plus"></i> Tambah PRB
                        </button>
                        <!-- <button class="btn btn-sm btn-warning pull-right mr-1" id="btnTestEdit">
                            Test Edit
                        </button> -->
                    </h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#search_tgl_prb" class="active" data-toggle="tab" role="tab" aria-controls="search_tgl_prb" aria-selected="true">Cari Tanggal PRB</a>
                    <a href="#search_no_srb" data-toggle="tab" role="tab" aria-selected="false">Cari No. SRB</a>
                </div>
                <div class="card-body tab-content">
                    <div class="card-body tab-content">
                        <div class="tab-pane active show fade" id="search_tgl_prb">
                            <div class="card-group">
                                <div class="card">
                                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                                        <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> List Tanggal PRB</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-md-3">
                                                Tanggal Awal
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="tglawal_prb">
                                            </div>
                                            <div class="col-md-3">
                                                Tanggal Akhir
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="tglakhir_prb">
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button class="btn btn-info" id="btn_search_tgl_prb">
                                                    <i class="fa fa-search"></i> Cari Data
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="search_no_srb">
                            <div class="card-group">
                                <div class="card">
                                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                                        <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> No. SRB</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-md-5">
                                                No. SRB
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="text_search_no_srb">
                                            </div>
                                            <div class="col-md-5">
                                                No. SEP
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="text_search_no_sep">
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button class="btn btn-info" id="btn_search_no_srb">
                                                    <i class="fa fa-search"></i> Cari Data
                                                </button>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-prb-container">
                        <div class="alert alert-danger" id="alert-prb"></div>
                    </div>
                    <div class="card card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped largeDataType" id="table-prb">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No. SRB</th>
                                        <th>No. SEP</th>
                                        <th>Tgl. SRB</th>
                                        <th>Nama - No.Kartu</th>
                                        <th>Program PRB</th>
                                        <th>DPJP</th>
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