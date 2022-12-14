<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/kamar_operasi">Kamar Operasi</a></li>
					<li class="breadcrumb-item active" aria-current="page">Pemeriksaan Medis</li>
				</ol>
			</nav>
            <h4><span id="nama-departemen"></span> - Asesmen<b class="text-info" id="heading_nama_poli"></b></h4>
		</div>
	</div>
</div>

<div class="row card-group-row">
    <div class="col-lg-12 col-md-12">
        <div class="z-0">
            <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
            
                <li class="nav-item">
                    <a href="#tab-poli-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								<i class="fa fa-briefcase-medical"></i>
							</span>
                        Laporan Pembedahan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								<i class="fa fa-briefcase-medical"></i>
							</span>
                        Instalasi Bedah Sentral
                    </a>
                </li>
               
            </ul>
        </div>
        <div class="card card-body tab-content">
            <div class="tab-pane show fade active" id="tab-poli-1">
                <?php require 'info-pasien.php'; ?>
                <?php
                
                require 'laporan-pembedahan.php';
                ?>
            </div>
            <div class="tab-pane show fade" id="tab-poli-2">
                <?php
                
                require 'instalasi-bedah-sentral.php';
                ?>
            </div>
        </div>
        <div class="card card-footer custom-card-footer">
            <div class="row">
                <div class="col-md-4">
                    <!-- <button type="button" class="btn btn-success" id="btnSelesai">
                        <i class="fa fa-check-circle"></i> Selesai
                    </button> -->
                </div>
            </div>
        </div>
    </div>
</div>