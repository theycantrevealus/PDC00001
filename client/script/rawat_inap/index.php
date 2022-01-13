<script type="text/javascript">
    $(function () {
        var listRI = $("#table-antrian-rawat-jalan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inap",
                type: "POST",
                data: function(d) {
                    d.request = "get_rawat_inap";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {

                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        var data = response.response_package.response_data;
                        for(var key in data) {
                            if(data[key].pasien !== null && data[key].pasien !== undefined) {
                                returnedData.push(data[key]);
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Pasien / Nama Dokter / Ruangan"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        
                        var tagihanAllow = 0;
                        var itemTagihanApotek = [];
                        var tagihan = row.invoice;
                        for(var ab in tagihan) {
                            for(az in tagihan[ab].invoice_detail) {
                                if(tagihan[ab].invoice_detail[az].status_bayar === "Y") {
                                    tagihanAllow = 1;
                                } else {
                                    if(tagihan[ab].invoice_detail[az].item_type === "master_unit_bed") {
                                        tagihanAllow = 1;    
                                    } else {
                                        if(tagihan[ab].invoice_detail[az].item_type === "master_inv" && itemTagihanApotek.indexOf(tagihan[ab].invoice_detail[az].item) < 0) {
                                        itemTagihanApotek.push(tagihan[ab].invoice_detail[az].item);
                                    }
                                    tagihanAllow = 0;
                                    break;
                                    }
                                }
                            }
                        }

                        var apotekAllow = 0;

                        var apotek = row.tagihan_apotek;
                        if(apotek.length > 0) {
                            for(var a in apotek) {
                                if(
                                    apotek[a].status_resep === "N" ||   //Verifikasi
                                    apotek[a].status_resep === "K"      //Bayar
                                ) {
                                    apotekAllow = 0;
                                    break;
                                } else {
                                    if(itemTagihanApotek.length > 0 && (apotek[a].status_resep === "L" || apotek[a].status_resep === "D")) {
                                        apotekAllow = 0;
                                        break;
                                    } else {
                                        apotekAllow = 1;
                                    }
                                }
                            }
                        } else {
                            apotekAllow = 1;
                        }


                        var laborAllow = 0;
                        var labor = row.tagihan_laboratorium;
                        
                        if(labor.length > 0) {
                            for(var a in labor) {
                                if(labor[a].detail === undefined || labor[a].detail === null) {
                                    laborAllow = 1;
                                } else {
                                    if(
                                        labor[a].status === "V" ||
                                        labor[a].status === "K"
                                    ) {
                                        laborAllow = 0;
                                        break;
                                    } else {
                                        laborAllow = 1;
                                    }
                                }
                            }
                        } else {
                            laborAllow = 1;
                        }



                        var radioAllow = 0;
                        var radio = row.tagihan_radiologi;
                        if(radio.length > 0) {
                            for(var a in radio) {
                                if(radio[a].detail === undefined || radio[a].detail === null) {
                                    console.log(1);
                                    radioAllow = 1;
                                } else {
                                    if(
                                        radio[a].status === "V" ||
                                        radio[a].status === "K"
                                    ) {
                                        radioAllow = 0;
                                        break;
                                    } else {
                                        radioAllow = 1;
                                    }
                                }
                            }
                        } else {
                            radioAllow = 1;
                        }


                        if(tagihanAllow > 0) {
                            if(row.bed !== undefined && row.bed !== null) {
                                return "<h5 class=\"autonum\" allow-inap=\"" + 1 + "|" + 1 + "|" + 1 + "\" id=\"uid_" + row.uid + "\" keterangan=\"" + row.keterangan + "\">" + row.autonum + "</h5>";
                            } else {
                                return "<h5 class=\"autonum\" allow-inap=\"" + apotekAllow + "|" + laborAllow + "|" + radioAllow + "\" id=\"uid_" + row.uid + "\" keterangan=\"" + row.keterangan + "\">" + row.autonum + "</h5>";
                            }
                        } else {
                            return "<h5 class=\"autonum\" allowTagihan=\"" + tagihanAllow + "\" allow-inap=\"" + 0 + "|" + 0 + "|" + 0 + "\" id=\"uid_" + row.uid + "\" keterangan=\"" + row.keterangan + "\">" + row.autonum + "</h5>";
                        }

                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.waktu_masuk_tanggal + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b kunjungan=\"" + row.kunjungan + "\" data=\"" + row.pasien.uid + "\" id=\"pasien_" + row.uid + "\" class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + row.pasien.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.nurse_station !== undefined && row.nurse_station !== null) {
                            return (row.kamar !== null) ? "<span bed=\"" + row.bed.uid + "\" kamar=\"" + row.kamar.uid + "\" id=\"kamar_" + row.uid + "\">" + row.kamar.nama + "</span><br />" + row.bed.nama  + "<br /><b class=\"text-info\">[" + row.nurse_station.kode_ns + "]</b> " +row.nurse_station.nama_ns: "";
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\" id=\"dokter_" + row.uid + "\" data=\"" + row.dokter.uid + "\">" + row.dokter.nama + "</span>"
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + ((row.asal !== undefined && row.asal !== null) ? row.asal.nama : "") + "</span>"
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\" id=\"penjamin_" + row.uid + "\" data=\"" + row.penjamin.uid + "\">" + row.penjamin.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            /*"<a href=\"" + __HOSTNAME__ + "/rawat_inap/dokter/index/" + row.pasien.uid + "/" + row.kunjungan + "/" + row.penjamin.uid + "\" class=\"btn btn-sm btn-info\">" +
                            "<span><i class=\"fa fa-sign-out-alt\"></i> Proses</span>" +
                            "</a>" +*/
                            "<button class=\"btn btn-sm btn-info btnProsesInap\" id=\"btn_proses_" + row.uid + "\">" +
                            "<span><i class=\"fa fa-sign-out-alt\"></i> Proses</span>" +
                            "</<button>" +
                            "<button disabled class=\"btn btn-sm btn-success btn-pulangkan-pasien\" id=\"pulangkan_" + row.pasien.uid + "\">" +
                            "<i class=\"fa fa-check\"></i> Pulangkan Pasien" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        var selectedUID = "";
        var selectedPasien = "";
        var selectedKunjungan = "";

        $("body").on("click", ".btnProsesInap", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            selectedUID = id;
            selectedPasien = $("#pasien_" + id).attr("data");
            selectedKunjungan = $("#pasien_" + id).attr("kunjungan");

            if(__RULE_PRA_INAP_ALLOW_ADMINISTRASI__ === 0) {
                $("#inap_penjamin").html("<option value=\"" + $("#penjamin_" + id).attr("data") + "\">" + $("#penjamin_" + id).html() + "</option>");
                $("#inap_dokter").html("<option>" + $("#dokter_" + id).html() + "</option>");
                $("#inap_keterangan").html($("#uid_" + id).attr("keterangan"));

                var kamar = $("#kamar_" + id).attr("kamar");
                var bed = $("#kamar_" + id).attr("bed");


                loadKamar("inap", kamar);
                loadBangsal("inap", $("#inap_kamar").val(), bed);


                $("#form-inap").modal("show");
            } else {
                var SpliterStatus = $("#uid_" + id).attr("allow-inap").split("|");
                var allowResep = parseInt(SpliterStatus[0]);
                var allowLabor = parseInt(SpliterStatus[1]);
                var allowRadio = parseInt(SpliterStatus[2]);

                if(allowResep === 1 && allowLabor === 1 && allowRadio === 1) {
                    $("#inap_penjamin").html("<option value=\"" + $("#penjamin_" + id).attr("data") + "\">" + $("#penjamin_" + id).html() + "</option>");
                    $("#inap_dokter").html("<option>" + $("#dokter_" + id).html() + "</option>");
                    $("#inap_keterangan").html($("#uid_" + id).attr("keterangan"));

                    var kamar = $("#kamar_" + id).attr("kamar");
                    var bed = $("#kamar_" + id).attr("bed");


                    loadKamar("inap", kamar);
                    loadBangsal("inap", $("#inap_kamar").val(), bed);


                    $("#form-inap").modal("show");
                } else {
                    //Detail Allow Inap
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Inap/tagihan_pra_inap/" + id,
                        type: "GET",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            var dataTagihan = response.response_package.response_data;
                            $("#form-administrasi").modal("show");
                            $("#table-tagihan-pra-inap tbody").html("");
                            var totalBiaya = 0;

                            for(var a in dataTagihan) {
                                var autoTagihanID = 1;



                                var tagihanAdministrasi = dataTagihan[a].administrasi;
                                for(var b in tagihanAdministrasi) {
                                    totalBiaya += parseFloat(tagihanAdministrasi[b].subtotal);

                                    $("#table-tagihan-pra-inap tbody").append("<tr>" +
                                        "<td>" + autoTagihanID + "</td>" +
                                        "<td>" + tagihanAdministrasi[b].item.nama + "</td>" +
                                        "<td class=\"wrap_content\"><span class=\"text-danger\">" + ((tagihanAdministrasi[b].status_bayar === "Y") ? "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Lunas</i></span>" : "<i class=\"fa fa-exclamation-circle\"></i> Belum Lunas</i></span>") + "</td>" +
                                        "<td class=\"number_style\">" + number_format(parseFloat(tagihanAdministrasi[b].subtotal), 2, ".", ",") + "</td>" +
                                        "</tr>");
                                    autoTagihanID++;
                                }




                                var tagihanTindakan = dataTagihan[a].tindakan;
                                for(var b in tagihanTindakan) {

                                    totalBiaya += parseFloat(tagihanTindakan[b].subtotal);

                                    $("#table-tagihan-pra-inap tbody").append("<tr>" +
                                        "<td>" + autoTagihanID + "</td>" +
                                        "<td>" + tagihanTindakan[b].item.nama + "</td>" +
                                        "<td class=\"wrap_content\"><span class=\"text-danger\">" + ((tagihanTindakan[b].status_bayar === "Y") ? "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Lunas</i></span>" : "<i class=\"fa fa-exclamation-circle\"></i> Belum Lunas</i></span>") + "</td>" +
                                        "<td class=\"number_style\">" + number_format(parseFloat(tagihanTindakan[b].subtotal), 2, ".", ",") + "</td>" +
                                        "</tr>");
                                    autoTagihanID++;
                                }






                                var tagihanApotek = dataTagihan[a].tagihan_apotek;
                                for(var b in tagihanApotek) {
                                    var status_parse = "";
                                    var watchTagihan = 0;
                                    var status = tagihanApotek[b].status_resep;
                                    if(status === "N") {
                                        status_parse = "<span class=\"text-info\"><i class=\"fa fa-clock\"></i> Sedang Verifikasi</span>";
                                    } else if(status === "C") {
                                        status_parse = "<span class=\"text-muted\"><i class=\"fa fa-exclamation-triangle\"></i> Cancel</span>";
                                    } else if(status === "K") {
                                        status_parse = "<span class=\"text-danger\"><i class=\"fa fa-exclamation-circle\"></i> Belum Lunas</span>";
                                    } else if(status === "L" || status === "P" || status === "S" || status === "D") {
                                        status_parse = "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Lunas</span>";
                                    } else {
                                        status_parse = status;
                                    }
                                    var kalkulasiTotal = 0;
                                    var TotalBiayaApotek = tagihanApotek[b].biaya;
                                    for(var tbApotek in TotalBiayaApotek) {
                                        if(TotalBiayaApotek[tbApotek].status_bayar === "N") {
                                            watchTagihan += 1;
                                        }
                                        kalkulasiTotal += parseFloat(TotalBiayaApotek[tbApotek].subtotal);
                                    }

                                    if(watchTagihan > 0) {
                                        status_parse = "<span class=\"text-warning\"><i class=\"fa fa-exclamation-circle\"></i> Belum Lunas - Dilunaskan Secara Prosedur</span>";
                                    }

                                    totalBiaya += kalkulasiTotal;

                                    $("#table-tagihan-pra-inap tbody").append("<tr>" +
                                        "<td>" + autoTagihanID + "</td>" +
                                        "<td>" + tagihanApotek[b].kode + "</td>" +
                                        "<td class=\"wrap_content\">" + status_parse + "</td>" +
                                        "<td class=\"number_style\">" + number_format(kalkulasiTotal, 2, ".", ",") + "</td>" +
                                        "</tr>");
                                    autoTagihanID++;
                                }



                                var tagihanLabor = dataTagihan[a].tagihan_laboratorium;
                                for(var b in tagihanLabor) {
                                    if(tagihanLabor[b].detail !== null  && tagihanLabor[b].detail !== undefined) {
                                        var status_parse = "";
                                        var status = tagihanLabor[b].status;
                                        if(status === "V") {
                                            status_parse = "<span class=\"text-info\"><i class=\"fa fa-clock\"></i> Sedang Verifikasi</i></span>";
                                        } else if(status === "K") {
                                            status_parse = "<span class=\"text-danger\"><i class=\"fa fa-exclamation-circle\"></i> Belum Lunas</i></span>";
                                        } else if(status === "L" || status === "P" || status === "D") {
                                            status_parse = "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Lunas</i></span>";
                                        }
                                        var kalkulasiTotal = 0;
                                        var TotalBiayaLaboratorium = tagihanLabor[b].biaya;
                                        for(var tbLabor in TotalBiayaLaboratorium) {
                                            kalkulasiTotal += parseFloat(TotalBiayaLaboratorium[tbLabor].subtotal);
                                        }

                                        totalBiaya += kalkulasiTotal;

                                        

                                        $("#table-tagihan-pra-inap tbody").append("<tr>" +
                                            "<td>" + autoTagihanID + "</td>" +
                                            "<td>" + tagihanLabor[b].no_order + "</td>" +
                                            "<td class=\"wrap_content\">" + status_parse + "</td>" +
                                            "<td class=\"number_style\">" + ((tagihanLabor[b].status === "V") ? number_format(0, 2, ".", ",") : number_format(kalkulasiTotal, 2, ".", ",")) + "</td>" +
                                            "</tr>");
                                        autoTagihanID++;
                                    }
                                }


                                var tagihanRadio = dataTagihan[a].tagihan_radiologi;
                                for(var b in tagihanRadio) {
                                    if(tagihanRadio[b].detail !== null  && tagihanRadio[b].detail !== undefined) {
                                        var status_parse = "";
                                        var status = tagihanRadio[b].status;
                                        if(status === "V") {
                                            status_parse = "<span class=\"text-info\"><i class=\"fa fa-clock\"></i> Sedang Verifikasi</i></span>";
                                        } else if(status === "K") {
                                            status_parse = "<span class=\"text-danger\"><i class=\"fa fa-exclamation-circle\"></i> Belum Lunas</i></span>";
                                        } else if(status === "L" || status === "P" || status === "D") {
                                            status_parse = "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Lunas</i></span>";
                                        }
                                        var kalkulasiTotal = 0;
                                        var TotalBiayaRadiologi = tagihanRadio[b].biaya;
                                        for(var tbRadio in TotalBiayaRadiologi) {
                                            kalkulasiTotal += parseFloat(TotalBiayaRadiologi[tbRadio].subtotal);
                                        }

                                        totalBiaya += kalkulasiTotal;

                                        $("#table-tagihan-pra-inap tbody").append("<tr>" +
                                            "<td>" + autoTagihanID + "</td>" +
                                            "<td>" + tagihanRadio[b].no_order + "</td>" +
                                            "<td class=\"wrap_content\">" + status_parse + "</td>" +
                                            "<td class=\"number_style\">" + ((tagihanRadio[b].status === "V") ? number_format(0, 2, ".", ",") : number_format(kalkulasiTotal, 2, ".", ",")) + "</td>" +
                                            "</tr>");
                                        autoTagihanID++;
                                    }
                                }


                            }

                            $("#total_biaya").html(number_format(totalBiaya, 2, ".", ","));

                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            }
        });

        $("body").on("click", ".btn-pulangkan-pasien", function () {
            var id = $(this).attr("id").split("_");
            selectedUID = id[id.length - 1];

            $("#form-pulang").modal("show");
        });

        $("#btnProsesInap").click(function () {
            Swal.fire({
                title: "Rawat Inap",
                text: "Proses Administrasi?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Inap",
                        type: "POST",
                        data: {
                            request: "update_inap",
                            uid: selectedUID,
                            pasien: selectedPasien,
                            //waktu_masuk: $("#inap_tanggal_masuk").val(),
                            kamar: $("#inap_kamar").val(),
                            penjamin: $("#inap_penjamin").val(),
                            bed: $("#inap_bed").val(),
                            dokter: $("#inap_dokter").val(),
                            kunjungan: selectedKunjungan,
                            keterangan: $("#inap_keterangan").val()
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Rawat Inap",
                                    "Administrasi berhasil diproses",
                                    "success"
                                ).then((result) => {
                                    listRI.ajax.reload();
                                    $("#form-inap").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    "Rawat Inap",
                                    response.response_package.response_message,
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else {
                    $("#form-inap").modal("hide");
                }
            });
        });

        $("#btnSubmitPulang").click(function() {
            Swal.fire({
                title: "Rawat Inap",
                text: "Pulangkan pasien?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Inap",
                        type: "POST",
                        data: {
                            request: "pulangkan_pasien",
                            uid: selectedUID,
                            jenis: $("input[name=\"txt_jenis_pulang\"]:checked").val(),
                            keterangan: $("#txt_keterangan_pulang").val()
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Rawat Inap",
                                    "Pasien dipulangkan!",
                                    "success"
                                ).then((result) => {
                                    $("#form-pulang").modal("hide");
                                    listRI.ajax.reload();
                                });
                            } else {
                                console.log(response);
                            }
                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });

        loadKamar("inap");

        $(".inputan_inap").select2({
            dropdownParent:$("#form-inap")
        });

        $("#inap_kamar").change(function() {
            loadBangsal("inap", $("#inap_kamar").val());
        });

        function resetSelectBox(selector, name){
            $("#"+ selector +" option").remove();
            var opti_null = "<option value='' selected disabled>"+ name +" </option>";
            $("#" + selector).append(opti_null);
        }

        function loadKamar(target_ui, selected = ""){
            resetSelectBox(target_ui + "_kamar", "Ruangan");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Ruangan",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData !== undefined) {
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if(MetaData[i].uid === selected) {
                                $(selection).attr({
                                    "selected": "selected"
                                });
                            }
                            $("#" + target_ui + "_kamar").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadBangsal(target_ui, kamar, selected = ""){
            resetSelectBox(target_ui + "_bed", "Pilih Ranjang");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Bed/bed-ruangan-avail/" + kamar,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData !== undefined){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if(MetaData[i].uid === selected) {
                                $(selection).attr({
                                    "selected": "selected"
                                });
                            }
                            $("#" + target_ui + "_bed").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }
    });
</script>

<div id="form-inap" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Proses Rawat Inap</h5>
            </div>
            <div class="modal-body" id="inap-container">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="inap_penjamin" class="form-control select2 inputan_inap" readonly disabled></select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="inap_dokter" class="form-control select2 inputan_inap" readonly disabled></select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Kamar <span class="red">*</span></label>
                                    <select id="inap_kamar" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Kamar</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Ranjang <span class="red">*</span></label>
                                    <select id="inap_bed" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Ranjang</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-12 mb-12">
                                    <label>Keterangan <span class="red">*</span></label>
                                    <textarea type="text" id="inap_keterangan" class="form-control" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesInap">
                    <span>
                        <i class="fa fa-check"></i> Proses
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="form-administrasi" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Administrasi Pasien</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered largeDataType" id="table-tagihan-pra-inap">
                    <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Item Penagihan</th>
                            <th class="wrap_content">Status</th>
                            <th class="wrap_content">Biaya</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">TOTAL</td>
                            <td id="total_biaya" class="number_style"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>





<div id="form-pulang" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pemulangan Pasien</h5>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <h6>Jenis Pulang</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="txt_jenis_pulang" value="P" checked/>
                                <label class="form-check-label">
                                    PAPS
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="txt_jenis_pulang" value="D" />
                                <label class="form-check-label">
                                    Dokter
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <h6>Keterangan Pemulangan</h6>
                    <textarea style="min-height: 100px;" class="form-control" id="txt_keterangan_pulang"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitPulang">Proses</button>
            </div>
        </div>
    </div>
</div>