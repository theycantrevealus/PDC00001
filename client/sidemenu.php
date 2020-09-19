<?php
	function reloadModul($pdo, $parent, $group, $access = array()) {
		$query = $pdo->prepare('SELECT * FROM modul WHERE deleted_at IS NULL AND parent = ? AND show_on_menu = ? AND menu_group = ? ORDER BY show_order ASC');
		$query->execute(array($parent, 'Y', $group));
		$read = $query->fetchAll(\PDO::FETCH_ASSOC);
		$availMenu = 0;
		foreach ($read as $key => $value) {
			//CHECK Child
			$child = $pdo->prepare('SELECT * FROM modul WHERE deleted_at IS NULL AND parent = ? AND show_on_menu = ? AND menu_group = ?');
			$child->execute(array($value['id'], 'Y', $group));
			$LinkManager = ($child->rowCount() > 0) ? "#menu-" . $value['id'] : __HOSTNAME__ . '/' .$value['identifier'];
			$activeCheck = false;
			if(__HOSTNAME__ . '/' . implode('/', __PAGES__) == $LinkManager) {
				$activeCheck = true;
			}

			if(in_array($value['id'], $access)) {
				?>
				<li class="sidebar-menu-item <?php echo ($activeCheck == true) ? "active" : ""; ?>" parent-child="<?php echo $parent; ?>">
					<a class="sidebar-menu-button" <?php echo ($child->rowCount() > 0) ? "data-toggle=\"collapse\"" : ""; ?> href="<?php echo $LinkManager; ?>">
						<?php
							if($parent == 0) {
						?>
						<i class="sidebar-menu-icon sidebar-menu-icon--left material-icons"><?php echo $value['icon'] ?></i>
						<?php
							}
						?>
						<span class="sidebar-menu-text"><?php echo $value['nama']; ?></span>
						<?php
							if($child->rowCount() > 0) {
						?>
						<span class="ml-auto sidebar-menu-toggle-icon"></span>
						<?php
							}
						?>
					</a>
					<?php
						if($child->rowCount() > 0) {
					?>
					<ul class="sidebar-submenu collapse" id="menu-<?php echo $value['id']; ?>" master-child="<?php echo $parent; ?>">
						<?php
							$availMenu += reloadModul($pdo, $value['id'], $group, $access);
						?>
					</ul>
					<?php
						}
					?>
				</li>
				<?php
				$availMenu++;
			}
		}
		return $availMenu;
	}
?>
<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
	<div class="mdk-drawer__content">
		<div class="sidebar sidebar-light sidebar-left simplebar" data-simplebar>
			<!-- <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account">
				<a href="<?php echo __HOSTNAME__; ?>/template/profile.html" class="flex d-flex align-items-center text-underline-0 text-body">
					<span class="avatar mr-3">
						<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/avatar/demi.png" alt="avatar" class="avatar-img rounded-circle">
					</span>
					<span class="flex d-flex flex-column">
						<strong><?php echo $_SESSION['nama']; ?></strong>
						<small class="text-muted text-uppercase">Account Manager</small>
					</span>
				</a>
				<div class="dropdown ml-auto">
					<a href="<?php echo __HOSTNAME__; ?>/template/#" data-toggle="dropdown" data-caret="false" class="text-muted"><i class="material-icons">more_vert</i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<div class="dropdown-item-text dropdown-item-text--lh">
							<div><strong>Adrian Demian</strong></div>
							<div>@adriandemian</div>
						</div>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item active" href="<?php echo __HOSTNAME__; ?>/template/index.html">Dashboard</a>
						<a class="dropdown-item" href="<?php echo __HOSTNAME__; ?>/template/profile.html">My profile</a>
						<a class="dropdown-item" href="<?php echo __HOSTNAME__; ?>/template/edit-account.html">Edit account</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?php echo __HOSTNAME__; ?>/template/login.html">Logout</a>
					</div>
				</div>
			</div> -->
			<div class="sidebar-heading sidebar-m-t" id="sidemenu_1">Menu</div>
			<ul class="sidebar-menu">
				<?php
					$sideMenu1 = reloadModul($pdo, 0, 1, $_SESSION['akses_halaman']);
				?>
			</ul>
			<div class="sidebar-heading sidebar-m-t" id="sidemenu_2">Master Data</div>
			<div class="sidebar-block p-0">
				<ul class="sidebar-menu">
					<?php
						$sideMenu2 = reloadModul($pdo, 0, 2, $_SESSION['akses_halaman']);
					?>	
				</ul>
			</div>
			<div class="sidebar-heading sidebar-m-t" id="sidemenu_3">Setting</div>
			<div class="sidebar-block p-0">
				<ul class="sidebar-menu">
					<?php
						$sideMenu3 = reloadModul($pdo, 0, 3, $_SESSION['akses_halaman']);
					?>	
				</ul>

				<!-- <div class="sidebar-p-a sidebar-b-y">
					<div class="d-flex align-items-top mb-2">
						<div class="sidebar-heading m-0 p-0 flex text-body js-text-body">Progress</div>
						<div class="font-weight-bold text-success">60%</div>
					</div>
					<div class="progress">
						<div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>