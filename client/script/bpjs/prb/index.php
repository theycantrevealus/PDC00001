<script type="text/javascript">
    $(function() {
        var selectedKartu = "";
        var refreshData = 'N';
        var MODE = "ADD";

        var mode_search = "ADD";

        $("#tglawal_prb").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#tglakhir_prb").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/caritglprb?tglawal=" + $("#tglawal_prb").val() + "&tglakhir=" + $("#tglakhir_prb").val();
        // var getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/caritglprb?tglawal=2023-08-01&tglakhir=2023-08-31";

        $("#btn_search_tgl_prb").click(function() {
            $('#alert-prb-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/caritglprb?tglawal=" + $("#tglawal_prb").val() + "&tglakhir=" + $("#tglakhir_prb").val();
            MODE = "TGL_SRB";
            mode_search = "SEARCH";
            DataPRB.ajax.url(getUrl).load();
        });

        $("#btn_search_no_srb").click(function() {
            $('#alert-prb-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/cariprb?nosrb=" + $("#text_search_no_srb").val() + "&nosep=" + $("#text_search_no_sep").val();
            MODE = "NO_SRB";
            mode_search = "SEARCH";
            DataPRB.ajax.url(getUrl).load();
        });

        $('#alert-prb-container').hide();

        var DataPRB = $("#table-prb").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "GET",
            "ajax": {
                url: getUrl,
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
                dataSrc: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        if (mode_search === "SEARCH") {
                            $('#alert-prb').text(response.metadata.message);
                            $('#alert-prb-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-prb-container').fadeOut();
                        if (MODE === "TGL_SRB") {
                            return response.response.prb.list;
                        } else if (MODE === "NO_SRB") {
                            return response.response.prb;
                        }
                    }
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [
                [-1],
                ["All"]
            ],
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
                        return row.tglSRB;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noSRB;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noSEP;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.peserta.nama + " - " + row.peserta.noKartu;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.programPRB.kode + " - " + row.programPRB.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.DPJP.kode + " - " + row.DPJP.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +

                            "<button class=\"btn btn-info btn-sm btnEditPRB\" no-sep=\"" + row.noSEP + "\" id=\"" + row.noSRB + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusPRB\" no-sep=\"" + row.noSEP + "\" id=\"" + row.noSRB + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnHapusPRB", function() {
            var no_sep = $(this).attr("no-sep");
            var no_SRB = $(this).attr("id");
            Swal.fire({
                title: "BPJS PRB",
                text: "Hapus PRB " + no_SRB + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${__BPJS_SERVICE_URL__}prb/sync.sh/deleteprb`,
                        type: "DELETE",
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
                        data: {
                            "t_prb": {
                                "noSrb": no_SRB,
                                "noSep": no_sep,
                                "user": __MY_NAME__
                            }
                        },
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS PRB',
                                    'PRB Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    DataPRB.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS PRB',
                                    response.metaData.message,
                                    'error'
                                ).then((result) => {
                                    DataPRB.ajax.reload();
                                });
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

        $("body").on("click", ".btnEditPRB", function() {
            var no_SRB = $(this).attr("id");
            var no_sep = $(this).attr("no-sep");
            MODE = "EDIT";

            // $("#modal-prb").modal("show");

            //Load Detail
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}prb/sync.sh/cariprb?nosrb=${no_SRB}&nosep=${no_sep}`,
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
                    var data = response.response[0];

                    $("#txt_bpjs_prb_sep").val(data.noSEP);
                    $("#txt_bpjs_prb_noSRB").val(data.noSRB);
                    $("#txt_bpjs_prb_nokartu").val(data.peserta.noKartu);
                    $("#txt_bpjs_prb_email").val(data.peserta.email);
                    $("#txt_bpjs_prb_alamat").val(data.alamat);
                    $("#txt_bpjs_prb_program").val(data.programPRB.kode);
                    $("#txt_bpjs_prb_dpjp").val(data.DPJP.kode);
                    $("#txt_bpjs_prb_keterangan").val(data.keterangan);
                    $("#txt_bpjs_prb_saran").val(data.saran);

                    $("#modal-prb").modal("show");

                },
                error: function(response) {
                    console.log(response);
                }
            });

        });

        $("#txt_bpjs_prb_sep").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-prb'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisep`,
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
                        nomorsep: term.term
                    };
                },
                cache: true,
                processResults: function(response) {
                    var data = response.response;
                    console.log(data);
                    return {
                        results: $.map(data, function() {
                            return {
                                text: data.noSep + "-" + data.peserta.nama,
                                id: data.noSep,
                                tglSep: data.tglSep,
                                noKartu: data.peserta.noKartu
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_bpjs_prb_tgl_sep").val((data.tglSep !== undefined && data.tglSep !== null) ? data.tglSep : "-");
            $("#txt_bpjs_prb_nokartu").val((data.noKartu !== undefined && data.noKartu !== null) ? data.noKartu : "-");
            loadDPJPSpesialis();
        });

        $("#txt_bpjs_prb_program").select2({
            dropdownParent: $('#modal-prb')
        });

        $("#txt_bpjs_prb_jenis_layan_dpjp").select2({
            dropdownParent: $('#modal-prb')
        }).on("select2:select", function(e) {
            loadDPJPSpesialis();
        });

        $("#txt_bpjs_prb_spesialistik_dpjp").select2({
            dropdownParent: $('#modal-prb')
        }).on("select2:select", function(e) {
            loadDPJPSpesialis();
        });

        $("#txt_bpjs_prb_dpjp").select2({
            "language": {
                "noResults": function() {
                    return "DPJP tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-prb')
        });

        function loadDPJPSpesialis() {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getdokter?jnspelayanan=${$("#txt_bpjs_prb_jenis_layan_dpjp option:selected").val()}&tglpelayanan=${$("#txt_bpjs_prb_tgl_sep").val()}&kode=${$("#txt_bpjs_prb_spesialistik_dpjp option:selected").val()}`,
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
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_prb_dpjp").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kode,
                                text: `${data[a].kode} - ${data[a].nama}`
                            })
                        }
                        $("#txt_bpjs_prb_dpjp").select2({
                            data: parsedData
                        });
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        loadSpesialistik();

        function loadSpesialistik(target) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getspesialis`,
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
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_prb_spesialistik_dpjp").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kode,
                                text: `${data[a].kode} - ${data[a].nama}`
                            })
                        }
                        $("#txt_bpjs_prb_spesialistik_dpjp").select2({
                            data: parsedData
                        });
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        loadProgramPRB();

        function loadProgramPRB() {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getdiagnosaprb`,
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
                success: function(response) {
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_prb_program").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kode,
                                text: `${data[a].kode} - ${data[a].nama}`
                            })
                        }
                        $("#txt_bpjs_prb_program").select2({
                            data: parsedData
                        });
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#btnTambahPRB").click(function() {
            var MODE = "ADD";
            $("#modal-prb").modal("show");
            $("#txt_bpjs_prb_sep").focus();
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
                    url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getobatprb`,
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
                            namaobat: term.term
                        };
                    },
                    processResults: function(response) {
                        if (response.response !== undefined) {
                            var data = response.response;
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama + " - " + item.kode,
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

            if (
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
            // var pasien = $("#txt_bpjs_prb_pasien option:selected").val();

            var sep = $("#txt_bpjs_prb_sep").val();
            var kartu = $("#txt_bpjs_prb_nokartu").val();
            var email = $("#txt_bpjs_prb_email").val();
            var alamat = $("#txt_bpjs_prb_alamat").val();
            var programPRB = $("#txt_bpjs_prb_program option:selected").val();
            var kodeDPJP = $("#txt_bpjs_prb_dpjp option:selected").val();
            var keterangan = $("#txt_bpjs_prb_keterangan").val();
            var saran = $("#txt_bpjs_prb_saran").val();
            var obatList = [];
            var obatInt = [];

            $("#autoObatPRB tbody tr").each(function(e) {
                var obat = $(this).find("td:eq(1) select option:selected").val();
                var namaObat = $(this).find("td:eq(1) select option:selected").text();
                var signa1 = $(this).find("td:eq(2) input").val();
                var signa2 = $(this).find("td:eq(3) input").val();
                var jumlah = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");

                if (
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

            if (MODE === "ADD") {
                Swal.fire({
                    title: "BPJS PRB",
                    text: "Buat PRB baru?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `${__BPJS_SERVICE_URL__}prb/sync.sh/insertprb`,
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
                            data: {
                                "t_prb": {
                                    "noSep": sep,
                                    "noKartu": kartu,
                                    "alamat": alamat,
                                    "email": email,
                                    "programPRB": programPRB,
                                    "kodeDPJP": kodeDPJP,
                                    "keterangan": keterangan,
                                    "saran": saran,
                                    "user": __MY_NAME__,
                                    "obat": obatList
                                }
                            },
                            success: function(response) {
                                console.clear();
                                console.log(response);
                                if (parseInt(response.metadata.code) === 200) {
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
                                        response.metadata.message,
                                        "warning"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function(response) {
                                Swal.fire(
                                    "PRB",
                                    'Aksi Gagal',
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "BPJS PRB",
                    text: "Buat PRB edit?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `${__BPJS_SERVICE_URL__}prb/sync.sh/updateprb`,
                            type: "PUT",
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
                            data: {
                                "t_prb": {
                                    "noSrb": "9118924",
                                    "noSep": sep,
                                    "alamat": alamat,
                                    "email": email,
                                    "kodeDPJP": kodeDPJP,
                                    "keterangan": keterangan,
                                    "saran": saran,
                                    "user": __MY_NAME__,
                                    "obat": obatList
                                }
                            },
                            success: function(response) {
                                console.clear();
                                console.log(response);
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "BPJS PRB",
                                        "PRB Berhasil diedit!",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-prb").modal("hide");
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal edit PRB",
                                        response.metaData.message,
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
            }

        });
    });
</script>




<div id="modal-prb" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> PRB <code>Baru</code>
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
                                <!-- <div class="col-8 form-group">
                                    <label for="">Pasien</label>
                                    <select id="txt_bpjs_prb_pasien" class="form-control uppercase"></select>
                                </div> -->
                                <div class="col-3 form-group">
                                    <label for="">SEP</label>
                                    <!-- <input type="text" id="txt_bpjs_prb_sep" class="form-control uppercase"> -->
                                    <select id="txt_bpjs_prb_sep" class="form-control uppercase"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Tgl. SEP</label>
                                    <input type="text" id="txt_bpjs_prb_tgl_sep" class="form-control uppercase" readonly>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">No. Kartu</label>
                                    <input type="text" id="txt_bpjs_prb_nokartu" autocomplete="off" class="form-control uppercase" readonly />
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Email</label>
                                    <input type="email" autocomplete="off" class="form-control" id="txt_bpjs_prb_email" />
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Alamat</label>
                                    <textarea class="form-control" id="txt_bpjs_prb_alamat"></textarea>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Program PRB</label>
                                    <select class="form-control uppercase" id="txt_bpjs_prb_program"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control uppercase" id="txt_bpjs_prb_jenis_layan_dpjp">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Spesialistik</label>
                                    <select class="form-control uppercase" id="txt_bpjs_prb_spesialistik_dpjp"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">DPJP</label>
                                    <select class="form-control uppercase" id="txt_bpjs_prb_dpjp"></select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Keterangan</label>
                                    <textarea class="form-control" id="txt_bpjs_prb_keterangan"></textarea>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Saran</label>
                                    <textarea class="form-control" id="txt_bpjs_prb_saran"></textarea>
                                </div>
                                <div class="col-12">
                                    <table class="table largeDataType" id="autoObatPRB">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content text-center">No</th>
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
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>