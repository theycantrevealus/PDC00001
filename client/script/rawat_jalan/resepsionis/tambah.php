<script type="text/javascript">
	$(function(){
		var currentPasien = localStorage.getItem("currentPasien");
		var currentAntrianID = localStorage.getItem("currentAntrianID");
		var curremtAntrianType = localStorage.getItem("currentAntrianType");


		var penjaminMetaData;

		/*alert(currentPasien);
		alert(currentAntrianID);*/

		var uid_pasien = __PAGES__[3];
		var dataPasien = loadPasien(uid_pasien);

		loadPenjamin();
		if(curremtAntrianType !== "DEFAULT") {
            loadPoli(curremtAntrianType);
            loadDokter(curremtAntrianType);
        } else {
            loadPoli();
        }

        if(curremtAntrianType !== "DEFAULT") {
            loadPrioritas(__PRIORITY_HIGH__);
            loadBed(__KAMAR_IGD__);
            loadCaraDatang();
        } else {
            loadPrioritas();
        }

		$("#departemen").on('change', function(){
			var poli = $(this).val();

			if (poli != ""){
				loadDokter(poli);
			}
		});

        $("#modal-sep").on('hidden.bs.modal', function () {
            $("#btnSubmit").removeAttr("disabled").addClass("btn-success").removeClass("btn-warning").html("Simpan Data");
        });
		
		$("#btnSubmit").click(function(){
            var me = $(this);
            var lastCap = me.html();
            me.attr({
                "disabled" : "disabled"
            }).addClass("btn-warning").removeClass("btn-success").html("Processing...");
			var dataObj = {};
			if(currentAntrianID != undefined || currentAntrianID != null) {
				$('.inputan').each(function(){
					var key = $(this).attr("id");
					var value = $(this).val();
					dataObj[key] = value;
				});

				dataObj.pasien = uid_pasien;
				dataObj.currentPasien = currentPasien;
				dataObj.currentAntrianID = currentAntrianID;

				if(dataObj.departemen != null && dataObj.dokter != null && dataObj.penjamin != null && dataObj.prioritas != null) {
					if(dataObj.penjamin === __UIDPENJAMINBPJS__) {


                        if(__BPJS_MODE__ > 0) {
                            //Get Nomor BPJS
                            $.ajax({
                                async: false,
                                url: __HOSTAPI__ + "/BPJS/info_bpjs/" + __PAGES__[3],
                                beforeSend: function (request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type: "GET",
                                success: function (response) {
                                    $("#modal-sep").modal("show");
                                    $("#btnProsesPasien").hide();
                                    $("#btnProsesSEP").hide();
                                    $("#hasil_bpjs").hide();

                                    var data = response.response_package.response_data[0];
                                    var restMeta;
                                    var penjaminList = data.history_penjamin;
                                    console.log(penjaminList);
                                    for(var penjaminKey in penjaminList) {
                                        if(penjaminList[penjaminKey].penjamin === __UIDPENJAMINBPJS__) {
                                            restMeta = JSON.parse(penjaminList[penjaminKey].rest_meta);
                                            console.log(restMeta);
                                        }
                                    }

                                    if(restMeta !== undefined) {
                                        if(restMeta !== undefined && restMeta !== null) {
                                            $("#txt_no_bpjs").val(restMeta.data.peserta.noKartu);
                                            penjaminMetaData = cekPasienBPJS(loadPasien(__PAGES__[3]), restMeta.data.peserta.noKartu);
                                        }

                                    } else {
                                        $("#txt_no_bpjs").focus();
                                    }

                                    me.removeAttr("disabled").addClass("btn-success").removeClass("btn-warning").html(lastCap);
                                },
                                error: function (response) {
                                    console.log(response);
                                    me.removeAttr("disabled").addClass("btn-success").removeClass("btn-warning").html(lastCap);
                                }
                            });
                        } else {
                            $.ajax({
                                async: false,
                                url: __HOSTAPI__ + "/Antrian",
                                data: {
                                    request : "tambah-kunjungan",
                                    dataObj : dataObj
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type: "POST",
                                success: function(response) {
                                    console.clear();
                                    localStorage.getItem("currentPasien");
                                    localStorage.getItem("currentAntrianID");
                                    console.log(response);

                                    if(response.response_package.response_notif == 'K') {
                                        push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                            Swal.fire(
                                                'Berhasil ditambahkan!',
                                                'Silahkan arahkan pasien ke kasir',
                                                'success'
                                            ).then((result) => {
                                                location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                            });
                                        });

                                    } else if(response.response_package.response_notif == 'P') {
                                        // push_socket(__ME__, "kasir_daftar_baru", "*", "Antrian pasien a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                            // Swal.fire(
                                            //     'Berhasil ditambahkan!',
                                            //     'Silahkan arahkan pasien ke poli',
                                            //     'success'
                                            // ).then((result) => {
                                            //     location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                            // });
                                        // });
                                        // if(dataObj.penjamin === __UIDPENJAMINUMUM__) {
                                        //     push_socket(__ME__, "antrian_poli_baru", "*", "Antrian poli baru", "info").then(function() {
                                        //         console.log("pushed!");
                                        //     });
                                        // }
                                        Swal.fire(
                                            'Berhasil ditambahkan!',
                                            'Silahkan arahkan pasien ke poli',
                                            'success'
                                        ).then((result) => {
                                            push_socket(__ME__, "antrian_poli_baru", "*", "Antrian poli baru", "info").then(function() {
                                                location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                            });
                                        });

                                    } else {
                                        console.log("command not found");
                                    }

                                    me.attr({
                                        "disabled" : "disabled"
                                    }).addClass("btn-success").removeClass("btn-warning").html(lastCap);

                                },
                                error: function(response) {
                                    console.log("Error : ");
                                    console.log(response);
                                    me.attr({
                                        "disabled" : "disabled"
                                    }).addClass("btn-success").removeClass("btn-warning").html(lastCap);
                                }
                            });
                        }


					} else {


                        if(dataObj.departemen === __POLI_IGD__) {
                            if(
                                dataObj.bangsal !== null && dataObj.bangsal !== undefined &&
                                dataObj.cara_datang !== null && dataObj.cara_datang !== undefined
                            ) {
                                $.ajax({
                                    async: false,
                                    url: __HOSTAPI__ + "/Antrian",
                                    data: {
                                        request : "tambah-kunjungan",
                                        dataObj : dataObj
                                    },
                                    beforeSend: function(request) {
                                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                    },
                                    type: "POST",
                                    success: function(response){
                                        localStorage.getItem("currentPasien");
                                        localStorage.getItem("currentAntrianID");

                                        
                                        if(response.response_package.response_notif == 'K') {
                                            push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                                Swal.fire(
                                                    'Berhasil ditambahkan!',
                                                    'Silahkan arahkan pasien ke kasir',
                                                    'success'
                                                ).then((result) => {
                                                    location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                                });
                                            });

                                        } else if(response.response_package.response_notif == 'P') {
                                            /*push_socket(__ME__, "kasir_daftar_baru", "*", "Antrian pasien a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                                Swal.fire(
                                                    'Berhasil ditambahkan!',
                                                    'Silahkan arahkan pasien ke poli',
                                                    'success'
                                                ).then((result) => {
                                                    location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                                });
                                            });*/
                                            if(dataObj.penjamin === __UIDPENJAMINUMUM__) {
                                                push_socket(__ME__, "antrian_poli_baru", "*", "Antrian poli baru", "info").then(function() {
                                                    console.log("pushed!");
                                                });
                                            }

                                        } else {
                                            alert();
                                            console.log("command not found");
                                        }

                                        me.attr({
                                            "disabled" : "disabled"
                                        }).addClass("btn-success").removeClass("btn-warning").html(lastCap);

                                    },
                                    error: function(response) {
                                        console.log("Error : ");
                                        console.log(response);
                                    }
                                });
                            } else {
                                if(dataObj.bangsal === null || dataObj.bangsal === undefined) {
                                    Swal.fire(
                                        'Pendaftaran IGD',
                                        'Silahkan pilih ranjang',
                                        'error'
                                    ).then((result) => {
                                        //
                                    });
                                } else if(dataObj.cara_datang === null || dataObj.cara_datang === undefined) {
                                    Swal.fire(
                                        'Pendaftaran IGD',
                                        'Silahkan pilih cara datang',
                                        'error'
                                    ).then((result) => {
                                        //
                                    });
                                }
                                me.removeAttr("disabled").addClass("btn-success").removeClass("btn-warning").html(lastCap);
                            }
                        } else {
                            $.ajax({
                                async: false,
                                url: __HOSTAPI__ + "/Antrian",
                                data: {
                                    request : "tambah-kunjungan",
                                    dataObj : dataObj
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type: "POST",
                                success: function(response){
                                    console.log(response);
                                    localStorage.getItem("currentPasien");
                                    localStorage.getItem("currentAntrianID");

                                    if(response.response_package.response_notif == 'K') {
                                        push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                            Swal.fire(
                                                'Berhasil ditambahkan!',
                                                'Silahkan arahkan pasien ke kasir',
                                                'success'
                                            ).then((result) => {
                                                location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                            });
                                        });

                                    } else if(response.response_package.response_notif == 'P') {
                                        /*push_socket(__ME__, "kasir_daftar_baru", "*", "Antrian pasien a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                            Swal.fire(
                                                'Berhasil ditambahkan!',
                                                'Silahkan arahkan pasien ke poli',
                                                'success'
                                            ).then((result) => {
                                                location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                            });
                                        });*/
                                        // if(dataObj.penjamin === __UIDPENJAMINUMUM__) {
                                        //     push_socket(__ME__, "antrian_poli_baru", "*", "Antrian poli baru", "info").then(function() {
                                        //         console.log("pushed!");
                                        //     });
                                        // }

                                        Swal.fire(
                                            'Berhasil ditambahkan!',
                                            'Silahkan arahkan pasien ke poli',
                                            'success'
                                        ).then((result) => {
                                            push_socket(__ME__, "antrian_poli_baru", "*", "Antrian poli baru", "info").then(function() {
                                                location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                            });
                                        });

                                    } else {
                                        alert();
                                        console.log("command not found");
                                    }

                                    me.removeAttr("disabled").addClass("btn-success").removeClass("btn-warning").html(lastCap);

                                },
                                error: function(response) {
                                    console.log("Error : ");
                                    console.log(response);
                                }
                            });
                        }
					}
				} else {
					Swal.fire(
                        'Pendaftaran IGD',
                        'Data Belum Lengkap',
                        'error'
                    ).then((result) => {
                        me.removeAttr("disabled").addClass("btn-success").removeClass("btn-warning").html(lastCap);
                    });
				}
			} else {
			    console.log(currentAntrianID);
                console.log(currentPasien);
            }
			return false;
		});

        function loadBed(ruangan) {
            $.ajax({
                url:__HOSTAPI__ + "/Bed/bed-ruangan/" + ruangan,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    console.log(response);
                    var MetaData = response.response_package.response_data;
                    $("#ruangan option").remove();
                    for(i = 0; i < MetaData.length; i++){
                        if(MetaData[i].status == "R") {
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            $("#bangsal").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

		$("#btnProsesPasien").click(function() {
			var dataObj = {};
			$('.inputan').each(function(){
				var key = $(this).attr("id");
				var value = $(this).val();

				dataObj[key] = value;
			});

			dataObj.pasien = uid_pasien;
			dataObj.currentPasien = currentPasien;
			dataObj.currentAntrianID = currentAntrianID;
			if(dataObj.penjamin === __UIDPENJAMINBPJS__) {
			    //Add SEP Info
                dataObj.valid_start = penjaminMetaData.data.peserta.tglTMT;
                dataObj.valid_end = penjaminMetaData.data.peserta.tglTAT;
                dataObj.penjaminMeta = JSON.stringify(penjaminMetaData);
            }

			if(dataObj.departemen != null && dataObj.dokter != null && dataObj.penjamin != null && dataObj.prioritas != null) {
			    $.ajax({
					async: false,
					url: __HOSTAPI__ + "/Antrian",
					data: {
						request : "tambah-kunjungan",
						dataObj : dataObj
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
                        localStorage.getItem("currentPasien");
                        localStorage.getItem("currentAntrianID");


                        if(response.response_package.response_notif == 'K') {
                            push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                Swal.fire(
                                    'Berhasil ditambahkan!',
                                    'Silahkan arahkan pasien ke kasir',
                                    'success'
                                ).then((result) => {
                                    location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                });
                            });

                        } else if(response.response_package.response_notif == 'P') {
                            /*push_socket(__ME__, "antrian_poli_baru", "*", "Antrian pasien a/n. " + $("#nama").val(), "warning").then(function () {

                            });*/

                            Swal.fire(
                                'Berhasil ditambahkan!',
                                'Silahkan arahkan pasien ke poli',
                                'success'
                            ).then((result) => {
                                location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                            });

                        } else {
                            console.log(response);
                        }
					},
					error: function(response) {
						console.log("Error : ");
						console.log(response);
					}
				});
			}
		});

		$(".select2").select2({});





		$("#hasil_bpjs").hide();
		$("#btnProsesPasien").hide();

		$("#btnCariPasien").click(function() {
            penjaminMetaData = cekPasienBPJS(dataPasien, $("#txt_no_bpjs").val());
		});
	});

	function cekPasienBPJS(dataPasien, target) {
	    var penjaminMetaData;
	    $("#hasil_bpjs").hide();
        $("#btnProsesPasien").hide();
        $.ajax({
            url: __HOSTAPI__ + "/BPJS",
            async: false,
            data: {
                request : "cek_peserta",
                no_bpjs: target
            },
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "POST",
            success: function(response) {

                console.clear();

                console.log(response);
                
                var data = response.response_package;

                
                penjaminMetaData = data;

                if(data.metaData !== undefined) {
                    if(parseInt(data.metaData.code) === 200) {
                        if(data.data === null) {
                            cekPasienBPJS(dataPasien, target);
                        } else {
                            $("#hasil_bpjs").fadeIn();
                            var pasienData = data.data.peserta;
                            $("#btnProsesPasien").show();
                            
                            if(pasienData.statusPeserta.keterangan === "AKTIF") {
                                if(pasienData.nik != dataPasien.nik) { //Cek NIK
                                    $("#status_bpjs").addClass("text-danger").removeClass("text-success").html("NIK tidak sama");
                                } else {
                                    $("#status_bpjs").addClass("text-success").removeClass("text-danger").html(pasienData.statusPeserta.keterangan);
                                    //$("#btnProsesPasien").show();
                                }
                            } else {
                                $("#status_bpjs").addClass("text-danger").removeClass("text-success").html(pasienData.statusPeserta.keterangan);
                            }

                            $("#pekerjaan_pasien").html(pasienData.jenisPeserta.keterangan);
                            $("#nama_pasien").html(pasienData.nama);
                            $("#nik_pasien").html(pasienData.nik);
                            $("#nomor_peserta").html(pasienData.noKartu);
                            $("#tll_pasien").html(pasienData.tglLahir);
                            //$("#faskes_pasien").html(pasienData.provUmum.kdProvider + " " + pasienData.provUmum.nmProvider);
                            $("#faskes_pasien").html(pasienData.provUmum.kdProvider + " - " + pasienData.provUmum.nmProvider);
                            $("#usia_pasien").html(pasienData.umur.umurSaatPelayanan);
                            $("#kelamin_pasien").html((pasienData.sex == "L") ? "Laki-laki" : "Perempuan");

                            $("#tanggal_kartu").html(pasienData.tglCetakKartu);

                            //TAT Tanggal Akhir Kartu
                            //TMT Tanggal Mulai Kartu


                            $("#txt_bpjs_nomor").val($("#txt_no_bpjs").val());
                            $("#txt_bpjs_nik").val(pasienData.nik);
                            $("#txt_bpjs_nama").val(pasienData.nama);
                            $("#txt_bpjs_rm").val($("#no_rm").val());
                        }
                    } else {
                        Swal.fire(
                            'BPJS',
                            data.metaData.message,
                            'warning'
                        ).then((result) => {
                            $("#txt_no_bpjs").focus();
                        });
                    }
                }
            },
            error: function(response) {
                console.log("Error : ");
                console.log(response);
            }
        });

        return penjaminMetaData;
    }

	function loadPasien(uid){

		var dataPasien = null;

		if (uid != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Antrian/pasien-detail/" + uid,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	            	dataPasien = response.response_package;
	            	$.each(dataPasien, function(key, item){
	                	$("#" + key).val(item);
	                });
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}
		
		return dataPasien;
	}



	/*========== FUNC FOR LOAD PENJAMIN ==========*/
    function loadPenjamin() {
        var dataPenjamin = null;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Penjamin/penjamin",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData !== undefined && MetaData !== null){
                	for(var i in MetaData){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#penjamin").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataPenjamin;
    }

    function loadPoli(targetted = ""){
    	var dataPoli = null;

    	if(targetted === __POLI_IGD__) {
    	    //Show Cara data dan keterangan cara datang
            $(".poli_igd").show();
            $(".poli_lain").hide();
        } else {
            $(".poli_igd").hide();
            $(".poli_lain").show();
        }

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Poli/poli-available",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPoli = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
                        $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    if(MetaData[i].uid !== __POLI_INAP__) {
                            if(targetted !== "") {
                                if(MetaData[i].uid === targetted) {
                                    $(selection).attr("selected", "selected");
                                }
                                $("#departemen").append(selection);
                            } else {
                                if(MetaData[i].editable) {
                                    $("#departemen").append(selection);
                                }
                            }

                        }
	                }

                    if(targetted !== "") {
                        $("#departemen").attr("disabled", "disabled");
                    } else {
                        $("#departemen").removeAttr("disabled");
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataPoli;
    }

    function loadPrioritas(targetted = 0){
    	var term = 11;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + term,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
	                    if(parseInt(targetted) > 0) {
	                        if(parseInt(MetaData[i].id) === targetted) {
                                $(selection).attr("selected", "selected");
                            }
                        }
	                    $("#prioritas").append(selection);
	                }

                    if(parseInt(targetted) > 0) {
                        $("#prioritas").attr("disabled", "disabled");
                    } else {
                        $("#prioritas").removeAttr("disabled");
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadCaraDatang(targetted = 0){
        var term = 17;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + term,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                    for(i = 0; i < MetaData.length; i++){
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                        if(parseInt(targetted) > 0) {
                            if(parseInt(MetaData[i].id) === targetted) {
                                $(selection).attr("selected", "selected");
                            }
                        }
                        $("#cara_datang").append(selection);
                    }

                    if(parseInt(targetted) > 0) {
                        $("#cara_datang").attr("disabled", "disabled");
                    } else {
                        $("#cara_datang").removeAttr("disabled");
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadDokter(poli){
    	resetSelectBox('dokter', 'Dokter');

    	$.ajax({
    		async: false,
            url:__HOSTAPI__ + "/Poli/poli-set-dokter/" + poli,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPoli = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].dokter).html(MetaData[i].nama);
	                    $("#dokter").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
    	})
    }

    function loadKelasRawat(){
        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/BPJS/get_kelas_rawat_select2",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var data = response.response_package.content.response.list;

                $("#txt_bpjs_kelas_rawat option").remove();
                for(var a = 0; a < data.length; a++) {
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", data[a].kode).html(data[a].nama);
                    $("#txt_bpjs_kelas_rawat").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }



    function resetSelectBox(selector, name){
		$("#"+ selector +" option").remove();
		var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
        $("#" + selector).append(opti_null);
	}
</script>
<div id="modal-sep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Pengecekan SEP</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<div class="col-md-12">
						<div class="row">
							<label for="txt_cari">Cek Peserta BPJS</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="search-form search-form--light input-group-lg col-md-10">
								<input type="text" class="form-control" placeholder="No. BPJS" id="txt_no_bpjs" />
							</div>
							<div class="col-md-2">
								<button class="btn btn-success" id="btnCariPasien">
									<i class="fa fa-search"></i>
								</button>
							</div>
                            <div class="col-lg-12">
                                <br />
                                <b class="text-warning">
                                    <i class="fa fa-info-circle"></i> Pastikan nomor sesuai dengan kartu BPJS. Jika tidak sesuai maka kemungkinan pasien yang dipilih salah. Input ulang nomor pada kartu untuk mengecek calon pasien.
                                </b>
                            </div>
						</div>
					</div>
				</div>
				<div class="form-group col-md-12" >
					<table class="table table-striped" id="hasil_bpjs">
						<tr><td width="120px">No. Peserta</td><td width="10px">:</td><td id="nomor_peserta"><?php echo $bpjs_nomor;?></td></tr>
						<tr><td>NIK</td><td>:</td><td id="nik_pasien"></td></tr>
						<tr><td>Nama</td><td>:</td><td id="nama_pasien"></td></tr>
						<tr><td>Tanggal Lahir</td><td>:</td><td id="tll_pasien"></td></tr>
						<tr><td>Usia</td><td>:</td><td id="usia_pasien"></td></tr>
						<tr><td>Jenis Kelamin</td><td>:</td><td id="kelamin_pasien"></td></tr>
						<tr><td>Pekerjaan</td><td>:</td><td id="pekerjaan_pasien"></td></tr>
						<tr><td>Faskes Pertama</td><td>:</td><td id="faskes_pasien"></td></tr>
						<tr><td>Tanggal Kartu</td><td>:</td><td id="tanggal_kartu"></td></tr>
						<tr><td>Status</td><td>:</td><td id="status_bpjs"></td></tr>
					</table>
				</div>
				
			</div>
			<div class="modal-footer">

                <button class="btn btn-success" id="btnProsesPasien">
                    <i class="fa fa-check"></i> Proses
                </button>
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div> 
	</div> 
</div>



<div id="modal-sep-new" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-6 mb-6 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-6 mb-6">
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Nomor Medical Record (MR)</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                        </div>

                                        <div class="col-12 col-md-7 form-group">
                                            <label for="">Tanggal SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly value="<?php echo date('d F Y'); ?>">
                                        </div>
                                        <div class="col-12 col-md-9 form-group">
                                            <label for="">Faskes</label>
                                            <select class="form-control sep" id="txt_bpjs_faskes">
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD KAB. BINTAN - KAB. BINTAN (KEPRI)</option>
                                            </select>
                                        </div>


                                        <div class="col-12 col-md-8 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="2">Rawat Jalan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 mb-9 form-group">
                                            <label for="">Kelas Rawat</label>
                                            <select class="form-control sep" id="txt_bpjs_kelas_rawat"></select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-6">
                                        <div class="col-12 col-md-4 mb-4 form-group">
                                            <label for="">Jenis Asal Rujukan</label>
                                            <select class="form-control uppercase sep" id="txt_bpjs_jenis_asal_rujukan">
                                                <option value="1">Puskesmas</option>
                                                <option value="2">Rumah Sakit</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Asal Rujukan</label>
                                            <select class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                        </div>
                                        <div class="col-12 col-md-5 mb-4 form-group">
                                            <label for="">Tanggal Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan">
                                        </div>
                                        <div class="col-12 col-md-6 mb-4 form-group">
                                            <label for="">Nomor Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor_rujukan" />
                                        </div>
                                        <div class="col-12 col-md-12 form-group">
                                            <label for="">Catatan</label>
                                            <textarea class="form-control" id="txt_bpjs_catatan"></textarea>
                                        </div>
                                        <div class="col-12 col-md-12 form-group">
                                            <label for="">Diagnosa Awal</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_diagnosa_awal" readonly>
                                        </div>
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Poli Tujuan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_poli_tujuan" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesPasien">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>