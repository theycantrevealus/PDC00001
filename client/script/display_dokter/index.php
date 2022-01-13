<script type="text/javascript">
$(function() {

    protocolLib = {
        loggedIn: function(protocols, type, parameter, sender, receiver, time) {
            refreshAbsense();
        },
        loggedOut: function(protocols, type, parameter, sender, receiver, time) {
            refreshAbsense();
        },
    };

    refreshAbsense();

    function refreshAbsense() {
        $.ajax({
            url:__HOSTAPI__ + "/Pegawai/kehadiran_dokter",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                $("#kehadiranDokter").html("");
                var online = [];
                var offline = [];
                var data = response.response_package.response_data;
                
                for(var a in data) {
                    if(data[a].logged_in === "A") {
                        online.push(data[a]);
                    } else {
                        offline.push(data[a]);
                    }
                }

                // online = online.concat(offline);
                online = online;

                var inRow = 1;
                var inGroup = 0;
                $("#kehadiranDokter").append("<div class=\"carousel-item active\"><div class=\"row\"></div></div>");
                for(var a in online) {
                    var dataPoli = [];
                    for(var b in online[a].poli) {
                        if(online[a].poli[b].poli !== null) {
                            dataPoli.push(online[a].poli[b].poli);
                        }
                    }
                    if(inRow > __AD_DOKTER_COUNT__) {
                        $("#kehadiranDokter").append("<div class=\"carousel-item\"><div class=\"row\"></div></div>");
                        inRow = 0;
                        inGroup++;
                    } else {
                        $(".carousel-item:eq(" + inGroup + ") div.row").append("<div class=\"col-lg-3\">" +
                            "<div class=\"card\">"+
                                "<div class=\"card-body\"><h4 class=\"" + (online[a].logged_in === "A" ? "text-success" : "") + "\">" + online[a].nama + "</h4>Poliklinik:<br /><h5 class=\"" + (online[a].logged_in === "A" ? "text-success" : "") + "\">" + ((dataPoli.length > 0) ? dataPoli.join(", ") : "-") + "</h5></div>" +
                            "</div>" +
                        "</div>");
                    }
                    inRow++;
                }

                /* for(var a in offline) {
                    var dataPoli = [];
                    for(var b in offline[a].poli) {
                        if(offline[a].poli[b].poli !== null) {
                            dataPoli.push(offline[a].poli[b].poli);
                        }
                    }
                    $("#kehadiranDokter").append("<div class=\"col-lg-3\">" +
                        "<div class=\"card " + (offline[a].logged_in === "A" ? "" : "") + "\">"+
                            "<div class=\"card-body\"><h4>" + offline[a].nama + "</h4>Poliklinik:<br /><h5>" + ((dataPoli.length > 0) ? dataPoli.join(", ") : "-") + "</h5></div>" +
                        "</div>" +
                    "</div>");
                } */
            },
            error:function(response) {
                console.clear();
                console.log(response);
            }
        });
    }
});
</script>