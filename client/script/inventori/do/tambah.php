<script type="text/javascript">
	$(function(){
		var poData = po_selection();
		var itemDataList = loadItem();
		var selectedItem = [];
		var allowAdd = false;
		$("#po").attr("disabled", "disabled");
		if(__PAGES__[3] !== undefined) {
			allowAdd = true;
			load_po(__PAGES__[3]);
			$("#supplier").attr("disabled", "disabled");
			loadGudang();
			//autoRow();
			/*loadItem(1);
			loadSatuan(1);*/
		} else {
			allowAdd = true;
			loadGudang();
			loadPemasok();
			/*loadItem(1);
			loadSatuan(1);*/
			autoRow();
			renderPO(poData);
		}
		}

		function load_po(parameter) {
			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/PO/view/" + parameter,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "GET",
				success: function(response){
					var po_data = response.response_package.response_data[0];
					loadPemasok(po_data.supplier.uid);
					renderPO(poData, po_data.uid);
					var itemData = po_data.item;
					for(var itemDataKey in itemData) {
						autoRow(selectedItem, itemData[itemDataKey].uid_barang);
					}
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});
		}

		function renderPO(poData, selected = "") {
			for(var poKey in poData) {
				$("#po").append("<option " + ((poData[poKey].uid == selected) ? "selected=\"selected\"" : "") + ">" + poData[poKey].nomor_po + "</option>");
			}
		}

		function po_selection() {
			var poData;
			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/PO",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "GET",
				success: function(response){
					var parsePO = response.response_package.response_data;
					poData = parsePO;
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});
			return poData;
		}

		function autoRow(selectedItemList = [], selectedItem = "") {
			$("#table-item-do tbody tr").removeClass("last-row");
			var newRow = document.createElement("TR");
			$(newRow).addClass("last-row");

			var newID = document.createElement("TD");
			var newItem = document.createElement("TD");
			var newKode = document.createElement("TD");
			var newQty = document.createElement("TD");
			var newSatuan = document.createElement("TD");
			var newKeterangan = document.createElement("TD");
			var newAksi = document.createElement("TD");

			var newItemSeletor = document.createElement("SELECT");
			var itemData = loadItem(selectedItem);
			var selectedSatuan = "-";
			$(newItemSeletor).append("<option satuan=\"-\" value=\"none\">Pilih Obat</option>");
			for(var itemKey in itemData) {
				if(selectedItemList.indexOf(itemData[itemKey].uid) < 0) {
					$(newItemSeletor).append("<option satuan=\"" + itemData[itemKey].satuan_terkecil.nama + "\" " + ((itemData[itemKey].uid == selectedItem) ? "selected=\"selected\"" : "") + " value=\"" + itemData[itemKey].uid + "\">" + itemData[itemKey].nama.toUpperCase() + "</option>");
				}
			}
			if(!allowAdd) {
				$(newItemSeletor).attr("disabled", "disabled");	
			} else {
                $(newItemSeletor).attr("disabled", "disabled");
            }
			$(newItem).append(newItemSeletor);
			$(newItemSeletor).select2().addClass("itemSelection form-control");


			var newExpiredDate = document.createElement("INPUT");
			$(newExpiredDate).attr("type", "date");
			$(newItem).append("<br />Tanggal Kedaluwarsa");
			$(newItem).append(newExpiredDate);
			$(newExpiredDate).addClass("form-control kadaluarsa");


			var newBatch = document.createElement("INPUT");
			$(newKode).append(newBatch);
			$(newBatch).addClass("form-control kode_batch");

			var newQtyInput = document.createElement("INPUT");
			$(newQty).append(newQtyInput);
			$(newQtyInput).addClass("form-control qty").inputmask({
				alias: 'decimal', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
			});

			$(newSatuan).html($(newItemSeletor).find("option:selected").attr("satuan"));

			var newKeteranganInput = document.createElement("TEXTAREA");
			$(newKeterangan).append(newKeteranganInput);
			$(newKeteranganInput).addClass("form-control");

			var newDelete = document.createElement("BUTTON");
			$(newAksi).append(newDelete);
			$(newDelete).addClass("btn btn-sm btn-danger deleteItem").html("<i class=\"fa fa-ban\"></i>");

			$(newRow).append(newID);
			$(newRow).append(newItem);
			$(newRow).append(newKode);
			$(newRow).append(newQty);
			$(newRow).append(newSatuan);
			$(newRow).append(newKeterangan);
			$(newRow).append(newAksi);
			$("#table-item-do tbody").append(newRow);
			rebaseTable();
		}

		function rebaseTable() {
			$("#table-item-do tbody tr").each(function(e) {
				var id = (e + 1);
				$(this).attr("id", "row_" + id);
				$(this).find("td:eq(0)").html(id);

				//Item
				$(this).find("td:eq(1) select").attr("id", "item_" + id);
				$(this).find("td:eq(1) input").attr("id", "kadaluarsa_" + id);

				//Kode
				$(this).find("td:eq(2) input").attr("id", "kode_batch_" + id);

				//Qty
				$(this).find("td:eq(3) input").attr("id", "qty_" + id);

				//Satuan
				$(this).find("td:eq(4)").attr("id", "satuan_" + id);

				//Keterangan
				$(this).find("td:eq(5) textarea").attr("id", "keterangan_" + id);

				//Delete
				$(this).find("td:eq(6) button").attr("id", "delete_" + id);
			});
		}

		function loadGudang(){
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Inventori/gudang",
				type: "GET",
				 beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var dataGudang = response.response_package.response_data;

					for(i = 0; i < dataGudang.length; i++){
						var selection = document.createElement("OPTION");

						$(selection).attr("value", dataGudang[i].uid).html(dataGudang[i].nama);
						$("#gudang").append(selection);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});

		}

		function loadPemasok(selected = ""){
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Supplier",
				type: "GET",
				 beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var dataPemasok = response.response_package.response_data;

					for(i = 0; i < dataPemasok.length; i++){
						var selection = document.createElement("OPTION");

						$(selection).attr("value", dataPemasok[i].uid).html(dataPemasok[i].nama);
						if(dataPemasok[i].uid == selected) {
							$(selection).attr("selected", "selected");
						}
						$("#supplier").append(selection);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});

		}

		function loadItem(selected){
			var dataItem;

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Inventori/item_detail/" + selected,
				type: "GET",
				 beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					dataItem = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});

			return dataItem;
		}

		$("select").select2();
		function checkAllowAdd(checkID) {
			var allow = false;
			if($("#row_" + checkID).hasClass("last-row")) {
				if((selectedItem.length + 1) < itemDataList.length) {
					if($("#item_" + checkID).val() != "none" && $("#kode_batch_" + checkID).val() != "" && $("#qty_" + checkID).inputmask("unmaskedvalue") > 0 && $("#kadaluarsa_" + checkID).val() != "") {
						allow = true;
					} else {
						allow = false;
					}
				} else {
					allow = false;
				}
			} else {
				allow = false;
			}
			return allow;
		}

		$("body").on("change", ".itemSelection", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
				}
				autoRow(selectedItem);
			}
			$("#satuan_" + id).html($(this).find("option:selected").attr("satuan"));
		});

		$("body").on("keyup", ".kode_batch", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
				}
				autoRow(selectedItem);
			}
		});

		$("body").on("keyup", ".qty", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
				}
				autoRow(selectedItem);
			}
		});

		$("body").on("keyup", ".kadaluarsa", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
				}
				autoRow(selectedItem);
			}
		});

		$("body").on("change", ".kadaluarsa", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
				}
				autoRow(selectedItem);
			}
		});

		$("body").on("click", ".deleteItem", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			console.log(selectedItem);
			console.log(selectedItem);
			if(!$("#row_" + id).hasClass("last-row")) {
				if(selectedItem.indexOf($("#item_" + id).val()) >= 0) {
					selectedItem.splice(selectedItem.indexOf($("#item_" + id).val()), 1);
					$(".last-row").remove();
					autoRow(selectedItem);
				}
				$("#row_" + id).remove();
				rebaseTable();
			}
		});


		$("#btnSubmit").click(function() {
			var gudang = $("#gudang").val();
			var supplier = $("#supplier").val();
			var nomor_po = $("#po").val();
			var nomor_do = $("#no_do").val();
			var tgl_dokumen = $("#tgl_dokumen").val();
			var no_invoice = $("#no_invoice").val();
			var tgl_invoice = $("#tgl_invoice").val();
			var itemDetailResult = [];
			var allowSave = false;
			$("#table-item-do tbody tr").each(function(e) {
				if(!$(this).hasClass("last-row")) {
					var item = $(this).find("td:eq(1) select").val();
					var tanggal_exp = $(this).find("td:eq(1) input").val();
					var batch = $(this).find("td:eq(2) input").val();
					var qty = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
					var keterangan = $(this).find("td:eq(5) textarea").val();
					if(batch != "" && qty > 0 && tanggal_exp != "") {
						itemDetailResult.push({
							item: item,
							batch: batch,
							tanggal_exp:tanggal_exp,
							qty: qty,
							keterangan: keterangan
						});
						allowSave = true;
						$("#table-item-do tbody tr:eq(" + e + ") td").removeClass("bg-error");
					} else {
						$("#table-item-do tbody tr:eq(" + e + ") td").addClass("bg-error");
						if(batch == "") {
							$(this).find("td:eq(2) input").focus();
						} else if(qty <=0) {
							$(this).find("td:eq(3) input").focus();
						} else {
							$(this).find("td:eq(1) input").focus();
						}
						
						allowSave = false;
						return false;
					}
				}
			});

			if(gudang != "none" && supplier != "none" && tgl_dokumen != "" && itemDetailResult.length > 0 && allowSave == true) {
				$.ajax({
					url: __HOSTAPI__ + "/DeliveryOrder",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request:"tambah_do",
						po:(__PAGES__[3] !== undefined) ? __PAGES__[3] : "none",
						gudang: gudang,
						supplier: supplier,
						nomor_do:nomor_do,
						tgl_dokumen: tgl_dokumen,
						no_invoice: no_invoice,
						tgl_invoice: tgl_invoice,
						item: itemDetailResult
					},
					type: "POST",
					success: function(response){
						//console.log(response);
						if(response.response_package.response_result > 0) {
							location.href = __HOSTNAME__ + '/inventori/do';
						}
					},
					error: function(response) {
						console.log("Error : ");
						console.log(response);
					}
				});
			}
		});
	});
</script>