<script type="text/javascript">
	$(function(){
		var tablePegawai = $("#table-pegawai").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Pegawai",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					return response.response_package;
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
						return 	"<div class=\"row\">" +
									"<div class=\"col-md-1 text-center\">" +
										"<img width=\"40\" src=\"" + __HOST__ + row.profile_pic + "\" class=\"rounded-circle img-responsive\" alt=\"" + row["nama"] + "\" />" +
									"</div>" +
									"<div class=\"col-md-11\">" +
										"<h5>" + row["nama"] + "</h5>" +
										"<a href=\"mailto:\"" + row["email"] + "\">" + row["email"] + "<small>(send mail)</small></a>" +
									"</div>" +
								"</div>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/pegawai/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm\">" +
										"<i class=\"fa fa-pencil\"></i> Edit" +
									"</a>" +
									"<button id=\"delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-pegawai\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-pegawai", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus pegawai?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Pegawai/pegawai/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(resp) {
						tablePegawai.ajax.reload();
					}
				});
			}
		});
	});
</script>