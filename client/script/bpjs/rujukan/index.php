<script type="text/javascript">
    $(function () {
        var currentRujukan = '';
        var RujukanList = $("#table-rujukan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Rujukan",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_all";
                },
                dataSrc:function(response) {
                    console.clear();
                    console.log(response);
                    var data = response.response_package.response_data;
                    if(data === undefined) {
                        data = [];
                    }
                    return data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            lengthMenu: [[-1], ["All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var selectedID = {};
                        var selectedMetaData;
                        for(var a = 0; a < row.pasien.history_penjamin.length; a++) {
                            if(row.pasien.history_penjamin[a].penjamin === row.penjamin.uid) {
                                selectedID = row.pasien.history_penjamin[a];
                                selectedMetaData = JSON.parse(row.pasien.history_penjamin[a].rest_meta);
                            }
                        }

                        return "<span pasien=\"" + row.pasien.uid + "\" id=\"pasien_" + row.uid + "\" nik=\"" + row.pasien.nik + "\" no_kartu=\"" + selectedMetaData.response.peserta.noKartu + "\">" + row.pasien.no_rm + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row.uid + "\" kontak=\"" + row.pasien.no_telp + "\">" + row.pasien.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<button id=\"rujukan_" + row.uid + "\" class=\"btn btn-info btn-sm btnRujuk\" target=\"" + row.penjamin.uid + "\">Proses</button>";
                    }
                }
            ]
        });

        $("#txt_bpjs_jenis_tujuan_rujukan").select2();

        $("#txt_bpjs_jenis_layanan").select2();

        $("#txt_bpjs_tujuan_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_poli",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.poli;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_tipe_rujukan").select2();

        $("#txt_bpjs_diagnosa").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_diagnosa",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.diagnosa;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_tujuan_rujukan").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_faskes_select2/" + $("#txt_bpjs_jenis_tujuan_rujukan").val(),
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    console.log(response);
                    var data = response.response_package.content;
                    if(data.metaData.message === "Sukses") {
                        return {
                            results: $.map(data.response.faskes, function (item) {
                                return {
                                    text: item.kode + " - " + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    } else {
                        Swal.fire(
                            "Faskes tidak ditemukan",
                            data.metaData.message,
                            "warning"
                        ).then((result) => {
                            //
                        });
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("body").on("click", ".btnRujuk", function () {
            var target = $(this).attr("target");
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentRujukan = id;
            var pasien = $("#pasien_" + id).attr("pasien");
            if(target === __UIDPENJAMINBPJS__) {


                get_sep_list(pasien);

                $("#txt_bpjs_nomor").val($("#pasien_" + id).attr("no_kartu"));
                $("#txt_bpjs_nik").val($("#pasien_" + id).attr("nik"));
                $("#txt_bpjs_nama").val($("#nama_" + id).html());
                $("#txt_bpjs_telepon").val($("#nama_" + id).attr("kontak"));
                $("#txt_bpjs_rm").val($("#pasien_" + id).html());

                $("#modal-rujuk-bpjs").modal("show");
            }
        });



        function get_sep_list(pasien) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_sep_list/" + pasien,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.response_data;
                    for(var sepK in data) {
                        $("#target_sep tbody").append("<tr>" +
                            "<td>" +
                            "<input type=\"radio\" class=\"target_sep\" kode=\"" + data[sepK].uid + "\" value=\"" + data[sepK].sep_no + "\" name=\"sep_" + data[sepK].pasien + "\">" +
                            "</td>" +
                            "<td>" + data[sepK].sep_no + "</td>" +
                            "</tr>");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }


        /*$("#txt_bpjs_nomor_sep").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_sep_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content;
                    if(data.metaData.message === "Sukses") {
                        return {
                            results: $.map(data, function (item) {
                                console.log(item);
                                return {
                                    text: item.noSep,
                                    id: item.noSep
                                }
                            })
                        };
                    } else {
                        console.clear();
                        console.log(response);
                        Swal.fire(
                            "SEP tidak ditemukan",
                            data.metaData.message,
                            "warning"
                        ).then((result) => {
                            //
                        });
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });*/

        $("#btnProsesRujuk").click(function () {
            Swal.fire({
                title: "Hapus SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya. Hapus",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    var sep_target = $("input.target_sep[type=\"radio\"]:checked");
                    var sep_no = sep_target.val();
                    var sep_uid = sep_target.attr("kode");

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/BPJS",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        data: {
                            request: "rujukan_baru",
                            rujukan: currentRujukan,
                            sep: sep_no,
                            sep_uid: sep_uid,
                            tujuan: $("#txt_bpjs_jenis_tujuan_rujukan").val(),
                            jenis_pelayanan: $("#txt_bpjs_jenis_layanan").val(),
                            catatan: $("#txt_bpjs_catatan").val(),
                            diagnosa: $("#txt_bpjs_diagnosa").val(),
                            tipe: $("#txt_bpjs_tipe_rujukan").val(),
                            poli: $("#txt_bpjs_tujuan_poli").val(),
                        },
                        type: "POST",
                        success: function (response) {
                            console.clear();
                            console.log(response);
                            if(parseInt(response.response_package.bpjs.content.metaData.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'Rujukan Berhasil',
                                    'success'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                    $("#modal-rujuk-bpjs").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.response_package.bpjs.content.metaData.message,
                                    'error'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                });
                            }
                        },
                        error: function (response) {
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });
    });
</script>








<div id="modal-rujuk-bpjs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rujuk Baru
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-5 mb-5 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">Kontak</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_telepon" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 col-md-8 mb-4 form-group">
                                    <label for="">Nomor Medical Record (MR)</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                </div>

                                <div class="col-12 col-md-7 form-group">
                                    <label for="">Pilih SEP</label>
                                    <table class="table table-bordered largeDataType" id="target_sep">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content"></th>
                                                <th>SEP</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-4 mb-4 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_jenis_tujuan_rujukan">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_rujukan"></select>
                                </div>
                                <div class="col-12 col-md-8 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_poli"></select>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tipe_rujukan">
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_diagnosa"></select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Catatan</label>
                                    <textarea class="form-control" id="txt_bpjs_catatan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <div id="spanBtnTambahPasien" hidden> -->
                <button class="btn btn-success" id="btnProsesRujuk">
                     <i class="fa fa-plus"></i> Tambah Rujukan Baru
                </button>
                <!-- </div> -->

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>