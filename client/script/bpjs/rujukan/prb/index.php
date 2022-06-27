<script type="text/javascript">
    $(function() {
        var selectedKartu = "";

        $("#txt_bpjs_rujuk_pasien").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "Pasien tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-prb'),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pasien/select2",
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
                                text: item.text,
                                id: item.uid,
                                email: item.email,
                                no_telp: item.no_telp,
                                jenkel_detail: item.jenkel_detail,
                                history_penjamin: item.history_penjamin
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            
            var restMeta = data.history_penjamin;
            for(var b in restMeta) {
                if(restMeta[b].penjamin === __UIDPENJAMINBPJS__) {
                    var bpjsData = JSON.parse(restMeta[b].rest_meta);
                    selectedKartu = bpjsData.data.peserta.noKartu;
                }
            }

            $("#txt_bpjs_rujuk_email").val((data.email !== undefined && data.email !== null) ? data.email : "-");
            $("#txt_bpjs_rujuk_kontak").val((data.no_telp !== undefined && data.no_telp !== null) ? data.no_telp : "-");
            $("#txt_bpjs_rujuk_jenkel").val((data.jenkel_detail.nama !== undefined && data.jenkel_detail.nama !== null) ? data.jenkel_detail.nama : "-");
            $("#txt_bpjs_rujuk_alamat").val((data.alamat !== undefined && data.alamat !== null) ? data.alamat : "-");

            load_sep(data.id);
        });

        $("#txt_bpjs_rujuk_sep").select2({
            dropdownParent: $('#modal-prb')
        });

        $("#txt_bpjs_rujuk_program").select2({
            dropdownParent: $('#modal-prb')
        });

        $("#txt_bpjs_rujuk_jenis_layan_dpjp").select2({
            dropdownParent: $('#modal-prb')
        }).on("select2:select", function(e) {
            loadDPJPSpesialis("#txt_bpjs_rujuk_dpjp");
        });

        

        $("#txt_bpjs_rujuk_spesialistik_dpjp").select2({
            dropdownParent: $('#modal-prb')
        });

        $("#txt_bpjs_rujuk_dpjp").select2({
            dropdownParent: $('#modal-prb')
        });

        function loadDPJPSpesialis(target) {
            $.ajax({
                url:__HOSTAPI__ + "/BPJS/get_dpjp/?jenis=" + $("#txt_bpjs_rujuk_jenis_layan_dpjp option:selected").val() + "&spesialistik=" + $("#txt_bpjs_rujuk_spesialistik_dpjp option:selected").val() + "&tanggal=" + $("#txt_bpjs_rujuk_sep option:selected").attr("tanggal-sep"),
                type: "GET",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    if(parseInt(response.response_package.metaData.code) === 200) {
                        var data = response.response_package.data.list;
                        $(target + " option").remove();
                        for(var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", data[a].kode).html(data[a].nama);
                            $(target).append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        loadSpesialistik("#txt_bpjs_rujuk_spesialistik_dpjp");

        function loadSpesialistik(target) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_spesialistik",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.data.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        

        loadProgramPRB("#txt_bpjs_rujuk_program");

        function loadProgramPRB(target, selected = "") {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_prb_program",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.data.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        if(parseInt(data[a].kode) === parseInt(selected)) {
                            $(selection).attr("selected", "selected");
                        }
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }


        function load_sep(pasien) {
            $.ajax({
                url:__HOSTAPI__ + "/BPJS/get_sep_pasien/" + pasien,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var data = response.response_package.response_data;
                    $("#txt_bpjs_rujuk_sep option").remove();
                    for(var a in data) {
                        $("#txt_bpjs_rujuk_sep").append("<option tanggal-sep=\"" + data[a].tanggal_sep + "\" value=\"" + data[a].sep_no + "\">" + data[a].sep_no + "</option>");
                    }

                    loadDPJPSpesialis("#txt_bpjs_rujuk_dpjp");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#btnTambahPRB").click(function() {
            $("#modal-prb").modal("show");
            $("#txt_bpjs_rujuk_nomor_kartu").focus();
        });

        function autoRowResep() {
            

            var newRow = document.createElement("TR");
            var newCellNo = document.createElement("TD");
            var newCellObat = document.createElement("TD");
            var newCellSigna1 = document.createElement("TD");
            var newCellSigna2 = document.createElement("TD");
            var newCellJumlah = document.createElement("TD");

            $(newCellNo).addClass("autonum");

            $(newRow).append(newCellNo);
            $(newRow).append(newCellObat);
            $(newRow).append(newCellSigna1);
            $(newRow).append(newCellSigna2);
            $(newRow).append(newCellJumlah);
            
            var newObat = document.createElement("SELECT");
            var newSigna1 = document.createElement("INPUT");
            var newSigna2 = document.createElement("INPUT");
            var newJumlah = document.createElement("INPUT");

            $("#autoObatPRB tbody").append(newRow);

            $(newCellObat).append(newObat);
            $(newCellSigna1).append(newSigna1);
            $(newCellSigna2).append(newSigna2);
            $(newCellJumlah).append(newJumlah);

            $(newSigna1).addClass("form-control checkerResep");
            $(newSigna2).addClass("form-control checkerResep");
            $(newJumlah).addClass("form-control checkerResep").inputmask({
                alias: "decimal",
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });



            $(newObat).addClass("form-control checkerResep").select2({
                minimumInputLength: 2,
                language: {
                    noResults: function() {
                        return "Obat tidak ditemukan";
                    }
                },
                dropdownParent: $('#modal-prb'),
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/BPJS/get_prb_generic",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        if(response.response_package.data !== undefined) {
                            var data = response.response_package.data.list;
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.kode
                                    }
                                })
                            };
                        } else {
                            return [];
                        }
                    }
                }
            }).on("select2:select", function(e) {
                var data = e.params.data;
            });

            rebaseResep();
        }

        function checkAllowAdd(id) {
            var obat = $("#resep_obat_" + id + " option:selected").val();
            var signa1 = $("#resep_signa1_" + id).val();
            var signa2 = $("#resep_signa2_" + id).val();
            var jumlah = $("#resep_jumlah_" + id).inputmask("unmaskedvalue");

            if(
                obat !== undefined && obat !== null && obat !== "" &&
                signa1 !== "" &&
                signa2 !== "" &&
                jumlah > 0
            ) {
                autoRowResep();
            }
            // else {
            //     console.log({
            //         obat: obat,
            //         signa1: signa1,
            //         signa2: signa2,
            //         jumlah: jumlah
            //     });
            // }
        }

        function rebaseResep() {
            $("#autoObatPRB tbody tr").each(function(e) {
                var id = (e + 1);
                $(this).find("td:eq(0)").html(id);
                $(this).find("td:eq(1) select").attr({
                    "id": "resep_obat_" + id
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "resep_signa1_" + id
                });

                $(this).find("td:eq(3) input").attr({
                    "id": "resep_signa2_" + id
                });

                $(this).find("td:eq(4) input").attr({
                    "id": "resep_jumlah_" + id
                });
            });
        }

        $("body").on("change", ".checkerResep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkAllowAdd(id);
        });

        

        autoRowResep();

        $("#btnProsesPRB").click(function() {
            var pasien = $("#txt_bpjs_rujuk_pasien option:selected").val();
            var sep = $("#txt_bpjs_rujuk_sep option:selected").val();
            var kartu = selectedKartu;
            var alamat = $("#txt_bpjs_rujuk_alamat").val();
            var email = $("#txt_bpjs_rujuk_email").val();
            var programPRB = $("#txt_bpjs_rujuk_program option:selected").val();
            var kodeDPJP = $("#txt_bpjs_rujuk_dpjp option:selected").val();
            var keterangan = $("#txt_bpjs_rujuk_alamat").val();
            var saran = $("#txt_bpjs_rujuk_saran").val();
            var jenkel = $("#txt_bpjs_rujuk_jenkel").val();
            var kontak = $("#txt_bpjs_rujuk_kontak").val();
            var obatList = [];
            var obatInt = [];

            Swal.fire({
                title: "BPJS PRB",
                text: "Buat PRB baru?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#autoObatPRB tbody tr").each(function(e) {
                        var obat = $(this).find("td:eq(1) select option:selected").val();
                        var namaObat = $(this).find("td:eq(1) select option:selected").text();
                        var signa1 = $(this).find("td:eq(2) input").val();
                        var signa2 = $(this).find("td:eq(3) input").val();
                        var jumlah = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");

                        if(
                            obat !== undefined && obat !== null && obat !== "" &&
                            signa1 !== "" &&
                            signa2 !== "" &&
                            jumlah > 0
                        ) {
                            obatList.push({
                                kdObat: obat,
                                signa1: signa1,
                                signa2: signa2,
                                jmlObat: jumlah
                            });

                            obatInt.push({
                                nmObat: namaObat,
                                kdObat: obat,
                                signa1: signa1,
                                signa2: signa2,
                                jmlObat: jumlah
                            });
                        }
                    });

                    $.ajax({
                        url:__HOSTAPI__ + "/BPJS",
                        type: "POST",
                        data: {
                            request: "prb_baru",
                            sep: sep,
                            kartu: kartu,
                            alamat: alamat,
                            email: email,
                            prb: programPRB,
                            dpjp: kodeDPJP,
                            jenkel: jenkel,
                            telp: kontak,
                            keterangan: keterangan,
                            saran: saran,
                            obat: obatList,
                            obatInt: obatInt
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if(parseInt(response.response_package.bpjs.metaData.code) === 200) {
                                Swal.fire(
                                    "Pembuatan PRB Berhasil!",
                                    "PRB telah dibuat",
                                    "success"
                                ).then((result) => {
                                    $("#modal-prb").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    "Gagal buat PRB",
                                    response.response_package.bpjs.metaData.message,
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });
    });
</script>




<div id="modal-prb" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> PRB Baru
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi PRB</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-8 form-group">
                                    <label for="">Pasien</label>
                                    <select id="txt_bpjs_rujuk_pasien" class="form-control uppercase"></select>
                                </div>
                                <div class="col-4 form-group">
                                    <label for="">SEP</label>
                                    <select id="txt_bpjs_rujuk_sep" class="form-control uppercase"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Email</label>
                                    <input type="email" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rujuk_email" readonly />
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Kontak</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rujuk_kontak" readonly />
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rujuk_jenkel" readonly />
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Alamat</label>
                                    <textarea class="form-control" id="txt_bpjs_rujuk_alamat" readonly></textarea>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Program PRB</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rujuk_program"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rujuk_jenis_layan_dpjp">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Spesialistik</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rujuk_spesialistik_dpjp"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">DPJP</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rujuk_dpjp"></select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Keterangan</label>
                                    <textarea class="form-control" id="txt_bpjs_rujuk_keterangan"></textarea>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Saran</label>
                                    <textarea class="form-control" id="txt_bpjs_rujuk_saran"></textarea>
                                </div>
                                <div class="col-12">
                                    <table class="table largeDataType" id="autoObatPRB">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content">No</th>
                                                <th>Obat</th>
                                                <th style="width: 10%;">Signa 1</th>
                                                <th style="width: 10%;">Signa 2</th>
                                                <th style="width: 10%;">Jlh</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesPRB">
                    <i class="fa fa-check"></i> Rujuk
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>