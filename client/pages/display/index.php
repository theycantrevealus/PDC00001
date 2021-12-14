<div class="anjungan-container" style="overflow: hidden;">
	<div class="container-fluid" style="padding: 0 !important; overflow: hidden;">
		<div class="row">
			<div class="col-md-12">
				<div class="card" style="background: transparent;">
					<div class="card-body" style="background: rgba(255, 255, 255, .7);">
						<table>
							<tr>
								<td>
									<!--<img style="position: absolute; width: 250px; height: 250px; top: -60px; left: 20px;" width="220" height="220" src="<?php /*echo __HOSTNAME__; */?>/template/assets/images/logo-text-white.png" />-->
                                    <img style="position: absolute; width: 120px; height: 100px; top: 5px; left: 20px;" width="400" height="50" src="<?php echo __HOSTNAME__; ?>/template/assets/images/clients/logo-icon-<?php echo __PC_IDENT__; ?>.png" />
								</td>
								<td style="padding-left: 220px;">
									<h3 style="color: #fff"><?php echo __PC_CUSTOMER__; ?></h3>
									<!--<i class="fa fa-map-marker" style="color: red;"></i> <span style="color: #fff">Jalan Dr. Soetomo No. 65, Sekip, LimaPuluh, Kota Pekanbaru, Riau 28155. Telp. (0761)23024</span>-->
                                    <i class="fa fa-map-marker" style="color: red;"></i> <span style="color: #fff"><?php echo __PC_CUSTOMER_ADDRESS__; ?></span>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="overflow: hidden; height: 100%;">
		<div class="row">
			<div class="col-md-6" style="position: absolute; height: 100%;">
				<center>
					<h1 style="padding: 10px !important; color: yellow; font-weight: bold;">ANTRIAN</h1>

					<div style="background: #000; border-radius: 10px; padding: 40px 20px">
						<h1 class="blink_me" id="current_antrian" style="font-size: 80pt !important; color: #fff">0</h1>
					</div>
				</center>
				<div class="row" id="loket-loader">
					
				</div>
			</div>
			<div class="col-md-6" style="left: 50%; height: 100%; position: absolute; padding-bottom: 100px;">
				<div class="row">
					<div class="col-md-12 bg-white" style="height: 100%; position: absolute; padding-top: 20px; overflow-y: scroll;">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body tab-content">
									<div class="tab-pane active show fade" id="kamar-tersedia">
										<div class="col-md-12">
											<h5 class="card-header__title flex m-0">Ketersediaan Ruang</h5>
											<div id="info-kamar" class="carousel slide" data-ride="carousel">
												<div class="carousel-inner">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--div class="col-md-12">
							<div class="card">
								<div class="card-header card-header-large bg-white d-flex align-items-center">
									<h5 class="card-header__title flex m-0">Kunjungan Per Unit</h5>
								</div>
								<div class="card-body tab-content">
									<div class="tab-pane active show fade" id="poli-pelayanan">
										<div class="col-md-12">
											<table class="table table-bordered" id="table-poli-pelayanan">
												<thead class="thead-dark">
													<tr>
														<th class="wrap_content">No</th>
														<th style="width: 50%;">Unit Pelayanan</th>
														<th class="wrap_content">Jlh</th>
														<th></th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div-->
					</div>
					<!-- <div class="col-md-12">
						<div id="carousel-slider" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">

							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div id="info-kamar" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
							</div>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	.carousel-item {
		min-height: 500px !important;
	}
</style>