<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		var tindakanKelas = "RAD";
		var columnBuilder = [];
		var penjaminBuilder = [];
		var tindakanBuilder = [];
		var dataBuilder;
		var tableTindakan;

		var metaData = {};

		function refresh_penjamin(target, selected = "") {
			var penjaminData = [];
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Penjamin/penjamin",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data;
					penjaminData = data;
					$(target).find("option").remove();
					for(var key in data) {
						$(target).append("<option " + ((data[key].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + data[key].uid + "\">" + data[key].nama + "</option>");
					}
					$(target).select2();
				}
			});
			return penjaminData;
		}

		function refresh_mitra(target, selected = "") {
            var mitraData = [];
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Mitra/mitra_item/LAB",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var data = response.response_package.response_data;
                    mitraData = data;
                    $(target).find("option").remove();
                    for(var key in data) {
                        $(target).append("<option " + ((data[key].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + data[key].uid + "\">" + data[key].nama + "</option>");
                    }
                    $(target).select2();
                }
            });
            return mitraData;
        }

		function refresh_tindakan(target, selected = "", oldData = {}) {
			var tindakanData = [];
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Tindakan/tindakan",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data;
					tindakanData = data;
					$(target).find("option").remove();
					for(var key in data) {
						//Check Tindakan Perpenjamin
						if(oldData[data[key].uid] == undefined) {
							$(target).append("<option " + ((data[key].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + data[key].uid + "\">" + data[key].nama + "</option>");
						}
					}
					$(target).select2();
				}
			});
			return tindakanData;
		}

		refresh_penjamin("#filter-penjamin", __UIDPENJAMINUMUM__);
		var returnProceed = refresh_kelas(tindakanKelas);
		columnBuilder = returnProceed.dataKelas;
		tableTindakan = returnProceed.table;
		dataBuilder = returnProceed.dataBuilder;

		tindakanBuilder = refresh_tindakan("#txt_tindakan", "", dataBuilder);
		penjaminBuilder = refresh_penjamin("#txt_penjamin", __UIDPENJAMINUMUM__);
        mitraBuilder = refresh_mitra("#txt_mitra");

		function refresh_kelas_data(tindakanKelas) {
			var columnKelas = {};
			var dataBuilder;
			var generateHeader = [{
				"title": "No",
				"data": "autonum"
			}, {
				"title" : "Tindakan",
				"data": "tindakan"
			}];

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Tindakan/kelas/" + tindakanKelas,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data;
					for(var key in data) {
						if(columnKelas[data[key].nama.replace(" ", "_").toLowerCase()] == undefined) {
							columnKelas[data[key].nama.replace(" ", "_").toLowerCase()] = 0;
						}
						generateHeader.push({
							"uid": data[key].uid,
							"title" : data[key].nama,
							"data": data[key].nama.replace(" ", "_").toLowerCase()
						});
					}

					generateHeader.push({
						"title" : "Aksi",
						"data": "action"
					});


					$.ajax({
						url: __HOSTAPI__ + "/Tindakan/get-harga-per-kelas/" + tindakanKelas + "/" + $("#filter-penjamin").val(),
						async: false,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type:"GET",
						success:function(response) {

							var DataPopulator = {};
							var DataPopulatorParsed = [];


							//Parse data from vertical to horizontal
							var data_harga = response.response_package;


							for(var key = 0; key < data_harga.length; key++) {
								if(data_harga[key].tindakan_detail != null) {
									var kelasTarget = data_harga[key].tindakan;
									if(DataPopulator[kelasTarget] === undefined) {
										DataPopulator[kelasTarget] = {
											uid: kelasTarget,
											nama: data_harga[key].tindakan_detail.nama
										};

										if(DataPopulator[kelasTarget].kelas_harga == undefined) {
											DataPopulator[kelasTarget].kelas_harga = columnKelas;
										}
									}
									var kelasKey = data_harga[key].kelas.nama.toLowerCase().replace(" ", "_");
									if(kelasKey in DataPopulator[kelasTarget].kelas_harga) {
										DataPopulator[kelasTarget][kelasKey] = data_harga[key].harga;
									}
								}
							}
							
							//Convert to array data
							var autonum = 1;
							for(var key in DataPopulator) {
								var parseKelas = {
									autonum: autonum,
									tindakan: "<label id=\"tindakan_" + DataPopulator[key].uid + "\">" + DataPopulator[key].nama + "</label>",
									tindakan_uid : DataPopulator[key].uid,
                                    mitra: DataPopulator[key].mitra,
									action: "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-info btn-sm btn-edit-tindakan\" tindakan=\"" + DataPopulator[key].uid + "\">" +
                                        "<span><i class=\"fa fa-pencil-alt\"></i>Edit</span></button>" +
                                        "</div>"
								};

								for(var KelasKey in DataPopulator[key]) {
									if(KelasKey in columnKelas) {
										parseKelas[KelasKey] = "<h6 class=\"text-right\">" + number_format(DataPopulator[key][KelasKey], 2, ".", ",") + "</h6>";
									}
								}

								DataPopulatorParsed.push(parseKelas);
								autonum++;
							}

							dataBuilder = DataPopulator;
						}
					});
				}
			});
			return dataBuilder;
		}



		






		//Generate class
		function refresh_kelas(tindakanKelas) {
			var columnKelas = {};
			var tableTindakan;
			var dataBuilder;
			var generateHeader = [{
				"title": "No",
				"data": "autonum"
			}, {
				"title" : "Tindakan",
				"data": "tindakan"
			}];

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Tindakan/kelas/" + tindakanKelas,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data;
					for(var key in data) {
						$("#table-tindakan thead tr").append("<th>" + data[key].nama + "</th>");
						if(columnKelas[data[key].nama.replace(" ", "_").toLowerCase()] == undefined) {
							columnKelas[data[key].nama.replace(" ", "_").toLowerCase()] = 0;
						}
						generateHeader.push({
							"uid": data[key].uid,
							"title" : data[key].nama,
							"data": data[key].nama.replace(" ", "_").toLowerCase()
						});
					}
					$("#table-tindakan thead tr").append("<th class=\"wrap_content\">Aksi</th>");

					generateHeader.push({
						"title" : "Aksi",
						"data": "action"
					});


					if(generateHeader.length == $("#table-tindakan thead th").length) {
						tableTindakan = $("#table-tindakan").DataTable({
							"ajax":{
								async: false,
								url: __HOSTAPI__ + "/Tindakan/get-harga-per-kelas/" + tindakanKelas + "/" + $("#filter-penjamin").val(),
								type: "GET",
								headers:{
									Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
								},
								dataSrc:function(response) {
									var DataPopulator = {};
									var DataPopulatorParsed = [];


									//Parse data from vertical to horizontal
									var data_harga = response.response_package;

									for(var key = 0; key < data_harga.length; key++) {
										if(data_harga[key].tindakan_detail != undefined) {
											var kelasTarget = data_harga[key].tindakan;
											
											if(DataPopulator[kelasTarget] === undefined) {
												DataPopulator[kelasTarget] = {
													uid: kelasTarget,
													nama: data_harga[key].tindakan_detail.nama
												};

												if(DataPopulator[kelasTarget].kelas_harga == undefined) {
													DataPopulator[kelasTarget].kelas_harga = columnKelas;
												}
											}

											var kelasKey = data_harga[key].kelas.nama.toLowerCase().replace(" ", "_");
											if(kelasKey in DataPopulator[kelasTarget].kelas_harga) {
												DataPopulator[kelasTarget][kelasKey] = data_harga[key].harga;
											}
										}
									}
									
									//Convert to array data
									var autonum = 1;
									for(var key in DataPopulator) {
										var parseKelas = {
											autonum: autonum,
											tindakan: "<label id=\"tindakan_" + DataPopulator[key].uid + "\">" + DataPopulator[key].nama + "</label>",
											tindakan_uid : DataPopulator[key].uid,
											action: "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                                "<button class=\"btn btn-info btn-sm btn-edit-tindakan\" tindakan=\"" + DataPopulator[key].uid + "\">" +
                                                "<span><i class=\"fa fa-pencil-alt\"></i>Edit</span></button> " +
                                                "<button class=\"btn btn-danger btn-sm btn-delete-tindakan-kelas\" tindakan=\"" + DataPopulator[key].uid + "\">" +
                                                "<span><i class=\"fa fa-trash\"></i>Hapus</span>" +
                                                "</button>" +
                                                "</div>"
										};

										for(var KelasKey in DataPopulator[key]) {
											if(KelasKey in columnKelas) {
												parseKelas[KelasKey] = "<h6 class=\"text-right\">" + number_format(DataPopulator[key][KelasKey], 2, ".", ",") + "</h6>";
											}
										}

										DataPopulatorParsed.push(parseKelas);
										autonum++;
									}
									dataBuilder = DataPopulator;
									return DataPopulatorParsed;
								}
							},
							fixedColumns:   {
								leftColumns: 2
							},
							autoWidth: false,
							aaSorting: [[0, "asc"]],
							columnDefs:[
								{"targets":0, "className":"dt-body-left"}
							],
							columns : generateHeader
						});
					}



				},
				error: function(response) {
					console.log(response);
				}
			});

			return {
				table: tableTindakan,
				dataKelas: generateHeader,
				dataBuilder: dataBuilder
			};
		}


		$("body").on("click", ".btn-edit-tindakan", function() {
			var uid = $(this).attr("tindakan");

			tindakanBuilder = refresh_tindakan("#txt_tindakan", uid);
			$("#txt_tindakan").attr("disabled", "disabled");
			dataBuilder = refresh_kelas_data(tindakanKelas);
			var tempDataBuilder = dataBuilder;

			/*$("#txt_tindakan option[value=\"" + uid + "\"").prop("selected", true);
			$("#txt_tindakan").val(uid).trigger("change");
			$("#txt_tindakan").parent().html("<h4>" + $("#tindakan_" + uid).html() + "</h4>");*/
			
			$("#form-tambah table tbody").html("");

			for(var i in columnBuilder) {

				if(
					columnBuilder[i].data != "autonum" &&
					columnBuilder[i].data != "tindakan" &&
					columnBuilder[i].data != "action"
				) {
					var newRow = document.createElement("TR");
					var newName = document.createElement("TD");
					var newPrice = document.createElement("TD");

					$(newName).html(columnBuilder[i].title);

					var newInput = document.createElement("INPUT");
					$(newInput).addClass("form-control harga-tindakan").val(tempDataBuilder[$("#txt_tindakan").val()][columnBuilder[i].data.replace(" ", "_").toLowerCase()]).attr({
						"kelas": columnBuilder[i].uid,
						"identifier": columnBuilder[i].data.replace(" ", "_").toLowerCase()
					}).inputmask({
						alias: 'currency', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
					});

					$(newPrice).append(newInput);

					$(newRow).append(newName);
					$(newRow).append(newPrice);

					$(newRow).attr("kelas", columnBuilder[i].uid);
					$("#form-tambah table tbody").append(newRow);
				}
			}

			$("#form-tambah").modal("show");
			MODE = "edit";
			return false;
		});

		
		$("body").on("click", ".btn-delete-tindakan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus tindakan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Tindakan/master_tindakan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableTindakan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-delete-tindakan-kelas", function() {
			var tindakan = $(this).attr("tindakan");
			var conf = confirm("Hapus tindakan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Tindakan/master_tindakan_kelas_harga/" + tindakan + "/" + $("#filter-penjamin").val(),
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableTindakan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
			return false;
		});

		$("#filter-penjamin").change(function() {
			var me = $(this);
			dataBuilder = refresh_kelas_data(tindakanKelas);
			tableTindakan.ajax.url(__HOSTAPI__ + "/Tindakan/get-harga-per-kelas/" + tindakanKelas + "/" + $("#filter-penjamin").val()).load();

			/*$("#txt_penjamin option[value=\"" + me.val() + "\"").prop("selected", true);
			$("#txt").val(me.val()).trigger("change");*/
		});
		
		/*$("body").on("click", ".btn-edit-tindakan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";

			let harga = $(this).data('harga');

			$("#txt_nama").val($("#nama_" + selectedUID).html());
			$("#txt_harga").val(harga);
			
			$("#form-tambah").modal("show");
			return false;
		});*/

		$("#tambah_master_tindakan").click(function() {
			$("#form-tambah-tindakan").modal("show");
			$("#txt_nama_master_tindakan_baru").val("");
			return false;
		});

		
		$("#tambah-tindakan").click(function() {
			$("#txt_nama").val("");
			$("#txt_tindakan").removeAttr("disabled");

			//Prepare Kelas
			$("#form-tambah table tbody").html("");
			for(var i in columnBuilder) {
				if(
					columnBuilder[i].data != "autonum" &&
					columnBuilder[i].data != "tindakan" &&
					columnBuilder[i].data != "action"
				) {
					var newRow = document.createElement("TR");
					var newName = document.createElement("TD");
					var newPrice = document.createElement("TD");

					$(newName).html(columnBuilder[i].title);

					var newInput = document.createElement("INPUT");
					$(newInput).addClass("form-control harga-tindakan").attr({
						"kelas": columnBuilder[i].uid,
						"identifier": columnBuilder[i].data.replace(" ", "_").toLowerCase()
					}).inputmask({
						alias: 'currency', rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
					});

					$(newPrice).append(newInput);

					$(newRow).append(newName);
					$(newRow).append(newPrice);

					$(newRow).attr("kelas", columnBuilder[i].uid);
					$("#form-tambah table tbody").append(newRow);
				}
			}

			$("#form-tambah").modal("show");
			MODE = "tambah";
		});

        $("#txt_mitra").change(function() {
            //
        });

		$("#txt_tindakan").change(function() {
			if(metaData[$("#txt_penjamin").val()] == undefined) {
				metaData[$("#txt_penjamin").val()] = {};
			}

			if(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()] == undefined) {
				metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()] = {};
			}

			$("#form-tambah table tbody tr").each(function() {
				var us = $(this);
				if(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] == undefined) {
					metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] = 0;
				}

				us.find("td:eq(1) input").val(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")]);
			});
		});

		$("#txt_penjamin").change(function() {
			var me = $(this);
			$("#filter-penjamin option[value=\"" + me.val() + "\"").prop("selected", true);
			$("#filter-penjamin").val(me.val()).trigger("change");

			tindakanBuilder = refresh_tindakan("#txt_tindakan", "", dataBuilder);

			if(metaData[$("#txt_penjamin").val()] == undefined) {
				metaData[$("#txt_penjamin").val()] = {};
			}

			if(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()] == undefined) {
				metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()] = {};
			}

			$("#form-tambah table tbody tr").each(function() {
				var us = $(this);
				if(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] == undefined) {
					metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] = 0;
				}

				us.find("td:eq(1) input").val(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")]);
			});
		});

        $("body").on("keyup", ".harga-tindakan", function() {
            var me = $(this);
            var kelas = me.attr("kelas");
            var kelasIden = me.attr("identifier");
            if($("#satu_harga").is(":checked")) {
                $("#txt_penjamin option").each(function () {
                    var penjaminType = $(this);
                    if (metaData[penjaminType.attr("value")] == undefined) {
                        metaData[penjaminType.attr("value")] = {};
                    }

                    if(metaData[penjaminType.attr("value")][$("#txt_tindakan").val()] == undefined) {
                        metaData[penjaminType.attr("value")][$("#txt_tindakan").val()] = {};
                    }
                });
            } else {
                if (metaData[$("#txt_penjamin").val()] == undefined) {
                    metaData[$("#txt_penjamin").val()] = {};
                }

                if(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()] == undefined) {
                    metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()] = {};
                }
            }



            $("#form-tambah table tbody tr").each(function() {
                var us = $(this);
                if($("#satu_harga").is(":checked")) {
                    $("#txt_penjamin option").each(function () {
                        var penjaminType = $(this);
                        if(metaData[penjaminType.attr("value")][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] == undefined) {
                            metaData[penjaminType.attr("value")][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] = 0;
                        }
                    });
                } else {
                    if(metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] == undefined) {
                        metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][us.find("td:eq(1) input").attr("kelas")] = 0;
                    }
                }
            });
            if($("#satu_harga").is(":checked")) {
                $("#txt_penjamin option").each(function () {
                    var penjaminType = $(this);
                    metaData[penjaminType.attr("value")][$("#txt_tindakan").val()][kelas] = me.inputmask("unmaskedvalue");
                });
            } else {
                metaData[$("#txt_penjamin").val()][$("#txt_tindakan").val()][kelas] = me.inputmask("unmaskedvalue");
            }
        });

		$("#btnSubmitMasterTindakan").click(function() {
			var nama = $("#txt_nama_master_tindakan_baru").val();
			if(nama != "") {
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Tindakan",
					data: {
						request: "tambah_master_tindakan",
						nama: nama
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						if(response.response_package.response_result > 0) {
							tindakanBuilder = refresh_tindakan("#txt_tindakan", response.response_package.response_unique);
							$("#form-tambah-tindakan").modal("hide");
							$("#txt_nama_master_tindakan_baru").val("");
						} else {
							notification ("warning", response.response_package.response_message, 3000, "duplicate_tindakan");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
			return false;
		});

		$("#btnSubmit").click(function() {
			var penjaminList = tindakanBuilder;
			
			var form_data = {};
			if(MODE == "tambah") {
				form_data = {
					request: "update_tindakan_kelas_harga",
					data: metaData
				};
			} else {
				form_data = {
					request: "update_tindakan_kelas_harga",
					data: metaData
				};
			}

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				data: form_data,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					metaData = {};
					dataBuilder = refresh_kelas_data(tindakanKelas);
					tindakanBuilder = refresh_tindakan("#txt_tindakan", "", dataBuilder);
					$("#form-tambah").modal("hide");
					tableTindakan.ajax.reload();
				},
				error: function(response) {
					console.log(response);
				}
			});
		});
		
		//$(".harga").inputmask({alias: 'currency', rightAlign: false, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});
	});
</script>

<div id="form-tambah" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Tindakan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-6">
						<label for="txt_nama">Nama Tindakan :</label>
						<select class="form-control" id="txt_tindakan"></select>
					</div>
					<div class="form-group col-md-4"></div>
					<div class="form-group col-md-2">
						<button id="tambah_master_tindakan" class="btn btn-info form-control" style="margin-top: 25px;">
							<i class="fa fa-plus"></i> Master Tindakan
						</button>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-5">
						<label for="txt_nama">Penjamin :</label>
						<select class="form-control" id="txt_penjamin"></select>
					</div>
                    <div class="form-group col-md-5">
                        <label for="txt_nama">Mitra :</label>
                        <select class="form-control" id="txt_mitra"></select>
                    </div>
				</div>
                <div class="row">
                    <div class="form-group col-md-5">
                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                            <input checked="" type="checkbox" id="satu_harga" class="custom-control-input">
                            <label class="custom-control-label" for="satu_harga">Yes</label>
                        </div>
                        <label for="subscribe">Satu Harga</label>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-md-12" id="kelas_loader">
						<table class="table largeDataType">
							<thead class="thead-dark">
								<tr>
									<th>Kelas</th>
									<th>Harga</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>




<div id="form-tambah-tindakan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Tindakan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_nama_master_tindakan_baru">Nama Tindakan :</label>
					<input type="text" class="form-control" id="txt_nama_master_tindakan_baru" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitMasterTindakan">Submit</button>
			</div>
		</div>
	</div>
</div>