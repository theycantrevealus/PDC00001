<script type="text/javascript">
    $(function () {
        var resepUID = __PAGES__[3];
        var targettedData = {};
        $.ajax({
            url:__HOSTAPI__ + "/Apotek/detail_resep_lunas/" + resepUID,
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                targettedData = response.response_package.response_data[0];
                console.log(targettedData);
                $("#nama-pasien").attr({
                    "set-penjamin": targettedData.antrian.penjamin_data.uid
                }).html(((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name !== null) ? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");
                loadDetailResep(targettedData);

            },
            error: function(response) {
                console.log(response);
            }
        });





        function loadDetailResep(data) {
            console.clear();
            console.log(data);
            $("#load-detail-resep tbody tr").remove();

            //==================================================================================== RESEP
            var autoResepNum = 1;
            for(var resepKey in data.detail)
            {
                var resepRow = document.createElement("TR");

                var resepIDCell = document.createElement("TD");
                $(resepIDCell).html(autoResepNum);

                var resepObatCell = document.createElement("TD");
                var resepObat = document.createElement("h5");

                $(resepObatCell).append("<span>" + data.detail[resepKey].detail.nama + " <b>[" + data.detail[resepKey].batch[0].kode + " - " + data.detail[resepKey].batch[0].expired + "]</b>" + "</span>").append(resepObat);
                $(resepObat).addClass("text-info").attr({
                    "id" : "revisi_obat_" + autoResepNum,
                    "uid" : data.detail[resepKey].obat
                });

                var resepSignaCell = document.createElement("TD");
                $(resepSignaCell).html(data.detail[resepKey].signa_qty + " &times; " + data.detail[resepKey].signa_pakai);
                var resepJumlahCell = document.createElement("TD");
                $(resepJumlahCell).html(parseFloat(data.detail[resepKey].qty)).attr({
                    "id" : "qty_resep_" + autoResepNum,
                    "old" : data.detail[resepKey].qty
                }).addClass("number_style");
                /*var resepJumlah = document.createElement("INPUT");
                $(resepJumlahCell).append(resepJumlah);
                $(resepJumlah).attr({
                    "id" : "qty_resep_" + autoResepNum
                }).addClass("form-control resep_qty_change").inputmask({
                    alias: 'decimal', rightAlign: true, placeholder: "0", prefix: "", autoGroup: false, digitsOptional: true
                }).val(parseFloat(data.detail[resepKey].qty));*/
                var resepHargaCell = document.createElement("TD");
                $(resepHargaCell).attr({
                    "id" : "harga_resep_" + autoResepNum
                }).html(data.detail[resepKey].batch[0].harga).addClass("number_style");
                var resepTotalCell = document.createElement("TD");
                $(resepTotalCell).attr({
                    "id" : "total_resep_" + autoResepNum
                }).html(data.detail[resepKey].batch[0].harga * parseFloat(data.detail[resepKey].qty)).addClass("number_style");
                var resepEdit = document.createElement("TD");
                $(resepEdit).html("<i class=\"fa fa-pencil-alt edit-resep-item pull-right\" id=\"ubah_resep_" + autoResepNum + "\"></i>").attr({
                    "id" : "edit_reset_change_" + autoResepNum
                });

                $(resepRow).append(resepIDCell);
                $(resepRow).append(resepObatCell);
                $(resepRow).append(resepSignaCell);
                $(resepRow).append(resepJumlahCell);
                $(resepRow).append(resepHargaCell);
                $(resepRow).append(resepTotalCell);
                $(resepRow).append(resepEdit);
                $("#load-detail-resep tbody").append(resepRow);

                autoResepNum += 1;
            }

            //==================================================================================== RACIKAN

            var autoNumRacikan = 1;
            var autoRacikan = 1;
            for(var racikanKey in data.racikan)
            {
                for(var itemRacikanKey in data.racikan[racikanKey].detail)
                {
                    var racikanRow = document.createElement("TR");
                    $(racikanRow).attr({
                        "uid": data.racikan[racikanKey].uid
                    });

                    var racikanID = document.createElement("TD");
                    $(racikanID).html(autoNumRacikan);
                    var racikanName = document.createElement("TD");
                    var racikanSigna = document.createElement("TD");
                    var racikanObat = document.createElement("TD");
                    $(racikanObat).attr({
                        "id": "racikan_kode_" + autoNumRacikan + "_" + autoRacikan,
                        "uid": data.racikan[racikanKey].detail[itemRacikanKey].detail.uid,
                        "old-name": data.racikan[racikanKey].detail[itemRacikanKey].detail.nama
                    }).html(data.racikan[racikanKey].detail[itemRacikanKey].detail.nama + "<h5 id=\"revisi_racikan_" + autoNumRacikan + "_" + autoRacikan +"\"></h5>");
                    var racikanJumlah = document.createElement("TD");
                    $(racikanJumlah).addClass("number_style").attr({
                        "id": "racikan_jumlah_" + autoNumRacikan,
                        "old": data.racikan[racikanKey].qty
                    }).html(data.racikan[racikanKey].qty);
                    var racikanHarga = document.createElement("TD");
                    var targetHargaRacikan = (data.racikan[racikanKey].detail[itemRacikanKey].batch.length > 0) ? data.racikan[racikanKey].detail[itemRacikanKey].batch[0].harga : 0;
                    $(racikanHarga).attr({
                        "id": "racikan_harga_" + autoNumRacikan + "_" + autoRacikan
                    }).html(targetHargaRacikan).addClass("number_style");
                    var racikanTotal = document.createElement("TD");
                    $(racikanTotal).attr({
                        "id": "racikan_total_" + autoNumRacikan + "_" + autoRacikan
                    }).addClass("number_style").html(parseFloat(targetHargaRacikan) * parseFloat(data.racikan[racikanKey].qty));
                    var racikanAction = document.createElement("TD");
                    $(racikanAction).append("<i class=\"fa fa-pencil-alt racikan_action_edit\" id=\"racikan_action_edit_" + autoNumRacikan + "_" + autoRacikan + "\"></i>").attr({
                        "id": "edit_racikan_change_" + autoNumRacikan + "_" + autoRacikan
                    });

                    if(itemRacikanKey == 0)
                    {
                        $(racikanID).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        });

                        $(racikanName).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        }).html("<span>" + data.racikan[itemRacikanKey].kode + "</span>");

                        $(racikanJumlah).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        });

                        $(racikanSigna).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        }).html(data.racikan[itemRacikanKey].signa_qty + " &times; " + data.racikan[itemRacikanKey].signa_pakai);

                        $(racikanRow).append(racikanID);
                        $(racikanRow).append(racikanName);
                        $(racikanRow).append(racikanSigna);
                        $(racikanRow).append(racikanJumlah);
                        $(racikanRow).append(racikanObat);
                        $(racikanRow).append(racikanHarga);
                        $(racikanRow).append(racikanTotal);
                        $(racikanRow).append(racikanAction);
                    } else {
                        $(racikanRow).append(racikanObat);
                        $(racikanRow).append(racikanHarga);
                        $(racikanRow).append(racikanTotal);
                        $(racikanRow).append(racikanAction);
                    }
                    $("#load-detail-racikan tbody").append(racikanRow);
                    autoRacikan++;
                }
                autoNumRacikan ++;
            }
        }
    });
</script>