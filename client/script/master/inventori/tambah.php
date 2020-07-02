<script type="text/javascript">
	$(function() {

		var MODE = "tambah";
		var productLib = [];
		var uid, rekap_varian;
		var basic = $("#image-uploader").croppie({
			enforceBoundary:false,
			viewport: {
				width: 220,
				height: 220
			},
		});

		basic.croppie("bind", {
			zoom: .4,
			url: __HOST__ + "/assets/images/inventori/unset.png"
		});

		$("#txt_nama").keyup(function() {
			$(".label_nama").html($(this).val());
		});

		$("#txt_kode").keyup(function() {
			$(".label_kode").html($(this).val().toUpperCase());
		});

		$("#txt_kategori").change(function() {
			$(".label_kategori").html($(this).find("option:selected").text());
		});

		$("#txt_manufacture").change(function() {
			$(".label_manufacture").html($(this).find("option:selected").text());
		});


		function load_kategori(target, selected = "") {
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/kategori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					kategoriData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < kategoriData.length; a++) {
						$(target).append("<option value=\"" + kategoriData[a].uid + "\">" + kategoriData[a].nama + "</option>");
					}
					$(".label_kategori").html($("#txt_kategori").find("option:selected").text());
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		function load_manufacture(target, selected = "") {
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/manufacture",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					manufactureData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < manufactureData.length; a++) {
						$(target).append("<option value=\"" + manufactureData[a].uid + "\">" + manufactureData[a].nama + "</option>");
					}
					$(".label_manufacture").html($("#txt_manufacture").find("option:selected").text());
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		function load_product() {
			var productData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					productData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});
			return productData;
		}

		function loadGudang() {
			var gudangData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					gudangData = response.response_package.response_data;
					for(var a = 0; a < gudangData.length; a++) {
						var newGudangRow = document.createElement("TR");

						var newGudangAICell = document.createElement("TD");
						var newGudangNameCell = document.createElement("TD");
						var newGudangRakCell = document.createElement("TD");
						var newGudangRak = document.createElement("INPUT");

						$(newGudangRak).addClass("form-control");

						$(newGudangAICell).html((a + 1));
						$(newGudangNameCell).html(gudangData[a].nama);
						$(newGudangRakCell).append(newGudangRak);

						$(newGudangRow).append(newGudangAICell);
						$(newGudangRow).append(newGudangNameCell);
						$(newGudangRow).append(newGudangRakCell);

						$("#table-lokasi-gudang tbody").append(newGudangRow);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
		}

		loadGudang();

		productLib = load_product();

		autoTable("#table-kombinasi", "kombinasi", [
			{
				"uri": "",
				"type": "span",
				"identifier": "row_kombinasi_",
				"db_col": "autonum"
			}, {
				"uri": __HOSTAPI__ + "/Inventori",
				"type": "select",
				"data":productLib,
				"identifier": "kombinasi_produk_",
				"db_col": "uid"
			}, {
				"uri": "",
				"type": "input",
				"inputType":"number",
				"identifier": "jumlah_kombinasi_",
				"db_col": ""
			}, {
				"uri": "",
				"type": "button",
				"child": [
					{
						"class": "btn btn-danger",
						"caption": "<i class=\"fa fa-times\"></i>",
						"identifier": "delete_satuan_",
					}
				],
				"db_col": ""
			}
		]);

		function load_satuan() {
			var satuanData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/satuan/",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					satuanData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});
			return satuanData;
		}

		function load_penjamin() {
			var penjaminData;
			$.ajax({
				url:__HOSTAPI__ + "/Penjamin/penjamin",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$("#table-penjamin tbody tr").remove();
					penjaminData = response.response_package.response_data;
					var varian_data = populate_varian_data();

					var parseVarian = [];
					for(var key in varian_data) {
						for(var b = 0; b < varian_data[key]["data"].length; b++) {
							parseVarian.push(key + " " + varian_data[key]["data"][b]);
						}
					}

					for(var a = 0; a < penjaminData.length; a++) {
						$("#table-penjamin tbody").append(
							"<tr>" +
								"<td rowspan=\"" + (parseVarian.length + 1) + "\">" + (a + 1) + "</td>" +
								"<td rowspan=\"" + (parseVarian.length + 1) + "\">" + penjaminData[a].nama + "</td>" +
							"</tr>"
						);
						for(var key in varian_data) {
							for(var b = 0; b < varian_data[key]["data"].length; b++) {
								$("#table-penjamin tbody").append(
									"<tr>" +
										"<td>" + varian_data[key]["text"] + " - " + varian_data[key]["data"][b] + "</td>" +
										"<td class=\"harga_penjamin_item\"></td>" +
									"</tr>"
								);
							}
						}
					}

					$("#table-penjamin tbody tr").each(function(){
						var hargaPenjamin = document.createElement("INPUT");
						$(hargaPenjamin).inputmask({
							alias: 'currency',
							rightAlign: true,
							placeholder: "0.00",
							prefix: "Rp ",
							autoGroup: false,
							digitsOptional: true
						}).addClass("form-control");
						$(this).find("td.harga_penjamin_item").append(hargaPenjamin);
					});
				},
				error: function(response) {
					console.log(response);
				}
			});
			return penjaminData;
		}
		load_penjamin();

		load_kategori("#txt_kategori");
		load_manufacture("#txt_manufacture");
		var satuanData = load_satuan();

		function reload_satuan(target, satuanData) {
			$(target).find("option").remove();
			for(var a = 0; a < satuanData.length; a++) {
				$(target).append("<option value=\"" + satuanData[a].uid + "\">" + satuanData[a].nama + "</option>");
			}
		}

		function autoTable(target, tableIden, columns = []) {
			$(target).find("tbody tr").removeClass("last-row");
			var nextID = $(target).find("tbody tr").length + 1;
			var rowContainer = document.createElement("TR");
			$(rowContainer).attr("id", tableIden + "_row_container_" + nextID).addClass("last-row");
			for(var cell = 0; cell < columns.length; cell++) {
				var cellContainer = document.createElement("TD");
				var uri = columns[cell].uri;
				var type = columns[cell].type;
				var inputType = columns[cell].inputType;
				var identifier = columns[cell].identifier;
				var db_col = columns[cell].db_col;

				if(type == "button") {
					var childEl = columns[cell].child;
					for(var zBut = 0; zBut < columns[cell].child.length; zBut++) {
						var cBut = document.createElement(type);
						$(cBut).addClass(columns[cell].child[zBut].class).html(columns[cell].child[zBut].caption).attr("id", columns[cell].child[zBut].identifier + nextID);
						$(cellContainer).append(cBut);
					}
				} else {
					var object_fill = document.createElement(type);
					if(cell == 0) {
						$(object_fill).html(nextID);
					}
					
					var targetData;
					if(uri != "") {
						$.ajax({
							url: uri,
							async:false,
							beforeSend: function(request) {
								request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
							},
							type:"GET",
							success:function(response) {
								targetData = response.response_package.response_data;
								reload_satuan(object_fill, targetData);
							},
							error: function(response) {
								console.log(response);
							}
						});
					}

					if(columns[cell].data !== undefined && columns[cell].data.length > 0) {
						$(object_fill).find("option").remove();
						for(var zSel = 0; zSel < columns[cell].data.length; zSel++) {
							$(object_fill).append("<option value=\"" + columns[cell].data[zSel].uid + "\">" + columns[cell].data[zSel].nama + "</option>");	
						}
					} else {
					
					}

					if(type == "input") {
						if(inputType == "number") {
							$(object_fill).inputmask({
								alias: 'currency',
								rightAlign: true,
								placeholder: "0.00",
								prefix: "",
								autoGroup: false,
								digitsOptional: true
							});
						}
					}



					$(object_fill).attr("id", identifier + nextID);
					if(type == "select" || type == "input") {
						$(object_fill).addClass("form-control");
					}
					$(cellContainer).append(object_fill);
				}
				$(rowContainer).append(cellContainer);
			}
			$(target).find("tbody").append(rowContainer);

			rebase_table(target);
		}

		function rebase_table(target) {
			$(target).find("tbody tr").each(function(e) {
				var identifier = "";
				var id = $(this).attr("id").split("_");
				var getID = id[id.length - 1];
				id.splice((id.length - 1), 1);
				$(this).attr("id", id.join("_") + "_" + (e + 1));

				$(this).find("span").each(function(f) {
					/*var curSel = $(this).attr("id").split("_");
					curSel.splice((curSel.length - 1), 1);
					$(this).addClass(curSel.join("_")).attr("id", curSel.join("_") + "_" + (e + 1));*/

					if(f == 0) {
						$(this).html((e + 1));
					} else {
						//
					}
				});

				//select
				$(this).find("select").each(function(f) {
					var curSel = $(this).attr("id").split("_");
					curSel.splice((curSel.length - 1), 1);
					$(this).addClass(curSel.join("_")).attr("id", curSel.join("_") + "_" + (e + 1));	
					$(this).select2();
				});

				//button
				$(this).find("button").each(function(f) {
					var curBut = $(this).attr("id").split("_");
					curBut.splice((curBut.length - 1), 1);
					$(this).addClass(curBut.join("_")).attr("id", curBut.join("_") + "_" + (e + 1));	
				});

				//input
				$(this).find("input").each(function(f) {
					var curIn = $(this).attr("id").split("_");
					curIn.splice((curIn.length - 1), 1);
					$(this).addClass(curIn.join("_")).attr("id", curIn.join("_") + "_" + (e + 1));	
				});

				checkSatuanKonversi(id);
			});
		}
		reload_satuan("#txt_satuan_terkecil", satuanData);
		autoTable("#table-konversi-satuan", "konversi_satuan", [
			{
				"uri": "",
				"type": "span",
				"identifier": "row_satuan_",
				"db_col": "autonum"
			}, {
				"uri": __HOSTAPI__ + "/Inventori/satuan",
				"type": "select",
				"data":satuanData,
				"identifier": "dari_satuan_",
				"db_col": "uid"
			}, {
				"uri": __HOSTAPI__ + "/Inventori/satuan",
				"type": "select",
				"identifier": "ke_satuan_",
				"db_col": "uid"
			}, {
				"uri": "",
				"type": "input",
				"inputType":"number",
				"identifier": "rasio_satuan_",
				"db_col": ""
			}, {
				"uri": "",
				"type": "button",
				"child": [
					{
						"class": "btn btn-danger",
						"caption": "<i class=\"fa fa-times\"></i>",
						"identifier": "delete_satuan_",
					}
				],
				"db_col": ""
			}
		]);
		checkSatuanKonversi(1);
		function checkSatuanKonversi(id){
			var checkDari = $("#dari_satuan_" + id).val();
			var checkKe = $("#ke_satuan_" + id).val();

			if(checkKe == checkDari) {
				$("#konversi_satuan_row_container_" + id).addClass("bg-danger-custom error-element");
			} else {
				$("#konversi_satuan_row_container_" + id).removeClass("bg-danger-custom error-element");
			}	
		}

		function autoVarian(forID, init = true) {

			var nextVarianID = $(".varian_value_receptor_" + forID).length + 1;
			
			//Reset Counter
			$(".varian_value_receptor_" + forID).removeClass("new_varian");
			$(".child_konversi_" + forID).removeClass("new-class-varian");
			$(".varian-delete_" + forID).removeClass("new_varian");

			var newVarianValue = document.createElement("INPUT");
			$(newVarianValue).addClass("form-control form-control-prepended new_varian varian_value_receptor varian_value_receptor_" + forID).attr({
				"placeholder": "DEFAULT",
				"id": "varian_value_receptor_" + forID + "_" + nextVarianID
			});

			if(init) {
				$("#table-varian tbody tr#varian_satuan_" + forID + " td:eq(2)").html("	<div class=\"form-group\">" +
																							"<label for=\"varian_value_receptor_" + forID + "_" + nextVarianID + "\"><small class\"text-danger\">Varian tetap</small></label>" +
																							"<div class=\"input-group input-group-merge mb-2\" style=\"width: 270px;\">" +
																								"<div class=\"input-group-prepend\">" +
																									"<div class=\"input-group-text\">" +
																										"<span class=\"fas fa-chevron-right\"></span>" +
																									"</div>" +
																								"</div>" +
																							"</div>" +
																						"</div>")
				$("#table-varian tbody tr#varian_satuan_" + forID + " td:eq(2)").find(".input-group").prepend(newVarianValue);
			} else {
				var newVarianInputRow = document.createElement("TR");
				$(newVarianInputRow).addClass("child_konversi new-class-varian child_konversi_" + forID).attr("id", "single_satuan_grouper_" + forID + "_" + nextVarianID);

				var newVarianCell = document.createElement("TD");
				$(newVarianCell).html("	<div class=\"form-group\">" +
											"<label for=\"varian_value_receptor_" + forID + "_" + nextVarianID + "\"></label>" +
											"<div class=\"input-group input-group-merge mb-2\" style=\"width: 270px;\">" +
												"<div class=\"input-group-prepend\">" +
													"<div class=\"input-group-text\">" +
														"<span class=\"fas fa-chevron-right\"></span>" +
													"</div>" +
												"</div>" +
											"</div>" +
										"</div>" +
										"<button id=\"delete_varian_value_receptor_"+ forID + "_" + nextVarianID + "\" class=\"btn btn-sm btn-danger varian-delete new_varian varian-delete_" + forID + "\"><small class\"text-danger\"><i class=\"fa fa-times\"></i> Hapus varian</small></button>");
				$(newVarianCell).find(".input-group").prepend(newVarianValue);
				$(newVarianInputRow).append(newVarianCell);
				if(nextVarianID == 1) {
					$("#table-varian tbody tr#varian_satuan_" + forID).after(newVarianInputRow);
				} else {
					$(newVarianInputRow).insertAfter($("#varian_value_receptor_" + forID + "_" + (nextVarianID - 1)).parent().parent().parent().parent());
				}
				$("#table-varian tbody tr#varian_satuan_" + forID + " td:eq(0)").attr("rowspan", $(".varian_value_receptor_" + forID).length);
				$("#table-varian tbody tr#varian_satuan_" + forID + " td:eq(1)").attr("rowspan", $(".varian_value_receptor_" + forID).length);
			}
			rekap_varian = populate_varian_data();

			//Render penjamin view
			load_penjamin();
		}

		function populateSatuan() {
			var unique = [];
			var populate = [];
			$("#table-konversi-satuan tbody tr").each(function(e) {
				if(!$(this).hasClass("last-row")) {
					$(this).find("select").each(function() {
						var getSatuanSelected= $(this).val();
						var getSatuanSelectedText = $(this).find("option:selected").text();
						if(!inArray(getSatuanSelected, unique)) {
							unique.push(getSatuanSelected);
							populate.push({
								"id": getSatuanSelected,
								"text": getSatuanSelectedText
							});
						}
					});
				}
			});

			for(var sPop = 0; sPop < populate.length; sPop++) {
				//Check exists
				if($("#table-varian tbody tr#varian_satuan_" + populate[sPop].id).length == 0) {
					var newVarianRow = document.createElement("TR");
					$(newVarianRow).attr("id", "varian_satuan_" + populate[sPop].id).addClass("parent_varians");

					var newVarianAI = document.createElement("TD");
					$(newVarianAI).html((sPop + 1));

					var newVarianSatuan = document.createElement("TD");
					$(newVarianSatuan).html(populate[sPop].text);

					var newVarianKemasan = document.createElement("TD");

					$(newVarianRow).append(newVarianAI);
					$(newVarianRow).append(newVarianSatuan);
					$(newVarianRow).append(newVarianKemasan);

					$("#table-varian tbody").append(newVarianRow);

					autoVarian(populate[sPop].id);
				} else {

				}
				//$("#txt_varian_kemasan").append("<option value=\"" + populate[sPop].id + "\">" + populate[sPop].text + "</option>");
			}

			//Remove not linked
			
			$("#table-varian tbody tr.parent_varians").each(function(e){
				if(!$(this).hasClass("child_konversi")) {
					var id = $(this).attr("id").split("_");
					id = id[id.length - 1];

					$(this).find("td:eq(0)").html((e + 1));

					if(!inArray(id, unique)) {
						$(this).remove();
						$(".child_konversi_" + id).remove();
					}
				}
			});

			return populate;
		}

		var varian_populate = populateSatuan();


		$("body").on("change", ".dari_satuan", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkSatuanKonversi(id);
			varian_populate = populateSatuan();
		});

		$("body").on("change", ".ke_satuan", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkSatuanKonversi(id);
			varian_populate = populateSatuan();
		});

		$("body").on("keyup", ".rasio_satuan", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(parseInt($(this).val()) > 0 && $("#konversi_satuan_row_container_" + id).hasClass("last-row")) {
				autoTable("#table-konversi-satuan", "konversi_satuan", [
					{
						"uri": "",
						"type": "span",
						"identifier": "row_satuan_",
						"db_col": "autonum"
					}, {
						"uri": __HOSTAPI__ + "/Inventori/satuan",
						"type": "select",
						"data":satuanData,
						"identifier": "dari_satuan_",
						"db_col": "uid"
					}, {
						"uri": __HOSTAPI__ + "/Inventori/satuan",
						"type": "select",
						"identifier": "ke_satuan_",
						"db_col": "uid"
					}, {
						"uri": "",
						"type": "input",
						"inputType":"number",
						"identifier": "rasio_satuan_",
						"db_col": ""
					}, {
						"uri": "",
						"type": "button",
						"child": [
							{
								"class": "btn btn-danger",
								"caption": "<i class=\"fa fa-times\"></i>",
								"identifier": "delete_satuan_",
							}
						],
						"db_col": ""
					}
				]);
				checkSatuanKonversi(id);
				varian_populate = populateSatuan();
			}
		});

		$("body").on("click", ".delete_satuan", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(!$("#konversi_satuan_row_container_" + id).hasClass("last-row")) {
				$("#konversi_satuan_row_container_" + id).remove();
				rebase_table("#table-konversi-satuan");
				varian_populate = populateSatuan();
			}
		});

		$("#txt_kategori").select2();
		$("#txt_manufacture").select2();
		$("#txt_satuan_terkecil").select2();

		$("body").on("click", ".varian-delete", function() {
			var id = $(this).attr("id").split("_");
			var grouperSatuan = id[id.length - 2];
			var singleSatuan = id[id.length - 1];
			
			if(!$(this).hasClass("new_varian")) {
				$("#single_satuan_grouper_" + grouperSatuan + "_" + singleSatuan).remove();
				$("#table-varian tbody tr#varian_satuan_" + grouperSatuan + " td:eq(0)").attr("rowspan", $(".varian_value_receptor_" + grouperSatuan).length);
				$("#table-varian tbody tr#varian_satuan_" + grouperSatuan + " td:eq(1)").attr("rowspan", $(".varian_value_receptor_" + grouperSatuan).length);
			} else {
				alert($("#single_satuan_grouper_" + grouperSatuan + "_" + singleSatuan).hasClass("new-class-varian"));	
			}
			return false;
		});

		$("body").on("keyup", ".varian_value_receptor", function() {
			var id = $(this).attr("id").split("_");
			var grouperSatuan = id[id.length - 2];
			var singleSatuan = id[id.length - 1];

			if($(this).hasClass("new_varian")) {
				autoVarian(grouperSatuan, false);
			}
		});

		$("body").on("blur", ".varian_value_receptor", function() {
			load_penjamin();
		});

		$("body").on("click", "a[href=\"tab-penjamin\"]", function() {
			load_penjamin();
		});





		$("#upload-image").change(function(){
			readURL(this, basic);
		});

		function populate_varian_data() {
			var populate_varian = {};
			$(".varian_value_receptor").each(function() {
				var id = $(this).attr("id").split("_");
				var grouperSatuan = id[id.length - 2];
				var singleSatuan = id[id.length - 1];

				if($(this).val() != "") {
					
					if(populate_varian[grouperSatuan] === undefined) {
						populate_varian[grouperSatuan] = {};
						populate_varian[grouperSatuan]["text"] = $("#varian_satuan_" + grouperSatuan).find("td:eq(1)").text();
						if(populate_varian[grouperSatuan]["data"] === undefined) {
							populate_varian[grouperSatuan]["data"] = [];	
						}
					}

					if(!inArray($(this).val(), populate_varian[grouperSatuan]["data"])) {
						populate_varian[grouperSatuan]["data"].push($(this).val());
					}
				}
			});

			return populate_varian;
		}

		function saveItem(__HOSTNAME__, __HOSTAPI__, MODE, stay = false) {
			//Halaman 1
			var nama = $("#txt_nama").val();
			var kategori = $("#txt_kategori").val();
			var kode = $("#txt_kode").val();
			var manufacture = $("#txt_manufacture").val();
			var keterangan = $("#txt_keterangan").val();
			var satuan_terkecil = $("#txt_satuan_terkecil").val();

			//Halaman 2
			var populate_konversi = [];
			var populate_varian = {};

			$("#table-konversi-satuan tbody tr").each(function(e) {
				if(!$("#konversi_satuan_row_container_" + (e + 1)).hasClass("last-row")) {
					var getDari = $("#dari_satuan_" + (e + 1)).val();
					var getKe = $("#ke_satuan_" + (e + 1)).val();
					var getRasio = $("#rasio_satuan_" + (e + 1)).inputmask("unmaskedvalue");
					populate_konversi.push({
						getDari:getDari,
						getKe:getKe,
						getRasio:getRasio
					});
				}
			});

			$(".varian_value_receptor").each(function() {
				var id = $(this).attr("id").split("_");
				var grouperSatuan = id[id.length - 2];
				var singleSatuan = id[id.length - 1];

				if($(this).val() != "") {
					if(!Array.isArray(populate_varian[grouperSatuan]) || populate_varian[grouperSatuan].length == 0) {
						populate_varian[grouperSatuan] = [];
					}

					if(!inArray($(this).val(), populate_varian[grouperSatuan])) {
						populate_varian[grouperSatuan].push($(this).val());
					}
				}
			});
			
			//Halaman 3
			var populate_harga = [];
		
			//Halaman 4
			var populate_lokasi = [];	

			//Halaman 5
			var populate_monitoring = [];

			/*
			
				1. Save data dapat dicicil. Paten la pokoknya 😍
				2. Minimal nama dan kode barang harus ada dulu 😠

			*/

			//if(nama != "" && kode != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async:false,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request: MODE + "_item",
						uid:uid,
						save_mode:stay,
						/*segment_informasi:{
							nama:nama,
							kode:kode,
							kategori:kategori,
							satuan_terkecil:satuan_terkecil,
							manufacture:manufacture,
							keterangan:keterangan
						},*/
						segment_satuan: {
							populate_konversi:populate_konversi,
							populate_varian:populate_varian
						}
					},
					type:"POST",
					success:function(response) {
						console.log(response);
						if(stay) {
							MODE = "edit";
							$("#mode_item").html("Edit");
							uid = response.response_package.response_uid;
							if(response.response_package.response_error == 0) {
								notification ("success", "Data berhasil diproses", 3000, "hasil_tambah");	
							} else if(response.response_package.response_error > 0) {
								notification ("warning", "Terjadi kesalahan data, silahkan cek kembali data yang telah diinput", 3000, "hasil_tambah_error");
							} else {
								notification ("danger", JSON.stringify(response), 3000, "hasil_tambah_dev");
							}
						} else {
							location.href = __HOSTNAME__ + "/master/inventori";	
						}
						
					},
					error: function(response) {
						console.log(response);
					}
				});
			//}
		}


		//Prepare Saving Data
		$("#btn_save_data_stay").click(function() {
			saveItem(__HOSTNAME__, __HOSTAPI__, MODE, true);
			return false;
		});

		$("#btn_save_data").click(function(){
			saveItem(__HOSTNAME__, __HOSTAPI__, MODE);
			return false;
		});

		function readURL(input, cropper) {
			var url = input.value;
			var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
				var reader = new FileReader();

				reader.onload = function (e) {
					
					cropper.croppie('bind', {
						url: e.target.result
					});
					//$('#imageLoader').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
			else{
				//$('#img').attr('src', '/assets/no_preview.png');
			}
		}
	});
</script>