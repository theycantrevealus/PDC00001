<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/template/#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
				</ol>
			</nav>
			<h4 class="m-0">Dashboard</h4>
		</div>
		<!-- <a href="<?php echo __HOSTNAME__; ?>/template/" class="btn btn-success ml-3">New Report</a> -->
	</div>
</div>




<div class="container-fluid page__container">

	<!-- <div class="alert alert-soft-warning d-flex align-items-center card-margin" role="alert">
		<i class="material-icons mr-3">error_outline</i>
		<div class="text-body"><strong>API gateways are now Offline.</strong> Please try the API later. If you want to stay up to date follow our <a href="<?php echo __HOSTNAME__; ?>/template/">Status Page </a></div>
	</div> -->
	<!-- <div class="row card-group-row">
		<div class="col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<?php echo json_encode($_SESSION['akses_halaman_link']); ?>
			</div>
		</div>
	</div>

	<div class="row card-group-row">
		<div class="col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<?php echo json_encode($_SESSION['akses_halaman']); ?>
			</div>
		</div>
	</div> -->
	<div class="row card-group-row">
		<div class="col-lg-4 col-md-6 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="flex">
					<div class="card-header__title text-muted mb-2">Antrian Resepsionis</div>
					<div class="text-amount"><i class="material-icons">person</i> <span id="antrian_resepsionis">20</span></div>
					<!-- <div class="text-stats text-success">31.5% <i class="material-icons">arrow_upward</i></div> -->
				</div>
				<div><i class="material-icons icon-muted icon-40pt ml-3">confirmation_number</i></div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="flex">
					<div class="card-header__title text-muted mb-2">Pasien sedang berobat</div>
					<div class="text-amount"><i class="material-icons">person</i> 10</div>
					<!-- <div class="text-stats text-success">51.5% <i class="material-icons">arrow_upward</i></div> -->
				</div>
				<div><i class="material-icons icon-muted icon-40pt ml-3">local_hospital</i></div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="flex">
					<div class="card-header__title text-muted mb-2">Pasien selesai dilayani</div>
					<div class="text-amount"><i class="material-icons">person</i> 2</div>
				</div>
				<div><i class="material-icons icon-muted icon-40pt ml-3">check</i></div>
			</div>
		</div>
	</div>

	
	
</div>


<div class="container-fluid page__container">
	<div class="row">
		<div class="col-md-12">
			<div class="card-group">
				<div class="card card-body text-center">
					<div class="mb-1"><i class="material-icons icon-muted icon-40pt">security</i></div>
					<div class="text-amount">12 </div>
					<div class="card-header__title mb-2">Pasien BPJS</div>
				</div>
				<div class="card card-body text-center">
					<div class="mb-1"><i class="material-icons icon-muted icon-40pt">assessment</i></div>
					<div class="text-amount">2</div>
					<div class="card-header__title  mb-2">Pasien Umum</div>
				</div>
			</div>
		</div>
		
	</div>
	
</div>
	

<div class="container-fluid page__container">
	<div class="card">
		<div class="card-header card-header-large bg-white">
			<h4 class="card-header__title">Persentasi kunjungan</h4>
		</div>
		<div class="card-body">
			<!-- <p>An area chart or area graph displays graphically quantitative data. It is based on the line chart.</p> -->

			<div class="chart">
				<canvas id="performanceAreaChart" class="chart-canvas"></canvas>
			</div>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header card-header-large bg-white" style="text-align: center;">
					<h4 class="card-header__title">5 Penyakit Terbesar</h4>
				</div>
				<div class="card-body d-flex align-items-center justify-content-center" style="height: 210px;">
					<div class="row">
						<div class="col-7">
							<div class="chart" style="height: calc(210px - 1.25rem * 2);">
								<canvas id="sakitDoughnutChart" data-chart-legend="#sakitDoughnutChartLegend">
									<!-- <span style="font-size: 1rem;" class="text-muted"><strong>Location</strong> doughnut chart goes here.</span> -->
								</canvas>
							</div>
						</div>
						<div class="col-5">
							<div id="sakitDoughnutChartLegend" class="chart-legend chart-legend--vertical"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-header card-header-large bg-white" style="text-align: center;">
					<h4 class="card-header__title">5 Pemakaian Obat Terbesar</h4>
				</div>
				<div class="card-body d-flex align-items-center justify-content-center" style="height: 210px;">
					<div class="row">
						<div class="col-7">
							<div class="chart" style="height: calc(210px - 1.25rem * 2);">
								<canvas id="obatDoughnutChart" data-chart-legend="#obatDoughnutChartLegend">
									<!-- <span style="font-size: 1rem;" class="text-muted"><strong>Location</strong> doughnut chart goes here.</span> -->
								</canvas>
							</div>
						</div>
						<div class="col-5">
							<div id="obatDoughnutChartLegend" class="chart-legend chart-legend--vertical"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<div class="container-fluid page__container">
	<div class="row">
		<div class="col-md-6">
			<div class="card-group">
				<div class="card card-body text-center">
					<div class="card-header__title mb-2">Jumlah Seluruh Pasien</div>
					<div class="text-amount">65,241 <i class="material-icons">person</i></div>
				</div>
			</div>
		</div>
	</div>
</div>

