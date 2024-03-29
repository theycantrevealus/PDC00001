<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var selectedKunjungan = "", selectedPenjamin = "", selected_waktu_masuk = "";

        /*
        *
        * 4 - 7 PAGES
        *
        * */
        $.ajax({
            url: __HOSTAPI__ + "/Invoice/biaya_pasien_total/" + __PAGES__[3],
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                var data = [];
                if(response.response_package.response_data !== undefined && response.response_package.response_data !== null) {
                    data = response.response_package.response_data;
                }
                var filtered = [];
                for(var a in data) {
                    for(var b in data[a].detail) {
                        if(data[a].detail[b].departemen === __POLI_INAP__) {
                            filtered.push({
                                invoice: data[a].nomor_invoice,
                                item: data[a].detail[b].item.nama,
                                qty: data[a].detail[b].qty,
                                harga: data[a].detail[b].harga,
                                subtotal: data[a].detail[b].subtotal,
                                keterangan: data[a].detail[b].keterangan
                            });
                        }
                    }
                }

                var autonum = 1;
                for(var a in filtered) {
                    var newRow = document.createElement("TR");
                    var newNo = document.createElement("TD");
                    var newItem = document.createElement("TD");
                    var newJlh = document.createElement("TD");
                    var newHarga = document.createElement("TD");
                    var newSub = document.createElement("TD");

                    $(newNo).html(autonum);
                    $(newItem).html("<span class=\"badge badge-info badge-custom-caption\">" + filtered[a].invoice + "</span><b style=\"padding-left: 20px;\">" + filtered[a].item + "</b><p>" + filtered[a].keterangan + "</p>");
                    $(newJlh).html(filtered[a].qty).addClass("number_style");
                    $(newHarga).html(filtered[a].harga).addClass("number_style");
                    $(newSub).html(filtered[a].subtotal).addClass("number_style");

                    $(newRow).append(newNo);
                    $(newRow).append(newItem);
                    $(newRow).append(newJlh);
                    $(newRow).append(newHarga);
                    $(newRow).append(newSub);

                    $("#biaya_pasien tbody").append(newRow);
                    autonum++;
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        $.ajax({
            url: __HOSTAPI__ + "/Pasien/pasien-info/" + __PAGES__[3],
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success: function (response) {
                var filteredData = response.response_package.response_data;
                $("#target_pasien").html(filteredData[0].uid);
                $("#rm_pasien").html(filteredData[0].no_rm);
                $("#nama_pasien").html((filteredData[0].panggilan_name === null) ? filteredData[0].nama : filteredData[0].panggilan_name.nama + " " +  filteredData[0].nama);
                $("#jenkel_pasien").html(filteredData[0].jenkel_detail.nama);
                $("#tempat_lahir_pasien").html(filteredData[0].tempat_lahir);
                $("#alamat_pasien").html(filteredData[0].alamat);
                $("#usia_pasien").html(filteredData[0].usia);
                $("#tanggal_lahir_pasien").html(filteredData[0].tanggal_lahir_parsed);
            },
            error: function(response) {
                console.log(response);
            }
        });

        var tableRiwayatObat = $("#table-riwayat-obat-inap").DataTable({
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
                    d.request = "riwayat_obat_igd";
                    d.pasien = __PAGES__[3];
                    d.kunjungan = __PAGES__[4];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response.response_package === undefined || response.response_package.response_data === undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
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
                searchPlaceholder: "Cari Resep"
            },
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.logged_at + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.resep_kode === null) ? "<h6 class=\"text-center\">-</h6>" : "<span class=\"badge badge-info badge-custom-caption\">" + row.resep_kode + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.obat + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"number_style\">" + row.qty + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nama_petugas + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.keterangan;
                    }
                }
            ]
        });

        var tableResep = $("#table-resep-inap").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d) {
                    d.request = "resep_inap";
                    d.pasien = __PAGES__[3];
                    d.kunjungan = __PAGES__[4];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    var rawData = [];

                    if(response.response_package === undefined || response.response_package.response_data === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    for(var a in rawData) {
                        if(rawData[a].antrian_detail.departemen === __POLI_INAP__) {
                            returnedData.push(rawData[a]);
                        }
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
                searchPlaceholder: "Cari Resep"
            },
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parsed + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var detail = row.detail;
                        var parsedDetail = "<span class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Tidak ada resep</span>";
                        if(detail.length > 0) {
                            parsedDetail = "<div class=\"row\">";
                            for(var a in detail) {
                                if(detail[a].detail.nama !== "") {
                                    parsedDetail += "<div class=\"col-md-12\">" +
                                        "<span class=\"badge badge-info badge-custom-caption\"><i class=\"fa fa-tablets\"></i> " + detail[a].detail.nama + "</span><br />" +
                                        "<div style=\"padding-left: 20px;\">" + detail[a].signa_qty + " &times; " + detail[a].signa_pakai + " <label class=\"text-info\">[" + detail[a].qty + "]</label></div>" +
                                        "</div>";
                                }
                            }
                            parsedDetail += "</div>";
                        }

                        return parsedDetail;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var racikan = row.racikan;
                        var parsedDetail = "<span class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Tidak ada racikan</span>";
                        if(racikan.length > 0) {
                            parsedDetail = "<div class=\"row\">";
                            for(var a in racikan) {
                                var detailRacikan = racikan[a].detail;
                                parsedDetail += "<div class=\"col-md-12\">" +
                                    "<span class=\"badge badge-success badge-custom-caption\">" + racikan[a].kode + "</span><br />" +
                                    "<div style=\"padding-left: 20px;\">" + racikan[a].signa_qty + " &times; " + racikan[a].signa_pakai + " <label class=\"text-info\">[" + racikan[a].qty + "]</label></div>" +
                                    "<ol>";
                                for(var b in detailRacikan) {
                                    parsedDetail += "<span style=\"margin-bottom: 5px;\" class=\"badge badge-info badge-custom-caption\"><i class=\"fa fa-tablets\"></i> " + detailRacikan[b].detail.nama + "</span>";
                                }
                                parsedDetail += "</ul></div>";
                            }
                            parsedDetail += "</div>";
                        }
                        return parsedDetail;
                    }
                }
            ]
        });

        var tableAntrianRawat = $("#table-antrian-rawat-jalan-perawat").DataTable({
            "ajax":{
                url: __HOSTAPI__ + "/Asesmen",
                type: "POST",
                data: function(d) {
                    d.request = "get_antrian_asesmen_rawat_igd";
                    d.pasien = __PAGES__[3];
                },
                headers:{
                Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.log(response)
                    var filteredData = [];
                    var data = response.response_package.response_data;
                    return data;
                }
            },autoWidth: false,
                    "bInfo" : false,
                    aaSorting: [[0, "asc"]],
                    "columnDefs":[
                        {"targets":0, "className":"dt-body-left"}
                    ],
                    "columns" : [
                        {
                            "data" : null, render: function(data, type, row, meta) {
                                return row.autonum;
                            }
                        },
                        {
                            "data" : null, render: function(data, type, row, meta) {
                                return row.waktu_masuk;
                            }
                        },
                        {
                            "data" : null, render: function(data, type, row, meta) {

                            if(row.waktu_keluar === null || row.waktu_keluar === undefined){
                                return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                    "<a href=\"" + __HOSTNAME__ + "/igdv2/dokter/antrian/" + row.uid + "/" + row.uid_pasien + "/" + row.uid_kunjungan + "\" class=\"btn btn-success btn-sm\">" +
                                    "<span><i class=\"fa fa-sign-out-alt\"></i>Proses Perobatan</span>" +
                                    "</a>" +
                                    "</div>"
                            } else {
                                return "";
                            }
                            
                            }
                        }
                    ]
            });

        $("body").on("click", ".cppt_paginate_prev", function() {
            if(currentCPPTStep > 1) {
                currentCPPTStep -= 1;
                loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
            }
            return false;
        });

        $("body").on("click", ".cppt_paginate_next", function() {
            var total = $(".cppt_paginate").length;
            if(currentCPPTStep < total) {
                currentCPPTStep += 1;
                loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
            }
            return false;
        });

        $("body").on("click", ".cppt_paginate", function(e) {
            e.preventDefault();
            var tar = $(this).attr("target");
            currentCPPTStep = parseInt(tar);
            loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
            return false;
        });

        loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");

        $("#filter_date").change(function() {
            loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], currentCPPTStep, "");
        });

        /*var tableAntrian= $("#table-antrian-rawat-jalan").DataTable({
            "ajax":{
                url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-medis/igd",
                type: "GET",
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var filteredData = [];
                    var data = response.response_package.response_data;

                    var reautoNum = 1;
                    for(var a = 0; a < data.length; a++) {
                        if(
                            data[a].uid_pasien === __PAGES__[3] &&
                            data[a].uid_kunjungan === __PAGES__[4] &&
                            data[a].uid_poli === __POLI_IGD__
                        ) {
                            console.log(data[a]);
                            data[a].autonum = reautoNum;
                            filteredData.push(data[a]);
                            reautoNum++;
                        }
                    }

                    if(filteredData.length > 0) {
                        selectedKunjungan = filteredData[0].uid_kunjungan;
                        selectedPenjamin = filteredData[0].uid_penjamin;
                        selected_waktu_masuk = filteredData[0].waktu_masuk;
                        //console.log(filteredData[0].pasien_detail);
                        $("#target_pasien").html(filteredData[0].pasien);
                        $("#rm_pasien").html(filteredData[0].no_rm);
                        $("#nama_pasien").html((filteredData[0].pasien_detail.panggilan_name === null) ? filteredData[0].pasien_detail.nama : filteredData[0].pasien_detail.panggilan_name.nama + " " +  filteredData[0].pasien_detail.nama);
                        $("#jenkel_pasien").html(filteredData[0].pasien_detail.jenkel_detail.nama);
                        $("#tempat_lahir_pasien").html(filteredData[0].pasien_detail.tempat_lahir);
                        $("#alamat_pasien").html(filteredData[0].pasien_detail.alamat);
                        $("#usia_pasien").html(filteredData[0].pasien_detail.usia);
                        $("#tanggal_lahir_pasien").html(filteredData[0].pasien_detail.tanggal_lahir_parsed);
                    } else {
                        //Pasien Detail
                        $.ajax({
                            url: __HOSTAPI__ + "/Pasien/pasien-detail/" + __PAGES__[3],
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"GET",
                            success:function(response) {

                                var pasienData = response.response_package.response_data;
                                $("#target_pasien").html(pasienData[0].nama);
                                $("#rm_pasien").html(pasienData[0].no_rm);
                                $("#nama_pasien").html((pasienData[0].panggilan_name === null) ? pasienData[0].nama : pasienData[0].panggilan_name.nama + " " +  pasienData[0].nama);
                                $("#usia_pasien").html(pasienData[0].usia);
                                $("#jenkel_pasien").html(pasienData[0].jenkel_detail.nama);
                                $("#tanggal_lahir_pasien").html(pasienData[0].tanggal_lahir_parsed);
                                $("#tempat_lahir_pasien").html(pasienData[0].tempat_lahir);
                                $("#alamat_pasien").html(pasienData[0].alamat);

                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }

                    return filteredData;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.waktu_masuk + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.asesmen_detail !== undefined && row.asesmen_detail !== null) ? (row.asesmen_detail.keluhan_utama.substr(0, 150) + "...") : "Tidak ada subjective";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/igdv2/dokter/antrian/" + row.uid + "/" + row.uid_pasien + "/" + row.uid_kunjungan + "\" class=\"btn btn-success btn-sm\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });*/

        $("#btnInap").click(function() {
            loadPenjamin("inap", __PAGES__[5]);
            $("#form-inap").modal("show");
        });

        $("#btnTambahAsesmen").click(function() {
            $(this).attr({
                "disabled": "disabled"
            }).removeClass("btn-info").addClass("btn-warning").html("<i class=\"fa fa-sync\"></i> Menambahkan Asesmen");

            var formData = {
                request: "tambah_asesmen",
                penjamin: __PAGES__[5],
                kunjungan: __PAGES__[4],
                pasien: __PAGES__[3],
                poli: __POLI_IGD__
            };

            $.ajax({
                url: __HOSTAPI__ + "/IGD",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: formData,
                success:function(response) {
                    location.href = __HOSTNAME__ + "/igdv2/dokter/antrian/" + response.response_package.response_values[0] + "/" + __PAGES__[3] + "/" + __PAGES__[4] + "/" + __PAGES__[5] + "/" + __PAGES__[6];
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });


        $("body").on("click", ".lampiran_view_trigger", function() {
            var target = $(this).attr("target");
            $("#modal-lampiran-viewer").modal("show");

            var request = new XMLHttpRequest();
            request.open('GET', target, true);
            request.responseType = 'blob';
            request.onload = function() {
                var ctx = $("#pdfViewer")[0].getContext('2d');
                var img = new Image;
                img.src = target;
                img.onload = function() {
                    ctx.imageSmoothingEnabled = false;
                    ctx.canvas.width = img.width;
                    ctx.canvas.height = img.height;
                    ctx.drawImage(img, 0, 0, img.width, img.height);
                }
                var reader = new FileReader();
                reader.readAsDataURL(request.response);
                reader.onload =  function(e){
                    var fileReader = new FileReader();
                    fileReader.onload = function() {
                        var pdfData = new Uint8Array(this.result);
                        // Using DocumentInitParameters object to load binary data.
                        var loadingTask = pdfjsLib.getDocument({
                            data: pdfData
                        });
                        loadingTask.promise.then(function(pdf) {
                            // Fetch the first page
                            var pageNumber = 1;
                            pdf.getPage(pageNumber).then(function(page) {
                                var scale = 1.5;
                                var viewport = page.getViewport({
                                    scale: scale
                                });
                                // Prepare canvas using PDF page dimensions
                                var canvas = $("#pdfViewer")[0];
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                // Render PDF page into canvas context
                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                var renderTask = page.render(renderContext);
                                renderTask.promise.then(function() {
                                    //$("#btnSubmit").removeAttr("disabled").html("Terima SK").removeClass("btn-warning").addClass("btn-primary");
                                });
                            });
                        }, function(reason) {
                            // PDF loading error
                            console.error(reason);
                        });
                    };
                    //fileReader.readAsArrayBuffer(file);
                    fileReader.readAsArrayBuffer(request.response);
                };
            };
            request.send();

            return false;
        });



        $(".print_manager").click(function() {
            var targetSurat = $(this).attr("id");
            $("#target-judul-cetak").html("CETAK " + targetSurat.toUpperCase() + " PASIEN");
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/pasien_" + targetSurat + ".php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    pc_customer: __PC_CUSTOMER__,
                    no_rm:$("#rm_pasien").html(),
                    pasien: "An. " + $("#nama_pasien").html(),
                    tanggal_lahir: $("#tanggal_lahir_pasien").html(),
                    usia: $("#usia_pasien").html() + " tahun",
                    dokter: __MY_NAME__,
                    waktu_masuk: selected_waktu_masuk,
                    alamat: $("#alamat_pasien").html(),
                    tempat_lahir: $("#tempat_lahir_pasien").html()
                },
                success: function (response) {
                    //$("#dokumen-viewer").html(response);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "igd",
                        afterPrint: function() {
                            $("#cetak").modal("hide");
                            $("#dokumen-viewer").html("");
                        }
                    });
                }
            });
        });

        $("#inap_dokter").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Dokter tidak ditemukan";
                }
            },
            placeholder:"Cari Dokter",
            dropdownParent: $("#form-inap"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pegawai/get_all_dokter_select2",
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
                                text: item.nama_dokter,
                                id: item.uid
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;

        });

        $("#btnProsesInap").click(function() {
            Swal.fire({
                title: "Daftar untuk rawat inap?",
                text: "Arahkan pasien / keluarga pasien untuk menyelesaikan administrasi inap",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Inap",
                        type: "POST",
                        data: {
                            request: "tambah_inap",
                            pasien: __PAGES__[3],
                            penjamin: $("#inap_penjamin").val(),
                            dokter: $("#inap_dokter").val(),
                            kunjungan: __PAGES__[4],
                            keterangan: $("#inap_keterangan").val(),
                            asal: "igd",
                            poli_asal: __POLI_IGD__
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            if (response.response_package.response_result > 0) {
                                location.href = __HOSTNAME__ + "/igd";
                            } else {
                                console.log(response);
                            }
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        function loadPenjamin(target_ui, selected = "") {
            var dataPenjamin = null;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Penjamin/penjamin",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");
                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if(MetaData[i].uid === selected) {
                                $(selection).attr("selected", "selected");
                            }
                            $("#" + target_ui + "_penjamin").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPenjamin;
        }
    });
</script>


<div id="modal-lampiran-viewer" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index: 2048;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Lampiran Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas style="width: 100%; border: solid 1px #ccc;" id="pdfViewer"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>


<div id="form-inap" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pindah Rawat Inap</h5>
            </div>
            <div class="modal-body" id="inap-container">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="inap_penjamin" class="form-control select2 inputan_inap" required disabled>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="inap_dokter" class="form-control select2 inputan_inap"></select>
                                </div>
                                <!--div class="col-12 col-md-6 mb-3">
                                    <label>Kamar <span class="red">*</span></label>
                                    <select id="inap_kamar" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Kamar</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Ranjang <span class="red">*</span></label>
                                    <select id="inap_bed" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Ranjang</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3" id="group_inap_tanggal_masuk">
                                    <label>Tanggal Masuk <span class="red">*</span></label>
                                    <input type="date" id="inap_tanggal_masuk" class="form-control input-group" required />
                                </div-->
                                <div class="col-12 col-md-12 mb-12">
                                    <label>Keterangan <span class="red">*</span></label>
                                    <textarea type="text" id="inap_keterangan" class="form-control" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesInap">
                    <span>
                        <i class="fa fa-check"></i> Pindah Rawat Inap
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>