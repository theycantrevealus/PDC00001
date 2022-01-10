<script type="text/javascript">
	$(function() {
        var currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#opname_notif_amprah");
        reCheckStatus(currentStatus);
        function reCheckStatus(currentStatus) {
            if(currentStatus === "A") {
                $(".disable-panel-opname").hide();
                $("#btnSubmitAmprah").removeAttr("disabled");
            } else {
                $(".disable-panel-opname").show();
                $("#btnSubmitAmprah").attr({
                    "disabled": "disabled"
                });
            }
        }
	    $("#txt_tanggal").datepicker({
			dateFormat: 'DD, dd MM yy',
			autoclose: true
		}).datepicker("setDate", new Date());


		if(__UNIT__.response_data !== undefined) {
			if(__UNIT__.response_data.length > 0) {
				$("#txt_unit").val(__UNIT__.response_data[0].nama);
			}
		} else {
			$("#txt_unit").val(__UNIT__.nama);
		}
		
		$("#txt_nama").val(__MY_NAME__);

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
			
			var item = document.createElement("TD");
			var itemSelector = document.createElement("SELECT");
			//load_product(itemSelector);
			$(item).append(itemSelector).append("<br /><br /><span class=\"kentut\"></span>");
			$(itemSelector).select2({
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

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid,
                                    stok: item.batch,
                                    satuan_terkecil: item.satuan_terkecil.nama
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                var totalStok = 0;
                for(var a in data.stok) {
                    console.log(data.stok[a]);
                    if(data.stok[a].gudang.uid === __GUDANG_UTAMA__) {
                        totalStok += data.stok[a].stok_terkini
                    }
                }

                console.log(totalStok);

                if(data.satuan_terkecil != undefined) {
                    $(this).children("[value=\""+ data.id + "\"]").attr({
                        "satuan-caption": data.satuan_terkecil,
                        "stok": totalStok
                    });
                } else {
                    return false;
                }
            });

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
                $(this).find("td:eq(1) span.kentut").attr("id", "stok_" + (e + 1));
				$(this).find("td:eq(2)").attr("id", "satuan_" + (e + 1));
				$(this).find("td:eq(3) input").attr("id", "qty_" + (e + 1));
			});
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

		$("body").on("select2:select", ".item-amprah", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			$("#satuan_" + id).html($(this).find("option:selected").attr("satuan-caption"));
            $("#stok_" + id).html("Stok gudang : " + $(this).find("option:selected").attr("stok"));
			if($("#qty_" + id).inputmask("unmaskedvalue") > 0 && $("#row_" + id).hasClass("new-row") && $("#item_" + id).val() != "none") {
				autoTable("#table-detail-amprah");
			}			
		});

		$("body").on("keyup", ".qty", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			if($(this).inputmask("unmaskedvalue") > 0 && $("#row_" + id).hasClass("new-row") && $("#item_" + id).val() != "none") {
				autoTable("#table-detail-amprah");
			}
		});

		var metaData = {};

		$("#btnSubmitAmprah").click(function() {
			//Prepare Verifikasi
			$("#table-detail-amprah tbody tr").each(function(e) {
				if(!$(this).hasClass("new-row")) {
					var item = $(this).find("td:eq(1) select").val();
					if(metaData[item] == undefined) {
						metaData[item] = {
							nama : "",
							satuan : "",
							qty : 0
						};
					}

					metaData[item].nama = $(this).find("td:eq(1) select option:selected").text();
					metaData[item].satuan = $(this).find("td:eq(2)").text();
					metaData[item].qty = parseFloat($(this).find("td:eq(3) input").inputmask("unmaskedvalue"));
				}
			});

			if(!jQuery.isEmptyObject(metaData)) {
				$("#table-verifikasi tbody tr").remove();
			
				var autonum = 1;
				for(var key in metaData) {
					var verif_row = document.createElement("TR");

					var verif_num = document.createElement("TD");
					var verif_item = document.createElement("TD");
					var verif_satuan = document.createElement("TD");
					var verif_qty = document.createElement("TD");

					$(verif_row).append(verif_num);
					$(verif_row).append(verif_item);
					$(verif_row).append(verif_satuan);
					$(verif_row).append(verif_qty);

					$(verif_num).html(autonum);
					$(verif_item).html(metaData[key].nama);
					$(verif_satuan).html(metaData[key].satuan);
					$(verif_qty).html(metaData[key].qty).addClass("number_style");

					$("#table-verifikasi tbody").append(verif_row);
					autonum++;
				}

				$("#verif_unit").html($("#txt_unit").val());
				$("#verif_tanggal").html($("#txt_tanggal").val());
				$("#verif_nama").html($("#txt_nama").val());
				$("#verif_keterangan").html($("#txt_keterangan").val());
				$("#form-verifikasi-amprah").modal("show");
			} else {
				notification ("danger", "Isi data permintaan amprah", 3000, "detail_amprah");
			}
		});

		
		$("#btnSubmitVerifikasi").click(function() {
			var tanggal_amprah = $("#txt_tanggal").datepicker("getDate");
			var tanggal_amprah = new Date($("#txt_tanggal").datepicker("getDate"));
			var parseTanggal_amprah =  tanggal_amprah.getFullYear() + "-" + str_pad(2, tanggal_amprah.getMonth()+1) + "-" + str_pad(2, tanggal_amprah.getDate());
			var conf = confirm("Proses Amprah?");
			if(conf) {
				$("#btnSubmitVerifikasi").attr("disabled", "disabled");
				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async:false,
					data:{
						request : "tambah_amprah",
						tanggal : parseTanggal_amprah,
						keterangan : $("#txt_keterangan").val(),
						item : metaData
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							//Notif Amprah Untuk Apotek
							if(__MY_PRIVILEGES__.response_data[0].uid === __UIDAPOTEKER__) {
								push_socket(
									__ME__,
									"amprah_new_approval_request",
									__UIDKARUAPOTEKER__,
									"Permohonan Amprah Baru",
									"info").then(function() {
									notification ("success", "Amprah berhasil di ajukan kepada karu apotek", 3000, "hasil_amprah");
									location.href = __HOSTNAME__ + "/inventori/amprah";
								});
							} else {
								push_socket(
									__ME__,
									"amprah_new_approved",
									__UIDKARUAPOTEKER__,
									"Permohonan Amprah Baru",
									"info"
								).then(function() {
									notification ("success", "Amprah berhasil di ajukan", 3000, "hasil_amprah");
									location.href = __HOSTNAME__ + "/inventori/amprah";
								});
							}
						} else {
							notification ("danger", "Amprah gagal di proses", 3000, "hasil_amprah");
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
						<h4 class="text-center">Bukti Amprah</h4>
						<br />
					</div>
					<div class="col-6">
						<table class="table form-mode">
							<tr>
								<td class="wrap_content">Unit Pengamprah</td>
								<td class="wrap_content">:</td>
								<td id="verif_unit"></td>
							</tr>
							<tr>
								<td class="wrap_content">Nama Pengamprah</td>
								<td class="wrap_content">:</td>
								<td id="verif_nama"></td>
							</tr>
						</table>
					</div>
					<div class="col-6">
						<table class="table form-mode">
							<tr>
								<td class="wrap_content">Tanggal Amprah</td>
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
									<th class="wrap_content">Satuan</th>
									<th class="wrap_content">Permintaan</th>
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
				<button type="button" class="btn btn-primary" id="btnSubmitVerifikasi">Proses Amprah</button>
			</div>
		</div>
	</div>
</div>