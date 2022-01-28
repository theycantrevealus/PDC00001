<?php
    $judul_laporan = 'Laporan Pemakaian Obat';

    $yesterday = new DateTime(date('Y-m-d')); // For today/now, don't pass an arg.
    $yesterday->modify("-1 day");

    $tomorrow = new DateTime(date('Y-m-d'));
    $tomorrow->modify("+1 day");
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
                    <div class="card-header card-header-tabs-basic nav" role="tablist">
                        <a href="#perpenjamin" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Penjamin</a>
                        <a href="#perpasien" data-toggle="tab" role="tab" aria-selected="false">Pasien</a>
                    </div>
                    <div class="card-body">
                        <div class="card-body tab-content" style="min-height: 100px;">
                            <div class="tab-pane active show fade" id="perpenjamin">
                                <div class="row">
                                    <div class="col-md-9">
                                        <input id="range_laporan" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $yesterday->format("Y-m-d"); ?> to <?php echo $tomorrow->format("Y-m-d"); ?>" />
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary" id="btnCetak">
                                            <span>
                                                <i class="fa fa-print"></i> Cetak
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <br />
                                <div class="table-responsive">
                                    <table class="table border-bottom table-bordered" id="tabel-laporan-obat-penjamin">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 30%">Obat/BHP</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="perpasien">
                                <table class="table border-bottom table-bordered" id="tabel-laporan-obat-pasien">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat/BHP</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
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