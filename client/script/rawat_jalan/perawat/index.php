<script type="text/javascript">
	$(function(){
		var antrian_count = 0;

		var tableAntrianPerawat = $("#table-antrian-perawat").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-rawat",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					antrian_count = response.response_package.length;
					console.log(response);
					return response.response_package;
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
						return row["dokter"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["penjamin"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["user_resepsionis"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						var button = "<a href='"+ __HOSTNAME__ +"/rawat_jalan/perawat/antrian/"+ row['uid'] +"' class='btn btn-info btn-sm' data-toggle='tooltip' title='Isi Assesmen Pasien'><i class='fa fa-address-card'></i></a>";

						if (row['status_asesmen'] === true){
							button = "<a href='"+ __HOSTNAME__ +"/rawat_jalan/perawat/antrian/"+ row['uid'] +"' class='btn btn-warning btn-sm' data-toggle='tooltip' title='Edit Assesmen Pasien'><i class='fa fa-address-card'></i></a>";
						}

						return "<div class=\"btn-group \" role=\"group\" aria-label=\"Basic example\">" + button + "</div>";
					}
				}
			],
			rowCallback: function (row, data) {
				if (data['status_assesmen'] === true){
					$(row).css({"background-color":"#E0F5E5","color":"#000"});
				}
			}
		});

		$("#jlh-antrian").html(antrian_count);

	});


</script>