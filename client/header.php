<div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
	<div class="mdk-header__content">

		<div class="navbar navbar-expand-sm navbar-main navbar-dark bg-custom  pr-0" id="navbar" data-primary>
			<div class="container-fluid p-0">

				<!-- Navbar toggler -->

				<button class="navbar-toggler navbar-toggler-right d-block d-md-none" type="button" data-toggle="sidebar">
					<span class="navbar-toggler-icon"></span>
				</button>


				<!-- Navbar Brand -->
				<a href="<?php echo __HOSTNAME__; ?>" class="navbar-brand" style="padding: 10px 60px;">
					<img class="navbar-brand-icon" style="position: absolute;" src="<?php echo __HOSTNAME__; ?>/template/assets/images/logo-text-white.png" width="140" height="60" alt="SIMRS PETALA BUMI">
				</a>
				
				<ul class="nav navbar-nav d-none d-sm-flex navbar-height align-items-center">
					<li class="nav-item dropdown">
						<a href="#notifications_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
							<i class="material-icons nav-icon" id="counter-notif-identifier">notifications</i>
						</a>
						<div id="notifications_menu" class="dropdown-menu dropdown-menu-right navbar-notifications-menu">
							<div class="dropdown-item d-flex align-items-center py-2">
								<span class="flex navbar-notifications-menu__title m-0">Notifications</span>
								<a id="clear_notif" href="" class="text-muted"><small>Clear all</small></a>
							</div>
							<div class="navbar-notifications-menu__content" data-simplebar>
								<div class="py-2" id="notification-container"></div>
							</div>
							<a href="javascript:void(0);" class="dropdown-item text-center navbar-notifications-menu__footer">View All</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a href="<?php echo __HOSTNAME__; ?>/template/#account_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
							<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/avatar/demi.png" class="rounded-circle" width="32" alt="Frontted" />
							<span class="ml-1 d-flex-inline">
								<span class="text-light"><?php echo $_SESSION['nama']; ?></span>
							</span>
						</a>
						<div id="account_menu" class="dropdown-menu dropdown-menu-right" style="overflow: auto;">
							<div class="dropdown-item-text dropdown-item-text--lh">
								<div><strong><?php echo $_SESSION['nama']; ?></strong></div>
								<div><?php echo $_SESSION['email']; ?></div>
							</div>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item active" href="<?php echo __HOSTNAME__; ?>/template/index.html">Dashboard</a>
							<a class="dropdown-item" href="<?php echo __HOSTNAME__; ?>/template/profile.html">My profile</a>
							<a class="dropdown-item" href="<?php echo __HOSTNAME__; ?>/template/edit-account.html">Edit account</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo __HOSTNAME__; ?>/system/logout">Logout</a>
						</div>
					</li>
				</ul>

			</div>
		</div>

	</div>
</div>