<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS Rencana Kontrol SPRI</li>
                </ol>
            </nav>
            <h4 class="m-0">Permintaan Rencana Kontrol <code>SPRI</code></h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">
                    Rencana Kontrol <code>SPRI</code>
                        <button class="btn btn-sm btn-info pull-right" id="btnTambahPRB">
                            <i class="fa fa-plus"></i> Tambah SPRI
                        </button>
                    </h5>
                </div>
                <div class="card-body tab-content">
                    <div class="row">
                        <div class="col-8">
                            <input id="range_spri" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                        </div>
                        <div class="col-4">
                            <button class="btn btn-info" id="btn_sync_bpjs">
                                <i class="fa fa-sync"></i> Sync Data BPJS
                            </button>
                        </div>
                        <div class="col-12">
                            <br />
                            <div class="tab-pane active show fade" id="rs_ini">
                                <table class="table table-bordered table-striped largeDataType" id="table-spri">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Tanggal</th>
                                        <th class="wrap_content">No. RK</th>
                                        <th>Pasien</th>
                                        <th>DPJP</th>
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
    </div>
</div>