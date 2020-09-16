<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		
		var tableTindakan = $("#table-tindakan").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Tindakan/rawat-jalan",
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
						return "<span id='harga_"+ row['uid'] +"' class='separated_comma'>" + row['harga'] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\" data-harga='"+ row['harga'] +"'>" +
										"<i class=\"fa fa-edit\"></i> Edit" +
									"</button>" +
									"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		//make comma in datatable
		$(".separated_comma").digits();

		$("body").on("click", ".btn-delete-tindakan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus tindakan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Tindakan/master_tindakan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableTindakan.ajax.reload();
						$(".separated_comma").digits();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		
		$("body").on("click", ".btn-edit-tindakan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";

			let harga = $(this).data('harga');

			$("#txt_nama").val($("#nama_" + selectedUID).html());
			$("#txt_harga").val(harga);
			
			$("#form-tambah").modal("show");
			return false;
		});

		
		$("#tambah-tindakan").click(function() {
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";

			$(".harga").val("");
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var harga = $("#txt_harga").inputmask("unmaskedvalue");
			
			if(nama != "" && harga != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_tindakan_rawat_jalan",
						"nama": nama,
						"harga": harga
					};
				} else {
					form_data = {
						"request": "edit_tindakan_rawat_jalan",
						"uid": selectedUID,
						"nama": nama,
						"harga": harga
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Tindakan",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						console.log(response);
						$("#txt_nama").val("");
						$("#txt_harga").val("");
						$("#form-tambah").modal("hide");
						tableTindakan.ajax.reload();
						$(".separated_comma").digits();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
		

		$(".harga").inputmask({alias: 'currency', rightAlign: false, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});
	});

	//function for create digits
	$.fn.digits = function(){ 
        return this.each(function(){ 
            $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
        })
    }
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Tindakan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_nama">Nama Tindakan :</label>
					<input type="text" class="form-control" id="txt_nama" />
				</div>
				<div class="form-group col-md-12">
					<label for="txt_harga">Harga :</label>
					<input type="text" class="form-control harga" id="txt_harga" />
				</div>
				<!-- <hr /> -->
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>