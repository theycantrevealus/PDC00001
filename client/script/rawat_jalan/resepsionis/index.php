<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function(){
		var params;
		var MODE = false;
		var currentAntrianType = "DEFAULT";
        $(".sep").select2();
        $("#txt_bpjs_tanggal_rujukan").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true,
            beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }
        });

        $("#txt_bpjs_laka_tanggal").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true
        }).datepicker("setDate", new Date());



        $(".laka_lantas_suplesi_container").hide();
        $(".laka_lantas_container").hide();


        $("input[type=\"radio\"][name=\"txt_bpjs_laka\"]").change(function() {
            if(parseInt($(this).val()) === 1) {
                $(".laka_lantas_container").fadeIn();
            } else {
                $(".laka_lantas_container").fadeOut();
            }
        });

        $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]").change(function() {
            if(parseInt($(this).val()) === 1) {
                $(".laka_lantas_suplesi_container").fadeIn();
            } else {
                $(".laka_lantas_suplesi_container").fadeOut();
            }
        });

        var selectedLakaPenjamin = [];
        var selectedListRujukan = [];

        $("input[type=\"checkbox\"][name=\"txt_bpjs_laka_penjamin\"]").change(function() {
            var selectedvalue = $(this).val();
            if($(this).is(":checked")) {
                if(selectedLakaPenjamin.indexOf(selectedvalue) < 0)
                {
                    selectedLakaPenjamin.push(selectedvalue);
                }
            } else {
                selectedLakaPenjamin.splice(selectedLakaPenjamin.indexOf(selectedvalue), 1);
            }
        });


		var tableAntrian= $("#table-antrian-rawat-jalan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Antrian/antrian",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var data = response.response_package.response_data;
				    var filtered = [];
				    for(var key in data) {
				        if(data[key].departemen !== "IGD") {
				            filtered.push(data[key])
                        }
                    }
					return filtered;
				}
			},
			autoWidth: false,
			"bInfo" : false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"waktu_masuk_" + row.uid + "\">" + row["waktu_masuk"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row.uid_pasien + "\">" + row["pasien"] + "<span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"poli_" + row.uid_pasien + "\">" + row["departemen"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"dokter_" + row.uid + "\">" + row.dokter + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
					    if(row["uid_penjamin"] == __UIDPENJAMINBPJS__) {
							if(parseInt(row['sep']) > 0) {
								return row["penjamin"] + " <h6 class=\"nomor_sep\">" + row.sep + "</h6>";
							} else {
							    if(row.waktu_keluar !== undefined && row.waktu_keluar !== null) {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                } else {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
							        //return row["penjamin"];
                                }
							}
						} else {
							return row["penjamin"];
						}
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["user_resepsionis"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-success btn-sm btn-pasien-pulang\">" +
										"<i class=\"fa fa-check\"></i>" +
									"</button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"gelang\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"kartu\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"lab\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"tracer\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"spbk\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"sosial\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"bayi\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                    "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"identitas\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +

								"</div>";
					}
				}
			]
		});




        var tableAntrianIGD= $("#table-antrian-IGD").DataTable({
            "ajax":{
                url: __HOSTAPI__ + "/Antrian/igd",
                type: "GET",
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    return response.response_package.response_data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["waktu_masuk"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row.uid_pasien + "\">" + row["pasien"] + "<span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row["uid_penjamin"] == __UIDPENJAMINBPJS__) {
                            if(parseInt(row['sep']) > 0) {
                                return row["penjamin"] + " <h6 class=\"nomor_sep\">" + row.sep + "</h6>";
                            } else {
                                if(row.waktu_keluar !== undefined && row.waktu_keluar !== null) {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                } else {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    //return row["penjamin"];
                                }
                            }
                        } else {
                            return row["penjamin"];
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["user_resepsionis"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-info btn-sm btn-pasien-pulang\">" +
                            "<i class=\"fa fa-check\"></i> Pulangkan Pasien" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });


        $("#table-antrian-RI").DataTable({
            "ajax":{
                url: __HOSTAPI__ + "/Antrian/rawat_inap",
                type: "GET",
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    return response.response_package.response_data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"waktu_masuk_" + row.uid + "\">" + row["waktu_masuk"] + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row.uid_pasien + "\">" + row["pasien"] + "<span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row["uid_penjamin"] == __UIDPENJAMINBPJS__) {
                            if(parseInt(row['sep']) > 0) {
                                return row["penjamin"] + " <h6 class=\"nomor_sep\">" + row.sep + "</h6>";
                            } else {
                                if(row.waktu_keluar !== undefined && row.waktu_keluar !== null) {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                } else {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    //return row["penjamin"];
                                }
                            }
                        } else {
                            return row["penjamin"];
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["user_resepsionis"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-info btn-sm btn-pasien-pulang\">" +
                            "<i class=\"fa fa-check\"></i> Pulangkan Pasien" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#btnTambahIGD").click(function() {
            //$("#modal-tambah-igd").modal("show");
            currentAntrianType = __POLI_IGD__;
            $("#modal-cari").modal("show");
        });

		$("body").on("click", ".btn-pasien-pulang", function() {
		    var id = $(this).attr("id").split("_");
		    id = id[id.length - 1];

            Swal.fire({
                title: "Pulangkan pasien?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Antrian",
                        type: "POST",
                        data: {
                            request: "pulangkan_pasien",
                            uid: id
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            console.log(response);
                            if(response.response_package.response_result > 0) {
                                tableAntrian.ajax.reload();
                            } else {
                                Swal.fire(
                                    "Pulangkan pasien",
                                    "Pasien gagal dipulangkan",
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
		    return false;
        });


        

        var selectedSEPAntrian = "";
        var selectedSEPAntriamMeta;
        var selectedSEPNoKartu = "";

        var isRujukan = false;

        $("body").on("click", ".daftar_sep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Pasien/pasien-detail/" + id,
                type: "GET",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function (response) {
                    var data = response.response_package.response_data[0];
                    var penjaminList = data.history_penjamin;
                    var bpjsMeta;

                    for(var penjaminKey in penjaminList) {
                        if(penjaminList[penjaminKey].penjamin === __UIDPENJAMINBPJS__) {
                            bpjsMeta = penjaminList[penjaminKey].rest_meta;
                        }
                    }

                    if(bpjsMeta !== undefined) {
                        $("#txt_bpjs_nama").val(data.nama);
                        $("#txt_bpjs_nik").val(data.nik);
                        $("#txt_bpjs_telepon").val(data.no_telp);

                        for(var pKey in data.history_penjamin) {
                            if(data.history_penjamin[pKey].penjamin === __UIDPENJAMINBPJS__)
                            {
                                var metaDataBPJS = JSON.parse(data.history_penjamin[pKey].rest_meta);
                                selectedSEPNoKartu = metaDataBPJS.response.peserta.noKartu;
                                $("#txt_bpjs_nomor").val(metaDataBPJS.response.peserta.noKartu);
                                loadKelasRawat(metaDataBPJS.response.peserta.hakKelas.keterangan);
                            }
                        }
                    }
                },
                error: function(response) {
                    //
                }
            });

            loadProvinsi("#txt_bpjs_laka_suplesi_provinsi");
            loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val());
            loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val());
            loadSpesialistik("#txt_bpjs_dpjp_spesialistik");


            $("#txt_bpjs_laka_suplesi_provinsi").select2({
                dropdownParent: $("#group_provinsi")
            });

            $("#txt_bpjs_dpjp_spesialistik").select2({
                dropdownParent: $("#group_spesialistik")
            });

            $("#txt_bpjs_laka_suplesi_kabupaten").select2({
                dropdownParent: $("#group_kabupaten")
            });

            $("#txt_bpjs_laka_suplesi_kecamatan").select2({
                dropdownParent: $("#group_kecamatan")
            });

            $("#txt_bpjs_nomor_rujukan").select2({
                autoclose: true,
                dropdownParent: $("#group_nomor_rujukan")
            });

            $("#txt_bpjs_dpjp").select2({
                dropdownParent: $("#group_dpjp")
            });

            $("#txt_bpjs_kelas_rawat").select2({
                dropdownParent: $("#group_kelas_rawat")
            });

            $("#txt_bpjs_asal_rujukan").select2({disabled:"readonly"});

            $("#txt_bpjs_jenis_asal_rujukan").select2({disabled:"readonly"});





            $("#txt_bpjs_jenis_asal_rujukan").change(function() {
                loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
            });

            $("#txt_bpjs_dpjp_spesialistik").change(function() {
                loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
            });

            $("#txt_bpjs_nomor_rujukan").change(function() {
                loadInformasiRujukan(selectedListRujukan[$(this).find("option:selected").index()]);
            });

            /*$("#txt_bpjs_asal_rujukan").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Faskes tidak ditemukan";
                    }
                },
                dropdownParent: $("#modal-sep-new"),
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_faskes_select2/",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term,
                        type:$("#txt_bpjs_jenis_asal_rujukan").val()
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.faskes;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });*/

            $("#txt_bpjs_diagnosa_awal").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Diagnosa tidak ditemukan";
                    }
                },
                dropdownParent: $("#group_diagnosa"),
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/BPJS/get_diagnosa",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.content.response.diagnosa;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control").on("select2:select", function(e) {
                //
            });

            $("#txt_bpjs_poli_tujuan").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Faskes tidak ditemukan";
                    }
                },
                dropdownParent: $("#group_poli"),
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/BPJS/get_poli",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.content.response.poli;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control").on("select2:select", function(e) {
                //
            });
            
            
            //======================================================================
            
            
            var SEPButton = $(this);
            SEPButton.html("Memuat SEP...").removeClass("btn-info").addClass("btn-warning");

            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var antrian = $(this).attr("antrian");
            selectedSEPAntrian = antrian;

            var allowSEP = $(this).attr("allow_sep");
            if(allowSEP === "1") {
                $("#btnProsesSEP").show();
            } else {
                $("#btnProsesSEP").hide();
            }
            $("#txt_bpjs_rm").val($("#rm_" + uid).html());

            $("#txt_bpjs_internal_poli").html($("#poli_" + uid).html());


            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Asesmen/antrian-detail/" + antrian,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var data = response.response_package.response_data[0];
                    selectedSEPAntriamMeta = data;

                    var diagnosa_kerja = data.diagnosa_kerja;
                    var diagnosa_banding = data.diagnosa_banding;
                    var icd10_kerja = data.icd10_kerja;
                    var icd10_banding = data.icd10_banding;

                    $("#txt_bpjs_internal_dk").html(diagnosa_kerja);
                    $("#txt_bpjs_internal_db").html(diagnosa_banding);


                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/BPJS/get_rujukan_list/" + $("#txt_bpjs_nomor").val(),
                        type: "GET",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            $("#txt_bpjs_nomor_rujukan " + " option").remove();
                            $("#txt_bpjs_nomor_rujukan").select2("destroy");
                            $("#txt_bpjs_nomor_rujukan").select2();
                            $("#txt_bpjs_nomor_rujukan").select2("val", "");
                            if(response.response_package.content.response !== null) {
                                $("#panel-rujukan").show();
                                var data = response.response_package.content.response.rujukan;
                                selectedListRujukan = data;



                                if(data.length > 0) {
                                    isRujukan = true;
                                    for(var a = 0; a < data.length; a++) {
                                        if(parseInt(data[a].pelayanan.kode) === 2) {
                                            var selection = document.createElement("OPTION");

                                            $(selection).attr("value", data[a].noKunjungan.toUpperCase()).html(data[a].noKunjungan.toUpperCase());
                                            $("#txt_bpjs_nomor_rujukan").append(selection);
                                        }
                                    }

                                    $(".informasi_rujukan").show();
                                    $("#btnProsesSEP").show();
                                    loadInformasiRujukan(selectedListRujukan[0]);
                                    loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                                } else {
                                    isRujukan = false;
                                    $(".informasi_rujukan").hide();
                                    $("#btnProsesSEP").hide();
                                }
                            } else {
                                isRujukan = false
                                $(".informasi_rujukan").hide();
                                $("#panel-rujukan").hide();
                                $("#btnProsesSEP").hide();
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });


                    for(var dKey in icd10_kerja)
                    {
                        $("#txt_bpjs_internal_icdk").append("<li>" + icd10_kerja[dKey].nama + "</li>");
                    }

                    for(var dKey in icd10_banding)
                    {
                        $("#txt_bpjs_internal_icdb").append("<li>" + icd10_banding[dKey].nama + "</li>");
                    }

                    SEPButton.html("Daftar SEP").removeClass("btn-warning").addClass("btn-info");
                },
                error: function(response) {
                    console.log(response);
                }
            });

            $("#modal-sep-new").modal("show");
        });

        $("#btnProsesSEP").click(function () {
            Swal.fire({
                title: 'Data sudah benar?',
                showDenyButton: true,
                confirmButtonText: `Sudah`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    var tanggal_rujukan = new Date($("#txt_bpjs_tanggal_rujukan").datepicker("getDate"));
                    var parse_tanggal_rujukan =  tanggal_rujukan.getFullYear() + "-" + str_pad(2, tanggal_rujukan.getMonth()+1) + "-" + str_pad(2, tanggal_rujukan.getDate());


                    var tanggal_laka = new Date($("#txt_bpjs_laka_tanggal").datepicker("getDate"));
                    var parse_tanggal_laka =  tanggal_laka.getFullYear() + "-" + str_pad(2, tanggal_laka.getMonth()+1) + "-" + str_pad(2, tanggal_laka.getDate());

                    if(isRujukan)
                    {
                        dataSetSEP = {
                            request: "sep_baru",
                            no_kartu: $("#txt_bpjs_nomor").val(),
                            ppk_pelayanan: $("#txt_bpjs_faskes").val(),
                            kelas_rawat: $("#txt_bpjs_kelas_rawat").val(),
                            no_mr: $("#txt_bpjs_rm").val().replace(new RegExp(/-/g),""),
                            asal_rujukan: $("#txt_bpjs_jenis_asal_rujukan").val(),
                            ppk_rujukan: $("#txt_bpjs_asal_rujukan").val(),
                            tgl_rujukan: parse_tanggal_rujukan,
                            no_rujukan: $("#txt_bpjs_nomor_rujukan").val(),
                            catatan: $("#txt_bpjs_catatan").val(),
                            diagnosa_awal: $("#txt_bpjs_diagnosa_awal").val(),
                            poli: $("#txt_bpjs_poli_tujuan").val(),
                            eksekutif: $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val(),
                            cob: $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val(),
                            katarak: $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val(),

                            laka_lantas: $("input[type=\"radio\"][name=\"txt_bpjs_laka\"]:checked").val(),
                            laka_lantas_penjamin: selectedLakaPenjamin.join(","),
                            laka_lantas_tanggal_kejadian: parse_tanggal_laka,
                            laka_lantas_keterangan: $("#txt_bpjs_laka_keterangan").val(),
                            laka_lantas_suplesi: $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                            laka_lantas_suplesi_nomor: $("#txt_bpjs_laka_suplesi_nomor").val(),
                            laka_lantas_suplesi_provinsi: $("#txt_bpjs_laka_suplesi_provinsi").val(),
                            laka_lantas_suplesi_kabupaten: $("#txt_bpjs_laka_suplesi_kabupaten").val(),
                            laka_lantas_suplesi_kecamatan: $("#txt_bpjs_laka_suplesi_kecamatan").val(),

                            skdp: $("#txt_bpjs_skdp").val(),
                            dpjp: $("#txt_bpjs_dpjp").val(),
                            telepon: $("#txt_bpjs_telepon").val()
                        };
                    } else {
                        dataSetSEP = {
                            request: "sep_baru",
                            no_kartu: $("#txt_bpjs_nomor").val(),
                            ppk_pelayanan: $("#txt_bpjs_faskes").val(),
                            kelas_rawat: $("#txt_bpjs_kelas_rawat").val(),
                            no_mr: $("#txt_bpjs_rm").val().replace(new RegExp(/-/g),""),
                            asal_rujukan: $("#txt_bpjs_jenis_asal_rujukan").val(),
                            ppk_rujukan: $("#txt_bpjs_asal_rujukan").val(),
                            tgl_rujukan: parse_tanggal_rujukan,
                            no_rujukan: "",
                            catatan: $("#txt_bpjs_catatan").val(),
                            diagnosa_awal: $("#txt_bpjs_diagnosa_awal").val(),
                            poli: $("#txt_bpjs_poli_tujuan").val(),
                            eksekutif: $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val(),
                            cob: $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val(),
                            katarak: $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val(),

                            laka_lantas: $("input[type=\"radio\"][name=\"txt_bpjs_laka\"]:checked").val(),
                            laka_lantas_penjamin: selectedLakaPenjamin.join(","),
                            laka_lantas_tanggal_kejadian: parse_tanggal_laka,
                            laka_lantas_keterangan: $("#txt_bpjs_laka_keterangan").val(),
                            laka_lantas_suplesi: $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                            laka_lantas_suplesi_nomor: $("#txt_bpjs_laka_suplesi_nomor").val(),
                            laka_lantas_suplesi_provinsi: $("#txt_bpjs_laka_suplesi_provinsi").val(),
                            laka_lantas_suplesi_kabupaten: $("#txt_bpjs_laka_suplesi_kabupaten").val(),
                            laka_lantas_suplesi_kecamatan: $("#txt_bpjs_laka_suplesi_kecamatan").val(),

                            skdp: $("#txt_bpjs_skdp").val(),
                            dpjp: $("#txt_bpjs_dpjp").val(),
                            telepon: $("#txt_bpjs_telepon").val()
                        };
                    }

                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/BPJS",
                        type: "POST",
                        data: dataSetSEP,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response.response_package.content.metaData.code === "201") {
                                Swal.fire(
                                    "Gagal buat SEP",
                                    response.response_package.content.metaData.message,
                                    "warning"
                                ).then((result) => {
                                });
                            } else {
                                Swal.fire(
                                    "Pembuatan SEP Berhasil!",
                                    "SEP telah dibuat",
                                    "success"
                                ).then((result) => {
                                    console.clear();
                                    console.log(response);
                                    $("#modal-sep-new").modal("hide");
                                });
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {
                }
            });
        });

        $("#txt_bpjs_laka_suplesi_provinsi").on("change", function () {
            loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val());
        });

        $("#txt_bpjs_laka_suplesi_kabupaten").on("change", function () {
            loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val());
        });


		/*================== FORM CARI AREA ====================*/

		$('#table-list-pencarian').DataTable({
			"bFilter": false,
			"bInfo" : false
		});

		$("#txt_cari").on('keyup', function(){
			params = $("#txt_cari").val();

			$("#table-list-pencarian tbody").html("");
			$("#pencarian-notif").attr("hidden",true);
			$("#loader-search").removeAttr("hidden");
			if (params != ""){
				setTimeout(function(){
					$.ajax({
						async: false,
						url:__HOSTAPI__ + "/Antrian/cari-pasien/" + params,
						type: "GET",
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						success: function(response){
							var MetaData = dataTindakan = response.response_package.response_data;

							var html = "";
							if (MetaData != ""){
								$.each(MetaData, function(key, item){
									var nik = item.nik;
									if (nik == null){
										nik = '-';
									}

									var buttonAksi = "<td style='text-align:center;'><button id=\"btn_daftar_pasient_" + item.uid + "\" class=\"btn btn-sm btn-info btnDaftarPasien\" data-toggle=\"tooltip\" title=\"Tambah ke Antrian\"><i class=\"fa fa-user-plus\"></i></button></td>";

									if (item.berobat == true){
										buttonAksi = "<td clsas=\"wrap_content\" style=\"text-align:center;\"><span class=\"badge badge-warning\">Sedang Berobat</span></td>";
									}

									html += "<tr disabled>" +
												"<td class=\"wrap_content\">"+ item.autonum  +"</td>" +
												"<td>"+ item.no_rm +"</td>" +
												"<td>"+ item.nama +"</td>" +
												"<td>"+ nik +"</td>" +
												"<td class=\"wrap_content\">"+ item.jenkel +"</td>" +
												buttonAksi +
											"</tr>";
								});
							} else {
								html += "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
							}
							
							$("#table-list-pencarian tbody").html(html);
							$("#loader-search").attr("hidden",true);
						},
						error: function(response) {
							console.log(response);
						}
					});
					
				}, 250);
			} else {
				$("#loader-search").attr("hidden",true);

				var html = "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
				$("#table-list-pencarian tbody").html(html);
			}
			
			$("#btnTambahPasien").fadeIn("fast");
		});

		$("#btnTambahPasien").click(function(){
			localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
		});

		$("#btnTambahAntrian").click(function(){
			var currentAntrian = $("#txt_current_antrian").attr("current_queue");
			if(currentAntrian == undefined || currentAntrian == null) {
				alert("Tidak ada antrian");
			} else {
                currentAntrianType = "DEFAULT";
				$("#btnTambahPasien").fadeOut("false");
				$("#txt_cari").val("");
				$("#table-list-pencarian tbody").html("<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>");
				$("#modal-cari").modal("show");
			}
		});

		$("body").on("click", ".btnDaftarPasien", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			localStorage.setItem("currentPasien", uid);
            localStorage.setItem("currentAntrianType", currentAntrianType);
			localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
			location.href = __HOSTNAME__ + "/rawat_jalan/resepsionis/tambah/" + uid;
		});


        function loadKelasRawat(selected = ""){
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
                        if(data[a].nama.toUpperCase() === selected.toUpperCase()) {
                            $(selection).attr("selected", "selected");
                        }
                        $("#txt_bpjs_kelas_rawat").append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadProvinsi(target) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_provinsi",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadKabupaten(target, provinsi) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_kabupaten/" + provinsi,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadKecamatan(target, kabupaten) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_kecamatan/" + kabupaten,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadSpesialistik(target) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_spesialistik",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadDPJP(target, jenis, spesialistik) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_dpjp/" + jenis + "/" + spesialistik,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].kode + " - " + data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadInformasiRujukan(data) {
            $("#txt_bpjs_rujuk_perujuk").html(data.provPerujuk.kode + " - " + data.provPerujuk.nama);
            $("#txt_bpjs_rujuk_tanggal").html(data.tglKunjungan);
            $("#txt_bpjs_rujuk_poli").html(data.poliRujukan.kode + " - " + data.poliRujukan.nama);
            $("#txt_bpjs_rujuk_diagnosa").html(data.diagnosa.kode + " - " + data.diagnosa.nama);
            $("#txt_bpjs_rujuk_keluhan").html((data.keluhan === "") ? "-" : data.keluhan);
            $("#txt_bpjs_rujuk_hak_kelas").html(data.peserta.hakKelas.kode + " - " + data.peserta.hakKelas.keterangan);
            $("#txt_bpjs_rujuk_jenis_peserta").html(data.peserta.jenisPeserta.kode + " - " + data.peserta.jenisPeserta.keterangan);


            var queryDate = data.tglKunjungan,
                dateParts = queryDate.match(/(\d+)/g)
            realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

            $("#txt_bpjs_tanggal_rujukan").datepicker("setDate", realDate);
            $("#txt_bpjs_jenis_asal_rujukan").val(data.provPerujuk.jenis).trigger("change");
            $("#txt_bpjs_asal_rujukan").html("<option value=\"" + data.provPerujuk.info.kode + "\">" + data.provPerujuk.info.kode + " - " + data.provPerujuk.info.nama + "</option>")
            /*$("#txt_bpjs_asal_rujukan").select2("data", {
                "id": data.provPerujuk.info.kode,
                "text": data.provPerujuk.info.kode + " - " + data.provPerujuk.info.nama
            }, true);*/
            //$("#txt_bpjs_kelas_rawat").val(data.peserta.hakKelas.kode).trigger("change");
            /*$("#txt_bpjs_kelas_rawat").select2("data", {
                "id": data.peserta.hakKelas.kode,
                "text": data.peserta.hakKelas.nama
            });*/
        }



		//SOCKET
		Sync.onmessage = function(evt) {
			var signalData = JSON.parse(evt.data);
			var command = signalData.protocols;
			var type = signalData.type;
			var sender = signalData.sender;
			var receiver = signalData.receiver;
			var time = signalData.time;
			var parameter = signalData.parameter;

			if(command !== undefined && command !== null && command !== "") {
				protocolLib[command](command, type, parameter, sender, receiver, time);
			}
		}

		var protocolLib = {
			userlist: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			userlogin: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			anjungan_kunjungan_baru: function(protocols, type, parameter, sender, receiver, time) {
				refresh_notification();
				reinitAntrianSync($("#txt_loket").val());
			},
			anjungan_kunjungan_panggil: function(protocols, type, parameter, sender, receiver, time) {
				//
			}
		};

		//INIT
		function reinitAntrianSync(argument) {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan/check_job/" + argument,
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var dataCheck = response.response_package;
					$("#sisa_antrian").html(dataCheck.response_standby);
					if(dataCheck.response_data.length > 0) {
						//Belum terproses
						$("#btnGunakanLoket").attr("disabled", "disabled");
						$("#btnSelesaiGunakan").removeAttr("disabled");
						$("#txt_loket")
							.append("<option value=\"" + dataCheck.response_data[0].loket.uid + "\">" + dataCheck.response_data[0].loket.nama_loket + "</option>")
							.attr("disabled", "disabled");
						$("#txt_loket").select2();
						$("#txt_current_antrian").html(dataCheck.response_queue).attr({
							"current_queue": dataCheck.response_queue_id
						});
					} else {
						if(dataCheck.response_used != undefined && dataCheck.response_used != "") {
							load_loket("#txt_loket", dataCheck.response_used);
							$("#txt_loket").attr("disabled", "disabled");
							$("#btnSelesaiGunakan").removeAttr("disabled", "disabled");
							$("#btnGunakanLoket").attr("disabled", "disabled");
							reloadPanggilan($("#txt_loket").val(), dataCheck.response_queue_id);
							//Otomatis Panggil
							//reloadPanggilan($("#txt_loket").val());
						} else {
							load_loket("#txt_loket");
							$("#btnNext").attr("disabled", "disabled");
							$("#btnTambahAntrian").attr("disabled", "disabled");
						}
						$("#txt_loket").select2();
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		reinitAntrianSync($("#txt_loket").val());

		function load_loket(target, selected = "") {
			//
			var loketData;
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan/avail_loket",
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					loketData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < loketData.length; a++) {
						$(target).append("<option " + (loketData[a].uid == selected ? "selected=\"selected\"" : "") + " value=\"" + loketData[a].uid + "\">" + loketData[a].nama_loket + "</option>")
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return loketData;
		}

		function reloadPanggilan(loket, currentQueue = "") {
			if(currentQueue != "") {
				dataForm = {
					request:"next_antrian",
					loket:loket,
					currentQueue: currentQueue
				}
			} else {
				dataForm = {
					request:"next_antrian",
					loket:loket
				}
			}
			var currentQueue;
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan",
				type: "POST",
				data:dataForm,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					if(response.response_package !== undefined && response.response_package !== null) {
						currentQueue = response.response_package;
						$("#sisa_antrian").html(currentQueue.response_standby);
						if((currentQueue.response_queue == "" || currentQueue.response_queue == undefined || currentQueue.response_queue == null || currentQueue.response_queue == 0)) {
							//reloadPanggilan(loket, "");
						} else {
							
							$("#txt_current_antrian").html((currentQueue.response_queue == "" || currentQueue.response_queue == undefined || currentQueue.response_queue == null) ? "0" : currentQueue.response_queue).attr({
								"current_queue": currentQueue.response_queue_id
							});
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return currentQueue;
		}

		$("#btnGunakanLoket").click(function() {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan",
				type: "POST",
				data:{
					request:"ambil_antrian",
					loket:$("#txt_loket").val()
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					if(response.response_package.response_result > 0) {
						notification ("success", "Loket berhasil digunakan", 3000, "hasil_loket");
						$("#btnGunakanLoket").attr("disabled", "disabled");
						reloadPanggilan($("#txt_loket").val());
						$("#txt_loket").attr("disabled", "disabled");
						$("#btnSelesaiGunakan").removeAttr("disabled");	
						$("#btnNext").removeAttr("disabled", "disabled");
						$("#btnTambahAntrian").removeAttr("disabled");
					} else {
						if(response.response_package.response_loket_user == __ME__) {
							notification ("success", "Loket berhasil digunakan", 3000, "hasil_loket");
							$("#btnGunakanLoket").attr("disabled", "disabled");
							reloadPanggilan($("#txt_loket").val());
							$("#txt_loket").attr("disabled", "disabled");
							$("#btnSelesaiGunakan").removeAttr("disabled");	
							$("#btnNext").removeAttr("disabled", "disabled");
							$("#btnTambahAntrian").removeAttr("disabled");
						} else {
							notification ("info", "Loket sudah digunakan " + response.response_package.response_loket, 3000, "hasil_loket");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnSelesaiGunakan").click(function() {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan",
				type: "POST",
				data:{
					request:"selesai_antrian"
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					/*if(response.response_package.response_result > 0) {
						load_loket("#txt_loket");
						notification ("success", "Berhasil keluar dari loket", 3000, "hasil_loket");
						$("#txt_current_antrian").html("0");
						$("#btnGunakanLoket").removeAttr("disabled");
						$("#txt_loket").removeAttr("disabled");
						$("#btnSelesaiGunakan").attr("disabled", "disabled");
						$("#btnNext").attr("disabled", "disabled");
						$("#btnTambahAntrian").attr("disabled", "disabled");
					} else {
						notification ("warning", "Anda telah keluar loket", 3000, "hasil_loket");
					}*/
					load_loket("#txt_loket");
					notification ("success", "Berhasil keluar dari loket", 3000, "hasil_loket");
					$("#txt_current_antrian").html("0");
					$("#btnGunakanLoket").removeAttr("disabled");
					$("#txt_loket").removeAttr("disabled");
					$("#btnSelesaiGunakan").attr("disabled", "disabled");
					$("#btnNext").attr("disabled", "disabled");
					$("#btnTambahAntrian").attr("disabled", "disabled");
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnNext").click(function() {
			reloadPanggilan($("#txt_loket").val(), $("#txt_current_antrian").attr("current_queue"));
		});

		$("#btnPanggil").click(function() {
			push_socket($("#txt_loket").val(), "anjungan_kunjungan_panggil", "display_machine", {
				loket: $("#txt_loket").val(),
				nomor: $("#txt_current_antrian").html()
			}, "info");
		});


        $(".print_manager").click(function() {
            var targetSurat = $(this).attr("jenis");
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var pasien = $(this).attr("pasien");

            //$("#target-judul-cetak").html("CETAK " + targetSurat.toUpperCase() + " PASIEN");
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Pasien/pasien-detail/" + pasien,
                type: "GET",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function (response) {
                    dataPasien = response.response_package.response_data[0];
                    dataPasien.pc_customer = __PC_CUSTOMER__;
                    dataPasien.pc_dokter = $("#dokter_" + uid).html();
                    dataPasien.waktu_masuk = $("#waktu_masuk_" + uid).html();
                    console.log(dataPasien);

                    $.ajax({
                        async: false,
                        url: __HOST__ + "miscellaneous/print_template/pasien_" + targetSurat + ".php",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: dataPasien,
                        success: function (response) {
                            //$("#dokumen-viewer").html(response);
                            var containerItem = document.createElement("DIV");
                            $(containerItem).html(response);
                            $(containerItem).printThis({
                                importCSS: true,
                                base: false,
                                pageTitle: "cetak",
                                afterPrint: function() {
                                    //
                                }
                            });
                        }
                    });
                },
                error: function(response) {
                    //
                }
            });
        });
	});

</script>

<script src="<?= __HOSTNAME__ ?>/template/assets/vendor/toastr.min.js"></script>
<script src="<?= __HOSTNAME__ ?>/template/assets/js/toastr.js"></script>

<div id="modal-print" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Print Option
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div id="modal-cari" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Antrian</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-6">
					<div class="col-md-6">
						<div class="row">
							<label for="txt_cari">Cari Pasien</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="search-form search-form--light input-group-lg col-md-10">
								<input type="text" class="form-control" placeholder="Nama / NIK / No. RM" id="txt_cari">
							</div>
							<div class="col-md-12" hidden id="pencarian-notif" style="color: red; font-size: 0.8rem;">
								Mohon ketikkan kata kunci pencarian
							</div>
							<div class="col-md-2">
								<div class="loader loader-lg loader-primary" id="loader-search" hidden></div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group col-md-12" >
					<!-- style="height: 100px; overflow: scroll;" -->
					<table class="table table-bordered table-striped" id="table-list-pencarian">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>No. RM</th>
								<th>Nama</th>
								<th>NIK</th>
								<th class="wrap_content">Jenis Kelamin</th>
								<th class="wrap_content">Aksi</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
				
			</div>
			<div class="modal-footer">
				<!-- <div id="spanBtnTambahPasien" hidden> -->
				<a href="<?= __HOSTNAME__ ?>/pasien/tambah?antrian=true" class="btn btn-success" id="btnTambahPasien">
				<!-- <i class="fa fa-plus"></i>  -->Tambah Pasien Baru
				</a>
				<!-- </div> -->
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div> 
	</div> 
</div>


<div id="modal-sep-new" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-5 mb-5 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">Kontak</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_telepon" readonly>
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
                                    <div class="col-6">
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Nomor Medical Rahecord (MR)</label>
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
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD PETALA BUMI - KOTA PEKAN BARU</option>
                                            </select>
                                        </div>


                                        <div class="col-12 col-md-8 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="2">Rawat Jalan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                            <label for="">Kelas Rawat</label>
                                            <select class="form-control" id="txt_bpjs_kelas_rawat"></select>
                                        </div>
                                    </div>

                                    <div class="col-6" id="panel-rujukan">
                                        <div class="col-12 col-md-6 mb-4 form-group" id="group_nomor_rujukan">
                                            <label for="">Nomor Rujukan</label>
                                            <select data-width="100%" class="form-control uppercase" id="txt_bpjs_nomor_rujukan"></select>
                                            <!--<input type="text" class="form-control uppercase" id="txt_bpjs_nomor_rujukan" />-->
                                        </div>
                                        <div class="col-12 col-md-4 mb-4 form-group">
                                            <label for="">Jenis Asal Rujukan</label>
                                            <select class="form-control uppercase sep" id="txt_bpjs_jenis_asal_rujukan">
                                                <option value="1">Puskesmas</option>
                                                <option value="2">Rumah Sakit</option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Asal Rujukan</label>
                                            <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                        </div>
                                        <div class="col-12 col-md-5 mb-4 form-group">
                                            <label for="">Tanggal Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan">
                                        </div>

                                        <div class="informasi_rujukan">
                                            <table class="table form-mode">
                                                <tr>
                                                    <td>Perujuk</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_perujuk"></td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Kunjungan</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_tanggal"></td>
                                                </tr>
                                                <tr>
                                                    <td>Poli</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_poli"></td>
                                                </tr>
                                                <tr>
                                                    <td>Diagnosa</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_diagnosa"></td>
                                                </tr>
                                                <tr>
                                                    <td>Keluhan</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_keluhan"></td>
                                                </tr>
                                                <tr>
                                                    <td>Hak Kelas</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_hak_kelas"></td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Peserta</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_jenis_peserta"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Perobatan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Tujuan</label>
                                                <select class="form-control" id="txt_bpjs_poli_tujuan"></select>
                                            </div>
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Eksekutif</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                <label for="">Diagnosa Awal</label>
                                                <select class="form-control sep" id="txt_bpjs_diagnosa_awal"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Catatan</label>
                                                <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_catatan" style="min-height: 200px"></textarea>
                                            </div>
                                            <div class="col-12 col-md-6 mb-4 form-group">
                                                <label for="">Nomor SKDP</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_skdp" />
                                            </div>
                                            <div class="col-12 col-md-8 mb-8 form-group" id="group_spesialistik">
                                                <label for="">Spesialistik DPJP</label>
                                                <select class="form-control" id="txt_bpjs_dpjp_spesialistik"></select>
                                            </div>
                                            <div class="col-12 col-md-9 mb-9 form-group" id="group_dpjp">
                                                <label for="">Kode DPJP</label>
                                                <select class="form-control sep" id="txt_bpjs_dpjp"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">COB</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Katarak</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="alert alert-info">
                                                <div class="col-12 col-md-8 mb-4 form-group">
                                                    <b for="">Poli Tujuan</b>
                                                    <blockquote style="padding-left: 25px;">
                                                        <h6 id="txt_bpjs_internal_poli"></h6>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Kerja</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdk"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_dk"></p>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Banding</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdb"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_db"></p>
                                                    </blockquote>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Jaminan Laka Lantas</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_laka" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_laka" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="laka_lantas_container">
                                                <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                    <label for="">Penjamin Laka Lantas</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="1" />
                                                                <label class="form-check-label">
                                                                    Jasa Raharja
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="2" />
                                                                <label class="form-check-label">
                                                                    BPJS Ketenagakerjaan
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="3" />
                                                                <label class="form-check-label">
                                                                    TASPEN PT
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="4" />
                                                                <label class="form-check-label">
                                                                    ASABRI PT
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 mb-4 form-group">
                                                    <label for="">Tanggal Kejadian</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_tanggal">
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <label for="">Keterangan</label>
                                                    <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_laka_keterangan" style="min-height: 200px"></textarea>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <label for="">Suplesi</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="0" checked/>
                                                                <label class="form-check-label">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="1" />
                                                                <label class="form-check-label">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="laka_lantas_suplesi_container">
                                                    <div class="col-12 col-md-6 mb-4 form-group">
                                                        <label for="">Nomor SEP Suplesi</label>
                                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_suplesi_nomor" />
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_provinsi">
                                                        <label for="">Provinsi Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_provinsi"></select>
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_kabupaten">
                                                        <label for="">Kabupaten Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kabupaten"></select>
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_kecamatan">
                                                        <label for="">Kecamatan Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kecamatan"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="modal-tambah-igd" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Tambah Pasien IGD
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p><strong class="headings-color">Informasi Pasien</strong></p>
                            <p class="text-muted">Mohon pastikan informasi pasien cocok</p>
                        </div>
                        <div class="col-lg-8 card-form__body card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Nama Pasien</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nama" disabled required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Jenis Kelamin</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nama_jenkel" disabled required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Nomor Rekam Medis</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="no_rm" disabled required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tanggal_lahir" disabled required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p><strong class="headings-color">Detail Kunjungan</strong></p>
                            <p class="text-muted">Mohon masukkan data dengan benar<br>* Wajib diisi</p>
                        </div>
                        <div class="col-lg-8 card-form__body card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="penjamin" class="form-control select2 inputan" required>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Prioritas <span class="red">*</span></label>
                                    <select id="prioritas" class="form-control select2 inputan" required>
                                        <option value="" disabled selected>Pilih Prioritas</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="dokter" class="form-control select2 inputan" required>
                                        <option value="" disabled selected>Pilih Dokter</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Penanggung Jawab Pasien <span class="red">*</span></label>
                                    <input type="" name="pj_pasien" id="pj_pasien" maxlength="100" class="form-control inputan" required value="">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Informasi didapat dari <span class="red">*</span></label>
                                    <input type="" name="info_didapat_dari" id="info_didapat_dari" maxlength="100" class="form-control inputan" required value="">
                                </div>
                                <div class="col-lg-8 card-form__body card-body">
                                    <div class="form-row">
                                        <button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
                                        &nbsp;
                                        <a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/resepsionis" class="btn btn-danger">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Tambah
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>