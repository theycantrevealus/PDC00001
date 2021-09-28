<script type="text/javascript">
    
    $(function (){

        let tableJadwal = $("#table_jadwal_operasi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/KamarOperasi/jadwal_operasi",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
                    console.clear();
                    console.log(response);
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return "<h5 class=\"autonum\">" + (meta.row + meta.settings._iDisplayStart + 1) + "</h5>";
                	}  
    			},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pasien;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.jenis_operasi;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.operasi;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return "<span class=\"wrap_content\">" + row['dokter'] + "</span>";
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.ruangan;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.tgl_operasi_parsed;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.jam_mulai;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.jam_selesai;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						let status = row.status_pelaksanaan;

						if (status == 'N') {
							return '<span class="badge badge-custom-caption badge-outline-info">Akan Dilaksakan</span>';
						} else if (status == 'P') {
							return '<span class="badge badge-custom-caption badge-outline-warning">Sedang Proses</span>';
						} else if (status == 'D') {
							return '<span class="badge badge-custom-caption badge-outline-success">Selesai</span>';
						}
						
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						let btn = "";
						
						//BUTTON UNTUK STATUS PELAKSANAAN

						if (row['status_pelaksanaan'] == 'N') {
							//BUTTON TRANSAKSI (UNTUK EDIT DAN DELETE JADWAL)
							btn = "" +
								`<div class="btn-group col-md-12" role="group" aria-label="Basic example">` +
									`<a class="btn btn-info btn-sm btn_edit_jenis wrap_content" href="${__HOSTNAME__}/kamar_operasi/jadwal/edit/${row["uid"]}" data-toggle='tooltip' title='Edit'>` +
										`<span><i class="fa fa-edit"></i> Edit</span>` +
									`</a> ` +
									`<button data-uid="${row['uid']}" class="btn btn-danger btn-sm btn_delete_jadwal wrap_content" data-toggle="tooltip" title="Hapus">` +
										`<span><i class="fa fa-trash"></i> Hapus</span>` +
									`</button>` +
                                    `<button class="btn btn-warning btn-sm btn_proses_jadwal wrap_content" data-uid="${row["uid"]}" data-toggle='tooltip' title='Tandai Sedang Proses'>` +
                                        `<span><i class="fa fa-spinner"></i> Proses</span>` +
                                    `</button> ` +
								`</div>`;
						} else if (row['status_pelaksanaan'] == 'P'){
							btn = `<div class="btn-group col-md-12" role="group" aria-label="Basic example"><button data-uid="${row['uid']}" class="btn btn-success btn-sm btn_selesai_jadwal wrap_content" data-toggle="tooltip" title="Tandai Selesai">` +
										`<span><i class="fa fa-check"></i> Selesai</span>` +
									`</button>` +
								`</div>`;

						} else if (row['status_pelaksanaan'] == 'D') {
							btn = "";
						}

						return btn;
					}
				}
			]
		});

        $("#table_jadwal_operasi tbody").on('click', '.btn_delete_jadwal', function(){
            let uid = $(this).data("uid");


            Swal.fire({
                title: "Informasi Operasi",
                text: "Hapus jadwal operasi item?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/KamarOperasi/kamar_operasi_jadwal/" + uid,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"DELETE",
                        success:function(resp) {
                            tableJadwal.ajax.reload();
                        }
                    });
                }
            });
        });


		$("#table_jadwal_operasi tbody").on('click', '.btn_proses_jadwal', function(){
            let uid = $(this).data("uid");

			let form_data = {
				'request' : 'proses_jadwal_operasi',
				'uid' : uid
			};

            Swal.fire({
                title: "Informasi Operasi",
                text: "Proses operasi akan berlangsung?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/KamarOperasi",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: form_data,
                        success:function(resp) {
                            console.log(resp);
                            tableJadwal.ajax.reload();
                        }
                    });
                }
            });
        });


        var doneUID = "";
        var doneFormData = {
            'request' : 'selesai_jadwal_operasi',
            'uid' : doneUID,
            'item': []
        };


		$("#table_jadwal_operasi tbody").on('click', '.btn_selesai_jadwal', function() {

            doneUID = $(this).data("uid");
            doneFormData = {
                'request' : 'selesai_jadwal_operasi',
                'uid' : doneUID,
                'item': []
            };

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/KamarOperasi/get_jadwal_pasien_detail/" + doneUID,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    $("#penggunaan_obat tbody tr").remove();
                    let MetaData = response.response_package.response_data[0];
                    $("#pasien").html(MetaData.pasien.nama);
                    $("#no_rm_pasien").html(MetaData.pasien.no_rm);
                    $("#nik_pasien").html(MetaData.pasien.nik);
                    $("#jenis_operasi").html(MetaData.jenis_operasi_detail.nama);
                    $("#tgl_operasi").html(MetaData.tgl_operasi_parsed);
                    $("#jam_mulai").html(MetaData.jam_mulai);
                    $("#jam_selesai").html(MetaData.jam_selesai);
                    $("#ruang_operasi").html(MetaData.ruang_operasi_detail.nama);
                    $("#dokter").html(MetaData.dokter_detail.nama);
                    $("#operasi").html(MetaData.operasi);

                    $("#form-post-operation").modal("show");

                    var detailObat = MetaData.paket;
                    for(var abz in detailObat) {
                        autoObat({
                            obat: {
                                uid: detailObat[abz].obat.uid,
                                nama: detailObat[abz].obat.nama
                            },
                            jlh: detailObat[abz].qty_rencana,
                            satuan: detailObat[abz].obat.satuan_terkecil_info.nama,
                            remark: detailObat[abz].remark
                        });
                    }

                    autoObat();
                }
            });

        });


        function autoObat(setter = {
            obat: {
                uid: "",
                nama: ""
            },
            jlh: 0,
            satuan: "",
            remark: ""
        }) {
            $("#penggunaan_obat tbody tr").removeClass("last-row");
            var newRow = document.createElement("TR");
            var newCellID = document.createElement("TD");
            var newCellObat = document.createElement("TD");
            var newCellQty = document.createElement("TD");
            var newCellSatuan = document.createElement("TD");
            var newCellAksi = document.createElement("TD");

            var newObat = document.createElement("SELECT");
            var newRemark = document.createElement("TEXTAREA");
            var newQty = document.createElement("INPUT");
            var newDelete = document.createElement("BUTTON");
            var newBatchList = document.createElement("OL");

            var kebutuhan = parseFloat(setter.jlh);
            var usedBatch = calculateBatch(setter.obat.uid, kebutuhan);
            for(var ang in usedBatch) {
                if(usedBatch[ang].kode !== "") {
                    $(newBatchList).append("<li>" + usedBatch[ang].kode + " <i class=\"fa fa-arrow-right\"></i> <b class=\"text-purple\">(" + usedBatch[ang].qty + ")</b></li>");
                }
            }


            $(newCellObat).append(newObat).append("<br /><br />Keterangan").append(newRemark);
            $(newCellQty).append(newQty).append("<br /><strong>Saran Batch:</strong><br />").append(newBatchList);
            $(newCellAksi).append(newDelete);

            $(newObat).select2({
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
                    url:__HOSTAPI__ + "/Inventori/get_item_select2/me",
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
                                    penjamin: item.penjamin,
                                    satuan_terkecil: item.satuan_terkecil,
                                    stok: item.stok,
                                    batch: item.batch
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];

                $("#satuan_" + id + " h5").html(data.satuan_terkecil.nama);

                $("#bList_" + id + " li").remove();
                usedBatch = calculateBatch(data.id, kebutuhan);
                /*
                for(var anh in data.batch) {
                    if(data.batch[anh].gudang.uid === __GUDANG_DEPO_OK__ && data.batch[anh].kode !== "") {
                        if(kebutuhan > 0) {
                            if(kebutuhan >= data.batch[anh].stok_terkini) {
                                usedBatch.push({
                                    kode: data.batch[anh].kode,
                                    qty: data.batch[anh].stok_terkini
                                });
                                kebutuhan -= data.batch[anh].stok_terkini;
                            } else {
                                usedBatch.push({
                                    kode: data.batch[anh].kode,
                                    qty: kebutuhan
                                });
                                kebutuhan = 0;
                            }
                        }
                    }
                }*/

                for(var ang in usedBatch) {
                    if(usedBatch[ang].kode !== "") {
                        $("#bList_" + id).append("<li>" + usedBatch[ang].kode + " <i class=\"fa fa-arrow-right\"></i> <b class=\"text-purple\">(" + usedBatch[ang].qty + ")</b></li>");
                    }
                }


                checkAutoObat(id);
            });

            if(setter.obat.uid !== "") {
                $(newObat).append("<option title=\"" + setter.obat.nama + "\" value=\"" + setter.obat.uid + "\">" + setter.obat.nama + "</option>");
                $(newObat).select2("data", {id: setter.obat.uid, text: setter.obat.nama});
                $(newObat).trigger("change");
            }

            $(newRemark).addClass("form-control").val((setter.remark !== "") ? setter.remark : "");

            $(newQty).attr({
                "autocomplete": "off"
            }).addClass("form-control qty_obat").inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).val(parseFloat(setter.jlh));

            $(newCellSatuan).html("<h5 class=\"text-center\">" + ((setter.satuan !== "") ? setter.satuan : "-") + "</h5>");

            $(newDelete).html("<span><i class=\"fa fa-trash\"></i></span>").addClass("btn btn-danger btnHapusObat");

            $(newRow).append(newCellID);
            $(newRow).append(newCellObat);
            $(newRow).append(newCellQty);
            $(newRow).append(newCellSatuan);
            $(newRow).append(newCellAksi);

            $(newRow).addClass("last-row");
            $("#penggunaan_obat tbody").append(newRow);

            rebaseResep();
        }

        function calculateBatch(item, kebutuhan) {
            var usedBatch = [];
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Inventori/item_batch/" + item,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.response_data;
                    if(data === undefined || data === null) {
                        data = []
                    }

                    for(var anh in data) {
                        if(data[anh].gudang.uid === __GUDANG_DEPO_OK__ && data[anh].kode !== "") {
                            if(kebutuhan > 0) {
                                if(kebutuhan >= data[anh].stok_terkini) {
                                    usedBatch.push({
                                        kode: data[anh].kode,
                                        qty: data[anh].stok_terkini
                                    });
                                    kebutuhan -= data[anh].stok_terkini;
                                } else {
                                    usedBatch.push({
                                        kode: data[anh].kode,
                                        qty: kebutuhan
                                    });
                                    kebutuhan = 0;
                                }
                            }
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return usedBatch;
        }

        function rebaseResep() {
            $("#penggunaan_obat tbody tr").each(function (e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "row_" + id
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");

                $(this).find("td:eq(1) select").attr({
                    "id": "obat_" + id
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "qty_" + id
                });

                $(this).find("td:eq(2) ol").attr({
                    "id": "bList_" + id
                });

                $(this).find("td:eq(3)").attr({
                    "id": "satuan_" + id
                });

                $(this).find("td:eq(4) button").attr({
                    "id": "delete_" + id
                });
            });
        }

        function checkAutoObat(id) {
            if(
                $("#row_" + id).hasClass("last-row") &&
                $("#obat_" + id).val() !== undefined && $("#obat_" + id).val() !== "" && $("#obat_" + id).val() !== null &&
                parseFloat($("#qty_" + id).inputmask("unmaskedvalue")) > 0
            ) {
                autoObat();
            }
        }



        $("body").on("keyup", ".qty_obat", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            $("#bList_" + id + " li").remove();
            var kebutuhan = parseFloat($(this).inputmask("unmaskedvalue"));
            var usedBatch = calculateBatch($("#obat_" + id + " option:selected").val(), kebutuhan);
            for(var ang in usedBatch) {
                if(usedBatch[ang].kode !== "") {
                    $("#bList_" + id).append("<li>" + usedBatch[ang].kode + " <i class=\"fa fa-arrow-right\"></i> <b class=\"text-purple\">(" + usedBatch[ang].qty + ")</b></li>");
                }
            }

            checkAutoObat(id);
        });

        $("body").on("click", ".btnHapusObat", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(!$("#row_" + id).hasClass("last-row")) {
                $("#row_" + id).remove();
            }
            rebaseResep();
        });


        $("#btn-selesai-operasi").click(function () {

            var item = [];
            $("#penggunaan_obat tbody tr").each(function (e) {
                if(!$(this).hasClass("last-row")) {
                    item.push({
                        obat: $(this).find("td:eq(1) select").val(),
                        qty: $(this).find("td:eq(2) input").inputmask("unmaskedvalue"),
                        remark: $(this).find("td:eq(1) textarea").val()
                    });
                }
            });

            doneFormData.item = item;

            Swal.fire({
                title: "Informasi Operasi",
                text: "Pastikan data sudah benar. Selesaikan Operasi?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/KamarOperasi",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: doneFormData,
                        success:function(resp) {
                            $("#form-post-operation").modal("hide");
                            tableJadwal.ajax.reload();
                        }
                    });
                }
            });
        });

    
    });

</script>

<div id="form-post-operation" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Data Paska Operasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-8">
                                <table class="table form-mode largeDataType">
                                    <tr>
                                        <td style="width: 20%">No. RM</td><td class="wrap_content">:</td><td style="width: 30%" id="no_rm_pasien"></td>
                                        <td style="width: 20%">Dokter</td><td class="wrap_content">:</td><td id="dokter" style="width: 30%"></td>
                                    </tr>
                                    <tr>
                                        <td>NIK Pasien</td><td class="wrap_content">:</td><td id="nik_pasien"></td>
                                        <td>Ruang Operasi</td><td class="wrap_content">:</td><td id="ruang_operasi"></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Pasien</td><td class="wrap_content">:</td><td id="pasien"></td>
                                        <td>Operasi</td><td class="wrap_content">:</td><td id="operasi"></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Operasi</td><td class="wrap_content">:</td><td id="tgl_operasi"></td>
                                        <td>Jenis Operasi</td><td class="wrap_content">:</td><td id="jenis_operasi"></td>
                                    </tr>
                                    <tr>
                                        <td>Waktu Operasi</td><td class="wrap_content">:</td><td><b id="jam_mulai"></b> - <b id="jam_selesai"></b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <div class="alert alert-soft-info d-flex align-items-center card-margin" role="alert">
                                    <i class="material-icons mr-3">error_outline</i>
                                    <div class="text-body"><strong>Informasi:</strong> Semua data penggunaan obat/BHP akan <code><b>langsung memproses stok</b></code> pada Depo OK. Harap cek kembali dengan teliti entry penggunaan obat/BHP dibawah ini.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="penggunaan_obat" class="table table-striped table-bordered largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat/BHP</th>
                                        <th style="width: 20%">Jumlah</th>
                                        <th class="wrap_content">Satuan</th>
                                        <th class="wrap_content">Aksi</th>
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
                <button type="button" class="btn btn-success" id="btn-selesai-operasi"><i class="fa fa-check-circle"></i> Selesai</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Kembali</button>
            </div>
        </div>
    </div>
</div>
