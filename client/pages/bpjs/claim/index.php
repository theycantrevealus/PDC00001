<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS Claim</li>
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
                    <h5 class="card-header__title flex m-0">VClaim BPJS</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#tracked" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">SEP Terdaftar</a>
                    <a href="#untracked" data-toggle="tab" role="tab" aria-selected="false">SEP Lainnya</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="tracked">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">SEP</h5>
                                <div class="col-md-6" style="border: solid 1px #ccc">
                                    <input id="range_sep" type="text" class="form-control" placeholder="Filter Tanggal" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                </div>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade" id="list-resep">

                                    <table class="table table-bordered table-striped largeDataType" id="table-sep">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 25%;">Pasien</th>
                                            <th style="width: 20%;">Perujuk</th>
                                            <th>No. SEP</th>
                                            <th class="wrap_content">Poli</th>
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