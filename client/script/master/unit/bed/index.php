<script type="text/javascript">
	$(function(){
        loadLantai();
	    loadRuangan($("#lantai").val());


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
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
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
                        return "<span id=\"tarif_" + row["uid"] + "\" data-uid=\""+ row['uid_lantai'] +"\">" + number_format(parseFloat(row.tarif), 2, ".", ",") + "</span>";
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-bed\" id=\"bed_edit_" + row["uid"] + "\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"bed_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-bed\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
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

		$("#txt_tarif").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            digitsOptional: true
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

			var lantai = $("#lantai_" + uid).data("uid");
			$("#lantai").val(lantai).trigger('change');

            var ruangan = $("#ruangan_" + uid).data("uid");
            $("#ruangan").val(ruangan).trigger('change');

            $("#txt_tarif").val($("#tarif_" + uid).html());

			$("#form-tambah").modal("show");
			return false;
		});
		
		$("#tambah-bed").click(function() {
			$("#txt_nama").val("");
			$("#ruangan").val("").trigger('change');

            $("#lantai").val("").trigger('change');

			$("#form-tambah").modal("show");
			MODE = "tambah";
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var ruangan = $("#ruangan").val();
            var lantai = $("#lantai").val();
            var tarif = $("#txt_tarif").inputmask("unmaskedvalue");

			if(nama != "" && ruangan != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_bed",
						"nama": nama,
                        "lantai": lantai,
						"ruangan": ruangan,
                        "tarif": tarif
					};
				} else {
					form_data = {
						"request": "edit_bed",
						"uid": selectedUID,
						"nama": nama,
                        "lantai": lantai,
						"ruangan": ruangan,
                        "tarif": tarif
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
					    var result = response.response_package.response_result;
					    if(result > 0) {
                            $("#txt_nama").val("");
                            $("#ruangan").val("");
                            $("#ruangan").trigger('change');
                            $("#form-tambah").modal("hide");
                            tableBed.ajax.reload();
                        }
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$(".ruangan, .lantai").select2({
    		dropdownParent: $("#form-tambah")
		});

	});

	$("body").on("change", "#lantai", function() {
	    loadRuangan($(this).val());
    });

	function loadRuangan(lantai){
        $.ajax({
            url:__HOSTAPI__ + "/Ruangan/ruangan-lantai/" + lantai,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                console.log(response);
                var MetaData = response.response_package.response_data;
                $("#ruangan option").remove();
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

    function loadLantai(){
        $.ajax({
            url:__HOSTAPI__ + "/Lantai",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                    $("#lantai").append(selection);
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
                        <label for="ruangan">Lantai:</label>
                        <select class="form-control lantai" id="lantai">
                            <option value="" disabled selected>Pilih Lantai</option>
                        </select>
                    </div>
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
                    <div class="form-group col-md-12">
                        <label for="txt_nama">Tarif/Hari:</label>
                        <input type="text" class="form-control" id="txt_tarif" />
                    </div>
				</div>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
				<button type="button" class="btn btn-primary" id="btnSubmit">
                    <span>
                        <i class="fa fa-check"></i> Submit
                    </span>
                </button>
			</div>
		</div>
	</div>
</div>