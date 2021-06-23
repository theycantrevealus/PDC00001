<script type="text/javascript">
    $(function(){
        var MODE = "tambah", selectedUID;

        var tableGudang = $("#table-item").DataTable({
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
                    d.request = "get_stok_back_end";
                    d.gudang = __UNIT__.gudang;
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var rawData = [];
                    var returnedData = [];
                    var uniqueData = {};

                    console.clear();
                    console.log(response);


                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    for(var dataKey in rawData)
                    {
                        if(rawData[dataKey].gudang === __UNIT__.gudang/* && parseFloat(rawData[dataKey].stok_terkini) > 0*/) {
                            if(uniqueData[rawData[dataKey].barang] === undefined) {
                                uniqueData[rawData[dataKey].barang] = {
                                    barang: rawData[dataKey].barang,
                                    stok_terkini: parseFloat(rawData[dataKey].stok_terkini),
                                    stok_batch: 0,
                                    detail : rawData[dataKey].detail,
                                    image: rawData[dataKey].image,
                                    kategori_obat: rawData[dataKey].kategori_obat,
                                    kode_barang: rawData[dataKey].kode_barang
                                };
                            }

                            if(Array.isArray(rawData[dataKey].batch)) {
                                var batchData = rawData[dataKey].batch;
                                for(var bKey in batchData) {
                                    if(batchData[bKey].gudang.uid === __UNIT__.gudang) {
                                        uniqueData[rawData[dataKey].barang].stok_batch += parseFloat(batchData[bKey].stok_terkini);
                                    }
                                }
                            }
                        }
                    }
                    var autonum = 1;
                    for(var pKey in uniqueData)
                    {
                        returnedData.push({
                            autonum: autonum,
                            barang: pKey,
                            detail: uniqueData[pKey].detail,
                            stok_terkini: uniqueData[pKey].stok_terkini,
                            image: uniqueData[pKey].image,
                            kategori_obat: rawData[dataKey].kategori_obat,
                            kode_barang: rawData[dataKey].kode_barang,
                            stok_batch: uniqueData[pKey].stok_batch
                        });
                        autonum++;
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
                searchPlaceholder: "Cari Nama Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var kategoriObat = "";
                        for(var kategoriObatKey in row.kategori_obat) {
                            if(row["kategori_obat"][kategoriObatKey].kategori != null) {
                                kategoriObat += "<span style=\"margin: 5px;\" class=\"badge badge-info\">" + row["kategori_obat"][kategoriObatKey].kategori + "</span>";
                            }
                        }

                        return 		"<div class=\"row\">" +
                            "<div class=\"col-md-2\">" +
                            "<center><img style=\"border-radius: 5px;\" src=\"" + __HOST__ + row.image + "\" width=\"60\" height=\"60\" /></center>" +
                            "</div>" +
                            "<div class=\"col-md-10\">" +
                            "<b><i>" + ((row.kode_barang == undefined) ? "[KODE_BARANG]" : row.kode_barang.toUpperCase()) + "</i></b><br />" +
                            "<h5>" + ((row.detail !== null) ? row.detail.nama.toUpperCase() : "") + "</h5>" +
                            kategoriObat +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style\">" + row.stok_batch + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/inventori/stok/kartu/view/" + row["barang"] + "\" class=\"btn btn-info btn-sm\">" +
                            "<i class=\"fa fa-eye\"></i> View" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });
    });
</script>
