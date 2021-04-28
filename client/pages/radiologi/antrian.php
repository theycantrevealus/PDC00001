<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/radiologi">Radiologi</a></li>
					<li class="breadcrumb-item active" aria-current="page">Hasil Pemeriksaan Radiologi</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="z-0">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="nav_list">
					<li class="nav-item">
						<a href="#tab-radiologi-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-radiologi-1" >
							<span class="nav-link__count">
								01
								<b class="inv-tab-status text-success" id="status-1"><i class="fa fa-check-circle"></i></b>
							</span>
							Hasil Pemeriksaan
						</a>
					</li>
					<li class="nav-item">
						<a href="#tab-radiologi-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								02
								<b class="inv-tab-status text-success" id="status-3"><i class="fa fa-check-circle"></i></b>
							</span>
							Lampiran Pemeriksaan
						</a>
					</li>
				</ul>
			</div>
            <form id="formRadioSimpan">
                <div class="card card-body tab-content" id="content_list">
                    <div class="tab-pane show fade active" id="tab-radiologi-1">
                        <?php require 'hasil.php'; ?>
                        <?php //require 'action_panel.php'; ?>
                    </div>
                    <div class="tab-pane show fade " id="tab-radiologi-2">
                        <?php require 'gambar.php'; ?>
                        <?php //require 'action_panel.php'; ?>
                    </div>

                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info" id="btnSimpan">
                                <i class="fa fa-save"></i> Simpan
                            </button>

                            <a href="<?php echo __HOSTNAME__; ?>/radiologi" class="btn btn-danger">
                                <i class="fa fa-ban"></i> Kembali
                            </a>
                            <button class="btn btn-success pull-right" id="btnSelesai">
                                <i class="fa fa-check-circle"></i> Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>