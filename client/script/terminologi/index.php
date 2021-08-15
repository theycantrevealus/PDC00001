<script type="text/javascript">
	$(function(){
		var tableTerminologi = $("#table-terminologi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Terminologi/terminologi",
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
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<a href=\"" + __HOSTNAME__ + "/terminologi/child/" + row["id"] + "\">" + row["nama"] + "</a>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/terminologi/child/" + row["id"] + "\" class=\"btn btn-info btn-sm\" data-toggle='tooltip' title='Tampil Term Items'>" +
										"<span><i class=\"fa fa-list\"></i>Detail</span>" +
									"</a>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-terminologi", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus terminologi?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi/terminologi/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(resp) {
						tableTerminologi.ajax.reload();
					}
				});
			}
		});
	});
</script>