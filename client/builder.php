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
	<div class="notification-container"></div>
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
	<script type="text/javascript">
		$(function() {
			$(".tooltip-custom").each(function() {
				var data = $(this).attr("data-toggle");
				$(this).tooltip({
					placement: "top",
					title: data
				});
			});
		});
		
		function inArray(needle, haystack) {
			var length = haystack.length;
			for(var i = 0; i < length; i++) {
				if(haystack[i] == needle) return true;
			}
			return false;
		}

		function notification (mode, title, time, identifier) {
			var alertContainer = document.createElement("DIV");
			var alertTitle = document.createElement("STRONG");
			var alertDismiss = document.createElement("BUTTON");
			var alertCloseButton = document.createElement("SPAN");

			$(alertContainer).addClass("alert alert-dismissible fade show alert-" + mode).attr({
				"role": "alert",
				"id": identifier
			});

			$(alertTitle).html(title);

			$(alertDismiss).attr({
				"type": "button",
				"data-dismiss": "alert",
				"aria-label": "Close"
			}).addClass("close");

			$(alertCloseButton).attr({
				"aria-hidden": true
			}).html("&times;");

			$(alertContainer).append(alertTitle);
			$(alertDismiss).append(alertCloseButton);
			$(alertContainer).append(alertDismiss);

			$(".notification-container").append(alertContainer);

			setTimeout(function() {
				$(alertContainer).fadeOut();
			}, time);
		}
	</script>
</body>

</html>