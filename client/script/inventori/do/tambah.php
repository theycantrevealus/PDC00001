<script type="text/javascript">
	$(function(){
<<<<<<< HEAD
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
			autoRow();
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
			var itemData = loadItem();
			var selectedSatuan = "-";
			$(newItemSeletor).append("<option satuan=\"-\" value=\"none\">Pilih Obat</option>");
			for(var itemKey in itemData) {
				if(selectedItemList.indexOf(itemData[itemKey].uid) < 0) {
					$(newItemSeletor).append("<option satuan=\"" + itemData[itemKey].satuan_terkecil.nama + "\" " + ((itemData[itemKey].uid == selectedItem) ? "selected=\"selected\"" : "") + " value=\"" + itemData[itemKey].uid + "\">" + itemData[itemKey].nama.toUpperCase() + "</option>");
				}
			}
			if(!allowAdd) {
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
=======
		var no_urut_universal = 1;
        var dataInfo = {};
        var dataItems = {};
        var using_po = false;
        var uid_po;
        var supplier_po = loadFromPo();
>>>>>>> 444f66fa28a7c09cf1292601ddfec062146874f8

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

		function loadItem(){
			var dataItem;

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Inventori",
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

<<<<<<< HEAD
		$("body").on("keyup", ".qty", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
=======
			let stats = checkItemColumn(id);
			if (stats == true){
				if ($("#barang_" + id).parent().parent().hasClass("last")) {
                    no_urut_universal++;
					newColumn(no_urut_universal, using_po, uid_po);
					$("#barang_" + id).parent().parent().removeClass("last");
                    //setLastRow('item_' + no_urut_universal);
                    setNomorUrut("table-item-do","no_urut");
>>>>>>> 444f66fa28a7c09cf1292601ddfec062146874f8
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

<<<<<<< HEAD
		$("body").on("change", ".kadaluarsa", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var checker = checkAllowAdd(id);
			if(checker && allowAdd) {
				$("#item_" + id).attr("disabled", "disabled");
				if(selectedItem.indexOf($("#item_" + id).val()) < 0) {
					selectedItem.push($("#item_" + id).val());
=======
			let stats = checkItemColumn(id);
			if (stats == true){
				if ($("#barang_" + id).parent().parent().hasClass("last")) {
					no_urut_universal++;
                    newColumn(no_urut_universal, using_po, uid_po);
					$("#barang_" + id).parent().parent().removeClass("last");
                    //setLastRow('item_' + no_urut_universal);
                    setNomorUrut("table-item-do","no_urut");
>>>>>>> 444f66fa28a7c09cf1292601ddfec062146874f8
				}
				autoRow(selectedItem);
			}
		});

<<<<<<< HEAD
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
=======
		$("#table-item-do tbody").on('click', '.btn-hapus-item', function(){
            $(this).closest("tr").remove();
            setNomorUrut("table-item-do","no_urut");
        });

        $("#supplier").on('change', function(){
            //$("#po").val("");
            //.trigger('change');
            $("#table-item-do tbody").html("");
            using_po = false;
            no_urut_universal = 1;
            newColumn(no_urut_universal, using_po, uid_po)
            setNomorUrut("table-item-do","no_urut");
        });

        $("#po").on('change', function(){
            uid_po = $(this).val();

            if (uid_po != ""){
                using_po = true;

                $("#supplier").val(supplier_po[uid_po]).trigger('change');
                $("#table-item-do tbody").html("");
                var MetaData = loadPoItems(uid_po);              
                var opsi = "<option value=''>Pilih Item</option>";


                //$("#supplier").val(MetaData[0].uid_supplier).trigger('change');

                for(i = 0; i < MetaData.length; i++){
                    opsi += "<option value='"+ MetaData[i].uid_barang +"'>"+ MetaData[i].nama_barang + "</option>";
                }
                
                var html = "";
                let no_urut = 1;
                for(i = 0; i < MetaData.length; i++){
                    html = '<tr>' +
                            '<td class="no_urut"></td>' +
                            '<td><select class="form-control itemInputanSelect select2 items" id="barang_'+ no_urut +'" nama="barang_'+ no_urut +'">'+ opsi +'</select>' + 
                                '<div class="input-group">' +
                                    '<div class="input-group-prepend">' +
                                        '<span class="input-group-text" id="kedaluarsa_label_'+ no_urut +'">Kedaluarsa</span>' +
                                    '</div>' +
                                    '<input type="date" name="kedaluarsa_'+ no_urut +'" id="kedaluarsa_'+ no_urut +'" class="form-control itemInputan items" placeholder="Kedaluarsa" aria-describedby="kedaluarsa_label">' + 
                                '</div>' + 
                            '</td>' +
                            '<td><input type="text" name="kode_batch_'+ no_urut +'" id="kode_batch_'+ no_urut +'" class="form-control itemInputan items" placeholder="Kode Batch"></td>' +
                            '<td><input type="number" name="qty_'+ no_urut +'" id="qty_'+ no_urut +'" class="form-control itemInputan items" value="0"></td>' +
                            '<td><span id="satuan_'+ no_urut +'">Satuan</span></td>' + 
                            '<td><textarea class="form-control items" id="keterangan_'+ no_urut +'" nama="keterangan_'+ no_urut +'"></textarea></td>' + 
                            '<td><button class="btn btn-sm btn-danger btn-hapus-item" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button></td>' +
                        '</tr>';

                    $("#table-item-do tbody").append(html);
                    $("#barang_" + no_urut).val(MetaData[i].uid_barang);
                    no_urut_universal = no_urut;
                    no_urut++;
                }
                setNomorUrut("table-item-do","no_urut");
                    
            } else {
                $("#table-item-do tbody").html("");
                using_po = false;
                no_urut_universal = 1;
                newColumn(no_urut_universal, using_po, uid_po)
                setNomorUrut("table-item-do","no_urut");
                $("#supplier").val("").trigger('change');
            }
        });

        $("#btnSubmit").click(function(){

            $(".informasi").each(function(){
                let value = $(this).val();

                if (value != "" && value != null){
                    $this = $(this);
                    let name = $(this).attr("id");

                    dataInfo[name] = value;
                }
            });

            $(".items").each(function(){
                let value = $(this).val();

                if (value != "" && value != null && value != 0){
                    $this = $(this);
                    let row = $(this).attr("id").split("_");
                    let name = row.slice(0, row.length - 1).join("_");
                    row = row[row.length - 1];
                    
                    if (row in dataItems){
                        dataItems[row][name] = value;
                    } else {
                        dataItems[row] = {[name]: value};
                    }
                }
            });

            dataInfo['po'] = uid_po;

            console.log(dataInfo);

            //if (dataInfo[''])

           /* $.ajax({
                async: false,
                url: __HOSTAPI__ + "/DeliveryOrder",
                data: {
                    request : "tambah-do",
                    dataInfo : dataInfo,
                    dataItems : dataItems
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response){
                    //console.log(response);
                    location.href = __HOSTNAME__ + '/inventori/do';
                },
                error: function(response) {
                    console.log("Error : ");
                    console.log(response);
                }
            });
            */

            return false;

        });

        $('.select2').select2({});
	});
>>>>>>> 444f66fa28a7c09cf1292601ddfec062146874f8


<<<<<<< HEAD
		$("#btnSubmit").click(function() {
			var gudang = $("#gudang").val();
			var supplier = $("#supplier").val();
			var nomor_po = $("#po").val();
			var nomor_do = $("#no_do").val();
			var tgl_dokumen = $("#tgl_dokumen").val();
			var no_invoice = $("#no_invoice").val();
			var tgl_invoice = $("#tgl_invoice").val();
			var itemDetailResult = [];
			$("#table-item-do tbody tr").each(function() {
				if(!$(this).hasClass("last-row")) {
					var item = $(this).find("td:eq(1) select").val();
					var tanggal_exp = $(this).find("td:eq(1) input").val();
					var batch = $(this).find("td:eq(2) input").val();
					var qty = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
					var keterangan = $(this).find("td:eq(5) textarea").val();
					if(batch != "" && qty > 0) {
						itemDetailResult.push({
							item: item,
							batch: batch,
							tanggal_exp:tanggal_exp,
							qty: qty,
							keterangan: keterangan
						});
					}
				}
			});

			if(gudang != "none" && supplier != "none" && tgl_dokumen != "" && itemDetailResult.length > 0) {
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
=======
		return stats;
	}

	function newColumn(no_urut, using_po, uid_po){
        let html = '<tr>' +
					'<td class="no_urut"></td>' +
					'<td><select class="form-control itemInputanSelect select2 items" id="barang_'+ no_urut +'" nama="barang_'+ no_urut +'"><option value="">Pilih Item</option></select>' + 
                        '<div class="input-group">' +
                            '<div class="input-group-prepend">' +
                                '<span class="input-group-text" id="kedaluarsa_label_'+ no_urut +'">Kedaluarsa</span>' +
                            '</div>' +
                            '<input type="date" name="kedaluarsa_'+ no_urut +'" id="kedaluarsa_'+ no_urut +'" class="form-control itemInputan items" placeholder="Kedaluarsa" aria-describedby="kedaluarsa_label">' + 
                        '</div>' + 
                    '</td>' +
					'<td><input type="text" name="kode_batch_'+ no_urut +'" id="kode_batch_'+ no_urut +'" class="form-control itemInputan items" placeholder="Kode Batch"></td>' +
					'<td><input type="number" name="qty_'+ no_urut +'" id="qty_'+ no_urut +'" class="form-control itemInputan items" value="0"></td>' +
					'<td><span id="satuan_'+ no_urut +'">Satuan</span></td>' + 
					'<td><textarea class="form-control items" id="keterangan_'+ no_urut +'" nama="keterangan_'+ no_urut +'"></textarea></td>' + 
					'<td><button class="btn btn-sm btn-danger btn-hapus-item" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button></td>' +
				'</tr>';

        $("#table-item-do tbody").append(html);
        setNomorUrut("table-item-do","no_urut");
        if (using_po == true){
            var MetaData = loadPoItems(uid_po);              
            var opsi = "<option value=''>Pilih Item</option>";

            for(i = 0; i < MetaData.length; i++){
                opsi += "<option value='"+ MetaData[i].uid_barang +"'>"+ MetaData[i].nama_barang + "</option>";
            }
            $("#barang_" + no_urut).html(opsi);
        } else {
            loadItem(no_urut);
        }
    }

    function loadItem(selector_id){
    	var dataItem;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Inventori",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                dataItem = response.response_package.response_data;

                var html = "";
                 for(i = 0; i < dataItem.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", dataItem[i].uid).html(dataItem[i].nama);
                    $("#barang_" + selector_id).append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataItem;
    }

    function loadSatuan(selector_id){
    	var dataSatuan;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Inventori/satuan",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                dataSatuan = response.response_package.response_data;

                var html = "";
                 for(i = 0; i < dataSatuan.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", dataSatuan[i].uid).html(dataSatuan[i].nama);
                    //$("#satuan_" + selector_id).append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataSatuan;
    }

    function loadFromPo(uid_supplier = null){
        var supplier_po = [];
        var url = "";

        if (uid_supplier == null){
            url = '/load-po-available';
        } else {
            url = '/load-po-supplier/' + uid_supplier;
        }

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/DeliveryOrder" + url,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                for(i = 0; i < MetaData.length; i++){
                    if ($("#po option[value='"+ MetaData[i].po +"']").length == 0){
                        var selection = document.createElement("OPTION");
                        $(selection).attr("value", MetaData[i].po).html(MetaData[i].nomor_po);
                        $("#po").append(selection);
                        
                        supplier_po[MetaData[i].po] = MetaData[i].uid_supplier;
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return supplier_po;
    }

    function loadPoItems(uid_po){
        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/PO/po-items/" + uid_po,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                MetaData = response.response_package.response_data;
            },
            error: function(response) {
                console.log(response);
            }
        });

        return MetaData;
    }

	function setNomorUrut(table_name, no_urut_class){
        /*set dynamic serial number*/
        let rowCount = $("#"+ table_name +" tr").length;
        let table = $("#"+ table_name);
        let rowNum = parseInt(rowCount) - 1;
        $("."+ no_urut_class).html("");
        table.find('tr:eq('+ rowNum +')').addClass("last");

        for (var i = 0, row; i < rowCount; i++) {
            //console.log()
            table.find('tr:eq('+ i +')').find('td:eq(0)').html(i);
        }
        /*--------*/
    }

   /* function setLastRow(selector){
    	$("#" + selector).parent().parent().addClass("last");
    }*/
>>>>>>> 444f66fa28a7c09cf1292601ddfec062146874f8
</script>