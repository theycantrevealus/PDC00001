<script type="text/javascript">
	
	$(function(){
		function load_gudang(target) {
			var gudangData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target + " option").remove();
					gudangData = response.response_package.response_data;
					for(var a in gudangData) {
						var newOption = document.createElement("OPTION");
						$(newOption).html(gudangData[a].nama).attr({
							"value":gudangData[a].uid
						});
						$(target).append(newOption);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
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

		load_product_resep("#txt_obat");
		load_gudang("#txt_gudang");
		load_gudang("#target_gudang_import");

		load_product_resep("#txt_obat_tambah");
		load_gudang("#txt_gudang_tambah");

		$("#txt_obat").select2();
		$("#txt_obat_tambah").select2();
		$("#txt_gudang").select2();
		$("#txt_gudang_tambah").select2();
		$("#target_gudang_import").select2({
            dropdownParent: $("#review-import")
        });
		$("#txt_qty_tambah").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		})

		

		var tableStokAwal = $("#table-stok-awal").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "get_stok_log_backend";
                    d.gudang = $("#txt_gudang").val();
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {

                    var returnData = [];



                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }



                    for(var dataKey in returnedData) {
                        if(returnedData[dataKey].gudang == $("#txt_gudang").val()) {
                            if(
                                //returnedData[dataKey].kategori != null &&
                                returnedData[dataKey].barang !== null
                            ) {
                                returnData.push(returnedData[dataKey]);
                            }
                        }
                    }


                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Obat"
            },

			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
					    if(row.barang !== null) {
                            return row.barang.nama;
                        } else {
                            return "";
                        }
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return (row["batch"]["batch"] !== undefined && row["batch"]["batch"] !== null) ? row["batch"]["batch"] : "-";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["batch"]["expired_date"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + row["masuk"] + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + row["keluar"] + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + row["saldo"] + "</h6>";
					}
				}
			]
		});

		$("#txt_gudang").change(function() {
			tableStokAwal.ajax.reload();
		});

		$("#tambahStokAwal").click(function() {
			$("#form-tambah").modal("show");
		});

		$("#btnSubmitStokAwal").click(function() {
			var gudang = $("#txt_gudang_tambah").val();
			var item = $("#txt_obat_tambah").val();
			var batch = $("#txt_batch_tambah").val();
			var qty = $("#txt_qty_tambah").inputmask("unmaskedvalue");
			var rawExp = $("#txt_exp_tambah").datepicker("getDate");
			var exp =  rawExp.getFullYear() + "-" + str_pad(2, rawExp.getMonth()+1) + "-" + str_pad(2, rawExp.getDate());
			
			if(batch!= "" && qty > 0) {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async:false,
					data:{
						request: "tambah_stok_awal",
						gudang: gudang,
						item: item,
						batch: batch,
						qty: qty,
						exp: exp
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							tableStokAwal.ajax.reload();
							$("#form-tambah").modal("hide");

							$("#txt_qty_tambah").val(0);
							$("#txt_batch_tambah").val("");
							$("#txt_exp_tambah").val("").datepicker("update");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}

			return false;
		});








        var generated_data = [];

        $("#btn-import").click(function () {
            $("#form-import").modal("show");
            return false;
        });

        $("#importStokAwal").click(function() {
            $("#review-import").modal("show");
        });

        $("#upload_csv").submit(function(event) {
            event.preventDefault();
            $("#csv_file_data").html("<h6 class=\"text-center\">Load Data...</h6>");
            var formData = new FormData(this);
            formData.append("request", "stok_import_fetch");
            $.ajax({
                url: __HOSTAPI__ + "/Inventori",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    $("#review-import").modal();

                    var data = response.response_package;
                    console.clear();
                    console.log(data);

                    $("#csv_file_data").html("");
                    var thead = "";
                    if(data.column)
                    {
                        thead += "<tr>";
                        for(var count = 0; count < data.column.length; count++)
                        {
                            thead += "<th>"+data.column[count]+"</th>";
                        }
                        thead += "</tr>";
                    }
                    var table_view = document.createElement("TABLE");
                    $(table_view).append("<thead class=\"thead-dark\">" + thead + "</thead>");
                    $("#csv_file_data").append(table_view);
                    var filtedData = [];

                    for(var aa in data.row_data) {
                        if($("#target_gudang_import").val() === __GUDANG_UTAMA__) {
                            filtedData.push(data.row_data[aa]);
                        } else {
                            if(data.row_data[aa].stok > 0) {
                                filtedData.push(data.row_data[aa]);
                            }
                        }
                    }
                    console.log(data);
                    generated_data = filtedData;
                    $(table_view).addClass("table table-responsive table-bordered table-striped largeDataType").DataTable({
                        data:filtedData,
                        columns : data.column_builder
                    });

                    $("#upload_csv")[0].reset();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });


        $("#import_data").click(function () {
            Swal.fire({
                title: 'Proses import data?',
                showDenyButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Batal`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#csv_file_data").html("<h6 class=\"text-center\">Importing...</h6>");
                    $("#import_data").attr("disabled", "disabled");
                    $("#csv_file").attr("disabled", "disabled");
                    $.ajax({
                        url: __HOSTAPI__ + "/Inventori",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "proceed_import_stok",
                            gudang:$("#target_gudang_import").val(),
                            data_import:generated_data
                        },
                        success:function(response)
                        {
                            console.clear();
                            console.log(response);
                            var html = "Imported : " + response.response_package.success_proceed + "<br />";
                            var failedData = response.response_package.failed_data;
                            var failedResult = document.createElement("table");
                            $(failedResult).addClass("table").append("<thead class=\"thead-dark\">" +
                            "<tr>" +
                            "<th>Nama</th>" +
                            "<th>Satuan</th>" +
                            "<th>Harga</th>" +
                            "<th>Kedaluwarsa</th>" +
                            "<th>Stok</th></tr></thead><tbody></tbody>");

                            $("#csv_file_data").html(html).append(failedResult);
                            $(failedResult).DataTable({
                                data: failedData,
                                columns: [
                                    { data: "nama" },
                                    { data: "satuan" },
                                    { data: "harga" },
                                    { data: "kedaluarsa" },
                                    { data: "stok" }
                                ]
                            });
                            tableStokAwal.ajax.reload();
                            $("#import_data").removeAttr("disabled");
                            $("#csv_file").removeAttr("disabled");
                        },
                        error: function (response) {
                            $("#csv_file_data").html(response);
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {
                    //
                }
            });
        });
	});

</script>





<div id="review-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Import Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">CSV</h5>
                                <form id="upload_csv" method="post" enctype="multipart/form-data">
                                    <input type="file" name="csv_file" id="csv_file" accept=".csv" />
                                    <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />
                                </form>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade">
                                    <div class="row">
                                        <div class="col-md-3">
                                            Tujuan Gudang :<br />
                                            <b class="text-info">
                                                <i class="fa fa-info-circle"></i> Pastikan gudang terpilih dengan benar
                                            </b>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="target_gudang_import"></select>
                                        </div>
                                        <div class="col-md-12">
                                            <hr />
                                            <div id="csv_file_data" style="overflow-y: scroll" class="table-responsive"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="import_data">Import</button>
            </div>
        </div>
    </div>
</div>


<div id="form-tambah" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Stok Awal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="col-md-6">
                    <div class="form-group col-md-6">
                        <label for="txt_no_skp">Gudang:</label>
                        <select class="form-control" id="txt_gudang_tambah"></select>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="txt_no_skp">Item:</label>
                        <select class="form-control" id="txt_obat_tambah"></select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txt_no_skp">Batch:</label>
                        <input type="text" class="form-control uppercase" id="txt_batch_tambah" />
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txt_no_skp">Tanggal Kadaluarsa:</label>
                        <input type="text" class="form-control txt_tanggal" id="txt_exp_tambah" readonly />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="txt_no_skp">Saldo:</label>
                        <input type="text" class="form-control" id="txt_qty_tambah" />
                    </div>
                </div>
				<div class="col-md-6">

                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitStokAwal">Tambah</button>
			</div>
		</div>
	</div>
</div>