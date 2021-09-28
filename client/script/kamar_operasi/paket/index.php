<script type="text/javascript">
    $(function () {
        var selectedUID = "";
        var MODE = "tambah";
        var tablePaketObat = $("#table_paket_obat").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 50, -1], [5, 50, "All"]],
            serverMethod: "POST",
            "ajax": {
                url: __HOSTAPI__ + "/KamarOperasi",
                type: "POST",
                data: function (d) {
                    d.request = "paket_obat_list";
                },
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc: function (response) {

                    console.clear();
                    console.log(response);
                    var resepDataRaw = response.response_package.response_data;

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return resepDataRaw;
                }
            },
            language: {
                search: "",
                searchPlaceholder: "No.RM/Nama Pasien"
            },
            autoWidth: false,
            "bInfo": false,
            aaSorting: [[2, "asc"]],
            "columnDefs": [
                {"targets": 0, "className": "dt-body-left"}
            ],
            "rowCallback": function (row, data, index) {
                /*if (data.departemen.uid === __POLI_IGD__) {
                    $("td", row).addClass("bg-danger-custom text-danger");
                }*/
            },
            "columns": [
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.detail.length;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.remark;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btnEditPaket\" id=\"paket_" + row.uid + "\">" +
                            "<span>" +
                            "<i class=\"fa fa-edit\"></i> Edit" +
                            "</span>" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusPaket\" id=\"hapus_" + row.uid + "\">" +
                            "<span>" +
                            "<i class=\"fa fa-trash\"></i> Delete" +
                            "</span>" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#btnTambahPaket").click(function () {
            MODE = "tambah";
            $("#form-tambah").modal("show");
            $("#autoObat tbody tr").remove();
            autoObat();
        });

        $("body").on("click", ".btnHapusPaket", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            Swal.fire({
                title: "Paket Obat",
                text: "Hapus paket?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/KamarOperasi/kamar_operasi_paket_obat/" + id,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"DELETE",
                        success:function(response) {
                            tablePaketObat.ajax.reload();
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btnEditPaket", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            MODE = "edit";
            selectedUID = id;
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/KamarOperasi/get_paket_detail/" + id,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){

                    $("#form-tambah").modal("show");
                    $("#autoObat tbody tr").remove();
                    var data = response.response_package.response_data[0];
                    $("#txt_nama").val(data.nama);
                    $("#txt_keterangan").val(data.remark);
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
                    autoObat();

                },
                error: function(response) {
                    console.log(response);
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


            $(newCellObat).append(newObat).append("<br /><br />Keterangan").append(newRemark);
            $(newCellQty).append(newQty);
            $(newCellAksi).append(newDelete);

            $(newObat).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                placeholder:"Cari Barang",
                dropdownParent: $("#form-tambah"),
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
                                    penjamin: item.penjamin,
                                    satuan_terkecil: item.satuan_terkecil
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

                checkAutoObat(id);
            });

            if(setter.obat.uid !== "") {
                $(newObat).append("<option title=\"" + setter.obat.nama + "\" value=\"" + setter.obat.uid + "\">" + setter.obat.nama + "</option>");
                $(newObat).select2("data", {id: setter.obat.uid, text: setter.obat.nama});
                $(newObat).trigger("change");
            }

            $(newRemark).addClass("form-control").val((setter.remark !== "") ? setter.remark : "");

            $(newQty).addClass("form-control qty_obat").inputmask({
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
            $("#autoObat").append(newRow);

            rebaseResep();
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

            console.clear();
            console.log(id);

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

        $("#btnSubmitObat").click(function () {
            var nama = $("#txt_nama").val();
            var remark = $("#txt_keterangan").val();
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

            if(nama !== "" && item.length > 0) {
                Swal.fire({
                    title: "Paket Obat",
                    text: "Apakah data paket obat sudah benar?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Cek Kembali",
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            async: false,
                            url:__HOSTAPI__ + "/KamarOperasi",
                            type: "POST",
                            data: {
                                request: MODE + "_paket",
                                uid: selectedUID,
                                nama: nama,
                                remark: remark,
                                item: item
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response){

                                if(response.response_package.response_result > 0) {
                                    Swal.fire(
                                        "Paket Obat",
                                        "Paket Obat Berhasil Diproses",
                                        "success"
                                    ).then((result) => {
                                        tablePaketObat.ajax.reload();
                                        $("#form-tambah").modal("hide");
                                    });
                                }

                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                if(nama === "") {
                    Swal.fire(
                        "Paket Obat",
                        "Nama paket tidak boleh kosong",
                        "error"
                    ).then((result) => {
                        //
                    });
                }

                if(item.length === 0) {
                    Swal.fire(
                        "Verifikasi Laboratorium",
                        "Obat/BHP harus lebih dari satu",
                        "error"
                    ).then((result) => {
                        //
                    });
                }
            }
        });
    });
</script>


<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Paket Obat <span class="title-term"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-6">
                    <label for="txt_nama">Nama Paket :</label>
                    <input type="text" class="form-control" id="txt_nama" />
                </div>
                <div class="form-group col-md-12">
                    <label for="txt_keterangan">Keterangan :</label>
                    <textarea class="form-control" name="txt_keterangan" id="txt_keterangan" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <table class="table table-bordered largeDataType" id="autoObat">
                        <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th style="width: 50%">Nama Obat / BHP</th>
                                <th style="width: 10%">Jlh</th>
                                <th style="width: 10%">Satuan</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitObat">Submit</button>
            </div>
        </div>
    </div>
</div>