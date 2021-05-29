<script type="text/javascript">
    $(function () {
        var MODE = "NEW";

        var dataSetPetugas = [];
        var currentPetugas = [];
        var selectedPetugas = [];
        var currentPetugasUID = "";
        var selectedJabatan = "";

        var dataSetRanjang = [];
        var currentRanjang = [];
        var selectedRanjang = [];
        var currentRanjangUID = "";

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
                "<button class=\"btn btn-sm btn-danger\">" +
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
        $("#filterRuangan").select2().on("select2:select", function(e) {
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
                "<button class=\"btn btn-sm btn-danger\">" +
                "<span>" +
                "<i class=\"fa fa-times\"></i> Hapus" +
                "</span>" +
                "</button>"
            ];
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
                url: __HOSTAPI__ + "/Inap",
                type: "POST",
                data: function(d) {
                    d.request = "get_nurse_station";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(returnedData == undefined || returnedData.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = returnedData.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = returnedData.length;

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
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                }
            ]
        });



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
                title: 'Tambah Nurse Station?',
                text: 'Pastikan data sudah benar. Data akan digunakan untuk pelayanan rawat inap',
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Inap",
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