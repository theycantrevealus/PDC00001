<script type="text/javascript">
    $(function() {

        var currentUIDBatal = "";

        protocolLib = {
            permintaan_resep_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", parameter, 3000, "notif_pasien_baru");
                tableResep.ajax.reload();
            }
        };

        function load_resep() {
            var selected = [];
            var resepData = [];
            $.ajax({
                url:__HOSTAPI__ + "/Apotek",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var resepDataRaw = response.response_package.response_data;
                    for(var resepKey in resepDataRaw) {
                        if(
                            resepDataRaw[resepKey].antrian.departemen != null
                        ) {
                            resepData.push(resepDataRaw[resepKey]);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return resepData;
        }

        function load_product_resep(target, selectedData = "", appendData = true) {
            var selected = [];
            var productData;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_detail/" + selectedData,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    productData = response.response_package.response_data;
                    for (var a = 0; a < productData.length; a++) {
                        var penjaminList = [];
                        var penjaminListData = productData[a].penjamin;
                        for(var penjaminKey in penjaminListData) {
                            if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                                penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                            }
                        }

                        if(selected.indexOf(productData[a].uid) < 0 && appendData) {
                            $(target).append("<option penjamin-list=\"" + penjaminList.join(",") + "\" satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            //return (productData.length == selected.length);
            return {
                allow: (productData.length == selected.length),
                data: productData
            };
        }

        function populateObat(data) {
            var obatList = {};
            for(var a = 0; a < data.length; a++) {
                if(data[a].detail != undefined) {
                    var listBiasa = data[a].detail;
                    for(var b = 0; b < listBiasa.length; b++) {
                        if(obatList[listBiasa[b].obat] == undefined) {
                            obatList[listBiasa[b].obat] = {
                                nama: "",
                                counter: 0
                            };
                        }

                        obatList[listBiasa[b].obat]['nama'] = listBiasa[b].detail.nama;
                        obatList[listBiasa[b].obat]['counter'] += 1;
                    }

                    var listRacikan = data[a].racikan;
                    for(var c = 0; c < listRacikan.length; c++) {
                        for(var d = 0; d < listRacikan[c].detail.length; d++) {
                            if(obatList[listRacikan[c].detail[d].obat] == undefined) {
                                obatList[listRacikan[c].detail[d].obat] = {
                                    nama: "",
                                    counter: 0
                                };
                            }

                            obatList[listRacikan[c].detail[d].obat]['nama'] = listRacikan[c].detail[d].detail.nama;
                            obatList[listRacikan[c].detail[d].obat]['counter'] += 1;
                        }
                    }
                }
            }

            return obatList;
        }

        /*var listResep = load_resep();
        var requiredItem = populateObat(listResep);
        for(var requiredItemKey in requiredItem) {
            $("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama.toUpperCase()/!* + " <b class=\"text-danger\">" + requiredItem[requiredItemKey].counter + " <i class=\"fa fa-receipt\"></i></b>"*!/ + "</li>");
        }*/


        var tableResep = $("#table-resep").DataTable({
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
                    d.request = "get_resep_dokter";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var resepDataRaw = response.response_package.response_data;
                    var parsedData = [];
                    var IGD = [];

                    console.log(resepDataRaw);

                    for(var resepKey in resepDataRaw) {
                        if(resepDataRaw[resepKey].antrian.departemen !== undefined && resepDataRaw[resepKey].antrian.departemen !== null) {
                            if(resepDataRaw[resepKey].antrian.departemen.uid === __POLI_IGD__) {
                                IGD.push(resepDataRaw[resepKey]);
                            } else {

                                parsedData.push(resepDataRaw[resepKey]);
                            }
                        }
                    }
                    var autonum = 1;
                    var finalData = IGD.concat(parsedData);
                    for(var az in finalData) {
                        finalData[az].autonum = autonum;
                        autonum++;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return resepDataRaw;
                }
            },
            language: {
                search: "",
                searchPlaceholder: "No.RM/Nama Pasien"
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[2, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.created_at_parsed;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.departemen !== undefined && row.antrian.departemen !== null) {
                            if(row.departemen.uid === __POLI_INAP__) {
                                return row.departemen.nama + "<br />" +
                                    "<span class=\"text-info\">" + row.ns_detail.kode_ns + "</span> - " + row.ns_detail.nama_ns;
                            } else {
                                return row.departemen.nama;
                            }
                        } else {
                            return "";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return ((row.pasien_info.panggilan_name !== undefined && row.pasien_info.panggilan_name !== null) ? row.pasien_info.panggilan_name.nama : "") + " " + row.pasien_info.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a class=\"btn btn-info btn-sm btn-edit-mesin " + ((row.departemen.uid === __POLI_IGD__) ? "blob blue" : "") + "\" href=\"" + __HOSTNAME__ + "/resep/view/" + row.uid + "\">" +
                            "<span><i class=\"fa fa-pencil-alt\"></i> Detail</span>" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });


    });
</script>