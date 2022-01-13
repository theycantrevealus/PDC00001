<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item">Laboratorium</li>
                    <li class="breadcrumb-item active" aria-current="page">Antrian Laboratorium</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card-group">
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-2">
                            <i class="material-icons icon-muted icon-30pt">account_circle</i>
                        </div>
                        <div class="col-md-10">
                            <b><?php echo $_SESSION['nama']; ?></b>
                            <br />
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-12">
                            <b>Antrian</b>
                            <h5 class="text-info handy" id="current-poli">
                                <small><i class="fa fa-sync text-success" id="change-poli"></i></small>
                            </h5>
                            <b id="jlh-antrian">0</b> antrian
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body">
                <table class="table table-bordered largeDataType" id="table-lab">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th class="wrap_content">Kode</th>
                        <th>Nama</th>
                        <th>Spesimen</th>
                        <th class="wrap_content">Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>