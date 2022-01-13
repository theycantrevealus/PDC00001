<script type="text/javascript">
	$(function() {
        var currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#opname_notif_amprah");
        reCheckStatus(currentStatus);
        function reCheckStatus(currentStatus) {
            if(currentStatus === "A") {
                $(".disable-panel-opname").hide();
                $("#btnSubmitAmprah").show();
                $("#btnSubmitAmprah").removeAttr("disabled");
            } else {
                $(".disable-panel-opname").show();
                $("#btnSubmitAmprah").hide();
                $("#btnSubmitAmprah").attr({
                    "disabled": "disabled"
                });
            }
        }
		var metaDataOpname = {};
		$("#txt_tanggal").datepicker({
			dateFormat: 'DD, dd MM yy',
			autoclose: true
		}).datepicker("setDate", new Date());

		$("#txt_unit_asal").val(__UNIT__.nama).attr({
            "disabled": "disabled"
        });
		$("#txt_nama").val(__MY_NAME__);
		load_unit("#txt_unit_asal", "", __UNIT__.uid);
		load_unit("#txt_unit_tujuan", __UNIT__.uid);
		$("#txt_unit_asal").select2();
		$("#txt_unit_tujuan").select2();

		autoTable("#table-detail-amprah");

		function autoTable(target, setter = {
			item: "",
			satuan : "",
			permintaan : ""
		}) {
			$(target + " tbody tr").removeClass("new-row");
			var row = document.createElement("TR");
			$(row).addClass("new-row");
			var num = document.createElement("TD");

            $(num).addClass("autonum");
			
			var item = document.createElement("TD");
			var itemSelector = document.createElement("SELECT");
			load_product(itemSelector);
			$(item).append(itemSelector);
			$(itemSelector).select2().addClass("form-control item-amprah");

			var satuan = document.createElement("TD");
			var permintaan = document.createElement("TD");
			var permintaanInput = document.createElement("INPUT");
			$(permintaan).append(permintaanInput);
			$(permintaanInput).inputmask({
				alias: 'currency', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
			}).addClass("form-control qty");

			var aksi = document.createElement("TD");
			var btnDelete = document.createElement("BUTTON");
			$(btnDelete).addClass("btn btn-danger btn-sm").html("<i class=\"fa fa-trash\"></i>");
			$(aksi).append(btnDelete);

			$(row).append(num);
			$(row).append(item);
			$(row).append(satuan);
			$(row).append(permintaan);
			$(row).append(aksi);
			$(target + " tbody").append(row);

			rebaseTable(target);
		}


		function rebaseTable(target) {
			$(target).find("tbody tr").each(function(e) {
				$(this).attr("id", "row_" + (e + 1));
				$(this).find("td:eq(0)").html((e + 1));
				$(this).find("td:eq(1) select").attr("id", "item_" + (e + 1));
				$(this).find("td:eq(2)").attr("id", "satuan_" + (e + 1));
				$(this).find("td:eq(3) input").attr("id", "qty_" + (e + 1));
			});
		}


		function load_satuan(target, selected = "") {
			var satuanData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/satuan",
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

		function load_unit(target, exclude, selected = "") {
			var unitData;
			$.ajax({
				url:__HOSTAPI__ + "/Unit/get_unit",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					unitData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < unitData.length; a++) {
						if(unitData[a].uid != exclude) {
						    $(target).append("<option " + ((unitData[a].status === "A") ? "" : "disabled") + " " + ((unitData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + unitData[a].gudang.uid + "\">" + unitData[a].nama + " " + ((unitData[a].status === "A") ? "" : "(Opname)") + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return unitData;
		}

		function load_product(target, selectedData = "", appendData = true) {
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
					$(target).append("<option value=\"none\">Pilih Item</option>");
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




		var tableCurrentStock = $("#table-detail-mutasi").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[100], ["100"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_stok_gudang";
					d.gudang = /*__UNIT__.gudang*/ $("#txt_unit_asal").val();
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
						if(metaDataOpname[dataSet[a].uid] === undefined) {
							metaDataOpname[dataSet[a].uid] = {};
						}

						if(metaDataOpname[dataSet[a].uid][dataSet[a].batch.uid] === undefined) {
							metaDataOpname[dataSet[a].uid][dataSet[a].batch.uid] = {
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
						return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<b id=\"item_identifier_" + row.barang + "|" + row.batch.uid + "\">" + row.nama + "</b>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span>" + row.batch.batch + "</span><br /><b>" + row.batch.expired_date + "</b>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.stok_terkini;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.satuan_terkecil.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control aktual_qty\" id=\"item_" + row.uid + "\" batch=\"" + row.batch.uid + "\" value=\"" + metaDataOpname[row.uid][row.batch.uid].nilai + "\" placeholder=\"0.00\" />";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<input type=\"text\" class=\"form-control\" placeholder=\"Keterangan per-item\" />";
					}
				}
			]
		}).on("draw.dt", function (e) {
			$(".aktual_qty").each(function() {
				var id = $(this).attr("id").split("_");
				id = id[id.length - 1];

				var batch = $(this).attr("batch");

				$(this).inputmask({
					alias: 'decimal',
					rightAlign: true,
					placeholder: "0.00",
					prefix: "",
					autoGroup: false,
					digitsOptional: true
				}).val(metaDataOpname[id][batch].nilai);
			});
		});

		$("body").on("keyup", ".aktual_qty", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var batch = $(this).attr("batch");
			if($(this).inputmask("unmaskedvalue") > 0) {
				//autoTable("#table-detail-amprah");
				metaDataOpname[id][batch].nilai = $(this).inputmask("unmaskedvalue");
			}
		});

        $("#txt_unit_asal").change(function () {
            tableCurrentStock.ajax.reload();
        });

		var metaData = {};

		$("#btnSubmitAmprah").click(function() {
			//Prepare Verifikasi
            var allowSave = false;
			$("#table-detail-mutasi tbody tr").each(function(e) {
				if(!$(this).hasClass("new-row")) {
					var item_uid = $(this).find("td:eq(1) b").attr("id").split("_");
					var item = item_uid[item_uid.length - 1];

					//var item = $(this).find("td:eq(1) b").html();
					if(metaData[item] == undefined) {
						metaData[item] = {
							nama : "",
							batch: "",
							satuan : "",
							stok: 0,
							mutasi : 0,
							keterangan: ""
						};
					}

					metaData[item].nama = $(this).find("td:eq(1)").html();
					metaData[item].batch = $(this).find("td:eq(2) span").html();
					metaData[item].stok = $(this).find("td:eq(3)").html();
					metaData[item].satuan = $(this).find("td:eq(4)").html();
					metaData[item].mutasi += parseFloat($(this).find("td:eq(5) input").inputmask("unmaskedvalue"));
					metaData[item].keterangan = $(this).find("td:eq(6) input").val();
				}
			});

			if(!jQuery.isEmptyObject(metaData)) {
				$("#table-verifikasi tbody tr").remove();
				var totalMutasi = 0;
				var autonum = 1;
				for(var key in metaData) {
					var verif_row = document.createElement("TR");

					var verif_num = document.createElement("TD");
					var verif_item = document.createElement("TD");
					var verif_batch = document.createElement("TD");
					var verif_satuan = document.createElement("TD");
					var verif_stok = document.createElement("TD");
					var verif_mutasi = document.createElement("TD");

					$(verif_row).append(verif_num);
					$(verif_row).append(verif_item);
					$(verif_row).append(verif_batch);
					$(verif_row).append(verif_satuan);
					$(verif_row).append(verif_stok);
					$(verif_row).append(verif_mutasi);

					$(verif_num).html(autonum);
					$(verif_item).html(metaData[key].nama);
					$(verif_batch).html(metaData[key].batch);
					$(verif_satuan).html(metaData[key].satuan);
					$(verif_stok).html(metaData[key].stok).addClass("number_style");
					$(verif_mutasi).html(metaData[key].mutasi).addClass("number_style");
					if(metaData[key].mutasi > 0) {
						$("#table-verifikasi tbody").append(verif_row);
						totalMutasi += metaData[key].mutasi;
					}
						
					autonum++;
				}

				if(totalMutasi > 0) {
                    if($("#txt_keterangan").val() !== "") {
                        $("#verif_unit_asal").html($("#txt_unit_asal option:selected").text());
                        $("#verif_unit_tujuan").html($("#txt_unit_tujuan option:selected").text());
                        $("#verif_tanggal").html($("#txt_tanggal").val());
                        $("#verif_nama").html($("#txt_nama").val());
                        $("#verif_keterangan").html($("#txt_keterangan").val());

                        $("#form-verifikasi-amprah").modal("show");
                    } else {
                        notification ("danger", "Keterangan mutasi wajib diisi", 3000, "detail_mutasi");
                        $("#txt_keterangan").focus();
                    }
				} else {
					notification ("danger", "Isi data permintaan mutasi", 3000, "detail_mutasi");
				}
					
			} else {
				notification ("danger", "Isi data permintaan mutasi", 3000, "detail_mutasi");
			}
		});

		
		$("#btnSubmitVerifikasi").click(function() {

            Swal.fire({
                title: "Proses Mutasi?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#btnSubmitVerifikasi").attr("disabled", "disabled");
                    $.ajax({
                        url:__HOSTAPI__ + "/Inventori",
                        async:false,
                        data:{
                            request : "tambah_mutasi",
                            dari: $("#txt_unit_asal").val(),
                            ke: $("#txt_unit_tujuan").val(),
                            keterangan : $("#txt_keterangan").val(),
                            item : metaData
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                notification ("success", "Mutasi Stok berhasil di proses", 3000, "hasil_mutasi");
                                //location.href = __HOSTNAME__ + "/inventori/stok/mutasi";
                            } else {
                                notification ("danger", "Mutasi Stok gagal di proses", 3000, "hasil_mutasi");
                                $("#btnSubmitVerifikasi").removeAttr("disabled");
                            }
                        },
                        error: function(response) {
                            console.log(response);
                            $("#btnSubmitVerifikasi").removeAttr("disabled");
                        }
                    });
                }
            });
		});
	});
</script>
<div id="form-verifikasi-amprah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Verifikasi Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<h4 class="text-center">Bukti Mutasi Barang</h4>
						<br />
					</div>
					<div class="col-6">
						<table class="table form-mode">
							<tr>
								<td class="wrap_content">Unit Asal</td>
								<td class="wrap_content">:</td>
								<td id="verif_unit_asal"></td>
							</tr>
							<tr>
								<td class="wrap_content">Diproses Oleh</td>
								<td class="wrap_content">:</td>
								<td id="verif_nama"></td>
							</tr>
						</table>
					</div>
					<div class="col-6">
						<table class="table form-mode">
							<tr>
								<td class="wrap_content">Unit Tujuan</td>
								<td class="wrap_content">:</td>
								<td id="verif_unit_tujuan"></td>
							</tr>
							<tr>
								<td class="wrap_content">Tanggal Mutasi</td>
								<td class="wrap_content">:</td>
								<td id="verif_tanggal"></td>
							</tr>
						</table>
					</div>
					<div class="col-12">
						<table id="table-verifikasi" class="table table-bordered">
							<thead class="thead-dark">
								<tr>
									<th class="wrap_content">No</th>
									<th>Item</th>
									<th>Batch</th>
									<th class="wrap_content">Satuan</th>
									<th class="wrap_content">Stok</th>
									<th class="wrap_content">Mutasi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-12">
						<b>Keterangan</b>
						<br />
						<p id="verif_keterangan"></p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Edit Data</button>
				<button type="button" class="btn btn-primary" id="btnSubmitVerifikasi">Proses Mutasi</button>
			</div>
		</div>
	</div>
</div>