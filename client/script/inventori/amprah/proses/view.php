<script type="text/javascript">
	$(function() {
		var metaData = {};
		var unit_pengamprah = {};
		var pegawai_pengamprah = "";
		var selectedItem = "";
		var targetJumlahAmprah = 0;

		//Load Stok dari unit pengamprah
        var tableStokPengamprah = $("#table-monitor-batch").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[10, 15, -1], [10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d){
                    d.request = "get_stok_batch_unit";
                    d.gudang = unit_pengamprah.gudang;
                    d.barang = selectedItem;
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    for(var a in dataSet) {
                        if(parseFloat(dataSet[a].stok_terkini) > 0) {
                            returnedData.push(dataSet[a]);
                        }
                    }

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Batch"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.batch;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.expired_date;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6>" + number_format(row.stok_terkini, 2, ".", ",") + "</h6>";
                    }
                }
            ]
        });




		$.ajax({
			url:__HOSTAPI__ + "/Inventori/get_amprah_detail/" + __PAGES__[4],
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				var data = response.response_package.response_data[0];
				$("#verif_kode").html(data.kode_amprah);
				$("#verif_nama").html(data.pegawai_detail.nama);
				unit_pengamprah = data.pegawai_detail.unit_detail;
				pegawai_pengamprah = data.pegawai_detail.uid;
				$("#verif_unit").html(data.pegawai_detail.unit_detail.kode + " - " + data.pegawai_detail.unit_detail.nama);
				$("#verif_tanggal").html(data.tanggal);

				for(var key in data.amprah_detail) {


					if(metaData[data.amprah_detail[key].item.uid] == undefined) {
						metaData[data.amprah_detail[key].item.uid] = {
							nama : data.amprah_detail[key].item.nama,
							batch : data.amprah_detail[key].batch.response_data,
							totalRequest : 0,
							keterangan : ""
						};
					}


					var viewer_row = document.createElement("TR");

					var col_autonum = document.createElement("TD");
					var col_obat = document.createElement("TD");
					var col_satuan = document.createElement("TD");
					var col_permintaan = document.createElement("TD");
					var col_jumlah = document.createElement("TD");
					var col_batch = document.createElement("TD");

					$(col_autonum).html((parseInt(key) + 1));
					$(col_obat).html(data.amprah_detail[key].item.nama).attr({
						"id": "barang_" + data.amprah_detail[key].id
					});
					$(col_satuan).html(data.amprah_detail[key].item.satuan_terkecil.nama);
					$(col_permintaan).html(number_format(data.amprah_detail[key].jumlah, 2, ".", ",")).addClass("number_style").attr({
						"id": "request_qty_" + data.amprah_detail[key].item.uid
					});

					var jumlah_disetujui = document.createElement("BUTTON");
					$(jumlah_disetujui).attr({
						"id": "qty_" + data.amprah_detail[key].item.uid
					}).addClass("btn btn-sm btn-info qty pull-right").html("<i class=\"fa fa-pencil-alt\"></i>");
					$(col_batch).append(jumlah_disetujui).append("<ol id=\"item_batch_" + data.amprah_detail[key].item.uid + "\"></ol><p style=\"padding-left: 50px;\" id=\"keterangan_amprah_" + data.amprah_detail[key].item.uid + "\"></p>");
					
					$(col_jumlah).attr({
						"id": "qty_disetujui_" + data.amprah_detail[key].item.uid
					}).addClass("number_style").html(0);

					$(viewer_row).append(col_autonum);
					$(viewer_row).append(col_obat);
					$(viewer_row).append(col_satuan);
					$(viewer_row).append(col_permintaan);
					$(viewer_row).append(col_jumlah);
					$(viewer_row).append(col_batch);

					$("#table-verifikasi tbody").append(viewer_row);
				}

				$("#verif_keterangan").html(data.keterangan);
			},
			error: function(response) {
				console.log(response);
			}
		});

		function TotalAllQty() {
            var totalAll = 0;
            $("#table-batch tbody tr").each(function (e) {
                var totalRow = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
                totalAll += parseFloat(totalRow);
            });

            $("#total_dipenuhi").html(number_format(totalAll, 2, ".", ","));
        }

		$("body").on("keyup", ".batch_qty", function() {
		    TotalAllQty();
        });

		$("body").on("click", ".qty", function() {
		    var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			selectedItem = id;

			tableStokPengamprah.ajax.reload();

			$("#keterangan_per_item").val($("#keterangan_amprah_" + selectedItem).text());

			if(metaData[id] != undefined) {
				$("#table-batch tbody tr").remove();
				//$("#table-monitor-batch tbody tr").remove();
                targetJumlahAmprah = parseFloat($("#request_qty_" + id).html().replaceAll(",",""));
				
				$("#target_batch_amprah").html(metaData[id].nama);
				$("#qty_batch_amprah").html($("#request_qty_" + id).html());
				$("#unit_pengamprah").html(unit_pengamprah.nama);
				//alert($("#qty_" + id).html());
				
				for(var key in metaData[id].batch) {
					var targetDisetujui = (metaData[selectedItem].batch[key].disetujui == undefined) ? 0 : metaData[selectedItem].batch[key].disetujui;
					if(metaData[id].batch[key].gudang != null && metaData[id].batch[key].gudang.uid == __GUDANG_UTAMA__ && metaData[id].batch[key].stok_terkini > 0) {
						console.log(metaData[id].batch[key]);
						var batchRow = document.createElement("TR");
						$(batchRow).attr({
							"id":"batch_" + metaData[id].batch[key].batch
						});

						var batchAutoNumCont = document.createElement("TD");
						$(batchAutoNumCont).html((parseInt(key) + 1));

						var batchKodeCont = document.createElement("TD");
						$(batchKodeCont).html(metaData[id].batch[key].kode + "<br /><b>Exp : " + metaData[id].batch[key].expired + "</b>");

						var batchStokCont = document.createElement("TD");
						$(batchStokCont).html(number_format(metaData[id].batch[key].stok_terkini, 2, ".", ",")).addClass("number_style").attr({
                            "jumlah": metaData[id].batch[key].stok_terkini
                        });

						var batchJumlahCont = document.createElement("TD");
						var batchProsesJumlah = document.createElement("INPUT");
						$(batchProsesJumlah).addClass("form-control batch_qty").inputmask({
							alias: 'currency',
							rightAlign: true,
							placeholder: "0,00",
							prefix: "",
							autoGroup: false,
							digitsOptional: true,
							min: 0,
							max: metaData[id].batch[key].stok_terkini
						}).val((metaData[selectedItem].batch[key].disetujui == undefined) ? 0 : metaData[selectedItem].batch[key].disetujui);
						$(batchJumlahCont).append(batchProsesJumlah);

						if(metaData[id].batch[key].gudang.uid == __GUDANG_UTAMA__) {
							$(batchRow).append(batchAutoNumCont);
							$(batchRow).append(batchKodeCont);
							$(batchRow).append(batchStokCont);
							$(batchRow).append(batchJumlahCont);
						} else {
							// $(batchRow).append(batchAutoNumCont);
							// $(batchRow).append(batchKodeCont);
							// $(batchRow).append(batchStokCont);
						}

						$("#table-batch tbody").append(batchRow);
					}
				}
                $("#table-batch tfoot").remove();
                $("#table-batch").append("<tfoot><tr>" +
                    "<td colspan=\"3\" class=\"text-right\"><b>TOTAL</b></td>" +
                    "<td class=\"number_style\" id=\"total_dipenuhi\">0.00</td>" +
                    "</tr></tfoot>");
                TotalAllQty();
				$("#form-batch-barang").modal("show");
			}
		});

		$("#btnSubmitBatch").click(function() {
			var totalRequest = 0;
			$("#table-batch tbody tr").each(function() {
				var currentBatch = $(this).attr("id").split("_");
				currentBatch = currentBatch[currentBatch.length - 1];

				var currentCount = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
				totalRequest += parseFloat(currentCount);

				for(var bKey in metaData[selectedItem].batch) {
					if(metaData[selectedItem].batch[bKey].gudang.uid == __GUDANG_UTAMA__) {
						if(metaData[selectedItem].batch[bKey].batch == currentBatch) {
							if(metaData[selectedItem].batch[bKey].disetujui == undefined) {
								metaData[selectedItem].batch[bKey].disetujui = 0;
							}
							
							metaData[selectedItem].batch[bKey].disetujui = parseFloat(currentCount);
						}
					}
				}
			});

			if(parseFloat(totalRequest) != parseFloat(targetJumlahAmprah)) {
				if($("#keterangan_per_item").val() != "") {
					console.log('Waw');
					$("ol#item_batch_" + selectedItem + " li").remove();
					for(var bKey in metaData[selectedItem].batch) {
						var newListBatch = document.createElement("LI");

						if(parseFloat(metaData[selectedItem].batch[bKey].disetujui) > 0) {
							$(newListBatch).html(metaData[selectedItem].batch[bKey].kode + " - [<b>" + metaData[selectedItem].batch[bKey].expired + "</b>] <b style=\"padding-right: 120px; float: right\" class=\"text-info\">(" + metaData[selectedItem].batch[bKey].disetujui + ")</b>");
							$("ol#item_batch_" + selectedItem).append(newListBatch);
						}
					}
					$("p#keterangan_amprah_" + selectedItem).html($("#keterangan_per_item").val());
					metaData[selectedItem].totalRequest = totalRequest;
					metaData[selectedItem].keterangan = $("#keterangan_per_item").val();
					$("#qty_disetujui_" + selectedItem).html(totalRequest);
					$("#form-batch-barang").modal("hide");
				} else {
					console.log('Waw');
					$("#keterangan_per_item").focus();
					notification ("danger", "Jumlah tidak memenuhi permintaan. Wajib isi keterangan", 3000, "proceed_amprah");
				}
			} else {
				console.log("Total Request : " + totalRequest);
				console.log("Amprah Request : " + targetJumlahAmprah);
				
                $("ol#item_batch_" + selectedItem + " li").remove();
                for(var bKey in metaData[selectedItem].batch) {
                    var newListBatch = document.createElement("LI");

                    if(metaData[selectedItem].batch[bKey].disetujui > 0) {
                        $(newListBatch).html(metaData[selectedItem].batch[bKey].kode + " - [<b>" + metaData[selectedItem].batch[bKey].expired + "</b>] <b style=\"padding-right: 120px; float: right\" class=\"text-info\">(" + metaData[selectedItem].batch[bKey].disetujui + ")</b>");
                        $("ol#item_batch_" + selectedItem).append(newListBatch);
                    }
                }
                $("p#keterangan_amprah_" + selectedItem).html($("#keterangan_per_item").val());
                metaData[selectedItem].totalRequest = totalRequest;
                metaData[selectedItem].keterangan = $("#keterangan_per_item").val();
                $("#qty_disetujui_" + selectedItem).html(totalRequest);
                $("#form-batch-barang").modal("hide");
            }
		});

		$("#btn_penuhi").click(function () {
		    var totalTerpenuhi = 0;
		    var sisaTerpenuhi = targetJumlahAmprah;
            $("#table-batch tbody tr").each(function (e) {
                var totalRow = $(this).find("td:eq(2)").attr("jumlah");
                if(sisaTerpenuhi <= totalRow) {
                    totalTerpenuhi += sisaTerpenuhi;
                    $(this).find("td:eq(3) input").val(sisaTerpenuhi);
                    return false;
                } else {
                    $(this).find("td:eq(3) input").val(totalRow);
                    sisaTerpenuhi -= totalRow;
                }

                totalTerpenuhi += parseFloat(totalRow);
            });

            $("#total_dipenuhi").html(number_format(totalTerpenuhi, 2, ".", ","));
        });

		$("#btnSubmitProsesAmprah").click(function() {
			var conf = confirm("Proses Amprah?");
			if(conf) {
				$("#btnSubmitProsesAmprah").attr({
					"disabled" : "disabled"
				});
				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async:false,
					data:{
						request:"proses_amprah",
						amprah: __PAGES__[4],
						dari_unit : unit_pengamprah.uid,
						pegawai_pengamprah: pegawai_pengamprah,
						data:metaData
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							location.href = __HOSTNAME__ + "/inventori/amprah/proses";
						} else {
							$("#btnSubmitProsesAmprah").removeAttr("disabled");
						}
					},
					error: function(response) {
						console.log(response);
						$("#btnSubmitProsesAmprah").removeAttr("disabled");
					}
				});
			}
			return false;
		});
	});
</script>




<div id="form-batch-barang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Penentuan Batch</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-6" style="margin-bottom: 10px">
						<table class="table form-mode">
							<tr>
								<td>Nama Barang</td>
								<td class="wrap_content">:</td>
								<td>
									<b id="target_batch_amprah"></b>
								</td>
							</tr>
							<tr>
								<td>Jumlah Permintaan</td>
								<td class="wrap_content">:</td>
								<td>
									<b id="qty_batch_amprah"></b>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<h5 class="uppercase">Proses Batch</h5>
						<table id="table-batch" class="table table-bordered">
							<thead class="thead-dark">
								<tr>
									<th class="wrap_content">No</th>
									<th>Batch</th>
                                    <th>Tersedia</th>
									<th>Jumlah</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
                        <button class="btn btn-info pull-right" id="btn_penuhi">
                            <i class="fa fa-check-circle"></i> Penuhi Permintaan
                        </button>
					</div>
					<div class="col-6">
						<h5 class="uppercase">Monitor Sisa Stok Unit <b id="unit_pengamprah"></b></h5>
						<table id="table-monitor-batch" class="table table-bordered">
							<thead class="thead-dark">
								<tr>
									<th class="wrap_content">No</th>
									<th>Batch</th>
                                    <th>Expired</th>
									<th>Tersedia</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-12">
                        <br /><br />
						<b>Keterangan:</b>
						<textarea class="form-control" id="keterangan_per_item"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tolak Permintaan</button>
				<button type="button" class="btn btn-primary" id="btnSubmitBatch">Proses Batch</button>
			</div>
		</div>
	</div>
</div>