<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/inventori">Master Inventori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dokumen</li>
                </ol>
            </nav>
        </div>
        <!--<button class="btn btn-sm btn-info" id="tambah-gudang">
            <i class="fa fa-plus"></i> Tambah
        </button>-->
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Master Dokumen</h5>
                <a href="<?php echo __HOSTNAME__; ?>/master/dokumen/tambah" class="btn btn-info pull-right">
                    <i class="fa fa-plus"></i> Tambah Dokumen
                </a>
            </div>
            <div class="card card-group-row__card card-body">
                <table class="table table-bordered" id="table-dokumen">
                    <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Nama</th>
                            <th class="wrap_content">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>