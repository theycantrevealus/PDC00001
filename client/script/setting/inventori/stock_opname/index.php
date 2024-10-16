<style>
    #csv_file_data table {
        width: 100% !important;
    }
</style>
<script src="<?php echo __HOSTNAME__; ?>/plugins/cdndt/button.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/cdndt/button.html.js"></script>
<script type="text/javascript">
    $(function() {
        $("#filter_tanggal").change(function() {
            LOG.ajax.reload();
        });

        $("#filter_jam").change(function() {
            LOG.ajax.reload();
        });

        load_gudang("#filter_gudang");

        $("#filter_gudang").select2().on("select2:select", function(e) {
            LOG.ajax.reload();
        });

        var filtedData = [];
        var reportSODT;

        $("#upload_csv").submit(function(event) {
            event.preventDefault();
            $("#review-import").modal();
            $("#progressed_so").html(0);
            $("#csv_file_data").html("<h6 class=\"text-center\">Load Data...</h6>");
            var formData = new FormData(this);
            formData.append("request", "stok_import_fetch_auto_so");
            $("#so_progress").hide();
            $.ajax({
                url: __HOSTAPI__ + "/Inventori",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    filtedData = [];
                    var data = response.response_package;
                    
                    $("#csv_file_data").html("");
                    var thead = "";
                    if(data.column) {
                        thead += "<tr>";
                        for(var count = 0; count < data.column.length; count++) {
                            if(data.column[count].toUpperCase() === "KEDALUARSA") {
                                thead += "<th style=\"width: 10%\">" + data.column[count] + "</th>";
                            } else {
                                thead += "<th>" + data.column[count] + "</th>";
                            }
                        }
                        thead += "</tr>";
                    }
                    var table_view = document.createElement("TABLE");
                    $(table_view).append("<thead class=\"thead-dark\">" + thead + "</thead>");
                    $("#csv_file_data").append(table_view);
                    

                    for(var aa in data.row_data) {
                        filtedData.push(data.row_data[aa]);
                    }
                    
                    generated_data = filtedData;
                    reportSODT = $(table_view).addClass("table table-striped largeDataType").DataTable({
                        dom: 'Bfrtip',
                        buttons: ['csv'],
                        lengthMenu: [[20, 50, -1], [20, 50, "All"]],
                        data:filtedData,
                        columns : data.column_builder
                    });

                    $("#csv_file_data table").css({
                        "width": "100%",
                        "table-layout": "fixed"
                    });

                    $("#total_so").html(filtedData.length);

                    $("#upload_csv")[0].reset();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });

        var currentProgCount = 0;

        $('#review-import').on('hidden.bs.modal', function () {
            $("#so_progress .progress-bar").attr({
                "aria-valuenow": 0
            }).css({
                "width": "0%"
            });
            currentProgCount = 0;
            $("#progressed_so").html(0);
            $("#import_data").removeAttr("disabled").addClass("btn-success").removeClass("btn-warning");
        })

        

        $("#import_data").click(function() {
            var failedDataSet = [];
            $("#import_data").attr({
                "disabled": "disabled"
            }).removeClass("btn-success").addClass("btn-warning");
            reportSODT.clear();
            reportSODT.draw();
            $("#so_progress").fadeIn(function() {
                console.clear();
                console.log("Report Data . . .");
                $("#total_so").html(filtedData.length);
                $("#so_progress .progress-bar").attr({
                    "aria-valuemax": filtedData.length,
                });
                $("#progressed_so").html(currentProgCount);
                console.clear();
                filtedData.forEach(function(ent) {
                    syncSO(ent, $("#filter_gudang option:selected").val()).then((result) => {
                        currentProgCount += parseFloat(result['result']);
                        // if(result['batch_failed'].length > 0) {
                        //     console.log(result['batch_failed']);    
                        // }
                        
                        if(parseFloat(result['result']) < 1) {
                            reportSODT.row.add(result['failed'][0]);
                            failedDataSet.push(result['failed'][0]);
                            reportSODT.draw();
                            console.log(result);
                        }
                        
                        $("#progressed_so").html(currentProgCount);
                        $("#so_progress .progress-bar").attr({
                            "aria-valuenow": currentProgCount
                        }).css({
                            "width": (currentProgCount / filtedData.length) * 100 + "%"
                        });
                    });
                });

                LOG.ajax.reload();
            });
        });

        async function syncSO(dataSet, gudang) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: __HOSTAPI__ + "/Inventori",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "POST",
                    data: {
                        request: "auto_so_prog",
                        gudang: gudang,
                        dataSet: dataSet,
                        date_limit_opname: $("#filter_tanggal").val() + " " + $("#filter_jam").val() + ":00"
                    },
                    success:function(response) {
                        resolve(response.response_package);
                    },
                    error: function(e) {
                        reject(e);
                    }
                });
            });
        }
        
        function load_gudang(target) {
            var gudangData;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/gudang",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    //$(target + " option").remove();
                    gudangData = response.response_package.response_data;
                    for(var a in gudangData) {
                        var newOption = document.createElement("OPTION");
                        $(newOption).html(gudangData[a].nama).attr({
                            "value":gudangData[a].uid
                        });

                        if(gudangData[a].uid === __UNIT__.gudang) {
                            $(newOption).attr({
                                "selected": "selected"
                            });
                        }
                        $(target).append(newOption);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return gudangData;
        }
        var LOG = $("#log-loader").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Setting",
                type: "POST",
                data: function(d) {
                    d.request = "get_stok_log";
                    d.gudang = $("#filter_gudang option:selected").val();
                    d.from = $("#filter_tanggal").val() + " " + $("#filter_jam").val() + ":00";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = response.response_package.response_data;
                    
                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nama_gudang + "</span><h6>" + row.logged_at_parsed + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b class=\"wrap_content\"># " + row.jenis_transaksi + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.nama_barang + "<br /><span class=\"text-info\">" + row.batch + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style\">" + number_format(row.keluar, 2, '.', ',') + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style\">" + number_format(row.masuk, 2, '.', ',') + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style\">" + number_format(row.saldo, 2, '.', ',') + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(parseFloat(row.masuk) > 0 && parseFloat(row.keluar) === 0) {
                            return "<h5 class=\"number_style text-success\">&plus;" + number_format(row.masuk, 2, '.', ',') + "</h5>";
                        } else if(parseFloat(row.masuk) === 0 && parseFloat(row.keluar) > 0) {
                            return "<h5 class=\"number_style text-danger\">&minus;" + number_format(row.keluar, 2, '.', ',') + "</h5>";
                        } else {
                            return "";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                }
            ]
        });
    });
</script>

<div id="review-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Import Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">CSV</h5>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b id="progressed_so"></b>/<b id="total_so"></b>
                                            <div class="progress" id="so_progress">
                                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <hr />
                                            <div id="csv_file_data" style="overflow-y: scroll" class="table-responsive"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="import_data">Import</button>
            </div>
        </div>
    </div>
</div>