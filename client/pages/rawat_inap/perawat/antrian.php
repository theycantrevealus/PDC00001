<?php
/*$PoliList = array();
foreach ($_SESSION['poli']['response_data'] as $key => $value)
{
    foreach ($value['poli']['response_data'] as $PoliKey => $PoliValue)
    {
        array_push($PoliList, $PoliValue['uid']);
    }
}*/
?>
<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Rawat Jalan</li>
                    <li class="breadcrumb-item active" aria-current="page">Antrian Assesmen Awal</li>
                </ol>
            </nav>
            <h4>- Assesmen Awal</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-perawat">
                    <li class="nav-item tab-biasa">
                        <a href="#tab-assesment-awal-1" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
                                <i class="fa fa-address-book"></i>
							</span>
                            Asesmen Rawat Hal. 1
                        </a>
                    </li>
                    <li class="nav-item tab-biasa">
                        <a href="#tab-assesment-awal-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-address-book"></i>
							</span>
                            Asesmen Rawat Hal. 2
                        </a>
                    </li>
                    <li class="nav-item tab-irm" id="fisioterapi_nav">
                        <a href="#tab-assesment-terapis" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-running"></i>
							</span>
                            Terapis
                        </a>
                    </li>
                    <!--li class="nav-item tab-igd">
                        <a href="#tab-assesment-awal-igd-1" class="nav-link" data-toggle="tab" role="tab" aria-selected="false" aria-controls="tab-asesment-awal-igd-1">
							<span class="nav-link__count">
								01
							</span>
                            Askep IGD
                        </a>
                    </li>
                    <li class="nav-item tab-igd">
                        <a href="#tab-assesment-awal-igd-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
							</span>
                            Askep IGD
                        </a>
                    </li>
                    <li class="nav-item tab-igd">
                        <a href="#tab-assesment-awal-igd-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
							</span>
                            Askep IGD
                        </a>
                    </li>
                    <li class="nav-item tab-igd">
                        <a href="#tab-assesment-awal-igd-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								04
							</span>
                            Askep IGD
                        </a>
                    </li>
                    <li class="nav-item tab-igd-bid">
                        <a href="#tab-assesment-bidan-igd-1a" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								1a
							</span>
                            Askep [BIDAN]
                        </a>
                    </li>
                    <li class="nav-item tab-igd-bid">
                        <a href="#tab-assesment-bidan-igd-2a" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								2a
							</span>
                            Askep [BIDAN]
                        </a>
                    </li>
                    <li class="nav-item tab-igd-bid">
                        <a href="#tab-assesment-bidan-igd-3a" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								3a
							</span>
                            Askep [BIDAN]
                        </a>
                    </li>
                    <li class="nav-item tab-igd-bid">
                        <a href="#tab-assesment-bidan-igd-4a" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								4a
							</span>
                            Askep [BIDAN]
                        </a>
                    </li>
                    <li class="nav-item tab-igd-bid">
                        <a href="#tab-assesment-bidan-igd-5a" class="nav-link active" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								5a
							</span>
                            Askep [BIDAN]
                        </a>
                    </li-->
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade tab-biasa active" id="tab-assesment-awal-1">
                    <?php require 'halaman1.php'; ?>
                    <?php //require 'action_panel.php'; ?>
                </div>
                <div class="tab-pane show fade tab-biasa" id="tab-assesment-awal-2">
                    <?php require 'halaman2.php'; ?>
                    <?php //require 'action_panel.php'; ?>
                </div>
                <div class="tab-pane show fade tab-irm" id="tab-assesment-terapis">
                    <?php require 'fisioterapi.php'; ?>
                    <?php //require 'action_panel.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-awal-igd-1">
                    <?php require 'igd_1.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-awal-igd-2">
                    <?php require 'igd_2.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-awal-igd-3">
                    <?php require 'igd_3.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-awal-igd-4">
                    <?php require 'igd_4.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-bidan-igd-1a">
                    <?php require 'igd_1a.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-bidan-igd-2a">
                    <?php require 'igd_2a.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-bidan-igd-3a">
                    <?php require 'igd_3a.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-bidan-igd-4a">
                    <?php require 'igd_4a.php'; ?>
                </div>
                <div class="tab-pane show fade tab-igd" id="tab-assesment-bidan-igd-5a">
                    <?php require 'igd_5a.php'; ?>
                </div>
            </div>
            <div class="card card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" id="btnSelesai">
                            <i class="fa fa-check-circle"></i> Selesai
                        </button>
                        <a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/perawat" class="btn btn-danger">
                            <i class="fa fa-ban"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>