<div id="cari-pasien" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Pasien</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Cari Pasien:</label>
					<input type="text" class="form-control" id="txt_pasien" placeholder="Cari Nama / KTP / No. RM Pasien" />
				</div>
				<div class="col-md-12">
					<table class="table table-bordered" id="table_cari_pasien">
						<thead>
							<tr>
								<th style="width: 20px;">No</th>
								<th>No. RM</th>
								<th>Pasien</th>
								<th>Jenis Kelamin</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {

		var tableGudang = $("#table_cari_pasien").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Inventori/gudang",
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
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-gudang\" id=\"gudang_edit_" + row["uid"] + "\">" +
										"<i class=\"fa fa-input\"></i> Edit" +
									"</button>" +
									"<button id=\"gudang_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-gudang\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$('#cari-pasien').on('shown.bs.modal', function () {
			$('#txt_pasien').focus();
		});
		$("body").on("keyup", function(e) {
			var pressed = e.keyChar||e.which;

			if(pressed == 13) {
				$("#cari-pasien").modal("show").finish(function(){
					alert();
				});;
			} else if(pressed ==27) {
				$("#cari-pasien").modal("hide");
			}
		});
		$("#tambah-igd").click(function(){
			$("#cari-pasien").modal("show");
		});
	});
</script>

