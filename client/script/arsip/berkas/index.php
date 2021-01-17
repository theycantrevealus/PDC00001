<script type="text/javascript">
	$(function(){
        loadKategori();

		var MODE = "tambah", selectedUID;

        $("#kategori").on('change', function(){
			var id = $(this).val();
            loadSubkategori(id);
		});

		var tableBerkas = $("#table-berkas").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Arsip/berkas",
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
						return row["created_at"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["id"] + "\">" + row["nama"] + "</span><br><span class='badge badge-warning'>"+row["nama_kategori"]+"</span>";
					}
				},
                {
					"data" : null, render: function(data, type, row, meta) {
                        return "<a href=\"" + __HOST__ + "/document/arsip/" + row["berkas"] + "\">#Berkas</a>";
					}
				},
                {
					"data" : null, render: function(data, type, row, meta) {
						return row["keterangan"];
					}
				},
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.lokasi_simpan;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-berkas\" id=\"berkas_edit_" + row["uid"] + "\">" +
										"<i class=\"fa fa-pencil\"></i> Edit" +
									"</button>" +
									"<button id=\"berkas_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-berkas\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-berkas", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus berkas Arsip?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Arsip",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
                    data: {
					    request: "hapus_berkas",
                        uid: uid
                    },
					type:"POST",
					success:function(response) {
					    console.clear();
						console.log(response);
						tableBerkas.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-berkas", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit berkas");
			return false;
		});

		$("#tambah-berkas").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Berkas");

		});

        $("#uploadDokumen").on("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var nama = $("#txt_nama").val();
            var kategori = $("#kategori").val();
            var subkategori = $("#subkategori").val();
            var lokasi = $("#txt_lokasi").val();
            var keterangan = $("#txt_keterangan").val();

            formData.append("request", "add_berkas");
            formData.append("nama", nama);
            formData.append("kategori", kategori);
            formData.append("subkategori", subkategori);
            formData.append("lokasi", lokasi);
            formData.append("keterangan", keterangan);

            if(nama != "") {
                $.ajax({
					url: __HOSTAPI__ + "/Arsip",
					data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						if(response.response_package.response_result > 0) {
                            $("#txt_nama").val("");
                            $("#txt_lokasi").val("");
                            $("#txt_keterangan").val("");
                            resetSelectBox("kategori", "Kategori");
                            resetSelectBox("subkategori", "Subkategori");
                            $("#form-tambah").modal("hide");
                            tableBerkas.ajax.reload();
                        } else {
						    console.log(response);
                        }
					},
					error: function(response) {
						console.log(response);
					}
				});
            }
            return false;
        });

		/*$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "add_berkas",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_berkas",
						"id": selectedUID,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Arsip/berkas",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						//console.log(response);
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableBerkas.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});*/

        $(".kategori, .subkategori").select2({
    		dropdownParent: $("#form-tambah")
		});
	});

    function loadKategori(selected=''){
        resetSelectBox('kategori', 'kategori');
        $.ajax({
            url:__HOSTAPI__ + "/Arsip/kategori",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;
				 for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                    $("#kategori").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

    function loadSubkategori(idkategori){
        resetSelectBox('subkategori', 'Subkategori');
        $.ajax({
            url:__HOSTAPI__ + "/Arsip/subkategori-detail/"+idkategori,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                
                console.log(response);
                var MetaData = response.response_package.response_data;
				 for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                    $("#subkategori").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

    function resetSelectBox(selector, name){
		$("#"+ selector +" option").remove();
		var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
        $("#" + selector).append(opti_null);
	}
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
            <form id="uploadDokumen" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-large-title">Tambah Berkas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label for="kategori">Kategori:</label>
                        <select class="form-control kategori" id="kategori">
                            <option value="" disabled selected>Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="kategori">Subkategori:</label>
                        <select class="form-control subkategori" id="subkategori">
                            <option value="" selected>Pilih Subkategori</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="txt_no_skp">Nama Berkas:</label>
                        <input type="text" class="form-control" id="txt_nama" />
                    </div>
                    <div class="form-group col-md-12">
                        <label for="txt_lokasi">Lokasi Penyimpanan:</label>
                        <input type="text" class="form-control" id="txt_lokasi" />
                    </div>
                    <div class="form-group col-md-12">
                        <label for="txt_keterangan">Keterangan:</label>
                        <textarea id="txt_keterangan" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="txt_lokasi">Berkas:</label>
                        <input type="file" class="form-control" name="fupload" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Submit</button>
                </div>
            </form>
		</div>
	</div>
</div>