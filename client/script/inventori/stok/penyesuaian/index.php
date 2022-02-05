<script type="text/javascript">
	
	$(function(){
		var metaDataOpname = {};
		var tableDetailOpname;
        if(__UNIT__.gudang !== __GUDANG_UTAMA__) {
            $("#btnProsesStrategi").remove();
        }
		function load_gudang(target,selected = "") {
			var gudangData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target + " option").remove();
					gudangData = response.response_package.response_data;
					for(var a in gudangData) {
					    var newOption = document.createElement("OPTION");
						$(newOption).html(gudangData[a].nama).attr({
							"value":gudangData[a].uid,
                            "status": gudangData[a].status
						});
						if(gudangData[a].uid == selected) {
							$(newOption).attr({
								"selected" : "selected"
							});
						}
						$(target).append(newOption);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
		}

		function load_product_resep(target, selectedData = "", appendData = true) {
			var selected = [];
			var productData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target).find("option").remove();
					$(target).append("<option value=\"none\">Pilih Obat</option>");
					productData = response.response_package.response_data;
					for (var a = 0; a < productData.length; a++) {
						var penjaminList = [];
						var penjaminListData = productData[a].penjamin;
						for(var penjaminKey in penjaminListData) {
							// if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
							// 	penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
							// }
						}

						if(selected.indexOf(productData[a].uid) < 0 && appendData) {
							$(target).append("<option penjamin-list=\"" + penjaminList.join(",") + "\" satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			//return (productData.length == selected.length);
			return {
				allow: (productData.length == selected.length),
				data: productData
			};
		}

		load_product_resep("#txt_obat");
		load_gudang("#txt_gudang", __UNIT__.gudang);

		load_product_resep("#txt_obat_tambah");
		load_gudang("#txt_gudang_tambah");

        //var currentStatus = $("#txt_gudang option:selected").attr("status");
        var currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
		reCheckStatus(currentStatus);
		function reCheckStatus(currentStatus) {
            if(currentStatus === "A") {
                $("#allow_transact_opname button").removeAttr("disabled");
                $("#tambahAktifkanGudang").hide();
                $("#opname_ready_status").show();
                $("#opname_running_status").hide();
            } else {
                $("#warning_allow_transact_opname").append(" Harap <a href=\"" + __HOSTNAME__ + "/inventori/stok/penyesuaian\"><i class=\"fa fa-link\"></i> selesaikan opname</a> dahulu agar dapat melanjutkan proses transaksi");
                $("#allow_transact_opname button").attr({
                    "disabled": "disabled"
                });
                $("#tambahAktifkanGudang").show();
                $("#opname_ready_status").hide();
                $("#opname_running_status").show();
            }
        }

        protocolLib = {
            opname_warehouse: function(protocols, type, parameter, sender, receiver, time) {
                if(sender !== __ME__) {
                    notification (type, parameter, 3000, "opname_notifier");
                }
                currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
                reCheckStatus(currentStatus);
            },
            opname_warehouse_finish: function(protocols, type, parameter, sender, receiver, time) {
                if(sender !== __ME__) {
                    notification (type, parameter, 3000, "opname_notifier");
                }
                currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
                reCheckStatus(currentStatus);
            },
        };

        $("#prosesStrategi").click(function() {
            $("#form-rekap-post-opname").modal("show");
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/post_opname_strategy_load",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    $("#strategi-amprah tbody").html("");
                    var amprah = response.response_package.amprah;
                    var autoAmprah = 1;
                    for(var a in amprah) {
                        for(var aa in amprah[a].detail) {
                            if(aa < 1) {
                                $("#strategi-amprah tbody").append("<tr>" +
                                    "<td rowspan=\"" + amprah[a].detail.length + "\">" + autoAmprah + "</td>" +
                                    "<td rowspan=\"" + amprah[a].detail.length + "\">" + amprah[a].info.nama + "</td>" +
                                    "<td>" + amprah[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            } else {
                                $("#strategi-amprah").append("<tr>" +
                                    "<td>" + amprah[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            }
                        }
                        autoAmprah++;
                    }


                    var potong = response.response_package.potong;
                    var autoPotong = 1;
                    for(var a in potong) {
                        for(var aa in potong[a].detail) {
                            if(aa < 1) {
                                $("#strategi-potong tbody").append("<tr>" +
                                    "<td rowspan=\"" + potong[a].detail.length + "\">" + autoPotong + "</td>" +
                                    "<td rowspan=\"" + potong[a].detail.length + "\">" + potong[a].info.nama + "</td>" +
                                    "<td>" + potong[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            } else {
                                $("#strategi-potong").append("<tr>" +
                                    "<td>" + potong[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            }
                        }
                        autoPotong++;
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        function calculate_post_opname() {
            $("#form-rekap-post-opname").modal("show");
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/post_opname_strategy_load",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    $("#strategi-amprah tbody").html("");
                    var amprah = response.response_package.amprah;
                    var autoAmprah = 1;
                    for(var a in amprah) {
                        for(var aa in amprah[a].detail) {
                            if(aa < 1) {
                                $("#strategi-amprah tbody").append("<tr>" +
                                    "<td rowspan=\"" + amprah[a].detail.length + "\" class=\"autonum\">" + autoAmprah + "</td>" +
                                    "<td rowspan=\"" + amprah[a].detail.length + "\">" + amprah[a].info.nama + "</td>" +
                                    "<td>" + amprah[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            } else {
                                $("#strategi-amprah").append("<tr>" +
                                    "<td>" + amprah[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(amprah[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            }
                        }
                        autoAmprah++;
                    }

                    if($("#strategi-amprah tbody tr").length === 0) {
                        $("#strategi-amprah tbody").append("<tr><td colspan=\"5\" class=\"text-center\">Tidak ada transaksi</td></tr>");
                    }


                    $("#strategi-potong tbody").html("");
                    var potong = response.response_package.potong;
                    var autoPotong = 1;
                    for(var a in potong) {
                        for(var aa in potong[a].detail) {
                            if(aa < 1) {
                                $("#strategi-potong tbody").append("<tr>" +
                                    "<td rowspan=\"" + potong[a].detail.length + "\" class=\"autonum\">" + autoPotong + "</td>" +
                                    "<td rowspan=\"" + potong[a].detail.length + "\">" + potong[a].info.nama + "</td>" +
                                    "<td>" + potong[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            } else {
                                $("#strategi-potong").append("<tr>" +
                                    "<td>" + potong[a].detail[aa].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].detail[aa].qty, 2, ".", ",") + "</td>" +
                                    "<td class=\"number_style\">" + number_format(potong[a].total, 2, ".", ",") + "</td>" +
                                    "</tr>");
                            }
                        }
                        autoPotong++;
                    }

                    if($("#strategi-potong tbody tr").length === 0) {
                        $("#strategi-potong tbody").append("<tr><td colspan=\"5\" class=\"text-center\">Tidak ada transaksi</td></tr>");
                    }

                    load_gudang("#txt_gudang", __UNIT__.gudang);
                    currentStatus = $("#txt_gudang option:selected").attr("status");
                    reCheckStatus(currentStatus);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#tambahAktifkanGudang").click(function () {
            Swal.fire({
                title: "Selesai Penyesuaian?",
                html: "Prosedur ini akan mengaktifkan semua jalur barang masuk dan barang keluar dari dan ke gudang ini. Jika Anda ada di gudang utama maka semua gudang akan dibuka.",
                showDenyButton: true,
                type: "warning",
                confirmButtonText: "Ya",
                confirmButtonColor: "#1297fb",
                denyButtonText: "Tidak",
                denyButtonColor: "#ff2a2a"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Inventori",
                        async: false,
                        data: {
                            request: "post_opname_warehouse",
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        success:function(response) {
                            if(response.response_package.response_result > 0) {

                                calculate_post_opname();
                                tableHistoryOpname.ajax.reload();
                                tempTransact.ajax.reload();
                            } else {
                                if(response.response_package.response_result === -1) {
                                    calculate_post_opname();
                                } else {
                                    var gudangProgressPendingList = response.response_package.gudang_progress;
                                    var getListGudangPending = [];
                                    for(var aPGud in gudangProgressPendingList) {
                                        getListGudangPending.push("<span class=\"badge badge-custom-caption badge-outline-info\">" + gudangProgressPendingList[aPGud].gudang.nama + "</span>");
                                    }

                                    Swal.fire(
                                        'Penyesuaian Stok',
                                        response.response_package.response_message + "<br /><span>" + getListGudangPending.join(", ") + "</span>",
                                        'warning'
                                    ).then((result) => {
                                        //
                                    });
                                }
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

		$('#txt_periode_awal').datepicker("setDate", $.datepicker.parseDate( "yy-mm-dd", $('#txt_periode_awal').attr("setTanggal")));
		$('#txt_periode_akhir').datepicker("setDate", $.datepicker.parseDate( "yy-mm-dd", $('#txt_periode_akhir').attr("setTanggal")));

		$("#txt_obat").select2();
		$("#txt_obat_tambah").select2();
		$("#txt_gudang").select2();
		$("#txt_gudang_tambah").select2();
		$("#txt_qty_tambah").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		

		var tableHistoryOpname = $("#table-stok-opname").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_opname_history";
					d.gudang = __UNIT__.gudang;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
            "rowCallback": function ( row, data, index ) {
                if(data.status === "D") {
                    $("td", row).addClass("bg-success-custom");
                } else if(data.status === "A") {
                    $("td", row).addClass("bg-purple-custom");
                }
            },
			language: {
				search: "",
				searchPlaceholder: "Cari Barang"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.dari;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.sampai;
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.gudang_detail.nama;
                    }
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.created_at;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-sm btn-info detail_opname\" id=\"opname_" + row.uid + "\"><i class=\"fa fa-eye\"></i> Lihat</button>" +
								"</div>";
					}
				},
			]
		});

		$("#txt_gudang").change(function() {
			tableHistoryOpname.ajax.reload();
		});

		$("#tambahStokAwal").click(function() {
		    if(currentStatus === "A") {
                Swal.fire({
                    title: "Mulai Penyesuaian Stok?",
                    html: "Prosedur ini akan menghentikan semua jalur barang masuk dan barang keluar dari dan ke gudang ini.",
                    showDenyButton: true,
                    type: "warning",
                    confirmButtonText: "Ya",
                    confirmButtonColor: "#1297fb",
                    denyButtonText: "Tidak",
                    denyButtonColor: "#ff2a2a"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:__HOSTAPI__ + "/Inventori",
                            async: false,
                            data: {
                                request: "opname_warehouse",
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"POST",
                            success:function(response) {
                                if(response.response_package.response_result > 0) {
                                    push_socket(__ME__, "opname_warehouse", "*", "" + __UNIT__.nama + " mengadakan stok opname. Transaksi gudang dihentikan sementara. Harap selesaikan semua transaksi yang sedang berjalan", "info").then(function () {
                                        load_gudang("#txt_gudang", __UNIT__.gudang);
                                        currentStatus = $("#txt_gudang option:selected").attr("status");
                                        reCheckStatus(currentStatus);
                                        if(response.response_package.temp_stok.length === 0) {
                                            $("#form-tambah").modal("show");
                                            tableCurrentStock.ajax.reload();
                                        } else {
                                            Swal.fire(
                                                'Penyesuaian Stok',
                                                'Sedang ada transaksi yang sedang berjalan. Harap informasikan semua unit untuk menyelesaikan transaksi kemudian menghentikan transaksi baru',
                                                'warning'
                                            ).then((result) => {
                                                //
                                            });
                                        }
                                    });
                                } else {
                                    Swal.fire(
                                        'Penyesuaian Stok',
                                        'Sedang ada transaksi yang sedang berjalan. Harap informasikan semua unit untuk menyelesaikan transaksi kemudian menghentikan transaksi baru',
                                        'warning'
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
            } else {
                $("#form-tambah").modal("show");
		        /*$.ajax({
                    url:__HOSTAPI__ + "/Inventori/check_temp_transact",
                    async: false,
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type:"GET",
                    success:function(response) {
                        if(response.response_package.response_data.length > 0) {
                            Swal.fire(
                                'Penyesuaian Stok',
                                'Sedang ada transaksi yang sedang berjalan. Harap informasikan semua unit untuk menyelesaikan transaksi kemudian menghentikan transaksi baru',
                                'warning'
                            ).then((result) => {
                                //
                            });
                        } else {

                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });*/
            }
		});

		$("body").on("click", ".detail_opname", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/get_opname_detail/" + uid,
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data[0];
                    if(data !== undefined) {
                        $("#txt_periode_awal_detail").html(data.dari);
                        $("#txt_periode_akhir_detail").html(data.sampai);
                        $("#txt_diproses_detail").html(data.pegawai.nama);
                        $("#txt_kode_detail").html(data.kode);
                        //$("#detail-opname tbody tr").remove();

                        if($("#detail-opname").hasClass("dataTable")) {
                            tableDetailOpname.ajax.reload();
                        } else {
                            tableDetailOpname = $("#detail-opname").DataTable({
                                processing: true,
                                serverSide: true,
                                sPaginationType: "full_numbers",
                                bPaginate: true,
                                lengthMenu: [[20, 50, 200], [20, 50, 200]],
                                serverMethod: "POST",
                                "ajax":{
                                    url: __HOSTAPI__ + "/Inventori",
                                    type: "POST",
                                    data: function(d){
                                        d.request = "get_opname_detail_item";
                                        d.uid = uid;
                                    },
                                    headers:{
                                        Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                    },
                                    dataSrc:function(response) {
                                        var dataSet = response.response_package.response_data;
                                        if(dataSet == undefined) {
                                            dataSet = [];
                                        }

                                        response.draw = parseInt(response.response_package.response_draw);
                                        response.recordsTotal = response.response_package.recordsTotal;
                                        response.recordsFiltered = response.response_package.recordsFiltered;
                                        return dataSet;
                                    }
                                },
                                autoWidth: false,
                                language: {
                                    search: "",
                                    searchPlaceholder: "Cari Barang"
                                },
                                "columns" : [
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                        }
                                    },
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            return row.nama_barang;
                                        }
                                    },
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            return row.batch.batch;
                                        }
                                    },
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            return "<h5 class=\"number_style text-right\">" + number_format(row.qty_awal, 2, ".", ",") + "</h5>";
                                        }
                                    },
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            return "<h5 class=\"number_style text-right\">" + number_format(row.qty_akhir, 2, ".", ",") + "</h5>";
                                        }
                                    },
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            var parsedVisual = "";
                                            var selisih = 0;
                                            if(row.qty_awal > row.qty_akhir) {
                                                selisih = parseFloat(row.qty_awal) - parseFloat(row.qty_akhir);
                                                parsedVisual = "<b class=\"text-danger\">" + selisih + " <i class=\"fa fa-arrow-down\"></i> </b>";
                                            } else if(row.qty_awal < row.qty_akhir) {
                                                selisih = parseFloat(row.qty_akhir) - parseFloat(row.qty_awal);
                                                parsedVisual = "<b class=\"text-warning\">" + selisih + " <i class=\"fa fa-arrow-up\"></i> </b>";
                                            } else {
                                                selisih = parseFloat(row.qty_akhir) - parseFloat(row.qty_awal);
                                                parsedVisual = "<b class=\"text-success\">" + selisih + " <i class=\"fa fa-check\"></i> </b>";
                                            }
                                            return "<h5 class=\"number_style text-right\" style=\"padding-right: 20px;\"><span class=\"wrap_content\">" + parsedVisual + "</span></h5>";
                                        }
                                    },
                                    {
                                        "data" : null, render: function(data, type, row, meta) {
                                            return (row.keterangan === "") ? "-" : row.keterangan;
                                        }
                                    },
                                ]
                            });
                        }
                        /*for(var b in data.detail) {
                            var parsedVisual = "";
                            var selisih = 0;
                            if(data.detail[b].qty_awal > data.detail[b].qty_akhir) {
                                selisih = parseFloat(data.detail[b].qty_awal) - parseFloat(data.detail[b].qty_akhir);
                                parsedVisual = "<b class=\"text-danger\"><i class=\"fa fa-caret-down\"></i> " + selisih + "</b>";
                            } else if(data.detail[b].qty_awal < data.detail[b].qty_akhir) {
                                selisih = parseFloat(data.detail[b].qty_akhir) - parseFloat(data.detail[b].qty_awal);
                                parsedVisual = "<b class=\"text-warning\"><i class=\"fa fa-caret-up\"></i> " + selisih + "</b>";
                            } else {
                                selisih = parseFloat(data.detail[b].qty_akhir) - parseFloat(data.detail[b].qty_awal);
                                parsedVisual = "<b class=\"text-success\"><i class=\"fa fa-check\"></i> " + selisih + "</b>";
                            }

                            $("#detail-opname tbody").append(
                                "<tr>" +
                                    "<td>" + data.detail[b].autonum + "</td>" +
                                    "<td>" + data.detail[b].item.nama + "</td>" +
                                    "<td>" + data.detail[b].batch.batch + "</td>" +
                                    "<td class=\"number_style\">" + data.detail[b].qty_awal + "</td>" +
                                    "<td class=\"number_style\">" + data.detail[b].qty_akhir + "</td>" +
                                    "<td class=\"number_style\">" + parsedVisual + "</td>" +
                                    "<td>" + data.detail[b].keterangan + "</td>" +
                                "</tr>"
                            );
                        }*/

                        $("#txt_keterangan_detail").html(data.keterangan);

                        $("#form-detail").modal("show");
                    } else {
                        console.clear();
                        console.log(response);
                    }
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnProsesStrategi").click(function() {
            Swal.fire({
                title: "Proses Transaksi Tertunda?",
                showDenyButton: true,
                type: "warning",
                confirmButtonText: "Ya",
                confirmButtonColor: "#1297fb",
                denyButtonText: "Tidak",
                denyButtonColor: "#ff2a2a"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Inventori",
                        async: false,
                        data: {
                            request: "post_opname_strategy"
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        success:function(response) {
                            push_socket(__ME__, "opname_warehouse_finish", "*", "" + __UNIT__.nama + " selesai stok opname. Transaksi gudang dapat diproses.", "success").then(function () {
                                tableHistoryOpname.ajax.reload();
                                tableCurrentStock.ajax.reload();
                                $("#form-rekap-post-opname").modal("hide");
                                currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
                                reCheckStatus(currentStatus);
                            });
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

		$("#btnSubmitStokOpname").click(function() {
            

            var allowSaveDataOpname = false;

            for(var az in metaDataOpname) {
                if(metaDataOpname[az].keterangan === null) {
                    metaDataOpname[az].keterangan = "";
                }

                if(
                    metaDataOpname[az].keterangan !== null &&
                    metaDataOpname[az].keterangan !== undefined &&
                    metaDataOpname[az].keterangan !== ""
                ) {
                    allowSaveDataOpname = true;
                } else {
                    allowSaveDataOpname = false;
                    break;
                }
            }

            if(allowSaveDataOpname === true) {
                Swal.fire({
                    title: "Data Sudah Benar?",
                    showDenyButton: true,
                    type: "warning",
                    confirmButtonText: "Ya",
                    confirmButtonColor: "#1297fb",
                    denyButtonText: "Tidak",
                    denyButtonColor: "#ff2a2a"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var rawAwal = $("#txt_periode_awal").datepicker("getDate");
                        var awal =  rawAwal.getFullYear() + "-" + str_pad(2, rawAwal.getMonth()+1) + "-" + str_pad(2, rawAwal.getDate());

                        var rawAkhir = $("#txt_periode_akhir").datepicker("getDate");
                        var akhir =  rawAkhir.getFullYear() + "-" + str_pad(2, rawAkhir.getMonth()+1) + "-" + str_pad(2, rawAkhir.getDate());

                        $.ajax({
                            url:__HOSTAPI__ + "/Inventori",
                            async: false,
                            data: {
                                request: "tambah_opname",
                                dari:awal,
                                sampai:akhir,
                                keterangan:$("#txt_keterangan").val(),
                                item:metaDataOpname
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"POST",
                            success:function(response) {
                                tableHistoryOpname.ajax.reload();
                                tableCurrentStock.ajax.reload();
                                if(response.response_package.response_result > 0) {
                                    $("#form-tambah").modal("hide");
                                } else {
                                    Swal.fire(
                                        'Penyesuaian Stok',
                                        response.response_package,
                                        'warning'
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
            } else {
                Swal.fire(
                        'Penyesuaian Stok',
                        'Semua keterangan per item wajib diisi',
                        'warning'
                    ).then((result) => {
                        //
                    });
            }
		});

		
        $("#resync_job").click(function() {
            tableCurrentStock.ajax.reload();
        });

		var tableCurrentStock = $("#current-stok").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[10, 50, -1], [10, 50, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_stok_gudang_opname";
					d.gudang = __UNIT__.gudang;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
                    var opnameItemIden = response.response_package.opname_iden;
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					for(var a in dataSet) {
                        //console.log(dataSet[a].supervisi_detail);
                        if(metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid] == undefined) {
							metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid] = {
								qty_awal: dataSet[a].stok_terkini,
                                signed: (dataSet[a].supervisi === __ME__) ? 1 : 0,
								batch: dataSet[a].batch.uid,
								nilai: (dataSet[a].old_value !== undefined && dataSet[a].old_value !== null) ? dataSet[a].old_value : 0,
								keterangan: (dataSet[a].keterangan !== undefined && dataSet[a].keterangan !== null) ? dataSet[a].keterangan : "-"
							};
						}

                        // metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid].qty_awal = dataSet[a].stok_terkini;
                        // metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid].batch = dataSet[a].batch.uid;
                        // metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid].signed = (dataSet[a].supervisi === __ME__) ? 1 : 0;
                        // metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid].nilai = (dataSet[a].old_value !== undefined && dataSet[a].old_value !== null) ? dataSet[a].old_value : 0;
                        // metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid].keterangan = (dataSet[a].keterangan !== undefined && dataSet[a].keterangan !== null) ? dataSet[a].keterangan : "-";
                        // metaDataOpname[dataSet[a].uid + "_" + dataSet[a].batch.uid].qty_akhir = opnameItemIden[dataSet[a].uid + "_" + dataSet[a].batch.uid];

					}

					$("#txt_keterangan").val(response.response_package.keterangan);

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Barang"
			},
            "rowCallback": function ( row, data, index ) {
                console.info(metaDataOpname[data.barang + "_" + data.batch.uid].signed);
                if(metaDataOpname[data.barang + "_" + data.batch.uid].signed > 0) {
                    $("td", row).addClass("bg-success-custom");
                } else {
                    //$("td", row).addClass("bg-danger-custom");
                }
            },
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<b>" + row.nama + "</b><span class=\"pull-right text-info\" style=\"font-size: 12pt;\">[" + row.batch.batch + "]</span>" + "<br /><small>ED: " + row.batch.expired_date + "</small>";
					}
				},
                {
					"data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"wrap_content\">" + ((row.supervisi_detail.nama !== undefined && row.supervisi_detail.nama !== "") ? row.supervisi_detail.nama : "-")  + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"number_style text-right\">" + number_format(row.stok_terkini, 2, ".", ",") + "</h6>";
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.satuan_terkecil.nama;
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {

						return "<input type=\"text\" class=\"form-control aktual_qty\" id=\"item_" + row.uid + "\" batch=\"" + row.batch.uid + "\" placeholder=\"0.00\" value=\"" + parseFloat((metaDataOpname[row.uid + "_" + row.batch.uid] !== null && metaDataOpname[row.uid + "_" + row.batch.uid] !== undefined) ? metaDataOpname[row.uid + "_" + row.batch.uid].nilai : 0) + "\" />";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control keterangan_item\" id=\"keterangan_" + row.uid + "\" batch=\"" + row.batch.uid + "\" placeholder=\"Keterangan per Item\" value=\"" + ((metaDataOpname[row.uid + "_" + row.batch.uid] !== null && metaDataOpname[row.uid + "_" + row.batch.uid] !== undefined) ? metaDataOpname[row.uid + "_" + row.batch.uid].keterangan : "-") + "\" />";
					}
				}
			]
		}).on("draw.dt", function () {
            $(".aktual_qty").inputmask({
				alias: 'decimal',
				rightAlign: true,
				placeholder: "0.00",
				prefix: "",
				autoGroup: false,
				digitsOptional: true
			});
		});








		/*var tableDetailOpname = $("#detail-opname").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_stok_gudang";
					d.gudang = __UNIT__.gudang;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					for(var a in dataSet) {
						if(metaDataOpname[dataSet[a].uid] == undefined) {
							metaDataOpname[dataSet[a].uid] = {
								qty_awal: dataSet[a].stok_terkini,
								batch: dataSet[a].batch.uid,
								nilai: 0,
								keterangan: ""
							};
						}
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Barang"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<b>" + row.nama + "</b><span class=\"pull-right text-info\" style=\"font-size: 14pt;\">[" + row.batch.batch + "]</span>" + "<br />" + row.batch.expired_date;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.stok_terkini;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control aktual_qty\" id=\"item_" + row.uid + "\" batch=\"" + row.batch.uid + "\" placeholder=\"0.00\" />";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control keterangan_item\" id=\"keterangan_" + row.uid + "\" placeholder=\"Keterangan per Item\" />";
					}
				}
			]
		});*/

		$("#txt_diproses").val(__MY_NAME__);

		$("body").on("keyup", ".aktual_qty", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

            var batch = $(this).attr("batch");

            if(metaDataOpname[uid + "_" + batch] == undefined) {
                metaDataOpname[uid + "_" + batch] = {
                    qty_awal: 0,
                    signed: 0,
                    batch: "",
                    nilai: 0,
                    keterangan: "-"
                };
            }

            metaDataOpname[uid + "_" + batch].signed = 1;
			metaDataOpname[uid + "_" + batch].nilai = parseFloat($(this).inputmask("unmaskedvalue"));
		});

		$("body").on("change", ".keterangan_item", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

            var batch = $(this).attr("batch");

            if(metaDataOpname[uid + "_" + batch] == undefined) {
                metaDataOpname[uid + "_" + batch] = {
                    qty_awal: 0,
                    signed: 0,
                    batch: "",
                    nilai: 0,
                    keterangan: "-"
                };
            }

            metaDataOpname[uid + "_" + batch].signed = 1;
			metaDataOpname[uid + "_" + batch].keterangan = $(this).val();
		});


















        var tempTransact = $("#table-temp").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "get_temp_transact";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var rawData = [];

                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return rawData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"text-right " + ((__UNIT__.gudang === row.gudang_asal.uid) ? "text-info" : "") + "\">" + row.gudang_asal.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.gudang_tujuan !== undefined && row.gudang_tujuan !== null) {
                            return "<span style=\"display: block\" class=\"" + ((__UNIT__.gudang === row.gudang_tujuan.uid) ? "text-info" : "") + "\">" + row.gudang_tujuan.nama + "</span>";
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"number_style\">" + number_format(row.qty, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.item.satuan_terkecil_info.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var solution = "";
                        if(row.gudang_tujuan !== undefined && row.gudang_tujuan !== null) {
                            if((row.transact_table === "resep" || row.transact_table === "racikan") && row.gudang_tujuan.uid === __UNIT__.gudang) {
                                if(row.gudang_asal.uid === __GUDANG_UTAMA__) {
                                    solution = "amprah";
                                } else {
                                    solution = "mutasi";
                                }
                            } else if((row.transact_table === "resep" || row.transact_table === "racikan") && row.gudang_asal.uid === __UNIT__.gudang && row.gudang_tujuan.uid === null) {
                                solution = "general";
                            } else {
                                solution = "undefined";
                            }
                        } else {
                            if(row.gudang_tujuan === undefined || row.gudang_tujuan === null) {
                                solution = "general";
                            } else {
                                if((row.transact_table === "resep" || row.transact_table === "racikan") && row.gudang_asal.uid === __UNIT__.gudang) {
                                    solution = "general";
                                } else {
                                    solution = "undefined";
                                }
                            }
                        }
                        return "<span class=\"badge badge-custom-caption badge-outline-info\">" + solution.toUpperCase() + "</span>";
                    }
                }
            ]
        });
		
	});

</script>










<div id="form-rekap-post-opname" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Penyesuaian Transaksi Post Opname</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="z-0">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                        <li class="nav-item">
                            <a href="#tab-post-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true">
							<span class="nav-link__count">
								01
							</span>
                                Amprah Kekurangan Stok
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab-post-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" >
							<span class="nav-link__count">
								02
							</span>
                                Potong Stok
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane show fade active" id="tab-post-1">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <br />
                                    </div>
                                    <div class="col-lg-1">
                                        <span class="badge badge-custom-caption badge-outline-purple">AMPRAH</span>
                                    </div>
                                    <div class="col-lg-11">
                                        <p>
                                            <b class="text-danger">Pengambilan Manual</b> dari gudang farmasi. Lengkapi informasi <b class="text-info">[Nama Pengamprah]</b>
                                        </p>
                                    </div>
                                    <div class="col-lg-12">
                                        <br />
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <table class="table table-bordered table-striped" id="strategi-amprah">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th style="width: 50%">Barang</th>
                                                        <th class="wrap_content">Batch</th>
                                                        <th class="wrap_content">Jumlah</th>
                                                        <th class="wrap_content">Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show fade" id="tab-post-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <br />
                                    </div>
                                    <div class="col-lg-1">
                                        <h4 class="badge badge-custom-caption badge-outline-purple">POTONG</h4>
                                    </div>
                                    <div class="col-lg-11">
                                        <p>
                                            Memotong langsung pada stok gudang terkait karena pada saat transaksi, barang tersedia.
                                        </p>
                                    </div>
                                    <div class="col-lg-12">
                                        <br />
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <table class="table table-bordered table-striped" id="strategi-potong">
                                                    <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th style="width: 50%">Barang</th>
                                                        <th class="wrap_content">Batch</th>
                                                        <th class="wrap_content">Jumlah</th>
                                                        <th class="wrap_content">Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnProsesStrategi">Proses</button>
            </div>
        </div>
    </div>
</div>


















<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Penyesuaian Stok</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="txt_no_skp">Periode Awal:</label>
									<input type="text" class="form-control txt_tanggal" id="txt_periode_awal" setTanggal="<?php echo $day->format('Y-m-1'); ?>" readonly />
								</div>
								<div class="form-group col-md-6">
									<label for="txt_no_skp">Periode Akhir:</label>
									<input type="text" class="form-control txt_tanggal" id="txt_periode_akhir" setTanggal="<?php echo $day->format('Y-m-d'); ?>" readonly />
								</div>
								<div class="form-group col-md-6">
									<label for="txt_no_skp">Dikerjakan Oleh:</label>
									<input type="text" class="form-control" id="txt_diproses" readonly />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card card-form">
					<div class="row no-gutters">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0">
                            <button class="btn btn-success pull-right" id="resync_job">Sync Data</button>
                            </h5>
                        </div>
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-12">

									<table class="table table-bordered table-striped" id="current-stok">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th style="width: 40%">Barang</th>
                                                <th class="wrap_content">Petugas</th>
												<th class="wrap_content">Stok</th>
                                                <th>Satuan</th>
												<th style="width: 10%;">Aktual</th>
												<th>Keterangan</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<b>Keterangan:</b>
									<textarea placeholder="Keterangan Penyesuaian Stok" class="form-control" id="txt_keterangan" style="min-height: 200px"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitStokOpname">Simpan</button>
			</div>
		</div>
	</div>
</div>










<div id="form-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Hasil Penyesuaian Stok</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="txt_kode_detail">Kode Opname:</label>
									<br />
									<b id="txt_kode_detail"></b>
								</div>
								<div class="form-group col-md-6">
									<label for="txt_diproses_detail">Dikerjakan Oleh:</label>
									<br />
									<b id="txt_diproses_detail"></b>
								</div>
								<div class="form-group col-md-6">
									<label for="txt_periode_awal_detail">Periode Awal:</label>
									<br />
									<b id="txt_periode_awal_detail"></b>
								</div>
								<div class="form-group col-md-6">
									<label for="txt_periode_akhir_detail">Periode Akhir:</label>
									<br />
									<b id="txt_periode_akhir_detail"></b>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-12">
									<table class="table table-bordered largeDataType" id="detail-opname">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th>Barang</th>
												<th class="wrap_content">Batch</th>
												<th class="wrap_content">Awal</th>
												<th class="wrap_content">Akhir</th>
												<th class="wrap_content">Selisih</th>
												<th style="width: 15%;">Keterangan</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
                                <div class="form-group col-md-12">
                                    Keterangan:<br />
                                    <table class="table">
                                        <tr>
                                            <td class="bg-danger" style="width: 50px !important;"> </td><td>Stok kurang</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-warning" style="width: 50px !important;"> </td><td>Stok berlebih</td>
                                        </tr>
                                        <tr>
                                        <td class="bg-success" style="width: 50px !important;"> </td><td>Stok sesuai</td>
                                        </tr>
                                    </table>
                                </div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<b>Keterangan:</b>
									<p id="txt_keterangan_detail"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>