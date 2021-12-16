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

                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    for(var dataKey in rawData) {
                        if(rawData[dataKey].gudang === __UNIT__.gudang/* && parseFloat(rawData[dataKey].stok_terkini) > 0*/) {
                            if(uniqueData[rawData[dataKey].barang] === undefined) {
                                uniqueData[rawData[dataKey].barang] = {
                                    barang: rawData[dataKey].barang,
                                    stok_terkini: parseFloat(rawData[dataKey].stok_terkini),
                                    stok_batch: 0,
                                    batch: {},
                                    detail : rawData[dataKey].detail,
                                    image: rawData[dataKey].image,
                                    kategori_obat: rawData[dataKey].kategori_obat,
                                    kode_barang: rawData[dataKey].kode_barang,
                                    in: rawData[dataKey].in,
                                    out: rawData[dataKey].out
                                };
                            }


                            var uniqueBatch = {};
                            if(Array.isArray(rawData[dataKey].batch)) {
                                var batchData = rawData[dataKey].batch;
                                for(var bKey in batchData) {
                                    if(batchData[bKey].gudang.uid === __UNIT__.gudang && batchData[bKey].barang === rawData[dataKey].barang) {
                                        if(uniqueBatch[batchData[bKey].batch] === undefined && uniqueBatch[batchData[bKey].batch] === null) {
                                            uniqueBatch[batchData[bKey].batch] = 0;
                                        }
                                        uniqueBatch[batchData[bKey].batch] = parseFloat(batchData[bKey].stok_terkini);
                                        uniqueData[rawData[dataKey].barang].stok_batch += parseFloat(batchData[bKey].stok_terkini);
                                    }
                                }

                                uniqueData[rawData[dataKey].barang].batch = uniqueBatch;
                            }
                        }
                    }

                    var autonum = 1;
                    for(var pKey in uniqueData) {

                        for(var bza in uniqueData[pKey].batch) {
                            //uniqueData[rawData[dataKey].barang].stok_batch += uniqueBatch[bza];
                        }

                        returnedData.push({
                            autonum: autonum,
                            barang: pKey,
                            detail: uniqueData[pKey].detail,
                            stok_terkini: uniqueData[pKey].stok_terkini,
                            image: uniqueData[pKey].image,
                            kategori_obat: rawData[dataKey].kategori_obat,
                            kode_barang: rawData[dataKey].kode_barang,
                            stok_batch: uniqueData[pKey].stok_batch,
                            in: uniqueData[pKey].in,
                            out: uniqueData[pKey].out,
                            batch: uniqueData[pKey].batch
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
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var kategoriObat = "";
                        for(var kategoriObatKey in row.kategori_obat) {
                            if(row["kategori_obat"][kategoriObatKey].kategori != null) {
                                kategoriObat += "<span style=\"margin: 5px;\" class=\"badge badge-outline-purple badge-custom-caption\">" + row["kategori_obat"][kategoriObatKey].kategori + "</span>";
                            }
                        }

                        return 		"<div class=\"row\">" +
                            "<div class=\"col-md-2\">" +
                            "<center><img style=\"border-radius: 5px;\" src=\"" + __HOST__ + row.image + "\" width=\"60\" height=\"60\" /></center>" +
                            "</div>" +
                            "<div class=\"col-md-10\">" +
                            "<b><i class=\"text-info\">" + ((row.kode_barang == undefined) ? "[KODE_BARANG]" : row.kode_barang.toUpperCase()) + "</i></b><br />" +
                            "<h5>" + ((row.detail !== null) ? row.detail.nama.toUpperCase() : "") + "</h5>" +
                            kategoriObat +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style text-right\">" + number_format(row.in, 2, ".", ",") + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style text-right\">" + number_format(row.out, 2, ".", ",") + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<h5 class=\"number_style wrap_content\">" + row.stok_terkini + "</h5>";
                        var counter = 0;
                        console.log(row.batch);
                        for(var az in row.batch) {
                            counter+= parseFloat(row.batch[az]);
                        }
                        return "<h5 class=\"number_style text-right\">" + row.counter + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"wrap_content\">" + row.detail.satuan_terkecil_info.nama + "</h6>";
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
