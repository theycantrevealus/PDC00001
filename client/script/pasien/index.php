<script type="text/javascript">
	$(function(){

		var MODE = "tambah", selectedUID;
		var tablePasien = $("#table-pasien").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Pasien/pasien",
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
						return "<span id=\"norm_" + row["uid"] + "\">" + row["no_rm"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tgllahir_" + row["uid"] + "\">" + row["tanggal_lahir"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"jenkel_" + row["uid"] + "\">" + row["jenkel"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tgldaftar_" + row["uid"] + "\">" + row["tgl_daftar"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									/*"<button id=\"poli_view_" + row['uid'] + "\" class=\"btn btn-warning btn-sm btn-detail-poli\">" +
									 	"<i class=\"fa fa-list\"></i> Detail" +
									"</button>" +*/
									"<a href=\"" + __HOSTNAME__ + "/master/pasien/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-pasien\">" +
										"<i class=\"fa fa-edit\"></i> Edit" +
									"</a>" +
									"<button id=\"pasien_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-poli\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-pasien", function(){
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



		$("#table-pasien tbody").on('click', '.btn-detail-pasien', function(){
            
        });

	});
	

</script>


<div id="view-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tindakan dari : <span id="title-tindakan"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered" id="table-view-tindakan">
					<thead>
						
					</thead>

					<tbody>
						
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>