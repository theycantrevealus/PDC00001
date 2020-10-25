<script type="text/javascript">
	$(function(){

		//var MODE = "tambah", selectedUID;
		var tableAntrianPoli = $("#table-antrian-poli").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Antrian/antrian",
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
						return row["no_rm"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["pasien"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["departemen"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["user_resepsionis"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return number_format(row["harga"].harga, 2, ".", ",");
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "";
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
