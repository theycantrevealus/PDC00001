<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function(){

		var selectedUID;
		var selectedUIDPasien;
		var selectedUIDKwitansi;
		var selectedPoli;
		var selectedPasien;
		var selectedPenjamin;
		var selectedKunjungan;
		var totalItemPay = 0;
		var totalItemPayDiscount = 0;
		var currentPasienName;
		var itemMeta = [];

		

		function getDateRange(target) {
			var rangeKwitansi = $(target).val().split(" to ");
			if(rangeKwitansi.length > 1) {
				return rangeKwitansi;
			} else {
				return [rangeKwitansi, rangeKwitansi];
			}	
		}

		var tableKwitansi = $("#table-kwitansi").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
            "order": [[ 1, "desc" ]],
			"ajax":{
				url: __HOSTAPI__ + "/Invoice",
				type: "POST",
				data: function(d){
					d.request = "kwitansi_data";
					d.from = getDateRange("#range_kwitansi")[0];
					d.to = getDateRange("#range_kwitansi")[1];
					d.column_set = ['created_at', 'nomor_kwitansi', 'created_at', 'metode_bayar', 'pegawai', 'terbayar'];
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var dataSet = response.response_package.response_data;
					var dataResponse = [];
					if(dataSet == undefined) {
						dataSet = [];
					}

					for(var kwitansiKey in dataSet) {
					    if(
					        dataSet[kwitansiKey].pasien !== null &&
                            dataSet[kwitansiKey].pasien !== undefined
                        ) {
					        dataResponse.push(dataSet[kwitansiKey]);
                        }
                    }

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataResponse;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Nomor Kwitansi"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
					    if(
					        row.pasien.panggilan_name !== undefined &&
                            row.pasien.panggilan_name !== null
                        ) {
                            return row.nomor_kwitansi + " - " + row.pasien.panggilan_name.nama + " " + row.pasien.nama;
                        } else {
                            return row.nomor_kwitansi + " - " + row.pasien.nama;
                        }
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.tanggal_bayar;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.metode_bayar;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.terbayar;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btnDetailKwitansi\" invoice_payment=\"" + row.uid + "\" invoice=\"" + row.invoice + "\" id=\"invoice_" + row.uid + "\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                            "</button>" +
                            "</div>";
					}
				}
			]
		});

		$("#btnCetakFaktur").click(function() {

            var data = $("#payment-detail-loader").html();
		    var containerTemp = document.createElement("DIV");
		    $(containerTemp).html(data);


            $(containerTemp).find(".row, .card-body, .card-header").css({
                "width": "100%"
            });
            $(containerTemp).find(".col-6").removeClass("text-center").css({
                "text-align": "left",
                "width": "50%",
                "margin-bottom": "20px"
            });
            $(containerTemp).find("table thead tr th:eq(0)").remove();
		    $(containerTemp).find("table tbody tr").each(function() {


                $(this).find("td").css({
                    "color": "#000 !important"
                });

		        var attr = $(this).find("td:eq(0)").attr("colspan");
		        if(typeof attr !== 'undefined' && attr !== false) {

                } else {
                    $(this).find("td:eq(0)").remove();
                }
            });

            $(containerTemp).find("table tbody tr td:eq(1)").css({
                "width": "50px",
                "color": "#000 !important"
            });

            $(containerTemp).find("table tbody tr td[colspan=\"5\"]").css({
                "padding-top": "20px"
            });

            $(containerTemp).find("table tbody tr td:eq(2)").css({
                "width": "50%",
                "color": "#000 !important"
            });

            $(containerTemp).find("table thead").addClass("thead-dark");
		    $(containerTemp).find("#keterangan-faktur").attr({
                "colspan": "3"
            });

		    $(containerTemp).find("#nama-pasien-faktur span").css({
                "display": "inline"
            });


            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/kasir_faktur.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    kwitansi_data: $(containerTemp).html(),
                    pasien: $("#payment-detail-loader .info-kwitansi col-3:eq(1)").html(),
                    pegawai: $("#payment-detail-loader .info-kwitansi col-3:eq(2)").html(),
                    tgl_bayar: $("#payment-detail-loader .info-kwitansi col-3:eq(3)").html(),
                    poli: $("#payment-detail-loader .info-kwitansi col-3:eq(4)").html()
                },
                success: function (response) {
                    var containerItem = document.createElement("DIV");

                    /*var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=200,top="+(screen.height-400)+",left="+(screen.width-840));
                    win.document.body.innerHTML = response;*/

                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: true,
                        importStyle: true,
                        header: null,
                        footer: null,
                        pageTitle: "Kwitansi",
                        afterPrint: function() {
                            $("#form-payment-detail").modal("hide");
                        }
                    });
                },
                error: function (response) {
                    //
                }
            });
		    return false;
        });

		$("body").on("click", ".btnDetailKwitansi", function() {
		    var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			selectedUID = $(this).attr("invoice");
			selectedUIDKwitansi = $(this).attr("invoice_payment");

			$.ajax({
                async: false,
				url: __HOSTNAME__ + "/pages/kasir/pasien/payment_detail.php",
				type: "POST",
				success: function(response) {
					$("#form-payment-detail").modal("show");
					$("#payment-detail-loader").html(response);
					$.ajax({
                        async: false,
						url:__HOSTAPI__ + "/Invoice/payment/" + uid,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type:"GET",
						success:function(response_data) {

							var pasienInfo = response_data.response_package.response_data[0].pasien;

							selectedUIDPasien = pasienInfo.uid;

							if(
                                pasienInfo.panggilan_name !== undefined &&
                                pasienInfo.panggilan_name !== null
                            ) {
                                $("#nama-pasien-faktur").html("Pasien:<br /><b>" + pasienInfo.panggilan_name.nama + " " + pasienInfo.nama + " [<span class=\"text-info\">" + pasienInfo.no_rm + "</span>]</b>");
                            } else {
                                $("#nama-pasien-faktur").html("Pasien:<br /><b>" + pasienInfo.nama + " [<span class=\"text-info\">" + pasienInfo.no_rm + "</span>]</b>");
                            }

							$("#nomor-faktur").html($("#kwitansi_" + uid).html());

							var historyData = response_data.response_package.response_data[0];
							var historyDetail = historyData.detail;

							$("#pegawai-faktur").html("Diterima Oleh :<br /><b>" + historyData.pegawai.nama + "</b>");
							$("#tanggal-faktur").html("Tanggal Bayar :<br /><b>" + historyData.tanggal_bayar + "</b>");
							var deptList = [];
							for(var depKey in historyData.antrian) {
							    deptList.push(historyData.antrian[depKey].nama);
                            }
							$("#poli").html("Departemen/Poli :<br /><b>" + deptList.join(", ") + "</b>");
							$("#keterangan-faktur").html(historyData.keterangan);
							$("#total-faktur").html(number_format(historyData.terbayar, 2, ".", ","));
							$("#diskon-faktur").html(0);
							$("#grand-total-faktur").html(number_format(historyData.terbayar, 2, ".", ","));

							var billing_group = {};

							for(var historyKey in historyDetail) {
							    if(billing_group[historyDetail[historyKey].billing_group] === undefined) {
                                    billing_group[historyDetail[historyKey].billing_group] = [];
                                }


                                billing_group[historyDetail[historyKey].billing_group].push({
                                    uid: historyDetail[historyKey].item_uid,
                                    status: historyDetail[historyKey].status,
                                    nama: historyDetail[historyKey].item.toUpperCase(),
                                    allow_retur: historyDetail[historyKey].allow_retur,
                                    qty: historyDetail[historyKey].qty,
                                    harga: historyDetail[historyKey].harga,
                                    subtotal: historyDetail[historyKey].subtotal
                                });

								/*$("#invoice_detail_history tbody").append(
									"<tr>" +
										"<td>" + ((historyDetail[historyKey].status == "P") ? ((historyDetail[historyKey].allow_retur) ? "<input type=\"checkbox\" class=\"returItem\" value=\"" + historyDetail[historyKey].item_uid + "\" />" : "<i class=\"fa fa-exclamation-circle text-warning\"></i>") : "<i class=\"fa fa-times text-danger\"></i>") + "</td>" +
										"<td>" + (parseInt(historyKey) + 1)+ "</td>" +
										"<td>" + historyDetail[historyKey].item.toUpperCase() + "</td>" +
										"<td class=\"number_style\">" + historyDetail[historyKey].qty + "</td>" +
										"<td class=\"text-right\">" + number_format(historyDetail[historyKey].harga, 2, ".", ",") + "</td>" +
										"<td class=\"text-right\">" + number_format(historyDetail[historyKey].subtotal, 2, ".", ",") + "</td>" +
									"</tr>"
								);*/
							}

							for(var groupKey in billing_group) {
                                $("#invoice_detail_history tbody").append("<tr>" +
                                    "<td></td>" +
                                    "<td colspan=\"5\" class=\"bg-info\" style=\"color: #fff\">" + groupKey.toUpperCase() + "</td>" +
                                    "</tr>");
                                for(var itemKey in billing_group[groupKey]) {
                                    $("#invoice_detail_history tbody").append(
                                        "<tr>" +
                                        "<td>" + ((billing_group[groupKey][itemKey].status == "P") ? ((billing_group[groupKey][itemKey].allow_retur) ? "<input type=\"checkbox\" class=\"returItem\" value=\"" + billing_group[groupKey][itemKey].uid + "\" />" : "<i class=\"fa fa-exclamation-circle text-warning\"></i>") : "<i class=\"fa fa-times text-danger\"></i>") + "</td>" +
                                        "<td style=\"width: 50px !important;\">" + (parseInt(itemKey) + 1)+ "</td>" +
                                        "<td>" + billing_group[groupKey][itemKey].nama.toUpperCase() + "</td>" +
                                        "<td class=\"number_style\">" + billing_group[groupKey][itemKey].qty + "</td>" +
                                        "<td class=\"text-right\">" + number_format(billing_group[groupKey][itemKey].harga, 2, ".", ",") + "</td>" +
                                        "<td class=\"text-right\">" + number_format(billing_group[groupKey][itemKey].subtotal, 2, ".", ",") + "</td>" +
                                        "</tr>"
                                    );
                                }
                            }
						}
					});
				}
			});
		});

		$("#range_kwitansi").change(function() {
			tableKwitansi.ajax.reload();
		});

		
		var tableAntrianBayarRJ = $("#table-biaya-pasien-rj").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Invoice",
				type: "POST",
				data: function(d) {
					d.request = "biaya_pasien";
					d.from = getDateRange("#range_invoice")[0];
					d.to = getDateRange("#range_invoice")[1];
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var returnedData = [];
					if(returnedData == undefined || returnedData.response_package == undefined) {
						returnedData = [];
					}

					for(var InvKeyData in response.response_package.response_data) {
					    if(
                            response.response_package.response_data[InvKeyData].antrian_kunjungan.poli !== undefined &&
                            response.response_package.response_data[InvKeyData].antrian_kunjungan.poli !== null
                        ) {
                            if(
                                response.response_package.response_data[InvKeyData].antrian_kunjungan !== undefined &&
                                response.response_package.response_data[InvKeyData].pasien !== undefined &&
                                response.response_package.response_data[InvKeyData].pasien !== null &&
                                response.response_package.response_data[InvKeyData].antrian_kunjungan.poli.uid !== __POLI_IGD__ &&
                                response.response_package.response_data[InvKeyData].antrian_kunjungan.poli.uid !== __POLI_INAP__
                            ) {
                                if(!response.response_package.response_data[InvKeyData].lunas) {
                                    if(response.response_package.response_data[InvKeyData].pasien.panggilan_name === undefined) {
                                        response.response_package.response_data[InvKeyData].pasien.panggilan_name = "";
                                    }
                                    returnedData.push(response.response_package.response_data[InvKeyData]);
                                }
                            } else {
                                //
                            }
                        }
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = returnedData.length;
					
					return returnedData;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Nomor Invoice"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span style=\"white-space: pre\">" + row.nomor_invoice + "</span>";
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"white-space: pre\">" + row.created_at_parse + "</span>";
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
					    if(
					        row.pasien.panggilan_name !== undefined &&
                            row.pasien.panggilan_name !== null
                        ) {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.panggilan_name.nama + " " + row.pasien.nama + "</b>";
                        } else {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.nama + "</b>";
                        }
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
					    var poliList = [];
					    var uniquePoliList = [];
					    for(var az in row.antrian_kunjungan.poli_list) {
					        var targetPoli = {
					            uid: "",
                                nama: ""
                            };
					        if(row.antrian_kunjungan.poli_list[az].poli !== null) {
                                targetPoli = {
                                    uid: row.antrian_kunjungan.poli_list[az].poli.uid,
                                    nama: row.antrian_kunjungan.poli_list[az].poli.nama
                                };
                            } else {
                                targetPoli = {
                                    uid: __POLI_INAP__,
                                    nama: "Rawat Inap"
                                };
                            }
					        if(uniquePoliList.indexOf(targetPoli.uid) < 0) {
					            uniquePoliList.push(targetPoli.uid);
                                poliList.push("<span class=\"badge badge-custom-caption badge-info\"><i class=\"fa fa-tags\"></i> " + targetPoli.nama + "</span>");
                            }
                        }
					    if(poliList.length > 0) {
                            return poliList.join(" ");
                        } else {
                            return "<span class=\"badge badge-custom-caption badge-info\"><i class=\"fa fa-tags\"></i> " + row.antrian_kunjungan.poli.nama + "</span>";
                        }
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class=\"wrap_content\">" + row.antrian_kunjungan.pegawai.nama + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
                        var invDetail = row.invoice_detail;
                        var totalParse = 0;
                        for(var a in invDetail) {
                            if(
                                invDetail[a].departemen !== __POLI_INAP__ &&
                                invDetail[a].departemen !== __POLI_IGD__
                            ) {
                                totalParse += parseFloat(invDetail[a].subtotal);
                            }
                        }
                        return "<span style=\"display: block\" class=\"text-right number_style\">" + number_format(totalParse, 2, ".", ",") + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btnDetail\" classified=\"RJ\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                            "</button>" +
                            "</div>";
					}
				}
			]
		});









        var tableAntrianBayarRI = $("#table-biaya-pasien-ri").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Invoice",
                type: "POST",
                data: function(d) {
                    d.request = "biaya_pasien";
                    d.from = getDateRange("#range_invoice")[0];
                    d.to = getDateRange("#range_invoice")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];

                    if(returnedData == undefined || returnedData.response_package == undefined) {
                        returnedData = [];
                    }
                    for(var InvKeyData in response.response_package.response_data) {
                        if(
                            response.response_package.response_data[InvKeyData].antrian_kunjungan.poli !== undefined &&
                            response.response_package.response_data[InvKeyData].antrian_kunjungan.poli !== null
                        ) {
                            if (
                                response.response_package.response_data[InvKeyData].antrian_kunjungan !== undefined &&
                                response.response_package.response_data[InvKeyData].pasien !== undefined &&
                                response.response_package.response_data[InvKeyData].pasien !== null &&
                                response.response_package.response_data[InvKeyData].antrian_kunjungan.poli.uid === __POLI_INAP__
                            ) {
                                if (!response.response_package.response_data[InvKeyData].lunas) {
                                    if (response.response_package.response_data[InvKeyData].pasien.panggilan_name === undefined) {
                                        response.response_package.response_data[InvKeyData].pasien.panggilan_name = "";
                                    }
                                    returnedData.push(response.response_package.response_data[InvKeyData]);
                                }
                            } else {
                                if(response.response_package.response_data[InvKeyData].departemen_terkait.indexOf(__POLI_INAP__) >= 0) {
                                    returnedData.push(response.response_package.response_data[InvKeyData]);
                                }
                            }
                        } else {
                            if(response.response_package.response_data[InvKeyData].departemen_terkait.indexOf(__POLI_INAP__) >= 0) {
                                returnedData.push(response.response_package.response_data[InvKeyData]);
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"white-space: pre\">" + row.nomor_invoice + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"white-space: pre\">" + row.created_at_parse + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(
                            row.pasien.panggilan_name !== undefined &&
                            row.pasien.panggilan_name !== null
                        ) {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.panggilan_name.nama + " " + row.pasien.nama + "</b>";
                        } else {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.nama + "</b>";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"badge badge-custom-caption badge-info\"><i class=\"fa fa-tags\"></i> Rawat Inap</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.antrian_kunjungan.pegawai.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var invDetail = row.invoice_detail;
                        var totalParse = 0;
                        for(var a in invDetail) {
                            if(invDetail[a].departemen === __POLI_INAP__) {
                                totalParse += parseFloat(invDetail[a].subtotal);
                            }
                        }
                        return "<span style=\"display: block\" class=\"text-right number_style\">" + number_format(totalParse, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btnDetail\" classified=\"RI\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail</span></button>" +
                            "</div>";
                    }
                }
            ]
        });



















        var tableAntrianBayarIGD = $("#table-biaya-pasien-igd").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Invoice",
                type: "POST",
                data: function(d) {
                    d.request = "biaya_pasien";
                    d.from = getDateRange("#range_invoice")[0];
                    d.to = getDateRange("#range_invoice")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(returnedData == undefined || returnedData.response_package == undefined) {
                        returnedData = [];
                    }

                    for(var InvKeyData in response.response_package.response_data) {
                        if(
                            response.response_package.response_data[InvKeyData].antrian_kunjungan.poli !== undefined &&
                            response.response_package.response_data[InvKeyData].antrian_kunjungan.poli !== null
                        ) {
                            if (
                                response.response_package.response_data[InvKeyData].antrian_kunjungan !== undefined &&
                                response.response_package.response_data[InvKeyData].pasien !== undefined &&
                                response.response_package.response_data[InvKeyData].pasien !== null &&
                                response.response_package.response_data[InvKeyData].antrian_kunjungan.poli.uid === __POLI_IGD__
                            ) {
                                if (!response.response_package.response_data[InvKeyData].lunas) {
                                    if (response.response_package.response_data[InvKeyData].pasien.panggilan_name === undefined) {
                                        response.response_package.response_data[InvKeyData].pasien.panggilan_name = "";
                                    }
                                    returnedData.push(response.response_package.response_data[InvKeyData]);
                                }
                            } else {
                                //
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nomor_invoice + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parse + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(
                            row.pasien.panggilan_name !== undefined &&
                            row.pasien.panggilan_name !== null
                        ) {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.panggilan_name.nama + " " + row.pasien.nama + "</b>";
                        } else {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.nama + "</b>";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"badge badge-custom-caption badge-info\"><i class=\"fa fa-tags\"></i> IGD</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return row.antrian_kunjungan.pegawai.nama + " di <b>" + row.antrian_kunjungan.loket.nama_loket + "</b>";
                        return "<span class=\"wrap_content\">" + row.antrian_kunjungan.pegawai.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var invDetail = row.invoice_detail;
                        var totalParse = 0;
                        for(var a in invDetail) {
                            if(invDetail[a].departemen === __POLI_IGD__) {
                                totalParse += parseFloat(invDetail[a].subtotal);
                            }
                        }
                        return "<span style=\"display: block\" class=\"text-right number_style\">" + number_format(totalParse, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btnDetail\" classified=\"IGD\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });












        protocolLib = {
            userlist: function(protocols, type, parameter, sender, receiver, time) {
                //
            },
            userlogin: function(protocols, type, parameter, sender, receiver, time) {
                //
            },
            kasir_daftar_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", "Transaksi baru", 3000, "notif_pasien_baru");
                tableAntrianBayarRJ.ajax.reload();
                tableAntrianBayarRI.ajax.reload();
                tableAntrianBayarIGD.ajax.reload();
            },
            asesmen_berlangsung: function(protocols, type, parameter, sender, receiver, time) {
                if(
                    selectedUIDPasien === parameter.pasien &&
                    ($("#form-payment-detail").data('bs.modal') || {})._isShown
                ) {
                    $("#form-payment-detail").modal("hide");
                    Swal.fire(
                        "System",
                        "Asesmen sedang berlangsung. Retur biaya tidak dapat dilakukan kembali",
                        "warning"
                    ).then((result) => {
                        //
                    });
                    //$("input.returItem[value=\"" + parameter.tindakan + "\]").parent().html("<i class=\"fa fa-exclamation-circle text-warning\"></i> Asesmen Berlangsung");
                }
            }
        };


		$("#range_invoice").change(function() {
            tableAntrianBayarRJ.ajax.reload();
            tableAntrianBayarRI.ajax.reload();
            tableAntrianBayarIGD.ajax.reload();
		});

		


		$("body").on("click", ".btnDetail", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var me = $(this);
			me.removeClass("btn-info").addClass("btn-warning").html("<span><i class=\"fa fa-hourglass-half\"></i>Loading</span>");
			var poli = $(this).attr("poli");
			var pasien = $(this).attr("pasien");
			var penjamin = $(this).attr("penjamin");
			var kunjungan = $(this).attr("kunjungan");
			var classified =  $(this).attr("classified");

			selectedUID = uid;
			selectedPoli = poli;
			selectedPasien = pasien;
			selectedPenjamin = penjamin;
			selectedKunjungan = kunjungan;
			totalItemPay = 0;
			totalItemPayDiscount = 0;
			currentPasienName = "";
			itemMeta = [];

			$.ajax({
				url: __HOSTNAME__ + "/pages/kasir/pasien/form.php",
				type: "POST",
				success: function(response) {
					$("#form-loader").html(response);
					$.ajax({
						url:__HOSTAPI__ + "/Invoice/detail/" + uid,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type:"GET",
						success:function(response_data) {
							var invoice_detail = response_data.response_package.response_data[0];

							if(
							    invoice_detail.pasien.panggilan_name !== undefined &&
                                invoice_detail.pasien.panggilan_name !== null
                            ) {
                                $("#nama-pasien").html(invoice_detail.pasien.panggilan_name.nama + " " + invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]");
                                currentPasienName = invoice_detail.pasien.panggilan_name.nama + " " + invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]";
                            } else {
                                $("#nama-pasien").html(invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]");
                                currentPasienName = invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]";
                            }


							$("#nomor-invoice").html(invoice_detail.nomor_invoice);
							var invoice_detail_item = invoice_detail.invoice_detail;

							var kategoriBayar = {
							    "biaya_administrasi":{
							        caption:"Biaya Administrasi",
                                    item: []
                                },
                                "biaya_tindakan_tindakan_":{
                                    caption:"Tindakan Poli",
                                    item: []
                                },
                                "biaya_tindakan_pendukung":{
                                    caption:"Tindakan Pendukung",
                                    item: []
                                },
                                "biaya_obat":{
                                    caption:"Biaya Obat",
                                    item: []
                                },
                                "biaya_racikan":{
                                    caption:"Biaya Racikan",
                                    item: []
                                },
                                "tarif_kamar": {
                                    caption:"Tarif Kamar",
                                    item: []
                                }
                            };

							var item_grouper = {};
							var filteredClassified = [];
                            for(var invKey in invoice_detail_item) {
                                if(classified === 'RJ') {
                                    if(
                                        invoice_detail_item[invKey].departemen !== __POLI_INAP__ &&
                                        invoice_detail_item[invKey].departemen !== __POLI_IGD__
                                    ) {
                                        filteredClassified.push(invoice_detail_item[invKey]);
                                    }
                                } else if(classified === 'RI') {
                                    if(
                                        invoice_detail_item[invKey].departemen === __POLI_INAP__
                                    ) {
                                        filteredClassified.push(invoice_detail_item[invKey]);
                                    }
                                } else if(classified === 'IGD') {
                                    if(
                                        invoice_detail_item[invKey].departemen === __POLI_IGD__
                                    ) {
                                        filteredClassified.push(invoice_detail_item[invKey]);
                                    }
                                }

                            }

                            invoice_detail_item = filteredClassified;

							for(var invKey in invoice_detail_item) {
							    if(invoice_detail_item[invKey].item_type === "master_tindakan")
                                {
                                    //Biaya Admnistrasi
                                    //Biaya Tindakan
                                }
								var status_bayar = "";
								if(invoice_detail_item[invKey].status_bayar == 'N') {
                                    status_bayar = "<input item-id=\"" + invoice_detail_item[invKey].id + "\" value=\"" + invoice_detail_item[invKey].subtotal + "\" type=\"checkbox\" class=\"proceedInvoice\" />";
                                } else if(invoice_detail_item[invKey].status_bayar == 'V') {
                                    status_bayar = "<span class=\"text-info\" style=\"white-space: pre\"><i class=\"fa fa-info-circle\"></i> Verifikasi</span>";
								} else {
									if(invoice_detail_item[invKey].item.allow_retur == true) {
										if(invoice_detail_item[invKey].status_berobat == undefined) {
											status_bayar = "<span class=\"text-success\" style=\"white-space: pre\"><i class=\"fa fa-check\"></i> Lunas</span>";
										} else {
											if(invoice_detail_item[invKey].status_berobat.status == "N") {
												//status_bayar = "<button class=\"btn btn-info btn-sm btn-retur-pembayaran\" id=\"retur_pembayaran_" + invoice_detail_item[invKey].item.uid + "\">Retur</button>";
												status_bayar = "<span class=\"text-success\" style=\"white-space: pre\"><i class=\"fa fa-check\"></i> Lunas</span>";
											} else {
												status_bayar = "<span class=\"text-success\" style=\"white-space: pre\"><i class=\"fa fa-check\"></i> Lunas</span>";
											}
										}
									} else {
										status_bayar = "<span class=\"text-success\" style=\"white-space: pre\"><i class=\"fa fa-check\"></i> Lunas</span>";
									}
								}

								if(item_grouper[invoice_detail_item[invKey].billing_group] === undefined) {
                                    item_grouper[invoice_detail_item[invKey].billing_group] = {
                                        item: []
                                    };
                                }

								item_grouper[invoice_detail_item[invKey].billing_group].item.push({
                                    status_bayar: status_bayar,
                                    autonum: invoice_detail_item[invKey].autonum,
                                    nama: invoice_detail_item[invKey].item.nama.toUpperCase(),
                                    qty: invoice_detail_item[invKey].qty,
                                    departemen: invoice_detail_item[invKey].departemen,
                                    keterangan: invoice_detail_item[invKey].keterangan,
                                    penjamin: invoice_detail_item[invKey].penjamin.nama,
                                    harga: number_format(invoice_detail_item[invKey].harga, 2, ".", ","),
                                    total: number_format(invoice_detail_item[invKey].subtotal, 2, ".", ",")
                                });

								itemMeta = invoice_detail_item;
							}

							for(itemKey in item_grouper) {
							    var parseName = "";
							    if(itemKey === "tindakan") {
							        parseName = "Tindakan";
                                } else if(itemKey === "obat"){
							        parseName = "Obat / BHP";
                                } else if(itemKey === "laboratorium"){
                                    parseName = "Laboratorium";
                                } else if(itemKey === "radiologi"){
                                    parseName = "Radiologi";
                                } else if(itemKey === "administrasi"){
                                    parseName = "Administrasi";
                                } else if(itemKey === "tarif_kamar"){
                                    parseName = "Tarif Kamar";
                                } else {
							        parseName = "Unspecified";
                                }

                                $("#invoice_detail_item").append(
                                    "<tr>" +
                                    "<td colspan=\"6\" class=\"bg-info\"><h6 class=\"text-white\">" + parseName + "</h6></td>" +
                                    "</tr>"
                                );
							    var detailData = item_grouper[itemKey].item;
							    for(var itemDetailKey in detailData) {
                                    $("#invoice_detail_item").append(
                                        "<tr>" +
                                        "<td>" + detailData[itemDetailKey].status_bayar + "</td>" +
                                        "<td>" + detailData[itemDetailKey].autonum + "</td>" +
                                        "<td>" + detailData[itemDetailKey].nama + "<br /><b class=\"text-muted\">" + detailData[itemDetailKey].keterangan + "</b>" + " <span style=\"float: right; margin-right: 50px;\" class=\"badge badge-info\">" + detailData[itemDetailKey].penjamin + "</span></td>" +
                                        "<td>" + detailData[itemDetailKey].qty + "</td>" +
                                        "<td class=\"text-right\">" + detailData[itemDetailKey].harga + "</td>" +
                                        "<td class=\"text-right\">" + detailData[itemDetailKey].total + "</td>" +
                                        "</tr>"
                                    );
                                    /*$("#invoice_detail_item").append(
                                    "<tr>" +
                                    "<td>" + status_bayar + "</td>" +
                                    "<td>" + invoice_detail_item[invKey].autonum + "</td>" +
                                    "<td>" + invoice_detail_item[invKey].item.nama.toUpperCase() + " <span style=\"float: right; margin-right: 50px;\" class=\"badge badge-info\">" + invoice_detail_item[invKey].penjamin.nama + "</span></td>" +
                                    "<td>" + invoice_detail_item[invKey].qty + "</td>" +
                                    "<td class=\"text-right\">" + number_format(invoice_detail_item[invKey].harga, 2, ".", ",") + "</td>" +
                                    "<td class=\"text-right\">" + number_format(invoice_detail_item[invKey].subtotal, 2, ".", ",") + "</td>" +
                                    "</tr>"
                                );*/
                                }
                            }

							var history_payment = invoice_detail.history;
							for(var hisKey in history_payment) {
								$("#payment_history tbody").append(
									"<tr>" +
										"<td>" + history_payment[hisKey].autonum + "</td>" +
										"<td id=\"kwitansi_" + history_payment[hisKey].uid + "\">" + history_payment[hisKey].nomor_kwitansi + "</td>" +
										"<td>" + history_payment[hisKey].tanggal_bayar + "</td>" +
										"<td>" + history_payment[hisKey].metode_bayar + "</td>" +
										"<td>" + history_payment[hisKey].pegawai.nama + "</td>" +
										"<td class=\"text-right\">" + number_format(history_payment[hisKey].terbayar, 2, ".", ",") + "</td>" +
										"<td><button class=\"btn btn-sm btn-info btnDetailPayment\" id=\"payment_" + history_payment[hisKey].uid + "\">" +
                                    "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                                    "</button></td>" +
									"</tr>"
								);
							}

							$("#form-invoice").modal("show");
                            me.removeClass("btn-warning").addClass("btn-info").html("<span><i class=\"fa fa-eye\"></i>Detail</span>");
						},
						error: function(response) {
							console.log("Error : " + response);
						}
					});
				}
			});
		});

		

		$("body").on("change", "#bulk-all", function() {
			if($(this).is(":checked")) {
				$(".proceedInvoice").prop("checked", true);
				$(".proceedInvoice").each(function() {
					totalItemPay += parseFloat($(this).val());
				});
			} else {
				$(".proceedInvoice").prop("checked", false);
				totalItemPay = 0;
			}

			/*$("#text-total").html(number_format(totalItemPay, 2, ".", ","));
			var diskonAll = $("#txt_diskon_all").val();
			var diskonTypeAll = $("#txt_diskon_type_all").val();
			if(totalItemPay > 0) {
				if(diskonTypeAll == "P") {
					totalItemPayDiscount = totalItemPay - (diskonAll / 100 * totalItemPay);
				} else if(diskonTypeAll == "A") {
					totalItemPayDiscount = totalItemPay - diskonAll;
				} else {
					totalItemPayDiscount = totalItemPay;
				}
			} else {
				totalItemPayDiscount = 0;
			}

			$("#text-grand-total").html(number_format(totalItemPayDiscount, 2, ".", ","));*/
		});
		
		$("body").on("change", ".proceedInvoice", function() {
			var allChecked = false;
			$(".proceedInvoice").each(function(){
				if(!$(this).is(":checked")) {
					allChecked = false;
					return false;
				} else {
					allChecked = true;
				}
			});

			if(allChecked) {
				$("#bulk-all").prop("checked", true);
			} else {
				$("#bulk-all").prop("checked", false);
			}

			/*var diskonAll = $("#txt_diskon_all").val();
			var diskonTypeAll = $("#txt_diskon_type_all").val();

			if($(this).is(":checked")) {
				totalItemPay += parseInt($(this).val());
			} else {
				totalItemPay -= parseInt($(this).val());
			}

			$("#text-total").html(number_format(totalItemPay, 2, ".", ","));

			if(totalItemPay > 0) {
				if(diskonTypeAll == "P") {
					totalItemPayDiscount = totalItemPay - (diskonAll / 100 * totalItemPay);
				} else if(diskonTypeAll == "A") {
					totalItemPayDiscount = totalItemPay - diskonAll;
				} else {
					totalItemPayDiscount = totalItemPay;
				}
			} else {
				totalItemPayDiscount = 0;
			}

			$("#text-grand-total").html(number_format(totalItemPayDiscount, 2, ".", ","));*/
		});

		$("body").on("change", "#txt_diskon_type_all", function() {
			var diskonAll = $("#txt_diskon_all").val();
			var diskonTypeAll = $("#txt_diskon_type_all").val();
			
			if(totalItemPay > 0) {
				if(diskonTypeAll == "P") {
					totalItemPayDiscount = totalItemPay - (diskonAll / 100 * totalItemPay);
				} else if(diskonTypeAll == "A") {
					totalItemPayDiscount = totalItemPay - diskonAll;
				} else {
					$("#txt_diskon_all").val(0);
					totalItemPayDiscount = totalItemPay;
				}
			} else {
				totalItemPayDiscount = 0;
			}
			$("#text-grand-total").html(number_format(totalItemPayDiscount, 2, ".", ","));
		});

		$("body").on("keyup", "#txt_diskon_all", function() {
			var diskonAll = $("#txt_diskon_all").val();
			var diskonTypeAll = $("#txt_diskon_type_all").val();
			
			if(totalItemPay > 0) {
				if(diskonTypeAll == "P") {
					totalItemPayDiscount = totalItemPay - (diskonAll / 100 * totalItemPay);
				} else if(diskonTypeAll == "A") {
					totalItemPayDiscount = totalItemPay - diskonAll;
				} else {
					totalItemPayDiscount = totalItemPay;
				}
			} else {
				totalItemPayDiscount = 0;
			}
			$("#text-grand-total").html(number_format(totalItemPayDiscount, 2, ".", ","));
		});

		var selectedPay = [];

		$("body").on("click", "#btnBukaFaktur", function() {
			selectedPay = [];
			$(".proceedInvoice").each(function() {
				var id = $(this).attr("item-id");
				if($(this).is(":checked")) {
					if(selectedPay.indexOf(id) < 0) {
						selectedPay.push(parseInt(id));
					}
				}
			});

			if(selectedPay.length > 0) {
				$.ajax({
					url: __HOSTNAME__ + "/pages/kasir/pasien/payment.php",
					type: "POST",
					success: function(response) {
						$("#form-payment").modal("show");
						$("#payment-loader").html(response);
						$("#faktur-nama-pasien").html(currentPasienName);

						$("#txt_diskon_type_all").select2();
						$("#txt_diskon_all").inputmask({
							alias: 'currency',
							rightAlign: true,
							placeholder: "0.00",
							prefix: "",
							autoGroup: false,
							digitsOptional: true
						});

						var totalFaktur = 0;

						for(var selKey in itemMeta) {
							if(selectedPay.indexOf(itemMeta[selKey].id) >= 0) {
								totalFaktur += parseFloat(itemMeta[selKey].subtotal);
								$("#fatur_detail_item tbody").append(
									"<tr>" +
										"<td>" + itemMeta[selKey].autonum + "</td>" +
										"<td>" + itemMeta[selKey].item.nama + "</td>" +
										"<td>" + itemMeta[selKey].qty + "</td>" +
										"<td class=\"text-right\">" + number_format(itemMeta[selKey].harga, 2, ".", ",") + "</td>" +
										"<td class=\"text-right\">" + number_format(itemMeta[selKey].subtotal, 2, ".", ",") + "</td>" +
									"</tr>"
								);
							}
						}

						$("#text-total").html(number_format(totalFaktur, 2, ".", ","));
						$("#text-grand-total").html(number_format(totalFaktur, 2, ".", ","));
					}
				});
			} else {
				alert("Pilih item pembayaran.");
			}
		});

		$("#btnBayar").click(function() {
			Swal.fire({
                title: 'Sudah terima uang?',
                showDenyButton: true,
                type: 'warning',
                confirmButtonText: `Sudah`,
                confirmButtonColor: `#1297fb`,
                denyButtonText: `Belum`,
                denyButtonColor: `#ff2a2a`,
                onOpen(popup) {
                    var oldContentDeny = $(".swal2-deny").html();
                    $(".swal2-deny").html("");
                    $(".swal2-deny").append("<span><i class=\"fa fa-times\"></i>" + oldContentDeny + "</span>").removeClass("swal2-deny").addClass("btn btn-danger");

                    var oldContentConfirm = $(".swal2-confirm").html();
                    $(".swal2-confirm").html("");
                    $(".swal2-confirm").append("<span><i class=\"fa fa-check\"></i>" + oldContentConfirm + "</span>").removeClass("swal2-confirm").addClass("btn btn-success").css({
                        "background-color": "#48BA16",
                        "border-color": "#48BA16",
                        "box-shadow": "inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075)"
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Invoice",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data:{
                            request: "proses_bayar",
                            invoice: selectedUID,
                            invoice_item: selectedPay,
                            metode: "CASH",
                            discount:$("#txt_diskon_all").val(),
                            discount_type:$("#txt_diskon_type_all").val(),
                            pasien:selectedPasien,
                            penjamin:selectedPenjamin,
                            kunjungan:selectedKunjungan,
                            poli:selectedPoli,
                            keterangan:$("#keterangan-faktur").val()
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire({
                                    title: "Pembayaran Berhasil!",
                                    type: "success",
                                    html: response.response_package.response_message,
                                    onOpen(popup) {
                                        var oldContentDeny = $(".swal2-deny").html();
                                        $(".swal2-deny").html("");
                                        $(".swal2-deny").append("<span><i class=\"fa fa-times\"></i>" + oldContentDeny + "</span>").removeClass("swal2-deny").addClass("btn btn-danger");

                                        var oldContentConfirm = $(".swal2-confirm").html();
                                        $(".swal2-confirm").html("");
                                        $(".swal2-confirm").append("<span><i class=\"fa fa-check\"></i>" + oldContentConfirm + "</span>").removeClass("swal2-confirm").addClass("btn btn-success").css({
                                            "background-color": "#48BA16",
                                            "border-color": "#48BA16",
                                            "box-shadow": "inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075)"
                                        });
                                    }
                                }).then((result) => {
                                    tableAntrianBayarRJ.ajax.reload();
                                    tableAntrianBayarRI.ajax.reload();
                                    tableAntrianBayarIGD.ajax.reload();
                                    tableKwitansi.ajax.reload();
                                    $("#form-invoice").modal("hide");
                                    $("#form-payment").modal("hide");

                                    var notifier_target = response.response_package.response_notifier;
                                    for(var notifKey in notifier_target)
                                    {
                                        push_socket(__ME__, notifier_target[notifKey].protocol, notifier_target[notifKey].target, notifier_target[notifKey].message, "info").then(function() {
                                            //console.log("pushed!");
                                        });
                                    }

                                });
                            } else {
                                //console.log(response);
                                tableAntrianBayarRJ.ajax.reload();
                                tableAntrianBayarRI.ajax.reload();
                                tableAntrianBayarIGD.ajax.reload();
                                tableKwitansi.ajax.reload();
                                $("#form-invoice").modal("hide");
                                $("#form-payment").modal("hide");
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {
                    //Swal.fire('Changes are not saved', '', 'info')
                }
            });
		});

		$("body").on("click", ".btnDetailPayment", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			$.ajax({
				url: __HOSTNAME__ + "/pages/kasir/pasien/payment_detail.php",
				type: "POST",
				success: function(response) {
					$("#form-payment-detail").modal("show");
					$("#payment-detail-loader").html(response);
					$("#nama-pasien-faktur").html($("#nama-pasien").html());
					$("#nomor-faktur").html($("#kwitansi_" + uid).html());
					
					$.ajax({
						url:__HOSTAPI__ + "/Invoice/payment/" + uid,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type:"GET",
						success:function(response_data) {
							var historyData = response_data.response_package.response_data[0];
							var historyDetail = historyData.detail;

							$("#pegawai-faktur").html("Diterima Oleh : " + historyData.pegawai.nama);
							$("#tanggal-faktur").html("Tanggal Bayar : " + historyData.tanggal_bayar);
							$("#keterangan-faktur").html(historyData.keterangan);
							$("#total-faktur").html(number_format(historyData.terbayar, 2, ".", ","));
							$("#diskon-faktur").html(0);
							$("#grand-total-faktur").html(number_format(historyData.terbayar, 2, ".", ","));
							for(var historyKey in historyDetail) {
								$("#invoice_detail_history tbody").append(
									"<tr>" +
										"<td>" + ((historyDetail[historyKey].status == "P") ? "<input type=\"checkbox\" class=\"returItem\" value=\"" + historyDetail[historyKey].item_uid + "\" />" : "<i class=\"fa fa-times text-danger\"></i>") + "</td>" +
										"<td>" + (parseInt(historyKey) + 1)+ "</td>" +
										"<td>" + historyDetail[historyKey].item + "</td>" +
										"<td>" + historyDetail[historyKey].qty + "</td>" +
										"<td class=\"text-right\">" + number_format(historyDetail[historyKey].harga, 2, ".", ",") + "</td>" +
										"<td class=\"text-right\">" + number_format(historyDetail[historyKey].subtotal, 2, ".", ",") + "</td>" +
									"</tr>"
								);
							}
						}
					});
				}
			});
		});

		/*$("body").on("click", ".btn-retur-pembayaran", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			//Return Biaya
			var conf = confirm("Return Biaya ?");
			if(conf) {
				$.ajax({
					url: __HOSTAPI__ + "/Invoice",
					type: "POST",
					data:{
						request: "retur_biaya",
						item:uid,
						invoice:selectedUID
					},
					success: function(response) {
						var responseData = response.response_package.response_result;
					}
				});
			}
			return false;
		});*/

		$("#btnProsesRetur").click(function() {
            Swal.fire({
                title: "Retur Transaksi?",
                showDenyButton: true,
                type: "warning",
                confirmButtonText: "Ya",
                confirmButtonColor: "#1297fb",
                denyButtonText: "Tidak",
                denyButtonColor: "#ff2a2a"
            }).then((result) => {
                if (result.isConfirmed) {
                    var itemList = [];
                    $(".returItem").each(function(e){
                        var item = $(this).val();
                        itemList.push(item);
                    });

                    if(itemList.length > 0) {
                        $.ajax({
                            url: __HOSTAPI__ + "/Invoice",
                            type: "POST",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            data:{
                                request: "retur_biaya",
                                item:itemList,
                                invoice:selectedUID,
                                payment:selectedUIDKwitansi
                            },
                            success: function(response) {
                                var resultCheck = 0;

                                for(var returnKey in response.response_package) {
                                    resultCheck += response.response_package[returnKey].response_result
                                }

                                if(resultCheck == $(".returItem:checked").length) {
                                    $("#form-payment-detail").modal("hide");
                                }
                                $("#form-payment-detail").modal("hide");
                                tableKwitansi.ajax.reload();

                                push_socket(__ME__, "retur_barhasil", "*", "Tidak jadi berobat", "info").then(function() {
                                    //
                                });
                            }
                        });
                    } else {
                        alert("Pilih item yang akan diretur");
                    }
                }
            });
			return false;
		});
	});
</script>
<div id="form-invoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="nomor-invoice"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="form-loader">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Kembali</button>
			</div>
		</div>
	</div>
</div>



<div id="form-payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Buka Faktur Pembayaran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="payment-loader">
				
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnBayar"><i class="fa fa-check"></i> Proses</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Kembali</button>
			</div>
		</div>
	</div>
</div>



<div id="form-payment-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="nomor-faktur"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="payment-detail-loader">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Kembali</button>
				<button type="button" class="btn btn-warning" id="btnProsesRetur"><i class="fa fa-database"></i> Proses Retur</button>
				<button type="button" class="btn btn-success" id="btnCetakFaktur"><i class="fa fa-print"></i> Cetak Faktur</button>
			</div>
		</div>
	</div>
</div>