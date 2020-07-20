<script type="text/javascript">
	$(function(){
		var usage_update = {},
            usage_delete = {},
           	usage_add = {},
            dataObj = {};

        var selectedID;
		var id_term = __PAGES__[2];
		var termData = loadDetailTerm(id_term);

		$("#txt_nama_term").val(termData.nama);

        var tableTerminologiItem = $("#table-term-usage").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
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
						return "<input type='text' class='form-control usage_update' id='usage_"+ row["id"] +
                        "' value='"+ row["nama"] +"' />";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"delete_" + row['id'] + "\" class=\"btn btn-danger btn-sm btn-delete-term-item\">" +
										"<i class=\"fa fa-trash\"></i>" +
									"</a>" +
								"</div>";
					}
				}
			]
        })

        $(".title-term").html(termData.nama);

        $("#tambah-item").click(function() {
			$("#txt_nama_item").val("");
			$("#form-tambah").modal("show");

			MODE = "tambah";
		});

		$("#table-term-usage tbody").on('focusout', '.usage_update', function(){
			var nama = $(this).val();
			
			if (nama != ""){
				var id = $(this).attr("id").split("_");
				id = id[id.length - 1];

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Terminologi/edit-terminologi-items/" + id,
					data: {
						"request": "edit-terminologi-item",
						"id": id,
						"nama": nama,
						"id_term": id_term
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama_term").val(termData.nama);
						tableTerminologiItem.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("#table-term-usage tbody").on("click", ".btn-delete-term-item", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus terminologi item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi/terminologi-item/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(resp) {
						tableTerminologiItem.ajax.reload();
					}
				});
			}

			return false;
		});

		$("#btnSubmitItem").click(function() {
			var nama = $("#txt_nama_item").val();

			if (nama != ""){
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Terminologi",
					data: {
						"request": "tambah-terminologi-item",
						"nama": nama,
						"id_term": id_term
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama_item").val("");
						$("#form-tambah").modal("hide");
						tableTerminologiItem.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama_term").val();

			if(nama != "") {

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Terminologi",
					data: {
						"request": "edit-terminologi",
						"id": id_term,
						"nama": nama
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						 location.href = __HOSTNAME__ + "/terminologi";
					},
					error: function(response) {
						console.log(response);
					}
				});
			}

			return false;
		});
	});

	function loadDetailTerm(params){
		var dataTerm = null;

		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Terminologi/terminologi-detail/" + params,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type: "GET",
			success: function(response){
				dataTerm = response.response_package.response_data[0];
			},
			error: function(response) {
				console.log(response);
			}
		});

		return dataTerm;
	}

	function loadUsage(params){
        var dataUsage = null;

		$.ajax({
            async: false,
			url: __HOSTAPI__ + "/Terminologi/terminologi-items/" + params,
			type: "GET",
			beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                dataUsage = response.response_package.response_data;
            },
            error: function(response) {
                console.log(response);
            }
		})

        return dataUsage;
    }

    function deleteUsage(params){
        var dataUsage = null;

		$.ajax({
            async: false,
			url: __HOSTAPI__ + "/Terminologi/terminologi-items/" + params,
			type: "DELETE",
			beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                dataUsage = response.response_package.response_data;
            },
            error: function(response) {
                console.log(response);
            }
		})

        return dataUsage;
    }
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title"> <span class="title-form"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Nama Terminologi Item :</label>
					<input type="text" class="form-control" id="txt_nama_item" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitItem">Submit</button>
			</div>
		</div>
	</div>
</div>