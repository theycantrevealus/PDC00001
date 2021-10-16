<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori">Inventori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengembalian Barang</li>
                </ol>
            </nav>
            <h4>Inventori - Pengembalian Barang</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-header__title flex m-0">
                Pengembalian Barang
                <a href="<?php echo __HOSTNAME__; ?>/inventori/return/tambah" class="btn btn-info pull-right" id="tambah-gudang">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table-return">
                <thead class="thead-dark">
                <tr>
                    <th class="wrap_content">No</th>
                    <th>Kode</th>
                    <th>Pemasok</th>
                    <th>Tanggal</th>
                    <th>Oleh</th>
                    <th class="wrap_content">Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>