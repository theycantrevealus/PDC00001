<script type="text/javascript">
    $(function () {
        var MODE = "NEW";
        var usedNS = {};
        var currentNS = "";
        var dataSetPetugas = [];
        var currentPetugas = [];
        var selectedPetugas = [];
        var currentPetugasUID = "";
        var selectedJabatan = "";

        var dataSetRanjang = [];
        var currentRanjang = [];
        var selectedRanjang = [];
        var currentRanjangUID = "";
        var allow_manage = true;

        var dataPetugas = $('#listPetugas').DataTable({
            data: dataSetPetugas,
            columns: [
                { title: "No" },
                { title: "Nama" },
                { title: "Aksi" }
            ],
            drawCallback: function( settings ) {
                $('#listPetugas thead tr th:last-child').addClass("wrap_content");
                $('#listPetugas tbody tr td:last-child').addClass("wrap_content");

                $('#listPetugas thead tr th:eq(0)').addClass("wrap_content");
                $('#listPetugas tbody tr td:eq(0)').addClass("wrap_content");
            }
        });



        $("#cariPetugas").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "Petugas tidak ditemukan";
                }
            },
            placeholder:"Cari Petugas",
            cache: true,
            dropdownParent: $("#form-nurse-station"),
            selectOnClose: true,
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pegawai/get_all_perawat_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                processResults: function (response) {
                    var data = response.response_package.response_data;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_perawat,
                                id: item.uid,
                                jabatan: item.nama_jabatan
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            currentPetugas = [
                (dataSetPetugas.length + 1),
                "<b class=\"text-info\">" + data.jabatan + "</b><br />" + data.text,
                "<button uid=\"" + currentPetugasUID + "\" class=\"btn btn-sm btn-danger btnDeletePetugas\" id=\"target_petugas_" + (dataSetPetugas.length + 1) + "\">" +
                "<span>" +
                "<i class=\"fa fa-times\"></i> Hapus" +
                "</span>" +
                "</button>"
            ];
            currentPetugasUID = data.id;
        }).on('results:message', function(params){
            this.dropdown._resizeDropdown();
            this.dropdown._positionDropdown();
        });

        $("#btnTambahPetugas").click(function () {
            if(selectedPetugas.indexOf(currentPetugasUID) < 0) {
                selectedPetugas.push(currentPetugasUID);
                dataSetPetugas.push(currentPetugas);
                dataPetugas.clear().rows.add(dataSetPetugas).draw();
            } else {
                Swal.fire(
                    "Nurse Station",
                    "Petugas sudah ada",
                    "warning"
                ).then((result) => {
                    //
                });
            }
        });

        loadRuangan("#filterRuangan");
        $("#filterRuangan").select2({
            dropdownParent: $("#form-nurse-station")
        }).on("select2:select", function(e) {
            loadRanjang("#filterRanjang", $("#filterRuangan option:selected").val());
        });

        loadRanjang("#filterRanjang", $("#filterRuangan option:selected").val());

        $("#filterRanjang").select2();

        var dataRanjang = $("#table-tempat-tidur").DataTable({
            data: dataSetRanjang,
            columns: [
                { title: "No" },
                { title: "Ruangan" },
                { title: "Ranjang" },
                { title: "Aksi" }
            ],
            drawCallback: function( settings ) {
                $('#table-tempat-tidur thead tr th:last-child').addClass("wrap_content");
                $('#table-tempat-tidur tbody tr td:last-child').addClass("wrap_content");

                $('#table-tempat-tidur thead tr th:eq(0)').addClass("wrap_content");
                $('#table-tempat-tidur tbody tr td:eq(0)').addClass("wrap_content");
            }
        });

        $("#btnTambahAsuhan").click(function () {
            currentRanjangUID = $("#filterRanjang option:selected").val();
            currentRanjang = [
                (dataSetRanjang.length + 1),
                $("#filterRuangan option:selected").text(),
                $("#filterRanjang option:selected").text(),
                "<button uid=\"" + currentRanjangUID + "\" class=\"btn btn-sm btn-danger btnDeleteRanjang\" id=\"target_ranjang_" + (dataSetPetugas.length + 1) + "\">" +
                "<span>" +
                "<i class=\"fa fa-times\"></i> Hapus" +
                "</span>" +
                "</button>"
            ];

            if(usedBed.indexOf(currentRanjangUID) < 0) {
                if(selectedRanjang.indexOf(currentRanjangUID) < 0) {
                    selectedRanjang.push(currentRanjangUID);
                    dataSetRanjang.push(currentRanjang);
                    dataRanjang.clear().rows.add(dataSetRanjang).draw();
                } else {
                    Swal.fire(
                        "Nurse Station",
                        "Ranjang sudah ditambahkan",
                        "warning"
                    ).then((result) => {
                        //
                    });
                }
            } else {
                Swal.fire(
                    "Nurse Station",
                    "Ranjang sudah terdaftar pada nurse station lain",
                    "warning"
                ).then((result) => {
                    //
                });
            }
        });

        function loadRanjang(target, ruangan, selected = "") {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Bed/bed-ruangan/" + ruangan,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    $(target).find("option").remove();
                    var data = response.response_package.response_data;
                    if(data.length > 0) {
                        for(var a in data) {
                            var newSelection = document.createElement("option");
                            $(newSelection).attr({
                                "value": data[a].uid
                            }).html(data[a].nama);
                            $(target).append(newSelection);
                        }
                    }
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        }

        function loadRuangan(target, selected = "") {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Ruangan/ruangan",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    $(target).find("option").remove();
                    var data = response.response_package.response_data;
                    for(var a in data) {
                        var newSelection = document.createElement("option");
                        $(newSelection).attr({
                            "value": data[a].uid
                        }).html(data[a].kode_ruangan + " - " + data[a].nama);
                        $(target).append(newSelection);
                    }
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        }

        var ns = $("#table_nurse_station").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/IGD",
                type: "POST",
                data: function(d) {
                    d.request = "get_nurse_station";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response.response_package == undefined || response.response_package.response_data == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    usedNS = response.response_package.usedNS;
                    usedBed = response.response_package.usedBed;

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = returnedData.length;
                    console.log(returnedData);

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"text-info\">[" + ((row.kode !== undefined || row.kode !== null) ? row.kode : "") + "]</h5>" + ((row.nama !== undefined || row.nama !== null) ? row.nama : "-");
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var parseRanjang = "<div class=\"row\">";
                        var dataRanjang = row.ranjang;
                        for(var a in dataRanjang) {

                            var status_ranjang =  "";
                            if(dataRanjang[a].status === null || dataRanjang[a].status === undefined) {
                                status_ranjang = "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Tidak ada Pelayanan</span>";
                            } else {
                                status_ranjang = "<b>" + dataRanjang[a].status.nama_pasien + "</b><br />" + dataRanjang[a].status.nama_dokter;
                            }
                            parseRanjang += "<div class=\"col-lg-3\">" +
                                "<i class=\"fa fa-bed\"></i> " + ((dataRanjang[a] !== undefined && dataRanjang[a] !== null && dataRanjang[a].detail !== undefined && dataRanjang[a].detail !== null && dataRanjang[a].detail.nama !== undefined && dataRanjang[a].detail.nama !== null) ? dataRanjang[a].detail.nama : "???") +
                                "</div>" +
                                "<div class=\"col-lg-9\">" + status_ranjang +
                                "</div>";
                            if(a < dataRanjang.length - 1) {
                                parseRanjang += "<div class=\"col-lg-12\"><hr /></div>";
                            }
                        }
                        parseRanjang += "</div>";
                        return parseRanjang;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var parsePetugas = "<ul>";
                        var dataPetugas = row.petugas;
                        for(var a in dataPetugas) {
                            parsePetugas += "<li>" +
                                dataPetugas[a].nama_petugas
                                "</li>";
                        }
                        dataPetugas += "</ul>";
                        return parsePetugas;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var checkRanjang = row.ranjang;
                        var allowManageRanjang = false;
                        if(checkRanjang.length > 0) {
                            for(var a in checkRanjang) {
                                if(!checkRanjang[a].allow_manage) {
                                    allowManageRanjang = false;
                                    break;
                                } else {
                                    allowManageRanjang = true;
                                }
                            }
                        } else {
                            allowManageRanjang = true;
                        }

                        if(allowManageRanjang) {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button id=\"edit_" + row.uid + "\" class=\"btn btn-info btn-sm btnEditNS\">" +
                                "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
                                "</button>" +
                                "<button id=\"delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btnDeleteNS\">" +
                                "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
                                "</button>" +
                                "</div>";
                        } else {
                            return "<span class=\"text-warning wrap_content\"><i class=\"fa fa-exclamation-triangle\"></i> Nurse Station sedang aktif</span>";
                        }
                    }
                }
            ]
        });

        $("body").on("click", ".btnDeleteNS", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            Swal.fire({
                title: "Hapus Nurse Station?",
                text: "Nurse Station akan tetap ada namun di non-aktifkan",
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/IGD/nurse_station/" + uid,
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "DELETE",
                        success: function (response) {
                            var result = response.response_package.response_result;
                            if(result > 0) {
                                ns.ajax.reload();
                            }
                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btnEditNS", function () {
            MODE = "EDIT";
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            currentNS = uid;
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Inap/detail_ns/" + uid,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    var result = response.response_package.response_data[0];
                    console.log(result);
                    allow_manage = response.response_package.allow_manage;
                    if(!allow_manage) {
                        Swal.fire(
                            "Nurse Station",
                            "Kosongkan nurse station sebelum melakukan perubahan data",
                            "warning"
                        ).then((result) => {
                            //
                        });

                    } else {
                        $("#txt_kode").val(result.kode);
                        $("#txt_nama").val(result.nama);
                        $("#txt_unit").append("<option value=\"" + result.uid_unit + "\">" + result.kode_unit + " - " + result.nama_unit + "</option>");
                        $('#txt_unit').val(result.uid_unit).trigger("change");
                        var petugas = result.petugas;
                        dataSetPetugas = [];
                        selectedPetugas = [];
                        for(var a in petugas) {
                            if(selectedPetugas.indexOf(petugas[a].petugas) < 0) {
                                selectedPetugas.push(petugas[a].petugas);
                            }

                            var composePetugas = [
                                (dataSetPetugas.length + 1),
                                "<b class=\"text-info\">" + petugas[a].nama_jabatan + "</b><br />" + petugas[a].nama_petugas,
                                "<button uid=\"" + petugas[a].petugas + "\" class=\"btn btn-sm btn-danger btnDeletePetugas\" id=\"target_petugas_" + (dataSetPetugas.length + 1) + "\">" +
                                "<span>" +
                                "<i class=\"fa fa-times\"></i> Hapus" +
                                "</span>" +
                                "</button>"
                            ];
                            dataSetPetugas.push(composePetugas);
                        }
                        dataPetugas.clear().rows.add(dataSetPetugas).draw();

                        var ranjang = result.ranjang;
                        dataSetRanjang = [];
                        selectedRanjang = [];
                        for(var a in ranjang) {
                            if(selectedRanjang.indexOf(ranjang[a].detail.uid) < 0) {
                                selectedRanjang.push(ranjang[a].detail.uid);
                            }

                            var composeRanjang = [
                                (dataSetRanjang.length + 1),
                                (ranjang[a].detail.ruangan_detail !== null && ranjang[a].detail.ruangan_detail !== undefined) ? ranjang[a].detail.ruangan_detail.nama : "",
                                ranjang[a].detail.nama,
                                "<button uid=\"" + ranjang[a].detail.uid + "\" class=\"btn btn-sm btn-danger btnDeleteRanjang\" id=\"target_ranjang_" + (dataSetRanjang.length + 1) + "\">" +
                                "<span>" +
                                "<i class=\"fa fa-times\"></i> Hapus" +
                                "</span>" +
                                "</button>"
                            ];

                            dataSetRanjang.push(composeRanjang);
                        }
                        dataRanjang.clear().rows.add(dataSetRanjang).draw();

                        $("#form-nurse-station").modal("show");
                    }
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        });


        $("body").on("click", ".btnDeleteRanjang", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var uid = $(this).attr("uid");
            selectedRanjang.splice(selectedRanjang.indexOf(uid), 1);
            usedBed.splice(selectedRanjang.indexOf(uid), 1);

            for(var a in dataSetRanjang) {
                if(parseInt(dataSetRanjang[a][0]) === parseInt(id)) {
                    dataSetRanjang.splice(a, 1);
                }
            }
            dataSetRanjang = rebaseDataSet(dataSetRanjang, "btnDeleteRanjang", "target_ranjang_", uid);
            dataRanjang.clear().rows.add(dataSetRanjang).draw();
        });

        $("body").on("click", ".btnDeletePetugas", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var uid = $(this).attr("uid");
            selectedPetugas.splice(selectedPetugas.indexOf(uid), 1);

            for(var a in dataSetPetugas) {
                if(parseInt(dataSetPetugas[a][0]) === parseInt(id)) {
                    dataSetPetugas.splice(a, 1);
                }
            }
            dataSetPetugas = rebaseDataSet(dataSetPetugas, "btnDeletePetugas", "target_petugas_", uid);
            dataPetugas.clear().rows.add(dataSetPetugas).draw();
        });

        function rebaseDataSet(data, classifier, composeItem, uid) {
            for(var a in data) {
                data[a][0] = (parseInt(a) + 1);
                data[a][data[a].length - 1] = "<button uid=\"" + uid + "\" class=\"btn btn-sm btn-danger " + classifier + "\" id=\"" + composeItem + (parseInt(a) + 1) + "\">" +
                    "<span>" +
                    "<i class=\"fa fa-times\"></i> Hapus" +
                    "</span>" +
                    "</button>";
            }
            return data;
        }


        $("#btnTambahNS").click(function () {
            MODE = "NEW";
            $("#form-nurse-station").modal("show");
        });

        $("#txt_unit").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "Unit tidak ditemukan";
                }
            },
            placeholder:"Cari Unit",
            cache: true,
            dropdownParent: $("#form-nurse-station"),
            selectOnClose: true,
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Unit/get_unit_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                processResults: function (response) {
                    var data = response.response_package.response_data;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.kode + " - " +item.nama,
                                id: item.uid,
                                kode: item.kode
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;

        }).on('results:message', function(params){
            this.dropdown._resizeDropdown();
            this.dropdown._positionDropdown();
        });

        $("#btnProsesNS").click(function () {
            var kode = $("#txt_kode").val();
            var nama = $("#txt_nama").val();
            var unit = $("#txt_unit option:selected").val();
            var getPetugas = selectedPetugas;
            var getRanjang = selectedRanjang;

            Swal.fire({
                title: "Tambah Nurse Station?",
                text: "Pastikan data sudah benar. Data akan digunakan untuk pelayanan rawat inap",
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    if(MODE === "NEW") {
                        $.ajax({
                            async: false,
                            url: __HOSTAPI__ + "/IGD",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            data: {
                                request: "tambah_nurse_station",
                                nama: nama,
                                kode: kode,
                                unit: unit,
                                petugas: getPetugas,
                                ranjang: getRanjang
                            },
                            type: "POST",
                            success: function(response) {
                                var result = response.response_package.response_result;
                                if(result > 0) {
                                    $("#form-nurse-station").modal("hide");
                                    ns.ajax.reload();
                                }
                            },
                            error: function(response) {
                                console.clear();
                                console.log(response);
                            }
                        });
                    } else {
                        console.clear();
                        console.log({
                            request: "edit_nurse_station",
                            uid: currentNS,
                            nama: nama,
                            kode: kode,
                            unit: unit,
                            petugas: getPetugas,
                            ranjang: getRanjang
                        });
                        $.ajax({
                            async: false,
                            url: __HOSTAPI__ + "/IGD",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            data: {
                                request: "edit_nurse_station",
                                uid: currentNS,
                                nama: nama,
                                kode: kode,
                                unit: unit,
                                petugas: getPetugas,
                                ranjang: getRanjang
                            },
                            type: "POST",
                            success: function(response) {
                                console.log(response);
                                var result = response.response_package.response_result;
                                if(result > 0) {
                                    $("#form-nurse-station").modal("hide");
                                    ns.ajax.reload();
                                } else {
                                    Swal.fire(
                                        "Nurse Station",
                                        "Gagal Update Nurse Station",
                                        "warning"
                                    ).then((result) => {
                                        ns.ajax.reload();
                                        console.log(response);
                                    });
                                }
                            },
                            error: function(response) {
                                console.clear();
                                console.log(response);
                            }
                        });
                    }
                }
            });
        });
    });
</script>

<div id="form-nurse-station" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Manajemen Nurse Station
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="form-loader">
                <div class="card-group">
                    <div class="card card-body">
                        <div class="d-flex flex-row">
                            <div class="col-md-12">
                                <b>Informasi Dasar</b>
                                <hr />
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="txt_kode">Kode</label>
                                        <div class="input-group">
                                            <input type="text" id="txt_kode" class="form-control form-control-appended" placeholder="Kode Nurse Station" />
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <label for="txt_nama">Nama</label>
                                        <div class="input-group">
                                            <input type="text" id="txt_nama" class="form-control form-control-appended" placeholder="Nama Nurse Station" />
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="txt_unit">Unit <b class="text-info"><i class="fa fa-info-circle"></i> Target manajemen stok</b></label>
                                        <div class="input-group">
                                            <select type="text" id="txt_unit" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body">
                        <div class="d-flex flex-row">
                            <div class="col-md-12">
                                <b>Petugas</b>
                                <hr />
                                <div class="row form-group">
                                    <div class="col-lg-8">
                                        <select class="form-control" id="cariPetugas">
                                            <option>Pilih</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <button class="btn btn-success" id="btnTambahPetugas">
                                            <span>
                                                <i class="fa fa-plus-circle"></i> Tambah
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                                <table class="table table-bordered largeDataType" id="listPetugas" style="width: 100% !important;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Nama</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body">
                        <div class="d-flex flex-row">
                            <div class="col-md-12">
                                <b>Asuhan</b>
                                <hr />
                                <div class="row">
                                    <div class="col-lg-6">
                                        <select class="form-control" id="filterRuangan"></select>
                                    </div>
                                    <div class="col-lg-1" style="padding-top: 8px;">
                                        <i class="fa fa-chevron-right"></i>
                                    </div>
                                    <div class="col-lg-5">
                                        <select class="form-control" id="filterRanjang"></select>
                                    </div>
                                    <div class="col-lg-12">
                                        <br />
                                        <button class="btn btn-success pull-right" id="btnTambahAsuhan">
                                            <span>
                                                <i class="fa fa-plus-circle"></i> Tambah
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                                <table class="table table-bordered largeDataType" id="table-tempat-tidur" style="width: 100% !important;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Ruangan</th>
                                            <th>Ranjang</th>
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
                <button type="button" class="btn btn-success" id="btnProsesNS"><i class="fa fa-check"></i> Proses</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Kembali</button>
            </div>
        </div>
    </div>
</div>