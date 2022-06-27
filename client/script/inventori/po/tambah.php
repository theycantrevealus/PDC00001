<script type="text/javascript">
	$(function() {

		//Monitoring
		$("#txt_tanggal").change(function() {
			check_page_1();
		});

		function check_page_1() {
            if($.datepicker.formatDate('yy-mm-dd', new Date($("#txt_tanggal").datepicker("getDate"))) !== "" && $("#table-detail-po tbody tr").not(".last-row").length > 1) {
				$("#status-utama").fadeIn();
			} else {
				$("#status-utama").fadeOut();
			}
		}

		function check_page_2() {
			if($("#po_document_table tbody tr").length > 0) {
				$("#status-dokumen").fadeIn();
			} else {
				$("#status-dokumen").fadeOut();
			}
		}


		$(".inv-tab-status").hide();
		$("#txt_jenis_diskon_all").select2();
		$("#txt_diskon_all").inputmask({
			alias: 'currency', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
		});


		function loadTermSelectBox(selector, id_term, selected = ""){
			$.ajax({
				url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var MetaData = response.response_package.response_data;

					if (MetaData != ""){
						for(i = 0; i < MetaData.length; i++){
							var selection = document.createElement("OPTION");
							if(MetaData[i].id == selected) {
								$(selection).attr("selected", "selected");
							}

							$(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
							$("#" + selector).append(selection);
						}
					}

				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		loadTermSelectBox("txt_sumber_dana", 18);

		function load_supplier(target, selected = "") {
			var kategoriData;
			/*$.ajax({
				url:__HOSTAPI__ + "/Supplier",
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
				},
				error: function(response) {
					console.log(response);
				}
			});*/
            $(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                placeholder:"Cari Pemasok",
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Supplier/get_supplier_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
            });
			return kategoriData;
		}

		function load_manufacture(target, selected = "") {
			var manufactureData;
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
						$(target).append("<option " + ((manufactureData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + manufactureData[a].uid + "\">" + manufactureData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return manufactureData;
		}

		function load_satuan(target, selected = "") {
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
					$(target).find("option").remove();
					for(var a = 0; a < satuanData.length; a++) {
						$(target).append("<option " + ((satuanData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + satuanData[a].uid + "\">" + satuanData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return satuanData;
		}

		function load_product(target, selectedData = "", appendData = true) {
			var selected = [];
			var productData;
			/*$.ajax({
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
			});*/

			$(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                placeholder:"Cari Barang",
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid,
                                    penjamin: item.penjamin
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                var penjaminListData = data.penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }
            });

			/*return {
				allow: (productData.length == selected.length),
				data: productData
			};*/
		}

		function autoPODetail() {
			//var nextID = $("#table-detail-po tbody tr").length + 1;
			$("#table-detail-po tbody tr").removeClass("last-row");
			var newRow = document.createElement("TR");
			var newCellID = document.createElement("TD");
			var newCellItem = document.createElement("TD");
			var newCellQty = document.createElement("TD");
			var newCellSatuan = document.createElement("TD");
			var newCellHarga = document.createElement("TD");
			var newCellDisc = document.createElement("TD");
			var newCellDiscType = document.createElement("TD");
			var newCellSubtotal = document.createElement("TD");

			var newItem = document.createElement("SELECT");
			$(newCellItem).append(newItem);
            $(newItem).addClass("form-control item");
            load_product(newItem);

			var keteranganItem = document.createElement("TEXTAREA");
			$(newCellItem).append("<br /><br /><b>Keterangan Item</b>").append(keteranganItem);
			$(keteranganItem).addClass("form-control").attr({
				"placeholder": "Keterangan Produk"
			});
			
			var newQty = document.createElement("INPUT");
			$(newQty).inputmask({
				alias: 'currency', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true, min: 1
			}).addClass("form-control qty").val(1);
			$(newCellQty).append(newQty);

			/*var newSatuan = document.createElement("SELECT");
			load_satuan(newSatuan);
			$(newCellSatuan).append(newSatuan);
			$(newSatuan).select2().addClass("form-control satuan");*/

			var newHarga = document.createElement("INPUT");
			$(newHarga).inputmask({
				alias: 'decimal', rightAlign: true, placeholder: "0,00", prefix: "", groupSeparator: ".", autoGroup: false, digitsOptional: true
			}).addClass("form-control harga");
			$(newCellHarga).append("<b>Per satuan terkecil</b>").append(newHarga);

			var newDisc = document.createElement("INPUT");
			$(newDisc).inputmask({
				alias: 'currency', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
			}).addClass("form-control disc");
			$(newCellHarga).append("<br /><b>Diskon</b>").append(newDisc);

			var newDiscType = document.createElement("SELECT");
			var discTypeLib = {
				N:"None",
				P:"Percent",
				A:"Amount"
			};
			for(var discKey in discTypeLib) {
				var newDiscOption = document.createElement("OPTION");
				$(newDiscOption).attr({
					"value": discKey
				}).html(discTypeLib[discKey]);
				$(newDiscType).append(newDiscOption);
			}
			$(newCellHarga).append("<br /><b>Jenis Diskon</b>").append(newDiscType);
			$(newDiscType).select2().addClass("form-control disc_type");

			var newSubTotal = document.createElement("H5");
			$(newSubTotal).html(number_format(0, 2, ".", ",")).addClass("total");
			
			var newDeletePODetail = document.createElement("BUTTON");
			$(newDeletePODetail).html("<i class=\"fa fa-ban\"></i>").addClass("btn btn-sm btn-danger delete");

			$(newCellSubtotal).addClass("text-right").append(newSubTotal).append(newDeletePODetail);

			$(newRow).append(newCellID);
			$(newRow).append(newCellItem);
			$(newRow).append(newCellQty);
			$(newRow).append(newCellSatuan);
			$(newRow).append(newCellHarga);
			//$(newRow).append(newCellDisc);
			$(newRow).append(newCellSubtotal);

			$("#table-detail-po tbody").append(newRow);
			rebasePODetail();
		}

		function rebasePODetail() {
			$("#table-detail-po tbody tr").each(function(e) {
				var id = (e + 1);

				$(this).attr({
					"id": "po_detail_" + id
				});

				$(this).find("td:eq(0)").html(id);

				//PRODUCT
				$(this).find("td:eq(1) select").attr({
					"id": "product_" + id
				});
				
				//QTY
				$(this).find("td:eq(2) input").attr({
					"id": "qty_" + id
				});

				//SATUAN
				$(this).find("td:eq(3)").attr({
					"id": "satuan_" + id
				});

				//HARGA
				$(this).find("td:eq(4) input:eq(0)").attr({
					"id": "harga_" + id
				});

				//DISC
				$(this).find("td:eq(4) input:eq(1)").attr({
					"id": "disc_" + id
				});

				$(this).find("td:eq(4) select").attr({
					"id": "disc_type_" + id
				});

				$(this).find("td:eq(5) button").attr({
					"id": "delete_" + id
				});

				$(this).find("td:eq(5) h5").attr({
					"id": "total_" + id
				});
			});
			check_page_1();
			$("#table-detail-po tbody tr:eq(" + ($("#table-detail-po tbody tr").length - 1) + ")").addClass("last-row");
		}

		autoPODetail();

		load_supplier("#txt_supplier");
		//$("#txt_supplier").select2();

		function calculateRow(id) {
			var qty = $("#qty_" + id).inputmask("unmaskedvalue");
			var harga = $("#harga_" + id).inputmask("unmaskedvalue");
			var discount = $("#disc_" + id).inputmask("unmaskedvalue");
			var discountType = $("#disc_type_" + id).val();
			var total = 0;
			if(discountType == "N") {
				total = qty * harga;
			} else if(discountType == "P") {
				total = (qty * harga) - (discount / 100 * (qty * harga));
			} else {
				total = (qty * harga) - discount;
			}

			return total;
		}

		function calculateAll(total) {
			var discountTypeAll = $("#txt_jenis_diskon_all").val();
			var discountValueAll = $("#txt_diskon_all").inputmask("unmaskedvalue");
			var grandTotal = 0;
			if(discountTypeAll == "N") {
				grandTotal = total;
			} else if(discountTypeAll == "P") {
				grandTotal = total - (discountValueAll / 100 * total);
			} else {
				grandTotal = total - discountValueAll;
			}
			$("#grandTotal h5").html(number_format(grandTotal, 2, ".", ","));
			return grandTotal;
		}

		function calculateGrandTotal() {
			var grandTotal = 0;
			$(".total").each(function(f) {
				var getValue = ($(this).attr("get-value") === undefined) ? 0 : parseFloat($(this).attr("get-value"));
				grandTotal += parseFloat(getValue);
			});

			$("#allTotal h5").html(number_format(grandTotal, 2, ".", ","));

			return grandTotal;
		}

		$("body").on("keyup", ".qty", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var total = calculateRow(id);
			//number_format (number, decimals, dec_point, thousands_sep)
			$("#total_" + id).attr({
				"get-value": total
			}).html(number_format(total, 2, ".", ","));
			calculateAll(calculateGrandTotal());

			if($("#po_detail_" + id).hasClass("last-row") && parseInt($(this).inputmask("unmaskedvalue")) > 0) {
				autoPODetail();
			}
		});

		$("body").on("keyup", "#txt_diskon_all", function() {
			calculateAll(calculateGrandTotal());
		});

		$("body").on("change", "#txt_jenis_diskon_all", function() {
			calculateAll(calculateGrandTotal());
		});

		$("body").on("keyup", ".harga", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var total = calculateRow(id);
			$("#total_" + id).attr({
				"get-value": total
			}).html(number_format(total, 2, ".", ","));
			calculateAll(calculateGrandTotal());

			if($("#po_detail_" + id).hasClass("last-row") && parseInt($(this).inputmask("unmaskedvalue")) > 0) {
				autoPODetail();
			}
		});

		$("body").on("keyup", ".disc", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var total = calculateRow(id);
			$("#total_" + id).attr({
				"get-value": total
			}).html(number_format(total, 2, ".", ","));
			calculateAll(calculateGrandTotal());

			if($("#po_detail_" + id).hasClass("last-row") && parseInt($(this).inputmask("unmaskedvalue")) > 0) {
				autoPODetail();
			}
		});

		$("body").on("change", ".disc_type", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var total = calculateRow(id);
			$("#total_" + id).attr({
				"get-value": total
			}).html(number_format(total, 2, ".", ","));
			calculateAll(calculateGrandTotal());

			if($("#po_detail_" + id).hasClass("last-row") && parseInt($(this).inputmask("unmaskedvalue")) > 0) {
				autoPODetail();
			}
		});

		$("body").on("click", ".delete", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(!$("#po_detail_" + id).hasClass("last-row")) {
				$("#po_detail_" + id).remove();
				rebasePODetail();
			}
		});
		//=============================================================================================================================
		var fileList = [];
		function autoDocument(file) {
			var newDocRow = document.createElement("TR");

			var newDocCellNum = document.createElement("TD");
			var newDocCellDoc = document.createElement("TD");
			$(newDocCellDoc).addClass("text-center");
			var newDocCellAct = document.createElement("TD");

			var newDocument = document.createElement("CANVAS");
			$(newDocument).css({
				"width": "75%"
			});
			$(newDocCellDoc).append(newDocument);
			if (file.type == "application/pdf" && file != undefined) {
				var fileReader = new FileReader();
				fileReader.onload = function() {
					var pdfData = new Uint8Array(this.result);
					// Using DocumentInitParameters object to load binary data.
					var loadingTask = pdfjsLib.getDocument({
						data: pdfData
					});
					loadingTask.promise.then(function(pdf) {
						// Fetch the first page
						var pageNumber = 1;
						pdf.getPage(pageNumber).then(function(page) {
							var scale = 1.5;
							var viewport = page.getViewport({
								scale: scale
							});
							// Prepare canvas using PDF page dimensions
							var canvas = $(newDocument)[0];
							var context = canvas.getContext('2d');
							canvas.height = viewport.height;
							canvas.width = viewport.width;
							// Render PDF page into canvas context
							var renderContext = {
								canvasContext: context,
								viewport: viewport
							};
							var renderTask = page.render(renderContext);
							renderTask.promise.then(function() {
								//
							});
						});
					}, function(reason) {
						console.error(reason);
					});
				};
				fileReader.readAsArrayBuffer(file);
			}

			var newDeleteDoc = document.createElement("button");
			$(newDeleteDoc).addClass("btn btn-sm btn-danger delete_document").html("<span style=\"display: block;\"><i class=\"fa fa-ban\"></i> Hapus</span>");
			$(newDocCellAct).append(newDeleteDoc);

			$(newDocRow).append(newDocCellNum);
			$(newDocRow).append(newDocCellDoc);
			$(newDocRow).append(newDocCellAct);

			$("#po_document_table").append(newDocRow);
			rebaseDocument();
		}

		function rebaseDocument() {
			$("#po_document_table tbody tr").each(function(e) {
				var id = (e + 1);
				$(this).attr({
					"id": "document_" + id
				});
				$(this).find("td:eq(0)").html((e + 1));
				$(this).find("td:eq(2) button").attr({
					"id": "delete_document_" + id
				});
			});
		}

		var pdfjsLib = window['pdfjs-dist/build/pdf'];
		pdfjsLib.GlobalWorkerOptions.workerSrc = __HOSTNAME__ + '/plugins/pdfjs/build/pdf.worker.js';
		var file;

		$('#form-upload-document').on('shown.bs.modal', function () {
			if (file.type == "application/pdf" && file != undefined) {
				var fileReader = new FileReader();
				fileReader.onload = function() {
					var pdfData = new Uint8Array(this.result);
					// Using DocumentInitParameters object to load binary data.
					var loadingTask = pdfjsLib.getDocument({
						data: pdfData
					});
					loadingTask.promise.then(function(pdf) {
						// Fetch the first page
						var pageNumber = 1;
						pdf.getPage(pageNumber).then(function(page) {
							var scale = 1.5;
							var viewport = page.getViewport({
								scale: scale
							});
							// Prepare canvas using PDF page dimensions
							var canvas = $("#pdfViewer")[0];
							var context = canvas.getContext('2d');
							canvas.height = viewport.height;
							canvas.width = viewport.width;
							// Render PDF page into canvas context
							var renderContext = {
								canvasContext: context,
								viewport: viewport
							};
							var renderTask = page.render(renderContext);
							renderTask.promise.then(function() {
								//$("#btnSubmit").removeAttr("disabled").html("Terima SK").removeClass("btn-warning").addClass("btn-primary");
							});
						});
					}, function(reason) {
						// PDF loading error
						console.error(reason);
					});
				};
				fileReader.readAsArrayBuffer(file);
			}
		});

		$("#add_file").change(function(e) {
			$("#form-upload-document").modal("show");
			file = e.target.files[0];
		});

		$("#btnSubmitDocument").click(function() {
			autoDocument(file);
			fileList.push(file);
			check_page_2();
			$("#form-upload-document").modal("hide");
		});

		$("body").on("click", ".delete_document", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			fileList.splice((id - 1), 1);
			$("#document_" + id).hide();
			rebaseDocument();
			return false;
		});

		$("body").on("change", ".item", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var satuanCaption = $(this).find("option:selected").attr("satuan-caption");
			$("#satuan_" + id).html((satuanCaption != undefined) ? satuanCaption : "-");
		});

		$("#submitPO").submit(function() {
			var oldCaption = $("#btnSubmitPO").html();
			$("#btnSubmitPO").attr({
				"disabled": "disabled"
			}).html("<i class=\"fa fa-save\"></i> Sedang Proses").removeClass("btn-success");

			var supplier = $("#txt_supplier").val();
			var tanggal = $.datepicker.formatDate('yy-mm-dd', new Date($("#txt_tanggal").datepicker("getDate")));
			var diskonAll = $("#txt_diskon_all").inputmask("unmaskedvalue");
			var diskonJenisAll = $("#txt_jenis_diskon_all").val();
			var sumber_dana = $("#txt_sumber_dana option:selected").val();
			var keteranganAll = $("#txt_keterangan").val();

			var itemList = [];
			var form_data = new FormData(this);

			form_data.append("request", "tambah_po");
			form_data.append("supplier", supplier);
			form_data.append("tanggal", tanggal);
			form_data.append("diskonAll", diskonAll);
			form_data.append("diskonJenisAll", diskonJenisAll);
			form_data.append("sumber_dana", sumber_dana);
			form_data.append("keteranganAll", keteranganAll);
			//form_data.append("fileList", JSON.stringify(fileList));

			$("#table-detail-po tbody tr").each(function(e) {
				if(!$(this).hasClass("last-row")) {
					//PRODUCT
					var item = $(this).find("td:eq(1) select").val();

					//KETERANGAN
					var keterangan = $(this).find("td:eq(1) textarea").val();

					//QTY
					var qty = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");

					//SATUAN
					var satuan = $(this).find("td:eq(3) select").val();

					//HARGA
					var harga = $(this).find("td:eq(4) input:eq(0)").inputmask("unmaskedvalue");

					//DISC
					var diskon = $(this).find("td:eq(4) input:eq(1)").inputmask("unmaskedvalue");
					var jenis_diskon = $(this).find("td:eq(4) select").val();

					itemList.push({
						item: item,
						qty: qty,
						satuan: satuan,
						harga: harga,
						diskon: diskon,
						keterangan:keterangan,
						jenis_diskon: jenis_diskon
					});
				}
			});


			form_data.append("itemList", JSON.stringify(itemList));

			for(var fl = 0; fl < fileList.length; fl++) {
				form_data.append("fileList[]", fileList[fl]);
			}

			if(
				itemList.length > 0 &&
				tanggal != ""
			) {
				$.ajax({
					url:__HOSTAPI__ + "/PO",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					processData: false,
					contentType: false,
					data: form_data,
					success: function(resp) {
						//console.log(resp);
						if(resp.response_package.po_master.response_result > 0) {
							location.href = __HOSTNAME__ + '/inventori/po';
						}
					},
					error: function(resp) {
						$("#btnSubmitPO").attr({
							"disabled": "disabled"
						}).html("<i class=\"fa fa-save\"></i> Sedang Proses").removeClass("btn-success");
						console.clear();
						console.log(resp);
					}
				});
			} else {
				if(itemList.length == 0) {
					notification ("warning", "Input item PO", 3000, "warning_item_po");
					$("a[href=\"#tab-po-1\"]").click();
				}

				if(tanggal == "") {
					notification ("warning", "Tanggal PO Kosong", 3000, "warning_tanggal_po");
					$("a[href=\"#tab-po-1\"]").click().promise().done(function(){
						setInterval(function(){
							$("#txt_tanggal").focus();
						}, 1000);
					});

				}
			}
			return false;
		});
	});
</script>

<div id="form-upload-document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Upload Document</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<canvas style="width: 100%; border: solid 1px #ccc;" id="pdfViewer"></canvas>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitDocument">Submit</button>
			</div>
		</div>
	</div>
</div>