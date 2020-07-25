<script type="text/javascript">
	$(function() {
		function load_resep() {
			var selected = [];
			var resepData;
			$.ajax({
				url:__HOSTAPI__ + "/Apotek",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					resepData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});

			return resepData;
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

		function populateObat(data) {
			var obatList = {};
			for(var a = 0; a < data.length; a++) {
				var listBiasa = data[a].detail;
				for(var b = 0; b < listBiasa.length; b++) {
					if(obatList[listBiasa[b].obat] == undefined) {
						obatList[listBiasa[b].obat] = {
							nama: "",
							counter: 0
						};
					}

					obatList[listBiasa[b].obat]['nama'] = listBiasa[b].detail.nama;
					obatList[listBiasa[b].obat]['counter'] += 1;
				}

				var listRacikan = data[a].racikan;
				for(var c = 0; c < listRacikan.length; c++) {
					for(var d = 0; d < listRacikan[c].detail.length; d++) {
						if(obatList[listRacikan[c].detail[d].obat] == undefined) {
							obatList[listRacikan[c].detail[d].obat] = {
								nama: "",
								counter: 0
							};
						}

						obatList[listRacikan[c].detail[d].obat]['nama'] = listRacikan[c].detail[d].detail.nama;
						obatList[listRacikan[c].detail[d].obat]['counter'] += 1;
					}
				}
			}

			return obatList;
		}
		var listResep = load_resep();
		var requiredItem = populateObat(listResep);
		for(var requiredItemKey in requiredItem) {
			$("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama + " <b class=\"text-danger\">" + requiredItem[requiredItemKey].counter + " <i class=\"fa fa-receipt\"></i></b></li>");
		}

		var tableResep= $("#table-resep").DataTable({
			"data": load_resep(),
			autoWidth: false,
			"bInfo" : false,
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
						return row.antrian.departemen.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.antrian.pasien_info.panggilan_name.nama + " " + row.antrian.pasien_info.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.dokter.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.antrian.penjamin_data.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";
					}
				}
			]
		});

		$("body").on("click", ".btn-verfikasi", function() {
			var id = $(this).attr("id").split("_");
			var dataRow = id[id.length - 1];
			var resepUID = id[id.length - 2];

			$("#modal-verifikasi").modal("show");
			var targettedData = listResep[(dataRow - 1)];
			$("#nama-pasien").attr({
				"set-penjamin": targettedData.antrian.penjamin_data.uid
			}).html(targettedData.antrian.pasien_info.panggilan_name.nama + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");
			loadDetailResep(targettedData);
		});

		function loadDetailResep(data) {
			$("#load-detail-resep tbody tr").remove();
			for(var a = 0; a < data.detail.length; a++) {
				var newDetailRow = document.createElement("TR");

				var newDetailCellID = document.createElement("TD");
				$(newDetailCellID).html((a + 1));

				var newDetailCellObat = document.createElement("TD");
				var newObat = document.createElement("SELECT");
				$(newDetailCellObat).append(newObat);
				var ObatData = load_product_resep(newObat, data.detail[a].detail.uid, false);

				var newBatchSelector = document.createElement("SELECT");
				$(newDetailCellObat).append("<b style=\"padding-top: 10px; display: block\">Batch</b>").append(newBatchSelector);

				//$(newDetailCellObat).html(data.detail[a].detail.nama);
				var batchDataUnique = [];
				var batchData = [];
				var setDiskon = 0;
				var setDiskonType = "N";
				var itemData = ObatData.data;

				var parsedItemData = [];
				var obatNavigator = [];
				for(var dataKey in itemData) {
					var penjaminList = [];
					var penjaminListData = itemData[dataKey].penjamin;
					

					for(var penjaminKey in penjaminListData) {
						if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
							penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);

							if(penjaminListData[penjaminKey].penjamin.uid == $("#nama-pasien").attr("set-penjamin")) {
								setDiskon = penjaminListData[penjaminKey].profit;
								setDiskonType = penjaminListData[penjaminKey].profit_type;
							}
						}
					}

					var batchListData = itemData[dataKey].batch;
					for(var batchKey in batchListData) {
						if(batchDataUnique.indexOf(batchListData[batchKey].batch) < 0) {
							batchDataUnique.push(batchListData[batchKey].batch);
							batchData.push(batchListData[batchKey]);
						}
					}
					
					obatNavigator.push(itemData[dataKey].uid);

					parsedItemData.push({
						id: itemData[dataKey].uid,
						"jumlah": data.detail[a].qty,
						"penjamin-list": penjaminList,
						"satuan-caption": itemData[dataKey].satuan_terkecil.nama,
						"satuan-terkecil": itemData[dataKey].satuan_terkecil.uid,
						text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
						html: 	"<div class=\"select2_item_stock\">" +
									"<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
									"<div>" + itemData[dataKey].stok + "</div>" +
								"</div>",
						title: itemData[dataKey].nama
					});
				}

				$(newDetailCellObat).attr({
					"disc": setDiskon,
					"disc-type": setDiskonType
				});
				
				$(newBatchSelector).addClass("form-control batch-loader").select2();

				var newDetailCellQty = document.createElement("TD");
				$(newDetailCellQty).html("<h6>" + data.detail[a].qty + " <span>" + parsedItemData[obatNavigator.indexOf(itemData[dataKey].uid)]['satuan-caption'] + "</span></h6>");

				var newDetailCellHarga = document.createElement("TD");
				$(newDetailCellHarga).addClass("text-right");
				
				var newDetailCellTotal = document.createElement("TD");
				$(newDetailCellTotal).addClass("text-right");

				var newDetailCellPenjamin = document.createElement("TD");
				var PenjaminAvailable = data.detail[a].detail.penjamin;
				//var penjaminList = [];
				for(var penjaminKey in PenjaminAvailable) {
					if(penjaminList.indexOf(PenjaminAvailable[penjaminKey].penjamin) < 0) {
						penjaminList.push(PenjaminAvailable[penjaminKey].penjamin);
					}
				}

				if(penjaminList.indexOf(data.antrian.penjamin) < 0) {
					$(newDetailCellPenjamin).html("<span class=\"badge badge-danger\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak ditanggung penjamin</span>");
				} else {
					$(newDetailCellPenjamin).html("<span class=\"badge badge-success\"><i class=\"fa fa-check\" style=\"margin-right: 5px;\"></i> Ditanggung penjamin</span>");
				}

				var newDetailCellAksi = document.createElement("TD");

				var newVerifButton = document.createElement("BUTTON");
				$(newDetailCellAksi).append(newVerifButton);
				$(newVerifButton).addClass("btn btn-sm btn-success").html("<i class=\"fa fa-check\"></i> Verifikasi");

				for(var batchRKey in batchData) {
					if(batchData[batchRKey].barang == data.detail[a].detail.uid) {
						$(newBatchSelector).append("<option harga=\"" + batchData[batchRKey].harga + "\" value=\"" + batchData[batchRKey].batch + "\">" + batchData[batchRKey].kode + "</option>");
						$(newDetailCellHarga).html(((parseFloat(batchData[batchRKey].harga) > 0) ? number_format(batchData[batchRKey].harga, 2, ".", ",") : 0));
						$(newDetailCellTotal).html(((parseFloat(data.detail[a].qty * batchData[batchRKey].harga) > 0) ? number_format(data.detail[a].qty * batchData[batchRKey].harga, 2, ".", ",") : 0));
					}
				}

				/*var newRevisiButton = document.createElement("BUTTON");
				$(newDetailCellAksi).append(newRevisiButton);
				$(newRevisiButton).addClass("btn btn-sm btn-info").html("<i class=\"fa fa-receipt\"></i> Revisi");*/


				//=======================================
				$(newDetailRow).append(newDetailCellID);
				$(newDetailRow).append(newDetailCellObat);
				$(newDetailRow).append(newDetailCellQty);
				$(newDetailRow).append(newDetailCellHarga);
				$(newDetailRow).append(newDetailCellTotal);
				$(newDetailRow).append(newDetailCellPenjamin);
				$(newDetailRow).append(newDetailCellAksi);

				$("#load-detail-resep tbody").append(newDetailRow);










				$(newObat).addClass("form-control resep-obat").select2({
					data: parsedItemData,
					placeholder: "Pilih Obat",
					selectOnClose: true,
					val: data.detail[a].detail.uid,
					escapeMarkup: function(markup) {
						return markup;
					},
					templateResult: function(data) {
						return data.html;
					},
					templateSelection: function(data) {
						return data.text;
					}
				}).on("select2:select", function(e) {
					var currentObat = $(this).val();
					var dataObat = e.params.data;
					$(this).children("[value=\""+ dataObat['id'] + "\"]").attr({
						"data-value": dataObat["data-value"],
						"jumlah": dataObat["jumlah"],
						"penjamin-list": dataObat["penjamin-list"],
						"satuan-caption": dataObat["satuan-caption"],
						"satuan-terkecil": dataObat["satuan-terkecil"]
					});

					var penjaminAvailable = parsedItemData[obatNavigator.indexOf(currentObat)]['penjamin-list'];
					var diskonNilai = 0;
					var diskonType = "N";
					if(penjaminAvailable.length > 0) {
						if(penjaminAvailable.indexOf($("#nama-pasien").attr("set-penjamin")) >= 0) {
							var diskonNilai = parseInt($(this).parent().parent().find("td:eq(1)").attr("disc"));
							var diskonType = $(this).parent().parent().find("td:eq(1)").attr("disc-type");
							$(this).parent().parent().find("td:eq(5)").html("<span class=\"badge badge-success\"><i class=\"fa fa-check\" style=\"margin-right: 5px;\"></i> Ditanggung penjamin</span>");
						} else {
							$(this).parent().parent().find("td:eq(5)").html("<span class=\"badge badge-danger\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak ditanggung penjamin</span>");
						}
					} else {
						$(this).parent().parent().find("td:eq(5)").html("<span class=\"badge badge-danger\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak ditanggung penjamin</span>");
					}
					$(this).parent().parent().find("td:eq(2) span").html(parsedItemData[obatNavigator.indexOf(currentObat)]['satuan-caption']);


					var refreshBatchData = refreshBatch(currentObat);
					$(this).parent().find("select.batch-loader option").remove();
					for(var batchKeyD in refreshBatchData) {
						$(this).parent().find("select.batch-loader").append("<option harga=\"" + refreshBatchData[batchKeyD].harga + "\" value=\"" + refreshBatchData[batchKeyD].batch + "\">[" + refreshBatchData[batchKeyD].gudang.nama + "] - " + refreshBatchData[batchKeyD].kode + "</option>");
					}

					var setterHarga = $(this).parent().find("select.batch-loader option:selected").attr("harga");
					var setterQty = parseInt($(this).parent().parent().find("td:eq(2)").text());
					var setterHargaPenjamin = parseInt(setterHarga);
					if(setDiskonType == "P") {
						setterHargaPenjamin = setterHargaPenjamin + (diskonNilai / 100 * setterHargaPenjamin);
					} else if(setDiskonType == "A") {
						setterHargaPenjamin += diskonNilai;
					}

					var totalHargaPenjamin = setterQty * setterHargaPenjamin;

					$(this).parent().parent().find("td:eq(3)").html(number_format(setterHargaPenjamin, 2, ".", ","));
					$(this).parent().parent().find("td:eq(4)").html(number_format(totalHargaPenjamin, 2, ".", ","));
				});

				$(newObat).val([data.detail[a].detail.uid]).trigger("change").trigger({
					type:"select2:select",
					params: {
						data: parsedItemData
					}
				});

				$(newObat).find("option:selected").attr({
					"data-value": parsedItemData[obatNavigator.indexOf(data.detail[a].detail.uid)]["data-value"],
					"jumlah": parsedItemData[obatNavigator.indexOf(data.detail[a].detail.uid)]["jumlah"],
					"penjamin-list": penjaminList,
					"satuan-caption": parsedItemData[obatNavigator.indexOf(data.detail[a].detail.uid)]["satuan-caption"],
					"satuan-terkecil": parsedItemData[obatNavigator.indexOf(data.detail[a].detail.uid)]["satuan-terkecil"]
				});
			}
		}

		$("body").on("change", ".batch-loader", function() {
			var hargaSet = $(this).attr("harga");
			//alert(hargaSet);
		});

		function refreshBatch(item) {
			var batchData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/item_batch/" + item,
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					batchData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});
			return batchData;
		}
	});
</script>
<div id="modal-verifikasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Verifikasi Resep</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-lg">
					<div class="card">
						<div class="card-header card-header-large bg-white d-flex align-items-center">
							<h5 class="card-header__title flex m-0">Daftar Resep / <span class="text-info" id="nama-pasien"></span></h5>
						</div>
						<div class="card-header card-header-tabs-basic nav" role="tablist">
							<a href="#tab-resep" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Resep</a>
							<a href="#tab-racikan" data-toggle="tab" role="tab" aria-selected="false">Racikan</a>
						</div>
						<div class="card-body tab-content">
							<div class="tab-pane active show fade" id="tab-resep">
								<table id="load-detail-resep" class="table table-bordered table-striped largeDataType">
									<thead class="thead-dark">
										<tr>
											<th class="wrap_content"><i class="fa fa-hashtag"></i></th>
											<th width="30%">Obat</th>
											<th width="15%">Jumlah</th>
											<th width="20%">Harga</th>
											<th width="20%">Total</th>
											<th>Penjamin</th>
											<th width="15%">Aksi</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="tab-pane show fade" id="tab-racikan">
								<table id="load-detail-racikan" class="table table-bordered">
									<thead class="thead-dark">
										<tr>
											<th class="wrap_content"><i class="fa fa-hashtag"></i></th>
											<th>Racikan</th>
											<th>Obat</th>
											<th>Penjamin</th>
											<th class="wrap_content">Aksi</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success"><i class="fa fa-check"></i> Proses</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
			</div>
		</div>
	</div>
</div>