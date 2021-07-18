<script type="text/javascript">
    $(function () {
        var UID = __PAGES__[4];
        loadCPPT("2021-01-01", "2021-08-01", __PAGES__[3]);
        function loadCPPT(from, to, pasien) {
            $("#cppt_loader").html("");
            $.ajax({
                url: __HOSTAPI__ + "/CPPT",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: {
                    request: "group_tanggal",
                    pasien: pasien,
                    from: from,
                    to: to,
                    current: UID
                },
                success:function(response) {
                    var data = response.response_package;
                    console.clear();
                    //console.log(data);
                    for(var a in data) {
                        $.ajax({
                            url: __HOSTNAME__ + "/pages/pasien/cppt-grouper.php",
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"POST",
                            data: {
                                group_tanggal_caption: data[a].parsed,
                                group_tanggal_name: a
                            },
                            success:function(responseGrouper) {
                                $("#cppt_loader").append(responseGrouper);
                                var listData = data[a].data;
                                for(var b in listData) {
                                    var currentData = listData[b].data[0];
                                    //if(currentData.uid !== UID) {
                                    console.log(currentData);
                                    $.ajax({
                                        url: __HOSTNAME__ + "/pages/pasien/cppt-single.php",
                                        async:false,
                                        beforeSend: function(request) {
                                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                        },
                                        type:"POST",
                                        data: {
                                            currentData: UID,
                                            __HOST__: __HOST__,
                                            __ME__: __ME__,
                                            group_tanggal_name: a,
                                            waktu_masuk: listData[b].parsed,
                                            waktu_masuk_name: listData[b].parsed.replaceAll(":", "_"),
                                            departemen: currentData.departemen.nama,
                                            dokter_uid: currentData.dokter.uid,
                                            dokter: currentData.dokter.nama,
                                            dokter_pic: __HOST__ + currentData.dokter.profile_pic,
                                            icd10_kerja: currentData.asesmen.icd10_kerja,
                                            icd10_banding: currentData.asesmen.icd10_banding,
                                            keluhan_utama:currentData.asesmen.keluhan_utama,
                                            keluhan_tambahan:currentData.asesmen.keluhan_tambahan,
                                            diagnosa_kerja:currentData.asesmen.diagnosa_kerja,
                                            diagnosa_banding:currentData.asesmen.diagnosa_banding,
                                            pemeriksaan_fisik:currentData.asesmen.pemeriksaan_fisik,
                                            planning:currentData.asesmen.planning,
                                            tindakan: currentData.asesmen.tindakan,
                                            resep: currentData.asesmen.resep,
                                            racikan: currentData.asesmen.racikan,
                                            laboratorium: currentData.asesmen.laboratorium,
                                            radiologi: currentData.asesmen.radiologi
                                        },
                                        success:function(responseSingle) {
                                            $("#group_cppt_" + a).append(responseSingle);
                                        },
                                        error: function(responseSingleError) {
                                            console.log(responseSingleError);
                                        }
                                    });
                                    //}
                                }
                            },
                            error: function(responseGrouperError) {
                                console.log(responseGrouperError);
                            }
                        });
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    });
</script>