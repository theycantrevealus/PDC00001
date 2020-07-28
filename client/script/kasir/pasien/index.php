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
					return response.response_package.response_data;
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
						return 	"<button class=\"btn btn-info btn-sm btnDetail\" id=\"invoice_" + row.uid + "\"><i class=\"fa fa-eye\"></i></button>";
					}
				}
			]
		});

		var selectedUID;

		$("body").on("click", ".btnDetail", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			selectedUID = uid;

			$.ajax({
				url: __HOSTNAME__ + "/pages/kasir/pasien/form.php",
				type: "POST",
				success: function(response) {
					$("#form-loader").html(response);
					$("#txt_diskon_type_all").select2();
					$("#txt_diskon_all").inputmask({
						alias: 'currency',
						rightAlign: true,
						placeholder: "0.00",
						prefix: "",
						autoGroup: false,
						digitsOptional: true
					});

					$.ajax({
						url:__HOSTAPI__ + "/Invoice/detail/" + uid,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type:"GET",
						success:function(response_data) {
							var invoice_detail = response_data.response_package.response_data[0];
							$("#nama-pasien").html(invoice_detail.pasien.panggilan_name.nama + " " + invoice_detail.pasien.nama + " [<span class=\"text-info\">" + invoice_detail.pasien.no_rm + "</span>]");
							$("#nomor-invoice").html(invoice_detail.nomor_invoice);
							var invoice_detail_item = invoice_detail.invoice_detail;
							for(var invKey in invoice_detail_item) {
								$("#invoice_detail_item").append(
									"<tr>" +
										"<td><input item-id=\"" + invoice_detail_item[invKey].id + "\" value=\"" + invoice_detail_item[invKey].subtotal + "\" type=\"checkbox\" class=\"proceedInvoice\" /></td>" +
										"<td>" + invoice_detail_item[invKey].autonum + "</td>" +
										"<td>" + invoice_detail_item[invKey].item.nama + "</td>" +
										"<td>" + invoice_detail_item[invKey].qty + "</td>" +
										"<td class=\"text-right\">" + number_format(invoice_detail_item[invKey].harga, 2, ".", ",") + "</td>" +
										"<td class=\"text-right\">" + number_format(invoice_detail_item[invKey].subtotal, 2, ".", ",") + "</td>" +
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

		var totalItemPay = 0;
		var totalItemPayDiscount = 0;
		
		$("body").on("change", ".proceedInvoice", function() {
			var diskonAll = $("#txt_diskon_all").val();
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

			$("#text-grand-total").html(number_format(totalItemPayDiscount, 2, ".", ","));
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

		$("#btnBayar").click(function() {
			$(".proceedInvoice").each(function() {
				var id = $(this).attr("item-id");
				if($(this).is(":checked")) {
					if(selectedPay.indexOf(id) < 0) {
						selectedPay.push(id);
					}
				}
			});

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
						metode: "CASH"
					},
					success:function(response) {
						console.log(response);
						if(response.response_package.response_result > 0) {
							tableAntrianBayar.ajax.reload();
							$("#form-invoice").modal("hide");
						}
					},
					error: function(response) {
						console.log("Error : " + response);
					}
				});
			}
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
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-success" id="btnBayar">Buka Faktur</button>
			</div>
		</div>
	</div>
</div>