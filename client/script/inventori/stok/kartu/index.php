<script src="<?php echo __HOSTNAME__; ?>/plugins/cdndt/button.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/cdndt/button.html.js"></script>

<script type="text/javascript">
    $(function(){
        var MODE = "tambah", selectedUID;

        var tableExportStok = $("#tableExportStok").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            dom: 'Bfrtip',
            buttons: [{
                text: 'Export CSV',
                action: function (e, dt, node, config) {
                    $.ajax({
                        url: __HOSTAPI__ + "/Inventori",
                        type: "POST",
                        headers:{
                            Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                        },
                        data: {
                            request: 'export_current_gudang_stok',
                            gudang: __UNIT__.gudang
                        },
                        success: function(res, status, xhr) {
                            var dataSet = "\"batch\",\"nama\",\"kedaluarsa\",\"stok\",\"dup\"\n";
                            
                            res = res.response_package;
                            
                            for(var a in res) {
                                dataSet += "\"" + res[a].batch + "\",\"" + res[a].barang + "\",\"" + res[a].ed + "\"," + res[a].stok_terkini + "," + res[a].dup + "\n";
                            }
                            
                            var csvData = new Blob([dataSet], {type: 'text/csv;charset=utf-8;'});
                            var csvURL = window.URL.createObjectURL(csvData);
                            var tempLink = document.createElement('a');
                            tempLink.href = csvURL;
                            var currentdate = new Date();

                            tempLink.setAttribute('download', (__UNIT__.nama.toUpperCase().replace(' ', '_')) + '-' + currentdate.getDate() + '/' + (currentdate.getMonth()+1) + '/' + currentdate.getFullYear() + '_' + currentdate.getHours() + '_' + currentdate.getMinutes() + '_' + currentdate.getSeconds() + '.csv');
                            tempLink.click();
                        }
                    });
                }
            }],
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "data_populate_export_stok";
                    d.gudang = __UNIT__.gudang;
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
                        return row.batch;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.barang;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.stok_terkini;
                    }
                }
            ]
        });

        $("#btnExport").click(function() {
            $("#review-stok-export").modal("show");
        });

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
                    console.clear();
                    console.log(response);
                    var rawData = [];
                    var returnedData = [];
                    var uniqueData = {};

                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    for(var dataKey in rawData) {
                        if(uniqueData[rawData[dataKey].barang] === undefined) {
                            uniqueData[rawData[dataKey].barang] = {
                                barang: rawData[dataKey].barang,
                                stok_terkini: 0,
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

                        uniqueData[rawData[dataKey].barang].stok_terkini += parseFloat(rawData[dataKey].stok_terkini);
                        // if(rawData[dataKey].gudang === __UNIT__.gudang/* && parseFloat(rawData[dataKey].stok_terkini) > 0*/) {
                            
                        //     var uniqueBatch = {};
                        //     if(Array.isArray(rawData[dataKey].batch)) {
                        //         var batchData = rawData[dataKey].batch;
                        //         for(var bKey in batchData) {
                        //             if(batchData[bKey].gudang.uid === __UNIT__.gudang && batchData[bKey].barang === rawData[dataKey].barang) {
                        //                 if(uniqueBatch[batchData[bKey].batch] === undefined && uniqueBatch[batchData[bKey].batch] === null) {
                        //                     uniqueBatch[batchData[bKey].batch] = 0;
                        //                 }
                        //                 uniqueBatch[batchData[bKey].batch] = parseFloat(batchData[bKey].stok_terkini);
                        //                 uniqueData[rawData[dataKey].barang].stok_batch += parseFloat(batchData[bKey].stok_terkini);
                        //             }
                        //         }

                        //         uniqueData[rawData[dataKey].barang].batch = uniqueBatch;
                        //     }
                        // }
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
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return returnedData;
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
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
                        return "<h5 class=\"number_style wrap_content\">" + row.stok_terkini + "</h5>";
                        // var counter = 0;
                        // console.log(row.batch);
                        // for(var az in row.batch) {
                        //     counter+= parseFloat(row.batch[az]);
                        // }
                        // return "<h5 class=\"number_style text-right\">" + counter + "</h5>";
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

<div id="review-stok-export" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Export Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Data Export</h5>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped" id="tableExportStok">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th class="wrap_content">No</th>
                                                        <th class="wrap_content">Batch</th>
                                                        <th>Item</th>
                                                        <th class="wrap_content">Saldo</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
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
