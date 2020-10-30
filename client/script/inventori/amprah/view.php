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
	});
</script>