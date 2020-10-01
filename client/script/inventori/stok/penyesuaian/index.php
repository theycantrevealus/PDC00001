<script type="text/javascript">
	
	$(function(){
		var metaDataOpname = {};
		function load_gudang(target,selected = "") {
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
						if(gudangData[a].uid == selected) {
							$(newOption).attr({
								"selected" : "selected"
							});
						}
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
		load_gudang("#txt_gudang", __UNIT__.gudang);

		load_product_resep("#txt_obat_tambah");
		load_gudang("#txt_gudang_tambah");
		$('#txt_periode_awal').datepicker("setDate", $.datepicker.parseDate( "yy-mm-dd", $('#txt_periode_awal').attr("setTanggal")));
		$('#txt_periode_akhir').datepicker("setDate", $.datepicker.parseDate( "yy-mm-dd", $('#txt_periode_akhir').attr("setTanggal")));

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
		});

		

		var tableHistoryOpname = $("#table-stok-opname").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_opname_history";
					d.gudang = __UNIT__.gudang;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Barang"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.dari;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.sampai;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.created_at;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-sm btn-info detail_opname\" id=\"opname_" + row.uid + "\"><i class=\"fa fa-eye\"></i> Lihat</button>" +
								"</div>";
					}
				},
			]
		});

		$("#txt_gudang").change(function() {
			tableHistoryOpname.ajax.reload();
		});

		$("#tambahStokAwal").click(function() {
			$("#form-tambah").modal("show");
		});

		$("body").on("click", ".detail_opname", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/get_opname_detail/" + uid,
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data[0];
					$("#txt_periode_awal_detail").html(data.dari);
					$("#txt_periode_akhir_detail").html(data.sampai);
					$("#txt_diproses_detail").html(data.pegawai.nama);
					$("#txt_kode_detail").html(data.kode);
					$("#detail-opname tbody tr").remove();
					for(var b in data.detail) {
						var parsedVisual = "";
						var selisih = 0;
						if(data.detail[b].qty_awal > data.detail[b].qty_akhir) {
							selisih = parseFloat(data.detail[b].qty_awal) - parseFloat(data.detail[b].qty_akhir);
							parsedVisual = "<b class=\"text-danger\"><i class=\"fa fa-caret-down\"></i> " + selisih + "</b>";
						} else if(data.detail[b].qty_awal < data.detail[b].qty_akhir) {
							selisih = parseFloat(data.detail[b].qty_akhir) - parseFloat(data.detail[b].qty_awal);
							parsedVisual = "<b class=\"text-warning\"><i class=\"fa fa-caret-up\"></i> " + selisih + "</b>";
						} else {
							selisih = parseFloat(data.detail[b].qty_akhir) - parseFloat(data.detail[b].qty_awal);
							parsedVisual = "<b class=\"text-success\"><i class=\"fa fa-check\"></i> " + selisih + "</b>";
						}

						$("#detail-opname tbody").append(
							"<tr>" +
								"<td>" + data.detail[b].autonum + "</td>" +
								"<td>" + data.detail[b].item.nama + "</td>" +
								"<td>" + data.detail[b].batch.batch + "</td>" +
								"<td class=\"number_style\">" + data.detail[b].qty_awal + "</td>" +
								"<td class=\"number_style\">" + data.detail[b].qty_akhir + "</td>" +
								"<td class=\"number_style\">" + parsedVisual + "</td>" +
								"<td>" + data.detail[b].keterangan + "</td>" +
							"</tr>"
						);
					}

					$("#txt_keterangan_detail").html(data.keterangan);

					$("#form-detail").modal("show");
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnSubmitStokOpname").click(function() {
			var conf = confirm("Data sudah benar?");
			if(conf) {
				var rawAwal = $("#txt_periode_awal").datepicker("getDate");
				var awal =  rawAwal.getFullYear() + "-" + str_pad(2, rawAwal.getMonth()+1) + "-" + str_pad(2, rawAwal.getDate());

				var rawAkhir = $("#txt_periode_akhir").datepicker("getDate");
				var akhir =  rawAkhir.getFullYear() + "-" + str_pad(2, rawAkhir.getMonth()+1) + "-" + str_pad(2, rawAkhir.getDate());

				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async: false,
					data: {
						request: "tambah_opname",
						dari:awal,
						sampai:akhir,
						keterangan:$("#txt_keterangan").val(),
						item:metaDataOpname
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							$("#form-tambah").modal("hide");
							tableHistoryOpname.ajax.reload();
							tableCurrentStock.ajax.reload();
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		


		var tableCurrentStock = $("#current-stok").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_stok_gudang";
					d.gudang = __UNIT__.gudang;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					for(var a in dataSet) {
						if(metaDataOpname[dataSet[a].uid] == undefined) {
							metaDataOpname[dataSet[a].uid] = {
								qty_awal: dataSet[a].stok_terkini,
								batch: dataSet[a].batch.uid,
								nilai: 0,
								keterangan: ""
							};
						}
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Barang"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<b>" + row.nama + "</b><span class=\"pull-right text-info\" style=\"font-size: 14pt;\">[" + row.batch.batch + "]</span>" + "<br />" + row.batch.expired_date;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.stok_terkini;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control aktual_qty\" id=\"item_" + row.uid + "\" batch=\"" + row.batch.uid + "\" placeholder=\"0.00\" />";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control keterangan_item\" id=\"keterangan_" + row.uid + "\" placeholder=\"Keterangan per Item\" />";
					}
				}
			]
		}).on("draw.dt", function () {
			$(".aktual_qty").inputmask({
				alias: 'decimal',
				rightAlign: true,
				placeholder: "0.00",
				prefix: "",
				autoGroup: false,
				digitsOptional: true
			});
		});

		$("#txt_diproses").val(__MY_NAME__);

		$("body").on("keyup", ".aktual_qty", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			metaDataOpname[uid].nilai = parseFloat($(this).inputmask("unmaskedvalue"));
		});

		$("body").on("keyup", ".keterangan_item", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			metaDataOpname[uid].keterangan = $(this).val();
		});
		
	});

</script>


<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Penyesuaian Stok</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="txt_no_skp">Periode Awal:</label>
									<input type="text" class="form-control txt_tanggal" id="txt_periode_awal" setTanggal="<?php echo $day->format('Y-m-1'); ?>" readonly />
								</div>
								<div class="form-group col-md-6">
									<label for="txt_no_skp">Periode Akhir:</label>
									<input type="text" class="form-control txt_tanggal" id="txt_periode_akhir" setTanggal="<?php echo $day->format('Y-m-d'); ?>" readonly />
								</div>
								<div class="form-group col-md-6">
									<label for="txt_no_skp">Dikerjakan Oleh:</label>
									<input type="text" class="form-control" id="txt_diproses" readonly />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-12">
									<table class="table table-bordered" id="current-stok">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th>Barang</th>
												<th class="wrap_content">Stok</th>
												<th style="width: 10%;">Aktual</th>
												<th>Keterangan</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<b>Keterangan:</b>
									<textarea placeholder="Keterangan Penyesuaian Stok" class="form-control" id="txt_keterangan" style="min-height: 200px"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitStokOpname">Simpan</button>
			</div>
		</div>
	</div>
</div>










<div id="form-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Hasil Penyesuaian Stok</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-6">
									<label for="txt_kode_detail">Kode Opname:</label>
									<br />
									<b id="txt_kode_detail"></b>
								</div>
								<div class="form-group col-md-6">
									<label for="txt_diproses_detail">Dikerjakan Oleh:</label>
									<br />
									<b id="txt_diproses_detail"></b>
								</div>
								<div class="form-group col-md-6">
									<label for="txt_periode_awal_detail">Periode Awal:</label>
									<br />
									<b id="txt_periode_awal_detail"></b>
								</div>
								<div class="form-group col-md-6">
									<label for="txt_periode_akhir_detail">Periode Akhir:</label>
									<br />
									<b id="txt_periode_akhir_detail"></b>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card card-form">
					<div class="row no-gutters">
						<div class="col-lg-12 card-body">
							<div class="row">
								<div class="form-group col-md-12">
									<table class="table table-bordered" id="detail-opname">
										<thead class="thead-dark">
											<tr>
												<th class="wrap_content">No</th>
												<th>Barang</th>
												<th class="wrap_content">Batch</th>
												<th class="wrap_content">Awal</th>
												<th class="wrap_content">Akhir</th>
												<th>Rate</th>
												<th>Keterangan</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<b>Keterangan:</b>
									<p id="txt_keterangan_detail"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitStokOpname">Simpan</button>
			</div>
		</div>
	</div>
</div>