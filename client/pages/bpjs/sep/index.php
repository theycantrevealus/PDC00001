<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">SEP</li>
                </ol>
            </nav>
            <h4 class="m-0">SEP</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">History Kunjungan</h5>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list-resep">
                        <div class="card-group">
                            <div class="card card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-4">
                                        Jenis Pelayanan
                                        <select class="form-control" id="jenis_pelayanan">
                                            <option value="2">Rawat Jalan</option>
                                            <option value="1">Rawat Inap</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        Periode
                                        <input id="range_sep" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                    </div>
                                    <div class="col-md-2">
                                        <br />
                                        <button class="btn btn-info" id="btn_sync_bpjs">
                                            <i class="fa fa-sync"></i> Sync Data BPJS
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-body">
                            <table class="table table-bordered table-striped largeDataType" id="table-sep">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 10%;">SEP</th>
                                    <th>Pasien</th>
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