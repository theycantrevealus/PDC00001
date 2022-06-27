<script type="text/javascript">
    $(function () {

        var clickedTab = [1];
        var selectedPropinsi = 0;
        var selectedKabupaten = 0;
        var DIAGNOSA, POLI, FASKES, DPJP, PROCEDURE, PROVINSI, KABUPATEN, KECAMATAN, DOKTER, SPESIALISTIK, RUANG_RAWAT, CARA_KELUAR, PASCA_PULANG;
        var allowLoading = false;

        //Init
        DIAGNOSA = $("#bpjs_table_diagnosa").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            //searchDelay: 3000,
            serverMethod: "POST",
            initComplete: function()  {
                $("#bpjs_table_diagnosa_filter input").unbind().bind("keyup", function(e) {
                    if(e.keyCode == 13) {
                        if(this.value.length > 2 || this.value.length == 0) {
                            DIAGNOSA.search(this.value).draw();
                        }
                    }

                    return;
                });
            },
            "ajax": {
                async: false,
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function (d) {
                    d.request = "get_referensi_diagnosa";
                },
                dataSrc: function (response) {
                    if(response !== null && response.response_package !== null) {
                        
                        allowLoading = true;
                        $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                        var data = response.response_package.data;

                        response.draw = parseInt(response.response_package.response_draw);
                        response.recordsTotal = response.response_package.recordsTotal;
                        response.recordsFiltered = response.response_package.recordsFiltered;

                        return data;
                    } else {
                        return [];
                    }
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                    return [];
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [[10, 50, -1], [10, 50, "All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.kode;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.nama;
                    }
                }
            ]
        });

        $("#tab-referensi-bpjs .nav-link").click(function(e) {
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            if(allowLoading) {
                var child = $(this).get(0).hash.split("-");
                child = parseInt(child[child.length - 1]);
                if(child === 1) {
                    if(clickedTab.indexOf(child) >= 0) {
                        DIAGNOSA.ajax.reload();
                    }
                } else if(child === 2) {
                    if(clickedTab.indexOf(child) >= 0) {
                        POLI.ajax.reload();
                    } else {
                        clickedTab.push(2);
                        POLI = $("#bpjs_table_poli").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_poli_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            POLI.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_poli";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[10, 50, -1], [10, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 3) {
                    if (clickedTab.indexOf(child) >= 0) {
                        FASKES.ajax.reload();
                    } else {
                        clickedTab.push(3);
                        FASKES = $("#bpjs_table_fakses").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_fakses_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            FASKES.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_faskes";
                                    d.jenis = $("#bpjs_jenis_fakses").val();
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[10, 50, -1], [10, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 4) {
                    if (clickedTab.indexOf(child) >= 0) {
                        DPJP.ajax.reload();
                    } else {
                        clickedTab.push(4);
                        DPJP = $("#bpjs_table_dpjp").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_dpjp_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            DPJP.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_dpjp";
                                    d.jenis = $("#bpjs_jenis_fakses_dpjp").val();
                                    d.from = getDateRange("#range_dpjp")[0];
                                    d.to = getDateRange("#range_dpjp")[1];
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[10, 50, -1], [10, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 5) {
                    if (clickedTab.indexOf(child) >= 0) {
                        PROVINSI.ajax.reload();
                    } else {
                        clickedTab.push(5);
                        PROVINSI = $("#bpjs_table_provinsi").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_provinsi_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            PROVINSI.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_provinsi";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<button class=\"btn btn-info btn-sm btn_propinsi\" id=\"propinsi_" + row.kode + "\"><i class=\"fa fa-eye\"></i></button>";
                                    }
                                }
                            ]
                        });



                        KABUPATEN = $("#bpjs_table_kabupaten").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_kabupaten_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            KABUPATEN.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_kabupaten";
                                    d.propinsi = selectedPropinsi;
                                },
                                dataSrc: function (response) {
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<button class=\"btn btn-info btn-sm btn_kabupaten\" id=\"kabupaten_" + row.kode + "\"><i class=\"fa fa-eye\"></i></button>";
                                    }
                                }
                            ]
                        });

                        KECAMATAN = $("#bpjs_table_kecamatan").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_kecamatan_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            KECAMATAN.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_kecamatan";
                                    d.kabupaten = selectedKabupaten;
                                },
                                dataSrc: function (response) {
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 6) {
                    if (clickedTab.indexOf(child) >= 0) {
                        PROCEDURE.ajax.reload();
                    } else {
                        clickedTab.push(6);
                        PROCEDURE = $("#bpjs_table_procedure").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_procedure_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            PROCEDURE.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_procedure";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.kode + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 7) {
                    if (clickedTab.indexOf(child) >= 0) {
                        KELAS_RAWAT.ajax.reload();
                    } else {
                        clickedTab.push(7);
                        KELAS_RAWAT = $("#bpjs_table_kelas_rawat").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_kelas_rawat_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            KELAS_RAWAT.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_kelas_rawat";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.kode + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 8) {
                    if (clickedTab.indexOf(child) >= 0) {
                        DOKTER.ajax.reload();
                    } else {
                        clickedTab.push(8);
                        DOKTER = $("#bpjs_table_dokter").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_dokter_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            DOKTER.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_dokter";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 9) {
                    if (clickedTab.indexOf(child) >= 0) {
                        SPESIALISTIK.ajax.reload();
                    } else {
                        clickedTab.push(9);
                        SPESIALISTIK = $("#bpjs_table_spesialistik").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_spesialistik_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            SPESIALISTIK.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_spesialistik";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 10) {
                    if (clickedTab.indexOf(child) >= 0) {
                        RUANG_RAWAT.ajax.reload();
                    } else {
                        clickedTab.push(10);
                        RUANG_RAWAT = $("#bpjs_table_ruang_rawat").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_ruang_rawat_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            RUANG_RAWAT.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_ruang_rawat";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 11) {
                    if (clickedTab.indexOf(child) >= 0) {
                        CARA_KELUAR.ajax.reload();
                    } else {
                        clickedTab.push(11);
                        CARA_KELUAR = $("#bpjs_table_cara_keluar").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_cara_keluar_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            CARA_KELUAR.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_cara_keluar";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                },
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else if(child === 12) {
                    if (clickedTab.indexOf(child) >= 0) {
                        PASCA_PULANG.ajax.reload();
                    } else {
                        clickedTab.push(12);
                        PASCA_PULANG = $("#bpjs_table_pasca_pulang").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function()  {
                                $("#bpjs_table_pasca_pulang_filter input").unbind().bind("keyup", function(e) {
                                    if(e.keyCode == 13) {
                                        if(this.value.length > 2 || this.value.length == 0) {
                                            PASCA_PULANG.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __HOSTAPI__ + "/BPJS",
                                type: "POST",
                                headers: {
                                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                                },
                                data: function (d) {
                                    d.request = "get_referensi_pasca_pulang";
                                },
                                dataSrc: function (response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response_package.data;

                                    response.draw = parseInt(response.response_package.response_draw);
                                    response.recordsTotal = response.response_package.recordsTotal;
                                    response.recordsFiltered = response.response_package.recordsFiltered;

                                    return data;
                                }
                            },
                            autoWidth: false,
                            "bInfo": false,
                            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                            aaSorting: [[0, "asc"]],
                            "columnDefs": [{
                                "targets": 0,
                                "className": "dt-body-left"
                            }],
                            "columns": [
                                {
                                    "data": null, render: function (data, type, row, meta) {
                                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                                    }
                                }, {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.kode;
                                    }
                                }, {
                                    "data": null, render: function (data, type, row, meta) {
                                        return row.nama;
                                    }
                                }
                            ]
                        });
                    }
                } else {

                }
            } else {
                return false;
            }
        });

















        $("#bpjs_jenis_fakses").select2().on("select2:select", function(e) {
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            FASKES.ajax.reload();
        });

        $("#range_dpjp").change(function () {
            if(
                !Array.isArray(getDateRange("#range_dpjp")[0]) &&
                !Array.isArray(getDateRange("#range_dpjp")[1])
            ) {
                $("#tab-referensi-bpjs .nav-link").addClass("disabled");
                DPJP.ajax.reload();
            }
        });

        $("#bpjs_jenis_fakses_dpjp").select2().on("select2:select", function(e) {
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            DPJP.ajax.reload();
        });

        $("body").on("click", ".btn_propinsi", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            selectedPropinsi = id;
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            KABUPATEN.ajax.reload();
        });

        $("body").on("click", ".btn_kabupaten", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            selectedKabupaten = id;
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            KECAMATAN.ajax.reload();
        });



    });
</script>