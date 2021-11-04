<script type="text/javascript">

    $(function(){
        let jadwalUID = __PAGES__[3];

        loadJenis();
        loadDokter();
        loadRuangan();
        var detailObat = loadJadwalPasien(jadwalUID);
        console.log(detailObat);
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

        /*$.ajax({
            async: false,
            url:__HOSTAPI__ + "/KamarOperasi/get_paket_detail/" + $("#paket_obat option:selected").val(),
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {

                var data = response.response_package.response_data[0];
                for(var a in data.detail) {
                    autoObat({
                        obat: {
                            uid: data.detail[a].obat.uid,
                            nama: data.detail[a].obat.nama
                        },
                        jlh: data.detail[a].qty,
                        satuan: data.detail[a].obat.satuan_terkecil_info.nama,
                        remark: data.detail[a].remark
                    });
                }

            },
            error: function(response) {
                console.log(response);
            }
        });*/

        //submit data
        $("#form_add_jadwal").submit(function() {
            Swal.fire({
                title: "Informasi Operasi",
                text: "Update informasi pra-operasi. Pastikan data sudah benar!",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#btnSubmit").attr("disabled", "disabled");


                    var item = [];
                    $("#autoObat tbody tr").each(function (e) {
                        if(!$(this).hasClass("last-row")) {
                            item.push({
                                obat: $(this).find("td:eq(1) select").val(),
                                qty: $(this).find("td:eq(2) input").inputmask("unmaskedvalue"),
                                remark: $(this).find("td:eq(1) textarea").val()
                            });
                        }
                    });

                    let jenis_operasi = $("#jenis_operasi").val();
                    let tgl_operasi = $("#tgl_operasi").val();
                    let jam_mulai = $("#jam_mulai").val();
                    let jam_selesai = $("#jam_selesai").val();
                    let ruang_operasi = $("#ruang_operasi").val();
                    let dokter = $("#dokter").val();
                    let penjamin = $("#penjamin option:selected").val();
                    let operasi = $("#operasi").val();

                    let form_data = {
                        'request': 'edit_jadwal_operasi',
                        'jenis_operasi' : jenis_operasi,
                        'tgl_operasi' : tgl_operasi,
                        'jam_mulai' : jam_mulai,
                        'jam_selesai' : jam_selesai,
                        'ruang_operasi' : ruang_operasi,
                        'penjamin': penjamin,
                        'dokter' : dokter,
                        'paket_obat': $("#paket_obat").val(),
                        'operasi' : operasi,
                        'item': item,
                        'uid' : jadwalUID
                    }

                    console.log(form_data);

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/KamarOperasi",
                        data: form_data,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        success: function(response) {
                            $("#btnSubmit").removeAttr("disabled");
                            if (response.response_package != null || response.response_package != undefined) {
                                if (response.response_package.response_result > 0){
                                    Swal.fire(
                                        "Informasi Operasi",
                                        "Berhasil update jadwal operasi",
                                        "success"
                                    ).then((result) => {
                                        location.href = __HOSTNAME__ + '/kamar_operasi/jadwal';
                                    });
                                } else {
                                    Swal.fire(
                                        "Informasi Operasi",
                                        "Gagal mengupdate jadwal",
                                        "error"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });


            return false;
        });




















        $("body").on("keyup", ".qty_obat", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            $("#bList_" + id + " li").remove();
            var kebutuhan = parseFloat($(this).inputmask("unmaskedvalue"));
            var harga = $("#obat_" + id).attr("harga");
            //$("#hg_" + id).html(number_format((kebutuhan * harga), 2, ".", ","));
            var usedBatch = calculateBatch($("#obat_" + id + " option:selected").val(), kebutuhan);
            for(var ang in usedBatch) {
                if(usedBatch[ang].kode !== "") {
                    $("#bList_" + id).append("<li>" + usedBatch[ang].kode + " <i class=\"fa fa-arrow-right\"></i> <b class=\"text-purple\">(" + usedBatch[ang].qty + ")</b></li>");
                }
            }

            var totalHarga = 0;

            $("#autoObat tbody tr").each(function() {
                var harga = parseFloat($(this).find("td:eq(2) hg").attr("harga"));
                var qty = parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));
                if(harga > 0) {
                    totalHarga += (harga * qty);
                }
            });

            $("#totalBiayaObatOK").html(number_format(totalHarga, 2, '.', ','));

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



        $("#paket_obat").change(function () {
            var target = $(this).val();
            $("#autoObat tbody tr").remove();
            if(target === "none") {
                autoObat();
            } else {
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/KamarOperasi/get_paket_detail/" + target,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response) {

                        var data = response.response_package.response_data[0];
                        var totalHarga = 0;
                        for(var a in data.detail) {
                            if(data.detail[a].harga !== undefined && data.detail[a].qty !== undefined) {
                                totalHarga += (parseFloat(data.detail[a].harga) * parseFloat(data.detail[a].qty));
                            }

                            //console.log(totalHarga);
                            autoObat({
                                obat: {
                                    uid: data.detail[a].obat.uid,
                                    nama: data.detail[a].obat.nama
                                },
                                harga: (data.detail[a].harga !== undefined) ? parseFloat(data.detail[a].harga) : 0,
                                jlh: data.detail[a].qty,
                                satuan: data.detail[a].obat.satuan_terkecil_info.nama,
                                remark: data.detail[a].remark
                            });
                        }

                        $("#totalBiayaObatOK").html(number_format(totalHarga, 2, '.', ','));
                        autoObat();

                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        });


        autoObat();

        function autoObat(setter = {
            obat: {
                uid: "",
                nama: ""
            },
            jlh: 0,
            harga: 0,
            satuan: "",
            remark: ""
        }) {
            $("#autoObat tbody tr").removeClass("last-row");
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

            $(newBatchList).append("<hr />Rp. <hg harga=\"" + setter.harga + "\">" + number_format(setter.harga, 2, ".", ",") + "</hg>");


            $(newCellObat).append(newObat).append("<br /><br />Keterangan").append(newRemark);
            $(newCellQty).append(newQty).append("<br /><strong>Saran Batch:</strong><br />").append(newBatchList);
            $(newCellAksi).append(newDelete);

            $(newObat).attr({
                harga: setter.harga
            }).select2({
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
                                    harga: item.harga,
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
                    console.log(usedBatch[ang]);
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
            $("#autoObat tbody").append(newRow);

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
                success: function(response) {
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
            $("#autoObat tbody tr").each(function (e) {
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

                $(this).find("td:eq(2) hg").attr({
                    "id": "hg_" + id
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
























    });

    function loadJenis()
    {

		$.ajax({
            url: __HOSTAPI__ + "/KamarOperasi/jenis_operasi",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                
                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data;

                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#jenis_operasi").append(selection);
	                }
                
                }

                $("#jenis_operasi").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });

    }

    function loadDokter()
    {
        $.ajax({
            url: __HOSTAPI__ + "/Pegawai/get_all_dokter",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                
                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data;

                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama_dokter);
	                    $("#dokter").append(selection);
	                }
                
                }

                $("#dokter").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadRuangan()
    {
        $.ajax({
            url: __HOSTAPI__ + "/Ruangan/ruangan",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){

                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data;

                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#ruang_operasi").append(selection);
	                }
                
                }

                $("#ruang_operasi").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadJadwalPasien(uid) {
        var detailObat = [];
        $.ajax({
            async: false,
            url: __HOSTAPI__ + `/KamarOperasi/get_jadwal_pasien_detail/${uid}`,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {

                if (response.response_package != null || response.response_package != undefined) {
                    
                    let MetaData = response.response_package.response_data[0];

                    console.log(MetaData);
                    
                    $("#pasien").val(MetaData.pasien.nama);
                    $("#no_rm_pasien").val(MetaData.pasien.no_rm);
                    $("#nik_pasien").val(MetaData.pasien.nik);
                    $("#jenis_operasi").val(MetaData.jenis_operasi).trigger('change');
                    $("#tgl_operasi").val(MetaData.tgl_operasi);
                    $("#jam_mulai").val(MetaData.jam_mulai);
                    $("#jam_selesai").val(MetaData.jam_selesai);
                    $("#ruang_operasi").val(MetaData.ruang_operasi).trigger('change');
                    $("#dokter").val(MetaData.dokter).trigger('change');
                    $("#operasi").val(MetaData.operasi);
                    detailObat = MetaData.paket;

                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Penjamin/penjamin",
                        type: "GET",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            var MetaDataPenjamin = response.response_package.response_data;

                            if (MetaDataPenjamin !== undefined && MetaDataPenjamin !== null){
                                for(var i in MetaDataPenjamin) {
                                    var selection = document.createElement("OPTION");

                                    $(selection).attr("value", MetaDataPenjamin[i].uid).html(MetaDataPenjamin[i].nama);
                                    if(MetaDataPenjamin[i].uid === MetaData.penjamin) {
                                        $(selection).attr({
                                            "selected": "selected"
                                        });
                                    }

                                    $("#penjamin").append(selection);
                                }

                                $("#penjamin").select2();
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });


                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/KamarOperasi/get_paket_list_name",
                        type: "GET",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){

                            var data = response.response_package.response_data;
                            for(var ab in data) {
                                $("#paket_obat").append("<option " + ((data[ab].uid === MetaData.paket_obat) ? "selected" : "") + " value=\"" + data[ab].uid + "\">" + data[ab].nama + "</option>");
                            }

                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });



                
                }

            },
            error: function(response) {
                console.log(response);
            }
        });

        return detailObat;
    }

</script>