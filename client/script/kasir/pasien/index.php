<script type="text/javascript">
	$(function(){

		var tableAntrianBayar = $("#table-biaya-pasien").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Invoice",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					console.log(response);
					var returnedData = [];
					for(var InvKeyData in response.response_package.response_data) {
						if(response.response_package.response_data[InvKeyData].antrian_kunjungan != undefined) {
							if(!response.response_package.response_data[InvKeyData].lunas) {
								returnedData.push(response.response_package.response_data[InvKeyData]);
							}
						}
					}
					return returnedData;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.nomor_invoice;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pasien.no_rm + "<br /><b>" + row.pasien.panggilan_name.nama + " " + row.pasien.nama + "</b>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.antrian_kunjungan.poli.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.antrian_kunjungan.pegawai.nama + " di <b>" + row.antrian_kunjungan.loket.nama_loket + "</b>";
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

		var selectedUID;
		var selectedPoli;
		var selectedPasien;
		var selectedPenjamin;
		var selectedKunjungan;
		var totalItemPay = 0;
		var totalItemPayDiscount = 0;
		var currentPasienName;
		var itemMeta = [];


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
					console.log(uid);
					$.ajax({
						url:__HOSTAPI__ + "/Invoice/detail/" + uid,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type:"GET",
						success:function(response_data) {
							console.log(response_data);
							var invoice_detail = response_data.response_package.response_data[0];
							$("#nama-pasien").html(invoice_detail.pasien.panggilan_name.nama + " " + invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]");
							currentPasienName = invoice_detail.pasien.panggilan_name.nama + " " + invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]";
							$("#nomor-invoice").html(invoice_detail.nomor_invoice);
							var invoice_detail_item = invoice_detail.invoice_detail;
							for(var invKey in invoice_detail_item) {
								console.log(invoice_detail_item[invKey]);
								var status_bayar = "";
								if(invoice_detail_item[invKey].status_bayar == 'N') {
									status_bayar = "<input item-id=\"" + invoice_detail_item[invKey].id + "\" value=\"" + invoice_detail_item[invKey].subtotal + "\" type=\"checkbox\" class=\"proceedInvoice\" />";
								} else {
									if(invoice_detail_item[invKey].item.allow_retur == true) {
										if(invoice_detail_item[invKey].status_berobat.status == "N") {
											status_bayar = "<button class=\"btn btn-info btn-sm btn-retur-pembayaran\" id=\"retur_pembayaran_" + invoice_detail_item[invKey].item.uid + "\">Retur</button>";
										} else {
											status_bayar = "<span class=\"text-success\"><i class=\"fa fa-check\"></i> Lunas</span>";
										}
									} else {
										status_bayar = "<span class=\"text-success\"><i class=\"fa fa-check\"></i> Lunas</span>";
									}
								}
								$("#invoice_detail_item").append(
									"<tr>" +
										"<td>" + status_bayar + "</td>" +
										"<td>" + invoice_detail_item[invKey].autonum + "</td>" +
										"<td>" + invoice_detail_item[invKey].item.nama + " <span style=\"margin-left: 50px;\" class=\"badge badge-info\">" + invoice_detail_item[invKey].penjamin.nama + "</span></td>" +
										"<td>" + invoice_detail_item[invKey].qty + "</td>" +
										"<td class=\"text-right\">" + number_format(invoice_detail_item[invKey].harga, 2, ".", ",") + "</td>" +
										"<td class=\"text-right\">" + number_format(invoice_detail_item[invKey].subtotal, 2, ".", ",") + "</td>" +
									"</tr>"
								);

								itemMeta = invoice_detail_item;
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
			var conf = confirm("Proses pembayaran ?");
			if(conf) {

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
						console.log(response);
						if(response.response_package.response_result > 0) {
							tableAntrianBayar.ajax.reload();
							$("#form-invoice").modal("hide");
							$("#form-payment").modal("hide");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
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

		$("body").on("click", ".btn-retur-pembayaran", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			//Return Biaya
			var conf = confirm("Return Biaya ?");
			if(conf) {
				$.ajax({
					url: __HOSTNAME__ + "/pages/kasir/pasien/payment_detail.php",
					type: "POST",
					data:{
						request: "retur_biaya",
						item:uid,
						invoice:
					},
					success: function(response) {
						var responseData = response.response_package.response_result;
					}
				});
			}
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
				<button type="button" class="btn btn-success" id="btnBayar"><i class="fa fa-print"></i> Cetak Faktur</button>
			</div>
		</div>
	</div>
</div>