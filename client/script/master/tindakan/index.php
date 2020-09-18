<script type="text/javascript">
	$(function(){

		var MODE = "tambah", selectedUID;
		var tableTindakan = $("#table-tindakan").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					console.log(response);
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
						return "<span class='separated_comma'>" + row['harga']['1']['harga_tindakan'] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class='separated_comma'>" + row['harga']['2']['harga_tindakan'] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class='separated_comma'>" + row['harga']['3']['harga_tindakan'] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class='separated_comma'>" + row['harga']['4']['harga_tindakan'] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\">" +
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

			$("#txt_nama").val($("#nama_" + uid).html());
			loadEditHargaTindakan(selectedUID);
			
			$("#form-tambah").modal("show");
			return false;
		});

		
		$("#tambah-tindakan").click(function() {
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";

			$(".harga").val("");
		});

		$("#table_harga tbody").on('keyup','.urutan_1', function(){
			let harga_dasar = $(this).inputmask("unmaskedvalue");

			if (harga_dasar != ""){
				harga_dasar = parseFloat(harga_dasar);

				//auto isi kelas 2, 1, dan vip
				let kelas_2 = (harga_dasar * 0.25) + harga_dasar;
				let kelas_1 = (harga_dasar * 0.5) + harga_dasar;
				let vip = (harga_dasar * 0.875) + harga_dasar;

				$(".urutan_2").val(kelas_2);	//urutan diambil dari database
				$(".urutan_3").val(kelas_1);
				$(".urutan_4").val(vip);
			}
		});

		//function for count object length
		Object.size = function(obj) {
		    var size = 0, key;
		    for (key in obj) {
		        if (obj.hasOwnProperty(key)) size++;
		    }
		    return size;
		};

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var list_harga = {};

			//get all harga with kelas
			$(".harga").each(function(){
				let harga = $(this).inputmask("unmaskedvalue");
				let uid = $(this).attr("id").split("_");
				uid = uid[uid.length - 1];

				//console.log(harga);
				if (harga === ''){
					harga = 0;
				}

				list_harga[uid] = harga;
			});

			//let list_harga_size = Object.size(list_harga);
			
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_tindakan",
						"nama": nama,
						"harga": list_harga
					};
				} else {
					form_data = {
						"request": "edit_tindakan",
						"uid": selectedUID,
						"nama": nama,
						"harga": list_harga
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
		
		loadKelasTindakan();
	});

	function loadKelasTindakan(){
		$("#table_harga tbody").empty();

		$.ajax({
            url:__HOSTAPI__ + "/Tindakan/get-kelas",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                	var html;

                	$(MetaData).each(function(key, item){
                		html = "<tr>"+ 
                					"<td>"+ item.nama + "</td>" +			//class urutan, nomor urutan diambil dari db
                					"<td><input class='form-control required harga urutan_"+ item.urutan +"' id='kelas_"+ item.uid +"' /></td>" +
                				"</tr>";

                		$("#table_harga tbody").append(html);
                	});

                	//apply input mask
					$(".harga").inputmask({alias: 'currency', rightAlign: true, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

	//for load price of tindakan
	function loadEditHargaTindakan(params){	//uid_tindakan
		$.ajax({
            url:__HOSTAPI__ + "/Tindakan/get-harga-tindakan/" + params,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                	var html;

                	$(MetaData).each(function(key, item){
                		$("#kelas_" + item.kelas).val(item.harga);
                	});
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

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
					<label for="txt_no_skp">Nama Tindakan :</label>
					<input type="text" class="form-control" id="txt_nama" />
				</div>
				<!-- <hr /> -->
				<div class="form-group col-md-12">
					<table class="table table-bordered table-striped" id="table_harga">
						<thead>
							<tr>
								<th colspan="2" style="text-align: center;">Tabel Harga</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>