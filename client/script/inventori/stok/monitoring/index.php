<script type="text/javascript">
    $(function() {
        load_gudang("#txt_gudang");
        var monitoringTable = $("#monitoring-table").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "stok_monitoring";
                    d.gudang = $("#txt_gudang option:selected").val();
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.clear();
                    console.log(response);
                    var rawData = [];

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
                searchPlaceholder: "Cari Nama Barang"
            },
            "rowCallback": function ( row, data, index ) {
                var min = data.min;
                var max = data.max;
                var total = data.total;
                var status = "";
                if(total > max) {
                    status = "bg-warning-custom";
                } else if(total < min) {
                    status = "bg-danger-custom";
                } else {
                    status = "bg-success-custom";
                }

                $("td", row).addClass(status);
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style\">" + number_format(row.min, 2, ".", ",") + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style\">" + number_format(row.max, 2, ".", ",") + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var min = row.min;
                        var max = row.max;
                        var total = row.total;
                        var status = "";
                        var statusIcon = "";
                        if(total > max) {
                            status = "text-warning";
                            statusIcon = "fa fa-caret-up";
                        } else if(total < min) {
                            status = "text-danger";
                            statusIcon = "fa fa-caret-down";
                        } else {
                            status = "text-success";
                            statusIcon = "fa fa-check-circle";
                        }

                        return "<h5 class=\"number_style " + status + "\">" + number_format(row.total, 2, ".", ",") + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var min = row.min;
                        var max = row.max;
                        var total = row.total;
                        var status = "";
                        var statusIcon = "";
                        var statusCaption = "";
                        if(total > max) {
                            status = "badge-outline-warning";
                            statusIcon = "arrow_upward";
                            statusCaption = "Berlebih";
                        } else if(total < min) {
                            status = "badge-outline-danger";
                            statusIcon = "arrow_downward";
                            statusCaption = "Kurang";
                        } else {
                            status = "badge-outline-success";
                            statusIcon = "done_all";
                            statusCaption = "Sesuai";
                        }
                        return "<span style=\"min-width: 130px\" class=\"badge badge-custom-caption " + status + "\"><i class=\"material-icons\">" + statusIcon + "</i> " + statusCaption + "</span>";
                    }
                },
            ]
        });


        $("#txt_gudang").select2()
            .on("select2:select", function(e) {
                monitoringTable.ajax.reload();
            });

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
                    $(target + " option").remove();
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
    });
</script>