<?php
$judul_laporan = 'Rekapitulasi Pendapatan';
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
                            <div class="col-md-5">
                                <table class="form-mode table">
                                    <tr>
                                        <td>Periode</td>
                                        <td class="wrap_content">:</td>
                                        <td>
                                            <select class="form-control" id="periode-laporan"></select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="btnCetak">
                                    <span>
                                        <i class="fa fa-print"></i> Cetak
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="badge badge-danger"></div>

                        <div class="px-3">
                            <div class="table-responsive">
                                <table class="table border-bottom table-bordered mb-5 largeDataType" id="tabel-laporan">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Jenis Pelayanan</th>
                                        <th>Ref Sub Pelayanan</th>
                                        <th class="wrap_content"></th>
                                        <th></th>
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