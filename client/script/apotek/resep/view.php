<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function () {
        var currentMetaData, currentAsesmen, currentRacikanActive, targetKodeResep, currentStatusOpname = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");;
        var alasanUbah = "";
        var totalResep = 0;
        var totalRacikan = 0;
        var alasanLib = {}, alasanRacikanLib = {};
        var currentData = {
            resep: {},
            racikan: {}
        };

        var verifData = {
            resep: {},
            racikan: {}
        };

        var isChanged = false;

        $(".verifikator-tab a").click(function() {
            var targetID = $(this).attr("href");
            checkEachTab();
        });

        function checkEachTab() {
            //Check Resep
            $("#identifier_jumlah_resep").html($("#table-resep tbody tr").length);
            $("#identifier_jumlah_racikan").html($("#table-resep-racikan tbody tr.racikan-master").length);
            var kajianCheck = false;
            var kajian = populateAllKajian();

            for(var  zz in kajian) {
                if(kajian[zz] === "" || kajian[zz] === "") {
                    kajianCheck = false;
                    break;
                } else {
                    kajianCheck = true;
                }
            }
            if(kajianCheck) {
                $("#identifier_kajian").html("<i class=\"fa fa-check-circle\"></i>").addClass("badge-success").removeClass("badge-danger");
            } else {
                $("#identifier_kajian").html("<i class=\"fa fa-exclamation-circle\"></i>").addClass("badge-danger").removeClass("badge-success");
            }
            //Check Racikan

            //Check Kajian
        }


        $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").change(function() {
            $(this).parent().parent().addClass("active");
            $(".kajian_sel[value=\"y\"]").prop("checked", true);
            $(".kajian_sel[value=\"y\"]").parent().addClass("active");
            $(".kajian_sel[value=\"n\"]").prop("checked", false).removeAttr("checked");
            $(".kajian_sel[value=\"n\"]").parent().removeClass("active");
            checkEachTab();
            return false;
        });

        $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").change(function() {
            $(this).parent().parent().addClass("active");
            $(".kajian_sel[value=\"n\"]").prop("checked", true);
            $(".kajian_sel[value=\"n\"]").parent().addClass("active");
            $(".kajian_sel[value=\"y\"]").prop("checked", false).removeAttr("checked");
            $(".kajian_sel[value=\"y\"]").parent().removeClass("active");
            checkEachTab();
            return false;
        });

        $(".kajian_sel").change(function() {
            if($(this).val() === "n") {
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").prop("checked", false);
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").removeAttr("checked");
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").parent().removeClass("active");
            } else {
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").prop("checked", false);
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").removeAttr("checked");
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").parent().removeClass("active");
            }
            checkEachTab();
        });

        function populateAllKajian() {
            var populateData = {};
            $(".kajian_sel").each(function () {
                var currentName = $(this).attr("name");
                if(populateData[currentName] === undefined) {
                    //populateData[currentName] = "n";
                    populateData[currentName] = "";
                }
                if($(this).is(':checked')) {
                    populateData[currentName] = $(this).val();
                }
            });

            return populateData;
        }

        $.ajax({
            url:__HOSTAPI__ + "/Apotek/detail_resep_2/" + __PAGES__[3],
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                var data = response.response_package[0];

                currentAsesmen = data.asesmen.uid;
                targetKodeResep = data.kode;


                if(data.iterasi > 0) {
                    $("#iter-identifier").show();
                    $("#iterasi-resep").html(parseInt(data.iterasi));
                } else {
                    $("#iter-identifier").hide();
                }

                if(data.alergi_obat !== undefined && data.alergi_obat !== "" && data.alergi_obat !== null) {
                    $("#alergi_obat").html(data.alergi_obat);
                    $("#no-data-alergi-obat").hide();
                } else {
                    $("#no-data-alergi-obat").show();
                }

                if((data.asesmen.diagnosa_kerja !== undefined && data.asesmen.diagnosa_kerja !== "" && data.asesmen.diagnosa_kerja !== null) || data.asesmen.icd_kerja.length > 0) {
                    $("#diagnosa_utama").html(data.asesmen.diagnosa_kerja);
                    $("#no-data-diagnosa-utama").hide();
                } else {
                    $("#no-data-diagnosa-utama").show();
                }

                if((data.asesmen.diagnosa_banding !== undefined && data.asesmen.diagnosa_banding !== "" && data.asesmen.diagnosa_banding !== null) || data.asesmen.icd_banding.length > 0) {
                    $("#diagnosa_banding").html(data.asesmen.diagnosa_banding);
                    $("#no-data-diagnosa-banding").hide();
                } else {
                    $("#no-data-diagnosa-banding").show();
                }

                $("#txt_keterangan_resep").html((data.keterangan !== "" && data.keterangan !== undefined && data.keterangan !== null) ? data.keterangan : "-");
                $("#txt_keterangan_resep_racikan").html((data.keterangan_racikan !== "" && data.keterangan_racikan !== undefined && data.keterangan_racikan !== null) ? data.keterangan_racikan : "-");

                if(data.asesmen.icd_kerja !== undefined && data.asesmen.icd_kerja !== null) {
                    var icd_kerja = data.asesmen.icd_kerja;
                    if(icd_kerja !== undefined && icd_kerja !== null) {
                        for(var icdA in icd_kerja) {
                            if(icd_kerja[icdA] !== undefined && icd_kerja[icdA] !== null) {
                                $("#icd_utama").append("<li>" +
                                    "<b><span class=\"text-info\">" + icd_kerja[icdA].kode + "</span> - " + icd_kerja[icdA].nama + "</b>" +
                                    "</li>");
                            }
                        }
                    }
                }



                if(data.asesmen.icd_banding !== undefined && data.asesmen.icd_banding !== null) {
                    var icd_banding = data.asesmen.icd_banding;
                    if(icd_banding !== undefined && icd_banding !== null) {
                        for(var icdB in icd_banding) {
                            if(icd_banding[icdB] !== undefined && icd_banding[icdB] !== null) {
                                $("#icd_banding").append("<li>" +
                                    "<b><span class=\"text-info\">" + icd_banding[icdB].kode + "</span> - " + icd_banding[icdB].nama + "</b>" +
                                    "</li>");
                            }
                        }
                    }
                }


                if(data.resep !== undefined) {
                    currentMetaData = data.detail;
                    if(
                        currentMetaData.departemen === undefined ||
                        currentMetaData.departemen === null
                    ) {
                        currentMetaData.departemen = {
                            uid: __POLI_INAP__,
                            nama: "Rawat Inap"
                        };
                    }

                    $(".nama_pasien").html(currentMetaData.pasien.no_rm + " - " + ((currentMetaData.pasien.panggilan_name !== null) ? currentMetaData.pasien.panggilan_name.nama + " " + currentMetaData.pasien.nama : currentMetaData.pasien.nama));
                    $(".jk_pasien").html((currentMetaData.pasien.jenkel_detail !== undefined && currentMetaData.pasien.jenkel_detail !== null) ? currentMetaData.pasien.jenkel_detail.nama : "");
                    $(".tanggal_lahir_pasien").html(currentMetaData.pasien.tanggal_lahir_parsed);
                    $(".penjamin_pasien").html(currentMetaData.penjamin.nama);
                    $(".poliklinik").html(currentMetaData.departemen.nama);
                    $(".dokter").html(currentMetaData.dokter.nama);
                    $("#copy-resep-dokter").html(currentMetaData.dokter.nama);
                    $("#copy-resep-pasien").html((currentMetaData.pasien.panggilan_name !== null) ? currentMetaData.pasien.panggilan_name.nama + " " + currentMetaData.pasien.nama : currentMetaData.pasien.nama);
                    $("#copy-resep-pasien-lahir-user").html(currentMetaData.pasien.tanggal_lahir_parsed + " - " + currentMetaData.pasien.usia + " tahun");
                    $("#copy-resep-pasien-alamat").html(currentMetaData.pasien.alamat);
                    $("#copy-resep-tanggal").html(data.created_at_parsed);

                    if(data.resep.length > 0) {

                        var resep_obat_detail = data.resep;

                        keterangan_resep = data.resep[0].keterangan;
                        keterangan_racikan = data.resep[0].keterangan_racikan;

                        for(var resepKey in resep_obat_detail) {
                            totalResep = autoResep({
                                "obat": resep_obat_detail[resepKey].obat,
                                "obat_detail": resep_obat_detail[resepKey].obat_detail,
                                "aturan_pakai": resep_obat_detail[resepKey].aturan_pakai,
                                "keterangan": resep_obat_detail[resepKey].keterangan,
                                "signaKonsumsi": resep_obat_detail[resepKey].signa_qty,
                                "signaTakar": resep_obat_detail[resepKey].signa_pakai,
                                "signaHari": resep_obat_detail[resepKey].qty,
                                "iterasi": resep_obat_detail[resepKey].iterasi,
                                "qty_roman": resep_obat_detail[resepKey].qty_roman,
                                "sat_konsumsi": resep_obat_detail[resepKey].satuan_konsumsi,
                            });
                            if(currentData.resep[resep_obat_detail[resepKey].obat] === undefined) {
                                currentData.resep[resep_obat_detail[resepKey].obat] = {
                                    "aturan_pakai": 0,
                                    "signaKonsumsi": 0,
                                    "signaTakar": 0,
                                    "signaHari": 0
                                };
                            }

                            currentData.resep[resep_obat_detail[resepKey].obat] = {
                                "aturan_pakai": resep_obat_detail[resepKey].aturan_pakai,
                                "signaKonsumsi": resep_obat_detail[resepKey].signa_qty,
                                "signaTakar": resep_obat_detail[resepKey].signa_pakai,
                                "signaHari": resep_obat_detail[resepKey].qty
                            };
                        }
                    } else {
                        $("#table-resep tbody").append("<tr class=\"no-resep\"><td colspan=\"9\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada resep</td></tr>");
                    }

                    var racikan_detail = data.racikan;
                    if(racikan_detail.length === 0) {
                        $("#table-resep-racikan tbody.racikan").append("<tr><td colspan=\"8\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada racikan</td></tr>");
                    } else {
                        for(var racikanKey in racikan_detail) {
                            autoRacikan({
                                uid: racikan_detail[racikanKey].uid,
                                nama: racikan_detail[racikanKey].kode,
                                keterangan: racikan_detail[racikanKey].keterangan,
                                signaKonsumsi: racikan_detail[racikanKey].signa_qty,
                                signaTakar: racikan_detail[racikanKey].signa_pakai,
                                signaHari: racikan_detail[racikanKey].qty,
                                item:racikan_detail[racikanKey].item,
                                iterasi:racikan_detail[racikanKey].iterasi,
                                aturan_pakai: racikan_detail[racikanKey].aturan_pakai,
                                sat_konsumsi: racikan_detail[racikanKey].satuan_konsumsi,
                                qty_roman: racikan_detail[racikanKey].qty_roman
                            });

                            if(currentData.racikan[racikan_detail[racikanKey].uid] === undefined) {
                                currentData.racikan[racikan_detail[racikanKey].uid] = {
                                    signaKonsumsi: 0,
                                    signaTakar: 0,
                                    signaHari: 0,
                                    item: [],
                                    aturan_pakai: 0
                                };
                            }

                            currentData.racikan[racikan_detail[racikanKey].uid] = {
                                signaKonsumsi: racikan_detail[racikanKey].signa_qty,
                                signaTakar: racikan_detail[racikanKey].signa_pakai,
                                signaHari: racikan_detail[racikanKey].qty,
                                item: [],
                                aturan_pakai: racikan_detail[racikanKey].aturan_pakai
                            };

                            var itemKomposisi = racikan_detail[racikanKey].item;

                            for(var komposisiKey in itemKomposisi) {
                                var penjaminObatRacikanListUID = [];
                                var penjaminObatRacikanList = itemKomposisi[komposisiKey].obat_detail.penjamin;
                                for(var penjaminObatKey in penjaminObatRacikanList) {
                                    if(penjaminObatRacikanListUID.indexOf(penjaminObatRacikanList[penjaminObatKey].penjamin) < 0) {
                                        penjaminObatRacikanListUID.push(penjaminObatRacikanList[penjaminObatKey].penjamin);
                                    }
                                }

                                itemKomposisi[komposisiKey].satuan = "<b>" + itemKomposisi[komposisiKey].takar_bulat + "</b><sub nilaiExact=\"" + itemKomposisi[komposisiKey].ratio + "\">" + itemKomposisi[komposisiKey].takar_decimal + "</sub>";
                                itemKomposisi[komposisiKey].racikan = racikan_detail[racikanKey].uid;
                                var totalKomposisi = autoKomposisi((parseInt(racikanKey) + 1), itemKomposisi[komposisiKey], racikan_detail[racikanKey].qty);

                                currentData.racikan[racikan_detail[racikanKey].uid].item.push({
                                    obat: itemKomposisi[komposisiKey].obat,
                                    kekuatan: itemKomposisi[komposisiKey].kekuatan,
                                    jumlah: racikan_detail[racikanKey].qty
                                });
                            }
                        }
                        totalRacikan = calculate_racikan();
                    }


                    if(racikan_detail.length > 0) {
                        //autoRacikan();
                    }

                    $("#total_biaya_obat").html("Rp. " + number_format((totalResep + totalRacikan), 2, ".", ","));
                }

                checkEachTab();
            },
            error: function(response) {
                console.log(response);
            }
        });








        function autoResep(setter = {
            "obat": "",
            "obat_detail": {},
            "aturan_pakai": 0,
            "keterangan": "",
            "signaKonsumsi": 0,
            "signaTakar": 0,
            "signaHari": 0,
            "pasien_penjamin_uid": "",
            "iterasi": 0,
            "qty_roman": "",
            "sat_konsumsi": ""
        }) {
            $("#table-resep tbody tr").removeClass("last-resep");
            var newRowResep = document.createElement("TR");
            $(newRowResep).addClass("last-resep");
            var newCellResepID = document.createElement("TD");
            var newCellResepObat = document.createElement("TD");
            var newCellResepJlh = document.createElement("TD");
            var newCellResepSatuan = document.createElement("TD");
            var newCellResepSigna1 = document.createElement("TD");
            var newCellResepSigna2 = document.createElement("TD");
            var newCellResepSigna3 = document.createElement("TD");
            var newCellHarga = document.createElement("TD");
            var newCellResepPenjamin = document.createElement("TD");
            var newCellResepAksi = document.createElement("TD");


            var checkCopyResep = document.createElement("INPUT");
            $(checkCopyResep).attr({
                "type": "checkbox"
            }).addClass("form-control copy-resep");

            $(newCellResepAksi).append(checkCopyResep);

            var newObat = document.createElement("SELECT");
            $(newObat).attr({
                "roman": setter.qty_roman
            });

            $(newCellResepObat).append(newObat).append("<br /><br /><ol></ol><hr />");

            $(newCellResepObat).append(
                "<div class=\"row\" style=\"padding-top: 5px;\">" +
                "<div style=\"position: relative\" class=\"col-md-12 penjamin-container text-right\"></div>" +
                "<!--div class=\"col-md-7 aturan-pakai-container\"><span>Aturan Pakai</span></div-->" +
                "<div style=\"position: relative; padding-top: 5px;\" class=\"col-md-8 keterangan-container\"></div>" +
                "<div class=\"col-md-4 kategori-obat-container\"><span>Kategori Obat</span><br /></div>" +
                "</div>");
            var newAturanPakai = document.createElement("SELECT");
            var dataAturanPakai = autoAturanPakai();

            //$(newCellResepObat).find("div.aturan-pakai-container").append(newAturanPakai);
            $(newCellResepObat).find("div.aturan-pakai-container");
            $(newAturanPakai).addClass("form-control aturan-pakai").attr({
                "old-data": setter.aturan_pakai
            });
            $(newAturanPakai).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2().hide();
            for(var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakai).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }

            var keteranganPerObat = document.createElement("TEXTAREA");
            $(newCellResepObat).find("div.keterangan-container").append("<span><b>Keterangan / Aturan Pakai</b></span><textarea class=\"form-control keterangan_resep_dokter\">" + ((setter.keterangan !== "") ? setter.keterangan : "-") + "</textarea>")/*.append(keteranganPerObat)*/;
            /*$(keteranganPerObat).addClass("form-control").attr({
                "placeholder": "Keterangan per Obat",
                "disabled": "disabled"
            }).css({
                "min-height": "200px"
            }).val(setter.keterangan);*/

            if(parseInt(setter.iterasi) > 0) {
                $(newCellResepObat).append("<br /><h3 sath=\"" + setter.sat_konsumsi + "\" class=\"text-success text-right resep_script\" data=\"" + setter.iterasi + "\">Iter " + setter.iterasi + " &times; (" + setter.sat_konsumsi.toLowerCase() + ")</h3>");
            } else {
                $(newCellResepObat).append("<br /><h3 sath=\"" + setter.sat_konsumsi + "\" class=\"text-right\">(" + setter.sat_konsumsi.toLowerCase() + ")</h3>");
            }

            //Satuan pemakaian

            var itemData = [];
            var parsedItemData = [];
            var obatNavigator = [];
            for(var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }

                obatNavigator.push(itemData[dataKey].uid);
                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: 	"<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            var harga_tertinggi = 0;

            $(newObat).addClass("form-control resep-obat").select2({
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
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
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
                                var stokKeseluruhan = item.stok;
                                if(item.batch !== undefined) {
                                    var batchCheck = item.batch;
                                    for(var abat in batchCheck) {
                                        if(batchCheck[abat].gudang.uid === __GUDANG_APOTEK__) {
                                            stokApotek += parseFloat(batchCheck[abat].stok_terkini);
                                        }
                                    }
                                }

                                var colorSet = "";
                                if(stokApotek > 0) {
                                    colorSet = "#12a500";
                                } else if(stokApotek < 1 && stokKeseluruhan > 0) {
                                    colorSet = "#F58D00";
                                } else {
                                    colorSet = "#cf0000";
                                }

                                return {
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + colorSet + " !important;\">" + item.nama.toUpperCase() + "</div>",
                                    "html": 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + colorSet + " !important;\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
                placeholder: "Pilih Obat",
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
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];

                var oldData = $("#resep_obat_" + id).attr("old-data");
                if(data.id !== oldData) {
                    isChanged = true;
                }

                $(this).children("[value=\""+ data.id + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });


                var dataKategoriPerObat = autoKategoriObat(data["id"]);
                var kategoriObatDOM = "";
                if(dataKategoriPerObat.length > 0) {
                    $(newCellResepObat).find("div.kategori-obat-container").html("");
                    for(var kategoriObatKey in dataKategoriPerObat) {
                        if(
                            dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                            dataKategoriPerObat[kategoriObatKey].kategori !== null
                        ) {
                            kategoriObatDOM += "<span class=\"badge badge-custom-caption badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                        }
                    }
                    $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                }

                refreshBatch(data.id, id);

                $(newCellResepSatuan).html(data["satuan-caption"]);

                $("#total_biaya_obat").html("Rp. " + number_format((calculate_resep() + calculate_racikan()), 2, ".", ","));
            });

            $(newCellResepSatuan).html(setter.obat_detail.satuan_terkecil_info.nama);




            if(setter.obat !== "") {
                $(newObat).append("<option title=\"" + setter.obat_detail.nama + "\" value=\"" + setter.obat + "\" penjamin-list=\"" + setter.obat_detail.penjamin.join(",") + "\">" + setter.obat_detail.nama + "</option>");
                $(newObat).select2("data", {id: setter.obat, text: setter.obat_detail.nama});
                $(newObat).trigger("change");
                $(newObat).attr({
                    "old-data": setter.obat
                });

                if($(newObat).val() != "none") {
                    var dataKategoriPerObat = autoKategoriObat(setter.obat);
                    var kategoriObatDOM = "";
                    if(dataKategoriPerObat.length > 0) {
                        for(var kategoriObatKey in dataKategoriPerObat) {
                            if(
                                dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                                dataKategoriPerObat[kategoriObatKey].kategori !== null
                            ) {
                                kategoriObatDOM += "<span class=\"badge badge-custom-caption badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                            }
                        }
                        $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                    }
                }
            }



            var newJumlah = document.createElement("INPUT");
            $(newCellResepJlh).append(newJumlah);
            $(newJumlah).addClass("form-control resep_jlh_hari number_style").inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).attr({
                "placeholder": "0",
                "old-data": (setter.signaHari == 0) ? "" : setter.signaHari
            }).val((setter.signaHari == 0) ? "" : setter.signaHari);

            var newKonsumsi = document.createElement("INPUT");
            $(newCellResepSigna1).append(newKonsumsi).css({
                "position": "relative"
            });
            $(newKonsumsi).addClass("form-control resep_konsumsi number_style").attr({
                "placeholder": "0",
                "old-data": (setter.signaKonsumsi == 0) ? "" : setter.signaKonsumsi
            })/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/.val((setter.signaKonsumsi == 0) ? "" : setter.signaKonsumsi);

            $(newCellResepSigna2).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newTakar = document.createElement("INPUT");
            $(newCellResepSigna3).append(newTakar);
            $(newTakar).addClass("form-control resep_takar number_style").attr({
                "placeholder": "0",
                "old-data": (setter.signaTakar == 0) ? "" : setter.signaTakar
            })/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/.val((setter.signaTakar == 0) ? "" : setter.signaTakar);


            var newDeleteResep = document.createElement("BUTTON");
            //$(newCellResepAksi).append(newDeleteResep);
            $(newDeleteResep).addClass("btn btn-sm btn-danger resep_delete").html("<i class=\"fa fa-ban\"></i>");

            $(newCellHarga).addClass("number_style").html(harga_tertinggi);

            $(newRowResep).append(newCellResepID);
            $(newRowResep).append(newCellResepObat);
            $(newRowResep).append(newCellResepSigna1);
            $(newRowResep).append(newCellResepSigna2);
            $(newRowResep).append(newCellResepSigna3);
            $(newRowResep).append(newCellResepJlh);
            $(newRowResep).append(newCellResepSatuan);
            $(newRowResep).append(newCellHarga);
            $(newRowResep).append(newCellResepAksi);
            $("#table-resep").append(newRowResep);

            return rebaseResep();
        }

        function calculate_racikan() {
            var totalRacikan = 0;
            $("#table-resep-racikan > tbody.racikan > tr").each(function(e) {
                var id = (e + 1);
                var batchHarga = $("#racikan_harga_" + id).attr("harga");
                //totalRacikan += parseFloat(batchHarga);

                var currentPriceRacikan = 0;

                $("#komposisi_" + id + " tbody tr").each(function(f) {
                    var harga = $(this).find("td:eq(1) ol").attr("harga");
                    var qty = parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));

                    if(qty < 1 || isNaN(qty)) {
                        currentPriceRacikan += 0
                    } else {
                        currentPriceRacikan += (qty * harga)
                    }
                });

                $("#racikan_harga_" + id).attr({
                    "harga": currentPriceRacikan
                }).html(number_format(currentPriceRacikan, 2, ".", ","));

                totalRacikan += currentPriceRacikan;
            });

            $("#total_resep_racikan").html(number_format(totalRacikan, 2, ".", ",")).attr({
                "harga": totalRacikan
            });

            return totalRacikan;
        }

        function calculate_resep() {
            var totalResep = 0;
            $("#table-resep tbody tr").each(function(e) {
                var id = (e + 1);
                var batchHarga = $("#harga_obat_" + id).attr("harga");
                totalResep += parseFloat(batchHarga);
            });

            $("#total_resep_biasa").html(number_format(totalResep, 2, ".", ",")).attr({
                "harga": totalResep
            });

            return totalResep;
        }

        function rebaseResep() {
            var totalResep = 0;
            $("#table-resep tbody tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "resep_row_" + id
                });
                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");
                $(this).find("td:eq(1) select.resep-obat").attr({
                    "id": "resep_obat_" + id
                });

                $(this).find("td:eq(1) .keterangan_resep_dokter").attr({
                    "id": "keterangan_resep_obat_" + id
                });

                $(this).find("td:eq(1) h3").attr({
                    "id": "iterasi_resep_obat_" + id
                });

                $(this).find("td:eq(1) select.aturan-pakai").attr({
                    "id": "resep_obat_aturan_pakai_" + id
                });


                $(this).find("td:eq(1) ol").attr({
                    "id": "batch_obat_" + id
                });

                //load_product_resep($(this).find("td:eq(1) select.resep-obat"), "");
                if($(this).find("td:eq(1) select.resep-obat").val() != "none") {
                    /*var penjaminAvailable = $(this).find("td:eq(1) select option:selected").attr("penjamin-list").split(",");
                    //checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);*/
                }

                $(this).find("td:eq(2) input:eq(0)").attr({
                    "id": "resep_signa_konsumsi_" + id
                });
                $(this).find("td:eq(4) input:eq(0)").attr({
                    "id": "resep_signa_takar_" + id
                });
                $(this).find("td:eq(5) input").attr({
                    "id": "resep_jlh_hari_" + id
                });
                $(this).find("td:eq(6)").attr({
                    "id": "resep_satuan_" + id
                });
                $(this).find("td:eq(7)").attr({
                    "id": "harga_obat_" + id
                });
                $(this).find("td:eq(8) button").attr({
                    "id": "resep_delete_" + id
                });
                $(this).find("td:eq(8) input").attr({
                    "id": "resep_copy_" + id
                });

                //Sini
                var batchData = refreshBatch($(this).find("td:eq(1) select.resep-obat").val(), id);
                totalResep += parseFloat(batchData.harga);
            });

            $("#total_resep_biasa").html(number_format(totalResep, 2, ".", ",")).attr({
                "harga": totalResep
            });

            return totalResep;
        }

        function autoAturanPakai() {
            var dataAturanPakai;
            $.ajax({
                url:__HOSTAPI__ + "/Terminologi/terminologi-items/15",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    dataAturanPakai = response.response_package.response_data;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return dataAturanPakai;

        }

        function autoKategoriObat(obat) {
            var kategoriObat;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/kategori_per_obat/" + obat,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    kategoriObat = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return kategoriObat;
        }

        function refreshBatch(item, rowTarget = "", type = "resep") {
            var batchData;
            var harga_obat = 0;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_batch/" + item,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    batchData = response.response_package.response_data;
                    
                    if(batchData !== null) {
                        if(rowTarget !== "") {

                            var selectedBatchList = [];
                            var selectedProfitValue = 0;
                            var alternatedBatchList = [];
                            var uniqueBatch = {};
                            var harga_tertinggi = 0;
                            var total_kebutuhan = 0;
                            var final_price = 0;
                            var kebutuhan = 0;

                            var sortedGudangBatchApotek = [];
                            var sortedGudangBatchgFar = [];
                            var sortedGudangBatchLain = [];





                            if(type === "resep") {
                                
                                $("#batch_obat_" + rowTarget + " li").remove();

                                total_kebutuhan = parseFloat($("#resep_jlh_hari_" + rowTarget).inputmask("unmaskedvalue"));
                                kebutuhan = $("#resep_jlh_hari_" + rowTarget).inputmask("unmaskedvalue");

                                if(total_kebutuhan === 0 || isNaN(total_kebutuhan)) {
                                    $("#harga_obat_" + rowTarget).html(number_format(0, 2, ".", ",")).attr({
                                        "harga": 0
                                    });

                                    final_price = 0;
                                }


                                var rebaseStorage = [];
                                var alternateStorage = [];
                                for(bKey in batchData) {
                                    if(batchData[bKey].stok_terkini > 0) {
                                        if(batchData[bKey].gudang.uid === __GUDANG_APOTEK__) {
                                            rebaseStorage.push(batchData[bKey]);
                                        } else if(batchData[bKey].gudang.uid === __GUDANG_UTAMA__) {
                                            alternateStorage.push(batchData[bKey]);
                                        }
                                    }
                                }

                                batchData = rebaseStorage.concat(alternateStorage);
                                
                                var alternatedBatchListGFar = [];
                                var alternatedBatchListLain = [];




                                // for(bKey in batchData) {
                                //     if(batchData[bKey].gudang.uid === __GUDANG_APOTEK__) {
                                //         sortedGudangBatchApotek.push(batchData[bKey]);
                                //     } if(batchData[bKey].gudang.uid === __GUDANG_UTAMA__) {
                                //         sortedGudangBatchgFar.push(batchData[bKey]);
                                //     } else {
                                //         sortedGudangBatchLain.push(batchData[bKey]);
                                //     }
                                // }

                                //batchData = sortedGudangBatchApotek.concat(sortedGudangBatchgFar).concat(sortedGudangBatchLain);
                                //batchData = sortedGudangBatchApotek.concat(sortedGudangBatchgFar);


                                for(bKey in batchData) {

                                    if(batchData[bKey].harga > harga_tertinggi) {
                                        harga_tertinggi = batchData[bKey].harga;
                                    }

                                    if(kebutuhan > 0 && parseFloat(batchData[bKey].stok_terkini) > 0) {
                                        //if(batchData[bKey].gudang.uid === __UNIT__.gudang) {
                                        if(batchData[bKey].gudang.uid === __GUDANG_APOTEK__) {
                                            if(kebutuhan > batchData[bKey].stok_terkini) {
                                                batchData[bKey].used = parseFloat(batchData[bKey].stok_terkini);
                                                kebutuhan -= parseFloat(batchData[bKey].stok_terkini);
                                                if(uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid]  === undefined) {
                                                    selectedBatchList.push(batchData[bKey]);
                                                    uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid] = 1;
                                                }
                                            } else {
                                                batchData[bKey].used = parseFloat(kebutuhan);
                                                kebutuhan = 0;
                                                if(uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid]  === undefined) {
                                                    selectedBatchList.push(batchData[bKey]);
                                                    uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid] = 1;
                                                }
                                            }
                                        } else if(batchData[bKey].gudang.uid === __GUDANG_UTAMA__) {
                                            if(kebutuhan > batchData[bKey].stok_terkini) {
                                                batchData[bKey].used = parseFloat(batchData[bKey].stok_terkini);
                                                kebutuhan -= parseFloat(batchData[bKey].stok_terkini);
                                                if(uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid]  === undefined) {
                                                    if(batchData[bKey].gudang.uid === __GUDANG_APOTEK__) {
                                                        alternatedBatchListGFar.push(batchData[bKey]);
                                                    } else {
                                                        alternatedBatchListLain.push(batchData[bKey]);
                                                    }
                                                    //alternatedBatchList.push(batchData[bKey]);
                                                    uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid] = 1;
                                                }
                                            } else {
                                                batchData[bKey].used = parseFloat(kebutuhan);
                                                kebutuhan = 0;
                                                if(uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid]  === undefined) {
                                                    if(batchData[bKey].gudang.uid === __GUDANG_APOTEK__) {
                                                        alternatedBatchListGFar.push(batchData[bKey]);
                                                    } else {
                                                        alternatedBatchListLain.push(batchData[bKey]);
                                                    }
                                                    //alternatedBatchList.push(batchData[bKey]);
                                                    uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid] = 1;
                                                }
                                            }
                                        }
                                    }
                                }

                                alternatedBatchList = alternatedBatchListGFar.concat(alternatedBatchListLain);


                                var targettedBatch = [];

                                if(selectedBatchList.length > 0) {
                                    targettedBatch = selectedBatchList;
                                } else {
                                    targettedBatch = alternatedBatchList;
                                    alternatedBatchList = [];
                                }


                                if(item == 'fc62d22d-3aef-45b9-839f-9a84dceaaf7c') {
                                    console.log(targettedBatch);
                                }



                                //targettedBatch = selectedBatchList;


                                //Sort Batch
                                /*var sortedGudangBatchApotek = [];
                                var sortedGudangBatchgFar = [];
                                var sortedGudangBatchLain = [];

                                for(var batchSelKey in targettedBatchRaw) {
                                    if(targettedBatchRaw[batchSelKey].gudang.uid === __GUDANG_APOTEK__) {
                                        sortedGudangBatchApotek.push(targettedBatchRaw[batchSelKey]);
                                    } else if(targettedBatchRaw[batchSelKey].gudang.uid === __GUDANG_UTAMA__) {
                                        sortedGudangBatchgFar.push(targettedBatchRaw[batchSelKey]);
                                    } else {
                                        sortedGudangBatchLain.push(targettedBatchRaw[batchSelKey]);
                                    }
                                }

                                targettedBatch = sortedGudangBatchApotek.concat(sortedGudangBatchgFar).concat(sortedGudangBatchLain);*/

                                if(targettedBatch.length > 0) {
                                    var profitList = targettedBatch[0].profit
                                    for(var profKey in profitList) {
                                        if (profitList[profKey].penjamin === currentMetaData.penjamin.uid) {
                                            selectedProfitType = profitList[profKey].profit_type;
                                            selectedProfitValue = parseFloat(profitList[profKey].profit);
                                        }
                                    }

                                    var finalTotal = 0;
                                    var rawTotal = harga_tertinggi;
                                     
                                    
                                    if(selectedProfitType === "N") {
                                        finalTotal = rawTotal;
                                    } else if(selectedProfitType === "P") {
                                        finalTotal = rawTotal + (selectedProfitValue / 100 * rawTotal);
                                    } else {
                                        finalTotal = rawTotal + selectedProfitValue;
                                    }

                                    var counter_kebutuhan = total_kebutuhan;

                                    for(var batchSelKey in targettedBatch) {
                                        
                                        // if(targettedBatch[batchSelKey].barang === 'fc62d22d-3aef-45b9-839f-9a84dceaaf7c') {
                                        //     console.log(targettedBatch[batchSelKey].used + " --- " + counter_kebutuhan);
                                        // }

                                        counter_kebutuhan -= targettedBatch[batchSelKey].used;

                                        
                                        
                                        if(targettedBatch[batchSelKey].gudang.uid === __GUDANG_APOTEK__) {
                                            //$("#batch_obat_" + rowTarget).append("<li style=\"color:" + ((counter_kebutuhan === 0) ? "#cf0000" : "#12a500") + "\" batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + ((counter_kebutuhan > 0) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                            $("#batch_obat_" + rowTarget).append("<li batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + "</li>");
                                        } else {
                                            $("#batch_obat_" + rowTarget).append("<li style=\"color:" + ((counter_kebutuhan === 0) ? "#cf0000" : "#F58D00") + "\" batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + ((counter_kebutuhan > 0) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                        }
                                    }

                                    for(var batchSelKey in alternatedBatchList) {
                                        if(alternatedBatchList[batchSelKey].gudang.uid !== __GUDANG_APOTEK__) {
                                            $("#batch_obat_" + rowTarget).append("<li style=\"color:" + ((alternatedBatchList[batchSelKey].used < total_kebutuhan) ? "#cf0000" : "#12a500") + "\" batch=\"" + alternatedBatchList[batchSelKey].batch + "\"><b>[" + alternatedBatchList[batchSelKey].kode + "]</b> " + alternatedBatchList[batchSelKey].expired + " (" + alternatedBatchList[batchSelKey].used + ") - " + alternatedBatchList[batchSelKey].gudang.nama + ((alternatedBatchList[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                        } else {
                                            $("#batch_obat_" + rowTarget).append("<li style=\"color:" + ((alternatedBatchList[batchSelKey].used < total_kebutuhan) ? "#cf0000" : "#F58D00") + "\" batch=\"" + alternatedBatchList[batchSelKey].batch + "\"><b>[" + alternatedBatchList[batchSelKey].kode + "]</b> " + alternatedBatchList[batchSelKey].expired + " (" + alternatedBatchList[batchSelKey].used + ") - " + alternatedBatchList[batchSelKey].gudang.nama + ((alternatedBatchList[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                        }
                                    }
                                    
                                    $("#batch_obat_" + rowTarget).attr("harga", finalTotal);

                                    //Calculate harga
                                    $("#harga_obat_" + rowTarget).html(number_format(finalTotal * total_kebutuhan, 2, ".", ",")).attr({
                                        "harga": (finalTotal * total_kebutuhan)
                                    });

                                    final_price = (finalTotal * total_kebutuhan);
                                }





                            } else {    //Else racikan




                                $("#obat_komposisi_batch_" + rowTarget + " li").remove();

                                //racikan_jumlah_1
                                var groupExplitor = rowTarget.split("_");

                                total_kebutuhan = $("#jlh_komposisi_" + groupExplitor[0] + "_" + groupExplitor[1]).inputmask("unmaskedvalue");
                                kebutuhan = total_kebutuhan;

                                if(kebutuhan <= 0) {
                                    /*$("#jlh_komposisi_" + groupExplitor[0] + "_" + groupExplitor[1]).css({
                                        "background": "red"
                                    });*/
                                }

                                var rebaseStorage = [];
                                var alternateStorage = [];
                                for(bKey in batchData) {
                                    if(batchData[bKey].gudang.uid === __UNIT__.gudang) {
                                        rebaseStorage.push(batchData[bKey]);
                                    } else {
                                        alternateStorage.push(batchData[bKey]);
                                    }
                                }


                                batchData = rebaseStorage.concat(alternateStorage);


                                for(bKey in batchData) {
                                    if(batchData[bKey].gudang.uid === __GUDANG_APOTEK__) {
                                        sortedGudangBatchApotek.push(batchData[bKey]);
                                    } if(batchData[bKey].gudang.uid === __GUDANG_UTAMA__) {
                                        sortedGudangBatchgFar.push(batchData[bKey]);
                                    } else {
                                        sortedGudangBatchLain.push(batchData[bKey]);
                                    }
                                }

                                batchData = sortedGudangBatchApotek.concat(sortedGudangBatchgFar).concat(sortedGudangBatchLain);


                                for(bKey in batchData)
                                {
                                    if(batchData[bKey].harga > harga_tertinggi)
                                    {
                                        harga_tertinggi = batchData[bKey].harga;
                                    }

                                    if(batchData[bKey].gudang.uid === __UNIT__.gudang) {

                                        if(kebutuhan > 0 && batchData[bKey].stok_terkini > 0)
                                        {
                                            if(kebutuhan > batchData[bKey].stok_terkini)
                                            {
                                                batchData[bKey].used = parseFloat(batchData[bKey].stok_terkini);
                                            } else {
                                                batchData[bKey].used = parseFloat(kebutuhan);
                                            }
                                            kebutuhan = kebutuhan - batchData[bKey].stok_terkini;
                                            if(uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid]  === undefined) {
                                                selectedBatchList.push(batchData[bKey]);
                                                uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid] = 1;
                                            }
                                            /*if(uniqueBatch.indexOf(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid) < 0) {
                                                selectedBatchList.push(batchData[bKey]);
                                                uniqueBatch.push(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid);
                                            }*/

                                        }
                                    } else {
                                        if(kebutuhan > 0 && batchData[bKey].stok_terkini > 0)
                                        {
                                            if(kebutuhan > batchData[bKey].stok_terkini)
                                            {
                                                batchData[bKey].used = parseFloat(batchData[bKey].stok_terkini);
                                            } else {
                                                batchData[bKey].used = parseFloat(kebutuhan);
                                            }
                                            kebutuhan = kebutuhan - batchData[bKey].stok_terkini;
                                            if(uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid]  === undefined) {
                                                selectedBatchList.push(batchData[bKey]);
                                                uniqueBatch[batchData[bKey].batch + "-" + batchData[bKey].gudang.uid] = 1;
                                            }
                                            /*if(uniqueBatch.indexOf(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid) < 0) {
                                                alternatedBatchList.push(batchData[bKey]);
                                                uniqueBatch.push(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid);
                                            }*/

                                        }
                                    }
                                }


                                //Profit Manager
                                var selectedProfitType = "N";
                                var selectedProfitValue = 0;

                                var targettedBatch = [];

                                if(selectedBatchList.length > 0) {
                                    targettedBatch = selectedBatchList;
                                } else {
                                    targettedBatch = alternatedBatchList;
                                }



                                if(targettedBatch.length > 0) {
                                    var profitList = targettedBatch[0].profit
                                    for(var profKey in profitList) {
                                        if (profitList[profKey].penjamin === currentMetaData.penjamin.uid) {
                                            selectedProfitType = profitList[profKey].profit_type;
                                            selectedProfitValue = parseFloat(profitList[profKey].profit);
                                        }
                                    }

                                    var finalTotal = 0;
                                    var rawTotal = parseFloat(harga_tertinggi);

                                    if(selectedProfitType === "N") {
                                        finalTotal = rawTotal;
                                    } else if(selectedProfitType === "P") {
                                        finalTotal = rawTotal + (selectedProfitValue / 100 * rawTotal);
                                    } else {
                                        finalTotal = rawTotal + selectedProfitValue;
                                    }


                                    //Racikan session
                                    $("#obat_komposisi_batch_" + rowTarget).attr({
                                        "harga": finalTotal
                                    });


                                    for(var batchSelKey in targettedBatch) {
                                        if(targettedBatch[batchSelKey].used > 0) {
                                            if(targettedBatch[batchSelKey].gudang.uid === __GUDANG_APOTEK__) {
                                                //$("#obat_komposisi_batch_" + rowTarget).append("<li style=\"color:" + ((targettedBatch[batchSelKey].used < total_kebutuhan) ? "#cf0000" : "#12a500") + "\" batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + ((targettedBatch[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                                $("#obat_komposisi_batch_" + rowTarget).append("<li batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + "</li>");
                                            } else {
                                                $("#obat_komposisi_batch_" + rowTarget).append("<li style=\"color:" + ((targettedBatch[batchSelKey].used < total_kebutuhan) ? "#cf0000" : "#F58D00") + "\" batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + ((targettedBatch[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                            }

                                            //$("#obat_komposisi_batch_" + rowTarget).append("<li class=\"" + ((targettedBatch[batchSelKey].used < total_kebutuhan) ? "text-danger" : "text-success") + "\" batch=\"" + targettedBatch[batchSelKey].batch + "\"><b>[" + targettedBatch[batchSelKey].kode + "]</b> " + targettedBatch[batchSelKey].expired + " (" + targettedBatch[batchSelKey].used + ") - " + targettedBatch[batchSelKey].gudang.nama + ((targettedBatch[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                        }
                                    }

                                    var totalKalkulasi = 0;
                                    //Kalkulasi total harga komposisi
                                    $("#komposisi_" + groupExplitor[0] + " tbody tr").each(function() {
                                        var attrHarga = $(this).find("td:eq(1) ol").attr("harga");
                                        if (typeof attrHarga !== typeof undefined && attrHarga !== false) {
                                            var currentRacikanQty = parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));
                                            if(currentRacikanQty === 0 || isNaN(currentRacikanQty)) {
                                                totalKalkulasi += 0;
                                            } else {
                                                totalKalkulasi += parseFloat($(this).find("td:eq(1) ol").attr("harga")) * parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));
                                            }
                                        }
                                    });


                                    $("#racikan_harga_" + groupExplitor[0]).html(number_format(totalKalkulasi, 2, ".", ",")).attr({
                                        "harga": totalKalkulasi
                                    });

                                    final_price = totalKalkulasi
                                }
                            }

                            harga_obat = final_price;
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return {
                batch: batchData,
                harga: harga_obat
            };
        }

        $("body").on("keyup", ".resep_jlh_hari", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            refreshBatch($("#resep_obat_" + id).val(), id);
            totalResep = calculate_resep();
            totalRacikan = calculate_racikan();
            $("#total_biaya_obat").html("Rp. " + number_format((totalResep + totalRacikan), 2, ".", ","));
        });

        /*$("body").on("keyup", ".racikan_signa_jlh", function () {
            var groupRacikan = $(this).attr("id").split("_");
            groupRacikan = groupRacikan[groupRacikan.length - 1];
            $("#komposisi_" + groupRacikan + " tbody tr").each(function(e) {
                refreshBatch($(this).find("td:eq(1) h6").attr("uid-obat"), groupRacikan + "_" + (e + 1), "racikan");
            });
        });*/







        function autoRacikan(setter = {
            "uid": "",
            "nama": "",
            "keterangan": "",
            "signaKonsumsi": "",
            "signaTakar": "",
            "signaHari": "",
            "aturan_pakai": "",
            "iterasi": 0,
            "qty_roman": "",
            "sat_konsumsi": "",
            "item":[]
        }) {
            $("#table-resep-racikan tbody.racikan tr").removeClass("last-racikan");
            var newRacikanRow = document.createElement("TR");
            $(newRacikanRow).attr("uid", setter.uid);
            $(newRacikanRow).addClass("last-racikan racikan-master");

            var newRacikanCellID = document.createElement("TD");
            var newRacikanCellNama = document.createElement("TD");
            var newRacikanCellSignaA = document.createElement("TD");
            var newRacikanCellSignaX = document.createElement("TD");
            var newRacikanCellSignaB = document.createElement("TD");
            var newRacikanCellJlh = document.createElement("TD");
            var newRacikanCellHarga = document.createElement("TD");
            var newRacikanCellAksi = document.createElement("TD");

            var checkCopyRacikan = document.createElement("INPUT");
            $(checkCopyRacikan).attr({
                "type": "checkbox"
            }).addClass("form-control copy-racikan");

            $(newRacikanCellAksi).append(checkCopyRacikan);

            $(newRacikanCellHarga).addClass("number_style master-racikan-cell").append("<span></span>");

            $(newRacikanCellID).addClass("master-racikan-cell");
            $(newRacikanCellNama).addClass("master-racikan-cell");
            $(newRacikanCellSignaA).addClass("master-racikan-cell").css({
                "position": "relative"
            });
            $(newRacikanCellSignaX).addClass("master-racikan-cell");
            $(newRacikanCellSignaB).addClass("master-racikan-cell");
            $(newRacikanCellJlh).addClass("master-racikan-cell");
            $(newRacikanCellAksi).addClass("master-racikan-cell");

            var newRacikanNama = document.createElement("INPUT");
            $(newRacikanCellNama).append(newRacikanNama);
            $(newRacikanNama).addClass("form-control racikan_nama").css({
                "margin-bottom": "20px"
            }).attr({
                "placeholder": "Nama Racikan",
                "disabled": "disabled",
                "old-data": setter.uid,
                "roman": setter.qty_roman
            }).val(setter.nama);

            $(newRacikanCellNama).append(
                "<h6 style=\"padding-bottom: 10px;\">" +
                "Komposisi:" +
                "<button style=\"margin-left: 20px;\" uid-racikan=\"" + setter.uid + "\" class=\"btn btn-sm btn-info tambahKomposisi\"" +
                "<i class=\"fa fa-plus\"></i> Tambah" +
                "</button>" +
                "</h6>" +
                "<table class=\"table table-bordered komposisi-racikan largeDataType\" style=\"margin-top: 10px;\">" +
                "<thead class=\"thead-dark\">" +
                "<tr>" +
                "<th class=\"wrap_content\">No</th>" +
                "<th>Obat</th>" +
                /*"<th class=\"\">@</th>" +*/
                "<th class=\"wrap_content\">Jlh Terpakai</th>" +
                "<th>Kekuatan</th>" +
                "<th class=\"wrap_content\">Aksi</th>" +
                "<tr>" +
                "</thead>" +
                "<tbody class=\"komposisi-item\"></tbody>" +
                "</table>"
            );

            var newAturanPakaiRacikan = document.createElement("SELECT");

            //var dataAturanPakai = autoAturanPakai();

            $(newAturanPakaiRacikan).addClass("form-control aturan-pakai-racikan");
            var newKeteranganRacikan = document.createElement("TEXTAREA");
            //$(newRacikanCellNama).append("<span>Aturan Pakai</span>").append(newAturanPakaiRacikan).append("<span>Keterangan</span>").append(newKeteranganRacikan);
            $(newRacikanCellNama).append("<span>Keterangan / Aturan Pakai</span>").append(newKeteranganRacikan);
            if(setter.uid !== "") {
                $(newAturanPakaiRacikan).attr({
                    "uid-racikan": setter.uid
                });
            }
            // $(newAturanPakaiRacikan).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            // for(var aturanPakaiKey in dataAturanPakai) {
            //     $(newAturanPakaiRacikan).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            // }
            $(newKeteranganRacikan).addClass("form-control").attr({
                "placeholder": "Keterangan racikan"
            }).css({
                "min-height": "120px"
            }).val(setter.keterangan);

            if(parseInt(setter.iterasi) > 0) {
                $(newRacikanCellNama).append("<br /><h3 class=\"text-success text-right resep_script\" data=\"" + setter.iterasi + "\">Iter " + setter.iterasi + " &times; (" + setter.sat_konsumsi.toLowerCase() + ")</h3>");
            } else {
                $(newRacikanCellNama).append("<br /><h3 class=\"text-right\">(" + setter.sat_konsumsi.toLowerCase() + ")</h3>");
            }

            /*var newRacikanObat = document.createElement("SELECT");
            var newObatTakar = document.createElement("INPUT");
            $(newRacikanCellObat).append(newRacikanObat);
            var addAnother = load_product_resep(newRacikanObat, "");
            $(newRacikanCellObat).append("<br /><b>Takaran</b>");
            $(newRacikanCellObat).append(newObatTakar);
            $(newRacikanObat).addClass("form-control").select2();
            $(newObatTakar).addClass("form-control");*/

            var newRacikanSignaA = document.createElement("INPUT");
            $(newRacikanCellSignaA).append(newRacikanSignaA);
            $(newRacikanSignaA).addClass("form-control racikan_signa_a number_style").attr({
                "placeholder": "0"
            }).val(setter.signaKonsumsi)/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/;

            if(setter.uid !== "") {
                $(newRacikanSignaA).attr({
                    "uid-racikan": setter.uid
                });
            }

            $(newRacikanCellSignaX).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newRacikanSignaB = document.createElement("INPUT");
            $(newRacikanCellSignaB).append(newRacikanSignaB);
            $(newRacikanSignaB).addClass("form-control racikan_signa_b number_style").attr({
                "placeholder": "0"
            }).val(setter.signaTakar)/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/;

            if(setter.uid !== "") {
                $(newRacikanSignaB).attr({
                    "uid-racikan": setter.uid
                });
            }

            var newRacikanJlh = document.createElement("INPUT");
            $(newRacikanCellJlh).append(newRacikanJlh);
            $(newRacikanJlh).addClass("form-control racikan_signa_jlh number_style").attr({
                "placeholder": "0"
            }).val(setter.signaHari).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });

            if(setter.uid !== "") {
                $(newRacikanJlh).attr({
                    "uid-racikan": setter.uid
                });
            }

            var newRacikanDelete = document.createElement("BUTTON");
            //$(newRacikanCellAksi).append(newRacikanDelete);
            $(newRacikanDelete).addClass("btn btn-danger btn-sm btn-delete-racikan").html("<i class=\"fa fa-ban\"></i>");

            $(newRacikanRow).append(newRacikanCellID);
            $(newRacikanRow).append(newRacikanCellNama);
            $(newRacikanRow).append(newRacikanCellSignaA);
            $(newRacikanRow).append(newRacikanCellSignaX);
            $(newRacikanRow).append(newRacikanCellSignaB);
            $(newRacikanRow).append(newRacikanCellJlh);
            $(newRacikanRow).append(newRacikanCellHarga);
            $(newRacikanRow).append(newRacikanCellAksi);

            $("#table-resep-racikan tbody.racikan").append(newRacikanRow);
            rebaseRacikan();
        }

        function rebaseRacikan() {
            $("#table-resep-racikan > tbody.racikan > tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "row_racikan_" + id
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");

                $(this).find("td:eq(1) input.racikan_nama").attr({
                    "id": "racikan_nama_" + id
                });

                $(this).find("td:eq(1) select.aturan-pakai-racikan").attr({
                    "id": "racikan_aturan_pakai_" + id
                });

                $(this).find("td:eq(1) textarea").attr({
                    "id": "racikan_keterangan_" + id
                });

                $(this).find("td:eq(1) h3").attr({
                    "id": "racikan_iterasi_" + id
                });

                if($(this).find("td:eq(1) input") == "") {
                    $(this).find("td:eq(1) input").val("RACIKAN " + id);
                }

                $(this).find("td:eq(1) table").attr({
                    "id": "komposisi_" + id
                });

                $(this).find("td:eq(1) button.tambahKomposisi").attr({
                    "id": "tambah_komposisi_" + id
                });

                $(this).find("td:eq(2) input.racikan_signa_a").attr({
                    "id": "racikan_signaA_" + id
                });

                $(this).find("td:eq(4) input.racikan_signa_b").attr({
                    "id": "racikan_signaB_" + id
                });

                $(this).find("td:eq(5) input").attr({
                    "id": "racikan_jumlah_" + id
                });

                $(this).find("td:eq(6) span").attr({
                    "id": "racikan_harga_" + id
                });

                $(this).find("td:eq(7) button").attr({
                    "id": "racikan_delete_" + id
                });
                $(this).find("td:eq(7) input").attr({
                    "id": "racikan_copy_" + id
                });
            });
        }


        function autoKomposisi(id, setter = {}, global_qty = 0) {
            if(setter.obat != undefined || $("#komposisi_" + id + " tbody tr").length == 0 || $("#komposisi_" + id + " tbody tr:last-child td:eq(1)").html() != "") {
                var totalKomposisi = 0;
                var newKomposisiRow = document.createElement("TR");
                $(newKomposisiRow).addClass("komposisi-row");

                var newKomposisiCellID = document.createElement("TD");
                var newKomposisiCellObat = document.createElement("TD");
                var newKomposisiCellJumlah = document.createElement("TD");
                var newKomposisiCellSatuan = document.createElement("TD");
                var newKomposisiCellAksi = document.createElement("TD");

                $(newKomposisiCellID).addClass("autonum wrap_content");


                $(newKomposisiCellObat).append("<h6></h6><ol></ol>");

                var newKomposisiEdit = document.createElement("BUTTON");
                $(newKomposisiEdit).addClass("btn btn-sm btn-info btn_edit_komposisi").html("<i class=\"fa fa-pencil-alt\"></i>").attr({
                    "uid-racikan": setter.racikan
                });

                var newKomposisiDelete = document.createElement("BUTTON");
                $(newKomposisiDelete).addClass("btn btn-sm btn-danger btn_delete_komposisi").html("<i class=\"fa fa-ban\"></i>").attr({
                    "uid-racikan": setter.racikan
                });

                $(newKomposisiCellAksi).append("<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"></div>");
                $(newKomposisiCellAksi).find("div").append(newKomposisiEdit);
                $(newKomposisiCellAksi).find("div").append(newKomposisiDelete);

                var newJumlahObatRacikanPerItem = document.createElement("INPUT");
                $(newJumlahObatRacikanPerItem).addClass("form-control jlh_obat_racikan").attr({
                    "placeholder": "0",
                    "uid-racikan": setter.racikan
                }).val(global_qty).inputmask({
                    alias: 'decimal',
                    rightAlign: true,
                    placeholder: "0.00",
                    prefix: "",
                    autoGroup: false,
                    digitsOptional: true
                });

                $(newKomposisiCellJumlah).append(newJumlahObatRacikanPerItem);

                $(newKomposisiRow).append(newKomposisiCellID);
                $(newKomposisiRow).append(newKomposisiCellObat);
                $(newKomposisiRow).append(newKomposisiCellJumlah);
                $(newKomposisiRow).append(newKomposisiCellSatuan);
                $(newKomposisiRow).append(newKomposisiCellAksi);

                $("#komposisi_" + id + " tbody").append(newKomposisiRow);

                /*if($("#komposisi_" + id + " tbody tr").length == 1) {
                    //autoModal
                    prepareModal(id);
                }*/
                if(setter.obat != undefined) {
                    $(newKomposisiCellObat).find("h6").attr({
                        "uid-obat" : setter.obat
                    }).html(setter.obat_detail.nama.toUpperCase());

                    //$(newKomposisiCellJumlah).html(setter.ratio);
                    $(newKomposisiCellSatuan).html(setter.kekuatan);
                } else {
                    prepareModal(id);
                }

                totalKomposisi += rebaseKomposisi(id);

                return totalKomposisi;
            }
        }

        function load_product_resep(target, selectedData = "", appendData = true) {
            var selected = [];
            var productData = [];

            $(target).select2({
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
                    url:__HOSTAPI__ + "/Inventori/get_item_select2/" + $(".select2-search__field").val(),
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
                                    text: item.nama,
                                    id: item.uid,
                                    satuan_terkecil: item.satuan_terkecil.nama
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                if(data.satuan_terkecil != undefined) {
                    $(this).children("[value=\""+ data.id + "\"]").attr({
                        "satuan-caption": data.satuan_terkecil
                    });
                } else {
                    return false;
                }
            });

            return {
                allow: true,
                data: []
            };
        }

        function rebaseKomposisi(id) {
            var totalRacikan = 0;
            $("#komposisi_" + id + " tbody tr").each(function(e) {
                var cid = (e + 1);

                $(this).attr({
                    "id": "single_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(0)").html(cid);

                $(this).find("td:eq(1)").attr({
                    "id": "obat_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(1) ol").attr({
                    "id": "obat_komposisi_batch_" + id + "_" + cid
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "jlh_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(3)").attr({
                    "id": "takar_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(4) button:eq(0)").attr({
                    "id": "button_edit_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(4) button:eq(1)").attr({
                    "id": "button_delete_komposisi_" + id + "_" + cid
                });

                var komposisiRacikan = refreshBatch($(this).find("td:eq(1) h6").attr("uid-obat"), id + "_" + cid, "racikan");
                totalRacikan += komposisiRacikan.harga;
            });
            return totalRacikan
        }

        function prepareModal(id, setData = {
            obat: "",
            jlh: "",
            takarBulat: 1,
            takarDesimal: "",
            kekuatan: ""
        }) {
            $("#form-editor-racikan").modal("show");
            $("#modal-large-title").html($("#racikan_nama_" + id).val());

            //$("#txt_racikan_jlh").val(setData.jlh);
            //$("#txt_racikan_takar").val(setData.takar);
            $("#txt_racikan_takar").val(setData.takarDesimal);
            $("#txt_racikan_takar_bulat").val(setData.takarBulat);
            $("#txt_racikan_kekuatan").val(setData.kekuatan);

            var modalProduct = load_product_resep("#txt_racikan_obat", setData.obat, false);
            var itemData = modalProduct.data;
            var parsedItemData = [];
            for(var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }


                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: 	"<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            $("#txt_racikan_obat").addClass("form-control").select2({
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
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
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
                                var stokKeseluruhan = item.stok;
                                if(item.batch !== undefined) {
                                    var batchCheck = item.batch;
                                    for(var abat in batchCheck) {
                                        if(batchCheck[abat].gudang.uid === __GUDANG_APOTEK__) {
                                            stokApotek += parseFloat(batchCheck[abat].stok_terkini);
                                        }
                                    }
                                }

                                var colorSet = "";
                                if(stokApotek > 0) {
                                    colorSet = "#12a500";
                                } else if(stokApotek < 1 && stokKeseluruhan > 0) {
                                    colorSet = "#F58D00";
                                } else {
                                    colorSet = "#cf0000";
                                }

                                return {
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + colorSet + " !important;\">" + item.nama.toUpperCase() + "</div>",
                                    "html": 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + colorSet + " !important;\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
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
            }).val(setData.obat).trigger("change").on("select2:select", function(e) {
                var data = e.params.data;
                $(this).children("[value=\""+ data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });
            });

            if(setData.obat != "") {
                $("#txt_racikan_obat").append("<option title=\"" + setData.obat_nama + "\" value=\"" + setData.obat + "\">" + setData.obat_nama + "</option>");
                $("#txt_racikan_obat").select2("data", {id: setData.obat, text: setData.obat_nama});
                $("#txt_racikan_obat").trigger("change");
            }
        }

        $("#txt_racikan_obat").select2();

        /*$("#txt_racikan_jlh").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });*/

        var currentRacikID = 1;
        var currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length;
        var komposisiMode = "add";

        $("body").on("click", ".btn_edit_komposisi", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];

            currentRacikanActive = $(this).attr("uid-racikan");

            prepareModal(Pid, {
                obat: $("#obat_komposisi_" + Pid + "_" + thisID + " h6").attr("uid-obat"),
                obat_nama: $("#obat_komposisi_" + Pid + "_" + thisID + " h6").text(),
                takarBulat: $("#takar_komposisi_" + Pid + "_" + thisID).find("b").html(),
                takarDesimal: $("#takar_komposisi_" + Pid + "_" + thisID).find("sub").html(),
                kekuatan: $("#takar_komposisi_" + Pid + "_" + thisID).html()
            });

            currentKomposisiID = thisID;
            currentRacikID = Pid;
        });

        $("body").on("click", ".btn-delete-racikan", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            $("#row_racikan_" + thisID).remove();
            rebaseRacikan();
        });

        $("body").on("click", ".btn_delete_komposisi", function(){
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];

            var racikanUID = $(this).attr("uid-racikan");
            currentRacikanActive = racikanUID;

            Swal.fire({
                title: "Verfikasi Resep",
                text: "Hapus item komposisi?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    verifData = CheckVerifRacikan(verifData, Pid, {
                        uid: racikanUID
                    }, currentData, alasanRacikanLib);

                    $("#single_komposisi_" + Pid + "_" + thisID).remove();
                    rebaseKomposisi(Pid);
                    refreshBatch($("#obat_komposisi_" + Pid + "_" + thisID + " h6").attr("uid-obat"), Pid + "_" + thisID, "racikan");
                }
            });

            return false;
        });

        $("body").on("click", ".tambahKomposisi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentRacikID = id;
            var currentRacikanUID = $(this).attr("uid-racikan");
            currentRacikanActive = currentRacikanUID;
            currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length + 1;

            autoKomposisi(id);
        });

        $("body").on("click", "#btnSubmitKomposisi", function() {
            var infoPenjamin = "";
            $("#obat_komposisi_" + currentRacikID + "_" + currentKomposisiID + " h6")
                .html($("#txt_racikan_obat").find("option:selected").text() + infoPenjamin)
                .attr({
                    "uid-obat": $("#txt_racikan_obat").val()
                });
            $("#takar_komposisi_" + currentRacikID + "_" + currentKomposisiID).html($("#txt_racikan_kekuatan").val());
            //Tentukan Batch setelah dipilih
            refreshBatch($("#txt_racikan_obat").val(), currentRacikID + "_" + currentKomposisiID, "racikan");

            $("#form-editor-racikan").modal("hide");
            var racikanUID = currentRacikanActive;
            verifData = CheckVerifRacikan(verifData, currentRacikID, {
                uid: racikanUID
            }, currentData, alasanRacikanLib);
            calculate_racikan();
            $("#total_biaya_obat").html("Rp. " + number_format((calculate_resep() + calculate_racikan()), 2, ".", ","));
        });

        $("body").on("keyup", ".racikan_signa_a", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
            var racikanUID = $(this).attr("uid-racikan");
            verifData = CheckVerifRacikan(verifData, id, {
                uid: racikanUID
            }, currentData, alasanRacikanLib);
        });

        $("body").on("keyup", ".racikan_signa_b", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
            var racikanUID = $(this).attr("uid-racikan");
            verifData = CheckVerifRacikan(verifData, id, {
                uid: racikanUID
            }, currentData, alasanRacikanLib);
        });

        $("body").on("keyup", ".jlh_obat_racikan", function() {
            var id = $(this).attr("id").split("_");
            group = id[id.length - 2];
            id = id[id.length - 1];

            var racikanUID = $(this).attr("uid-racikan");
            //var racikanUID = currentRacikanActive;
            verifData = CheckVerifRacikan(verifData, group, {
                uid: racikanUID
            }, currentData, alasanRacikanLib);

            if($(this).inputmask("unmaskedvalue") < 1) {
                //$(this).val(1);
            }

            var racikanHargaLog = refreshBatch($("#obat_komposisi_" + group + "_" + id + " h6").attr("uid-obat"), group + "_" + id, "racikan");

            checkGenerateRacikan(group);

            totalRacikan = calculate_racikan();

            $("#total_biaya_obat").html("Rp. " + number_format((totalResep + totalRacikan), 2, ".", ","));
        });

        $("body").on("keyup", ".racikan_signa_jlh", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            var racikanUID = $(this).attr("uid-racikan");
            $("#total_biaya_obat").html("Rp. " + number_format((calculate_resep() + calculate_racikan()), 2, ".", ","));
            verifData = CheckVerifRacikan(verifData, id, {
                uid: racikanUID
            }, currentData, alasanRacikanLib);
        });

        //===========================================================================
        $("body").on("keyup", ".resep_konsumsi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            verifData = CheckVerifResep(verifData, id, {
                id: $("#resep_obat_" + id + " option:selected").val()
            }, alasanLib);
            //checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_takar", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            verifData = CheckVerifResep(verifData, id, {
                id: $("#resep_obat_" + id + " option:selected").val()
            }, alasanLib);
            //checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_jlh_hari", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            verifData = CheckVerifResep(verifData, id, {
                id: $("#resep_obat_" + id + " option:selected").val()
            }, alasanLib);
            //checkGenerateResep(id);
        });

        $("body").on("select2:select", ".resep-obat", function(e) {
            var data = e.params.data;

            $(this).children("[value=\""+ data["id"] + "\"]").attr({
                "data-value": data["data-value"],
                "penjamin-list": data["penjamin-list"],
                "satuan-caption": data["satuan-caption"],
                "satuan-terkecil": data["satuan-terkecil"]
            });

            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            //Reset Set Data
            var oldDataObat = $(this).attr("old-data");
            for(var az in verifData.resep) {
                if(az !== oldDataObat) {
                    delete verifData.resep[az];
                }
            }

            verifData = CheckVerifResep(verifData, id, data, alasanLib);

            if($(this).val() != "none") {
                var dataKategoriPerObat = autoKategoriObat(data['id']);
                var kategoriObatDOM = "";
                if(dataKategoriPerObat.length > 0) {
                    for(var kategoriObatKey in dataKategoriPerObat) {
                        if(
                            dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                            dataKategoriPerObat[kategoriObatKey].kategori !== null
                        ) {
                            kategoriObatDOM += "<span class=\"badge badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                        }
                    }
                }

                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />" + kategoriObatDOM);

                var penjaminAvailable = ($(this).find("option:selected").attr("penjamin-list") !== undefined) ? $(this).find("option:selected").attr("penjamin-list").split(",") : [];
                //checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);

                var satuanCaption = $(this).find("option:selected").attr("satuan-caption");
                $("#resep_satuan_" + id).html(satuanCaption);
                totalResep = rebaseResep();
            } else {
                $("#resep_obat_" + id).parent().find("div.penjamin-container").html("");
                $("#resep_satuan_" + id).html("");
                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />");
            }
        });

        $("body").on("click", ".resep_delete", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(!$("#resep_row_" + id).hasClass("last-resep")) {
                $("#resep_row_" + id).remove();
            }

            totalResep = rebaseResep();
            //$("#table-resep tbody tr").each(function(e));
        });

        function checkGenerateRacikan(id = 0) {
            if($(".last-racikan").length === 0) {
                //autoRacikan();
                //alert();
                //alert();
            } else {
                var obat = $("#racikan_nama_" + id).val();
                var jlh_obat = $("#racikan_jumlah_" + id).inputmask("unmaskedvalue");
                /*var signa_konsumsi = $("#racikan_signaA_" + id).inputmask("unmaskedvalue");
                var signa_hari = $("#racikan_signaB_" + id).inputmask("unmaskedvalue");*/
                var signa_konsumsi = $("#racikan_signaA_" + id).val();
                var signa_hari = $("#racikan_signaB_" + id).val();

                if(
                    parseFloat(jlh_obat) > 0 &&
                    /*parseFloat(signa_konsumsi) > 0 &&
                    parseFloat(signa_hari) > 0 &&*/
                    signa_konsumsi !== "" &&
                    signa_hari !== "" &&
                    obat != null &&
                    $("#row_racikan_" + id).hasClass("last-racikan")
                ) {

                    //autoRacikan();
                }
            }
        }

        function CheckVerifResep(newData, id, data, alasanLib = {}) {
            if(newData.resep[data.id] === undefined) {
                newData.resep[data.id] = {
                    "aturan_pakai": $("#resep_obat_aturan_pakai_" + id + " option:selected").val(),
                    /*"signaKonsumsi": $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue"),
                    "signaTakar": $("#resep_signa_takar_" + id).inputmask("unmaskedvalue"),*/
                    "signaKonsumsi": $("#resep_signa_konsumsi_" + id).val(),
                    "signaTakar": $("#resep_signa_takar_" + id).val(),
                    "signaHari": $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")
                };
            } else {
                newData.resep[data.id] = {
                    "aturan_pakai": $("#resep_obat_aturan_pakai_" + id + " option:selected").val(),
                    /*"signaKonsumsi": $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue"),
                    "signaTakar": $("#resep_signa_takar_" + id).inputmask("unmaskedvalue"),*/
                    "signaKonsumsi": $("#resep_signa_konsumsi_" + id).val(),
                    "signaTakar": $("#resep_signa_takar_" + id).val(),
                    "signaHari": $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")
                };
            }

            //Compare New and Old Data
            //console.log($("#resep_obat_" + id).attr("old-data") + " /// " + data.id);
            if($("#resep_obat_" + id).attr("old-data") !== data.id) {
                createResepChangeReason(id, alasanLib);
            } else {
                /*console.log("Compare aturan pakai : " + (parseFloat($("#resep_obat_aturan_pakai_" + id + " option:selected").val()) === parseFloat($("#resep_obat_aturan_pakai_" + id).attr("old-data"))));
                console.log("Compare konsumsi : " + (parseFloat($("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_konsumsi_" + id).attr("old-data"))));
                console.log("Compare takar : " + (parseFloat($("#resep_signa_takar_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_takar_" + id).attr("old-data"))));
                console.log("Compare hari : " + (parseFloat($("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_jlh_hari_" + id).attr("old-data"))));*/
                if(
                    parseFloat($("#resep_obat_aturan_pakai_" + id + " option:selected").val()) === parseFloat($("#resep_obat_aturan_pakai_" + id).attr("old-data")) &&
                    /*parseFloat($("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_konsumsi_" + id).attr("old-data")) &&
                    parseFloat($("#resep_signa_takar_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_takar_" + id).attr("old-data")) &&*/
                    $("#resep_signa_konsumsi_" + id).val() === $("#resep_signa_konsumsi_" + id).attr("old-data") &&
                    $("#resep_signa_takar_" + id).val() === $("#resep_signa_takar_" + id).attr("old-data") &&
                    parseFloat($("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_jlh_hari_" + id).attr("old-data"))
                ) {
                    $("#alasan_" + id).animate({
                        "left": "50px",
                        "opacity": "0"
                    }, function() {
                        $("#alasan_" + id).remove();
                    });
                } else {
                    createResepChangeReason(id, alasanLib);
                }
            }

            return newData;
        }

        function createResepChangeReason(autonum, alasanLib = {}) {
            if($("#alasan_" + autonum).length === 0) {
                var reasonText = document.createElement("TEXTAREA");
                var totalWidth = 0;
                for(var a = 2; a <= 7; a++) {
                    totalWidth += $("#resep_row_" + autonum + " td:eq(" + a + ")").width();
                }

                $(reasonText).css({
                    "position": "absolute",
                    "bottom": "50px",
                    "left": "1rem",
                    "right": "1rem",
                    "top": "auto",
                    "width": totalWidth + "px",
                    "height": $("#resep_row_" + autonum + " td:eq(1) textarea").height() + "px",
                    "resize": "none"
                }).addClass("resep-reason form-control").attr({
                    "id" : "alasan_" + autonum,
                    "placeholder": "Alasan Ubah Resep (WAJIB)"
                }).val((alasanLib["alasan_" + autonum] !== undefined) ? alasanLib["alasan_" + autonum].text : "");
                $("#resep_row_" + autonum + " td:eq(2)").append(reasonText);
            }
        }

        $("body").on("keyup", ".racikan-reason", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(alasanRacikanLib["alasan_racikan_" + id] !== undefined) {
                alasanRacikanLib["alasan_racikan_" + id] = {
                    text: "",
                    racikan: $("#racikan_nama_" + id).attr("old-data")
                };
            }

            alasanRacikanLib["alasan_racikan_" + id] = {
                text: $(this).val(),
                racikan: $("#racikan_nama_" + id).attr("old-data")
            };
        });

        $("body").on("keyup", ".resep-reason", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(alasanLib["alasan_" + id] !== undefined) {
                alasanLib["alasan_" + id] = {
                    text: "",
                    obat: $("#resep_obat_" + id).attr("old-data")
                };
            }

            alasanLib["alasan_" + id] = {
                text: $(this).val(),
                obat: $("#resep_obat_" + id).attr("old-data")
            };
        });

        function createRacikanChangeReason(autonum, alasanLib = {}) {
            if($("#alasan_racikan_" + autonum).length === 0) {
                var reasonText = document.createElement("TEXTAREA");
                var totalWidth = 0;
                for(var a = 2; a <= 6; a++) {
                    totalWidth += $("#row_racikan_" + autonum + " > td:eq(" + a + ")").width();
                }

                $(reasonText).css({
                    "position": "absolute",
                    "bottom": "150px",
                    "left": "1rem",
                    "right": "1rem",
                    "top": "auto",
                    "width": totalWidth + "px",
                    "height": $("#row_racikan_" + autonum + " td:eq(1) textarea").height() + "px",
                    "resize": "none"
                }).addClass("racikan-reason form-control").attr({
                    "id" : "alasan_racikan_" + autonum,
                    "placeholder": "Alasan Ubah Racikan (WAJIB)"
                }).val((alasanLib["alasan_racikan_" + autonum] !== undefined) ? alasanLib["alasan_racikan_" + autonum].text : "");
                $("#racikan_signaA_" + autonum).parent().append(reasonText);
            }
        }

        function CheckVerifRacikan(newData, id, data, oldData, alasanLib = {}) {
            if(data.uid === undefined) {
                console.log("False idenfier");
            } else {
                var oldRacikan = oldData.racikan;
                var itemNew = [];
                $("#komposisi_" + id + " tbody tr").each(function(e) {
                    var komposisiID = (e + 1);
                    itemNew.push({
                        obat: $(this).find("td:eq(1) h6").attr("uid-obat"),
                        kekuatan: $("#takar_komposisi_" + id + "_" + komposisiID).html(),
                        jumlah: $("#jlh_komposisi_" + id +"_" + komposisiID).inputmask("unmaskedvalue")
                    });
                });

                if(newData.racikan[data.uid] === undefined) {
                    newData.racikan[data.uid] = {
                        signaKonsumsi: $("#racikan_signaA_" + id).inputmask("unmaskedvalue"),
                        signaTakar: $("#racikan_signaB_" + id).inputmask("unmaskedvalue"),
                        signaHari: $("#racikan_jumlah_" + id).inputmask("unmaskedvalue"),
                        item: [],
                        aturan_pakai: $("#racikan_aturan_pakai_" + id + " option:selected").val()
                    };
                } else {
                    newData.racikan[data.uid] = {
                        signaKonsumsi: $("#racikan_signaA_" + id).inputmask("unmaskedvalue"),
                        signaTakar: $("#racikan_signaB_" + id).inputmask("unmaskedvalue"),
                        signaHari: $("#racikan_jumlah_" + id).inputmask("unmaskedvalue"),
                        item: itemNew,
                        aturan_pakai: $("#racikan_aturan_pakai_" + id + " option:selected").val()
                    };
                }



                var isSame = true;
                if(oldRacikan[data.uid] === undefined) {
                    //createRacikanChangeReason(id, alasanLib);
                    isSame = false;
                } else {
                    if(
                        parseFloat(oldRacikan[data.uid].signaKonsumsi) === parseFloat($("#racikan_signaA_" + id).inputmask("unmaskedvalue")) &&
                        parseFloat(oldRacikan[data.uid].signaTakar) === parseFloat($("#racikan_signaB_" + id).inputmask("unmaskedvalue")) &&
                        parseFloat(oldRacikan[data.uid].signaHari) === parseFloat($("#racikan_jumlah_" + id).inputmask("unmaskedvalue")) &&
                        parseFloat(oldRacikan[data.uid].aturan_pakai) === parseFloat($("#racikan_aturan_pakai_" + id).inputmask("unmaskedvalue"))
                    ) {
                        if(newData.racikan[data.uid].item.length !== oldRacikan[data.uid].item.length) {
                            isSame = false;
                        } else {
                            var dataCheckNew = {};

                            for(var b in newData.racikan[data.uid].item) {
                                if(dataCheckNew[newData.racikan[data.uid].item[b].obat] === undefined) {
                                    dataCheckNew[newData.racikan[data.uid].item[b].obat] =  {
                                        kekuatan: newData.racikan[data.uid].item[b].kekuatan,
                                        jumlah: newData.racikan[data.uid].item[b].jumlah
                                    };
                                }
                            }

                            if(Object.keys(dataCheckNew).length === 0 && dataCheckNew.constructor === Object) {
                                isSame = true;
                                /*$("#alasan_racikan_" + id).animate({
                                    "left": "50px",
                                    "opacity": "0"
                                }, function() {
                                    $("#alasan_racikan_" + id).remove();
                                });*/
                            } else {
                                for(var c in oldRacikan[data.uid].item) {
                                    if(dataCheckNew[oldRacikan[data.uid].item[c].obat] === undefined) {
                                        isSame = false;
                                        break;
                                    } else {
                                        if(
                                            dataCheckNew[oldRacikan[data.uid].item[c].obat].kekuatan === oldRacikan[data.uid].item[c].kekuatan &&
                                            parseFloat(dataCheckNew[oldRacikan[data.uid].item[c].obat].jumlah) === parseFloat(oldRacikan[data.uid].item[c].jumlah)
                                        ) {
                                            isSame = true;
                                        } else {
                                            isSame = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        isSame = false;
                    }
                }

                if(isSame) {
                    $("#alasan_racikan_" + id).animate({
                        "left": "50px",
                        "opacity": "0"
                    }, function() {
                        $("#alasan_racikan_" + id).remove();
                    });
                } else {
                    createRacikanChangeReason(id, alasanLib);
                }
            }
            calculate_racikan();
            return newData;
        }

        function CompareVerif(oldData, newData) {
            var changed = false;

            if(
                Object.keys(newData.resep).length === 0 && newData.resep.constructor === Object &&
                Object.keys(newData.racikan).length === 0 && newData.racikan.constructor === Object
            ) {
                changed = false
            } else {
                for(var a in newData.resep) {
                    if(oldData.resep[a] === undefined) {
                        changed = true;
                        break;
                    } else {
                        if(
                            parseFloat(newData.resep[a].aturan_pakai) === parseFloat(oldData.resep[a].aturan_pakai) &&
                            parseFloat(newData.resep[a].signaKonsumsi) === parseFloat(oldData.resep[a].signaKonsumsi) &&
                            parseFloat(newData.resep[a].signaTakar) === parseFloat(oldData.resep[a].signaTakar) &&
                            parseFloat(newData.resep[a].signaHari) === parseFloat(oldData.resep[a].signaHari)
                        ) {
                            changed = false;
                        } else {
                            changed = true;
                            break;
                        }
                    }
                }

                if(!changed) {
                    for(var a in newData.racikan) {
                        if(oldData.racikan[a] === undefined) {
                            changed = true;
                            break;
                        } else {
                            if(
                                parseFloat(newData.racikan[a].aturan_pakai) === parseFloat(oldData.racikan[a].aturan_pakai) &&
                                parseFloat(newData.racikan[a].signaKonsumsi) === parseFloat(oldData.racikan[a].signaKonsumsi) &&
                                parseFloat(newData.racikan[a].signaTakar) === parseFloat(oldData.racikan[a].signaTakar) &&
                                parseFloat(newData.racikan[a].signaHari) === parseFloat(oldData.racikan[a].signaHari)
                            ) {
                                var dataCheckNew = {};
                                if(newData.racikan[a].item.length === oldData.racikan[a].item.length) {
                                    for(var b in newData.racikan[a].item) {
                                        if(dataCheckNew[newData.racikan[a].item[b].obat] === undefined) {
                                            dataCheckNew[newData.racikan[a].item[b].obat] =  {
                                                kekuatan: newData.racikan[a].item[b].kekuatan,
                                                jumlah: newData.racikan[a].item[b].jumlah
                                            };
                                        }
                                    }

                                    if(Object.keys(dataCheckNew).length === 0 && dataCheckNew.constructor === Object) {
                                        changed = false;
                                    } else {
                                        for(var c in oldData.racikan[a].item) {
                                            if(dataCheckNew[oldData.racikan[a].item[c].obat] === undefined) {
                                                changed = true;
                                                break;
                                            } else {
                                                if(
                                                    dataCheckNew[oldData.racikan[a].item[c].obat].kekuatan === oldData.racikan[a].item[c].kekuatan &&
                                                    parseFloat(dataCheckNew[oldData.racikan[a].item[c].obat].jumlah) === parseFloat(oldData.racikan[a].item[c].jumlah)
                                                ) {
                                                    changed = false;
                                                } else {
                                                    changed = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    changed = true;
                                    break;
                                }
                            } else {
                                changed = true;
                                break;
                            }
                        }
                    }
                }
            }

            return changed;
        }

        $("body").on("change", ".aturan-pakai", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            verifData = CheckVerifResep(verifData, id, {
                id: $("#resep_obat_" + id + " option:selected").val()
            }, alasanLib);
        });

        $("body").on("change", ".aturan-pakai-racikan", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var racikanUID = $(this).attr("uid-racikan");

            verifData = CheckVerifRacikan(verifData, id, {
                uid: racikanUID
            }, currentData, alasanRacikanLib);
        });

        $("#btnSubmitAlasanUbah").click(function() {
            alasanUbah = $("#alasan-ubah-resep").val();
            if(alasanUbah !== "") {
                simpanDataVerifikasi(alasanLib, alasanRacikanLib);
            } else {
                Swal.fire(
                    "Verifikasi Gagal!",
                    "Alasan ubah harus diisi",
                    "warning"
                ).then((result) => {
                    $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                });
            }
        });

        $("#btnSelesai").click(function() {
            $(this).attr({
                "disabled": "disabled"
            }).addClass("btn-warning").removeClass("btn-info");
            $("#alasan-ubah-resep").val(alasanUbah);
            if(CompareVerif(currentData, verifData)) {
                $("#form-alasan-ubah").modal("show");
            } else {
                simpanDataVerifikasi(alasanLib, alasanRacikanLib);
            }
        });

        $("#form-alasan-ubah").on('hidden.bs.modal', function () {
            $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
        });

        function simpanDataVerifikasi(alasanLib, alasanRacikanLib) {
            var allowVerifReason = false;
            var allowVerifRacikanReason = false;

            if($(".resep-reason").length === 0 && $(".racikan-reason").length === 0) {
                allowVerifReason = true;
                allowVerifRacikanReason = true;
            } else {
                if($(".resep-reason").length === 0) {
                    allowVerifReason = true;
                } else {
                    $(".resep-reason").each(function(e) {
                        var a = (e + 1);
                        if($("#alasan_" + a).val() === "") {
                            allowVerifReason = false;
                            return false;
                        } else {
                            allowVerifReason = true;
                        }
                    });
                }

                if($(".racikan-reason").length === 0) {
                    allowVerifRacikanReason = true;
                } else {
                    $(".racikan-reason").each(function(e) {
                        var b = (e + 1);
                        if($("#alasan_racikan_" + b).val() === "") {
                            allowVerifRacikanReason = false;
                            return false;
                        } else {
                            allowVerifRacikanReason = true;
                        }
                    });
                }
            }

            /*alert($(".resep-reason").length);
            alert($(".racikan-reason").length);
            alert(allowVerifReason);
            alert(allowVerifRacikanReason);*/

            if(allowVerifReason && allowVerifRacikanReason) {
                Swal.fire({
                    title: "Verfikasi Resep",
                    text: "Pastikan semua obat sudah sesuai dan stok mencukupi. Data sudah benar?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        //Populate Resep

                        var allowSave = false;
                        var kajianCheck = false;
                        var kajian = populateAllKajian();

                        for(var  zz in kajian) {
                            if(kajian[zz] === "" || kajian[zz] === "") {
                                allowSave = false;
                                kajianCheck = false;
                                break;
                            } else {
                                allowSave = true;
                                kajianCheck = true;
                            }
                        }

                        if(allowSave) {
                            var resepItem = [];
                            if($("#table-resep tbody tr").length === 1 && $("#table-resep tbody tr:eq(1)").hasClass("no-resep")) {
                                allowSave = true;
                            }

                            $("#table-resep tbody tr").each(function(e) {
                                if(!$(this).hasClass("no-resep")) {
                                    var resepVerifIDSave = (e + 1);
                                    var obat = $(this).find("td:eq(1) select:eq(0)").val();
                                    if(obat !== null) {
                                        
                                        if($(this).find("td:eq(1) ol li").length === 0) {
                                            if(parseFloat($(this).find("td:eq(5) input").inputmask("unmaskedvalue")) === 0) {
                                                allowSave = true;
                                            } else {
                                                allowSave = false;
                                                return false;
                                            }
                                        } else {
                                            $(this).find("td:eq(1) ol li").each(function() {
                                                if($(this).find("i").hasClass("text-danger")) {
                                                    if(currentStatusOpname === "O") {
                                                        allowSave = true;
                                                    } else {
                                                        if(parseFloat($(this).find("td:eq(5) input").inputmask("unmaskedvalue")) === 0) {
                                                            allowSave = true;
                                                        } else {
                                                            allowSave = false;
                                                            return false;
                                                        }
                                                    }
                                                } else {
                                                    allowSave = true;
                                                }
                                            });
                                        }

                                        resepItem.push({
                                            "obat": $(this).find("td:eq(1) select:eq(0)").val(),
                                            /*"signa_qty": parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue")),
                                            "signa_pakai": parseFloat($(this).find("td:eq(4) input").inputmask("unmaskedvalue")),*/
                                            "signa_qty": $(this).find("td:eq(2) input").val(),
                                            "signa_pakai": $(this).find("td:eq(4) input").val(),
                                            "jumlah": parseFloat($(this).find("td:eq(5) input").inputmask("unmaskedvalue")),
                                            "harga": parseFloat($(this).find("td:eq(1) ol").attr("harga")),
                                            "aturan_pakai": $(this).find("td:eq(1) select:eq(1)").val(),
                                            "keterangan": $(this).find("td:eq(1) textarea").val(),
                                            "alasan_ubah": ($("#alasan_" + resepVerifIDSave).length > 0) ? $("#alasan_" + resepVerifIDSave).val() : ""
                                        });
                                    }
                                }
                            });

                            if(allowSave) {
                                var racikanItem = [];
                                $("#table-resep-racikan > tbody > tr").each(function(e) {
                                    var racikanVerifIDSave = (e + 1);
                                    var racikan_nama = $(this).find("td:eq(1) input").val();
                                    var qtyRacikan = parseFloat($(this).find("td input.racikan_signa_jlh").inputmask("unmaskedvalue"));
                                    if(racikan_nama !== undefined && racikan_nama !== "") {
                                        var komposisi = [];
                                        $(this).find("td:eq(1) table tbody tr").each(function() {
                                            var hargaPerObatRacikan = 0;
                                            if($(this).find("td:eq(1) ol").length > 0) {
                                                hargaPerObatRacikan = $(this).find("td:eq(1) ol").attr("harga");

                                                if($(this).find("td:eq(1) ol li").length === 0) {
                                                    if(parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue")) === 0) {
                                                        allowSave = true;
                                                    } else {
                                                        allowSave = false;
                                                        return false;
                                                    }
                                                } else {
                                                    $(this).find("td:eq(1) ol li").each(function() {
                                                        if(currentStatusOpname === "O") {
                                                            allowSave = true;
                                                        } else {
                                                            if($(this).find("i").hasClass("text-danger")) {
                                                                
                                                                if(parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue")) === 0) {
                                                                    allowSave = true;
                                                                } else {
                                                                    allowSave = false;
                                                                    return false;
                                                                }
                                                            } else {
                                                                allowSave = true;
                                                            }
                                                        }
                                                    });
                                                }
                                            }

                                            komposisi.push({
                                                "obat": $(this).find("td:eq(1) h6").attr("uid-obat"),
                                                "jumlah": parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue")),
                                                "kekuatan": $(this).find("td:eq(3)").html(),
                                                "harga": parseFloat(hargaPerObatRacikan)
                                            });

                                            // if($(this).find("td:eq(2) input").inputmask("unmaskedvalue") < 1) {
                                            //     allowSave = false;
                                            //     return  false;
                                            // }
                                        });

                                        racikanItem.push({
                                            "racikan_uid": $(this).attr("uid"),
                                            "racikan_nama": racikan_nama,
                                            "racikan_komposisi": komposisi,
                                            "alasan_ubah": ($("#alasan_racikan_" + racikanVerifIDSave).length > 0) ? $("#alasan_racikan_" + racikanVerifIDSave).val() : "",
                                            "aturan_pakai": $(this).find("td:eq(1) select").val(),
                                            "keterangan": $(this).find("td:eq(1) textarea").val(),
                                            /*"signa_qty": parseFloat($(this).find("td.master-racikan-cell:eq(2) input").inputmask("unmaskedvalue")),
                                            "signa_pakai": parseFloat($(this).find("td.master-racikan-cell:eq(4) input").inputmask("unmaskedvalue")),*/
                                            "signa_qty": $(this).find("td.master-racikan-cell:eq(2) input").val(),
                                            "signa_pakai": $(this).find("td.master-racikan-cell:eq(4) input").val(),
                                            "harga": parseFloat($(this).find("td.master-racikan-cell:eq(6) span").html().replace(/(,)/g, "")),
                                            "jumlah": parseFloat($(this).find("td.master-racikan-cell:eq(5) input").inputmask("unmaskedvalue"))
                                        });
                                    }
                                });
                            } else {
                                console.log("Disallow Save Resep");
                            }
                        }

                        if(allowSave) {
                            
                            $.ajax({
                                url:__HOSTAPI__ + "/Apotek",
                                async:false,
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type:"POST",
                                data: {
                                    request: "verifikasi_resep_2",
                                    uid: __PAGES__[3],
                                    alasan_ubah: $("#alasan-ubah-resep").val(),
                                    alasan_resep: alasanLib,
                                    alasan_racikan: alasanRacikanLib,
                                    asesmen:currentAsesmen,
                                    kunjungan: currentMetaData.kunjungan,
                                    antrian:currentMetaData.uid,
                                    pasien:currentMetaData.pasien.uid,
                                    penjamin: currentMetaData.penjamin.uid,
                                    resep: resepItem,
                                    racikan: racikanItem,
                                    departemen: currentMetaData.departemen.uid,
                                    kajian: kajian
                                },
                                success:function(response) {
                                    $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");

                                    if(response.response_package.antrian.response_result > 0) {
                                        if(currentMetaData.departemen.uid === __POLI_IGD__) {
                                            Swal.fire(
                                                "Verifikasi Berhasil!",
                                                "Silahkan minta pasien menunggu proses persiapan obat",
                                                "success"
                                            ).then((result) => {
                                                push_socket(__ME__, "antrian_apotek_baru", "*", "Permintaan Resep Baru IGD", "warning").then(function() {
                                                    location.href = __HOSTNAME__ + "/apotek/resep/";
                                                });
                                            });
                                        } else {
                                            if(currentMetaData.penjamin.uid === __UIDPENJAMINUMUM__) {
                                                Swal.fire(
                                                    "Verifikasi Berhasil!",
                                                    "Silahkan pasien menuju kasir",
                                                    "success"
                                                ).then((result) => {
                                                    push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya obat baru", "warning").then(function() {
                                                        location.href = __HOSTNAME__ + "/apotek/resep/";
                                                    });
                                                });
                                            } else {
                                                Swal.fire(
                                                    "Verifikasi Berhasil!",
                                                    "Silahkan minta pasien menunggu proses persiapan obat",
                                                    "success"
                                                ).then((result) => {
                                                    push_socket(__ME__, "antrian_apotek_baru", "*", "Permintaan Resep Baru BPJS", "warning").then(function() {
                                                        location.href = __HOSTNAME__ + "/apotek/resep/";
                                                    });
                                                });
                                            }
                                        }
                                        $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                                    } else {
                                        console.log(response);
                                        $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                                    }
                                },
                                error: function(response) {
                                    $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                                    console.log(response);
                                }
                            });
                        } else {
                            if(!kajianCheck) {
                                Swal.fire(
                                    "Verifikasi Gagal!",
                                    "Pastikan semua obat memiliki stok tersedia dan tidak bernilai kosong. Harap isi kajian resep",
                                    "warning"
                                ).then((result) => {
                                    $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                                    $("#form-alasan-ubah").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    "Verifikasi Gagal!",
                                    "Pastikan semua obat memiliki stok tersedia dan tidak bernilai kosong",
                                    "warning"
                                ).then((result) => {
                                    $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                                    $("#form-alasan-ubah").modal("hide");
                                });
                            }

                            $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                        }
                    } else {
                        $("#form-alasan-ubah").modal("hide");
                        $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                    }
                });
            } else {
                Swal.fire(
                    "Verifikasi Gagal!",
                    "Semua alasan perubahan resep dan racikan harus diisi",
                    "warning"
                ).then((result) => {
                    $("#btnSelesai").prop("disabled", false).removeClass("btn-warning").addClass("btn-success");
                    $("#form-alasan-ubah").modal("hide");
                });
            }
        }

        $("#btnCopyResep").click(function() {
            var itemP = [];

            //Ambil Semua Resep yang dicentang
            $(".copy-resep").each(function(e) {
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];
                var me = $(this);

                var regX = /(<([^>]+)>)/ig;
                var el = document.createElement("DIV");
                $(el).html($("#resep_obat_" + id + " option:selected").text());
                var obat = isHTML($("#resep_obat_" + id + " option:selected").text()) ? $(el).find("div").html() : $("#resep_obat_" + id + " option:selected").text();
                /*var signaA = $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue");
                var signaB = $("#resep_signa_takar_" + id).inputmask("unmaskedvalue");*/
                var signaA = $("#resep_signa_konsumsi_" + id).val();
                var signaB = $("#resep_signa_takar_" + id).val();
                var jumlah = $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue");
                var old_jumlah = $("#resep_jlh_hari_" + id).attr("old-data");
                var konsumsi = $("#resep_obat_aturan_pakai_" + id + " option:selected").html();
                var keterangan = $("#keterangan_resep_obat_" + id).text();
                var iterasi = $("#iterasi_resep_obat_" + id).attr("data");
                var roman = $("#resep_obat_" + id).attr("roman");
                var sath = ($("#iterasi_resep_obat_" + id).attr("sath") !== undefined) ? $("#iterasi_resep_obat_" + id).attr("sath") : "";
                itemP.push({
                    obat: [obat],
                    signa: "<b class=\"resep_script\"><span class=\"integral_sign\">&int;</span> " + signaA + " dd. " + signaB + "</b>",
                    konsumsi: konsumsi,
                    keterangan: keterangan,
                    jumlah: jumlah,
                    old_jumlah: old_jumlah,
                    iterasi: iterasi,
                    detOrig: (me.is(":checked")) ? "Y" : "N",
                    roman: roman,
                    sath: sath
                });
            });



            $(".copy-racikan").each(function() {
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];
                var me = $(this);

                var obatList = [];
                /*var signaA = $("#racikan_signaA_" + id).inputmask("unmaskedvalue");
                var signaB = $("#racikan_signaB_" + id).inputmask("unmaskedvalue");*/
                var signaA = $("#racikan_signaA_" + id).val();
                var signaB = $("#racikan_signaB_" + id).val();
                var jumlah = $("#racikan_jumlah_" + id).inputmask("unmaskedvalue");
                var old_jumlah = $("#racikan_jumlah_" + id).attr("old-data");
                var konsumsi = $("#racikan_aturan_pakai_" + id + " option:selected").html();
                var keterangan = $("#racikan_keterangan_" + id).val();
                var iterasi = $("#racikan_iterasi_" + id).attr("data");
                var roman = $("#racikan_nama_" + id).attr("roman");


                $("#komposisi_" + id + " tbody tr").each(function() {
                    var obat = $(this).find("td:eq(1) h6").html();
                    var kekuatan = $(this).find("td:eq(3)").html();

                    obatList.push(obat + " <b>" + kekuatan + "</b>");

                });

                itemP.push({
                    obat: obatList,
                    signa: "<b class=\"resep_script\"><span class=\"integral_sign\">&int;</span> " + signaA + " dd. " + signaB + "</b>",
                    konsumsi: konsumsi,
                    keterangan: keterangan,
                    jumlah: jumlah,
                    old_jumlah: old_jumlah,
                    roman: roman,
                    iterasi: iterasi,
                    detOrig: (me.is(":checked")) ? "Y" : "N",
                    sath: ""
                });
            });
            //Ambil Semua Racikan yang dicentang

            $("#copy-resep-report").html("");
            $("#form-copy-resep").modal("show");

            if(parseInt($("#iterasi-resep").html()) > 0) {
                $("#iter-copy-resep").html("Iter " + $("#iterasi-resep").html() + " &times;");
            } else {
                $("#iter-copy-resep").html("Ne Iter");
            }

            for(var a in itemP) {
                var obatList = "";
                for(var b in itemP[a].obat) {
                    obatList += "<h5 style=\"color: #000 !important\">" + itemP[a].obat[b] + "</h5>";
                }

                $("#copy-resep-report").append("<tr>" +
                    "<td class=\"resep_script\">R/</td>" +
                    "<td style=\"padding-bottom: 1cm !important; position: relative; color: #000 !important;\">" +
                    obatList +
                    "<h5 class=\"text-right resep_script\">" + " <b>" + itemP[a].roman + "</b><br />" +((parseInt(itemP[a].iterasi) > 0) ? ("Iter " + itemP[a].iterasi + " &times;") : "") + "</h5>" +
                    "<h4>" + itemP[a].signa + ((itemP[a].sath !== "") ? (" <b class=\"resep_script\">da. In " + itemP[a].sath.toLowerCase()) + "</b>" : "") + "</h4>" +
                    "<h6 class=\"text-right resep_script\" style=\"border-bottom: dashed 1px #000; margin-bottom: 10px\">" + ((parseInt(itemP[a].jumlah) > 0) ? ((itemP[a].jumlah === itemP[a].old_jumlah) ? "" : "det " + itemP[a].jumlah) : "ne det") + "</h6>" +
                    "</td>" +
                    "</tr>");
            }
        });

        $("#btnCetakCopyResep").click(function() {
            var dataCetak = $("#copy-resep-cetak").html();
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/resep_copy.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__: __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__.toUpperCase(),
                    __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__.toUpperCase(),
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __PC_IDENT__: __PC_IDENT__,
                    __PC_CUSTOMER_EMAIL__: __PC_CUSTOMER_EMAIL__,
                    __PC_CUSTOMER_ADDRESS_SHORT__: __PC_CUSTOMER_ADDRESS_SHORT__.toUpperCase(),
                    dataCetak: dataCetak
                },
                success: function(response) {
                    var printResepContainer = document.createElement("DIV");
                    $(printResepContainer).html(response);

                    /*var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=" + screen.width + ",height=" + screen.height + ",top=0,left=0");
                    win.document.body.innerHTML = $(printResepContainer).html();*/



                    $(printResepContainer).printThis({
                        loadCSS: "template/assets/css/app.css",
                        header: null,
                        footer: null,
                        pageTitle: "COPY_RESEP_" + targetKodeResep,
                        afterPrint: function() {
                            //
                        }
                    });

                },
                error: function(response) {
                    //
                }
            });
        });
    });
</script>

<div id="form-alasan-ubah" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Alasan Ubah Resep</h5>
            </div>
            <div class="modal-body">
                <textarea class="form-control" placeholder="Alasan Perubahan Resep" id="alasan-ubah-resep" style="min-height: 400px;"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitAlasanUbah">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="form-copy-resep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Copy Resep</h5>
            </div>
            <div class="modal-body">
                <div id="copy-resep-cetak">

                    <p class="text-center">
                        Dari dokter: <b id="copy-resep-dokter"></b>
                        <br />
                        Untuk: <b id="copy-resep-pasien"></b>
                        <br />
                        Tanggal Lahir/Usia:<br /><b id="copy-resep-pasien-lahir-user"></b>
                        <br />
                        Alamat:<br /><strong id="copy-resep-pasien-alamat"></strong>
                        <br />
                        Tanggal Resep: <b id="copy-resep-tanggal"></b>
                    </p>
                    <h5 id="iter-copy-resep" class="resep_script"></h5>
                    <hr />
                    <table class="form-mode table largeDataType" id="copy-resep-report">

                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnCetakCopyResep">Cetak</button>
            </div>
        </div>
    </div>
</div>

<div id="form-editor-racikan" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Komposisi Obat</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label for="txt_racikan_obat">Obat:</label>
                    <select class="form-control" id="txt_racikan_obat"></select>
                </div>
                <!-- <div class="form-group col-md-6">
                    <label for="txt_racikan_jlh">Jumlah:</label>
                    <input type="text" class="form-control" id="txt_racikan_jlh" />
                </div> -->
                <div class="form-group col-md-12">
                    <div class="kolom_kekuatan">
                        <label for="txt_racikan_kekuatan">Kekuatan:</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="txt_racikan_kekuatan" placeholder="0" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="kolom_takar" style="display: none">
                        <label for="txt_racikan_takar">Takar:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" value="1" class="form-control" id="txt_racikan_takar_bulat" placeholder="0" />
                            </div>
                            <div class="col-md-1">
                                <i class="fa fa-plus" style="margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="txt_racikan_takar" placeholder="a/b" />
                            </div>
                            <div class="col-md-3">
                                <small>Cth:<br />2 + 1/2</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group col-md-12">
                    <label for="txt_racikan_satuan">Satuan:</label>
                    <select class="form-control" id="txt_racikan_satuan"></select>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitKomposisi">Simpan</button>
            </div>
        </div>
    </div>
</div>