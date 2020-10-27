<script type="text/javascript">
	
	$(function(){
		function load_gudang(target) {
			var gudangData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target + " option").remove();
					gudangData = response.response_package.response_data;
					for(var a in gudangData) {
						var newOption = document.createElement("OPTION");
						$(newOption).html(gudangData[a].nama).attr({
							"value":gudangData[a].uid
						});
						$(target).append(newOption);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
		}

		function load_product_resep(target, selectedData = "", appendData = true) {
			var selected = [];
			var productData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target).find("option").remove();
					$(target).append("<option value=\"none\">Pilih Obat</option>");
					productData = response.response_package.response_data;
					for (var a = 0; a < productData.length; a++) {
						var penjaminList = [];
						var penjaminListData = productData[a].penjamin;
						for(var penjaminKey in penjaminListData) {
							if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
								penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
							}
						}

						if(selected.indexOf(productData[a].uid) < 0 && appendData) {
							$(target).append("<option penjamin-list=\"" + penjaminList.join(",") + "\" satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			//return (productData.length == selected.length);
			return {
				allow: (productData.length == selected.length),
				data: productData
			};
		}

		load_product_resep("#txt_obat");
		load_gudang("#txt_gudang");

		load_product_resep("#txt_obat_tambah");
		load_gudang("#txt_gudang_tambah");

		$("#txt_obat").select2();
		$("#txt_obat_tambah").select2();
		$("#txt_gudang").select2();
		$("#txt_gudang_tambah").select2();
		$("#txt_qty_tambah").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		})

		

		var tableStokAwal = $("#table-stok-awal").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Inventori/get_stok_log",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var rawData = response.response_package.response_data;
					console.log(rawData);
					var returnData = [];
					for(var dataKey in rawData) {
						if(rawData[dataKey].gudang == $("#txt_gudang").val()) {
							if(rawData[dataKey].kategori != null) {
								returnData.push(rawData[dataKey]);
							} else {
								returnData.push(rawData[dataKey]);
							}
						}
					}
					return returnData;
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
						return row["barang"]["nama"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["batch"]["batch"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["batch"]["expired_date"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + row["masuk"] + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + row["keluar"] + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + row["saldo"] + "</h6>";
					}
				}
			]
		});

		$("#txt_gudang").change(function() {
			tableStokAwal.ajax.reload();
		});

		$("#tambahStokAwal").click(function() {
			$("#form-tambah").modal("show");
		});

		$("#btnSubmitStokAwal").click(function() {
			var gudang = $("#txt_gudang_tambah").val();
			var item = $("#txt_obat_tambah").val();
			var batch = $("#txt_batch_tambah").val();
			var qty = $("#txt_qty_tambah").inputmask("unmaskedvalue");
			var rawExp = $("#txt_exp_tambah").datepicker("getDate");
			var exp =  rawExp.getFullYear() + "-" + str_pad(2, rawExp.getMonth()+1) + "-" + str_pad(2, rawExp.getDate());
			
			if(batch!= "" && qty > 0) {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async:false,
					data:{
						request: "tambah_stok_awal",
						gudang: gudang,
						item: item,
						batch: batch,
						qty: qty,
						exp: exp
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							tableStokAwal.ajax.reload();
							$("#form-tambah").modal("hide");

							$("#txt_qty_tambah").val(0);
							$("#txt_batch_tambah").val("");
							$("#txt_exp_tambah").val("").datepicker("update");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}

			return false;
		});
	});

</script>


<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Stok Awal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-6">
					<label for="txt_no_skp">Gudang:</label>
					<select class="form-control" id="txt_gudang_tambah"></select>
				</div>
				<div class="form-group col-md-8">
					<label for="txt_no_skp">Item:</label>
					<select class="form-control" id="txt_obat_tambah"></select>
				</div>
				<div class="form-group col-md-4">
					<label for="txt_no_skp">Batch:</label>
					<input type="text" class="form-control uppercase" id="txt_batch_tambah" />
				</div>
				<div class="form-group col-md-4">
					<label for="txt_no_skp">Tanggal Kadaluarsa:</label>
					<input type="text" class="form-control txt_tanggal" id="txt_exp_tambah" readonly />
				</div>
				<div class="form-group col-md-3">
					<label for="txt_no_skp">Saldo:</label>
					<input type="text" class="form-control" id="txt_qty_tambah" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitStokAwal">Tambah</button>
			</div>
		</div>
	</div>
</div>