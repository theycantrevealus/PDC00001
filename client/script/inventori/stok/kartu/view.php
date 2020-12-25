<script type="text/javascript">
    $(function () {
        let targetID = __PAGES__[4];

        //Get Item Detail
        $.ajax({
            url:__HOSTAPI__ + "/Inventori/kartu_stok/" + targetID + "/" + __UNIT__.gudang,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(resp) {
                var data = resp.response_package.response_data[0];

                $("#nama_barang").html(data.nama);
                $("#kemasan_barang").html(data.satuan_terkecil_info.nama);

                for(var a in data.log) {
                    var newRow = document.createElement("TR");
                    var newTgl = document.createElement("TD");
                    var newDoc = document.createElement("TD");
                    var newUraian = document.createElement("TD");
                    var newMasuk = document.createElement("TD");
                    var newKeluar = document.createElement("TD");
                    var newSaldo = document.createElement("TD");
                    var newKeterangan = document.createElement("TD");

                    $(newTgl).html(data.log[a].logged_at);
                    $(newDoc).html(data.log[a].dokumen);
                    $(newMasuk).html(number_format(data.log[a].masuk, 0, ",", ".")).addClass("number_style");
                    $(newKeluar).html(number_format(data.log[a].keluar, 0, ",", ".")).addClass("number_style");
                    $(newSaldo).html(number_format(data.log[a].saldo, 0, ",", ".")).addClass("number_style");



                    $(newRow).append(newTgl);
                    $(newRow).append(newDoc);
                    $(newRow).append(newUraian);
                    $(newRow).append(newMasuk);
                    $(newRow).append(newKeluar);
                    $(newRow).append(newSaldo);
                    $(newRow).append(newKeterangan);


                    $("#table-item-log tbody").append(newRow);
                }
            },
            error: function(resp) {
                console.log(resp);
            }
        });
    });
</script>