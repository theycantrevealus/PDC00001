<script type="text/javascript">
	$(function(){

		//var MODE = "tambah", selectedUID;
		var tableAntrianBayar = $("#table-antrian-biaya-konsul").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Kasir/konsul-dokter",
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
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['no_rm'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['pasien'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['poli'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['pegawai'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						var	number_string = row['harga'].toString(),
							sisa 	= number_string.length % 3,
							rupiah 	= number_string.substr(0, sisa),
							ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
								
						if (ribuan) {
							separator = sisa ? ',' : '';
							rupiah += separator + ribuan.join(',');
						}

						return '<span class="harga">' + rupiah + '</span>';
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button data-pasien='"+ row['uid_pasien'] +"' data-kunjungan=\"" + row['uid_kunjungan'] + "\" data-poli='"+ row['uid_poli'] + "' data-dokter='"+ row['uid_dokter'] +"' data-penjamin='"+ row['uid_penjamin'] +"' class=\"btn btn-success btn-sm btn-proses\" data-toggle='tooltip' title='Selesai Bayar'>" +
									 	"<i class=\"fa fa-check\"></i>" +
									"</button>" +
									/*"<button id=\"antrian_delete_" + row['uid_kunjungan'] + "\" class=\"btn btn-danger btn-sm btn-delete-antrian\" data-toggle='tooltip' title='Hapus'>" +
										"<i class=\"fas fa-window-close\"></i>" +
									"</button>" +*/
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-antrian", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus pasien?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Pasien/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tablePasien.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("#table-antrian-biaya-konsul tbody").on('click','.btn-proses', function(){
			var uid_kunjungan = $(this).data("kunjungan");
			var uid_poli = $(this).data("poli");
			var uid_dokter = $(this).data("dokter");
			var uid_pasien = $(this).data("pasien");
			var uid_penjamin = $(this).data("penjamin");

			var conf = confirm("Sudah dibayar?");
			if(conf) {
				$.ajax({
					url: __HOSTAPI__ + "/Kasir",
					data: {
						'request': 'konsul-dokter',
						'kunjungan': uid_kunjungan,
						'poli' : uid_poli,
						'dokter' : uid_dokter,
						'pasien' : uid_pasien,
						'penjamin' : uid_penjamin
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						//console.log(response);
						tableAntrianBayar.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
	});
</script>
