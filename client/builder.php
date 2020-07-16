<!DOCTYPE html>
<html lang="en" dir="ltr">


<?php
	$lastExist;
?>
<body class="layout-default">
	<?php require 'head.php'; ?>
	<?php
		if(__PAGES__[0] == 'anjungan') {
			require 'pages/anjungan/index.php';
		} else if(__PAGES__[0] == 'display') {
			require 'pages/display/index.php';
		}
	?>
	<div class="mdk-header-layout js-mdk-header-layout">
		<?php require 'header.php'; ?>
		<div class="mdk-header-layout__content">

			<div class="mdk-drawer-layout js-mdk-drawer-layout">
				<div class="mdk-drawer-layout__content page" id="app-settings">
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
		<div class="preloader">
			<div class="sidemenu-shimmer">
				<?php
					for($sh = 1; $sh <= 10; $sh++) {
				?>
				<div class="shine"></div>
				<?php
					}
				?>
			</div>
			<div class="content-shimmer">
				<span>
					<img width="80" height="80" src="<?php echo __HOSTNAME__; ?>/template/assets/images/preloader4.gif" />
					<br />
					Loading...
				</span>
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
		/*function formatMoney(number, decPlaces, decSep, thouSep) {
			decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
			decSep = typeof decSep === "undefined" ? "." : decSep;
			thouSep = typeof thouSep === "undefined" ? "," : thouSep;
			var sign = number < 0 ? "-" : "";
			var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
			var j = (j = i.length) > 3 ? j % 3 : 0;

			return sign +
			(j ? i.substr(0, j) + thouSep : "") +
			i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
			(decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
		}*/
		function number_format (number, decimals, dec_point, thousands_sep) {
			// Strip all characters but numerical ones.
			number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
			var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function (n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
			// Fix for IE parseFloat(0.55).toFixed(0) = 0;
			s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
			if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			}
			if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
			}
			return s.join(dec);
		}
	</script>
</body>

</html>