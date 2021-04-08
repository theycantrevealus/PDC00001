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
						return 	"<button class=\"btn btn-info btn-sm btnDetailKwitansi\" invoice_payment=\"" + row.uid + "\" invoice=\"" + row.invoice + "\" id=\"invoice_" + row.uid + "\"><i class=\"fa fa-eye\"></i></button>";
					}
				}
			]
		});

		$("#btnCetakFaktur").click(function() {
		    var data = $("#payment-detail-loader").html();
		    
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
                    kwitansi_data: $("#payment-detail-loader").html(),
                    pasien: $("#payment-detail-loader .info-kwitansi col-4:eq(1)").html(),
                    pegawai: $("#payment-detail-loader .info-kwitansi col-4:eq(2)").html(),
                    tgl_bayar: $("#payment-detail-loader .info-kwitansi col-4:eq(3)").html()
                },
                success: function (response) {
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
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
                                $("#nama-pasien-faktur").html(pasienInfo.panggilan_name.nama + " " + pasienInfo.nama + " [<span class=\"text-info\">" + pasienInfo.no_rm + "</span>]");
                            } else {
                                $("#nama-pasien-faktur").html(pasienInfo.nama + " [<span class=\"text-info\">" + pasienInfo.no_rm + "</span>]");
                            }

							$("#nomor-faktur").html($("#kwitansi_" + uid).html());

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
										"<td>" + ((historyDetail[historyKey].status == "P") ? ((historyDetail[historyKey].allow_retur) ? "<input type=\"checkbox\" class=\"returItem\" value=\"" + historyDetail[historyKey].item_uid + "\" />" : "<i class=\"fa fa-exclamation-circle text-warning\"></i>") : "<i class=\"fa fa-times text-danger\"></i>") + "</td>" +
										"<td>" + (parseInt(historyKey) + 1)+ "</td>" +
										"<td>" + historyDetail[historyKey].item.toUpperCase() + "</td>" +
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

		$("#range_kwitansi").change(function() {
			tableKwitansi.ajax.reload();
		});

		
		var tableAntrianBayarRJ = $("#table-biaya-pasien-rj").DataTable({
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
						return row.antrian_kunjungan.poli.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						//return row.antrian_kunjungan.pegawai.nama + " di <b>" + row.antrian_kunjungan.loket.nama_loket + "</b>";
                        return row.antrian_kunjungan.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span style=\"display: block\" class=\"text-right\">" + number_format(row.total_after_discount, 2, ".", ",") + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return 	"<button class=\"btn btn-info btn-sm btnDetail\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\"><i class=\"fa fa-eye\"></i></button>";
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
                        return "<span style=\"white-space: pre\">" + row.nomor_invoice + "</span>";
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
                        return row.antrian_kunjungan.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return row.antrian_kunjungan.pegawai.nama + " di <b>" + row.antrian_kunjungan.loket.nama_loket + "</b>";
                        return row.antrian_kunjungan.pegawai.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"text-right\">" + number_format(row.total_after_discount, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return 	"<button class=\"btn btn-info btn-sm btnDetail\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\"><i class=\"fa fa-eye\"></i></button>";
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
                        return "<span style=\"white-space: pre\">" + row.nomor_invoice + "</span>";
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
                        return row.antrian_kunjungan.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return row.antrian_kunjungan.pegawai.nama + " di <b>" + row.antrian_kunjungan.loket.nama_loket + "</b>";
                        return row.antrian_kunjungan.pegawai.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"text-right\">" + number_format(row.total_after_discount, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return 	"<button class=\"btn btn-info btn-sm btnDetail\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\"><i class=\"fa fa-eye\"></i></button>";
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

			var poli = $(this).attr("poli");
			var pasien = $(this).attr("pasien");
			var penjamin = $(this).attr("penjamin");
			var kunjungan = $(this).attr("kunjungan");

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
                                }
                            };

							var item_grouper = {};
							//console.log(invoice_detail_item);
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
                                        "<td>" + detailData[itemDetailKey].nama + " <span style=\"float: right; margin-right: 50px;\" class=\"badge badge-info\">" + detailData[itemDetailKey].penjamin + "</span></td>" +
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
										"<td><button class=\"btn btn-sm btn-info btnDetailPayment\" id=\"payment_" + history_payment[hisKey].uid + "\"><i class=\"fa fa-eye\"></i></button></td>" +
									"</tr>"
								);
							}

							$("#form-invoice").modal("show");
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
                denyButtonColor: `#ff2a2a`
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
                                Swal.fire(
                                    "Pembayaran Berhasil!",
                                    response.response_package.response_message,
                                    "success"
                                ).then((result) => {
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
                                            console.log("pushed!");
                                        });
                                    }

                                });
                            } else {
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
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Kembali</button>
				<button type="button" class="btn btn-success" id="btnBayar"><i class="fa fa-check"></i> Proses</button>
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