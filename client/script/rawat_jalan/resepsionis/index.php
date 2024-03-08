<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/pdfjs/pdf2.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {


        var params;
        var MODE = false;
        var currentAntrianType = "DEFAULT";
        var currentAntrianUID = "";
        var currentAntrianPasien = "";
        $(".sep").select2();
        var selectedSKDP = "";
        var selectedPasien = "";

        // $("#txt_bpjs_tanggal_rujukan").datepicker({
        //     dateFormat: "DD, dd MM yy",
        //     autoclose: true,
        //     beforeShow: function(i) {
        //         if ($(i).attr('readonly')) {
        //             return false;
        //         }
        //     }
        // });

        // $("#txt_bpjs_tanggal_rujukan_manualigd").datepicker({
        //     dateFormat: "DD, dd MM yy",
        //     autoclose: true,
        //     beforeShow: function(i) {
        //         if ($(i).attr('readonly')) {
        //             return false;
        //         }
        //     }
        // }).datepicker("setDate", new Date());

        $("#txt_bpjs_tgl_sep").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            beforeShow: function(i) {
                if ($(i).attr('readonly')) {
                    return false;
                }
            }
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_tanggal_rujukan_manualigd").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_laka_tanggal").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#panel_rujukan_manualigd").hide();
        $("input[type=\"radio\"][name=\"radio_tipe_rujukan\"]").change(function() {
            if (parseInt($(this).val()) === 1) {
                $("#panel_rujukan_online").hide();
                $("#panel_rujukan_manualigd").fadeIn();
            } else {
                $("#panel_rujukan_manualigd").hide();
                $("#panel_rujukan_online").fadeIn();
            }
        });

        $(".kelas_rawat_naik_container").hide();
        $("input[type=\"radio\"][name=\"radio_kelas_rawat_naik\"]").change(function() {
            if (parseInt($(this).val()) === 1) {
                $(".kelas_rawat_naik_container").fadeIn();
            } else {
                $(".kelas_rawat_naik_container").fadeOut();
            }
        });

        $(".laka_lantas_suplesi_container").hide();
        $(".laka_lantas_container").hide();


        $("#txt_bpjs_laka").change(function() {
            if (parseInt($("#txt_bpjs_laka option:selected").val()) !== 0) {
                $(".laka_lantas_container").fadeIn();
            } else {
                $(".laka_lantas_container").fadeOut();
            }
        });

        $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]").change(function() {
            if (parseInt($(this).val()) === 1) {
                $(".laka_lantas_suplesi_container").fadeIn();

                loadProvinsi("#txt_bpjs_laka_suplesi_provinsi");
                loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi option:selected").val());
                loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten option:selected").val());
            } else {
                $(".laka_lantas_suplesi_container").fadeOut();

                $("#txt_bpjs_laka_suplesi_provinsi option").remove();
                $("#txt_bpjs_laka_suplesi_kabupaten option").remove();
                $("#txt_bpjs_laka_suplesi_kecamatan option").remove();
            }
        });

        var selectedLakaPenjamin = [];
        var selectedListRujukan = [];

        $("input[type=\"checkbox\"][name=\"txt_bpjs_laka_penjamin\"]").change(function() {
            var selectedvalue = $(this).val();
            if ($(this).is(":checked")) {
                if (selectedLakaPenjamin.indexOf(selectedvalue) < 0) {
                    selectedLakaPenjamin.push(selectedvalue);
                }
            } else {
                selectedLakaPenjamin.splice(selectedLakaPenjamin.indexOf(selectedvalue), 1);
            }
        });

        function loadPoli(targetted = "") {
            var dataPoli = null;

            if (targetted === __POLI_IGD__) {
                //Show Cara data dan keterangan cara datang
                $(".poli_igd").show();
                $(".poli_lain").hide();
            } else {
                $(".poli_igd").hide();
                $(".poli_lain").show();
            }

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Poli/poli-available",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData != "") {
                        for (i = 0; i < MetaData.length; i++) {
                            var selection = document.createElement("OPTION");
                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if (MetaData[i].uid !== __POLI_INAP__) {
                                if (targetted !== "") {
                                    if (MetaData[i].uid === targetted) {
                                        $(selection).attr("selected", "selected");
                                    }
                                    $("#filter_poli").append(selection);
                                } else {
                                    if (MetaData[i].editable) {
                                        $("#filter_poli").append(selection);
                                    }
                                }

                            }
                        }

                        if (targetted !== "") {
                            $("#filter_poli").attr("disabled", "disabled");
                        } else {
                            $("#filter_poli").removeAttr("disabled");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPoli;
        }

        loadPoli();

        $("#filter_poli").select2().on('change', function() {
            tableAntrian.ajax.reload();
        });


        var tableAntrian = $("#table-antrian-rawat-jalan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [
                [20, 50, -1],
                [20, 50, "All"]
            ],
            serverMethod: "POST",
            "ajax": {
                url: __HOSTAPI__ + "/Antrian/antrian",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_list_antrian_backend";
                    d.poli = $("#filter_poli").val();
                },
                dataSrc: function(response) {
                    var data = response.response_package.response_data;
                    var filtered = [];

                    for (var key in data) {
                        if (data[key].departemen !== "IGD") {
                            filtered.push(data[key])
                        } else {
                            filtered.push(data[key])
                        }
                    }

                    return filtered;

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsFiltered;
                    response.recordsFiltered = response.response_package.recordsTotal;
                }
            },
            autoWidth: false,
            "bInfo": false,
            aaSorting: [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [{
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\" id=\"waktu_masuk_" + row.uid + "\">" + row["waktu_masuk"] + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content text-info\" id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span><br /><span class=\"wrap_content\" id=\"nama_" + row.uid_pasien + "\">" + row["pasien"] + "<span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<strong id=\"poli_" + row.uid_pasien + "\">" + row["departemen"] + "</strong><br /><span id=\"dokter_" + row.uid + "\">" + row.dokter + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        if (row["uid_penjamin"] === __UIDPENJAMINBPJS__) {
                            if (Date(row.created_at) < Date()) {
                                return "Antrian sudah lewat";
                            } else {
                                if (row['sep'] !== "none") {
                                    if (row.sep.response_data !== undefined) {
                                        return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    } else {
                                        return row["penjamin"] + " <h6 class=\"nomor_sep text-success\"><i class=\"fa fa-check\"></i> " + row.sep + "</h6>";
                                        //<button class=\"btn btn-success btn-cetak-sep\" id=\"cetak_sep_" + row.sep_uid + "\"><i class=\"fa fa-print\"></i> Cetak SEP</button>
                                    }

                                    //return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                } else {
                                    if (row.waktu_keluar !== undefined && row.waktu_keluar !== null) {
                                        /*return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>" +
                                            "<button class=\"btn btn-warning btn-sm pull-right btn-ajukan-sep\"><i class=\"fa fa-exclamation-circle\"></i> Ajukan SEP</button>";*/
                                        return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    } else {
                                        /*return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>" +
                                            "<button class=\"btn btn-warning btn-sm pull-right btn-ajukan-sep\"><i class=\"fa fa-exclamation-circle\"></i> Ajukan SEP</button>";*/
                                        return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    }
                                }
                            }
                        } else {
                            return row["penjamin"];
                        }
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row["user_resepsionis"];
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        if (row["uid_penjamin"] === __UIDPENJAMINBPJS__) {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-success btn-sm btn-pasien-pulang\">" +
                                "<i class=\"fa fa-check\"></i> Selesai" +
                                "</button>" +
                                // "<button id=\"batalkan_kunjungan_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-pasien-batal\">" +
                                // "<span><i class=\"fa fa-times-circle\"></i> Hapus</span>" +
                                // "</button>" +
                                "<div class=\"btn-group\">" +
                                "<button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">" +
                                "<i class=\"fa fa-print\"></i> Cetak" +
                                "</button>" +
                                "<div class=\"dropdown-menu\">" +
                                "<a id=\"cetak_sep_" + row.sep_uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"SEP\" href=\"#\">BPJS - SEP</a>" +
                                "<a id=\"cetak_kartu_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"kartu\" href=\"#\">Kartu Pasien</a>" +
                                "<a id=\"cetak_lab_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"lab\" href=\"#\">Label Laboratorium</a>" +
                                "<a id=\"cetak_tracer_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"tracer\" href=\"#\">Tracer</a>" +
                                "<a id=\"cetak_spbk_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"spbk\" href=\"#\">SPBK</a>" +
                                "<a id=\"cetak_gelang_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"sosial\" href=\"#\">Data Sosial Pasien</a>" +
                                "<a id=\"cetak_gelang_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"gelang\" href=\"#\">Gelang Pasien</a>" +
                                "<a id=\"cetak_bayi_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"bayi\" href=\"#\">Gelang Pasien Bayi</a>" +
                                "<a id=\"cetak_identitas_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"identitas\" href=\"#\">Identitas Pasien</a>" +
                                "</div>" +
                                "</div>" +
                                "</div>";
                        } else {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-success btn-sm btn-pasien-pulang\">" +
                                "<i class=\"fa fa-check\"></i> Selesai" +
                                "</button>" +
                                // "<button id=\"batalkan_kunjungan_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-pasien-batal\">" +
                                // "<span><i class=\"fa fa-times-circle\"></i> Hapus</span>" +
                                // "</button>" +
                                "<div class=\"btn-group\">" +
                                "<button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">" +
                                "<i class=\"fa fa-print\"></i> Cetak" +
                                "</button>" +
                                "<div class=\"dropdown-menu\">" +
                                "<a id=\"cetak_kartu_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"kartu\" href=\"#\">Kartu Pasien</a>" +
                                "<a id=\"cetak_lab_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"lab\" href=\"#\">Label Laboratorium</a>" +
                                "<a id=\"cetak_tracer_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"tracer\" href=\"#\">Tracer</a>" +
                                "<a id=\"cetak_spbk_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"spbk\" href=\"#\">SPBK</a>" +
                                "<a id=\"cetak_gelang_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"sosial\" href=\"#\">Data Sosial Pasien</a>" +
                                "<a id=\"cetak_gelang_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"gelang\" href=\"#\">Gelang Pasien</a>" +
                                "<a id=\"cetak_bayi_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"bayi\" href=\"#\">Gelang Pasien Bayi</a>" +
                                "<a id=\"cetak_identitas_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" class=\"dropdown-item print_manager\" jenis=\"identitas\" href=\"#\">Identitas Pasien</a>" +
                                "</div>" +
                                "</div>" +
                                /*"<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"gelang\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"kartu\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"lab\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"tracer\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"spbk\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"sosial\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"bayi\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +
                                "<button id=\"cetak_" + row.uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"identitas\" class=\"btn btn-info print_manager\"><i class=\"fa fa-print\"></i></button>" +*/

                                "</div>";

                        }
                    }
                }
            ]
        });

        $("body").on("click", ".btn-pasien-batal", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];


            Swal.fire({
                title: "Pasien batal berobat?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Antrian",
                        type: "POST",
                        data: {
                            request: "pulangkan_pasien",
                            uid: id
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            if (response.response_package.response_result > 0) {
                                tableAntrian.ajax.reload();
                                tableAntrianRI.ajax.reload();
                                tableAntrianIGD.ajax.reload();
                            } else {
                                Swal.fire(
                                    "Pulangkan pasien",
                                    "Pasien gagal dipulangkan",
                                    "error"
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




        var tableAntrianIGD = $("#table-antrian-IGD").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [
                [20, 50, -1],
                [20, 50, "All"]
            ],
            serverMethod: "POST",
            ajax: {
                url: __HOSTAPI__ + "/Antrian",
                type: "POST",
                data: function(d) {
                    d.request = "igd";
                },
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc: function(response) {
                    var returnedData = [];
                    if (response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return returnedData;
                },
                error: function(xhr, error, thrown) {
                    console.log(xhr);
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Pasien"
            },
            "columns": [{
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row["waktu_masuk"] + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content text-info\" id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span><br /><span class=\"wrap_content\" id=\"nama_" + row.uid_pasien + "\">" + row["nama_pasien"] + "<span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.dokter;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        if (row["uid_penjamin"] === __UIDPENJAMINBPJS__) {
                            if (Date(row.created_at) < Date()) {
                                return "Antrian sudah lewat";
                            } else {
                                if (row['sep'] !== "none") {
                                    if (row.sep.response_data !== undefined) {
                                        return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    } else {
                                        return row["penjamin"] + " <h6 class=\"nomor_sep text-success\"><i class=\"fa fa-check\"></i> " + row.sep + "</h6>" +
                                            "<button class=\"btn btn-success print_manager\" id=\"cetak_sep_" + row.sep_uid + "\" pasien=\"" + row.uid_pasien + "\" jenis=\"SEP\"><i class=\"fa fa-print\"></i> Cetak SEP</button>";
                                    }

                                    //return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                } else {
                                    if (row.waktu_keluar !== undefined && row.waktu_keluar !== null) {
                                        /*return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>" +
                                            "<button class=\"btn btn-warning btn-sm pull-right btn-ajukan-sep\"><i class=\"fa fa-exclamation-circle\"></i> Ajukan SEP</button>";*/
                                        return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    } else {
                                        /*return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>" +
                                            "<button class=\"btn btn-warning btn-sm pull-right btn-ajukan-sep\"><i class=\"fa fa-exclamation-circle\"></i> Ajukan SEP</button>";*/
                                        return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    }
                                }
                            }
                        } else {
                            return row["penjamin"];
                        }
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row["user_resepsionis"];
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        /*return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-info btn-sm btn-pasien-pulang\">" +
                            "<i class=\"fa fa-check\"></i> Pulangkan Pasien" +
                            "</button>" +
                            "</div>";*/
                        return "<div class=\"btn-group\">" +
                            "<button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<div class=\"dropdown-menu\">" +
                            "<a class=\"dropdown-item\" href=\"#\">Kartu Pasien</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">Label Laboratorium</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">Tracer</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">SPBK</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">Data Sosial Pasien</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">Gelang Pasien</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">Gelang Pasien Bayi</a>" +
                            "<a class=\"dropdown-item\" href=\"#\">Identitas Pasien</a>" +
                            "</div>";
                    }
                }
            ]
        });


        var tableAntrianRI = $("#table-antrian-RI").DataTable({
            "ajax": {
                url: __HOSTAPI__ + "/Antrian/rawat_inap",
                type: "GET",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc: function(response) {
                    return response.response_package.response_data;
                }
            },
            autoWidth: false,
            "bInfo": false,
            aaSorting: [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [{
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\" id=\"waktu_masuk_" + row.uid + "\">" + row["waktu_masuk"] + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\" id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span><br /><span class=\"wrap_content\" id=\"nama_" + row.uid_pasien + "\">" + row["pasien"] + "<span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.dokter;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        if (row["uid_penjamin"] == __UIDPENJAMINBPJS__) {
                            if (row['sep'] != "none") {
                                return row["penjamin"] + " <h6 class=\"nomor_sep\">" + row.sep + "</h6>";
                            } else {
                                if (row.waktu_keluar !== undefined && row.waktu_keluar !== null) {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                } else {
                                    return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\">Daftar SEP</button>";
                                    //return row["penjamin"];
                                }
                            }
                        } else {
                            return row["penjamin"];
                        }
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "";
                        // return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                        //     "<button id=\"pasien_pulang_" + row.uid + "\" class=\"btn btn-info btn-sm btn-pasien-pulang\">" +
                        //     "<i class=\"fa fa-check\"></i> Selesai" +
                        //     "</button>" +
                        //     "</div>";
                    }
                }
            ]
        });

        $("#btnTambahIGD").click(function() {
            //$("#modal-tambah-igd").modal("show");
            currentAntrianType = __POLI_IGD__;
            $("#modal-cari").modal("show");
        });

        $("body").on("click", ".btn-pasien-pulang", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            Swal.fire({
                title: "Pulangkan pasien?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Antrian",
                        type: "POST",
                        data: {
                            request: "pulangkan_pasien",
                            uid: id
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            if (response.response_package.response_result > 0) {
                                tableAntrian.ajax.reload();
                                tableAntrianRI.ajax.reload();
                                tableAntrianIGD.ajax.reload();
                            } else {
                                Swal.fire(
                                    "Pulangkan pasien",
                                    "Pasien gagal dipulangkan",
                                    "error"
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
            return false;
        });




        var selectedSEPAntrian = "";
        var selectedSEPAntriamMeta;
        var selectedSEPNoKartu = "";

        var isRujukan = false;

        $("body").on("click", ".daftar_sep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            selectedPasien = id;

            var SEPButton = $(this);
            SEPButton.html("Memuat SEP...").removeClass("btn-info").addClass("btn-warning");

            var antrian = $(this).attr("antrian");
            currentAntrianUID = antrian;


            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Pasien/pasien-detail/" + id,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var data = response.response_package.response_data[0];
                    var penjaminList = data.history_penjamin;
                    var bpjsMeta;


                    for (var penjaminKey in penjaminList) {
                        if (penjaminList[penjaminKey].penjamin === __UIDPENJAMINBPJS__) {
                            bpjsMeta = penjaminList[penjaminKey].rest_meta;
                        }
                    }

                    if (bpjsMeta !== undefined) {
                        currentAntrianPasien = data.uid;
                        $("#txt_bpjs_nama").val(data.nama);
                        $("#txt_bpjs_nik").val(data.nik);
                        $("#txt_bpjs_telepon").val(data.no_telp);

                        for (var pKey in data.history_penjamin) {
                            if (data.history_penjamin[pKey].penjamin === __UIDPENJAMINBPJS__) {
                                if (__BPJS_MODE__ > 0) {
                                    var metaDataBPJS = JSON.parse(data.history_penjamin[pKey].rest_meta);
                                    selectedSEPNoKartu = metaDataBPJS.data.peserta.noKartu;
                                    $("#txt_bpjs_nomor").val(metaDataBPJS.data.peserta.noKartu);
                                    // loadKelasRawat(metaDataBPJS.data.peserta.hakKelas.keterangan);
                                    $("#txt_bpjs_kelas_rawat").val(metaDataBPJS.data.peserta.hakKelas.kode).trigger("change");
                                }
                            }
                        }
                    }
                },
                error: function(response) {
                    //
                }
            });

            if (__BPJS_MODE__ > 0) {
                //sini boskuh


                $("#txt_bpjs_laka_suplesi_provinsi").select2({
                    dropdownParent: $("#group_provinsi")
                });

                $("#txt_bpjs_dpjp_spesialistik").change(function() {
                    loadDPJPSpesialis("#txt_bpjs_dpjp");
                });

                $("#txt_bpjs_jenis_layanan").change(function() {
                    loadDPJPSpesialis("#txt_bpjs_dpjp");
                });

                $("#txt_bpjs_dpjp").select2({
                    dropdownParent: $("#group_dpjp"),
                    "language": {
                        "noResults": function() {
                            return 'DPJP tidak ditemukan'
                        }
                    }
                });

                $("#txt_bpjs_laka_suplesi_kabupaten").select2({
                    dropdownParent: $("#group_kabupaten")
                });

                $("#txt_bpjs_laka_suplesi_kecamatan").select2({
                    dropdownParent: $("#group_kecamatan")
                });

                // $("#txt_bpjs_nomor_rujukan").select2({
                //     // autoclose: true,
                //     dropdownParent: $("#group_nomor_rujukan")
                // });

                $("#txt_bpjs_kelas_rawat").select2({
                    dropdownParent: $("#group_kelas_rawat")
                });

                $("#txt_bpjs_kelas_rawat_naik").select2({
                    dropdownParent: $("#group_kelas_rawat_naik")
                });

                $("#txt_bpjs_kelas_rawat_naik_pembiayaan").select2({
                    dropdownParent: $("#group_kelas_rawat_naik")
                });

                $("#txt_bpjs_asal_rujukan").select2({
                    disabled: "readonly"
                });

                // $("#txt_bpjs_jenis_asal_rujukan").select2({
                //     disabled: "readonly"
                // });


                /*$("#txt_bpjs_jenis_asal_rujukan").change(function() {
                    loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                });

                $("#txt_bpjs_jenis_asal_rujukan").change(function() {
                    loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                });


                $("#txt_bpjs_dpjp_spesialistik").change(function() {
                    loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                });*/

                // $("#txt_bpjs_nomor_rujukan").change(function() {
                //     loadInformasiRujukan(selectedListRujukan[$(this).find("option:selected").index()]);
                // });

                var urlSearchRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/carirujukanpcare";
                $("#txt_bpjs_jenis_asal_rujukan").change(function() {
                    if ($("#txt_bpjs_jenis_asal_rujukan option:selected").val() == 1) {
                        urlSearchRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/carirujukanpcare";
                        $("#txt_bpjs_nomor_rujukan").select2({
                            minimumInputLength: 2,
                            language: {
                                noResults: function() {
                                    return "No. Rujukan tidak ditemukan";
                                }
                            },
                            dropdownParent: $('#group_nomor_rujukan'),
                            ajax: {
                                url: urlSearchRujukan,
                                type: "GET",
                                dataType: "json",
                                crossDomain: true,
                                beforeSend: async function(request) {
                                    refreshToken().then((test) => {
                                        bpjs_token = test;
                                    })

                                    request.setRequestHeader("Accept", "application/json");
                                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                    request.setRequestHeader("x-token", bpjs_token);
                                },
                                data: function(term) {
                                    return {
                                        norujuk: term.term
                                    };
                                },
                                cache: true,
                                processResults: function(response) {
                                    if (parseInt(response.metadata.code) === 202) {
                                        Swal.fire(
                                            "No. Rujukan",
                                            response.metadata.message,
                                            "warning"
                                        );
                                    } else if (parseInt(response.metadata.code) === 200) {
                                        var data = [response.response];
                                        return {
                                            results: $.map(data, function() {
                                                return {
                                                    text: data[0].noKunjungan + " - " + data[0].peserta.nama,
                                                    id: data[0].noKunjungan,
                                                    tglKunjungan: data[0].tglKunjungan,
                                                    diagnosa_nama: data[0].diagnosa.nama,
                                                    diagnosa_kode: data[0].diagnosa.kode,
                                                    poliRujukan_nama: data[0].poliRujukan.nama,
                                                    poliRujukan_kode: data[0].poliRujukan.kode,
                                                    poliRujukan_nama: data[0].poliRujukan.nama,
                                                    provPerujuk_kode: data[0].provPerujuk.kode,
                                                    provPerujuk_nama: data[0].provPerujuk.nama,
                                                    keluhan: data[0].keluhan,
                                                    peserta_hakKelas_kode: data[0].peserta.hakKelas.kode,
                                                    peserta_hakKelas_keterangan: data[0].peserta.hakKelas.keterangan,
                                                    peserta_jenisPeserta_kode: data[0].peserta.jenisPeserta.kode,
                                                    peserta_jenisPeserta_keterangan: data[0].peserta.jenisPeserta.keterangan,
                                                    pelayanan_kode: data[0].pelayanan.kode,
                                                    pelayanan_nama: data[0].pelayanan.nama,
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
                            loadInformasiRujukan(data);
                        });
                    } else {
                        urlSearchRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/carirujukanrumahsakit";
                        $("#txt_bpjs_nomor_rujukan").select2({
                            minimumInputLength: 2,
                            language: {
                                noResults: function() {
                                    return "No. Rujukan tidak ditemukan";
                                }
                            },
                            dropdownParent: $('#group_nomor_rujukan'),
                            ajax: {
                                url: urlSearchRujukan,
                                type: "GET",
                                dataType: "json",
                                crossDomain: true,
                                beforeSend: async function(request) {
                                    refreshToken().then((test) => {
                                        bpjs_token = test;
                                    })

                                    request.setRequestHeader("Accept", "application/json");
                                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                    request.setRequestHeader("x-token", bpjs_token);
                                },
                                data: function(term) {
                                    return {
                                        norujuk: term.term
                                    };
                                },
                                cache: true,
                                processResults: function(response) {
                                    if (parseInt(response.metadata.code) === 202) {
                                        Swal.fire(
                                            "No. Rujukan",
                                            response.metadata.message,
                                            "warning"
                                        );
                                    } else if (parseInt(response.metadata.code) === 200) {
                                        var data = [response.response];
                                        return {
                                            results: $.map(data, function() {
                                                return {
                                                    text: data[0].noKunjungan + " - " + data[0].peserta.nama,
                                                    id: data[0].noKunjungan,
                                                    tglKunjungan: data[0].tglKunjungan,
                                                    diagnosa_nama: data[0].diagnosa.nama,
                                                    diagnosa_kode: data[0].diagnosa.kode,
                                                    poliRujukan_nama: data[0].poliRujukan.nama,
                                                    poliRujukan_kode: data[0].poliRujukan.kode,
                                                    poliRujukan_nama: data[0].poliRujukan.nama,
                                                    provPerujuk_kode: data[0].provPerujuk.kode,
                                                    provPerujuk_nama: data[0].provPerujuk.nama,
                                                    keluhan: data[0].keluhan,
                                                    peserta_hakKelas_kode: data[0].peserta.hakKelas.kode,
                                                    peserta_hakKelas_keterangan: data[0].peserta.hakKelas.keterangan,
                                                    peserta_jenisPeserta_kode: data[0].peserta.jenisPeserta.kode,
                                                    peserta_jenisPeserta_keterangan: data[0].peserta.jenisPeserta.keterangan,
                                                    pelayanan_kode: data[0].pelayanan.kode,
                                                    pelayanan_nama: data[0].pelayanan.nama,
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
                            loadInformasiRujukan(data);
                        });
                    }
                });

                $("#txt_bpjs_nomor_rujukan").select2({
                    minimumInputLength: 2,
                    language: {
                        noResults: function() {
                            return "No. Rujukan tidak ditemukan";
                        }
                    },
                    dropdownParent: $('#group_nomor_rujukan'),
                    ajax: {
                        url: urlSearchRujukan,
                        // url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listrujukanpesertapcare",
                        type: "GET",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: function(term) {
                            return {
                                norujuk: term.term
                                // nokartu: term.term
                            };
                        },
                        cache: true,
                        processResults: function(response) {
                            if (parseInt(response.metadata.code) === 202) {
                                Swal.fire(
                                    "No. Rujukan",
                                    response.metadata.message,
                                    "warning"
                                );
                            } else if (parseInt(response.metadata.code) === 200) {
                                var data = [response.response];
                                return {
                                    results: $.map(data, function() {
                                        return {
                                            text: data[0].noKunjungan + " - " + data[0].peserta.nama,
                                            id: data[0].noKunjungan,
                                            tglKunjungan: data[0].tglKunjungan,
                                            diagnosa_nama: data[0].diagnosa.nama,
                                            diagnosa_kode: data[0].diagnosa.kode,
                                            poliRujukan_nama: data[0].poliRujukan.nama,
                                            poliRujukan_kode: data[0].poliRujukan.kode,
                                            poliRujukan_nama: data[0].poliRujukan.nama,
                                            provPerujuk_kode: data[0].provPerujuk.kode,
                                            provPerujuk_nama: data[0].provPerujuk.nama,
                                            keluhan: data[0].keluhan,
                                            peserta_hakKelas_kode: data[0].peserta.hakKelas.kode,
                                            peserta_hakKelas_keterangan: data[0].peserta.hakKelas.keterangan,
                                            peserta_jenisPeserta_kode: data[0].peserta.jenisPeserta.kode,
                                            peserta_jenisPeserta_keterangan: data[0].peserta.jenisPeserta.keterangan,
                                            pelayanan_kode: data[0].pelayanan.kode,
                                            pelayanan_nama: data[0].pelayanan.nama,
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
                    loadInformasiRujukan(data);
                });

                function loadInformasiRujukan(data) {
                    var queryDate = data.tglKunjungan,
                        dateParts = queryDate.match(/(\d+)/g)
                    realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                    $("#txt_bpjs_tanggal_rujukan").datepicker("setDate", realDate);

                    $("#txt_bpjs_diagnosa_awal").append("<option title=\"" + data.diagnosa_kode + "\" value=\"" + data.diagnosa_kode + "\">" + data.diagnosa_nama + "</option>");
                    $("#txt_bpjs_diagnosa_awal").select2("data", {
                        id: data.diagnosa_kode,
                        text: data.diagnosa_nama
                    });
                    $("#txt_bpjs_diagnosa_awal").trigger("change");


                    $("#txt_bpjs_poli_tujuan").append("<option title=\"" + data.poliRujukan_kode + "\" value=\"" + data.poliRujukan_kode + "\">" + data.poliRujukan_kode + " - " + data.poliRujukan_nama + "</option>");
                    $("#txt_bpjs_poli_tujuan").select2("data", {
                        id: data.poliRujukan_kode,
                        text: data.poliRujukan_nama
                    });
                    $("#txt_bpjs_poli_tujuan").trigger("change");

                    $("#txt_bpjs_rujuk_poli").html(data.poliRujukan_kode + " - " + data.poliRujukan_nama);
                    $("#txt_bpjs_rujuk_diagnosa").html(data.diagnosa_kode + " - " + data.diagnosa_nama);
                    $("#txt_bpjs_rujuk_keluhan").html((data.keluhan === "" || data.keluhan === undefined) ? "-" : data.keluhan);
                    $("#txt_bpjs_rujuk_hak_kelas").html(data.peserta_hakKelas_kode + " - " + data.peserta_hakKelas_keterangan);
                    $("#txt_bpjs_rujuk_jenis_peserta").html(data.peserta_jenisPeserta_kode + " - " + data.peserta_jenisPeserta_keterangan);


                    $("#txt_bpjs_asal_rujukan").append("<option title=\"" + data.provPerujuk_kode + "\" value=\"" + data.provPerujuk_kode + "\">" + data.provPerujuk_kode + " - " + data.provPerujuk_nama + "</option>");
                    $("#txt_bpjs_asal_rujukan").select2("data", {
                        id: data.provPerujuk_kode,
                        text: data.provPerujuk_nama
                    });
                    $("#txt_bpjs_asal_rujukan").trigger("change");

                    $("#txt_bpjs_kelas_rawat").val(data.peserta_hakKelas_kode).trigger("change");
                    $("#txt_bpjs_kelas_rawat").select2("data", {
                        "id": data.peserta_hakKelas_kode,
                        "text": data.peserta_hakKelas_keterangan
                    });
                    $("#txt_bpjs_jenis_layanan").val(data.pelayanan_kode).trigger("change");
                    $("#txt_bpjs_jenis_layanan").select2("data", {
                        "id": data.pelayanan_kode,
                        "text": data.pelayanan_nama
                    });

                    // $("#txt_bpjs_jenis_asal_rujukan").val(data.provPerujuk_jenis).trigger("change");
                    // $("#txt_bpjs_asal_rujukan").html("<option value=\"" + data.provPerujuk.info.kode + "\">" + data.provPerujuk.info.kode + " - " + data.provPerujuk.info.nama + "</option>")
                    /*$("#txt_bpjs_asal_rujukan").select2("data", {
                        "id": data.provPerujuk.info.kode,
                        "text": data.provPerujuk.info.kode + " - " + data.provPerujuk.info.nama
                    }, true);*/
                }

                $("#txt_bpjs_asal_rujukan").select2({
                    minimumInputLength: 2,
                    "language": {
                        "noResults": function() {
                            return "Faskes tidak ditemukan";
                        }
                    },
                    dropdownParent: $("#modal-sep-new"),
                    ajax: {
                        url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
                        type: "POST",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: function(term) {
                            return {
                                jns: $("#txt_bpjs_jenis_asal_rujukan").val(),
                                kode: term.term
                            };
                        },
                        cache: true,
                        processResults: function(response) {
                            if (parseInt(response.metadata.code) !== 200) {
                                $("#txt_bpjs_asal_rujukan").trigger("change.select2");
                            } else {
                                var data = response.response;
                                console.log(data);
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }
                }).addClass("form-control").on("select2:select", function(e) {
                    //
                });

                $("#txt_bpjs_asal_rujukan_manualigd").select2({
                    minimumInputLength: 2,
                    "language": {
                        "noResults": function() {
                            return "Faskes tidak ditemukan";
                        }
                    },
                    dropdownParent: $("#col_group_asal_rujukan_manualigd"),
                    ajax: {
                        url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
                        type: "POST",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: function(term) {
                            return {
                                jns: $("#txt_bpjs_jenis_asal_rujukan_manualigd").val(),
                                kode: term.term
                            };
                        },
                        cache: true,
                        processResults: function(response) {
                            if (parseInt(response.metadata.code) !== 200) {
                                $("#txt_bpjs_asal_rujukan_manualigd").trigger("change.select2");
                            } else {
                                var data = response.response;
                                console.log(data);
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.kode + " - " + item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }
                }).addClass("form-control").on("select2:select", function(e) {
                    //
                });

                $("#txt_bpjs_diagnosa_awal").select2({
                    minimumInputLength: 2,
                    "language": {
                        "noResults": function() {
                            return "Diagnosa tidak ditemukan";
                        }
                    },
                    dropdownParent: $("#group_diagnosa"),
                    ajax: {
                        url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getdiagnosa`,
                        type: "POST",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: function(term) {
                            return {
                                kode: term.term
                            };
                        },
                        cache: true,
                        processResults: function(response) {
                            console.log(response);
                            if (parseInt(response.metadata.code) !== 200) {
                                $("#txt_bpjs_diagnosa_awal").trigger("change.select2");
                            } else {
                                var data = response.response;
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }
                }).addClass("form-control").on("select2:select", function(e) {
                    //
                });


                $("#txt_bpjs_fktp_1").select2({
                    minimumInputLength: 2,
                    "language": {
                        "noResults": function() {
                            return "Faskes tidak ditemukan";
                        }
                    },
                    dropdownParent: $("#group_poli"),
                    ajax: {
                        url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
                        type: "POST",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: function(term) {
                            return {
                                jns: $("#txt_bpjs_jenis_asal_rujukan option:selected").val(),
                                kode: term.term
                            };
                        },
                        cache: true,
                        processResults: function(response) {
                            var data = response.response;
                            console.log(data);
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.kode
                                    }
                                })
                            };
                        }
                    }
                }).addClass("form-control").on("select2:select", function(e) {

                });


                $("#txt_bpjs_poli_tujuan").select2({
                    minimumInputLength: 2,
                    "language": {
                        "noResults": function() {
                            return "Poli tidak ditemukan";
                        }
                    },
                    dropdownParent: $("#group_poli"),
                    ajax: {
                        url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getpoli`,
                        type: "GET",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: function(term) {
                            return {
                                kode: term.term
                            };
                        },
                        processResults: function(response) {
                            if (parseInt(response.metadata.code) !== 200) {
                                return [];
                            } else {
                                var data = response.response;
                                console.log(data);
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.kode + " - " + item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        },
                        error: function(error) {
                            console.clear();
                            console.log(error);
                        }
                    }
                }).addClass("form-control").on("select2:select", function(e) {

                });




                var uid = $(this).attr("id").split("_");
                uid = uid[uid.length - 1];

                var antrian = $(this).attr("antrian");
                selectedSEPAntrian = antrian;

                var allowSEP = $(this).attr("allow_sep");
                if (allowSEP === "1") {
                    $("#btnProsesSEP").show();
                } else {
                    $("#btnProsesSEP").hide();
                }
                $("#txt_bpjs_rm").val($("#rm_" + uid).html());

                $("#txt_bpjs_internal_poli").html($("#poli_" + uid).html());


                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + "/Asesmen/antrian-detail/" + antrian,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response) {
                        var data = response.response_package.response_data[0];

                        selectedSEPAntriamMeta = data;

                        var diagnosa_kerja = data.diagnosa_kerja;
                        var diagnosa_banding = data.diagnosa_banding;
                        var icd10_kerja = data.icd10_kerja;
                        var icd10_banding = data.icd10_banding;

                        $("#txt_bpjs_internal_dk").html(diagnosa_kerja);
                        $("#txt_bpjs_internal_db").html(diagnosa_banding);



                        // $.ajax({
                        //     async: false,
                        //     url: __HOSTAPI__ + "/BPJS/get_rujukan_list/" + $("#txt_bpjs_nomor").val(),
                        //     type: "GET",
                        //     beforeSend: function(request) {
                        //         request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        //     },
                        //     success: function(response) {
                        //         console.log("RUJUKAN LIST");
                        //         console.log(response);

                        //         $("#txt_bpjs_nomor_rujukan " + " option").remove();

                        //         $("#txt_bpjs_nomor_rujukan").select2("destroy");
                        //         $("#txt_bpjs_nomor_rujukan").select2();
                        //         $("#txt_bpjs_nomor_rujukan").select2("val", "");
                        //         if (
                        //             response.response_package !== undefined &&
                        //             response.response_package.metaData !== null
                        //         ) {
                        //             if (parseInt(response.response_package.metaData.code) === 200) {
                        //                 $("#panel-rujukan").show();
                        //                 var data = response.response_package.data;

                        //                 selectedListRujukan = data;

                        //                 if (data.length > 0) {
                        //                     isRujukan = true;
                        //                     for (var a = 0; a < data.length; a++) {
                        //                         if (parseInt(data[a].pelayanan.kode) === 2) {
                        //                             var selection = document.createElement("OPTION");

                        //                             $(selection).attr("value", data[a].noKunjungan.toUpperCase()).html(data[a].noKunjungan.toUpperCase());
                        //                             $("#txt_bpjs_nomor_rujukan").append(selection);
                        //                         }
                        //                     }

                        //                     $(".informasi_rujukan").show();
                        //                     $("#btnProsesSEP").show();
                        //                     loadInformasiRujukan(selectedListRujukan[0]);
                        //                     //loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                        //                 } else {
                        //                     isRujukan = false;
                        //                     $(".informasi_rujukan").hide();
                        //                     $("#btnProsesSEP").hide();
                        //                 }
                        //             } else {
                        //                 isRujukan = false
                        //                 $(".informasi_rujukan").hide();
                        //                 $("#panel-rujukan").hide();
                        //                 $("#btnProsesSEP").hide();
                        //             }
                        //         } else {
                        //             isRujukan = false
                        //             $(".informasi_rujukan").hide();
                        //             $("#panel-rujukan").hide();
                        //             $("#btnProsesSEP").hide();
                        //         }

                        //         if (!isRujukan) {
                        //             $("#btnProsesSEP").show();
                        //             $(".informasi_rujukan").show();
                        //             $("#panel-rujukan").show();
                        //         }
                        //     },
                        //     error: function(response) {
                        //         console.log(response);
                        //     }
                        // });


                        for (var dKey in icd10_kerja) {
                            $("#txt_bpjs_internal_icdk").append("<li>" + icd10_kerja[dKey].nama + "</li>");
                        }

                        for (var dKey in icd10_banding) {
                            $("#txt_bpjs_internal_icdb").append("<li>" + icd10_banding[dKey].nama + "</li>");
                        }

                        SEPButton.html("Daftar SEP").removeClass("btn-warning").addClass("btn-info");
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });

                $("#modal-sep-new").modal("show");
            } else {
                $("#modal-sep-offline").modal("show");
            }
        });

        $("#btnSEPOffline").click(function() {
            console.log('SEP OFFLINE')
            var bpjs_offline_sep = $("#txt_bpjs_offline_sep").val();
            Swal.fire({
                title: "Data sudah benar?",
                showDenyButton: true,
                confirmButtonText: "Sudah",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Pasien",
                        type: "POST",
                        data: {
                            request: "bpjs_sep_add_offline",
                            sep: bpjs_offline_sep,
                            antrian: currentAntrianUID,
                            pasien: selectedPasien
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            if (response.response_package.response_result > 0) {
                                $("#modal-sep-offline").modal("hide");
                                $("#txt_bpjs_offline_sep").val("");
                                tableAntrian.ajax.reload();
                                tableAntrianIGD.ajax.reload();
                                tableAntrianRI.ajax.reload();
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $(".group_txt_bpjs_flag_procedure").hide();
        $(".group_txt_bpjs_kode_penunjang").hide();

        $("#txt_bpjs_tujuan_kunjungan").change(function() {
            if ($("#txt_bpjs_tujuan_kunjungan option:selected").val() == '0') {
                $(".group_txt_bpjs_flag_procedure").hide();
                $(".group_txt_bpjs_kode_penunjang").hide();
            } else {
                $(".group_txt_bpjs_flag_procedure").show();
                $(".group_txt_bpjs_kode_penunjang").show();
            }

            if ($("#txt_bpjs_tujuan_kunjungan option:selected").val() == '0' || $("#txt_bpjs_tujuan_kunjungan option:selected").val() == '2') {
                $(".group_txt_bpjs_asesmen_pelayanan").show();
            } else {
                $(".group_txt_bpjs_asesmen_pelayanan").hide();
            }
        });

        $("#btnProsesSEP").click(function() {
            var btn_proses = $(this);

            Swal.fire({
                title: 'Data sudah benar?',
                text: 'Proses Insert SEP?',
                showDenyButton: true,
                confirmButtonText: `Sudah`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    btn_proses.html('Proses...').attr('disabled', true);

                    var asal_rujukan = "";
                    var tanggal_rujukan = "";
                    var nomor_rujukan = "";
                    var ppk_rujukan = "";

                    if ($("input[type=\"radio\"][name=\"radio_tipe_rujukan\"]:checked").val() == 0) {
                        asal_rujukan = $("#txt_bpjs_jenis_asal_rujukan").val();
                        tanggal_rujukan = $("#txt_bpjs_tanggal_rujukan").val();
                        nomor_rujukan = $("#txt_bpjs_nomor_rujukan").val();
                        ppk_rujukan = $("#txt_bpjs_asal_rujukan").val();
                    } else {
                        asal_rujukan = $("#txt_bpjs_jenis_asal_rujukan_manualigd").val();
                        tanggal_rujukan = $("#txt_bpjs_tanggal_rujukan_manualigd").val();
                        nomor_rujukan = $("#txt_bpjs_nomor_rujukan_manualigd").val();
                        ppk_rujukan = $("#txt_bpjs_asal_rujukan_manualigd").val();
                    }

                    var dateNow = new Date();
                    var tglSekarang = dateNow.getFullYear() + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + str_pad(2, dateNow.getDate());

                    dataSetSEP = {
                        "request": {
                            "t_sep": {
                                "noKartu": ($("#txt_bpjs_nomor").val()) ? $("#txt_bpjs_nomor").val() : "",
                                "tglSep": tglSekarang,
                                "ppkPelayanan": ($("#txt_bpjs_faskes").val()) ? $("#txt_bpjs_faskes").val() : "",
                                "jnsPelayanan": $("#txt_bpjs_jenis_layanan").val(),
                                "klsRawat": {
                                    "klsRawatHak": $("#txt_bpjs_kelas_rawat").val(),
                                    "klsRawatNaik": ($("#txt_bpjs_kelas_rawat_naik").val()) ? $("#txt_bpjs_kelas_rawat_naik").val() : "",
                                    "pembiayaan": ($("#txt_bpjs_kelas_rawat_naik_pembiayaan").val()) ? $("#txt_bpjs_kelas_rawat_naik_pembiayaan").val() : "",
                                    "penanggungJawab": ($("#txt_bpjs_kelas_rawat_naik_pembiayaan").val()) ? $("#txt_bpjs_kelas_rawat_naik_pembiayaan option:selected").text() : ""
                                },
                                "noMR": $("#txt_bpjs_rm").val().replace(new RegExp(/-/g), ""),
                                "rujukan": {
                                    "asalRujukan": asal_rujukan,
                                    "tglRujukan": tanggal_rujukan,
                                    "noRujukan": (nomor_rujukan) ? nomor_rujukan : "",
                                    "ppkRujukan": (ppk_rujukan) ? ppk_rujukan : "",
                                },
                                "catatan": $("#txt_bpjs_catatan").val(),
                                "diagAwal": ($("#txt_bpjs_diagnosa_awal").val()) ? $("#txt_bpjs_diagnosa_awal").val() : "",
                                "poli": {
                                    "tujuan": ($("#txt_bpjs_poli_tujuan").val()) ? $("#txt_bpjs_poli_tujuan").val() : "",
                                    "eksekutif": $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val()
                                },
                                "cob": {
                                    "cob": $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val()
                                },
                                "katarak": {
                                    "katarak": $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val()
                                },
                                "jaminan": {
                                    "lakaLantas": $("#txt_bpjs_laka").val(),
                                    "noLP": ($("#txt_bpjs_laka_no_lp").val()) ? $("#txt_bpjs_laka_no_lp").val() : "",
                                    "penjamin": {
                                        "tglKejadian": $("#txt_bpjs_laka_tanggal").val(),
                                        "keterangan": $("#txt_bpjs_laka_keterangan").val(),
                                        "suplesi": {
                                            "suplesi": $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                                            "noSepSuplesi": ($("#txt_bpjs_laka_suplesi_nomor").val()) ? $("#txt_bpjs_laka_suplesi_nomor").val() : "",
                                            "lokasiLaka": {
                                                "kdPropinsi": ($("#txt_bpjs_laka_suplesi_provinsi").val()) ? $("#txt_bpjs_laka_suplesi_provinsi").val() : "",
                                                "kdKabupaten": ($("#txt_bpjs_laka_suplesi_kabupaten").val()) ? $("#txt_bpjs_laka_suplesi_kabupaten").val() : "",
                                                "kdKecamatan": ($("#txt_bpjs_laka_suplesi_kecamatan").val()) ? $("#txt_bpjs_laka_suplesi_kecamatan").val() : ""
                                            }
                                        }
                                    }
                                },
                                "tujuanKunj": $("#txt_bpjs_tujuan_kunjungan").val(),
                                "flagProcedure": $("#txt_bpjs_flag_procedure").val(),
                                "kdPenunjang": $("#txt_bpjs_kode_penunjang").val(),
                                "assesmentPel": $("#txt_bpjs_asesmen_pelayanan").val(),
                                "skdp": {
                                    "noSurat": ($("#txt_bpjs_skdp").val()) ? $("#txt_bpjs_skdp").val() : "",
                                    "kodeDPJP": ($("#txt_bpjs_dpjp").val()) ? $("#txt_bpjs_dpjp").val() : "",
                                },
                                "dpjpLayan": ($("#txt_bpjs_jenis_layanan").val() === '2') ? $("#txt_bpjs_dpjp").val() : "",
                                "noTelp": $("#txt_bpjs_telepon").val(),
                                "user": __MY_NAME__
                            }
                        }
                    };

                    $.ajax({
                        url: `${__BPJS_SERVICE_URL__}sep/sync.sh/insertsep`,
                        type: "POST",
                        dataType: "json",
                        data: JSON.stringify(dataSetSEP),
                        crossDomain: true,
                        beforeSend: async function(request) {
                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/json");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        success: function(response) {
                            console.log(response.metadata.code);
                            if (parseInt(response.metadata.code) === 200) {
                                var data = response.response;
                                Swal.fire(
                                    "SEP Berhasil Disimpan!",
                                    "No.SEP " + data.noSep,
                                    "success"
                                ).then((result) => {
                                    push_socket(__ME__, "antrian_poli_baru", "*", "Antrian pasien a/n. " + $("#nama").val(), "warning").then(function() {
                                        $("#modal-sep-new").modal("hide");

                                        printSEP(data.noSep);
                                        btn_proses.html('<i class="fa fa-check"></i> Proses').attr('disabled', false);
                                    });
                                });

                                tableAntrian.ajax.reload();
                                tableAntrianIGD.ajax.reload();
                                tableAntrianRI.ajax.reload();

                                $.ajax({
                                    async: false,
                                    url: __HOSTAPI__ + "/BPJS",
                                    type: "POST",
                                    data: {
                                        request: "sep_insert",
                                        antrian: currentAntrianUID,
                                        pasien: currentAntrianPasien,
                                        noSep: data.noSep,
                                        sep_dinsos: data.informasi.dinsos,
                                        sep_prolanis: data.prolanisPRB,
                                        sep_sktm: data.noSKTM,

                                        no_kartu: ($("#txt_bpjs_nomor").val()) ? $("#txt_bpjs_nomor").val() : "",
                                        spesialistik_kode: $("#txt_bpjs_dpjp_spesialistik").val(),
                                        spesialistik_nama: $("#txt_bpjs_dpjp_spesialistik option:selected").text(),
                                        ppk_pelayanan: ($("#txt_bpjs_faskes").val()) ? $("#txt_bpjs_faskes").val() : "",
                                        kelas_rawat: $("#txt_bpjs_kelas_rawat").val(),
                                        no_mr: $("#txt_bpjs_rm").val().replace(new RegExp(/-/g), ""),
                                        asal_rujukan: asal_rujukan,
                                        ppk_rujukan: (ppk_rujukan) ? ppk_rujukan : "",
                                        tgl_rujukan: tanggal_rujukan,
                                        no_rujukan: (nomor_rujukan) ? nomor_rujukan : "",

                                        tujuan_kunjungan: $("#txt_bpjs_tujuan_kunjungan").val(),
                                        flag_procedure: $("#txt_bpjs_flag_procedure").val(),
                                        kode_penunjang: $("#txt_bpjs_kode_penunjang").val(),
                                        asesmen_pelayanan: $("#txt_bpjs_asesmen_pelayanan").val(),
                                        jenis_pelayanan: $("#txt_bpjs_jenis_layanan").val(),

                                        catatan: $("#txt_bpjs_catatan").val(),
                                        diagnosa_awal: ($("#txt_bpjs_diagnosa_awal").val()) ? $("#txt_bpjs_diagnosa_awal").val() : "",
                                        diagnosa_kode: $("#txt_bpjs_diagnosa_awal").select2('data')[0].text,
                                        poli: ($("#txt_bpjs_poli_tujuan").val()) ? $("#txt_bpjs_poli_tujuan").val() : "",
                                        eksekutif: $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val(),
                                        cob: $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val(),
                                        katarak: $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val(),

                                        laka_lantas: ($("#txt_bpjs_laka_no_lp").val()) ? '1' : '0',
                                        laka_lantas_penjamin: selectedLakaPenjamin.join(","),
                                        laka_lantas_tanggal_kejadian: $("#txt_bpjs_laka_tanggal").val(),
                                        laka_lantas_keterangan: $("#txt_bpjs_laka_keterangan").val(),
                                        laka_lantas_suplesi: $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                                        laka_lantas_suplesi_nomor: ($("#txt_bpjs_laka_suplesi_nomor").val()) ? $("#txt_bpjs_laka_suplesi_nomor").val() : "",
                                        laka_lantas_suplesi_provinsi: ($("#txt_bpjs_laka_suplesi_provinsi").val()) ? $("#txt_bpjs_laka_suplesi_provinsi").val() : "",
                                        laka_lantas_suplesi_kabupaten: ($("#txt_bpjs_laka_suplesi_kabupaten").val()) ? $("#txt_bpjs_laka_suplesi_kabupaten").val() : "",
                                        laka_lantas_suplesi_kecamatan: ($("#txt_bpjs_laka_suplesi_kecamatan").val()) ? $("#txt_bpjs_laka_suplesi_kecamatan").val() : "",

                                        skdp: ($("#txt_bpjs_skdp").val()) ? $("#txt_bpjs_skdp").val() : "",
                                        dpjp: ($("#txt_bpjs_dpjp").val()) ? $("#txt_bpjs_dpjp").val() : "",
                                        dpjp_nama: ($("#txt_bpjs_dpjp").val()) ? $("#txt_bpjs_dpjp").text() : "",
                                        telepon: $("#txt_bpjs_telepon").val()
                                    },
                                    beforeSend: function(request) {
                                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                    },
                                    success: function(response) {
                                        if (parseInt(response.response_package.bpjs.metaData.code) === 200) {
                                            console.log('Insert SEP Berhasil');
                                        } else {
                                            //
                                        }
                                    },
                                    error: function(response) {
                                        console.clear();
                                        console.log(response);
                                    }
                                });
                            } else {
                                Swal.fire(
                                    "BPJS SEP",
                                    response.metadata.message,
                                    "error"
                                );
                                btn_proses.html('<i class="fa fa-check"></i> Proses').attr('disabled', false);

                            }
                        },
                        error: function(response) {
                            Swal.fire(
                                "BPJS SEP",
                                response.responseJSON.metadata.message,
                                "error"
                            );
                            btn_proses.html('<i class="fa fa-check"></i> Proses').attr('disabled', false);

                            console.clear();
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {}
            });
        });

        $("#txt_bpjs_laka_suplesi_provinsi").on("change", function() {
            loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val());
        });

        $("#txt_bpjs_laka_suplesi_kabupaten").on("change", function() {
            loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val());
        });


        /*================== FORM CARI AREA ====================*/

        var CariPasien = $("#table-pencarian-pasien").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            "bFilter": false,
            "bInfo": false,
            lengthMenu: [
                [10, 20, -1],
                [10, 20, "All"]
            ],
            serverMethod: "POST",

            "ajax": {
                url: __HOSTAPI__ + "/Antrian",
                type: "POST",
                data: function(d) {
                    d.request = "cari_pasien";
                    d.cari = $("#txt_cari").val().toLowerCase();
                },
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc: function(response) {
                    $("#loader-search").attr("hidden", true);
                    var returnedData = [];
                    var parsedData = [];
                    if (response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    if (returnedData.length === 0 && $("#txt_cari").val() !== "") {
                        $("#btnTambahPasien").fadeIn();
                    } else {
                        $("#btnTambahPasien").fadeOut();
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;



                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Pasien"
            },
            "columns": [{
                    "data": "autonum",
                    render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": "autonum",
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.no_rm + "</span>";
                    }
                },
                {
                    "data": "autonum",
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nik + "</span>";
                    }
                },
                {
                    "data": "autonum",
                    render: function(data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data": "autonum",
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.jenkel + "</span>";
                    }
                },
                {
                    "data": "autonum",
                    render: function(data, type, row, meta) {
                        if (!row.lengkap) {
                            buttonAksi = "<button id=\"btn_lengkapi_pasien_" + row.uid + "\" class=\"btn btn-sm btn-warning btnLengkapiPasien\" data-toggle=\"tooltip\" title=\"Lengkapi Data Pasien\">" +
                                "<span><i class=\"fa fa-pencil-alt\"></i> Lengkapi Data</span>" +
                                "</button>";
                        } else {
                            buttonAksi = "<button id=\"btn_daftar_pasient_" + row.uid + "\" class=\"btn btn-sm btn-info btnDaftarPasien\" data-toggle=\"tooltip\" title=\"Tambah ke Antrian\">" +
                                "<span><i class=\"fa fa-user-plus\"></i>Tambah</span>" +
                                "</button>";
                        }

                        if (row.berobat == true) {
                            buttonAksi = "<span class=\"badge badge-warning\">Sedang Berobat</span>";
                        }

                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            buttonAksi +
                            "</div>";
                    }
                }
            ]
        });

        /*$('#table-list-pencarian').DataTable({
        	"bFilter": false,
        	"bInfo" : false
        });*/

        $("#txt_cari").on('keyup', function() {
            CariPasien.ajax.reload();
            $("#loader-search").removeAttr("hidden");
        });

        /*$("#txt_cari").on('keyup', function() {
            CariPasien.ajax.reload();
			params = $("#txt_cari").val();

			$("#table-list-pencarian tbody").html("");
			$("#pencarian-notif").attr("hidden",true);
			$("#loader-search").removeAttr("hidden");
			if (params !== ""){
				setTimeout(function() {
					$.ajax({
						async: false,
						url:__HOSTAPI__ + "/Antrian/cari-pasien/" + params,
						type: "GET",
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						success: function(response){
							var MetaData = dataTindakan = response.response_package.response_data;
							var html = "";
							if (MetaData != ""){
								$.each(MetaData, function(key, item){
									var nik = item.nik;
									if (nik == null){
										nik = '-';
									}

                                    var lengkap = "";

									var buttonAksi = "";

                                    if(!item.lengkap) {
                                        buttonAksi = "<td style='text-align:center;'><button id=\"btn_lengkapi_pasien_" + item.uid + "\" class=\"btn btn-sm btn-warning btnLengkapiPasien\" data-toggle=\"tooltip\" title=\"Lengkapi Data Pasien\">" +
                                            "<span><i class=\"fa fa-pencil-alt\"></i> Lengkapi Data</span>" +
                                            "</button></td>";
                                    } else {
                                        buttonAksi = "<td style='text-align:center;'>" +
                                            "<button id=\"btn_daftar_pasient_" + item.uid + "\" class=\"btn btn-sm btn-info btnDaftarPasien\" data-toggle=\"tooltip\" title=\"Tambah ke Antrian\">" +
                                            "<span><i class=\"fa fa-user-plus\"></i>Tambah</span>" +
                                            "</button>" +
                                            "</td>";
                                    }



									if (item.berobat == true) {
										buttonAksi = "<td clsas=\"wrap_content\" style=\"text-align:center;\"><span class=\"badge badge-warning\">Sedang Berobat</span>" + lengkap + "</td>";
									}



									html += "<tr disabled>" +
												"<td class=\"wrap_content\">"+ item.autonum  +"</td>" +
												"<td>"+ item.no_rm +"</td>" +
												"<td>"+ item.nama +"</td>" +
												"<td>"+ nik +"</td>" +
												"<td class=\"wrap_content\">"+ item.jenkel +"</td>" +
												buttonAksi +
											"</tr>";
								});
							} else {
								html += "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
							}
							
							$("#table-list-pencarian tbody").html(html);
							$("#loader-search").attr("hidden",true);
						},
						error: function(response) {
							console.log(response);
						}
					});
					
				}, 250);
			} else {
				$("#loader-search").attr("hidden",true);

				var html = "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
				$("#table-list-pencarian tbody").html(html);
			}
			
			$("#btnTambahPasien").fadeIn("fast");
		});*/

        $("body").on("click", ".btnLengkapiPasien", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            localStorage.setItem("currentPasien", uid);
            localStorage.setItem("currentAntrianType", currentAntrianType);
            localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
            location.href = __HOSTNAME__ + "/pasien/edit/" + uid + "?antrian=true"
        });

        $("#btnTambahPasien").click(function() {
            localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
            localStorage.setItem("currentAntrianType", currentAntrianType);
            //location.href = __HOSTNAME__ + "/pasien/edit/" + uid + "?antrian=true";
        });

        $("#btnTambahAntrian").click(function() {
            var currentAntrian = $("#txt_current_antrian").attr("current_queue");
            if (currentAntrian == undefined || currentAntrian == null) {
                alert("Tidak ada antrian");
            } else {
                currentAntrianType = "DEFAULT";
                $("#btnTambahPasien").fadeOut("false");
                $("#txt_cari").val("");
                $("#table-list-pencarian tbody").html("<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>");
                $("#modal-cari").modal("show");
            }
        });

        $("body").on("click", ".btnDaftarPasien", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            localStorage.setItem("currentPasien", uid);
            localStorage.setItem("currentAntrianType", currentAntrianType);
            localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
            location.href = __HOSTNAME__ + "/rawat_jalan/resepsionis/tambah/" + uid;
        });


        // function loadKelasRawat(selected = "") {
        //     $.ajax({
        //         async: false,
        //         url: __HOSTAPI__ + "/BPJS/get_kelas_rawat_select2",
        //         type: "GET",
        //         beforeSend: function(request) {
        //             request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        //         },
        //         success: function(response) {

        //             if (response.response_package === null || response.response_package.data === null || response.response_package.data.list === null) {
        //                 loadKelasRawat(selected);
        //             } else {
        //                 var data = response.response_package.data.list;
        //                 $("#txt_bpjs_kelas_rawat option").remove();
        //                 var targetParse = ["0", "I", "II", "III"];
        //                 for (var a = 0; a < data.length; a++) {
        //                     var selection = document.createElement("OPTION");

        //                     $(selection).attr("value", data[a].kode).html(data[a].nama);

        //                     var checkKelasNama = data[a].nama.toUpperCase().split("KELAS");
        //                     var checkSelectedKelas = selected.toUpperCase().split("KELAS");
        //                     if (checkKelasNama.length > 1) {
        //                         if (data[a].nama.toUpperCase() === "KELAS " + targetParse.indexOf(checkSelectedKelas[1].trim())) {
        //                             $(selection).attr("selected", "selected");
        //                             //console.log(data[a].nama.toUpperCase() + " >>> " + "KELAS " + targetParse.indexOf(checkSelectedKelas[1].trim()));
        //                         } else {
        //                             //console.log(data[a].nama.toUpperCase() + " >>> " + selected.toUpperCase());
        //                         }
        //                     } else {
        //                         if (data[a].nama.toUpperCase() === selected.toUpperCase()) {
        //                             $(selection).attr("selected", "selected");
        //                         }
        //                     }

        //                     $("#txt_bpjs_kelas_rawat").append(selection);
        //                 }
        //             }
        //         },
        //         error: function(response) {
        //             console.log(response);
        //         }
        //     });
        // }


        $("#txt_bpjs_dpjp_spesialistik").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Spesialis/Subspesialis tidak ditemukan";
                }
            },
            dropdownParent: $("#group_spesialistik"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getpoli`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                data: function(term) {
                    return {
                        kode: term.term
                    };
                },
                processResults: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
                    } else {
                        var data = response.response;
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.kode + " - " + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            loadDPJPSpesialis("#txt_bpjs_dpjp");
        });

        function loadSpesialistik(target) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getspesialis`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    if (response.response === null) {
                        loadSpesialistik(target);
                    } else {
                        var data = response.response;
                        $(target + " option").remove();
                        if (data === null) {
                            loadSpesialistik(target);
                        } else {
                            for (var a = 0; a < data.length; a++) {
                                var selection = document.createElement("OPTION");

                                $(selection).attr("value", data[a].kode).html(data[a].nama);
                                $(target).append(selection);
                            }
                            loadDPJPSpesialis("#txt_bpjs_dpjp");
                        }

                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadDPJPSpesialis(target) {
            var dateNow = new Date();
            var tglSekarang = dateNow.getFullYear() + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + str_pad(2, dateNow.getDate());

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdokter?jnspelayanan=" + $("#txt_bpjs_jenis_layanan option:selected").val() + "&tglpelayanan=" + tglSekarang + "&kode=" + $("#txt_bpjs_dpjp_spesialistik").val(),
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    $(target + " option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
                    } else {
                        var data = response.response;
                        for (var a = 0; a < data.length; a++) {
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

        function loadProvinsi(target) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getprov`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {

                    if (response.response === null) {
                        loadProvinsi(target);
                    } else {
                        var data = response.response;
                        $(target + " option").remove();
                        for (var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", data[a].kode).html(data[a].nama);
                            $(target).append(selection);
                        }

                        loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi option:selected").val());
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadKabupaten(target, provinsi) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getkab?kodeprov=` + provinsi,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {

                    if (response.response === null) {
                        loadKabupaten(target, provinsi);
                    } else {
                        var data = response.response;
                        $(target + " option").remove();
                        for (var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", data[a].kode).html(data[a].nama);
                            $(target).append(selection);
                        }

                        loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten option:selected").val());
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadKecamatan(target, kabupaten) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getkec?kodekab=` + kabupaten,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {

                    if (response.response === null) {
                        loadKecamatan(target, kabupaten);
                    } else {
                        var data = response.response;
                        $(target + " option").remove();
                        for (var a = 0; a < data.length; a++) {
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



        //SOCKET
        /*Sync.onmessage = function(evt) {
        	var signalData = JSON.parse(evt.data);
        	var command = signalData.protocols;
        	var type = signalData.type;
        	var sender = signalData.sender;
        	var receiver = signalData.receiver;
        	var time = signalData.time;
        	var parameter = signalData.parameter;

        	if(command !== undefined && command !== null && command !== "") {
        		protocolLib[command](command, type, parameter, sender, receiver, time);
        	}
        }*/

        protocolLib = {
            userlist: function(protocols, type, parameter, sender, receiver, time) {
                //
            },
            userlogin: function(protocols, type, parameter, sender, receiver, time) {
                //
            },
            isCalling: function(protocols, type, parameter, sender, receiver, time) {
                $("#btnPanggil").attr("disabled", "disabled");
            },
            doneCalling: function(protocols, type, parameter, sender, receiver, time) {
                $("#btnPanggil").removeAttr("disabled");
            },
            anjungan_kunjungan_baru: function(protocols, type, parameter, sender, receiver, time) {
                refresh_notification();
                reinitAntrianSync($("#txt_loket").val());
            },
            retur_barhasil: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrian.ajax.reload();
                tableAntrianIGD.ajax.reload();
                tableAntrianRI.ajax.reload();
            },
            loket_ambil_tiket: function(protocols, type, parameter, sender, receiver, time) {
                reinitAntrianSync($("#txt_loket").val());
            }
            /*anjungan_kunjungan_panggil: function(protocols, type, parameter, sender, receiver, time) {
                //
            }*/
        };

        //INIT
        function reinitAntrianSync(argument) {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Anjungan/check_job/" + argument,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var dataCheck = response.response_package;
                    $("#sisa_antrian").html(dataCheck.response_standby);
                    if (dataCheck.response_data.length > 0) {
                        //Belum terproses
                        $("#btnGunakanLoket").attr("disabled", "disabled");
                        $("#btnSelesaiGunakan").removeAttr("disabled");
                        $("#txt_loket")
                            .append("<option value=\"" + dataCheck.response_data[0].loket.uid + "\">" + dataCheck.response_data[0].loket.nama_loket + "</option>")
                            .attr("disabled", "disabled");
                        $("#txt_loket").select2();
                        // $("#txt_current_antrian").html(dataCheck.response_queue).attr({
                        // 	"current_queue": dataCheck.response_queue_id
                        // });

                    } else {
                        if (dataCheck.response_used != undefined && dataCheck.response_used != "") {
                            load_loket("#txt_loket", dataCheck.response_used);
                            $("#txt_loket").attr("disabled", "disabled");
                            $("#btnSelesaiGunakan").removeAttr("disabled", "disabled");
                            $("#btnGunakanLoket").attr("disabled", "disabled");
                            //reloadPanggilan($("#txt_loket").val(), dataCheck.response_queue_id);
                            //Otomatis Panggil
                            //reloadPanggilan($("#txt_loket").val());
                        } else {
                            load_loket("#txt_loket");
                            $("#btnNext").attr("disabled", "disabled");
                            $("#btnTambahAntrian").attr("disabled", "disabled");
                        }
                        $("#txt_loket").select2();
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        reinitAntrianSync($("#txt_loket").val());

        function load_loket(target, selected = "") {
            //
            var loketData;
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Anjungan/avail_loket",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    loketData = response.response_package.response_data;
                    $(target).find("option").remove();
                    for (var a = 0; a < loketData.length; a++) {
                        $(target).append("<option " + (loketData[a].uid == selected ? "selected=\"selected\"" : "") + " value=\"" + loketData[a].uid + "\">" + loketData[a].nama_loket + "</option>")
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return loketData;
        }

        function reloadPanggilan(loket, currentQueue = "") {
            if (currentQueue != "") {
                dataForm = {
                    request: "next_antrian",
                    loket: loket,
                    currentQueue: currentQueue
                }
            } else {
                dataForm = {
                    request: "next_antrian",
                    loket: loket
                }
            }
            var currentQueue;
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Anjungan",
                type: "POST",
                data: dataForm,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    if (response.response_package !== undefined && response.response_package !== null) {
                        currentQueue = response.response_package;
                        $("#sisa_antrian").html(currentQueue.response_standby);
                        if ((currentQueue.response_queue == "" || currentQueue.response_queue == undefined || currentQueue.response_queue == null || currentQueue.response_queue == 0)) {
                            //reloadPanggilan(loket, "");
                        } else {
                            $("#txt_current_antrian").html((currentQueue.response_queue == "" || currentQueue.response_queue == undefined || currentQueue.response_queue == null) ? "0" : currentQueue.response_queue).attr({
                                "current_queue": currentQueue.response_queue_id
                            });

                            push_socket($("#txt_loket").val(), "loket_ambil_tiket", "*", {
                                loket: $("#txt_loket").val(),
                                nomor: $("#txt_current_antrian").html()
                            }, "info");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return currentQueue;
        }

        $("#btnGunakanLoket").click(function() {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Anjungan",
                type: "POST",
                data: {
                    request: "ambil_antrian",
                    loket: $("#txt_loket").val()
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    if (response.response_package.response_result > 0) {
                        notification("success", "Loket berhasil digunakan", 3000, "hasil_loket");
                        $("#btnGunakanLoket").attr("disabled", "disabled");
                        //reloadPanggilan($("#txt_loket").val());
                        $("#txt_loket").attr("disabled", "disabled");
                        $("#btnSelesaiGunakan").removeAttr("disabled");
                        $("#btnNext").removeAttr("disabled", "disabled");
                        $("#btnTambahAntrian").removeAttr("disabled");
                    } else {
                        if (response.response_package.response_loket_user == __ME__) {
                            notification("success", "Loket berhasil digunakan", 3000, "hasil_loket");
                            $("#btnGunakanLoket").attr("disabled", "disabled");
                            //reloadPanggilan($("#txt_loket").val());
                            $("#txt_loket").attr("disabled", "disabled");
                            $("#btnSelesaiGunakan").removeAttr("disabled");
                            $("#btnNext").removeAttr("disabled", "disabled");
                            $("#btnTambahAntrian").removeAttr("disabled");
                        } else {
                            notification("info", "Loket sudah digunakan " + response.response_package.response_loket, 3000, "hasil_loket");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btnSelesaiGunakan").click(function() {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Anjungan",
                type: "POST",
                data: {
                    request: "selesai_antrian"
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    /*if(response.response_package.response_result > 0) {
                    	load_loket("#txt_loket");
                    	notification ("success", "Berhasil keluar dari loket", 3000, "hasil_loket");
                    	$("#txt_current_antrian").html("0");
                    	$("#btnGunakanLoket").removeAttr("disabled");
                    	$("#txt_loket").removeAttr("disabled");
                    	$("#btnSelesaiGunakan").attr("disabled", "disabled");
                    	$("#btnNext").attr("disabled", "disabled");
                    	$("#btnTambahAntrian").attr("disabled", "disabled");
                    } else {
                    	notification ("warning", "Anda telah keluar loket", 3000, "hasil_loket");
                    }*/
                    load_loket("#txt_loket");
                    notification("success", "Berhasil keluar dari loket", 3000, "hasil_loket");
                    $("#txt_current_antrian").html("0");
                    $("#btnGunakanLoket").removeAttr("disabled");
                    $("#txt_loket").removeAttr("disabled");
                    $("#btnSelesaiGunakan").attr("disabled", "disabled");
                    $("#btnNext").attr("disabled", "disabled");
                    $("#btnTambahAntrian").attr("disabled", "disabled");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btnNext").click(function() {
            reloadPanggilan($("#txt_loket").val(), $("#txt_current_antrian").attr("current_queue"));
            loadTerlewat($("#txt_loket").val());
        });

        $("#btnPanggil").click(function() {
            push_socket("display", "isCalling", "*", "", "info");
            push_socket($("#txt_loket").val(), "anjungan_kunjungan_panggil", "display_machine", {
                loket: $("#txt_loket").val(),
                nomor: $("#txt_current_antrian").html()
            }, "info");
        });

        loadTerlewat($("#txt_loket").val());
        $("#antrian_terlewat").select2();

        function loadTerlewat(loket) {
            $("#antrian_terlewat option").remove();
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Anjungan/terlewat/" + loket,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    var data = response.response_package.response_data;
                    for (var a in data) {
                        if (data[a].response_queue !== $("#txt_current_antrian").html()) {
                            $("#antrian_terlewat").append("<option value=\"" + data[a].id + "\">" + data[a].response_queue + "</option>");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#btnSetLewat").click(function() {
            //localStorage.setItem("currentPasien", uid);
            //localStorage.setItem("currentAntrianType", currentAntrianType);
            //localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
            $("#txt_current_antrian").html($("#antrian_terlewat option:selected").text()).attr({
                "current_queue": $("#antrian_terlewat option:selected").val()
            });
        });

        function printSEP(target) {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "sep/sync.sh/carisep?nomorsep=" + target,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    refreshToken().then((test) => {
                        bpjs_token = test;
                    })

                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var dataSEP = response.response;
                    $("#sep_nomor").html(dataSEP.noSep);
                    $("#sep_tanggal").html(dataSEP.tglSep);

                    $("#sep_nomor_kartu").html(dataSEP.peserta.noKartu + " <b class=\"text-info\">[No. Mr " + dataSEP.peserta.noMr + "]</b>");
                    $("#sep_nama_peserta").html(dataSEP.peserta.nama);
                    $("#sep_tanggal_lahir").html(dataSEP.peserta.tglLahir);
                    $("#sep_nomor_telepon").html(($("#txt_bpjs_telepon").val()) ? $("#txt_bpjs_telepon").val() : "-");
                    $("#sep_spesialis").html(dataSEP.poli);
                    $("#sep_catatan").html(dataSEP.catatan);

                    $("#sep_peserta").html(dataSEP.peserta.jnsPeserta);
                    $("#sep_jenis_rawat").html(dataSEP.jnsPelayanan);
                    $("#sep_jenis_kunjungan").html(dataSEP.tujuanKunj.nama);
                    $("#sep_procedure").html(dataSEP.flagProcedure.nama);
                    $("#sep_kelas_hak").html(dataSEP.peserta.hakKelas);
                    $("#sep_kelas_rawat").html(dataSEP.kelasRawat);
                    $("#sep_penjamin").html(dataSEP.penjamin);

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rc/sync.sh/carisep/?nomorsep=" + target,
                        type: "GET",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        success: function(response) {
                            var dataTambahan = response.response;
                            $("#sep_diagnosa_awal").html(dataTambahan.diagnosa);
                            $("#sep_perujuk").html(dataTambahan.provPerujuk.nmProviderPerujuk + " (" + dataTambahan.provPerujuk.kdProviderPerujuk + ")");
                        },
                        error: function(response) {
                            //
                        }
                    });

                    $("#modal-sep-cetak").modal("show");
                },
                error: function(response) {
                    //
                }
            });
        };


        $("body").on("click", "#btnCetakSEP", function() {
            var dateNow = new Date();
            var tgl_cetak = str_pad(2, dateNow.getDate()) + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();

            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/bpjs_sep.php",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    html_data_kiri: $("#data_sep_cetak_kiri").html(),
                    html_data_kanan: $("#data_sep_cetak_kanan").html(),
                    tgl_cetak: tgl_cetak
                },
                success: function(response) {
                    //$("#dokumen-viewer").html(response);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "Cetak SEP",
                        afterPrint: function() {
                            //
                        }
                    });
                }
            });
        });


        $("body").on("click", ".print_manager", function() {
            var targetSurat = $(this).attr("jenis");
            if (targetSurat === "SEP") {
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];

                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + "/BPJS/get_sep_detail/" + id,
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "GET",
                    success: function(response) {
                        var dataSEP = response.response_package.response_data[0];

                        $.ajax({
                            url: __BPJS_SERVICE_URL__ + "sep/sync.sh/carisep?nomorsep=" + dataSEP.sep_no,
                            type: "GET",
                            dataType: "json",
                            crossDomain: true,
                            beforeSend: async function(request) {
                                refreshToken().then((test) => {
                                    bpjs_token = test;
                                })

                                request.setRequestHeader("Accept", "application/json");
                                request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                request.setRequestHeader("x-token", bpjs_token);
                            },
                            success: function(response) {
                                var dataSEP = response.response;
                                if (parseInt(response.metadata.code) === 200) {
                                    $("#sep_nomor").html(dataSEP.noSep);
                                    $("#sep_tanggal").html(dataSEP.tglSep);

                                    $("#sep_nomor_kartu").html(dataSEP.peserta.noKartu + " <b class=\"text-info\">[No. Mr " + dataSEP.peserta.noMr + "]</b>");
                                    $("#sep_nama_peserta").html(dataSEP.peserta.nama);
                                    $("#sep_tanggal_lahir").html(dataSEP.peserta.tglLahir);
                                    $("#sep_nomor_telepon").html(($("#txt_bpjs_telepon").val()) ? $("#txt_bpjs_telepon").val() : "-");
                                    $("#sep_spesialis").html(dataSEP.poli);
                                    $("#sep_catatan").html(dataSEP.catatan);

                                    $("#sep_peserta").html(dataSEP.peserta.jnsPeserta);
                                    $("#sep_jenis_rawat").html(dataSEP.jnsPelayanan);
                                    $("#sep_jenis_kunjungan").html(dataSEP.tujuanKunj.nama);
                                    $("#sep_procedure").html(dataSEP.flagProcedure.nama);
                                    $("#sep_kelas_hak").html(dataSEP.peserta.hakKelas);
                                    $("#sep_kelas_rawat").html(dataSEP.kelasRawat);
                                    $("#sep_penjamin").html(dataSEP.penjamin);

                                    $.ajax({
                                        url: __BPJS_SERVICE_URL__ + "rc/sync.sh/carisep/?nomorsep=" + dataSEP.noSep,
                                        type: "GET",
                                        dataType: "json",
                                        crossDomain: true,
                                        beforeSend: async function(request) {
                                            refreshToken().then((test) => {
                                                bpjs_token = test;
                                            })

                                            request.setRequestHeader("Accept", "application/json");
                                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                            request.setRequestHeader("x-token", bpjs_token);
                                        },
                                        success: function(response) {
                                            var dataTambahan = response.response;
                                            $("#sep_diagnosa_awal").html(dataTambahan.diagnosa);
                                            $("#sep_perujuk").html(dataTambahan.provPerujuk.nmProviderPerujuk + " (" + dataTambahan.provPerujuk.kdProviderPerujuk + ")");
                                        },
                                        error: function(response) {
                                            //
                                        }
                                    });
                                    $("#modal-sep-cetak").modal("show");
                                } else {
                                    Swal.fire(
                                        'SEP tidak ditemukan!',
                                        'Data SEP tidak ada atau telah dihapus.',
                                        'warning'
                                    );
                                }
                            },
                            error: function(response) {
                                //
                            }
                        });

                    },
                    error: function(response) {
                        //
                    }
                });
            } else {
                var uid = $(this).attr("id").split("_");
                uid = uid[uid.length - 1];

                var pasien = $(this).attr("pasien");

                //$("#target-judul-cetak").html("CETAK " + targetSurat.toUpperCase() + " PASIEN");
                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + "/Pasien/pasien-detail/" + pasien,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response) {
                        dataPasien = response.response_package.response_data[0];
                        dataPasien.pc_customer = __PC_CUSTOMER__;
                        dataPasien.pc_customer_address_short = __PC_CUSTOMER_ADDRESS_SHORT__;
                        dataPasien.pc_customer_address = __PC_CUSTOMER_ADDRESS__;
                        dataPasien.pc_dokter = $("#dokter_" + uid).html();
                        dataPasien.waktu_masuk = $("#waktu_masuk_" + uid).html();

                        $.ajax({
                            async: false,
                            url: __HOST__ + "miscellaneous/print_template/pasien_" + targetSurat + ".php",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            data: dataPasien,
                            success: function(response) {
                                //$("#dokumen-viewer").html(response);
                                var containerItem = document.createElement("DIV");
                                $(containerItem).html(response);
                                $(containerItem).printThis({
                                    importCSS: true,
                                    base: false,
                                    pageTitle: "cetak",
                                    afterPrint: function() {
                                        //
                                    }
                                });
                            }
                        });
                    },
                    error: function(response) {
                        //
                    }
                });
            }
        });

        setTimeout(function() {

            tableAntrian.ajax.reload();
            tableAntrianRI.ajax.reload();
            tableAntrianIGD.ajax.reload();

        }, 5000);
    });
</script>

<script src="<?= __HOSTNAME__ ?>/template/assets/vendor/toastr.min.js"></script>
<script src="<?= __HOSTNAME__ ?>/template/assets/js/toastr.js"></script>

<div id="modal-print" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Print Option
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div id="modal-cari" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Tambah Kunjungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-6">
                    <div class="col-md-6">
                        <div class="row">
                            <label for="txt_cari">Cari Pasien</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="search-form search-form--light input-group-lg col-md-10">
                                <input type="text" class="form-control" placeholder="Nama / NIK / No. RM" id="txt_cari">
                            </div>
                            <div class="col-md-12" hidden id="pencarian-notif" style="color: red; font-size: 0.8rem;">
                                Mohon ketikkan kata kunci pencarian
                            </div>
                            <div class="col-md-2">
                                <div class="loader loader-lg loader-primary" id="loader-search" hidden></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- style="height: 100px; overflow: scroll;" -->
                    <div class="col-md-12">
                        <!--table class="table table-bordered table-striped largeDataType" id="table-list-pencarian">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th class="wrap_content">Jenis Kelamin</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table-->
                        <table class="table table-bordered table-striped largeDataType" id="table-pencarian-pasien">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th class="wrap_content">No. RM</th>
                                    <th class="wrap_content">NIK</th>
                                    <th>Nama</th>
                                    <th class="wrap_content">Jenis Kelamin</th>
                                    <th class="wrap_content">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <!-- <div id="spanBtnTambahPasien" hidden> -->
                <a href="<?= __HOSTNAME__ ?>/pasien/tambah?antrian=true" class="btn btn-success" id="btnTambahPasien">
                    Tambah Pasien Baru
                </a>
                <!--<button class="btn btn-success" id="btnTambahPasien">Tambah Pasien Baru</button>-->
                <!-- <i class="fa fa-plus"></i>  -->
                <!-- </div> -->

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div id="modal-sep-new" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta
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

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-6">
                                        <div class="col-12 form-group">
                                            <label for="">Nomor Medical Rahecord (MR)</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                        </div>

                                        <div class="col-12 form-group">
                                            <label for="">Tanggal SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Faskes</label>
                                            <select class="form-control sep" id="txt_bpjs_faskes" disabled>
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD PETALA BUMI - KOTA PEKAN BARU</option>
                                            </select>
                                        </div>

                                        <div class="col-12 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="2">Rawat Jalan</option>
                                                <option value="1">Rawat Inap</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                            <label for="">Kelas Rawat Hak</label>
                                            <select class="form-control" id="txt_bpjs_kelas_rawat">
                                                <option value="1">Kelas 1</option>
                                                <option value="2">Kelas 2</option>
                                                <option value="3">Kelas 3</option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Kelas Rawat Naik</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio_kelas_rawat_naik" value="0" checked />
                                                        <label class="form-check-label">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio_kelas_rawat_naik" value="1" />
                                                        <label class="form-check-label">
                                                            Ya
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kelas_rawat_naik_container">
                                            <div class="col-12 mb-9 form-group" id="group_kelas_rawat_naik">
                                                <label for="">Kelas Rawat Naik</label>
                                                <select class="form-control" id="txt_bpjs_kelas_rawat_naik">
                                                    <option value="" selected disabled></option>
                                                    <option value="1">VVIP</option>
                                                    <option value="2">VIP</option>
                                                    <option value="3">Kelas 1</option>
                                                    <option value="4">Kelas 2</option>
                                                    <option value="5">Kelas 3</option>
                                                    <option value="6">ICCU</option>
                                                    <option value="7">ICU</option>
                                                    <option value="8">Diatas Kelas 1</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-9 form-group" id="group_kelas_rawat_naik">
                                                <label for="">Pembiayaan</label>
                                                <select class="form-control" id="txt_bpjs_kelas_rawat_naik_pembiayaan">
                                                    <option value="" selected disabled></option>
                                                    <option value="1">Pribadi</option>
                                                    <option value="2">Pemberi Kerja</option>
                                                    <option value="3">Asuransi Kesehatan Tambahan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6" id="panel-rujukan">
                                        <div class="col-12 form-group">
                                            <label for="">Jenis Rujukan</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio_tipe_rujukan" value="0" checked />
                                                        <label class="form-check-label">
                                                            Rujukan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio_tipe_rujukan" value="1" />
                                                        <label class="form-check-label">
                                                            Rujukan Manual/IGD
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="panel_rujukan_online">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-4 form-group">
                                                        <label for="">Asal Rujukan</label>
                                                        <select class="form-control uppercase sep" id="txt_bpjs_jenis_asal_rujukan">
                                                            <option value="1">Fakses Tingkat 1</option>
                                                            <option value="2">Rumah Sakit</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-8 form-group" id="group_nomor_rujukan">
                                                        <label for="">Nomor Rujukan</label>
                                                        <!-- <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor_rujukan"> -->
                                                        <select data-width="100%" class="form-control uppercase" id="txt_bpjs_nomor_rujukan"></select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 form-group">
                                                <label for="">PPK Asal Rujukan</label>
                                                <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                            </div>
                                            <div class="col-12 mb-4 form-group">
                                                <label for="">Tanggal Rujukan</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan" readonly>
                                            </div>

                                            <div class="col-12 form-group">
                                                <div class="alert alert-info">
                                                    <div class="informasi_rujukan">
                                                        <table class="table form-mode">
                                                            <tr>
                                                                <td>Poli</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_poli"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Diagnosa</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_diagnosa"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Keluhan</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_keluhan"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Hak Kelas</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_hak_kelas"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jenis Peserta</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_jenis_peserta"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="panel_rujukan_manualigd">
                                            <div class="col-12 form-group" id="group_nomor_rujukan">
                                                <label for="">Nomor</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor_rujukan_manualigd">
                                            </div>

                                            <div class="col-12 form-group">
                                                <label for="">Asal Rujukan</label>
                                                <select class="form-control uppercase" id="txt_bpjs_jenis_asal_rujukan_manualigd">
                                                    <option value="1">Fakses Tingkat 1</option>
                                                    <option value="2">Rumah Sakit</option>
                                                </select>
                                            </div>
                                            <div class="col-12 form-group" id="col_group_asal_rujukan_manualigd">
                                                <label for="">PPK Asal Rujukan</label>
                                                <select data-width="100%" class="form-control uppercase" id="txt_bpjs_asal_rujukan_manualigd"></select>
                                            </div>
                                            <div class="col-12 mb-4 form-group">
                                                <label for="">Tanggal Rujukan</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan_manualigd">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Perobatan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="col-12 form-group" id="group_poli">
                                                <label for="">Poli Tujuan</label>
                                                <select class="form-control" id="txt_bpjs_poli_tujuan"></select>
                                            </div>
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Eksekutif</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="0" checked />
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-4 form-group">
                                                        <label for="">Tujuan Kunjungan</label>
                                                        <select class="form-control uppercase sep" id="txt_bpjs_tujuan_kunjungan">
                                                            <option value="0">Normal</option>
                                                            <option value="1">Prosedur</option>
                                                            <option value="2">Konsul Dokter</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-8 form-group group_txt_bpjs_flag_procedure">
                                                        <label for="">Flag Prosedur</label>
                                                        <select class="form-control uppercase sep" id="txt_bpjs_flag_procedure">
                                                            <option value=""></option>
                                                            <option value="0">Prosedur Tidak Berkelanjutan</option>
                                                            <option value="1">Prosedur dan Terapi Berkelanjutan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8 mb-8 form-group group_txt_bpjs_kode_penunjang">
                                                <label for="">Kode Penunjang</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_kode_penunjang">
                                                    <option value=""></option>
                                                    <option value="1">Radioterapi</option>
                                                    <option value="2">Kemoterapi</option>
                                                    <option value="3">Rehabilitasi Medik</option>
                                                    <option value="4">Rehabilitasi Psikososial</option>
                                                    <option value="5">Transfusi Darah</option>
                                                    <option value="6">Pelayanan Gigi</option>
                                                    <option value="7">Laboratorium</option>
                                                    <option value="8">USG</option>
                                                    <option value="9">Farmasi</option>
                                                    <option value="11">MRI</option>
                                                    <option value="12">Hemodialisa</option>
                                                    <option value="10">Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-12 mb-12 form-group group_txt_bpjs_asesmen_pelayanan">
                                                <label for="">Asesmen Pelayanan</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_asesmen_pelayanan">
                                                    <option value=""></option>
                                                    <option value="1">Poli Spesialis tidak tersedia pada hari sebelumnya</option>
                                                    <option value="2">Jam Poli telah berakhir pada hari sebelumnya</option>
                                                    <option value="3">Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya</option>
                                                    <option value="4">Atas Instruksi RS</option>
                                                    <option value="5">Tujuan Kontrol</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                <label for="">Diagnosa Awal</label>
                                                <select class="form-control sep" id="txt_bpjs_diagnosa_awal"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Catatan</label>
                                                <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_catatan" style="min-height: 200px"></textarea>
                                            </div>
                                            <div class="col-12 mb-4 form-group">
                                                <label for="">No. Surat Kontrol/SKDP</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_skdp" />
                                            </div>
                                            <div class="col-12 mb-8 form-group">
                                                <div class="row">
                                                    <div class="col-6 form-group" id="group_spesialistik">
                                                        <label for="">Spesialis/Subspesialis DPJP</label>
                                                        <select class="form-control" id="txt_bpjs_dpjp_spesialistik"></select>
                                                    </div>
                                                    <div class="col-6 form-group" id="group_dpjp">
                                                        <label for="">DPJP</label>
                                                        <select class="form-control" id="txt_bpjs_dpjp"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">COB</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="0" checked />
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Katarak</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="0" checked />
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="alert alert-info">
                                                <div class="col-12 col-md-8 mb-4 form-group">
                                                    <b for="">Poli Tujuan</b>
                                                    <blockquote style="padding-left: 25px;">
                                                        <h6 id="txt_bpjs_internal_poli"></h6>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Kerja</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdk"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_dk"></p>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Banding</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdb"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_db"></p>
                                                    </blockquote>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 mb-2 form-groupn">
                                                <label for="">Jaminan Laka Lantas</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_laka">
                                                    <option value="0">Bukan Kecelakaan lalu lintas [BKLL]</option>
                                                    <option value="1">KLL dan bukan kecelakaan Kerja [BKK]</option>
                                                    <option value="2">KLL dan KK</option>
                                                    <option value="3">KK</option>
                                                </select>
                                            </div>
                                            <div class="laka_lantas_container">
                                                <div class="col-12 mb-4 form-group">
                                                    <label for="">No. LP</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_no_lp">
                                                </div>
                                                <div class="col-12 mb-4 form-group">
                                                    <label for="">Tanggal Kejadian</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_tanggal">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="">Keterangan</label>
                                                    <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_laka_keterangan" style="min-height: 200px"></textarea>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="">Suplesi</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="0" checked />
                                                                <label class="form-check-label">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="1" />
                                                                <label class="form-check-label">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="laka_lantas_suplesi_container">
                                                    <div class="col-12 mb-4 form-group">
                                                        <label for="">Nomor SEP Suplesi</label>
                                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_suplesi_nomor" />
                                                    </div>
                                                    <div class="col-12 mb-4 form-group" id="group_provinsi">
                                                        <label for="">Provinsi Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_provinsi"></select>
                                                    </div>
                                                    <div class="col-12 mb-4 form-group" id="group_kabupaten">
                                                        <label for="">Kabupaten Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kabupaten"></select>
                                                    </div>
                                                    <div class="col-12 mb-4 form-group" id="group_kecamatan">
                                                        <label for="">Kecamatan Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kecamatan"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="modal-tambah-igd" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Tambah Pasien IGD
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p><strong class="headings-color">Informasi Pasien</strong></p>
                            <p class="text-muted">Mohon pastikan informasi pasien cocok</p>
                        </div>
                        <div class="col-lg-8 card-form__body card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Nama Pasien</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nama" disabled required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Jenis Kelamin</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="nama_jenkel" disabled required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Nomor Rekam Medis</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="no_rm" disabled required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="tanggal_lahir" disabled required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p><strong class="headings-color">Detail Kunjungan</strong></p>
                            <p class="text-muted">Mohon masukkan data dengan benar<br>* Wajib diisi</p>
                        </div>
                        <div class="col-lg-8 card-form__body card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="penjamin" class="form-control select2 inputan" required>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Prioritas <span class="red">*</span></label>
                                    <select id="prioritas" class="form-control select2 inputan" required>
                                        <option value="" disabled selected>Pilih Prioritas</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="dokter" class="form-control select2 inputan" required>
                                        <option value="" disabled selected>Pilih Dokter</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Penanggung Jawab Pasien <span class="red">*</span></label>
                                    <input type="" name="pj_pasien" id="pj_pasien" maxlength="100" class="form-control inputan" required value="">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Informasi didapat dari <span class="red">*</span></label>
                                    <input type="" name="info_didapat_dari" id="info_didapat_dari" maxlength="100" class="form-control inputan" required value="">
                                </div>
                                <div class="col-lg-8 card-form__body card-body">
                                    <div class="form-row">
                                        <button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
                                        &nbsp;
                                        <a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/resepsionis" class="btn btn-danger">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Tambah
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div id="modal-sep-offline" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /><br /><span>Surat Eligibilitas Peserta <b class="text-danger">[OFFLINE]</b></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-12">
                        <label>Nomor SEP</label>
                        <input type="text" class="form-control" id="txt_bpjs_offline_sep" placeholder="Nomor SEP dari Aplikasi VClaim" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnSEPOffline">
                    <i class="fa fa-check-circle"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>


<div id="modal-sep-cetak" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-2">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Surat Eligibilitas Peserta</span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-5 offset-sm-2" id="data_sep_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;">No. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_tanggal"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor_kartu"></td>
                            </tr>
                            <tr>
                                <td>Nama Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nama_peserta"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_tanggal_lahir"></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor_telepon"></td>
                            </tr>
                            <tr>
                                <td>Sub/Spesialis</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_spesialis"></td>
                            </tr>
                            <tr>
                                <td>Perujuk</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_perujuk"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa Awal</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_diagnosa_awal"></td>
                            </tr>
                            <tr>
                                <td>Catatan</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_catatan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_sep_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;padding-bottom:10px;">Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_peserta"></td>
                            </tr>
                            <tr>
                                <td>Jns. Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_jenis_rawat"></td>
                            </tr>
                            <tr>
                                <td>Jns. Kunjungan</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_jenis_kunjungan"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="wrap_content">:</td>
                                <td id="sep_procedure"></td>
                            </tr>
                            <tr>
                                <td>Kls. Hak</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_kelas_hak"></td>
                            </tr>
                            <tr>
                                <td>Kls. Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_kelas_rawat"></td>
                            </tr>
                            <tr>
                                <td>Penjamin</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_penjamin"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnCetakSEP">
                    <i class="fa fa-print"></i> Cetak
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>