<script type="text/javascript">
    $(function () {
        $.ajax({
            url:__HOSTAPI__ + "/Apotek/detail_resep/" + __PAGES__[3],
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                var data = response.response_package.response_data[0];
                console.log(data);
                var autoNumResep = 1;
                for(var key in data.detail)
                {
                    var resepRow = document.createElement("TR");

                    var resepID = document.createElement("TD");
                    var resepObat = document.createElement("TD");
                    var resepSigna = document.createElement("TD");
                    var resepJumlah = document.createElement("TD");
                    var resepHarga = document.createElement("TD");
                    var resepTotal = document.createElement("TD");
                    var resepAction = document.createElement("TD");


                    var currentBatch = {};
                    var currentProfit = "N";
                    var currentProfitType = 0;
                    if(
                        data.detail[key].batch.length > 0 &&
                        data.detail[key].batch !== undefined &&
                        data.detail[key].batch !== null
                    )
                    {
                        for(var batchK in data.detail[key].batch)
                        {
                            if(parseFloat(data.detail[key].batch[batchK].harga) > 0)
                            {
                                currentBatch = data.detail[key].batch[batchK];
                                for(var profitK in currentBatch.profit)
                                {
                                    if(currentBatch.profit[profitK].penjamin === data.antrian.penjamin)
                                    {
                                        currentProfit = parseFloat(currentBatch.profit[profitK].profit);
                                        currentProfitType = currentBatch.profit[profitK].profit_type;
                                        break;
                                    }
                                }

                                break;
                            }
                        }
                    } else {
                        currentBatch.kode = "";
                        currentBatch.expired = "";
                        currentBatch.harga = 0;
                    }

                    $(resepID).html(autoNumResep);
                    $(resepObat).html(data.detail[key].detail.nama).attr({
                        "uid" : data.detail[key].detail.uid,
                        "profit" : currentProfit,
                        "profit_type" : currentProfitType,
                        "old-uid" : data.detail[key].detail.uid,
                        "old-profit" : currentProfit,
                        "old-profit_type" : currentProfitType
                    });
                    $(resepSigna).html("<span>" + data.detail[key].signa_qty + "<span> &times; <span>" + data.detail[key].signa_pakai + "</span>");
                    $(resepJumlah).html(data.detail[key].qty).addClass("number_style").attr({
                        "old" : data.detail[key].qty
                    });
                    $(resepHarga).html(currentBatch.harga).addClass("number_style");
                    $(resepTotal).html(parseFloat(currentBatch.harga) * parseFloat(data.detail[key].qty)).addClass("number_style");

                    $(resepAction).html("<button class=\"btn btn-info btn-sm\">Revisi</button>");
                    //=================================
                    $(resepRow).append(resepID);
                    $(resepRow).append(resepObat);
                    $(resepRow).append(resepSigna);
                    $(resepRow).append(resepJumlah);
                    $(resepRow).append(resepHarga);
                    $(resepRow).append(resepTotal);
                    $(resepRow).append(resepAction);

                    $("#table-resep tbody").append(resepRow);
                    autoNumResep++;
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    });
</script>