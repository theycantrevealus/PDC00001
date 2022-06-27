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
                        PRB
                        <button class="btn btn-sm btn-info pull-right" id="btnTambahPRB">
                            <i class="fa fa-plus"></i> Tambah PRB
                        </button>
                    </h5>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="rs_ini">
                        <table class="table table-bordered table-striped largeDataType" id="table-rujukan">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Tanggal</th>
                                <th class="wrap_content">No. SRB</th>
                                <th>Pasien</th>
                                <th>PRB</th>
                                <th>DPJP</th>
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