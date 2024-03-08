<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS - PESERTA</li>
                </ol>
            </nav>
            <h4 class="m-0">BPJS - PESERTA</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">PESERTA</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#search_no_srb" class="active" data-toggle="tab" role="tab" aria-controls="search_no_srb" aria-selected="true">Cari No. Kartu BPJS</a>
                    <a href="#search_tgl_prb" data-toggle="tab" role="tab" aria-selected="false">Cari NIK</a>
                </div>
                <div class="card-body tab-content">
                    <div class="card-body tab-content">
                        <div class="tab-pane active show fade" id="search_no_srb">
                            <div class="card-group">
                                <div class="card">
                                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                                        <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> No. Kartu Peserta</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-md-5">
                                                No. Kartu
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="bynokartu_text_search_no_kartu">
                                            </div>
                                            <div class="col-md-5">
                                                Tanggal Pelayanan/SEP
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="bynokartu_text_search_tgl">
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button class="btn btn-info" id="btn_search_bynokartu">
                                                    <i class="fa fa-search"></i> Cari Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="search_tgl_prb">
                            <div class="card-group">
                                <div class="card">
                                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                                        <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> NIK Peserta</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-md-5">
                                                NIK
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="bynik_text_search_nik">
                                            </div>
                                            <div class="col-md-5">
                                                Tanggal Pelayanan/SEP
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="bynik_text_search_tgl">
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button class="btn btn-info" id="btn_search_bynik">
                                                    <i class="fa fa-search"></i> Cari Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="alert-peserta-container">
                        <div class="alert alert-danger" id="alert-peserta"></div>
                    </div>
                    <div class="card card-body">
                        <table class="table table-bordered table-striped largeDataType" id="table-peserta">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>No. Kartu</th>
                                    <th>J. Kelamin</th>
                                    <th>Hak Kelas</th>
                                    <th>Status Peserta</th>
                                    <th>Jenis Peserta</th>
                                    <th class="wrap_content text-center">Aksi</th>
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