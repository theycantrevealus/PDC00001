<script type="text/javascript">
	$(function(){
		var antrian_count = 0;

		var tableAntrianPerawat = $("#table-antrian-perawat").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Antrian/antrian",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					antrian_count = response.response_package.response_result;
					return response.response_package.response_data;
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
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									/*"<button id=\"penjamin_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-antrian\">" +
										"<i class=\"fa fa-trash\"></i>" +
									"</button>" +*/
									"<a href='"+ __HOSTNAME__ +"/rawat_jalan/perawat/tambah/"+ row['uid'] +"' class='btn btn-info btn-sm' data-toggle='tooltip' title='Isi Assesmen Pasien'><i class='fa fa-address-card'></i></a>"
								"</div>";
					}
				}
			]
		});

		$("#jlh-antrian").html(antrian_count);

	});


</script>