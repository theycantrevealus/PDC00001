<?php
    $PoliList = array();
    foreach ($_SESSION['poli']['response_data'] as $key => $value)
    {
        foreach ($value['poli']['response_data'] as $PoliKey => $PoliValue)
        {
            array_push($PoliList, $PoliValue['uid']);
        }
    }
?>
<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap/dokter">Rawat Inap</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap/dokter/index/<?php echo __PAGES__[4]; ?>/<?php echo __PAGES__[5]; ?>"><b id="target_pasien"></b></a></li>
					<li class="breadcrumb-item active" aria-current="page">Pemeriksaan Medis</li>
				</ol>
			</nav>
            <h4><span id="nama-departemen"></span> - Pemeriksaan <b class="text-info" id="heading_nama_poli">Rawat IGD</b></h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-inap">
					<li class="nav-item">
						<a href="#tab-poli-1" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								<i class="fa fa-address-book"></i>
							</span>
							Asesmen Rawat
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-2" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								<i class="fa fa-briefcase-medical"></i>
							</span>
							Asesmen Medis
						</a>
					</li>
                    <?php
                    if(in_array(__UIDFISIOTERAPI__, $PoliList)) {
                    ?>
                    <li class="nav-item">
                        <a href="#tab-poli-9" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                        <span class="nav-link__count">
                            <i class="fa fa-running"></i>
                        </span>
                            Fisioterapi
                        </a>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item">
						<a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-child"></i>
							</span>
							Tindakan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-pills"></i>
							</span>
							Resep
						</a>
					</li>
					<!--li class="nav-item">
						<a href="#tab-poli-5" class="nav-link" data-toggle="tab" role="tab" aria-selected="false" disabled>
							<span class="nav-link__count">
								<i class="fa fa-flask"></i>
							</span>
							Laboratorium
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-6" class="nav-link" data-toggle="tab" role="tab" aria-selected="false" disabled>
							<span class="nav-link__count">
								<i class="fa fa-life-ring"></i>
							</span>
							Radiologi
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-poli-7" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-tasks"></i>
							</span>
							CPPT
						</a>
                    </li-->
                    <li class="nav-item">
						<a href="#tab-poli-8" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-print"></i>
							</span>
							Dokumen
						</a>
					</li>
				</ul>
			</div>
			<div class="card card-body tab-content">
				<div class="tab-pane show fade perawat" id="tab-poli-1">
					<?php require 'info-pasien.php'; ?>
					<?php require 'perawat/form.php'; ?>
				</div>
				<div class="tab-pane show fade active" id="tab-poli-2">
					<?php require 'info-pasien.php'; ?>
                    <?php
                    if(in_array(__POLI_GIGI__, $PoliList)) {
                        require 'asesmen-gigi.php';
                    } else if(in_array(__POLI_MATA__, $PoliList)) {
                        require 'asesmen-mata.php';
                    }
                    require 'asesmen-awal.php';
                    ?>
				</div>
                <div class="tab-pane show fade" id="tab-poli-9">
                    <?php require 'info-pasien.php'; ?>
                    <?php require 'fisioterapi.php'; ?>
                </div>
                <div class="tab-pane show fade" id="tab-poli-3">
					<?php require 'info-pasien.php'; ?>
					<?php require 'tindakan.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-4">
					<?php require 'info-pasien.php'; ?>
					<?php require 'resep.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-5">
					<?php require 'info-pasien.php'; ?>
					<?php require 'laboratorium.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-6">
					<?php require 'info-pasien.php'; ?>
					<?php require 'radiologi.php'; ?>
				</div>
				<div class="tab-pane show fade" id="tab-poli-7">
					<?php require 'info-pasien.php'; ?>
					<?php require 'cppt.php'; ?>
				</div>
                <div class="tab-pane show fade" id="tab-poli-8">
                    <?php require 'info-pasien.php'; ?>
                    <?php require 'dokumen.php'; ?>
                </div>
                <?php require 'action-panel.php'; ?>
			</div>
		</div>
	</div>
</div>