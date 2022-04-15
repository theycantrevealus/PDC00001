<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/qrcode/qrcode.js"></script>
<script type="text/javascript">
  $(function() {
    var resepUID = __PAGES__[3];
    var resepJenis = __PAGES__[4];
    var targettedData = {};
    var selectedNamaPasien = "";
    var selectedRMPasien = "";
    var selectedUIDPasien = "";
    var currentStatusOpname = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
    var allowProcess = false;
    $.ajax({
      url: __HOSTAPI__ + "/Apotek/detail_resep_verifikator/" + resepUID,
      async: false,
      beforeSend: function(request) {
        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
      },
      type: "GET",
      success: function(response) {
        targettedData = response.response_package.response_data[0];


        selectedUIDPasien = targettedData.antrian.pasien_info.uid;
        selectedRMPasien = targettedData.antrian.pasien_info.no_rm;
        selectedNamaPasien = ((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name.nama !== null) ? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama;
        // $("#verifikator").html(targettedData.detail[0].verifikator.nama);
        $("#verifikator").html(targettedData.verifikator.nama);
        $("#txt_keterangan_resep").html(targettedData.keterangan);
        $("#txt_keterangan_racikan").html(targettedData.keterangan_racikan);
        $("#nama-pasien").attr({
          "set-penjamin": targettedData.antrian.penjamin_data.uid
        }).html(((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name.nama !== null) ? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");
        $("#jk-pasien").html(targettedData.antrian.pasien_info.jenkel_nama);
        $("#tanggal-lahir-pasien").html(targettedData.antrian.pasien_info.tanggal_lahir + " (" + targettedData.antrian.pasien_info.usia + " tahun)");
        //$("#verifikator").html(targettedData.verifikator.nama);
        loadDetailResep(targettedData);
      },
      error: function(response) {
        console.log(response);
      }
    });






    function load_product_resep(target, selectedData = "", appendData = true) {
      var selected = [];
      var productData;
      $.ajax({
        url: __HOSTAPI__ + "/Inventori/item_detail/" + selectedData,
        async: false,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          productData = response.response_package.response_data;
          for (var a = 0; a < productData.length; a++) {
            var penjaminList = [];
            var penjaminListData = productData[a].penjamin;
            for (var penjaminKey in penjaminListData) {
              if (penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
              }
            }

            if (selected.indexOf(productData[a].uid) < 0 && appendData) {
              $(target).append("<option penjamin-list=\"" + penjaminList.join(",") + "\" satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
            }
          }
        },
        error: function(response) {
          console.log(response);
        }
      });
      return {
        allow: (productData.length == selected.length),
        data: productData
      };
    }





    function loadDetailResep(data) {
      console.clear();
      console.log(data);
      $("#txt_alasan_ubah").html((data.alasan_ubah !== undefined && data.alasan_ubah !== null && data.alasan_ubah !== "") ? data.alasan_ubah : "-");
      $("#load-detail-resep tbody tr").remove();


      var grouperResep = {};

      var resepVerifikator = data.detail;
      for (var a = 0; a < resepVerifikator.length; a++) {
        if (grouperResep[resepVerifikator[a].item] === undefined) {
          grouperResep[resepVerifikator[a].item] = {
            batch: [],
            alasan_ubah: resepVerifikator[a].alasan_ubah,
            detail: resepVerifikator[a].detail,
            keterangan: resepVerifikator[a].keterangan,
            qty: 0,
            signa_pakai: resepVerifikator[a].signa_pakai,
            signa_qty: resepVerifikator[a].signa_qty,
            verifikator: resepVerifikator[a].verifikator,
            aturan_pakai: resepVerifikator[a].aturan_pakai
          };
        }
        resepVerifikator[a].batch.qty = parseFloat(resepVerifikator[a].qty);
        resepVerifikator[a].batch.stok_terkini = parseFloat(resepVerifikator[a].stok_terkini);
        grouperResep[resepVerifikator[a].item].batch.push(resepVerifikator[a].batch);
        grouperResep[resepVerifikator[a].item].qty += parseFloat(resepVerifikator[a].qty);
      }

      var resepAutonum = 1;
      for (var a in grouperResep) {
        var newDetailRow = document.createElement("TR");
        var newDetailCellID = document.createElement("TD");
        $(newDetailCellID).addClass("text-center").html("<h5 class=\"autonum\">" + (resepAutonum) + "</h5>");
        var newDetailCellObat = document.createElement("TD");
        $(newDetailCellObat).append("<h5 class=\"text-info\">" + grouperResep[a].detail.nama + "</h5>");
        $(newDetailCellObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
        $(newDetailCellObat).append("<span id=\"batch_resep_" + resepAutonum + "\" class=\"selected_batch\"><ol></ol></span>");
        var currentBatch = grouperResep[a].batch;
        for (var b in currentBatch) {
          $(newDetailCellObat).find("span ol").append("<li class=\"check_batch_avail " + ((currentBatch[b].stok_terkini >= currentBatch[b].qty) ? "text-success" : "text-danger") + "\" batch=\"" + currentBatch[b].uid + "\"><b>[" + currentBatch[b].batch + "]</b> " + currentBatch[b].expired_date_parsed + " (" + currentBatch[b].qty + ")</li>");
        }

        var newDetailCellSigna = document.createElement("TD");
        $(newDetailCellSigna).html("<h5 class=\"text_center wrap_content\">" + grouperResep[a].signa_qty + " &times; " + grouperResep[a].signa_pakai + "</h5>");
        var newDetailCellQty = document.createElement("TD");
        $(newDetailCellQty).addClass("text_center").append("<h5 class=\"text_center\">" + parseFloat(grouperResep[a].qty) + "</h5>").append("");

        var newDetailCellKeterangan = document.createElement("TD");
        $(newDetailCellKeterangan).css({
          "white-space": "pre-wrap"
        }).html(grouperResep[a].keterangan);

        var newDetailCellAlasan = document.createElement("TD");
        $(newDetailCellAlasan).html((grouperResep[a].alasan_ubah !== undefined && grouperResep[a].alasan_ubah !== null && grouperResep[a].alasan_ubah !== "") ? grouperResep[a].alasan_ubah : "-");
        $(newDetailRow).append(newDetailCellID);
        $(newDetailRow).append(newDetailCellObat);
        $(newDetailRow).append(newDetailCellSigna);
        $(newDetailRow).append(newDetailCellQty);
        $(newDetailRow).append(newDetailCellKeterangan);
        $(newDetailRow).append(newDetailCellAlasan);

        $("#load-detail-resep tbody").append(newDetailRow);

      }


      // for(var a = 0; a < data.detail.length; a++) {
      //     if(data.detail[a].detail !== null) {
      //         var ObatData = load_product_resep(newObat, data.detail[a].detail.uid, false);
      //         var selectedBatchResep = refreshBatch(data.detail[a].detail.uid);
      //         var selectedBatchList = [];

      //         var harga_tertinggi = 0;
      //         var kebutuhan = parseFloat(data.detail[a].qty);
      //         var jlh_sedia = 0;
      //         var butuh_amprah = 0;
      //         for(bKey in selectedBatchResep)
      //         {
      //             if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang && parseFloat(selectedBatchResep[bKey].stok_terkini) > 0) {
      //                 if(selectedBatchResep[bKey].harga > harga_tertinggi)    //Selalu ambil harga tertinggi
      //                 {
      //                     harga_tertinggi = parseFloat(selectedBatchResep[bKey].harga);
      //                 }

      //                 if(kebutuhan > 0)
      //                 {

      //                     if(kebutuhan > selectedBatchResep[bKey].stok_terkini)
      //                     {
      //                         selectedBatchResep[bKey].used = selectedBatchResep[bKey].stok_terkini;
      //                     } else {
      //                         selectedBatchResep[bKey].used = kebutuhan;
      //                     }
      //                     kebutuhan = kebutuhan - selectedBatchResep[bKey].stok_terkini;
      //                     if(selectedBatchResep[bKey].used > 0)
      //                     {
      //                         selectedBatchList.push(selectedBatchResep[bKey]);
      //                     }
      //                 }

      //                 if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang) {
      //                     jlh_sedia += selectedBatchResep[bKey].stok_terkini;
      //                 } else {
      //                     butuh_amprah += selectedBatchResep[bKey].stok_terkini;
      //                 }

      //             }
      //         }

      //         if(selectedBatchResep.length > 0)
      //         {
      //             var profit = 0;
      //             var profit_type = "N";

      //             for(var batchDetail in selectedBatchResep[0].profit)
      //             {
      //                 if(selectedBatchResep[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
      //                 {
      //                     profit = parseFloat(selectedBatchResep[0].profit[batchDetail].profit);
      //                     profit_type = selectedBatchResep[0].profit[batchDetail].profit_type;
      //                 }
      //             }

      //             var newDetailRow = document.createElement("TR");
      //             $(newDetailRow).attr({
      //                 "id": "row_resep_" + a,
      //                 "profit": profit,
      //                 "profit_type": profit_type
      //             });

      //             var newDetailCellID = document.createElement("TD");
      //             $(newDetailCellID).addClass("text-center").html("<h5 class=\"autonum\">" + (a + 1) + "</h5>");

      //             var newDetailCellObat = document.createElement("TD");
      //             var newObat = document.createElement("SELECT");
      //             $(newDetailCellObat).append("<h5 class=\"text-info\">" + data.detail[a].detail.nama + "</h5>");
      //             /*$(newObat).attr({
      //                 "id": "obat_selector_" + a
      //             }).addClass("obatSelector resep-obat form-control").select2();
      //             $(newObat).append("<option value=\"" + data.detail[a].detail.uid + "\">" + data.detail[a].detail.nama + "</option>").val(data.detail[a].detail.uid).trigger("change");*/



      //             $(newDetailCellObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
      //             $(newDetailCellObat).append("<span id=\"batch_resep_" + a + "\" class=\"selected_batch\"><ol></ol></span>");
      //             for(var batchSelKey in selectedBatchList)
      //             {
      //                 $(newDetailCellObat).find("span ol").append("<li batch=\"" + selectedBatchList[batchSelKey].batch + "\"><b>[" + selectedBatchList[batchSelKey].kode + "]</b> " + selectedBatchList[batchSelKey].expired + " (" + selectedBatchList[batchSelKey].used + ")</li>");
      //             }

      //             $(newDetailCellObat).attr({
      //                 harga: harga_tertinggi
      //             });

      //             var newDetailCellSigna = document.createElement("TD");
      //             $(newDetailCellSigna).html("<h5 class=\"text_center wrap_content\">" + data.detail[a].signa_qty + " &times; " + data.detail[a].signa_pakai + "</h5>");

      //             $(newDetailCellSigna).find("input").inputmask({
      //                 alias: 'decimal',
      //                 rightAlign: true,
      //                 placeholder: "0.00",
      //                 prefix: "",
      //                 autoGroup: false,
      //                 digitsOptional: true
      //             });

      //             var newDetailCellQty = document.createElement("TD");
      //             var newQty = document.createElement("INPUT");
      //             var statusSedia = "";

      //             /*if(parseFloat(data.detail[a].qty) <= parseFloat(data.detail[a].sedia))
      //             {
      //                     statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + data.detail[a].sedia + "</b>";
      //                 } else {
      //                 statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + data.detail[a].sedia + "</b>";
      //             }*/
      //             if(parseFloat(data.detail[a].qty) <= parseFloat(jlh_sedia))
      //             {
      //                 statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
      //             } else {
      //                 statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
      //             }

      //             if((parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia)) > 0) {
      //                 statusSedia += "<br /><b class=\"text-warning\"><i class=\"fa fa-exclamation-circle\"></i>Butuh Amprah : " + number_format(parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</b>";

      //                 if(currentStatusOpname === "A") {
      //                     $("#btnSelesai").attr({
      //                         "disabled": "disabled"
      //                     }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
      //                 } else {
      //                     $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
      //                 }

      //             } else {
      //                 var disabledStatus = $("#btnSelesai").attr('name');
      //                 if (typeof attr !== typeof undefined && attr !== false) {
      //                     // ...
      //                 } else {
      //                     $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
      //                 }
      //             }

      //             $(newDetailCellQty).addClass("text_center").append("<h5 class=\"text_center\">" + parseFloat(data.detail[a].qty) + "</h5>").append(statusSedia);
      //             /*$(newQty).inputmask({
      //                 alias: "decimal",
      //                 rightAlign: true,
      //                 placeholder: "0.00",
      //                 prefix: "",
      //                 autoGroup: false,
      //                 digitsOptional: true
      //             }).addClass("form-control qty_resep").attr({
      //                 "id": "qty_resep_" + a
      //             }).val(parseFloat(data.detail[a].qty));*/

      //             var totalObatRaw = parseFloat(harga_tertinggi);
      //             var totalObat = 0;
      //             if(profit_type === "N")
      //             {
      //                 totalObat = totalObatRaw
      //             } else if(profit_type === "P")
      //             {
      //                 totalObat = totalObatRaw + (profit / 100  * totalObatRaw);
      //             } else if(profit_type === "A")
      //             {
      //                 totalObat = totalObatRaw + profit;
      //             }

      //             var newDetailCellKeterangan = document.createElement("TD");
      //             $(newDetailCellKeterangan).css({
      //                 "white-space": "pre-wrap"
      //             }).html(data.detail[a].keterangan);

      //             var newDetailCellAlasan = document.createElement("TD");
      //             $(newDetailCellAlasan).html((data.detail[a].alasan_ubah !== undefined && data.detail[a].alasan_ubah !== null && data.detail[a].alasan_ubah !== "") ? data.detail[a].alasan_ubah : "-");
      //             //=======================================
      //             $(newDetailRow).append(newDetailCellID);
      //             $(newDetailRow).append(newDetailCellObat);
      //             $(newDetailRow).append(newDetailCellSigna);
      //             $(newDetailRow).append(newDetailCellQty);
      //             $(newDetailRow).append(newDetailCellKeterangan);
      //             $(newDetailRow).append(newDetailCellAlasan);

      //             $("#load-detail-resep tbody").append(newDetailRow);
      //         }
      //     }
      // }








      //==================================================================================== RACIKAN
      //Checker
      for (var b = 0; b < data.racikan.length; b++) {
        var racikanDetail = data.racikan[b].detail;
        for (var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
          var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
          var kebutuhan_racikan = parseFloat(racikanDetail[racDetailKey].jumlah);
          var jlh_sedia = 0;
          var butuh_amprah = 0;
          for (bKey in selectedBatchRacikan) {
            if (kebutuhan_racikan > 0) {
              if (selectedBatchRacikan[bKey].gudang.uid === __UNIT__.gudang) {
                jlh_sedia += selectedBatchRacikan[bKey].stok_terkini;
              } else {
                butuh_amprah += selectedBatchRacikan[bKey].stok_terkini;
              }
            }
          }
        }
      }


      $("#load-detail-racikan tbody").html("");
      for (var b = 0; b < data.racikan.length; b++) {
        var uniqueObatRacikan = {};


        var racikanDetail = data.racikan[b].detail;

        for (var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
          if (uniqueObatRacikan[racikanDetail[racDetailKey].obat] === undefined) {
            uniqueObatRacikan[racikanDetail[racDetailKey].obat] = {
              batch: [],
              detail: racikanDetail[racDetailKey].detail,
              id: racikanDetail[racDetailKey].id,
              jumlah: 0,
              stok_terkini: racikanDetail[racDetailKey].stok_terkini,
              kekuatan: racikanDetail[racDetailKey].kekuatan,
              obat: racikanDetail[racDetailKey].obat,
              pay: racikanDetail[racDetailKey].pay
            };
          }

          uniqueObatRacikan[racikanDetail[racDetailKey].obat].jumlah += parseFloat(racikanDetail[racDetailKey].jumlah);
          racikanDetail[racDetailKey].batch.jumlah = parseFloat(racikanDetail[racDetailKey].jumlah);
          racikanDetail[racDetailKey].batch.stok_terkini = parseFloat(racikanDetail[racDetailKey].stok_terkini);
          uniqueObatRacikan[racikanDetail[racDetailKey].obat].batch.push(racikanDetail[racDetailKey].batch);
        }

        racikanDetail = [];

        for (var ban in uniqueObatRacikan) {
          racikanDetail.push(uniqueObatRacikan[ban]);
        }




        for (var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
          var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
          var selectedBatchListRacikan = [];
          var selectedBatchListRacikanAmprah = racikanDetail[racDetailKey].batch;
          var harga_tertinggi_racikan = 0;
          //var kebutuhan_racikan = parseFloat(data.racikan[b].qty);
          var kebutuhan_racikan = parseFloat(racikanDetail[racDetailKey].jumlah);
          var jlh_sedia = 0;
          var butuh_amprah = 0;
          // for(bKey in selectedBatchRacikan)
          // {
          //     if(selectedBatchRacikan[bKey].harga > harga_tertinggi_racikan)    //Selalu ambil harga tertinggi
          //     {
          //         harga_tertinggi_racikan = selectedBatchRacikan[bKey].harga;
          //     }

          //     if(kebutuhan_racikan > 0)
          //     {

          //         if(kebutuhan_racikan > selectedBatchRacikan[bKey].stok_terkini)
          //         {
          //             selectedBatchRacikan[bKey].used = selectedBatchRacikan[bKey].stok_terkini;
          //         } else {
          //             selectedBatchRacikan[bKey].used = kebutuhan_racikan;
          //         }



          //         if(selectedBatchRacikan[bKey].gudang.uid === __UNIT__.gudang) {
          //             kebutuhan_racikan -= selectedBatchRacikan[bKey].stok_terkini;
          //             jlh_sedia += selectedBatchRacikan[bKey].stok_terkini;
          //             selectedBatchListRacikan.push(selectedBatchRacikan[bKey]);
          //         } else {
          //             butuh_amprah += selectedBatchRacikan[bKey].stok_terkini;
          //             selectedBatchListRacikanAmprah.push(selectedBatchRacikan[bKey]);
          //         }
          //     }
          // }


          if (selectedBatchListRacikan.length > 0) {
            var profit_racikan = 0;
            var profit_type_racikan = "N";

            for (var batchDetail in selectedBatchRacikan[0].profit) {
              if (selectedBatchRacikan[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin")) {
                profit_racikan = parseFloat(selectedBatchRacikan[0].profit[batchDetail].profit);
                profit_type_racikan = selectedBatchRacikan[0].profit[batchDetail].profit_type;
              }
            }

            var newRacikanRow = document.createElement("TR");


            $(newRacikanRow).addClass("racikan_row").attr({
              "id": "racikan_group_" + data.racikan[b].uid + "_" + racDetailKey,
              "group_racikan": data.racikan[b].uid
            });

            var newCellRacikanID = document.createElement("TD");
            var newCellRacikanNama = document.createElement("TD");
            var newCellRacikanSigna = document.createElement("TD");
            var newCellRacikanObat = document.createElement("TD");
            var newCellRacikanJlh = document.createElement("TD");
            var newCellRacikanKeterangan = document.createElement("TD");
            var newCellRacikanAlasan = document.createElement("TD");

            $(newCellRacikanID).attr("rowspan", racikanDetail.length).html("<h5 class=\"autonum\">" + (b + 1) + "</h5>");
            $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
            if (data.racikan[b].change.length > 0) {
              $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].change[0].signa_qty + " &times " + data.racikan[b].change[0].signa_pakai + "</h5>");
            } else {
              $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
            }

            $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

            var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
            var newRacikanObat = document.createElement("SELECT");
            var statusSediaRacikan = "";
            /*if(parseFloat(racikanDetail[racDetailKey].jumlah) <= parseFloat(racikanDetail[racDetailKey].sedia))
            {
                statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
            } else {
                statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
            }*/

            if (parseFloat(data.racikan[b].qty) <= parseFloat(jlh_sedia)) {
              //statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
            } else {
              //statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
            }

            /*if((parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia)) > 0) {
                statusSediaRacikan += "<br /><b class=\"text-info\"><i class=\"fa fa-exclamation-circle\"> Stok : " + number_format(parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</i></b>";
                $("#btnSelesai").attr({
                    "disabled": "disabled"
                }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                console.log("Case A");
                console.log(parseFloat(data.racikan[b].qty));
                console.log(parseFloat(jlh_sedia));
            } else {
                var disabledStatus = $("#btnSelesai").attr('name');
                if (typeof attr !== typeof undefined && attr !== false) {
                    $("#btnSelesai").attr({
                        "disabled": "disabled"
                    }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                    console.log("Case B");
                    console.log(parseFloat(data.racikan[b].qty));
                    console.log(parseFloat(jlh_sedia));
                } else {
                    $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                    console.log("Case C");
                    console.log(parseFloat(data.racikan[b].qty));
                    console.log(parseFloat(jlh_sedia));
                }
            }*/

            $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>").append(statusSediaRacikan);

            $(newRacikanObat).attr({
              "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
              "group_racikan": data.racikan[b].uid
            }).addClass("obatSelector racikan-obat form-control").select2();
            $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


            $(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
            $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");

            var akumulasi = 0;

            for (var batchSelKey in selectedBatchListRacikan) {
              $(newCellRacikanObat).find("span ol").append("<li class=\"check_batch_avail " + ((selectedBatchListRacikanAmprah[batchSelKey].stok_terkini >= selectedBatchListRacikanAmprah[batchSelKey].jumlah) ? "text-success" : "text-danger") + "\" batch=\"" + selectedBatchListRacikanAmprah[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikanAmprah[batchSelKey].batch + "]</b> " + selectedBatchListRacikanAmprah[batchSelKey].expired_date_parsed + " (" + selectedBatchListRacikanAmprah[batchSelKey].jumlah + ")</li>");
            }
            // for(var batchSelKey in selectedBatchListRacikan)
            // {
            //     if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
            //         if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
            //             $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
            //             akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
            //         }
            //     }
            // }


            // if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {

            //     for(var batchSelKey in selectedBatchListRacikanAmprah) {
            //         if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
            //             if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
            //                 $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
            //                 akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
            //             }
            //         }
            //     }
            // }


            $(newCellRacikanObat).attr({
              harga: harga_tertinggi_racikan
            });

            if (data.racikan[b].change.length > 0) {
              $(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[0].jumlah + "<h5>");
            } else {
              $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
            }

            //$(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[b].jumlah + "<h5>");
            $(newCellRacikanKeterangan).css({
              "white-space": "pre-wrap"
            }).html(data.racikan[b].change[0].keterangan);
            $(newCellRacikanAlasan).html((data.racikan[b].change.length > 0) ? ((data.racikan[b].change[0].alasan_ubah !== undefined && data.racikan[b].change[0].alasan_ubah !== null && data.racikan[b].change[0].alasan_ubah !== "") ? data.racikan[b].change[0].alasan_ubah : "-") : "-");
            //alert(b + " - " + racDetailKey);
            if (racDetailKey === 0) {
              $(newRacikanRow).append(newCellRacikanID);
              $(newRacikanRow).append(newCellRacikanNama);
              $(newRacikanRow).append(newCellRacikanSigna);
              $(newRacikanRow).append(newCellRacikanJlh);

              $(newRacikanRow).append(newCellRacikanObat);
              $(newRacikanRow).append(newCellRacikanKeterangan);
              $(newRacikanRow).append(newCellRacikanAlasan);
            } else {
              $(newRacikanRow).append(newCellRacikanObat);
            }

            $(newCellRacikanKeterangan).attr("rowspan", racikanDetail.length);
            $(newCellRacikanAlasan).attr("rowspan", racikanDetail.length);
            $("#load-detail-racikan tbody").append(newRacikanRow);

          } else { //Butuh Amprah

            var profit_racikan = 0;
            var profit_type_racikan = "N";

            for (var batchDetail in selectedBatchRacikan[0].profit) {
              if (selectedBatchRacikan[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin")) {
                profit_racikan = parseFloat(selectedBatchRacikan[0].profit[batchDetail].profit);
                profit_type_racikan = selectedBatchRacikan[0].profit[batchDetail].profit_type;
              }
            }

            var newRacikanRow = document.createElement("TR");


            $(newRacikanRow).addClass("racikan_row").attr({
              "id": "racikan_group_" + data.racikan[b].uid + "_" + racDetailKey,
              "group_racikan": data.racikan[b].uid
            });

            var newCellRacikanID = document.createElement("TD");
            var newCellRacikanNama = document.createElement("TD");
            var newCellRacikanSigna = document.createElement("TD");
            var newCellRacikanObat = document.createElement("TD");
            var newCellRacikanJlh = document.createElement("TD");
            var newCellRacikanKeterangan = document.createElement("TD");
            var newCellRacikanAlasan = document.createElement("TD");

            $(newCellRacikanID).attr("rowspan", racikanDetail.length).html("<h5 class=\"autonum\">" + (b + 1) + "</h5>");
            $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
            if (data.racikan[b].change.length > 0) {
              $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].change[0].signa_qty + " &times " + data.racikan[b].change[0].signa_pakai + "</h5>");
            } else {
              $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
            }

            $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

            var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
            var newRacikanObat = document.createElement("SELECT");
            var statusSediaRacikan = "";
            /*if(parseFloat(racikanDetail[racDetailKey].jumlah) <= parseFloat(racikanDetail[racDetailKey].sedia))
            {
                statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
            } else {
                statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
            }*/

            if (parseFloat(data.racikan[b].qty) <= parseFloat(jlh_sedia)) {
              //statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
            } else {
              //statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
            }

            /*if((parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia)) > 0) {
                statusSediaRacikan += "<br /><b class=\"text-info\"><i class=\"fa fa-exclamation-circle\"> Stok : " + number_format(parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</i></b>";
                $("#btnSelesai").attr({
                    "disabled": "disabled"
                }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                console.log("Case A");
                console.log(parseFloat(data.racikan[b].qty));
                console.log(parseFloat(jlh_sedia));
            } else {
                var disabledStatus = $("#btnSelesai").attr('name');
                if (typeof attr !== typeof undefined && attr !== false) {
                    $("#btnSelesai").attr({
                        "disabled": "disabled"
                    }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                    console.log("Case B");
                    console.log(parseFloat(data.racikan[b].qty));
                    console.log(parseFloat(jlh_sedia));
                } else {
                    $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                    console.log("Case C");
                    console.log(parseFloat(data.racikan[b].qty));
                    console.log(parseFloat(jlh_sedia));
                }
            }*/

            $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>").append(statusSediaRacikan);

            $(newRacikanObat).attr({
              "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
              "group_racikan": data.racikan[b].uid
            }).addClass("obatSelector racikan-obat form-control").select2();
            $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


            $(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
            $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");

            var akumulasi = 0;
            for (var batchSelKey in selectedBatchListRacikanAmprah) {
              $(newCellRacikanObat).find("span ol").append("<li class=\"check_batch_avail " + ((selectedBatchListRacikanAmprah[batchSelKey].stok_terkini >= selectedBatchListRacikanAmprah[batchSelKey].jumlah) ? "text-success" : "text-danger") + "\" batch=\"" + selectedBatchListRacikanAmprah[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikanAmprah[batchSelKey].batch + "]</b> " + selectedBatchListRacikanAmprah[batchSelKey].expired_date_parsed + " (" + selectedBatchListRacikanAmprah[batchSelKey].jumlah + ")</li>");
            }
            // for(var batchSelKey in selectedBatchListRacikanAmprah)
            // {
            //     if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
            //         if(parseFloat(selectedBatchListRacikanAmprah[batchSelKey].used) > 0) {
            //             $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikanAmprah[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikanAmprah[batchSelKey].kode + "]</b> " + selectedBatchListRacikanAmprah[batchSelKey].expired + " (" + selectedBatchListRacikanAmprah[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikanAmprah[batchSelKey].gudang.nama + "]</b></li>");
            //             akumulasi += parseFloat(selectedBatchListRacikanAmprah[batchSelKey].used);
            //         }
            //     }
            // }


            // if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {

            //     for(var batchSelKey in selectedBatchListRacikanAmprah) {
            //         if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
            //             if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
            //                 $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
            //                 akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
            //             }
            //         }
            //     }
            // }


            $(newCellRacikanObat).attr({
              harga: harga_tertinggi_racikan
            });

            if (data.racikan[b].change.length > 0) {
              $(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[0].jumlah + "<h5>");
            } else {
              $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
            }

            //$(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[b].jumlah + "<h5>");
            $(newCellRacikanKeterangan).css({
              "white-space": "pre-wrap"
            }).html(data.racikan[b].change[0].keterangan);
            $(newCellRacikanAlasan).html((data.racikan[b].change.length > 0) ? ((data.racikan[b].change[0].alasan_ubah !== undefined && data.racikan[b].change[0].alasan_ubah !== null && data.racikan[b].change[0].alasan_ubah !== "") ? data.racikan[b].change[0].alasan_ubah : "-") : "-");
            //alert(b + " - " + racDetailKey);
            if (racDetailKey === 0) {
              $(newRacikanRow).append(newCellRacikanID);
              $(newRacikanRow).append(newCellRacikanNama);
              $(newRacikanRow).append(newCellRacikanSigna);
              $(newRacikanRow).append(newCellRacikanJlh);

              $(newRacikanRow).append(newCellRacikanObat);
              $(newRacikanRow).append(newCellRacikanKeterangan);
              $(newRacikanRow).append(newCellRacikanAlasan);
            } else {
              $(newRacikanRow).append(newCellRacikanObat);
            }

            $(newCellRacikanKeterangan).attr("rowspan", racikanDetail.length);
            $(newCellRacikanAlasan).attr("rowspan", racikanDetail.length);
            $("#load-detail-racikan tbody").append(newRacikanRow);


          }
        }
      }
    }

    $("select, input.form-control").attr({
      "disabled": "disabled"
    });

    function refreshBatch(item) {
      var batchData;
      $.ajax({
        url: __HOSTAPI__ + "/Inventori/item_batch/" + item,
        async: false,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          batchData = response.response_package.response_data;
        },
        error: function(response) {
          console.log(response);
        }
      });
      return batchData;
    }

    $("body").on("click", ".btn-apotek-cetak", function() {
      var jenis_pasien = resepJenis;

      //Load Resep Detail
      $.ajax({
        url: __HOSTAPI__ + "/Apotek/detail_resep_verifikator/" + resepUID,
        async: false,
        beforeSend: function(request) {
          request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        },
        type: "GET",
        success: function(response) {
          targettedData = response.response_package.response_data[0];
          console.clear();

          var kajian = targettedData.kajian;
          for (var kaj in kajian) {
            $("#hasil_" + kajian[kaj].parameter_kajian).html((kajian[kaj].nilai === "y") ? "<span class=\"text-success wrap_content\"><i class=\"fa fa-check-circle\"></i> Ya</span>" : "<span class=\"text-danger wrap_content\"><i class=\"fa fa-times-circle\"></i> Tidak</span>");
          }

          var detail_dokter = targettedData.detail_dokter;
          var resep_dokter = [];
          for (var a in detail_dokter) {
            resep_dokter.push({
              obat: "<b>R\/</b> " + detail_dokter[a].detail.nama,
              satuan: detail_dokter[a].detail.satuan_terkecil_info.nama,
              kuantitas: detail_dokter[a].qty,
              satuan_konsumsi: detail_dokter[a].satuan_konsumsi,
              signa: detail_dokter[a].signa_qty + " &times; " + detail_dokter[a].signa_pakai,
              keterangan: detail_dokter[a].keterangan
            });
          }

          var detail_racikan_dokter = targettedData.racikan;
          var racikan_dokter = [];
          for (var b in detail_racikan_dokter) {
            racikan_dokter.push({
              racikan: "<b>R\/</b> " + detail_racikan_dokter[b].kode,
              kuantitas: detail_racikan_dokter[b].qty,
              signa: detail_racikan_dokter[b].signa_qty + " &times; " + detail_racikan_dokter[b].signa_pakai,
              keterangan: detail_racikan_dokter[b].keterangan,
              satuan_konsumsi: detail_racikan_dokter[b].satuan_konsumsi,
              item: detail_racikan_dokter[b].detail_dokter
            });
          }

          var totalAll = 0;
          var detail_apotek = targettedData.detail;
          var resep_apotek = [];
          for (var a in detail_apotek) {
            resep_apotek.push({
              obat: "<b>R\/</b> " + detail_apotek[a].detail.nama,
              satuan: detail_apotek[a].detail.satuan_terkecil_info.nama,
              kuantitas: detail_apotek[a].qty,
              signa: detail_apotek[a].signa_qty + " &times; " + detail_apotek[a].signa_pakai,
              keterangan: detail_apotek[a].keterangan,
              alasan_ubah: (detail_apotek[a].alasan_ubah !== "" && detail_apotek[a].alasan_ubah !== undefined && detail_apotek[a].alasan_ubah !== null) ? detail_apotek[a].alasan_ubah : "-",
              harga: "<h6 class=\"number_style\">" + ((detail_apotek[a].pay[0] !== undefined) ? number_format(parseFloat(detail_apotek[a].pay[0].harga), 2, ".", ",") : number_format(parseFloat(0), 2, ".", ",")) + "</h6>",
              subtotal: "<h6 class=\"number_style\">" + ((detail_apotek[a].pay[0] !== undefined) ? number_format(parseFloat(detail_apotek[a].pay[0].harga * detail_apotek[a].qty), 2, ".", ",") : number_format(parseFloat(0), 2, ".", ",")) + "</h6>",
            });
            totalAll += ((detail_apotek[a].pay[0] !== undefined) ? parseFloat(detail_apotek[a].pay[0].subtotal) : 0);
          }


          var detail_racikan_apotek = targettedData.racikan;
          var racikan_apotek = [];
          for (var b in detail_racikan_apotek) {
            var detailRacikanApotek = detail_racikan_apotek[b].detail;
            var subtotalRacikanApotek = 0;


            var prepareRacikanApotek = {
              kode: "<b>R\/</b> " + detail_racikan_apotek[b].kode,
              kuantitas: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].jumlah : detail_racikan_apotek[b].qty,
              signa: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].signa_qty + " &times; " + detail_racikan_apotek[b].change[0].signa_pakai : detail_racikan_apotek[b].signa_qty + " &times; " + detail_racikan_apotek[b].signa_pakai,
              keterangan: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].keterangan : detail_racikan_apotek[b].keterangan,
              alasan_ubah: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].alasan_ubah : "-",
              subtotal: 0,
              detail: []
            };


            for (var c in detailRacikanApotek) {
              if (detail_racikan_apotek[b].change.length > 0) {
                prepareRacikanApotek.detail.push({
                  obat: detailRacikanApotek[c].detail.nama,
                  kuantitas: ((detailRacikanApotek[c].pay[0] !== undefined) ? detailRacikanApotek[c].pay[0].qty : 0),
                  keterangan: detail_racikan_apotek[b].keterangan,
                  harga: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].harga), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                  subtotal: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].subtotal), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                });
              } else {
                prepareRacikanApotek.detail.push({
                  obat: detailRacikanApotek[c].detail.nama,
                  kuantitas: ((detailRacikanApotek[c].pay[0] !== undefined) ? detailRacikanApotek[c].pay[0].qty : 0),
                  signa: detail_racikan_apotek[b].signa_qty + " &times; " + detail_racikan_apotek[b].signa_pakai,
                  keterangan: detail_racikan_apotek[b].keterangan,
                  harga: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].harga), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                  subtotal: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].subtotal), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                });
              }
              subtotalRacikanApotek += ((detailRacikanApotek[c].pay[0] !== undefined) ? parseFloat(detailRacikanApotek[c].pay[0].subtotal) : 0);
              totalAll += ((detailRacikanApotek[c].pay[0] !== undefined) ? parseFloat(detailRacikanApotek[c].pay[0].subtotal) : 0);
            }

            racikan_apotek.push(prepareRacikanApotek);
          }

          targetKodeResep = targettedData.kode;
          targetRM = targettedData.pasien.no_rm;
          targetNamaPasien = targettedData.pasien.nama;
          targetTanggalResep = targettedData.created_at_parsed;
          targetHargaTotal = "Rp. " + number_format(totalAll, 2, ".", ",");
          $.ajax({
            async: false,
            url: __HOST__ + "miscellaneous/print_template/resep_view.php",
            beforeSend: function(request) {
              request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "POST",
            data: {
              __PC_CUSTOMER__: __PC_CUSTOMER__,
              __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__,
              __PC_IDENT__: __PC_IDENT__,
              __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
              __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
              kode: targettedData.kode,
              tanggal_resep: targettedData.created_at_parsed,
              no_mr: targettedData.pasien.no_rm,
              jenis_pasien: jenis_pasien,
              nama_pasien: targettedData.pasien.nama,
              departemen: (targettedData.antrian.poli_info !== undefined && targettedData.antrian.poli_info !== null) ? targettedData.antrian.poli_info.nama : "Rawat Inap",
              tanggal_lahir: targettedData.pasien.tanggal_lahir_parsed,
              verifikator: $("#verifikator").html(),
              dokter: targettedData.dokter.nama,
              jenis_kelamin: targettedData.pasien.jenkel_detail.nama,
              penjamin: targettedData.antrian.penjamin_data.nama,
              keterangan_resep: targettedData.keterangan,
              keterangan_racikan: targettedData.keterangan_racikan,
              alasan_ubah: targettedData.alasan_ubah,
              alergi: targettedData.alergi_obat,
              sep: (targettedData.antrian.penjamin === __UIDPENJAMINUMUM__) ? "-" : ((targettedData.bpjs !== undefined) ? ((targettedData.bpjs.sep !== undefined) ? targettedData.bpjs.sep : "-") : "-"),
              resep_dokter: resep_dokter,
              racikan_dokter: racikan_dokter,
              resep_apotek: resep_apotek,
              racikan_apotek: racikan_apotek,
              total_bayar: "<h6 class=\"number_style\">Rp. " + number_format(totalAll, 2, ".", ",") + "</h6>",
              terbilang: titleCase(terbilang(totalAll))
            },
            success: function(response) {
              $("#modal-cetak").modal("show");
              $("#cetak").html(response);
            },
            error: function() {
              //
            }
          });
        },
        error: function(response) {
          console.log(response);
        }
      });
    });

    $("#btnCetakResep").click(function() {
      var dataCetak = $("#target-cetak-resep").html();
      $.ajax({
        async: false,
        url: __HOST__ + "miscellaneous/print_template/resep_print.php",
        beforeSend: function(request) {
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

          var QRConst = document.createElement("DIV");
          $(QRConst).qrcode({
            width: 128,
            height: 128,
            text: targetRM + "\n" +
              targetNamaPasien + "\n" +
              targetTanggalResep + "\n" +
              targetHargaTotal + "\n"
          });

          var imgcanvas = $(QRConst).find("canvas")[0].toDataURL();
          $(printResepContainer).find("#qrcodeImage img").attr({
            src: imgcanvas
          });

          /*var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=" + screen.width + ",height=" + screen.height + ",top=0,left=0");
          win.document.body.innerHTML = $(printResepContainer).html();*/



          $(printResepContainer).printThis({
            /*header: null,
            footer: null,*/
            pageTitle: targetKodeResep,
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

    $("#btnSelesai").click(function() {

      var antrian = targettedData.antrian;
      var asesmen = targettedData.asesmen;
      var departemen = antrian.departemen;
      var kunjungan = antrian.kunjungan;
      var dokter = antrian.dokter;
      var penjamin = antrian.penjamin;

      //Check Ketersediaan obat di apotek dahulu
      var allowProc = false;
      $(".check_batch_avail").each(function() {
        if ($(this).hasClass("text-danger")) {
          allowProc = false;
          return false;
        } else {
          allowProc = true;
        }
      });

      if (allowProc) {
        Swal.fire({
          title: "Selesai Proses Resep?",
          text: "Pastikan batch sudah sesuai. Setelah konfirmasi stok akan terpotong",
          showDenyButton: true,
          confirmButtonText: "Ya",
          denyButtonText: "Tidak",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: __HOSTAPI__ + "/Apotek",
              async: false,
              beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
              },
              data: {
                request: "proses_resep",
                resep: resepUID,
                //antrian: antrian,
                nama_pasien: selectedRMPasien + " - " + selectedNamaPasien,
                asesmen: asesmen,
                kunjungan: kunjungan,
                dokter: dokter,
                penjamin: penjamin,
                departemen: departemen
              },
              type: "POST",
              success: function(response) {
                console.clear();
                console.log(response);
                if (response.response_package.stok_result > 0) {
                  push_socket(__ME__, "resep_selesai_proses", "*", "Resep pasien a/n. " + $("#nama-pasien").html() + " selesai diproses!", "info").then(function() {
                    Swal.fire(
                      "Proses Berhasil!",
                      "Resep berhasil diproses",
                      "success"
                    ).then((result) => {
                      location.href = __HOSTNAME__ + "/apotek/proses";
                    });
                  });
                } else {
                  Swal.fire(
                    "Proses Berhasil!",
                    "Resep berhasil diproses",
                    "success"
                  ).then((result) => {
                    location.href = __HOSTNAME__ + "/apotek/proses";
                  });
                }
              },
              error: function(response) {
                console.log(response);
              }
            });
          }
        });
      }
    });
  });
</script>

<div id="modal-cetak" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-large-title">Check Obat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-lg">
          <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
              <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Detail Resep</h5>
            </div>
            <div class="card-header card-header-tabs-basic nav" role="tablist">
              <a href="#cetak-utama" class="active" data-toggle="tab" role="tab" aria-controls="cetak-utama" aria-selected="true">Resep/Racikan</a>
              <a href="#cetak-kajian" data-toggle="tab" role="tab" aria-selected="false">Kajian Apotek</a>
            </div>
            <div class="card-body tab-content" style="min-height: 100px;">
              <div class="tab-pane active show fade" id="cetak-utama">
                <div class="row">
                  <div class="col-md-12">
                    <div id="cetak"></div>
                  </div>
                  <div class="col-md-12">
                    <button type="button" class="btn btn-purple pull-right" id="btnCetakResep"><i class="fa fa-print"></i> Cetak</button>
                  </div>
                </div>
              </div>
              <div class="tab-pane show fade" id="cetak-kajian">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-bordered largeDataType">
                      <thead class="thead-dark">
                        <tr>
                          <th colspan="2" style="width: 80%">Aspek Kajian</th>
                          <th class="wrap_content">
                            Hasil
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td rowspan="3" class="wrap_content">a.</td>
                          <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                            <b>Aspek Administrasi</b>
                          </td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Resep Lengkap</td>
                          <td id="hasil_kajian_resep_lengkap"></td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Pasien Sesuai</td>
                          <td id="hasil_kajian_pasien_sesuai"></td>
                        </tr>
                        <tr>
                          <td rowspan="3" class="wrap_content">b.</td>
                          <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                            <b>Aspek Farmasetik</b>
                          </td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Benar Obat</td>
                          <td id="hasil_kajian_benar_obat"></td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Benar Bentuk/Kekuatan/Jumlah</td>
                          <td id="hasil_kajian_benar_bentuk"></td>
                        </tr>
                        <tr>
                          <td rowspan="6" class="wrap_content">c.</td>
                          <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                            <b>Aspek Klinik</b>
                          </td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Benar Dosis/Frekuensi/Aturan Pakai</td>
                          <td id="hasil_kajian_benar_dosis"></td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Benar Rute Pemberian</td>
                          <td id="hasil_kajian_benar_rute"></td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Tidak Ada Interaksi Obat</td>
                          <td id="hasil_kajian_interaksi"></td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Tidak Ada Duplikasi</td>
                          <td id="hasil_kajian_duplikasi"></td>
                        </tr>
                        <tr>
                          <td style="padding-left: 30px">Tidak Alergi/Kontradiksi</td>
                          <td id="hasil_kajian_alergi"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
      </div>
    </div>
  </div>
</div>