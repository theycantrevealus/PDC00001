<!DOCTYPE html>
<html lang="en" dir="ltr">


<?php
	$lastExist = '';
?>
<?php require 'head.php'; ?>
<body class="layout-default">

	<?php
		if(__PAGES__[0] == 'anjungan') {
			require 'pages/anjungan/index.php';
		} else if(__PAGES__[0] == 'display') {
            require 'pages/display/index.php';
        } else if(__PAGES__[0] == 'display_dokter') {
            require 'pages/display_dokter/index.php';
        } else if(__PAGES__[0] == 'display_jadwal_operasi') {
			require 'pages/display_jadwal_operasi/index.php';
		}
	?>
	<div class="mdk-header-layout js-mdk-header-layout">
		<?php
            if(file_exists('header.php')) {
                require 'header.php';
            } else {
                echo 'no header found';
            }
            
        ?>
		<div class="mdk-header-layout__content">

			<div class="mdk-drawer-layout js-mdk-drawer-layout">
				<div class="mdk-drawer-layout__content page" id="app-settings">
					<?php
						if(empty(__PAGES__[0])) {
							require 'pages/system/dashboard.php';
						} else {
							if(implode('/', __PAGES__) == 'system/logout') {
								require 'pages/system/logout.php';
							} else {
								/*echo '<pre>';
								print_r($_SESSION['akses_halaman_link']);
								echo '</pre>';*/
								if(is_dir('pages/' . implode('/', __PAGES__))) {
									$isInAccess = '';
									$allowAccess = false;
									foreach (__PAGES__ as $key => $value) {
										if($key == 0) {
											$isInAccess .= $value;
										} else {
											$isInAccess .= '/' . $value;
										}

										if (in_array($isInAccess, $_SESSION['akses_halaman_link'])) {
											$allowAccess = true;
											break;
										} else {
											if($allowAccess) {
												$allowAccess = false;
											}
										}
									}

									if($allowAccess) {
										require 'pages/' . implode('/', __PAGES__) . '/index.php';
									} else {
                                        if(!$allowAccess) {
											require 'pages/system/403.php';
										} else {
											require 'pages/system/404.php';
										}
									}
								} else {
									if(file_exists('pages/' . implode('/', __PAGES__) . '.php')) {
										require 'pages/' . implode('/', __PAGES__) . '.php';
									} else {
										$isFile = 'pages';
										$isInAccess = '';
										$allowAccess = false;

										foreach (__PAGES__ as $key => $value) {
											if(file_exists($isFile . '/' . $value . '.php')) {
												$lastExist = $isFile . '/' . $value . '.php';
											}
											$isFile .= '/' . $value;
										}

										foreach (__PAGES__ as $key => $value) {
											if($key == 0) {
												$isInAccess .= $value;
											} else {
												$isInAccess .= '/' . $value;
											}

											//echo $isInAccess . '<br />';

											if (in_array($isInAccess, $_SESSION['akses_halaman_link'])) {
												$allowAccess = true;
												break;
											} else {
												if($allowAccess) {
													$allowAccess = false;
												}
											}
										}

										if(isset($lastExist) && $allowAccess) {
											require $lastExist;
										} else {
                                            if(!isset($lastExist)) {
                                                require 'pages/system/404.php';
                                            } else {
                                                if(!$allowAccess) {
                                                    require 'pages/system/403.php';
                                                }
                                            }
										}
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
					/*for($sh = 1; $sh <= 10; $sh++) {
                    ?>
                    <div class="shine"></div>
                    <?php
					}*/
				?>
			</div>
			<div class="content-shimmer">
				<center>
					<img width="240" height="220" src="<?php echo __HOSTNAME__; ?>/template/assets/images/preloader.gif" />
					<br />
					Loading...
				</center>
			</div>
		</div>
	</div>
	<div class="global-sync-container blinker_dc">
		<h4 class="text-center" style="font-family: Courier"><i class="fa fa-signal"></i><br /><br /><small>reconnecting</small></h4>
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
	<script type="text/javascript">
        var currentCPPTStep = 1;
        var targetModule = 0;
        var tutorList = {};
        function isHTML(str) {
            var a = document.createElement('div');
            a.innerHTML = str;

            for (var c = a.childNodes, i = c.length; i--; ) {
                if (c[i].nodeType == 1) return true;
            }

            return false;
        }

        Math.fmod = function (a,b) { return Number((a - (Math.floor(a / b) * b)).toPrecision(8)); };

        function penyebut(nilai) {
            nilai = Math.abs(nilai);
            var huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
            var temp = "";
            if (nilai < 12) {
                temp = " " + ((huruf[Math.floor(nilai)] !== undefined) ? huruf[Math.floor(nilai)] : "");
            } else if (nilai < 20) {
                temp = penyebut(nilai - 10) + " belas";
            } else if (nilai < 100) {
                temp = penyebut(nilai/10) + " puluh" + penyebut(nilai % 10);
            } else if (nilai < 200) {
                temp = " seratus" + penyebut(nilai - 100);
            } else if (nilai < 1000) {
                temp = penyebut(nilai/100) + " ratus" + penyebut(nilai % 100);
            } else if (nilai < 2000) {
                temp = " seribu" + penyebut(nilai - 1000);
            } else if (nilai < 1000000) {
                temp = penyebut(nilai/1000) + " ribu" + penyebut(nilai % 1000);
            } else if (nilai < 1000000000) {
                temp = penyebut(nilai/1000000) + " juta" + penyebut(nilai % 1000000);
            } else if (nilai < 1000000000000) {
                temp = penyebut(nilai/1000000000) + " milyar" + penyebut(Math.fmod(nilai,1000000000));
            } else if (nilai < 1000000000000000) {
                temp = penyebut(nilai/1000000000000) + " trilyun" + penyebut(Math.fmod(nilai,1000000000000));
            }
            return temp;
        }

        function stristr (haystack, needle, bool) {
            var pos = 0;

            haystack += '';
            pos = haystack.toLowerCase().indexOf((needle + '').toLowerCase());
            if (pos == -1) {
                return haystack;
            } else {
                if (bool) {
                    return haystack.substr(0, pos);
                } else {
                    return haystack.slice(pos);
                }
            }
        }

        function terbilang(nilai) {
            var x = stristr(nilai, '.') + "";

            if(nilai < 0) {
                hasil = "minus " + penyebut(nilai).trim();
            } else {
                hasil = penyebut(nilai).trim();
            }

            sen = x.split(".");


            if(sen.length > 0) {
                var str = "" + sen[sen.length - 1];
                var pad = "00"
                sen = str + pad.substring(0, pad.length - str.length);
                //return hasil + " " + penyebut(parseFloat(sen)).trim() + " sen";
                return hasil
            } else {
                return hasil;
            }
        }

        $(function() {
        var targetModule = 0;
        var tutorList = {};
		    var currentPageURL = document.URL;
		    //Check Child
            var checkerChild = currentPageURL.split("/");
            var childLibList = ["tambah", "edit", "view", "detail", "antrian"];
            var targettedChildWow = 0;
            var isChildMenuWow = false;
            for(var abczz in checkerChild) {
                if(childLibList.indexOf(checkerChild[abczz]) >= 0) {
                    targettedChildWow = abczz;
                    isChildMenuWow = true;
                    break;
                }
            }

            if(isChildMenuWow) {
                checkerChild.splice(targettedChildWow, (checkerChild.length - targettedChildWow));
                currentPageURL = checkerChild.join("/");
            }

            var currentMenuCheck = $("a.sidebar-menu-button[href=\"" + currentPageURL + "\"]").parent();
            while(parseInt(currentMenuCheck.attr("parent-child")) > 0) {
                var parentID = currentMenuCheck.attr("parent-child");
                $("#menu-" + parentID).addClass("show");
                $("a[href=\"#menu-" + parentID + "\"]").removeClass("collapsed");
                $("a[href=\"#menu-" + parentID + "\"]").parent().addClass("open");

                currentMenuCheck = $("a[href=\"#menu-" + parentID + "\"]").parent();

            }

			$(".txt_tanggal").datepicker({
				dateFormat: 'DD, dd MM yy',
				autoclose: true
			});

			moment.locale('id');
			var parentList = [];

			$(".sidebar-menu-item.active").each(function(){
				var activeMenu = $(this).attr("parent-child");
				$("a[href=\"#menu-" + activeMenu + "\"]").removeClass("collapsed").parent().addClass("open");
				$("ul#menu-" + activeMenu).addClass("show");
                targetModule = $(this).attr("target_modul");
			});

			$("ul.sidebar-submenu").each(function() {
				var hasMaster = $(this).attr("master-child");
				if (typeof hasMaster !== typeof undefined && hasMaster !== false && hasMaster > 0) {

					//$("a[href=\"#menu-" + hasMaster + "\"]").removeClass("collapsed").parent().addClass("open");
					$("ul#menu-" + hasMaster).addClass("show");

				}
			});

			//Load Module Tutorial
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Tutorial/get_tutorial/" + targetModule,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var data = response.response_package.response_data;
                    $("#tutor-loader").html("");
                    for(var a in data) {
                        $("#tutor-loader").append("<div item=\"" + data[a].uid + "\" class=\"dropdown-item tutor_run\" style=\"position:relative; padding: 0px 5px; cursor: pointer; cursor: hand;\">" +
                            "<i style=\"position:absolute; left: 5px; top: 2.5px\" class=\"material-icons nav-icon\">help_outline</i>" +
                            "<span style=\"padding-left: 25px\">" + data[a].nama + "</span>" +
                        "</div>");

                        if(tutorList[data[a].uid] === undefined) {
                            tutorList[data[a].uid] = {
                                name: data[a].nama,
                                step: []
                            }
                        }

                        var step = data[a].step;

                        for(var b in step) {
                            var currentTutor = {};
                            if(step[b].type === "B") {
                                currentTutor = {
                                    intro: step[b].remark,
                                    expectDOM: step[b].trigger_dom,
                                    expectDOMType: step[b].trigger_dom_type,
                                }
                            } else {
                                currentTutor = {
                                    element: document.querySelector(step[b].element_target),
                                    intro: step[b].remark,
                                    position: step[b].tooltip_pos,
                                    expectDOM: step[b].trigger_dom,
                                    expectDOMType: step[b].trigger_dom_type,
                                }
                            }
                            tutorList[data[a].uid].step.push(currentTutor);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            var tutorStart;
            $("body").on("click", ".tutor_run", function () {
                var tutorGroup = $(this).attr("item");
                tutorStart = introJs().setOptions({
                    steps:tutorList[tutorGroup].step,
                    showStepNumbers: true,
                    scrollToElement:true,
                    tooltipClass: "SOLOMON",
                    showProgress: false,
                    showBullets: true
                }).oncomplete(() => {
                    $(".modal.show").modal("hide");
                }).start();

                var needDOM = [];
                var needDOMProc = {};

                tutorStart.onchange(function(targetElement) {
                    if(needDOM.indexOf($(targetElement).attr("id")) > -1) {
                        if(needDOMProc[$(targetElement).attr("id")].type === "modal") {
                            $(needDOMProc[$(targetElement).attr("id")].dom).modal("show");
                        } else if (needDOMProc[$(targetElement).attr("id")].type === "tab") {
                            $(needDOMProc[$(targetElement).attr("id")].dom).tab("show");
                        }
                    }
                });

                tutorStart._options.steps.forEach(function(value, key) {
                    if(value.expectDOMType !== "" && value.expectDOMType !== undefined && value.expectDOMType !== null) {
                        if(needDOM.indexOf($(value.element).attr("id")) < 0) {
                            needDOM.push($(value.element).attr("id"));
                        }

                        if(needDOMProc[$(value.element).attr("id")] === undefined) {
                            needDOMProc[$(value.element).attr("id")] = {
                                dom: value.expectDOM,
                                type: value.expectDOMType
                            }
                        } else {
                            needDOMProc[$(value.element).attr("id")] = {
                                dom: value.expectDOM,
                                type: value.expectDOMType
                            }
                        }
                    }
                });

                tutorStart.onbeforechange(function(targetElement) {


                });
            })

			//$("ul[master-child=\"" + activeMenu + "\"").addClass("open");


			var idleCheck;
			function reloadSession(idleCheck) {
                var excludedPages = ['display_dokter','display','anjungan'];

                if(excludedPages.indexOf(__PAGES__[0]) < 0) {
                    window.clearTimeout(idleCheck);
    				idleCheck = window.setTimeout(function() {
					
                        $.ajax({
                            url:__HOSTAPI__ + "/Pegawai",
                            type: "POST",
                            data: {
                                request: "logged_out"
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response) {
                                localStorage.removeItem("currentLoggedInState");
                                location.href = __HOSTNAME__;
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    
				    },180 * 60 * 1000);

                    return idleCheck;
                }
			}

			$("body").on("click", function() {
				idleCheck = reloadSession(idleCheck);
			});

			$("body").on("keyup", function() {
				idleCheck = reloadSession(idleCheck);
			});

			$("body").on("mousemove", function() {
				idleCheck = reloadSession(idleCheck);
			});

			refresh_notification();

			$("body").on("click", "#clear_notif", function() {
				$.ajax({
					async: false,
					url:__HOSTAPI__ + "/Notification",
					type: "POST",
					data: {
						request: "clear_notif"
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					success: function(response) {
						refresh_notification();
					},
					error: function(response) {
						console.log(response);
					}
				});
				return false;
			});

			$("body").on("click", "a[href=\"#notifications_menu\"]", function() {
				$.ajax({
					async: false,
					url:__HOSTAPI__ + "/Notification",
					type: "POST",
					data: {
						request: "read_notif"
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					success: function(response) {
						refresh_notification();
					},
					error: function(response) {
						console.log(response);
					}
				});
			});

			$("body").on("click", "#refresh_protocol", function() {

                push_socket(__ME__, "refresh", "*", "Refresh page", "info").then(function() {
                    notification ("info", "Refresh page", 3000, "notif_update");
                });
            });
		});

        function getPageList(totalPages, page, maxLength) {
            if (maxLength < 5) throw "maxLength must be at least 5";

            function range(start, end) {
                return Array.from(Array(end - start + 1), (_, i) => i + start); 
            }

            var sideWidth = maxLength < 9 ? 1 : 2;
            var leftWidth = (maxLength - sideWidth*2 - 3) >> 1;
            var rightWidth = (maxLength - sideWidth*2 - 2) >> 1;
            
            if (totalPages <= maxLength) {
                // no breaks in list
                return range(1, totalPages);
            }
            if (page <= maxLength - sideWidth - 1 - rightWidth) {
                // no break on left of page
                return range(1, maxLength - sideWidth - 1)
                .concat(0, range(totalPages - sideWidth + 1, totalPages));
            }
            if (page >= totalPages - sideWidth - 1 - rightWidth) {
                // no break on right of page
                return range(1, sideWidth)
                .concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
            }
            // Breaks on both sides
            console.log(page);
            console.log(page - leftWidth);
            console.log(page + rightWidth);
            return range(1, sideWidth)
                .concat(0, range(page - leftWidth, page + rightWidth),
                0, range(totalPages - sideWidth + 1, totalPages));
        }

        function loadCPPT(from, to, pasien, currentStep, UID = "") {
            
            $("#cppt_loader").html("");
            $.ajax({
                url: __HOSTAPI__ + "/CPPT",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: {
                    request: "group_tanggal",
                    pasien: pasien,
                    from: from,
                    to: to,
                    offset: parseInt(currentStep),
                    current: UID
                },
                success:function(response) {
                    var data = response.response_package.data;
                    var total = parseInt(response.response_package.total);

                    $("#cppt_pagination ul li").remove();

                    $("#cppt_pagination ul").append("<li class=\"page-item\" id=\"cppt_prev_page\"><a class=\"page-link cppt_paginate_prev\">Previous</a></li>");
                    for(var azTot = 1; azTot < total; azTot++) {
                        //$("#cppt_pagination ul").append("<li class=\"page-item stepper" + ((currentStep === azTot) ? "active" : "") + "\"><a class=\"page-link cppt_paginate\" target=\"" + azTot + "\">" + (azTot) + "</a></li>");
                    }
                    $("#cppt_pagination ul").append("<li class=\"page-item\" id=\"cppt_next_page\"><a class=\"page-link cppt_paginate_next\">Next</a></li>");
                    
                    $("#cppt_pagination ul li.stepper").slice(1, -1).remove();
                    getPageList(total, parseInt(currentStep), 7).forEach( item => {
                        $("<li>").addClass("page-item stepper")
                                .addClass(item ? "current-page" : "disabled")
                                .toggleClass("active", item === parseInt(currentStep)).append(
                            $("<a>").addClass("page-link cppt_paginate").attr({
                                target: item}).text(item || "...")
                        ).insertBefore("#cppt_next_page");
                    });

                    
                    $("cppt_paginate_prev").toggleClass("disabled", currentStep === 1);
                    $(".cppt_paginate_next").toggleClass("disabled", currentStep === total);

                    if(data && Object.keys(data).length > 0 && data.constructor === Object) {
                        $("#no-data-panel").hide();
                        for(var a in data) {
                            $.ajax({
                                url: __HOSTNAME__ + "/pages/pasien/cppt-grouper.php",
                                async:false,
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type:"POST",
                                data: {
                                    group_tanggal_caption: data[a].parsed,
                                    group_tanggal_name: a
                                },
                                success:function(responseGrouper) {
                                    $("#cppt_loader").append(responseGrouper);
                                    var listData = data[a].data;
                                    for(var b in listData) {
                                        var currentData = listData[b].data[0];
                                        currentData.asesmen.resep = (currentData.asesmen.resep !== undefined && currentData.asesmen.resep !== null) ? [currentData.asesmen.resep[currentData.asesmen.resep.length - 1]] : [];
                                        currentData.asesmen.racikan = (currentData.asesmen.racikan !== undefined && currentData.asesmen.racikan !== null) ? [currentData.asesmen.racikan[currentData.asesmen.racikan.length - 1]] : [];
                                        $.ajax({
                                            url: __HOSTNAME__ + "/pages/pasien/cppt-single.php",
                                            async:false,
                                            beforeSend: function(request) {
                                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                            },
                                            type:"POST",
                                            data: {
                                                currentData: UID,
                                                __HOSTNAME__: __HOSTNAME__,
                                                __HOST__: __HOST__,
                                                __ME__: __ME__,
                                                asesmen: currentData.asesmen,
                                                kunjungan: currentData.kunjungan,
                                                antrian: currentData.uid,
                                                penjamin: currentData.penjamin,
                                                penjamin_detail: currentData.penjamin_detail,
                                                pasien: currentData.asesmen.pasien,
                                                group_tanggal_name: a,
                                                waktu_masuk: listData[b].parsed,
                                                waktu_masuk_name: listData[b].parsed.replaceAll(":", "_"),
                                                departemen: (currentData.departemen !== undefined && currentData.departemen !== null) ? currentData.departemen.nama : "Rawat Inap",
                                                dokter_uid: currentData.dokter.uid,
                                                dokter: currentData.dokter.nama,
                                                dokter_pic: __HOST__ + currentData.dokter.profile_pic,
                                                icd10_kerja: currentData.asesmen.icd10_kerja,
                                                icd10_banding: currentData.asesmen.icd10_banding,
                                                keluhan_utama:currentData.asesmen.keluhan_utama,
                                                keluhan_tambahan:currentData.asesmen.keluhan_tambahan,
                                                diagnosa_kerja:currentData.asesmen.diagnosa_kerja,
                                                diagnosa_banding:currentData.asesmen.diagnosa_banding,
                                                pemeriksaan_fisik:currentData.asesmen.pemeriksaan_fisik,
                                                planning:currentData.asesmen.planning,
                                                tindakan: currentData.asesmen.tindakan,
                                                resep: currentData.asesmen.resep,
                                                racikan: currentData.asesmen.racikan,
                                                laboratorium: currentData.asesmen.laboratorium,
                                                radiologi: currentData.asesmen.radiologi
                                            },
                                            success:function(responseSingle) {
                                                $("#group_cppt_" + a).append(responseSingle);
                                            },
                                            error: function(responseSingleError) {
                                                console.log(responseSingleError);
                                            }
                                        });
                                        //}
                                    }
                                },
                                error: function(responseGrouperError) {
                                    console.log(responseGrouperError);
                                }
                            });
                        }
                    } else {
                        $("#no-data-panel").show();
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function getDateRange(target) {
            var rangeItem = $(target).val().split(" to ");
            if(rangeItem.length > 1) {
                return rangeItem;
            } else {
                return [rangeItem, rangeItem];
            }
        }



		function refresh_notification() {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Notification",
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var newCounter = 0;
					$("#notification-container").html("");
					var notifData = response.response_package.response_data;
					for(var notifKey in notifData) {
						if(notifData[notifKey].status == "N") {
							newCounter++;
						}
						var notifContainer = document.createElement("DIV");
						var notifSenderContainer = document.createElement("DIV");
						var notifContentContainter = document.createElement("DIV");
						$(notifSenderContainer).html(	"<div class=\"avatar avatar-sm\" style=\"width: 32px; height: 32px;\">" +
															"<img src=\"" + __HOSTNAME__ + "/template/assets/images/avatar/queue.png\" alt=\"Avatar\" class=\"avatar-img rounded-circle\">" +
														"</div>").addClass("mr-3");
						if(notifData[notifKey].receiver_type == "group") {
							$(notifContentContainter).html(notifData[notifKey].notify_content).addClass("flex");
						} else {
							$(notifContentContainter).html("<a href=\"\">A.Demian</a> left a comment on <a href=\"\">Stack</a><br>" +
															"<small class=\"text-muted\">1 minute ago</small>").addClass("flex");
						}

						$(notifContainer).addClass("dropdown-item d-flex");
						$(notifContainer).append(notifSenderContainer);
						$(notifContainer).append(notifContentContainter);

						$("#notification-container").append(notifContainer);
					}
					if(newCounter > 0) {
						$("#counter-notif-identifier").addClass("navbar-notifications-indicator");
					} else {
						$("#counter-notif-identifier").removeClass("navbar-notifications-indicator");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}



        var serverTarget = "ws://" + __SYNC__ + ":" + __SYNC_PORT__;
        //var serverTarget = "ws://127.0.0.1:3000/socket.io/?EIO=3&transport=websocket";
		var Sync;
        var tm;
        var protocolLib = {
            akses_update: function(protocols, type, parameter, sender, receiver, time) {
                if(sender != receiver) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Pegawai",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            "request": "refresh_pegawai_access",
                            "uid": __ME__
                        },
                        success:function(resp) {
                            notification ("info", "Hak modul Anda sudah diupdate. Refresh halaman untuk akses baru", 3000, "hasil_modul_update");
                        }
                    });
                } else {
                    //
                }
            },
            reset_password: function(protocols, type, parameter, sender, receiver, time) {
                location.href = __HOSTNAME__ + "/system/logout";
            },
            refresh: function(protocols, type, parameter, sender, receiver, time) {
                location.reload();
            }
        };

        function checkStatusGudang(gudang, target_gudang) {
            var currentStatus = "";
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/gudang_detail/" + gudang,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var gudangInfo = response.response_package.response_data[0];
                    currentStatus = gudangInfo.status;
                    if(gudangInfo.status === "A") {
                        $(target_gudang).html("<b class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Gudang Aktif</b>");
                    } else {
                        $(target_gudang).html("<b class=\"text-warning\"><i class=\"fa fa-exclamation-circle\"></i> Gudang Opname</b>");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return currentStatus;
        }

	</script>
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

        
        function resend_socket(requestList, callback) {
            var sendingStatus = 0;
            for(var reqKey in requestList) {
                push_socket(
                    requestList[reqKey].sender,
                    requestList[reqKey].protocol,
                    requestList[reqKey].receiver,
                    requestList[reqKey].message,
                    requestList[reqKey].type
                ).then(function() {
                    //alert(reqKey);
                    sendingStatus++;
                });
            }

            /*if(sendingStatus === requestList.length) {

            } else {
                resend_socket(requestList, callback);
            }*/

            callback();
        }
        async function push_socket(sender, protocols, receiver, parameter, type) {

            if(Sync.readyState === WebSocket.CLOSED) {
                //Sync = SocketCheck(serverTarget, protocolLib, tm);
            }

            var msg = {
                protocols: protocols,
                sender: sender,
                receiver: receiver,
                parameter: parameter,
                type: type
            };

            return new Promise((resolve, reject) => {
                Sync.emit('message', msg);
                //Sync.send(JSON.stringify(msg));
                resolve(msg);
            });
        }

        $(function() {
            if ("WebSocket" in window) {

                //var Sync = new WebSocket(serverTarget);
                //console.log(protocolLib);
                //Sync = SocketCheck(serverTarget, protocolLib, tm);
                Sync = io.connect(serverTarget);
            
                Sync.on('message', data => {
                    console.log(data);
                    var audio;
                    var signalData = data;
                    var command = signalData.protocols;
                    var type = signalData.type;
                    var sender = signalData.sender;
                    var receiver = signalData.receiver;
                    var time = signalData.time;
                    var parameter = signalData.parameter;


                    if(command !== undefined && command !== null && command !== "") {

                        if(protocolLib[command] !== undefined) {
                            if(command === "anjungan_kunjungan_panggil") {
                                if (audio !== undefined && audio.audio !== undefined) {
                                    if (!audio.paused) {
                                        audio.audio.pause();
                                        audio.audio.currentTime = 0;
                                    } else {
                                        //alert();
                                    }
                                }
                                audio = protocolLib[command](command, type, parameter, sender, receiver, time);
                            } else if(command === "reset_password") {
                                if(receiver === __ME__) {
                                    protocolLib[command](command, type, parameter, sender, receiver, time);
                                }
                            } else {
                                if(receiver === __ME__ || sender === __ME__ || receiver === "*" || receiver === __MY_PRIVILEGES__.response_data[0]["uid"]) {
                                    if(receiver === __ME__ || receiver === __MY_PRIVILEGES__.response_data[0]["uid"]) {
                                        var audio = new Audio(), i = 0;
                                        audio.volume = 0.5;
                                        audio.playbackRate = 0.1;
                                        audio.loop = false;
                                        var playlist = [
                                            __HOST__ + "/audio/notif.mp3"
                                        ];
                                        var currentLength = 0;

                                        audio.addEventListener('ended', function () {
                                            i++;
                                            if(i == playlist.length) {
                                                audio.pause();
                                                audio.currentTime = 0;
                                                i = 0;
                                                console.log("Finished");
                                            } else {
                                                console.log("Playing : " + playlist[i]);
                                                audio.src = playlist[i];
                                                audio.play();
                                            }
                                        });

                                        audio.src = playlist[0];
                                        audio.currentTime = 0;
                                        audio.volume = 0.5;
                                        audio.playbackRate = 1;
                                        audio.loop = false;
                                        audio.play();
                                    }
                                    protocolLib[command](command, type, parameter, sender, receiver, time);
                                    //console.log("Sesuai " + __MY_PRIVILEGES__.response_data[0]["uid"]);
                                } else {
                                    protocolLib[command](command, type, parameter, sender, receiver, time);
                                    //console.log("Tidak sesuai " + __MY_PRIVILEGES__.response_data[0]["uid"]);
                                }
                            }
                        }
                    }
                });

            } else {
                console.log("WebSocket Not Supported");
            }

            $(".buttons-excel, .buttons-csv").css({
                "margin": "0 5px"
            }).removeClass("btn-secondary").addClass("btn-info").find("span").prepend("<i class=\"fa fa-dolly-flatbed\"></i>");

        });

        $(".global-sync-container").fadeOut();

        function SocketCheck(serverTarget, protocolLib, tm) {
            var audio;

            var Sync = new WebSocket(serverTarget);
            Sync.onopen = function() {
                clearInterval(tm);
                if(!currentLoggedInState) {
                    push_socket("system", "loggedIn", "*", "User logged in", "info").then(function() {
                        localStorage.setItem("currentLoggedInState", true);
                    });
                }
                $(".global-sync-container").fadeOut();
            }

            Sync.onmessage = function(evt) {
                var signalData = JSON.parse(evt.data);
                var command = signalData.protocols;
                var type = signalData.type;
                var sender = signalData.sender;
                var receiver = signalData.receiver;
                var time = signalData.time;
                var parameter = signalData.parameter;


                if(command !== undefined && command !== null && command !== "") {

                    if(protocolLib[command] !== undefined) {
                        if(command === "anjungan_kunjungan_panggil") {
                            if (audio !== undefined && audio.audio !== undefined) {
                                if (!audio.paused) {
                                    audio.audio.pause();
                                    audio.audio.currentTime = 0;
                                } else {
                                    //alert();
                                }
                            }
                            audio = protocolLib[command](command, type, parameter, sender, receiver, time);
                        } else if(command === "reset_password") {
                            if(receiver === __ME__) {
                                protocolLib[command](command, type, parameter, sender, receiver, time);
                            }
                        } else {
                            if(receiver === __ME__ || sender === __ME__ || receiver === "*" || receiver === __MY_PRIVILEGES__.response_data[0]["uid"]) {
                                if(receiver === __ME__ || receiver === __MY_PRIVILEGES__.response_data[0]["uid"]) {
                                    var audio = new Audio(), i = 0;
                                    audio.volume = 0.5;
                                    audio.playbackRate = 0.1;
                                    audio.loop = false;
                                    var playlist = [
                                        __HOST__ + "/audio/notif.mp3"
                                    ];
                                    var currentLength = 0;

                                    audio.addEventListener('ended', function () {
                                        i++;
                                        if(i == playlist.length) {
                                            audio.pause();
                                            audio.currentTime = 0;
                                            i = 0;
                                            console.log("Finished");
                                        } else {
                                            console.log("Playing : " + playlist[i]);
                                            audio.src = playlist[i];
                                            audio.play();
                                        }
                                    });

                                    audio.src = playlist[0];
                                    audio.currentTime = 0;
                                    audio.volume = 0.5;
                                    audio.playbackRate = 1;
                                    audio.loop = false;
                                    audio.play();
                                }
                                protocolLib[command](command, type, parameter, sender, receiver, time);
                                //console.log("Sesuai " + __MY_PRIVILEGES__.response_data[0]["uid"]);
                            } else {
                                protocolLib[command](command, type, parameter, sender, receiver, time);
                                //console.log("Tidak sesuai " + __MY_PRIVILEGES__.response_data[0]["uid"]);
                            }
                        }
                    }
                }
            }

            Sync.onclose = function() {
                $(".global-sync-container").fadeIn();
                var tryCount = 1;
                // tm = setInterval(function() {
                //     console.clear();
                //     console.log("CPR..." + tryCount);
                //     Sync = SocketCheck("ws://10.2.2.48:8089/socket.io/?EIO=3&transport=websocket", protocolLib, tm);
                //     tryCount++;
                // }, 3000);
            }

            Sync.onerror = function() {
                /*$(".global-sync-container").fadeIn();
                var tryCount = 1;
                tm = setInterval(function() {
                    console.clear();
                    console.log("CPR..." + tryCount);
                    Sync = SocketCheck(serverTarget, protocolLib);
                    tryCount++;
                }, 3000);*/
            }

            return Sync;
        }

		function inArray(needle, haystack) {
			var length = haystack.length;
			for(var i = 0; i < length; i++) {
				if(haystack[i] == needle) return true;
			}
			return false;
		}

        var floatContainer = document.createElement("DIV");
        $(floatContainer).addClass("manual_container");
        $("body").append(floatContainer);

        function notify_manual(mode, title, time, identifier, setTo, pos = "left") {
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

            var parentPos = $(setTo).offset();
            if(parentPos !== undefined) {
                var topPos = parentPos.top;
                var leftPos = parentPos.left;

                var marginFrom = 30;

                var floatContainer = document.createElement("DIV");
                $(floatContainer).append(alertContainer);

                $(floatContainer).addClass("manual_container");

                $("body").append(floatContainer);

                if(pos === "left") {
                    $(".manual_container").css({
                        "top": topPos + "px",
                        "left": (leftPos - $(floatContainer).width() - marginFrom) + "px"
                    });
                } else if(pos === "bottom") {
                    $(".manual_container").css({
                        "top": (topPos - $(floatContainer).width() - marginFrom) + "px",
                        "left": leftPos + "px"
                    });
                }

                setTimeout(function() {
                    $(alertContainer).fadeOut();
                }, time);
            }
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

        function titleCase(str) {
            var splitStr = str.toLowerCase().split(' ');
            for (var i = 0; i < splitStr.length; i++) {
                // You do not need to check if i is larger than splitStr length, as your for does that for you
                // Assign it back to the array
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
            }
            // Directly return the joined string
            return splitStr.join(' ');
        }

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


		function bpjs_load_faskes() {
			var dataFaskes = [];
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/BPJS/get_faskes",
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var data = [];
					if(response == undefined || response.response_package == undefined || response.response_package.response_data == undefined) {
						dataFaskes = [];
					} else {
						dataFaskes = response.response_package.response_data;
					}
				},
				error: function(response) {
					console.log(response);
				}
			});

			return dataFaskes;
		}

		function str_pad(str_length, target, objectPad = "0") {
			target = "" + target;
			var pad = "";
			for(var a = 1; a <= str_length; a++) {
				pad += objectPad;
			}
			var ans = pad.substring(0, pad.length - target.length) + target;
			return ans;
		}

		$(function() {
			var sideMenu1 = <?php echo json_encode($sideMenu1); ?>;
			var sideMenu2 = <?php echo json_encode($sideMenu2); ?>;
			var sideMenu3 = <?php echo json_encode($sideMenu3); ?>;

            $(".dataTables_filter .form-control").removeClass("form-control-sm");
            $(".dataTables_length .custom-select").removeClass("custom-select-sm");

			if(sideMenu1 > 0) {
				$("#sidemenu_1").show();
			} else {
				$("#sidemenu_1").hide();
			}

			if(sideMenu2 > 0) {
				$("#sidemenu_2").show();
			} else {
				$("#sidemenu_2").hide();
			}

			if(sideMenu3 > 0) {
				$("#sidemenu_3").show();
			} else {
				$("#sidemenu_3").hide();
			}


			$(".tooltip-custom").each(function() {
				var data = $(this).attr("data-toggle");
				$(this).tooltip({
					placement: "top",
					title: data
				});
			});

			$(".sidebar-menu").each(function(e) {
				$(this).find("li.sidebar-menu-item").each(function(f) {
					var shimmer = document.createElement("DIV");
					$(shimmer).addClass("shine");
					$(".sidemenu-shimmer").append(shimmer);
				});
			});

			var weekday=new Array(7);
			weekday[0]="Minggu";
			weekday[1]="Senin";
			weekday[2]="Selasa";
			weekday[3]="Rabu";
			weekday[4]="Kamis";
			weekday[5]="Jumat";
			weekday[6]="Sabtu";

			var monthName=new Array(7);
			monthName[0]="Januari";
			monthName[1]="Februari";
			monthName[2]="Maret";
			monthName[3]="April";
			monthName[4]="Mei";
			monthName[5]="Juni";
			monthName[6]="Juli";
			monthName[7]="Agustus";
			monthName[8]="September";
			monthName[9]="Oktober";
			monthName[10]="November";
			monthName[11]="Desember";

            $("#logoutButton").click(function() {
                push_socket("system", "loggedOut", "*", "User logged out", "info").then(function() {
                    $.ajax({
                        url:__HOSTAPI__ + "/Pegawai",
                        type: "POST",
                        data: {
                            request: "logged_out"
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            localStorage.removeItem("currentLoggedInState");
                            location.href = __HOSTNAME__;
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                });
                return false;
            });
		});
	</script>
	<div class="notification-container"></div>
</body>

</html>