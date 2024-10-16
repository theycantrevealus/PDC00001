<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/kamar_operasi/jadwal">Kamar Operasi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Paket Obat Operasi</li>
                </ol>
            </nav>
            <h2 class="m-0">Paket Obat Operasi</h2>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-header__title flex m-0">
                <i class="fa fa-hashtag"></i> Paket Obat Operasi
                <button class="btn btn-info pull-right" id="btnTambahPaket">Tambah</button>
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped largeDataType" id="table_paket_obat">
                <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th>Nama Paket</th>
                        <th class="wrap_content">Varian Obat/BHP</th>
                        <th>Keterangan</th>
                        <th class="wrap_content">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>