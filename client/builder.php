<!DOCTYPE html>
<html lang="en" dir="ltr">


<?php
	$lastExist;
?>
<body class="layout-default">
	<?php require 'head.php'; ?>
	<div class="preloader"></div>
	<div class="mdk-header-layout js-mdk-header-layout">
		<?php require 'header.php'; ?>
		<div class="mdk-header-layout__content">

			<div class="mdk-drawer-layout js-mdk-drawer-layout">
				<div class="mdk-drawer-layout__content page">
					<?php
						if(empty(__PAGES__[0])) {
							require 'pages/system/dashboard.php';
						} else {
							if(is_dir('pages/' . implode('/', __PAGES__))) {
								require 'pages/' . implode('/', __PAGES__) . '/index.php';
							} else {
								if(file_exists('pages/' . implode('/', __PAGES__) . '.php')) {
									require 'pages/' . implode('/', __PAGES__) . '.php';
								} else {
									$isFile = 'pages';
									foreach (__PAGES__ as $key => $value) {
										if(file_exists($isFile . '/' . $value . '.php')) {
											$lastExist = $isFile . '/' . $value . '.php';
										}

										$isFile .= '/' .$value;
									}
									if(isset($lastExist)) {
										require $lastExist;
									} else {
										require 'pages/system/404.php';	
									}
								}
							}
						}
					?>
				</div>
				<?php require 'sidemenu.php'; ?>
			</div>
		</div>
	</div>
	<!-- <div id="app-settings">
		<app-settings layout-active="default" :layout-location="{
	  'default': 'index.html',
	  'fixed': 'fixed-dashboard.html',
	  'fluid': 'fluid-dashboard.html',
	  'mini': 'mini-dashboard.html'
	}"></app-settings>
	</div> -->
	<?php require 'script.php'; ?>

	<?php
		if(empty(__PAGES__[0])) {
			require 'script/system/dashboard.php';
		} else {
			if(is_dir('script/' . implode('/', __PAGES__))) {
				include 'script/' . implode('/', __PAGES__) . '/index.php';
			} else {
				if(file_exists('script/' . implode('/', __PAGES__) . '.php')) {
					include 'script/' . implode('/', __PAGES__) . '.php';
				} else {
					if(isset($lastExist)) {
						$getScript = explode('/', $lastExist);
						$getScript[0] = 'script';
						include implode('/', $getScript);
					} else {
						include 'script/system/404.php';	
					}
				}
			}
		}
	?>

</body>

</html>