<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Radiologi</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <input id="range_history" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                    </div>
                    <div class="col-lg-12">
                        <br />
                        <table class="table table-bordered table-striped largeDataType" id="table-riwayat-radiologi">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th class="wrap_content">Waktu Order</th>
                                <th>Pasien</th>
                                <th>Poliklinik</th>
                                <th>Petugas Radiologi</th>
                                <th>Dokter Radiologi</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>