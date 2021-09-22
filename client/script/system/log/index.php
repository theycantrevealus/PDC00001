<script src="<?php echo __HOSTNAME__; ?>/plugins/chartjs/chart.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/json-viewer/json-viewer.js"></script>
<script type="text/javascript">
    $(function () {
        var actLib = {
            "D": "<i class=\"fa fa-trash text-danger\"></i>",
            "U": "<i class=\"fa fa-edit text-warning\"></i>",
            "I": "<i class=\"fa fa-plus-circle text-success\"></i>"
        };

        var configOption = {
            plugins: {
                legend: {
                    display: false
                }
            },
            scale: {
                ticks: {
                    display: false,
                    maxTicksLimit: 0
                }
            }
        };

        var ctx = document.getElementById("myChart").getContext("2d");

        var myNewChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: [],
                datasets: []
            },
            options: configOption
        });

        refreshData(myNewChart, $("#actionType option:selected").val(), $("#issuer option:selected").val());

        $("#graph-log-pie").click(function () {
            myNewChart.destroy();
            myNewChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: [],
                    datasets: []
                },
                options: configOption
            });
            refreshData(myNewChart, $("#actionType option:selected").val(), $("#issuer option:selected").val());
            myNewChart.update();
        });

        $("#graph-log-line").click(function () {
            myNewChart.destroy();
            myNewChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: [],
                    datasets: []
                },
                options: configOption
            });
            refreshData(myNewChart, $("#actionType option:selected").val(), $("#issuer option:selected").val());
            myNewChart.update();
        });

        $("#graph-log-bar").click(function () {
            myNewChart.destroy();
            myNewChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: [],
                    datasets: []
                },
                options: configOption
            });
            refreshData(myNewChart, $("#actionType option:selected").val(), $("#issuer option:selected").val());
            myNewChart.update();
        });

        $("#actionType").change(function () {
            refreshData(myNewChart, $("#actionType option:selected").val(), $("#issuer option:selected").val());
            LogList.ajax.reload();
        });

        $("#issuer").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Barang tidak ditemukan";
                }
            },
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pegawai/get_all_pegawai",
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
                            var stokApotek = 0;
                            var colorSet = "#808080";

                            return {
                                "id": item.uid,
                                "text": "<div style=\"color:" + colorSet + " !important;\">" + item.nama + "</div>",
                                "html": 	"<div class=\"select2_item_stock\">" +
                                    "<div style=\"color:" + colorSet + " !important;\">" + item.nama + "</div>" +
                                    "</div>",
                                "title": item.nama
                            }
                        })
                    };
                }
            },
            placeholder: "Pegawai",
            selectOnClose: true,
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                return data.html;
            },
            templateSelection: function(data) {
                return data.text;
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            refreshData(myNewChart, $("#actionType option:selected").val(), data.id);
            LogList.ajax.reload();
        });

        function refreshData(myNewChart, actionType  = "all", issuer = "all") {
            var forReturn = {
                labels: [],
                datasets: [{
                    backgroundColor : ["rgba(229, 0, 0, 1)"],
                    borderColor : ["rgba(255, 99, 132, 1)"],
                    label: "D",
                    data : []
                }, {
                    backgroundColor : ["rgba(63, 198, 0, 1)"],
                    borderColor : ["rgba(120, 255, 58, 1)"],
                    label: "I",
                    data : []
                }, {
                    backgroundColor : ["rgba(239, 243, 0, 1)"],
                    borderColor : ["rgba(255, 206, 86, 1)"],
                    label: "U",
                    data : []
                }],
            };
            $.ajax({
                url: __HOSTAPI__ + "/Log",
                async: false,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    request: "log_activity",
                    actionType: actionType,
                    issuer: issuer
                },
                success: function (response) {
                    var data = response.response_package;
                    //console.log(data);
                    if(data !== undefined && data !== null) {
                        for(var ax in data) {
                            forReturn.labels.push(ax.replaceAll("_", "/"));
                            var thisGroupInsert = 0;
                            var thisGroupUpdate = 0;
                            var thisGroupDelete = 0;

                            //Date Grouper
                            for(var ay in data[ax]) {

                                for(var az in data[ax][ay]) {
                                    if(data[ax][ay][az].action === "D") {
                                        //thisGroupDelete.push(data[ax][ay].length);
                                        //forReturn.datasets[0].data.push(data[ax][ay].length);
                                        thisGroupDelete += 1;
                                    }

                                    if(data[ax][ay][az].action === "I") {
                                        //thisGroupInsert.push(data[ax][ay].length);
                                        //forReturn.datasets[1].data.push(data[ax][ay].length);
                                        thisGroupInsert += 1;
                                    }

                                    if(data[ax][ay][az].action === "U") {
                                        //thisGroupUpdate.push(data[ax][ay].length);
                                        //forReturn.datasets[2].data.push(data[ax][ay].length);
                                        thisGroupUpdate += 1;
                                    }
                                }
                            }

                            forReturn.datasets[0].data.push(thisGroupDelete);
                            forReturn.datasets[1].data.push(thisGroupInsert);
                            forReturn.datasets[2].data.push(thisGroupUpdate);
                        }

                        myNewChart.data = forReturn;
                        myNewChart.update();
                    } else {
                        console.log(response);
                    }
                },
                error: function (response) {
                    //
                }
            });
        }


        var LogList = $("#table-log").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Log",
                type: "POST",
                data: function(d) {
                    d.request = "get_log_activity_dt";
                    d.actionType = $("#actionType option:selected").val();
                    d.issuer = $("#issuer option:selected").val();
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var rawData = [];
                    var returnedData = [];
                    var uniqueData = {};

                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return rawData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Log"
            },
            fixedColumns: {
                leftColumns: 3
            },
            scrollX: "200px",
            scrollCollapse: true,
            scrollY: 500,
            "rowCallback": function ( row, data, index ) {
                if(data.action === "D") {
                    $("td", row).addClass("bg-danger-custom");
                } else if(data.action === "I") {
                    $("td", row).addClass("bg-success-custom");
                } else if(data.action === "U") {
                    $("td", row).addClass("bg-warning-custom");
                }
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + actLib[row.action] + "&nbsp;&nbsp;&nbsp;" + row.logged_at_date_parsed + " <b class=\"badge badge-outline-info\">" + row.logged_at_time_parsed + "</b></span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content text-purple\"><i class=\"fa fa-user-circle\"></i> " + row.pegawai.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b class=\"text-info wrap_content\"><i class=\"fa fa-table\"></i> " + row.table_name + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {

                        try {
                            var oldData = JSON.parse(row.old_value);
                            var newData = JSON.parse(row.new_value);

                            var parseCompare = [];

                            if(oldData !== null && newData !== null) {
                                for(var ai in oldData) {
                                    if(oldData[ai] != newData[ai]) {
                                        parseCompare.push("<div class=\"row\">" +
                                            "<div class=\"col-lg-5 text-danger text-right\">" + ((oldData[ai] === undefined || oldData[ai] === null) ? "-/0" : oldData[ai]) + "</div>" +
                                            "<div class=\"col-lg-2 text-center\">" +
                                            "<code><i class=\"fa fa-chevron-right\"></i></code>" +
                                            "</div>" +
                                            "<div class=\"col-lg-5 text-success\">" + ((newData[ai] === undefined || newData[ai] === null) ? "-/0" : newData[ai]) + "</div>" +
                                            "</div>");
                                    } else {
                                        /*parseCompare.push("<tr>" +
                                            "<td class=\"text-muted text-right\">" + oldData[ai] + "</td>" +
                                            "<td class=\"text-muted\">" + newData[ai] + "</td>" +
                                            "</tr>");*/
                                    }
                                }

                                return parseCompare.join("");
                            } else {
                                return "";
                            }
                        } catch (e) {
                            return "";
                        }


                        /*return "<p>" +
                            "<a class=\"btn btn-info\" data-toggle=\"collapse\" href=\"#collapseExample" + row.id + "\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\">" +
                            "Changes" +
                            "</a>" +
                            "</p>" +
                            "<div class=\"collapse\" id=\"collapseExample" + row.id + "\">" +
                            "<div class=\"card card-body\">" +
                            "<table><thead><tr><th>Old</th><th>New</th></tr></thead><tbody>" + parseCompare.join("") + "</tbody></table>" +
                            "</div>" +
                            "</div>";*/

                        /*var jsonViewerOld = new JSONViewer();
                        jsonViewerOld.showJSON(JSON.parse(row.old_value), -1, -1);

                        var jsonViewerNew = new JSONViewer();
                        jsonViewerNew.showJSON(JSON.parse(row.old_value), -1, -1);

                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-6\">" +
                            "<code class=\"custom\"><pre>" + jsonViewerOld.getContainer().innerHTML + "</pre></code>" +
                            "</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<code class=\"custom\"><pre>" + jsonViewerNew.getContainer().innerHTML + "</pre></code>" +
                            "</div>" +
                            "</div>";*/
                    }
                }
            ]
        });




    });
</script>