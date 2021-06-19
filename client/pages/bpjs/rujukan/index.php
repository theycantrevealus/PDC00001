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
                    <h5 class="card-header__title flex m-0">Rujukan BPJS</h5>
                </div>
                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#rs_ini" class="active" data-toggle="tab" role="tab" aria-controls="rs_ini" aria-selected="true">Dari <?php echo __PC_CUSTOMER__; ?></a>
                    <a href="#rs_lain" data-toggle="tab" role="tab" aria-selected="false">Dari Faskes Lain</a>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="rs_ini">
                        <table class="table table-bordered table-striped largeDataType" id="table-rujukan">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th class="wrap_content">No. RM</th>
                                <th>Pasien</th>
                                <th>Poli</th>
                                <th>Dokter</th>
                                <th>No. Rujukan BPJS</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane show fade" id="rs_lain">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="check_online" value="Jalan" id="check_online">
                                    <label class='form-check-label' for="check_online">Check Online</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="cari_pasien_bpjs" placeholder="Cari Pasien" />
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped largeDataType" id="table-rujukan-lain">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Faskes</th>
                                        <th>Poli Tujuan</th>
                                        <th>Pasien</th>
                                        <th>Pelayanan</th>
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