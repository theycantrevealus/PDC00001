<script type="text/javascript">
    $(function () {
        var SEPList = $("#table-sep").DataTable({
            "ajax":{
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "sep";
                },
                dataSrc:function(response) {
                    console.log(response);
                    //return response.response_package.response_data;
                    return [];
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
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