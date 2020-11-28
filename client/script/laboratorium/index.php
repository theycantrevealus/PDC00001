<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function(){

	    var tableServiceLabor = $("#service_labor").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",

            "ajax":{
                url: __HOSTAPI__ + "/Laboratorium",
                type: "POST",
                data: function(d) {
                    d.request = "get-laboratorium-backend";
                    d.mode = "reqular";
                    d.status = 'P';
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    var parsedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    for(var key in returnedData) {
                        var detailData = returnedData[key].detail;
                        var itemAutonum = 1;
                        for(var key_lab in detailData) {
                            parsedData.push({
                                autonum:itemAutonum,
                                uid:returnedData[key].uid,
                                kode:returnedData[key].kode,
                                nama:returnedData[key].nama,
                                id: detailData[key_lab].id,
                                satuan: detailData[key_lab].satuan,
                                keterangan: detailData[key_lab].keterangan,
                                status: detailData[key_lab].status
                            });
                            itemAutonum++;
                        }
                    }
                    return parsedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Lab"
            },
            rowGroup: {
                startRender:function(rows,group){
                    return group +' ( '+rows.count()+' )';
                },
                endRender: function ( rows, group ) {

                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ? i : 0;
                    };

                    var groupData = rows.data();
                    var groupUX = [];
                    for(var groupKey in groupData) {
                        if(groupUX[groupData[groupKey].uid] === undefined) {
                            groupUX[groupData[groupKey].uid] = 1;
                        } else {
                            groupUX[groupData[groupKey].uid] += 1;
                        }
                    }



                    for(var parseKey in groupUX) {
                        $(".group_" + parseKey).parent().each(function (e) {
                            if(e > 0) {
                                $(this).remove();
                            }
                        });

                        $(".group_" + parseKey).parent().attr("rowspan", groupUX[parseKey]);
                    }

                    var sub = rows
                        .data()
                        .pluck(1)
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);


                    return $("<tr/>")
                        .append( "<td colspan=\"3\">End group for " + group + "</td>")
                        .append( "<td></td>")
                        .append("</tr>")
                },
                dataSrc: "kode"
            },
            "columns" : [
                {
                    "data" : "autonum", render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : "kode", render: function(data, type, row, meta) {
                        return "<b class=\"text-info group_" + row.uid + "\">" + row.kode.toUpperCase() + "</b> - " + row.nama;
                    }
                },
                {
                    "data" : "keterangan", render: function(data, type, row, meta) {
                        return row.keterangan;
                    }
                },
                {
                    "data" : "aksi", render: function(data, type, row, meta) {
                        var statusRender = (row.status === "A") ? "<button type=\"button\" class=\"btn btn-danger btn-sm btn-nonaktif\" lab=\"" + row.uid + "\" item=\"" + row.id + "\" data-toggle=\"tooltip\" title=\"Tandai tidak siap pesan\"><i class=\"fa fa-check\"></i> Non-aktifkan</button>" : "<button type=\"button\" class=\"btn btn-success btn-sm btn-aktif\" lab=\"" + row.uid + "\" item=\"" + row.id + "\" data-toggle=\"tooltip\" title=\"Tandai tidak pesan\"><i class=\"fa fa-check\"></i> Aktifkan</button>";

                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            statusRender +
                            "</div>";
                    }
                }
            ]
        });

	    $("body").on("click", ".btn-aktif", function() {
	        toogleStatus($(this));
        });

        $("body").on("click", ".btn-nonaktif", function() {
            toogleStatus($(this));
        });

        function toogleStatus(target) {
            var uid = target.attr("lab");
            var id = target.attr("item");

            var status = (target.hasClass("btn-aktif")) ? "A" : "N";

            Swal.fire({
                title: "Item Laboratorium",
                text: (status === "N") ? "Order item laboratorium ini tidak akan bisa di order" : "Order item laboratorium sudah bisa di order",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __HOSTAPI__ + "/Laboratorium",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "toogle_status_item_lab",
                            uid: uid,
                            id: id,
                            status: status
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                tableServiceLabor.ajax.reload();
                            } else {
                                console.log(response);
                                Swal.fire(
                                    "Item Laboratorium",
                                    "Status item laboratorium gagal diproses",
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error:function(response) {
                            //
                        }
                    });
                }
            });
        }

		var tableAntrianLabor = $("#table-antrian-labor").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Laboratorium",
                type: "POST",
                data: function(d) {
                    d.request = "get-antrian-backend";
                    d.mode = "reqular";
                    d.status = 'P';
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
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
                searchPlaceholder: "Cari Nomor Order"
            },
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["waktu_order"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["no_rm"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["pasien"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["departemen"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["dokter"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<a href=\"" + __HOSTNAME__ + "/laboratorium/antrian/" + row['uid'] + "\" class=\"btn btn-warning btn-sm\">" +
                                    "<i class=\"fa fa-sign-out-alt\"></i>" +
                                "</a>" +
                                "<a href=\"" + __HOSTNAME__ + "/laboratorium/cetak/" + row['uid'] + "\" target='_blank' class=\"btn btn-primary btn-sm\">" +
                                    "<i class=\"fa fa-print\"></i>" +
                                "</a>" +
                                "<button type=\"button\" id=\"order_lab_" + row.uid + "\" class=\"btn btn-success btn-sm btn-selesai\" data-toggle='tooltip' title='Tandai selesai'>" +
                                    "<i class=\"fa fa-check\"></i>" +
                                "</a>" +
                            "</div>";
					}
				}
			]
		});

        $("body").on("click", ".btn-selesai", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Orderan selesai akan langsung terkirim pada dokter yang melakukan permintaan pemeriksaan laboratorium dan tidak dapat diubah lagi. Mohon pastikan data sudah benar",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __HOSTAPI__ + "/Laboratorium",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "verifikasi_hasil",
                            uid: uid
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Order Laboratorium",
                                    "Pemeriksaan berhasil terkirim",
                                    "success"
                                ).then((result) => {
                                    tableAntrianLabor.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Order Laboratorium",
                                    "Order gagal diproses",
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error:function(response) {
                            //
                        }
                    });
                }
            });
        });



        $("#range_history").change(function() {
            tableHistoryLabor.ajax.reload();
        });

        function getDateRange(target) {
            var rangeHistory = $(target).val().split(" to ");
            if(rangeHistory.length > 1) {
                return rangeHistory;
            } else {
                return [rangeHistory, rangeHistory];
            }
        }

        var tableHistoryLabor = $("#table-history-labor").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Laboratorium",
                type: "POST",
                data: function(d) {
                    d.request = "get-antrian-backend";
                    d.from = getDateRange("#range_history")[0];
                    d.to = getDateRange("#range_history")[1];
                    d.mode = "history";
                    d.status = 'D';
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
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
                searchPlaceholder: "Cari Nomor Order"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["waktu_order"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["no_rm"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["pasien"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["departemen"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["dokter"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/laboratorium/view/" + row['uid'] + "/\" class=\"btn btn-info btn-sm\">" +
                                "<i class=\"fa fa-eye\"></i> Detail" +
                            "</a>" +
                            "<button class=\"btn btn-info btn-sm btnCetak\" id=\"lab_" + row.uid + "\">" +
                                "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });


        $("body").on("click", ".btnCetak", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var labPasien = loadPasien(uid);
            var labItem = loadLabOrderItem(uid);
            var labLampiran = loadLampiran(uid);

            //console.log(labItem);

            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/lab_hasil.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    lab_pasien: labPasien,
                    lab_item: labItem,
                    lab_lampiran: labLampiran
                },
                success: function (response) {
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "Laporan Laboratorium " + labPasien.pasien.no_rm,
                        afterPrint: function() {
                            //
                        }
                    });
                },
                error: function (response) {
                    //
                }
            });

            return false;
        });




        //SOCKET
        Sync.onmessage = function(evt) {
            var signalData = JSON.parse(evt.data);
            var command = signalData.protocols;
            var type = signalData.type;
            var sender = signalData.sender;
            var receiver = signalData.receiver;
            var time = signalData.time;
            var parameter = signalData.parameter;

            if(command !== undefined && command !== null && command !== "") {
                protocolLib.command(command, type, parameter, sender, receiver, time);
            } else {
                console.log(command);
            }
        }



        let protocolLib = {
            antrian_laboratorium_baru: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianLabor.ajax.reload();
                notification (type, parameter, 3000, "notif_lab_baru");
            },
            antrian_laboratorium_selesai: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianLabor.ajax.reload();
                tableHistoryLabor.ajax.reload();
            }
        };





















        function loadPasien(uid_order){		//uid_lab_order
            var MetaData;
            if (uid_order != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-data-pasien-antrian/" + uid_order,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){
                        MetaData = response.response_package;

                        /*if (Object.size(MetaData) > 0){
                            if (MetaData.pasien != ""){
                                $("#no_rm").html(MetaData.pasien.no_rm);
                                $("#tanggal_lahir").html(MetaData.pasien.tanggal_lahir);
                                $("#panggilan").html(MetaData.pasien.panggilan);
                                $("#nama").html(MetaData.pasien.nama);
                                $("#jenkel").html(MetaData.pasien.jenkel);
                            }
                        }*/
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return MetaData;
        }

        function loadLabOrderItem(params){	        //params = id lab_order_detail
            let dataItem;
            let html = "";
            if (params != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-order-detail-item/" + params,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){


                        if (response.response_package.response_result > 0){
                            dataItem = response.response_package.response_data;
                            $.each(dataItem, function(key, item){
                                html += "<div class=\"card\"><div class=\"card-header bg-white\">" +
                                    "<h5 class=\"card-header__title flex m-0\" style=\"padding-top: 20px;\"><i class=\"fa fa-hashtag\"></i> " + (key + 1) + ". "+ item.nama + "</h5>" +
                                    "</div><div class=\"card-body\">" +
                                    "<table class=\"table table-bordered table-striped largeDataType border-style\">" +
                                    "<thead class=\"thead-dark\">" +
                                    "<tr>" +
                                    "<th class=\"wrap_content\">No</th>" +
                                    "<th>Item</th>" +
                                    "<th>Nilai</th>" +
                                    "<th class=\"wrap_content\">Satuan</td>" +
                                    "<th class=\"wrap_content\">Nilai Min.</td>" +
                                    "<th class=\"wrap_content\">Nilai Maks.</td>" +
                                    "</tr>" +
                                    "</thead>" +
                                    "<tbody>";


                                var requestedItem = item.request_item.split(",").map(function(intItem) {
                                    return parseInt(intItem, 10);
                                });

                                if (item.nilai_item.length > 0){
                                    let nomor = 1;
                                    $.each(item.nilai_item, function(key, items){
                                        let nilai = items.nilai;

                                        if (nilai == null){
                                            nilai = "";
                                        }

                                        // id untuk input nilai formatnya: nilai_<uid tindakan>_<id nilai lab>
                                        if(requestedItem.indexOf(items.id_lab_nilai) < 0)
                                        {
                                            /*html += "<tr class=\"strikethrough\">" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td>" + items.keterangan + "</td>" +
                                                "<td><input id=\"nilai_" + items.uid_tindakan + "_" + items.id_lab_nilai + " value=\"" + nilai + "\" class=\"form-control inputItemTindakan\" /></td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>" +
                                                "</tr>";*/
                                        } else {
                                            html += "<tr>" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td style=\"width: 40%;\">" + items.keterangan + "</td>" +
                                                "<td>" + nilai + "</td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>" +
                                                "</tr>";
                                            nomor++;
                                        }
                                    });
                                }

                                html += "</tbody></table></div></div>";
                                $("#hasil_pemeriksaan").append(html);
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return html;
        }

        function loadLampiran(uid_order){
            let dataItem;

            if (uid_order != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-lampiran/" + uid_order,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){
                        if (response.response_package != ""){
                            dataItem = response.response_package.response_data;
                            let baseUrl = __HOST__ + '/document/laboratorium/' + uid_order + '/';

                            /*var pdfjsLib = window['pdfjs-dist/build/pdf'];
                            pdfjsLib.GlobalWorkerOptions.workerSrc = __HOSTNAME__ + '/plugins/pdfjs/build/pdf.worker.js';
                            var loadingTask;*/

                            $(dataItem).each(function(key, item){
                                loadLampiranCanvas(baseUrl + item.lampiran, item.id);
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return dataItem;
        }

        function loadLampiranCanvas(doc_url, id){
            var newDocRow = document.createElement("TR");

            var newDocCellNum = document.createElement("TD");
            var newDocCellDoc = document.createElement("TD");
            $(newDocCellDoc).addClass("text-center");
            var newDocCellAct = document.createElement("TD");

            var newDocument = document.createElement("CANVAS");

            $(newDocument)
                .css({
                    "width": "75%"
                })
                .attr('id', 'pdfViewer_' + id);

            $(newDocCellDoc).append(newDocument);
            if (doc_url != undefined) {
                // Using DocumentInitParameters object to load binary data.
                var loadingTask = pdfjsLib.getDocument({
                    url: doc_url
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
                        var canvas = $(newDocument)[0];
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
                            //
                        });
                    });
                }, function(reason) {
                    console.error(reason);
                });
            }

            var newDeleteDoc = document.createElement("button");
            $(newDeleteDoc)
                .addClass("btn btn-sm btn-danger delete_document_registered")
                .html("<span style=\"display: block;\"><i class=\"fa fa-trash\"></i></span>")
                .attr('type', 'button')
                .data('id', 'lampiran_' + id);

            $(newDocCellAct).append(newDeleteDoc);

            $(newDocRow).append(newDocCellNum);
            $(newDocRow).append(newDocCellDoc);
            $(newDocRow).append(newDocCellAct);

            $("#labor-lampiran-table").append(newDocRow);
            rebaseLampiran();
        }

        //fungsi untuk tanda ceklis tab lampiran
        /*function check_page_2() {
            if($("#po_document_table tbody tr").length > 0) {
                $("#status-dokumen").fadeIn();
            } else {
                $("#status-dokumen").fadeOut();
            }
        }*/

        function autoDocument(file) {
            var newDocRow = document.createElement("TR");

            var newDocCellNum = document.createElement("TD");
            var newDocCellDoc = document.createElement("TD");
            $(newDocCellDoc).addClass("text-center");
            var newDocCellAct = document.createElement("TD");

            var newDocument = document.createElement("CANVAS");
            $(newDocument).css({
                "width": "75%"
            });
            $(newDocCellDoc).append(newDocument);
            if (file.type == "application/pdf" && file != undefined) {
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
                            var canvas = $(newDocument)[0];
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
                                //
                            });
                        });
                    }, function(reason) {
                        console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }

            var newDeleteDoc = document.createElement("button");
            $(newDeleteDoc).addClass("btn btn-sm btn-danger delete_document").html("<span style=\"display: block;\"><i class=\"fa fa-trash\"></i></span>").attr('type', 'button');
            $(newDocCellAct).append(newDeleteDoc);

            $(newDocRow).append(newDocCellNum);
            $(newDocRow).append(newDocCellDoc);
            $(newDocRow).append(newDocCellAct);

            $("#labor-lampiran-table").append(newDocRow);
            rebaseLampiran();
        }

        function rebaseLampiran() {
            $("#labor-lampiran-table tbody tr").each(function(e) {
                var id = (e + 1);
                $(this).attr({
                    "id": "document_" + id
                });
                $(this).find("td:eq(0)").html((e + 1));
                $(this).find("td:eq(2) button").attr({
                    "id": "delete_document_" + id
                });
            });
        }
	});
</script>