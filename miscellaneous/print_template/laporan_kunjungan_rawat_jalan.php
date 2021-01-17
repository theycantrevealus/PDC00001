<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <div class="badge badge-danger"></div>

                <div class="px-3">
                    <div class="d-flex justify-content-center flex-column text-center my-5 navbar-light">
                        <a href="index.html" class="navbar-brand d-flex flex-column m-0" style="min-width: 0">
                            <img class="navbar-brand-icon mb-2" src="<?php echo __HOSTNAME__; ?>/template/assets/images/logo-text-white.png" width="50" alt="<?php echo __PC_CUSTOMER__; ?>">
                            <span><?php echo $judul_laporan; ?></span>
                        </a>
                        <div class="text-muted">Periode : <span id="periode"></span></div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-lg">
                            <div class="text-label">Dilaporkan Oleh</div>
                            <p class="mb-4">
                                <strong class="text-body" id="nama_saya"></strong>
                            </p>
                        </div>
                        <div class="col-lg text-right">
                            <div class="text-label">Tanggal Cetak</div>
                            <p class="mb-4">
                                <strong class="text-body"><?php echo date('d F Y'); ?></strong>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table border-bottom mb-5">
                            <thead class="thead-dark">
                            <tr>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Keluar</th>
                                <th>Nama Pasien</th>
                                <th>Alamat</th>
                                <th>Perusahaan Penjamin</th>
                                <th>Rekam Medis</th>
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