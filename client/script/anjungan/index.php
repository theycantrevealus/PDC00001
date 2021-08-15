<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function(){
		var jenisData = load_anjungan("#anjungan_selection");
		renderJenis(jenisData);
		$("#anjungan_selection").hide();
		$("body").on("keyup", function(e) {
			var code = e.keyChar || e.which;
			if(code == 13) {
				if($("#anjungan_selection").is(":hidden")) {
					$("#anjungan_selection").show().focus();
					$(".btn").attr("disabled", "disabled");
				} else if($("#anjungan_selection").is(":visible")) {
					$("#anjungan_selection").hide();
					$(".btn").removeAttr("disabled");
				}
			}
		});

		$("#anjungan_selection").change(function() {
			renderJenis(jenisData);
		});

		function renderJenis(jenis) {
			for(var a = 0; a < jenis.length; a++) {
				if(jenis[a].uid == $("#anjungan_selection").val()) {
					$("#loader-jenis").html("");
					for(var b = 0; b < jenis[a].jenis.length; b++) {
						var newJenis = document.createElement("BUTTON");
						$(newJenis).html("<h2>" + jenis[a].jenis[b].nama + "</h2>").attr({
							"id": "antrian_jenis_" + jenis[a].jenis[b].uid
						}).addClass("btn btn-lg btn-info btn-antrian").css({
							"min-height": "150px",
							"margin": "10px"
						});
						var buttonContainer = document.createElement("DIV");
						if(jenis[a].jenis.length < 2) {
							$(buttonContainer).addClass("col-lg-12");
						} else {
							$(buttonContainer).addClass("col-lg-6");
						}
						$(buttonContainer).append(newJenis);
						$("#loader-jenis").append(buttonContainer);
					}
				}
			}
		}

		function load_anjungan(target) {
			var anjunganData;
			$.ajax({
				url:__HOSTAPI__ + "/Anjungan",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					anjunganData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < anjunganData.length; a++) {
						$(target).append("<option value=\"" + anjunganData[a].uid + "\">" + anjunganData[a].kode_anjungan + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return anjunganData;
		}

		$("body").on("click", ".btn-antrian", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			//Jenis Antrian
			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Anjungan",
				data: {
					request: "tambah_antrian",
					anjungan: $("#anjungan_selection").val(),
					jenis: id
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					if(response.response_package.response_result > 0) {
						push_socket(__ME__, "anjungan_kunjungan_baru", "*", "Antrian Baru dengan nomor " + response.response_package.response_antrian, "warning").then(function () {
						    $.ajax({
                                async: false,
                                url: __HOSTNAME__ + "/print/antrian_anjungan.php",
                                data: {
                                    antrian: response.response_package.response_antrian,
                                    __HOSTNAME__: __HOSTNAME__,
                                    __PC_CUSTOMER__: __PC_CUSTOMER__.toUpperCase(),
                                    __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__.toUpperCase(),
                                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                                    __PC_IDENT__: __PC_IDENT__,
                                    __PC_CUSTOMER_EMAIL__: __PC_CUSTOMER_EMAIL__,
                                    __PC_CUSTOMER_ADDRESS_SHORT__: __PC_CUSTOMER_ADDRESS_SHORT__.toUpperCase()
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type: "POST",
                                success: function(response){
                                    var containerItem = document.createElement("DIV");
                                    $(containerItem).html(response);
                                    $(containerItem).printThis({
                                        header: null,
                                        footer: null,
                                        pageTitle: "Antrian",
                                        afterPrint: function() {
                                            $("#form-payment-detail").modal("hide");
                                        }
                                    });
                                    /*var win = window.open(document.URL, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
                                    win.document.write(html);
                                    win.document.close();
                                    win.focus();
                                    win.print();
                                    win.close();*/
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        });
					}
				},
				error: function(response) {
					console.log(response);
				}
			});

			return false;
		});
		//$("#anjungan_selection").select2();
	});
</script>