<?php
    $judul_laporan = 'Laporan Kunjungan Rawat Jalan';
?>
<div class="mdk-header-layout__content">
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">
            <div class="container-fluid page__heading-container">
                <div class="page__heading d-flex align-items-center">
                    <div class="flex">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                                <li class="breadcrumb-item">Laporan</li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $judul_laporan; ?></li>
                            </ol>
                        </nav>
                        <h4 class="m-0"><?php echo $judul_laporan; ?></h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid page__container">
                <div class="card">
                    <div class="card-header card-header-large bg-white d-flex align-items-center">
                        <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> <?php echo $judul_laporan; ?></h5>
                        <table class="form-mode">
                            <tr>
                                <td>Tanggal</td>
                                <td class="wrap_content">:</td>
                                <td>
                                    <input id="range_laporan" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                </td>
                            </tr>
                        </table>
                        <button class="btn btn-primary" id="btnCetak"><i class="material-icons">local_printshop</i> Cetak</button>
                    </div>
                    <div class="card-body">
                        <div class="badge badge-danger"></div>

                        <div class="px-3">
                            <div class="table-responsive">
                                <table class="table border-bottom table-bordered mb-5" id="tabel-laporan">
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
    </div>
</div>