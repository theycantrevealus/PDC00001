<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function() {
		$.ajax({
			url:__HOSTAPI__ + "/Inventori/get_amprah_detail/" + __PAGES__[3],
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				var data = response.response_package.response_data[0];
				console.log(data);
				$("#verif_kode").html(data.kode_amprah);
				$("#verif_nama").html(data.pegawai_detail.nama);
				$("#verif_unit").html(data.pegawai_detail.unit_detail.kode + " - " + data.pegawai_detail.unit_detail.nama);
				$("#verif_tanggal").html(data.tanggal);

				for(var key in data.amprah_detail) {
					$("#table-verifikasi tbody").append(
						"<tr>" +
							"<td>" + (parseInt(key) + 1) + "</td>" +
							"<td>" + data.amprah_detail[key].item.nama + "</td>" +
							"<td>" + data.amprah_detail[key].item.satuan_terkecil.nama + "</td>" +
							"<td class=\"number_style\">" + data.amprah_detail[key].jumlah + "</td>" +
						"</tr>"
					);
				}

				$("#verif_keterangan").html(data.keterangan);
			},
			error: function(response) {
				console.log(response);
			}
		});

		$("#btnCetak").click(function () {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/do_detail.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__: __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __NAMA_SAYA__ : __MY_NAME__,
                    __JUDUL__ : "Surat Bukti Amprah",
                    data: $("#hasil-amprah").html()

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
                        pageTitle: $("#verif_kode").html().replaceAll("/","_"),
                        afterPrint: function() {
                            $("#form-payment-detail").modal("hide");
                        }
                    });
                },
                error: function (response) {
                    //
                }
            });
        });
	});
</script>