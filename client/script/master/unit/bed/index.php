<script type="text/javascript">
	$(function(){
		loadRuangan();

		var MODE = "tambah", selectedUID;

		var groupColumn = 3;
		var tableBed = $("#table-bed").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Bed/bed",
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
						return "<span id=\"ruangan_" + row["uid"] + "\" data-uid=\""+ row['uid_ruangan'] +"\">" + row["ruangan"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"lantai_" + row["uid"] + "\" data-uid=\""+ row['uid_lantai'] +"\">" + row["lantai"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-bed\" id=\"bed_edit_" + row["uid"] + "\">" +
										"<i class=\"fa fa-edit\"></i> Edit" +
									"</button>" +
									"<button id=\"bed_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-bed\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			],
			/*"drawCallback": function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last = null;
	 
	            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq(i).before(
	                        '<tr class="group" style="background-color: #ddd"><td colspan="5">'+ group.lantai +'</td></tr>'
	                    );
	 
	                    last = group;
	                }
	            } );
	        }*/
		});

		$("body").on("click", ".btn-delete-bed", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus bed item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Bed/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableBed.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
		
		$("body").on("click", ".btn-edit-bed", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());

			var ruangan = $("#ruangan_" + uid).data("uid");
			$("#ruangan").val(ruangan);
			$("#ruangan").trigger('change');

			$("#form-tambah").modal("show");
			return false;
		});
		
		$("#tambah-bed").click(function() {
			$("#txt_nama").val("");
			$("#ruangan").val("");
			$("#ruangan").trigger('change');
			$("#form-tambah").modal("show");
			MODE = "tambah";
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var ruangan = $("#ruangan").val();

			if(nama != "" && ruangan != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_bed",
						"nama": nama,
						"ruangan": ruangan
					};
				} else {
					form_data = {
						"request": "edit_bed",
						"uid": selectedUID,
						"nama": nama,
						"ruangan": ruangan
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Bed",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
					    console.log(response);
						$("#txt_nama").val("");
						$("#ruangan").val("");
						$("#ruangan").trigger('change');
						$("#form-tambah").modal("hide");
						tableBed.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$(".ruangan").select2({
    		dropdownParent: $("#form-tambah")
		});

	});

	function loadRuangan(){
        $.ajax({
            url:__HOSTAPI__ + "/Ruangan/ruangan",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                    $("#ruangan").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Penjamin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="modal-body">
					<div class="form-group col-md-12">
						<label for="ruangan">Ruangan:</label>
						<select class="form-control ruangan" id="ruangan">
							<option value="" disabled selected>Pilih Ruangan</option>
						</select>
					</div>
					<div class="form-group col-md-12">
						<label for="txt_nama">Nama Bed:</label>
						<input type="text" class="form-control" id="txt_nama" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>