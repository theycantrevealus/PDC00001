<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo __PC_CUSTOMER__; ?>::Login</title>

		<!-- Prevent the demo from appearing in search engines -->
		<meta name="robots" content="noindex">
        <link rel="icon" href="<?php echo __HOSTNAME__; ?>/template/assets/images/clients/logo-icon-<?php echo __PC_IDENT__; ?>.ico" type="image/ico" sizes="16x16" />
		<!-- Simplebar -->
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/vendor/simplebar.min.css" rel="stylesheet">

		<!-- App CSS -->
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/app.css" rel="stylesheet">
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/app.rtl.css" rel="stylesheet">

		<!-- Material Design Icons -->
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/vendor-material-icons.css" rel="stylesheet">
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/vendor-material-icons.rtl.css" rel="stylesheet">

		<!-- Font Awesome FREE Icons -->
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/vendor-fontawesome-free.css" rel="stylesheet">
		<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/vendor-fontawesome-free.rtl.css" rel="stylesheet">
		<style type="text/css">
			.layout-login__form {
				position: relative;
			}

			.copyright-panel {
				position: absolute;
				bottom: 80px;
				left: 10%;
				height: auto;
				width: 80%;
			}
			.copyright-panel small {
				opacity: 0;
				font-style: italic;
				font-size: 8pt !important;
			}
			.copyright-panel img {
				position: absolute;
				opacity: 0;
				left: 50px;
			}
		</style>


		<!-- Global site tag (gtag.js) - Google Analytics -->
		<!-- <script async src="<?php echo __HOSTNAME__; ?>/template/https://www.googletagmanager.com/gtag/js?id=UA-133433427-1"></script> -->
		<script>
			/*window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-133433427-1');*/
		</script>


	<!-- Facebook Pixel Code -->
	<script>
		/*!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '327167911228268');
		fbq('track', 'PageView');*/
	</script>
	<!-- <noscript><img height="1" width="1" style="display:none" src="<?php echo __HOSTNAME__; ?>/template/https://www.facebook.com/tr?id=327167911228268&ev=PageView&noscript=1" /></noscript> -->
	<!-- End Facebook Pixel Code -->






	</head>

	<body class="layout-login" style="background-image: url(<?php echo __HOST__; ?>/client/template/assets/images/wallpaper.jpg); background-size: cover; background-position: center; background-attachment: fixed">
		<div class="layout-login__overlay"></div>
		<div class="layout-login__form" data-simplebar>
			<div class="d-flex justify-content-center mt-2 mb-5 navbar-light">
				<a href="<?php echo __HOSTNAME__; ?>/" class="navbar-brand" style="min-width: 0">
					<img class="navbar-brand-icon" src="<?php echo __HOSTNAME__; ?>/template/assets/images/clients/logo-icon-<?php echo __PC_IDENT__; ?>.png" width="100" height="100" alt="<?php echo __PC_CUSTOMER__; ?>">
                    <!--<img class="navbar-brand-icon" src="<?php /*echo __HOSTNAME__; */?>/template/assets/images/logo-text-black.png" width="180" height="180" alt="<?php echo __PC_CUSTOMER__; ?>">-->
				</a>
			</div>
            <h4 class="m-0"><?php echo __PC_CUSTOMER__; ?></h4>
			<p class="mb-5 text-muted">Hospital Information Management System</p>



            <form style="padding-top: 100px;">
				<div class="form-group">
					<label class="text-label" for="email_2">Email Address:</label>
					<div class="input-group input-group-merge">
						<input id="email_2" type="text" required="" class="form-control form-control-prepended" placeholder="account@<?php echo __SYSTEM_DOMAIN__; ?>">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<span class="far fa-envelope"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="text-label" for="password_2">Password:</label>
					<div class="input-group input-group-merge">
						<input id="password_2" type="password" required="" class="form-control form-control-prepended" placeholder="Enter your password">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<span class="fa fa-key"></span>
							</div>
						</div>
					</div>
				</div>
				<label id="error_message" class="text-danger"></label>
				<!-- <div class="form-group mb-5">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" checked="" id="remember">
						<label class="custom-control-label" for="remember">Remember me</label>
					</div>
				</div> -->
				<div class="form-group text-center">
					<button class="btn btn-primary mb-5" id="btnLogin" type="submit">
                        <span>
                            <i class="fa fa-check"></i> Login
                        </span>
                    </button><br>
					<!-- <a href="<?php echo __HOSTNAME__; ?>/template/">Forgot password?</a> <br>
					Don't have an account? <a class="text-body text-underline" href="<?php echo __HOSTNAME__; ?>/template/signup.html">Sign up!</a> -->
				</div>
			</form>
			<div class="copyright-panel">
                <small class="text-secondary">Powered By</small><br />
                <div class="row">
                    <!--<div class="col-6">
                        <img src="<?php /*echo __HOSTNAME__; */?>/template/assets/images/icon.jpg" width="60" />
                    </div>-->
                    <div class="col-6" style="padding-top: 10px;">
					<img class="navbar-brand-icon" src="<?php echo __HOSTNAME__; ?>/template/assets/images/clients/logo-icon-<?php echo __PC_IDENT__; ?>.png" width="80" height="80" alt="<?php echo __PC_CUSTOMER__; ?>">
                    </div>
                </div>
			</div>
		</div>
        <style type="text/css">
            form {
                margin-top: -100px;
            }
            .layout-login__overlay {
                background: rgba(250, 250, 250, .5) !important;
            }
            .layout-login__form {
                position: relative;
                box-shadow: 0 0 10px 10px rgba(0, 0, 0, .2);
            }
            .layout-login__form:after {
                position: absolute !important;
                top: 0; left: 0;
                content: "";
                width: 100%; height: 100%;
                background: #fff !important;
                z-index: -1;
                opacity: .9;
                background: #fff;
                background-size: cover;
                background-position: center;
            }
        </style>


		<!-- jQuery -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/jquery.min.js"></script>

		<!-- Bootstrap -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/popper.min.js"></script>
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/bootstrap.min.js"></script>

		<!-- Simplebar -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/simplebar.min.js"></script>

		<!-- DOM Factory -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/dom-factory.js"></script>

		<!-- MDK -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/material-design-kit.js"></script>

		<!-- App -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/toggle-check-all.js"></script>
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/check-selected-row.js"></script>
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/dropdown.js"></script>
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/sidebar-mini.js"></script>
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/app.js"></script>

		<!-- App Settings (safe to remove) -->
		<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/app-settings.js"></script>


		<script type="text/javascript">
			$(function(){
				$(".copyright-panel img, small").animate({
					"opacity": 1,
					"left": "0"
				}, 1500);

				$("form").submit(function() {
				    $("#btnLogin").removeClass("btn-info").addClass("btn-warning").html("<span><i class=\"fa fa-hourglass-half\"></i> Validating</span>").attr({
                        "disabled": "disabled"
                    });
					var email = $("#email_2").val();
					var password = $("#password_2").val();
					if(email != "") {
						$.ajax({
							url: __HOSTAPI__ + "/Pegawai",
							type: "POST",
							data:{
								request:"login",
								email:email,
								password:password
							},
							success:function(response) {
							    console.log(response);
                                $("#btnLogin").removeClass("btn-warning").addClass("btn-info").html("<span><i class=\"fa fa-check\"></i> Login</span>").removeAttr("disabled");
								if(response.response_result > 0) {
									location.reload();
								} else {
									$("#error_message").html(response.response_message);
								}
							},
							error: function(response) {
								console.log(response);
								$("#error_message").html(response);
							}
						});
					} else {
						$("#error_message").html("Email tidak boleh kosong")
					}
					return false;
				});
			});
		</script>

	</body>

</html>