<script type="text/javascript">
    $(function () {

        $("#txt_pasien").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Pasien tidak ditemukan";
                }
            },
            placeholder: "Cari Pasien",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pasien/select2/" + $(".select2-search__field").val(),
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
                                text: item.no_rm + " - " + item.nama,
                                id: item.uid,
                            }
                        })
                    };
                }
            }
        }).addClass("form-control item-amprah").on("select2:select", function(e) {
            emr.ajax.reload();
        });

        var emr = $("#table-emr").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "order": [[ 1, "desc" ]],
            "ajax":{
                url: __HOSTAPI__ + "/CPPT",
                type: "POST",
                data: function(d){
                    d.request = "emr";
                    d.pasien = $("#txt_pasien option:selected").val();
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var dataSet = response.response_package.response_data;
                    var dataResponse = [];
                    if(dataSet === undefined || dataSet === null) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return dataSet;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Kwitansi"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b class=\"wrap_content\">" + row.created_at_parsed + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var icd10 = row.detail.icd10_kerja;
                        return "<b class=\"wrap_content\">" + ((icd10.length > 0) ? icd10[0].kode : "") + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"text-info\">" + row.poli.nama + "</h5>" + row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
            ]
        });

        $("#table-emr_filter input").remove();
    });
</script>