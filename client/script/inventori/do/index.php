<script type="text/javascript">
	$(function(){

		var tablePo = $("#table-po").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/PO",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					//check barang sudah sampai semua atau belum
					var poData = response.response_package.response_data;
					for(var CPOKey in poData) {
						if(poData[CPOKey].supplier == undefined || poData[CPOKey].supplier == null) {
							poData[a].supplier = {
								nama: "No Data"
							};
						}

						if(poData[CPOKey].pegawai == undefined || poData[CPOKey].pegawai == null) {
							poData[CPOKey].pegawai = {
								nama: "No Data"
							};
						}
						
						//Check Item
						var poItem = poData[CPOKey].detail;
						for(var itemKey in poItem) {
							if(poItem[itemKey].sampai >= poItem[itemKey].qty) {
								poItem.splice(itemKey, 1);
							}
						}
						if(poItem.length == 0) {
							poData.splice(CPOKey, 1);
						}
					}
					return poData;
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
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.nomor_po;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.supplier.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<a href=\"" + __HOSTNAME__ + "/inventori/do/tambah/" + row.uid + "\" class=\"btn btn-info btn-sm btn-detail\"><i class=\"fa fa-box-open\"></i></a>";
					}
				},
			]
		});








		var tableDo = $("#table-do").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/DeliveryOrder",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var data = response.response_package.response_data;
					for(var a = 0; a < data.length; a++) {
						if(data[a].supplier == undefined || data[a].supplier == null) {
							data[a].supplier = {
								nama: "No Data"
							};
						}

						if(data[a].pegawai == undefined || data[a].pegawai == null) {
							data[a].pegawai = {
								nama: "No Data"
							};
						}
					}
					return data;
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
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.tgl_do;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.no_do;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.supplier.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.no_invoice;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.status;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<button class=\"btn btn-info btn-sm btn-detail\"><i class=\"fa fa-eye\"></i></button>";
					}
				},
			]
		});

		$("body").on("click", ".btn-delete-penjamin", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus penjamin item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Penjamin/master_penjamin/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tablePenjamin.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		
		$("body").on("click", ".btn-edit-penjamin", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			return false;
		});

		
		$("#tambah-penjamin").click(function() {
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";

		});


		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_penjamin",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_penjamin",
						"uid": selectedUID,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Penjamin",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tablePenjamin.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Penjamin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Nama Penjamin :</label>
					<input type="text" class="form-control" id="txt_nama" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>