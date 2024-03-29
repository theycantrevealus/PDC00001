<?php
$judul_laporan = 'Laporan Kamar Operasi';
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
                    <div class="card-header card-header-large bg-white">
                        <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> <?php echo $judul_laporan; ?></h5>
                    </div>
                    <div class="card-header bg-white">
                        <div class="row">
                            <!-- <div class="col-md-5">
                                <table class="form-mode table">
                                    <tr>
                                        <td>Penjamin</td>
                                        <td class="wrap_content">:</td>
                                        <td>
                                            <select class="form-control" id="txt_penjamin"></select>
                                        </td>
                                    </tr>
                                </table>
                            </div> -->
                            <div class="col-md-9">
                                <table class="form-mode table">
                                    <tr>
                                        <td>Tanggal</td>
                                        <td class="wrap_content">:</td>
                                        <td>
                                            <input id="range_laporan" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="btnCetak"><i class="fa fa-print"></i> Cetak</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="badge badge-danger"></div>

                        <div class="px-3">
                            <div class="table-responsive">
                                <table class="table border-bottom table-bordered mb-5" id="tabel-laporan">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>No. RM</th>
                                        <th>Pasien</th>
                                        <th>Dokter</th>
                                        <th>Jenis Operasi</th>
                                        <th>Ruangan</th>
                                        <th>Penjamin</th>
                                        <!-- <th>Aksi</th> -->
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