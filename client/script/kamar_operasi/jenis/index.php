<script type="text/javascript">
    
    $(function (){
        let MODE = "tambah";
        let selectedUID = "";
        
        var tableJenis = $("#table_jenis_operasi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/KamarOperasi/jenis_operasi",
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
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return meta.row + meta.settings._iDisplayStart + 1;
                	}  
    			},
				{
					"data" : null, render: function(data, type, row, meta) {
						return `<span id="nama_${row['uid']}">${row["nama"]}</span>`;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return `<span id="ket_${row['uid']}">${row["keterangan"]}</span>`;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return `<div class="btn-group" role="group" aria-label="Basic example">` +
									`<button class="btn btn-warning btn-sm btn_edit_jenis" data-uid="${row["uid"]}" data-toggle='tooltip' title='Edit'>` +
										`<i class="fa fa-edit"></i>` +
									`</button>` +
									`<button data-uid="${row['uid']}" class="btn btn-danger btn-sm btn_delete_jenis" data-toggle="tooltip" title="Hapus">` +
										`<i class="fa fa-trash"></i>` +
									`</button>` +
								`</div>`;
					}
				}
			]
		});

        $("#btnTambah").click(function(){
            MODE = "tambah";
            selectedUID = "";
            $("#form-tambah").modal("show");
        });

        $("#table_jenis_operasi tbody").on('click', '.btn_edit_jenis', function(){
            MODE = "edit";
            selectedUID = $(this).data("uid");
            let nama = $(`#nama_${selectedUID}`).text();
            let keterangan = $(`#ket_${selectedUID}`).text();

            $("#txt_nama").val(nama);
            $("#txt_keterangan").val(keterangan);

            $("#form-tambah").modal("show");
        });


        $("#table_jenis_operasi tbody").on('click', '.btn_delete_jenis', function(){
            let uid = $(this).data("uid");
            console.log(uid);

			var conf = confirm("Hapus jenis operasi item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/KamarOperasi/kamar_operasi_jenis_operasi/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(resp) {
						tableJenis.ajax.reload();
					}
				});
			} 
        });

        $("#btnSubmit").click(function() {
			let nama = $("#txt_nama").val();
            let keterangan = $("#txt_keterangan").val();

			if(nama != "") {
				let form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "add_jenis_operasi",
                        "nama": nama,
                        "keterangan": keterangan
					};
				} else {
					form_data = {
						"request": "edit_jenis_operasi",
						"uid": selectedUID,
						"nama": nama,
                        "keterangan": keterangan
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/KamarOperasi",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
                        $("#txt_nama").val("");
						$("#txt_keterangan").val("");
						$("#form-tambah").modal("hide");
						tableJenis.ajax.reload();
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
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah <span class="title-term"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_nama">Jenis Operasi :</label>
					<input type="text" class="form-control" id="txt_nama" />
                </div>
                <div class="form-group col-md-12">
                    <label for="txt_keterangan">Keterangan :</label>
                    <textarea class="form-control" name="txt_keterangan" id="txt_keterangan" cols="30" rows="5"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>