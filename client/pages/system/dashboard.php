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
		<!--a href="<?php echo __HOSTNAME__; ?>/template/" class="btn btn-success ml-3">New Report</a-->
	</div>
</div>




<div class="container-fluid page__container">

	<div class="alert alert-soft-warning d-flex align-items-center card-margin" role="alert">
		<i class="material-icons mr-3">error_outline</i>
		<div class="text-body"><strong>Selamat Datang.</strong> <?php echo __PC_CUSTOMER__; ?></div>
	</div>
    <?php
    if($_SESSION['jabatan']['response_data'][0]['nama'] === 'Administrator') {
        ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                    <li class="nav-item">
                        <a href="#master_labor" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								<i class="fa fa-address-book"></i>
							</span>
                            Laboratorium
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#master_radio" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								<i class="fa fa-address-book"></i>
							</span>
                            Radiologi
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="master_labor">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header card-header-large bg-white">
                                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Item Laboratorium tanpa nilai uji</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered largeDataType" id="monitoring_master_labor">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Nama</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="master_radio">
                </div>
            </div>
        </div>
    </div>
        <?php
    }
    ?>

	<!--div class="row card-group-row">
		<div class="col-lg-4 col-md-6 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="flex">
					<div class="card-header__title text-muted mb-2">Current Target</div>
					<div class="text-amount">&dollar;12,920</div>
					<div class="text-stats text-success">31.5% <i class="material-icons">arrow_upward</i></div>
				</div>
				<div><i class="material-icons icon-muted icon-40pt ml-3">gps_fixed</i></div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="flex">
					<div class="card-header__title text-muted mb-2">Earnings</div>
					<div class="text-amount">&dollar;3,642</div>
					<div class="text-stats text-success">51.5% <i class="material-icons">arrow_upward</i></div>
				</div>
				<div><i class="material-icons icon-muted icon-40pt ml-3">monetization_on</i></div>
			</div>
		</div>
		<div class="col-lg-4 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="flex">
					<div class="card-header__title text-muted mb-2">Website Traffic</div>
					<div class="text-amount">8,391</div>
					<div class="text-stats text-danger">3.5% <i class="material-icons">arrow_downward</i></div>
				</div>
				<div><i class="material-icons icon-muted icon-40pt ml-3">perm_identity</i></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-7">
			<div class="card dashboard-area-tabs" id="dashboard-area-tabs">
				<div class="card-header p-0 bg-white nav">
					<div class="row no-gutters flex" role="tablist">
						<div class="col" data-toggle="chart" data-target="#earningsTrafficChart" data-update='{"data":{"datasets":[{"label": "Traffic", "data":[10,2,5,15,10,12,15,25,22,30,25,40]}]}}' data-prefix="" data-suffix="k">
							<a href="<?php echo __HOSTNAME__; ?>/template/#" data-toggle="tab" role="tab" aria-selected="true" class="dashboard-area-tabs__tab card-body text-center active">
								<span class="card-header__title m-0">Sessions</span>
								<span class="text-amount">18,391</span>
							</a>
						</div>
						<div class="col border-left" data-toggle="chart" data-target="#earningsTrafficChart" data-update='{"data":{"datasets":[{"label": "Earnings", "data":[7,35,12,27,34,17,19,30,28,32,24,39]}]}}' data-prefix="$" data-suffix="k">
							<a href="<?php echo __HOSTNAME__; ?>/template/#" data-toggle="tab" role="tab" aria-selected="false" class="dashboard-area-tabs__tab card-body text-center">
								<span class="card-header__title m-0">Orders</span>
								<span class="text-amount">&dollar;8,942</span>
							</a>
						</div>
					</div>
				</div>
				<div class="card-body text-muted" style="height: 280px;">
					<div class="chart" style="height: calc(280px - 1.25rem * 2);">
						<canvas id="earningsTrafficChart">
							<span style="font-size: 1rem;"><strong>Website Traffic / Earnings</strong> area chart goes here.</span>
						</canvas>
					</div>
				</div>
			</div>



			<div class="card-group">
				<div class="card card-body text-center">
					<div class="mb-1"><i class="material-icons icon-muted icon-40pt">assessment</i></div>
					<div class="text-amount">3,642 </div>
					<div class="card-header__title mb-2">Visits</div>
				</div>
				<div class="card card-body text-center">
					<div class="mb-1"><i class="material-icons icon-muted icon-40pt">shopping_basket</i></div>
					<div class="text-amount">&dollar;12,311</div>
					<div class="card-header__title  mb-2">Purchases</div>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="card">
				<div class="card-header card-header-large bg-white">
					<h4 class="card-header__title">Revenue by location</h4>
				</div>
				<div class="card-body">
					<div id="vector-map-revenue" class="map mb-3" data-toggle="vector-map" data-vector-map-map="world_en" data-vector-map-show-tooltip="false" data-vector-map-enable-zoom="true" data-vector-map-scale="1.1" data-vector-map-pins='{
"it": "<div class=\"map-pin blue\"><span>Vatican City</span></div>",
"us": "<div class=\"map-pin blue\"><span>New York</span></div>",
"au": "<div class=\"map-pin blue\"><span>Sydney</span></div>"
}'>
					</div>

					<ul class="list-unstyled dashboard-location-tabs nav flex-column m-0" role="tablist">
						<li data-toggle="vector-map-focus" data-target="#vector-map-revenue" data-focus="us" data-animate="true">
							<div class="dashboard-location-tabs__tab active" data-toggle="tab" role="tab" aria-selected="true">
								<div><strong>New York</strong></div>
								<div class="d-flex align-items-center">
									<div class="flex mr-2">
										<div class="progress" style="height: 6px;">
											<div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div>72k</div>
								</div>
							</div>
						</li>
						<li data-toggle="vector-map-focus" data-target="#vector-map-revenue" data-focus="it" data-animate="true">
							<div class="dashboard-location-tabs__tab" data-toggle="tab" role="tab" aria-selected="true">
								<div><strong>Vatican City</strong></div>
								<div class="d-flex align-items-center">
									<div class="flex mr-2">
										<div class="progress" style="height: 6px;">
											<div class="progress-bar bg-primary" role="progressbar" style="width: 39%;" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div>39k</div>
								</div>
							</div>
						</li>
						<li data-toggle="vector-map-focus" data-target="#vector-map-revenue" data-focus="au" data-animate="true">
							<div class="dashboard-location-tabs__tab" data-toggle="tab" role="tab" aria-selected="true">
								<div><strong>Sydney</strong></div>
								<div class="d-flex align-items-center">
									<div class="flex mr-2">
										<div class="progress" style="height: 6px;">
											<div class="progress-bar bg-primary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div>25k</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg">
			<div class="card">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h4 class="card-header__title flex m-0">Recent Activity</h4>
					<div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
						<a href="<?php echo __HOSTNAME__; ?>/template/javascript:void(0)" class="link-date" data-toggle>13/03/2018 <span class="text-muted mx-1">to</span> 20/03/2018</a>
						<input class="d-none" type="hidden" value="13/03/2018 to 20/03/2018" data-input>
					</div>
				</div>
				<div class="card-header card-header-tabs-basic nav" role="tablist">
					<a href="<?php echo __HOSTNAME__; ?>/template/#activity_all" class="active" data-toggle="tab" role="tab" aria-controls="activity_all" aria-selected="true">All</a>
					<a href="<?php echo __HOSTNAME__; ?>/template/#activity_purchases" data-toggle="tab" role="tab" aria-controls="activity_purchases" aria-selected="false">Purchases</a>
					<a href="<?php echo __HOSTNAME__; ?>/template/#activity_emails" data-toggle="tab" role="tab" aria-controls="activity_emails" aria-selected="false">Emails</a>
					<a href="<?php echo __HOSTNAME__; ?>/template/#activity_quotes" data-toggle="tab" role="tab" aria-controls="activity_quotes" aria-selected="false">Quotes</a>
				</div>
				<div class="list-group tab-content list-group-flush">
					<div class="tab-pane active show fade" id="activity_all">


						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-purple">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>


							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_rsz_1andy-lee-642320-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>


									<strong class="text-15pt mr-1">Jenell D. Matney</strong>
								</div>
								<small class="text-muted">4 days ago</small>
							</div>
							<div>$573</div>


							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-teal">
									<i class="material-icons">email</i>
								</span>
							</div>


							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_daniel-gaffey-1060698-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>


									<strong class="text-15pt mr-1">Sherri J. Cardenas</strong>
								</div>
								<small>Improve spacings on Projects page</small>
							</div>
							<small class="text-muted">3 days ago</small>


							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  ">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>


							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_jeremy-banks-798787-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>


									<strong class="text-15pt mr-1">Joseph S. Ferland</strong>
								</div>
								<small class="text-muted">2 days ago</small>
							</div>
							<div>$244</div>


							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  ">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>


							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_joao-silas-636453-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>


									<strong class="text-15pt mr-1">Bryan K. Davis</strong>
								</div>
								<small class="text-muted">1 day ago</small>
							</div>
							<div>$664</div>


							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  ">
									<i class="material-icons">description</i>
								</span>
							</div>


							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_luke-porter-261779-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>


									<strong class="text-15pt mr-1">Kaci M. Langston</strong>
								</div>
								<small class="text-muted">just now</small>
							</div>
							<div>$631</div>


							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="card-footer text-center border-0">
							<a class="text-muted" href="<?php echo __HOSTNAME__; ?>/template/">View All (54)</a>
						</div>
					</div>
					<div class="tab-pane" id="activity_purchases">

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-purple">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_rsz_1andy-lee-642320-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Sherri J. Cardenas</strong>

								</div>
								<small class="text-muted">4 days ago</small>
							</div>
							<div>$573</div>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-purple">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_daniel-gaffey-1060698-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Joseph S. Ferland</strong>

								</div>
								<small class="text-muted">3 days ago</small>
							</div>
							<div>$612</div>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-purple">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_jeremy-banks-798787-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Bryan K. Davis</strong>

								</div>
								<small class="text-muted">2 days ago</small>
							</div>
							<div>$244</div>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle ">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_joao-silas-636453-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Kaci M. Langston</strong>

								</div>
								<small class="text-muted">1 day ago</small>
							</div>
							<div>$664</div>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle ">
									<i class="material-icons">monetization_on</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_michael-dam-258165-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1"></strong>

								</div>
								<small class="text-muted">just now</small>
							</div>
							<div>$631</div>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

					</div>
					<div class="tab-pane" id="activity_emails">

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-teal">
									<i class="material-icons">email</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_rsz_1andy-lee-642320-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Jenell D. Matney</strong>

								</div>
								<small>Confirmation required for design</small>
							</div>
							<small class="text-muted">4 days ago</small>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-teal">
									<i class="material-icons">email</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_daniel-gaffey-1060698-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Sherri J. Cardenas</strong>

								</div>
								<small>Improve spacings on Projects page</small>
							</div>
							<small class="text-muted">3 days ago</small>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle  bg-teal">
									<i class="material-icons">email</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_jeremy-banks-798787-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Joseph S. Ferland</strong>

								</div>
								<small>You unlocked a new Badge</small>
							</div>
							<small class="text-muted">2 days ago</small>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle ">
									<i class="material-icons">email</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_joao-silas-636453-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Bryan K. Davis</strong>

								</div>
								<small>Meeting on Friday</small>
							</div>
							<small class="text-muted">1 day ago</small>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

						<div class="list-group-item list-group-item-action d-flex align-items-center  bg-light ">
							<div class="avatar avatar-xs mr-3">
								<span class="avatar-title rounded-circle ">
									<i class="material-icons">email</i>
								</span>
							</div>

							<div class="flex">
								<div class="d-flex align-items-middle">
									<div class="avatar avatar-xxs mr-1">
										<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_luke-porter-261779-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
									</div>
									<strong class="text-15pt mr-1">Kaci M. Langston</strong>

								</div>
								<small>Design a new Brochure</small>
							</div>
							<small class="text-muted">just now</small>
							<i class="material-icons icon-muted ml-3">arrow_forward</i>
						</div>

					</div>
					<div class="tab-pane" id="activity_quotes"></div>
				</div>
			</div>
		</div>
		<div class="col-lg">
			<div class="row card-group-row">
				<div class="col-lg-6 card-group-row__col">
					<div class="card card-group-row__card card-body card-body-x-lg" style="position: relative; padding-bottom: calc(80px - 1.25rem); overflow: hidden; z-index: 0;">
						<div class="card-header__title text-muted mb-2">Products</div>
						<div class="text-amount">&dollar;8,391</div>
						<div class="text-stats text-success">31.5% <i class="material-icons">arrow_upward</i></div>
						<div class="chart" style="height: 80px; position: absolute; left: 0; right: 0; bottom: 0;">
							<canvas id="productsChart"></canvas>
						</div>
					</div>
				</div>
				<div class="col-lg-6 card-group-row__col">
					<div class="card card-group-row__card card-body card-body-x-lg" style="position: relative; padding-bottom: calc(80px - 1.25rem); overflow: hidden; z-index: 0;">
						<div class="card-header__title text-muted mb-2">Courses</div>
						<div class="text-amount">15,021</div>
						<div class="text-stats text-danger">31.5% <i class="material-icons">arrow_downward</i></div>
						<div class="chart" style="height: 80px; position: absolute; left: 0; right: 0; bottom: 0;">
							<canvas id="coursesChart"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h4 class="card-header__title flex m-0">Stats by Location</h4>
					<i class="material-icons icon-muted ml-3">expand_more</i>
				</div>
				<div class="card-header card-header-tabs-basic nav justify-content-center" role="tablist">
					<div data-toggle="chart" data-target="#locationDoughnutChart" data-update='{"data":{
"labels": ["United States", "United Kingdom", "Germany", "India"], 
"datasets": [{"label": "Traffic", "data":[25,25,15,35]}]
}}'>
						<a href="<?php echo __HOSTNAME__; ?>/template/#" class="active" data-toggle="tab" role="tab" aria-selected="true">
							Traffic
						</a>
					</div>
					<div data-toggle="chart" data-target="#locationDoughnutChart" data-update='{"data":{
"labels": ["United States", "United Kingdom", "Germany", "India"], 
"datasets": [{"label": "Purchases", "data":[15,17,25,43]}]
}}'>
						<a href="<?php echo __HOSTNAME__; ?>/template/#" data-toggle="tab" role="tab" aria-selected="false">
							Purchases
						</a>
					</div>
					<div data-toggle="chart" data-target="#locationDoughnutChart" data-update='{"data":{
"labels": ["United States", "United Kingdom", "Germany", "India"], 
"datasets": [{"label": "Quotes", "data":[53,17,25,5]}]
}}'>
						<a href="<?php echo __HOSTNAME__; ?>/template/#" data-toggle="tab" role="tab" aria-selected="false">
							Quotes
						</a>
					</div>
				</div>
				<div class="card-body d-flex align-items-center justify-content-center" style="height: 210px;">
					<div class="row">
						<div class="col-7">
							<div class="chart" style="height: calc(210px - 1.25rem * 2);">
								<canvas id="locationDoughnutChart" data-chart-legend="#locationDoughnutChartLegend">
									<span style="font-size: 1rem;" class="text-muted"><strong>Location</strong> doughnut chart goes here.</span>
								</canvas>
							</div>
						</div>
						<div class="col-5">
							<div id="locationDoughnutChartLegend" class="chart-legend chart-legend--vertical"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg">
			<div class="card">
				<div class="card-header card-header-large bg-white d-flex align-items-center">
					<h4 class="card-header__title flex m-0">TODO List</h4>
					<div><a href="<?php echo __HOSTNAME__; ?>/template/#" data-target="#todo" class="js-toggle-check-all">Mark All as Completed</a></div>
				</div>
				<div class="card-body bg-light">
					<ul class="list-unstyled list-todo" id="todo">
						<li>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" checked="" id="customCheck1">
								<label class="custom-control-label" for="customCheck1">Wireframe the CRM application pages</label>
							</div>
						</li>
						<li>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customCheck2">
								<label class="custom-control-label" for="customCheck2">Design a new page in Sketch</label>
							</div>
						</li>
						<li>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customCheck3">
								<label class="custom-control-label" for="customCheck3">Quote the custom work</label>
							</div>
						</li>
						<li>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customCheck4">
								<label class="custom-control-label" for="customCheck4">Interview John for Full-Stack Developer</label>
							</div>
						</li>
						<li>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customCheck5">
								<label class="custom-control-label" for="customCheck5">Research the success of CRM</label>
							</div>
						</li>
					</ul>
				</div>
				<div class="card-footer text-center">
					15 <span class="text-muted">of 100</span> <a href="<?php echo __HOSTNAME__; ?>/template/#"><i class="material-icons icon-muted float-right">arrow_forward</i></a>
				</div>
			</div>
		</div>
		<div class="col-lg">
			<div class="card">
				<div class="card-header card-header-large bg-white">
					<h4 class="card-header__title">Team Skills</h4>
				</div>
				<div class="card-body">
					<ul class="list-unstyled list-skills">
						<li>
							<div>HTML</div>
							<div class="flex">
								<div class="progress" style="height: 6px;">
									<div class="progress-bar" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<div class="text-dark-gray"><strong>61%</strong></div>
						</li>
						<li>
							<div>CSS/SCSS</div>
							<div class="flex">
								<div class="progress" style="height: 6px;">
									<div class="progress-bar bg-success" role="progressbar" style="width: 39%;" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<div class="text-dark-gray"><strong>39%</strong></div>
						</li>
						<li>
							<div>JAVASCRIPT</div>
							<div class="flex">
								<div class="progress" style="height: 6px;">
									<div class="progress-bar bg-dark-gray" role="progressbar" style="width: 76%;" aria-valuenow="76" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<div class="text-dark-gray"><strong>76%</strong></div>
						</li>
						<li>
							<div>RUBY ON RAILS</div>
							<div class="flex">
								<div class="progress" style="height: 6px;">
									<div class="progress-bar bg-danger" role="progressbar" style="width: 28%;" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<div class="text-dark-gray"><strong>28%</strong></div>
						</li>
						<li>
							<div>VUEJS</div>
							<div class="flex">
								<div class="progress" style="height: 6px;">
									<div class="progress-bar bg-dark" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<div class="text-dark-gray"><strong>50%</strong></div>
						</li>
					</ul>
				</div>
				<div class="card-footer text-center border-0">
					<a href="<?php echo __HOSTNAME__; ?>/template/#">
						<span class="text-muted">View All</span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header card-header-large bg-white">
			<h4 class="card-header__title">Current Users</h4>
		</div>
		<div class="card-header">
			<form class="form-inline">
				<label class="mr-sm-2" for="inlineFormFilterBy">Filter by:</label>
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormFilterBy" placeholder="Type a name">

				<label class="sr-only" for="inlineFormRole">Role</label>
				<select id="inlineFormRole" class="custom-select mb-2 mr-sm-2 mb-sm-0">
					<option value="All Roles">All Roles</option>
				</select>

				<div class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
					<input type="checkbox" class="custom-control-input" id="inlineFormPurchase">
					<label class="custom-control-label" for="inlineFormPurchase">Made a Purchase?</label>
				</div>
			</form>
		</div>


		<div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

			<table class="table mb-0 thead-border-top-0">
				<thead>
					<tr>

						<th style="width: 18px;">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#staff" id="customCheckAll">
								<label class="custom-control-label" for="customCheckAll"><span class="text-hide">Toggle all</span></label>
							</div>
						</th>

						<th>Employee</th>
						<th style="width: 150px;">Current Employer</th>
						<th style="width: 48px;">Projects</th>
						<th style="width: 37px;">Status</th>
						<th style="width: 120px;">Last Activity</th>
						<th style="width: 51px;">Earnings</th>
						<th style="width: 24px;"></th>
					</tr>
				</thead>
				<tbody class="list" id="staff">

					<tr class="selected">

						<td>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-check-selected-row" checked="" id="customCheck1_1">
								<label class="custom-control-label" for="customCheck1_1"><span class="text-hide">Check</span></label>
							</div>
						</td>

						<td>

							<div class="media align-items-center">
								<div class="avatar avatar-xs mr-2">
									<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_luke-porter-261779-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
								</div>
								<div class="media-body">

									<span class="js-lists-values-employee-name">Michael Smith</span>

								</div>
							</div>

						</td>

						<td>
							<div class="media align-items-center">
								<a href="<?php echo __HOSTNAME__; ?>/template/">Black Ops</a>
								<a href="<?php echo __HOSTNAME__; ?>/template/#" class="rating-link"><i class="material-icons ml-2">star</i></a>
							</div>
						</td>

						<td>12</td>
						<td><span class="badge badge-warning">ADMIN</span></td>
						<td><small class="text-muted">3 days ago</small></td>
						<td>&dollar;12,402</td>
						<td><a href="<?php echo __HOSTNAME__; ?>/template/" class="text-muted"><i class="material-icons">more_vert</i></a></td>
					</tr>
					<tr>

						<td>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck2_1">
								<label class="custom-control-label" for="customCheck2_1"><span class="text-hide">Check</span></label>
							</div>
						</td>

						<td>

							<div class="media align-items-center">
								<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/avatar/green.svg" class="mr-2" alt="avatar" />
								<div class="media-body">

									<span class="js-lists-values-employee-name">Connie Smith</span>

								</div>
							</div>

						</td>

						<td>
							<div class="media align-items-center">
								<a href="<?php echo __HOSTNAME__; ?>/template/#">Backend Ltd</a>
								<a href="<?php echo __HOSTNAME__; ?>/template/#" class="rating-link active"><i class="material-icons ml-2">star</i></a>
							</div>
						</td>

						<td>42</td>
						<td><span class="badge badge-success">USER</span></td>
						<td><small class="text-muted">1 week ago</small></td>
						<td>&dollar;1,943</td>
						<td><a href="<?php echo __HOSTNAME__; ?>/template/" class="text-muted"><i class="material-icons">more_vert</i></a></td>
					</tr>
					<tr>

						<td>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck3_1">
								<label class="custom-control-label" for="customCheck3_1"><span class="text-hide">Check</span></label>
							</div>
						</td>

						<td>

							<div class="media align-items-center">

								<div class="avatar avatar-xs mr-2">
									<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_daniel-gaffey-1060698-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
								</div>
								<div class="media-body">

									<span class="js-lists-values-employee-name">John Connor</span>

								</div>
							</div>

						</td>

						<td>
							<div class="media align-items-center">
								<a href="<?php echo __HOSTNAME__; ?>/template/">Frontted</a>
								<a href="<?php echo __HOSTNAME__; ?>/template/#" class="rating-link" onclick="document.getElementById('box').classList.toggle('grow');"><i class="material-icons ml-2">star</i></a>
							</div>
						</td>

						<td>42</td>
						<td><span class="badge badge-primary">MANAGER</span></td>
						<td><small class="text-muted">1 week ago</small></td>
						<td>&dollar;1,943</td>
						<td><a href="<?php echo __HOSTNAME__; ?>/template/" class="text-muted"><i class="material-icons">more_vert</i></a></td>
					</tr>

					<tr class="selected">

						<td>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-check-selected-row" checked="" id="customCheck1_2">
								<label class="custom-control-label" for="customCheck1_2"><span class="text-hide">Check</span></label>
							</div>
						</td>

						<td>

							<div class="media align-items-center">
								<div class="avatar avatar-xs mr-2">
									<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_luke-porter-261779-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
								</div>
								<div class="media-body">

									<span class="js-lists-values-employee-name">Michael Smith</span>

								</div>
							</div>

						</td>

						<td>
							<div class="media align-items-center">
								<a href="<?php echo __HOSTNAME__; ?>/template/">Black Ops</a>
								<a href="<?php echo __HOSTNAME__; ?>/template/#" class="rating-link"><i class="material-icons ml-2">star</i></a>
							</div>
						</td>

						<td>12</td>
						<td><span class="badge badge-warning">ADMIN</span></td>
						<td><small class="text-muted">3 days ago</small></td>
						<td>&dollar;12,402</td>
						<td><a href="<?php echo __HOSTNAME__; ?>/template/" class="text-muted"><i class="material-icons">more_vert</i></a></td>
					</tr>
					<tr>

						<td>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck2_2">
								<label class="custom-control-label" for="customCheck2_2"><span class="text-hide">Check</span></label>
							</div>
						</td>

						<td>

							<div class="media align-items-center">
								<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/avatar/green.svg" class="mr-2" alt="avatar" />
								<div class="media-body">

									<span class="js-lists-values-employee-name">Connie Smith</span>

								</div>
							</div>

						</td>

						<td>
							<div class="media align-items-center">
								<a href="<?php echo __HOSTNAME__; ?>/template/#">Backend Ltd</a>
								<a href="<?php echo __HOSTNAME__; ?>/template/#" class="rating-link active"><i class="material-icons ml-2">star</i></a>
							</div>
						</td>

						<td>42</td>
						<td><span class="badge badge-success">USER</span></td>
						<td><small class="text-muted">1 week ago</small></td>
						<td>&dollar;1,943</td>
						<td><a href="<?php echo __HOSTNAME__; ?>/template/" class="text-muted"><i class="material-icons">more_vert</i></a></td>
					</tr>
					<tr>

						<td>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck3_2">
								<label class="custom-control-label" for="customCheck3_2"><span class="text-hide">Check</span></label>
							</div>
						</td>

						<td>

							<div class="media align-items-center">

								<div class="avatar avatar-xs mr-2">
									<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/256_daniel-gaffey-1060698-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
								</div>
								<div class="media-body">

									<span class="js-lists-values-employee-name">John Connor</span>

								</div>
							</div>

						</td>

						<td>
							<div class="media align-items-center">
								<a href="<?php echo __HOSTNAME__; ?>/template/">Frontted</a>
								<a href="<?php echo __HOSTNAME__; ?>/template/#" class="rating-link" onclick="document.getElementById('box').classList.toggle('grow');"><i class="material-icons ml-2">star</i></a>
							</div>
						</td>

						<td>42</td>
						<td><span class="badge badge-primary">MANAGER</span></td>
						<td><small class="text-muted">1 week ago</small></td>
						<td>&dollar;1,943</td>
						<td><a href="<?php echo __HOSTNAME__; ?>/template/" class="text-muted"><i class="material-icons">more_vert</i></a></td>
					</tr>

				</tbody>
			</table>
		</div>

		<div class="card-body text-right">
			15 <span class="text-muted">of 1,430</span> <a href="<?php echo __HOSTNAME__; ?>/template/#" class="text-muted-light"><i class="material-icons ml-1">arrow_forward</i></a>
		</div>


	</div-->
</div>