<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Transact Print</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header card-header-large bg-white d-flex align-items-center">
            <h5 class="card-header__title flex m-0">Master Dokumen</h5>
            <button id="btnTambahTransact" class="btn btn-info pull-right">
                <span>
                    <i class="fa fa-plus"></i> Tambah Transact
                </span>
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped largeDataType" id="table-transact">
                <thead class="thead-dark">
                <tr>
                    <th class="wrap_content">No</th>
                    <th style="width: 20%">Identifier</th>
                    <th>Module</th>
                    <th class="wrap_content">Aksi</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>