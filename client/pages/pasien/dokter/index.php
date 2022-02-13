<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pasien</li>
                </ol>
            </nav>
            <h4 class="m-0">Data Pasien</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0">Data Pasien</h5>
                        </div>
                        <div class="card-body tab-content">
                            <div class="row">
                                <div class="col-lg-3 text-right">
                                    Filter Tanggal Pencarian Data
                                </div>
                                <div class="col-lg-6">
                                    <input id="range_pasien" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                </div>
                                <div class="col-lg-12">
                                    <br />
                                    <table class="table table-bordered largeDataType" id="table-pasien">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Pasien</th>
                                            <th class="wrap_content">Poliklinik</th>
                                            <th class="wrap_content">Kunjungan</th>
                                            <th>Tindakan Penunjang</th>
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
        </div>
    </div>
</div>