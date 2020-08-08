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
			$("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama.toUpperCase()/* + " <b class=\"text-danger\">" + requiredItem[requiredItemKey].counter + " <i class=\"fa fa-receipt\"></i></b>"*/ + "</li>");
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

		var targettedData;

		$("body").on("click", ".btn-verfikasi", function() {
			var id = $(this).attr("id").split("_");
			var dataRow = id[id.length - 1];
			var resepUID = id[id.length - 2];

			$("#modal-verifikasi").modal("show");
			targettedData = listResep[(dataRow - 1)];
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
				$(newDetailCellID).addClass("text-center").html((a + 1))
					/*.append("<button style=\"margin-top: 20px;\" class=\"btn btn-sm btn-info btnRevisi\" id=\"revisi_resep_" + data.detail[a].obat + "\"><i class=\"fa fa-pencil-alt\"></i></button>")*/;

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
						"signa-qty": data.detail[a].signa_qty,
						"signa-pakai": data.detail[a].signa_pakai,
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

				var newDetailCellSigna = document.createElement("TD");
				$(newDetailCellSigna).html("<div class=\"input-group mb-3\">" +
											"<input value=\"" + data.detail[a].signa_qty + "\" type=\"text\" class=\"form-control signa\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
											"<div class=\"input-group-prepend\">" +
												"<span class=\"input-group-text\" id=\"basic-addon1\">&times;</span>" +
											"</div>" +
											"<input type=\"text\" value=\"" + data.detail[a].signa_pakai + "\" class=\"form-control signa\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
											"</div>");
				$(newDetailCellSigna).find("input").inputmask({
					alias: 'decimal',
					rightAlign: true,
					placeholder: "0.00",
					prefix: "",
					autoGroup: false,
					digitsOptional: true
				});
					
				//$(newDetailCellSigna).html(data.detail[a].signa_qty + " &times; " + data.detail[a].signa_pakai);

				var newDetailCellQty = document.createElement("TD");
				//$(newDetailCellQty).html("<h6>" + data.detail[a].qty + " <span>" + parsedItemData[obatNavigator.indexOf(itemData[dataKey].uid)]['satuan-caption'] + "</span></h6>");
				$(newDetailCellQty).html("<div class=\"input-group mb-3\">" +
											"<input value=\"" + data.detail[a].qty + "\" type=\"text\" class=\"form-control qty_resep\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
											"<div class=\"input-group-append\">" +
												"<span class=\"input-group-text\" id=\"basic-addon1\">" + parsedItemData[obatNavigator.indexOf(itemData[dataKey].uid)]['satuan-caption'] + "</span>" +
											"</div>" +
										"</div>");
				$(newDetailCellQty).find("input").inputmask({
					alias: 'decimal',
					rightAlign: true,
					placeholder: "0.00",
					prefix: "",
					autoGroup: false,
					digitsOptional: true
				});

				var newDetailCellHarga = document.createElement("TD");
				$(newDetailCellHarga).addClass("text-right");
				
				var newDetailCellTotal = document.createElement("TD");
				$(newDetailCellTotal).addClass("text-right");

				//var newDetailCellPenjamin = document.createElement("TD");
				var PenjaminAvailable = data.detail[a].detail.penjamin;
				//var penjaminList = [];
				for(var penjaminKey in PenjaminAvailable) {
					if(penjaminList.indexOf(PenjaminAvailable[penjaminKey].penjamin) < 0) {
						penjaminList.push(PenjaminAvailable[penjaminKey].penjamin);
					}
				}

				/*if(penjaminList.indexOf(data.antrian.penjamin) < 0) {
					$(newDetailCellPenjamin).html("<span class=\"badge badge-danger\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Ya</span>");
				} else {
					$(newDetailCellPenjamin).html("<span class=\"badge badge-success\"><i class=\"fa fa-check\" style=\"margin-right: 5px;\"></i> Tidak</span>");
				}*/

				var newDetailCellAksi = document.createElement("TD");

				/*var newVerifButton = document.createElement("BUTTON");
				$(newDetailCellAksi).append(newVerifButton);
				$(newVerifButton).addClass("btn btn-sm btn-success").html("<i class=\"fa fa-check\"></i> Verifikasi");*/

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
				$(newDetailRow).append(newDetailCellSigna);
				$(newDetailRow).append(newDetailCellQty);
				$(newDetailRow).append(newDetailCellHarga);
				$(newDetailRow).append(newDetailCellTotal);
				//$(newDetailRow).append(newDetailCellPenjamin);
				//$(newDetailRow).append(newDetailCellAksi);

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
					checkRevisi(data);
					var dataObat = e.params.data;
					$(this).children("[value=\""+ dataObat['id'] + "\"]").attr({
						"data-value": dataObat["data-value"],
						"jumlah": dataObat["jumlah"],
						"signa-qty": dataObat["signa_qty"],
						"signa-pakai": dataObat["signa_pakai"],
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
							$(this).parent().parent().find("td:eq(6)").html("<span class=\"badge text-success\"><i class=\"fa fa-check\" style=\"margin-right: 5px;\"></i> Ya</span>");
						} else {
							$(this).parent().parent().find("td:eq(6)").html("<span class=\"badge text-danger\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak</span>");
						}
					} else {
						$(this).parent().parent().find("td:eq(6)").html("<span class=\"badge text-danger\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak</span>");
					}
					$(this).parent().parent().find("td:eq(3) span").html(parsedItemData[obatNavigator.indexOf(currentObat)]['satuan-caption']);


					var refreshBatchData = refreshBatch(currentObat);
					$(this).parent().find("select.batch-loader option").remove();
					for(var batchKeyD in refreshBatchData) {
						if(refreshBatchData[batchKeyD].gudang.uid == __GUDANG_APOTEK__) {
							$(this).parent().find("select.batch-loader").append("<option harga=\"" + refreshBatchData[batchKeyD].harga + "\" value=\"" + refreshBatchData[batchKeyD].batch + "\">[" + refreshBatchData[batchKeyD].gudang.nama + "] - " + refreshBatchData[batchKeyD].kode + " [" + refreshBatchData[batchKeyD].expired + "]</option>");
						} else {
							$(this).parent().find("select.batch-loader").append("<option harga=\"" + refreshBatchData[batchKeyD].harga + "\" value=\"" + refreshBatchData[batchKeyD].batch + "\">[" + refreshBatchData[batchKeyD].gudang.nama + "] - " + refreshBatchData[batchKeyD].kode + " [" + refreshBatchData[batchKeyD].expired + "] / AMPRAH</option>");
						}
					}

					var setterHarga = $(this).parent().find("select.batch-loader option:selected").attr("harga");
					var setterQty = parseInt($(this).parent().parent().find("td:eq(3) input").inputmask("unmaskedvalue"));
					var setterHargaPenjamin = parseInt(setterHarga);
					if(setDiskonType == "P") {
						setterHargaPenjamin = setterHargaPenjamin + (diskonNilai / 100 * setterHargaPenjamin);
					} else if(setDiskonType == "A") {
						setterHargaPenjamin += diskonNilai;
					}

					var totalHargaPenjamin = setterQty * setterHargaPenjamin;

					$(this).parent().parent().find("td:eq(4)").html(number_format(setterHargaPenjamin, 2, ".", ","));
					$(this).parent().parent().find("td:eq(5)").html("<b>" + number_format(totalHargaPenjamin, 2, ".", ",") + "</b>");
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








			//==================================================================================== RACIKAN







			//Racikan
			$("#load-detail-racikan tbody").html("");
			for(var b = 0; b < data.racikan.length; b++) {
				var racikanID = (b + 1);
				var racikanDetail = data.racikan[b].detail;

				for(var racDetailKey in racikanDetail) {


					var newRacikanRow = document.createElement("TR");

					var newCellRacikanID = document.createElement("TD");
					var newCellRacikanNama = document.createElement("TD");
					var newCellRacikanSigna = document.createElement("TD");
					var newCellRacikanObat = document.createElement("TD");
					var newCellRacikanJlh = document.createElement("TD");
					var newCellRacikanHarga = document.createElement("TD");
					var newCellRacikanTotal = document.createElement("TD");

					var newRacikanObat = document.createElement("SELECT");

					$(newCellRacikanID).attr("rowspan", racikanDetail.length).html(racikanID);
					$(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
					$(newCellRacikanSigna).attr("rowspan", racikanDetail.length).html(data.racikan[b].signa_qty + " &times; " + data.racikan[b].signa_pakai);
					$(newCellRacikanObat).append(newRacikanObat);
					$(newCellRacikanJlh).html(
						"<b>" + racikanDetail[racDetailKey].takar_bulat + "</b>" +
						"<sub>" + racikanDetail[racDetailKey].takar_decimal + "</sub>" +
						"<br />" +
						"(Ratio : <b identifier-racikan-ratio=\"" + racDetailKey + "\">" + racikanDetail[racDetailKey].ratio + "</b> | Dibulatkan : <text identifier-racikan-bulat=\"" + racDetailKey + "\">" + racikanDetail[racDetailKey].pembulatan + "</text>)<br />" +
						"Pemotongan Stok : <span identifier-racikan-jumlah=\"" + racDetailKey + "\"></span>");

					$(newCellRacikanHarga).addClass("text-right").attr({
						"identifier-racikan-harga" : racDetailKey
					});

					$(newCellRacikanTotal).attr({
						"identifier-racikan-total": racDetailKey
					}).addClass("text-right");
					
					var racikanQty = document.createElement("INPUT");
					$(newCellRacikanNama).append("<hr /><h6 style=\"padding-top: 20px; display: block\">Jumlah Racikan</h6>").append(racikanQty);
					$(racikanQty).attr({
						"identifier-racikan-jumlah-all": racDetailKey,
						"identifier-racikan-jumlah-group": b,
					}).addClass("form-control").val(data.racikan[b].qty).inputmask({
						alias: 'decimal',
						rightAlign: true,
						placeholder: "0.00",
						prefix: "",
						autoGroup: false,
						digitsOptional: true
					});

					if(racDetailKey < 1) {
						$(newRacikanRow).append(newCellRacikanID);
						$(newRacikanRow).append(newCellRacikanNama);
						$(newRacikanRow).append(newCellRacikanSigna);
						
						$(newRacikanRow).append(newCellRacikanObat);
						$(newRacikanRow).append(newCellRacikanJlh);
						$(newRacikanRow).append(newCellRacikanHarga);
						$(newRacikanRow).append(newCellRacikanTotal);
					} else {
						$(newRacikanRow).append(newCellRacikanObat);
						$(newRacikanRow).append(newCellRacikanJlh);
						$(newRacikanRow).append(newCellRacikanHarga);
						$(newRacikanRow).append(newCellRacikanTotal);
					}

					$("#load-detail-racikan tbody").append(newRacikanRow);


					var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
					var newRacikanBatchSelector = document.createElement("SELECT");
					$(newCellRacikanObat).css({
						"padding-bottom": "50px"
					}).append("<b style=\"padding-top: 10px; display: block\">Batch</b>").append(newRacikanBatchSelector);
					$(newRacikanBatchSelector).addClass("racikan-batch-loader").select2();

					//$(newDetailCellObat).html(data.detail[a].detail.nama);
					var batchRacikanDataUnique = [];
					var batchRacikanData = [];
					var setRacikanDiskon = 0;
					var setRacikanDiskonType = "N";
					var itemRacikanData = RacikanObatData.data;

					var parsedItemRacikanData = [];
					var obatRacikanNavigator = [];
					for(var dataRacikanKey in itemRacikanData) {
						var penjaminRacikanList = [];
						var penjaminRacikanListData = itemRacikanData[dataRacikanKey].penjamin;
						

						for(var penjaminRacikanKey in penjaminRacikanListData) {
							if(penjaminRacikanList.indexOf(penjaminListData[penjaminRacikanKey].penjamin.uid) < 0) {
								penjaminRacikanList.push(penjaminRacikanListData[penjaminRacikanKey].penjamin.uid);

								if(penjaminListData[penjaminKey].penjamin.uid == $("#nama-pasien").attr("set-penjamin")) {
									setDiskon = penjaminListData[penjaminKey].profit;
									setDiskonType = penjaminListData[penjaminKey].profit_type;
								}
							}
						}

						var batchRacikanListData = itemRacikanData[dataRacikanKey].batch;
						for(var batchRacikanKey in batchRacikanListData) {
							if(batchRacikanDataUnique.indexOf(batchRacikanListData[batchRacikanKey].batch) < 0) {
								batchRacikanDataUnique.push(batchRacikanListData[batchRacikanKey].batch);
								batchRacikanData.push(batchRacikanListData[batchRacikanKey]);
							}
						}
						
						obatRacikanNavigator.push(itemRacikanData[dataRacikanKey].uid);

						parsedItemRacikanData.push({
							id: itemRacikanData[dataRacikanKey].uid,
							/*"jumlah": data.detail[a].qty,
							"penjamin-list": penjaminList,
							"satuan-caption": itemData[dataKey].satuan_terkecil.nama,
							"satuan-terkecil": itemData[dataKey].satuan_terkecil.uid,
							"signa-qty": data.detail[a].signa_qty,
							"signa-pakai": data.detail[a].signa_pakai,*/
							text: "<div style=\"color:" + ((itemRacikanData[dataRacikanKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemRacikanData[dataRacikanKey].nama.toUpperCase() + "</div>",
							html: 	"<div class=\"select2_item_stock\">" +
										"<div style=\"color:" + ((itemRacikanData[dataRacikanKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemRacikanData[dataRacikanKey].nama.toUpperCase() + "</div>" +
										"<div>" + itemRacikanData[dataRacikanKey].stok + "</div>" +
									"</div>",
							title: itemRacikanData[dataRacikanKey].nama
						});
					}

					$(newRacikanObat).addClass("form-control racikan-obat").select2({
						data: parsedItemData,
						placeholder: "Pilih Obat",
						selectOnClose: true,
						val: racikanDetail[racDetailKey].obat,
						escapeMarkup: function(markup) {
							return markup;
						},
						templateResult: function(data) {
							return data.html;
						},
						templateSelection: function(data) {
							return data.text;
						}
					}).attr({
						"identifier-racikan-obat": racDetailKey,
						"identifier-racikan-obat-group": b,
					}).on("select2:select", function(e) {
						var currentObatRacikan = $(this).val();
						var identifierValue = $(this).attr("identifier-racikan-obat");
						var identifierGroup = $(this).attr("identifier-racikan-obat-group");
						var refreshRacikanBatchData = refreshBatch(currentObatRacikan);
						$(this).parent().find("select.racikan-batch-loader option").remove();
						for(var batchKeyDRacikan in refreshRacikanBatchData) {
							if(refreshRacikanBatchData[batchKeyDRacikan].gudang.uid == __GUDANG_APOTEK__) {
								$(this).parent().find("select.racikan-batch-loader").append(
									"<option harga=\"" + refreshRacikanBatchData[batchKeyDRacikan].harga + "\" value=\"" + refreshRacikanBatchData[batchKeyDRacikan].batch + "\">[" + refreshRacikanBatchData[batchKeyDRacikan].gudang.nama + "] - " + refreshRacikanBatchData[batchKeyDRacikan].kode + " [" + refreshRacikanBatchData[batchKeyDRacikan].expired + "]</option>"
								);
							} else {
								$(this).parent().find("select.racikan-batch-loader").append(
									"<option harga=\"" + refreshRacikanBatchData[batchKeyDRacikan].harga + "\" value=\"" + refreshRacikanBatchData[batchKeyDRacikan].batch + "\">[" + refreshRacikanBatchData[batchKeyDRacikan].gudang.nama + "] - " + refreshRacikanBatchData[batchKeyDRacikan].kode + " [" + refreshRacikanBatchData[batchKeyDRacikan].expired + "] / AMPRAH</option>"
								);
							}
						}

						//Kalkulasi harga racikan per batch
						var jumlahSet = parseFloat($("input[identifier-racikan-jumlah-group=\"" + identifierGroup + "\"]").inputmask("unmaskedvalue"));
						var hargaSet = parseFloat($(this).parent().find("select.racikan-batch-loader option:selected").attr("harga"));
						var bulatSet = parseFloat($("text[identifier-racikan-bulat=\"" + identifierValue + "\"]").html());
						var ratioSet = parseFloat($("b[identifier-racikan-ratio=\"" + identifierValue + "\"]").html());

						/*var jumlahSet = parseFloat($(this).parent().parent().parent().find("td:eq(1) input").inputmask("unmaskedvalue"));
						var hargaSet = parseFloat($(this).parent().parent().parent().find("td:eq(3) select:eq(1) option:selected").attr("harga"));
						var ratioSet = parseFloat($(this).parent().parent().parent().find("td:eq(4) b:eq(1)").html());*/
						
						var totalHaraSet = jumlahSet * hargaSet * bulatSet;
						$("span[identifier-racikan-jumlah=\"" + identifierValue + "\"]").html(jumlahSet * bulatSet);
						$("td[identifier-racikan-harga=\"" + identifierValue + "\"]").html(number_format(hargaSet, 2, ",", "."));
						$("td[identifier-racikan-total=\"" + identifierValue + "\"]").html(number_format(totalHaraSet, 2, ",", "."));
						/*$(this).parent().parent().parent().find("td:eq(4) span").html(jumlahSet * ratioSet);
						$(this).parent().parent().parent().find("td:eq(5)").html(number_format(hargaSet, 2, ",", "."));
						$(this).parent().parent().parent().find("td:eq(6)").html(number_format(totalHaraSet, 2, ",", "."));*/
					});

					$(newRacikanObat).val([racikanDetail[racDetailKey].obat]).trigger("change").trigger({
						type:"select2:select",
						params: {
							data: parsedItemRacikanData
						}
					});
				}
			}
		}

		$("body").on("change", ".batch-loader", function() {
			var hargaSet = $(this).find("option:selected").attr("harga");
			var disc = $(this).parent().parent().find("td:eq(1)").attr("disc");
			var disc_type = $(this).parent().parent().find("td:eq(1)").attr("disc-type");
			var hargaJual = 0;
			var qty = $(this).parent().parent().find("td:eq(3) input").inputmask("unmaskedvalue");
			
			if(disc_type == "P") {
				hargaJual = parseFloat(hargaSet) + (disc / 100 * hargaSet);
			} else if(dis_type == "A") {
				hargaJual = parseFloat(hargaSet) + disc;
			} else {
				hargaJual = hargaSet;
			}

			$(this).parent().parent().find("td:eq(4)").html(number_format(hargaJual, 2, ".", ","));
			$(this).parent().parent().find("td:eq(5) b").html(number_format((hargaJual * qty), 2, ".", ","));
		});

		$("body").on("change", ".racikan-batch-loader", function() {
			//
		});

		$("body").on("keyup", ".qty_resep", function() {
			console.clear();
			var hargaSet = $(this).parent().parent().parent().find("td:eq(1) select:eq(1) option:selected").attr("harga");
			var disc = $(this).parent().parent().parent().find("td:eq(1)").attr("disc");
			var disc_type = $(this).parent().parent().parent().find("td:eq(1)").attr("disc-type");
			var qty = $(this).parent().parent().parent().find("td:eq(3) input").inputmask("unmaskedvalue");
			var hargaJual = 0;

			if(disc_type == "P") {
				hargaJual = parseFloat(hargaSet) + (disc / 100 * hargaSet);
			} else if(dis_type == "A") {
				hargaJual = parseFloat(hargaSet) + disc;
			} else {
				hargaJual = hargaSet;
			}

			$(this).parent().parent().parent().find("td:eq(4)").html(number_format(hargaJual, 2, ".", ","));
			$(this).parent().parent().parent().find("td:eq(5) b").html(number_format((hargaJual * qty), 2, ".", ","));
		});

		/*$("body").on("click", ".btnRevisi", function() {
			var ke_obat = $(this).parent().parent().find("td:eq(1) select:eq(0)").val();
			var signa_qty = $(this).parent().parent().find("td:eq(2) input:eq(0)").inputmask("unmaskedvalue");
			var signa_pakai = $(this).parent().parent().find("td:eq(2) input:eq(1)").inputmask("unmaskedvalue");
			var qty = $(this).parent().parent().find("td:eq(3) input").inputmask("unmaskedvalue");

			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			var conf = confirm("Revisi resep?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Apotek",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					data:{
						request:"revisi_resep",
						resep: targettedData.uid,
						dari_obat:uid,
						ke_obat: ke_obat,
						signa_qty:signa_qty,
						signa_pakai:signa_pakai,
						qty: qty
					},
					success:function(response) {
						//
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});*/

		$("#btnProsesResep").click(function() {
			console.clear();
			var conf = confirm("Pastikan resep sudah benar sekali lagi. Anda yakin?");
			if(conf) {
				var UIDResep = targettedData.uid;
				var detail = [];
				//Ambil Resep Biasa
				$("#load-detail-resep tbody tr").each(function() {
					var obat_biasa = $(this).find("td:eq(1) select:eq(0)").val();
					var batch_biasa = $(this).find("td:eq(1) select:eq(1)").val();
					var harga_biasa = $(this).find("td:eq(1) select:eq(1) option:selected").attr("harga");
					var signa_qty_biasa = parseFloat($(this).find("td:eq(2) input:eq(0)").inputmask("unmaskedvalue"));
					var signa_pakai_biasa = parseFloat($(this).find("td:eq(2) input:eq(1)").inputmask("unmaskedvalue"));
					var jumlah_biasa = parseFloat($(this).find("td:eq(3) input").inputmask("unmaskedvalue"));

					if(signa_qty_biasa > 0 && signa_pakai_biasa > 0 && jumlah_biasa > 0) {
						detail.push({
							obat: obat_biasa,
							batch: batch_biasa,
							harga: harga_biasa,
							signa_qty: signa_qty_biasa,
							signa_pakai: signa_pakai_biasa,
							jumlah: jumlah_biasa
						});
					}
				});

				var racikan = [];
				//Ambil Resep Racikan
				$("#load-detail-racikan tbody tr").each(function(e) {
					if(e == 0) {
						var obat_racikan = $(this).find("td:eq(3) select:eq(0)").val();
						var batch_racikan = $(this).find("td:eq(3) select:eq(1)").val();
						var harga_racikan = $(this).find("td:eq(3) select:eq(1) option:selected").attr("harga");
						var jumlah_racikan = $(this).find("td:eq(4) span").html();
					} else {
						var obat_racikan = $(this).find("td:eq(0) select:eq(0)").val();
						var batch_racikan = $(this).find("td:eq(0) select:eq(1)").val();
						var harga_racikan = $(this).find("td:eq(0) select:eq(1) option:selected").attr("harga");
						var jumlah_racikan = $(this).find("td:eq(1) span").html();
					}

					racikan.push({
						obat:obat_racikan,
						batch: batch_racikan,
						harga: parseFloat(harga_racikan),
						jumlah: parseFloat(jumlah_racikan)
					});
				});

				$.ajax({
					url:__HOSTAPI__ + "/Apotek",
					async:false,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					data:{
						request: "verifikasi_resep",
						resep: UIDResep,
						asesmen:targettedData.asesmen,
						detail: detail,
						racikan: racikan
					},
					success:function(response) {
						if(response.response_package.response_result > 0) {
							$("#modal-verifikasi").modal("hide");
						} else {
							console.log(response);
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		function checkRevisi(targettedData){
			var resep_detail = targettedData.detail;
			var resep_racikan = targettedData.racikan;
		}

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
<div id="modal-verifikasi" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
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
						<div class="card-header card-header-large bg-white">
							<div class="row">
								<div class="col-lg-6">
									<h5 class="card-header__title flex m-0">Daftar Resep / <span class="text-info" id="nama-pasien"></span>. Total tagihan <span class="text-danger" id="jumlah-tagihan-pasien">0.00</span></h5>
								</div>
							</div>
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
											<th style="width: 40%;">Obat</th>
											<th width="15%">Signa</th>
											<th width="15%">Jumlah</th>
											<th class="wrap_content">Harga</th>
											<th class="wrap_content">Total</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="tab-pane show fade" id="tab-racikan">
								<table id="load-detail-racikan" class="table table-bordered largeDataType">
									<thead class="thead-dark">
										<tr>
											<th class="wrap_content"><i class="fa fa-hashtag"></i></th>
											<th style="width: 15%;">Racikan</th>
											<th>Signa</th>
											<th>Obat</th>
											<th>Jumlah (Otomatis)</th>
											<th>Harga</th>
											<th>Total</th>
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
				<button type="button" class="btn btn-success" id="btnProsesResep"><i class="fa fa-check"></i> Proses</button>
				<button type="button" class="btn btn-info"><i class="fa fa-print"></i> Copy Resep</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
			</div>
		</div>
	</div>
</div>