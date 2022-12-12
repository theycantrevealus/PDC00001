<script src="<?php echo __HOSTNAME__; ?>/plugins/range-slider-master/js/rSlider.min.js"></script>
<link href="<?php echo __HOSTNAME__; ?>/plugins/paginationjs/pagination.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
    $(function() {

        var d = new Date();







        function renderScale(target, targetInstances) {
            var NRScurrent = parseInt(target);

            var NRSchildTarget = 0;


            if(NRScurrent % 2 === 0) {
                NRSchildTarget = (NRScurrent / 2);
            } else {
                NRSchildTarget = ((NRScurrent - 1) / 2);
            }

            var colorStyleNRS =  [
                "linear-gradient(90deg, rgba(54, 198, 0, 1), rgba(102, 198, 0, 1))",
                "linear-gradient(90deg, rgba(102, 198, 0, 1), rgba(126, 198, 0, 1))",
                "linear-gradient(90deg, rgba(126, 198, 0, 1), rgba(192, 198, 12, 1))",
                "linear-gradient(90deg, rgba(192, 198, 12, 1), rgba(238, 166, 1, 1))",
                "linear-gradient(90deg, rgba(238, 166, 1, 1), rgba(238, 51, 1, 1))",
                "linear-gradient(90deg, rgba(238, 51, 1, 1), rgba(255, 0, 0, 1))"
            ];

            $(targetInstances).find(".NRSItems").each(function() {
                var currentIDNRS = $(this).attr("id").split("-");
                currentIDNRS = currentIDNRS[currentIDNRS.length - 1];
                if(currentIDNRS <= NRSchildTarget) {
                    $(this).css({
                        "opacity": "1",
                        "background": colorStyleNRS[currentIDNRS]
                    });
                } else {
                    $(this).css({
                        "opacity": ".5",
                        "background": "#ccc"
                    });
                }
            });
        }




        var allData = {};
        var uid_antrian = __PAGES__[3];
        var dataPasien = loadPasien(uid_antrian);


        
        



        var rangeDefiner = [
            {
                text: "",
                merge: 0
            }, {
                text: "Tidak",
                merge: 1
            }, {
                text: "Ringan",
                merge: 2
            }, {
                text: "Sedang",
                merge: 3
            }, {
                text: "Berat",
                merge: 3
            }, {
                text: "Berat Sekali",
                merge: 1
            }
        ];

        var sliderIGDBiasa, sliderIGDBidan;

        function renderSlider(id) {
            let target;
            return new Promise((resolve, reject) => {
                target = new rSlider({
                    target: id,
                    values: [0,1,2,3,4,5,6,7,8,9,10]
                });

                resolve(target);
            });
        }



        // $("a[data-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
        //     var targetPage = $(this).attr("href");
        //     if(dataPasien.antrian.departemen === __POLI_IGD__) {
        //         if(targetPage === "#tab-assesment-awal-igd-1") {
        //             if(sliderIGDBiasa === undefined) {
        //                 $("#scale-loader-image").html("");
        //                 renderSlider("#txt_nrs").then(function (resolve, reject) {
        //                     sliderIGDBiasa = resolve;

        //                     var imageCounter = 0;
        //                     $("#nrs_1 .rs-scale span").each(function(e) {
        //                         $(this).css({
        //                             "position":"relative"
        //                         });

        //                         if(e % 2 == 0) {
        //                             var imagesScale = __HOSTNAME__ + "/template/assets/images/NRS-" + imageCounter + ".png";
        //                             var marginLeft = $(this).find("ins").offset().left - $("#nrs_1 .rs-scale span").eq(0).offset().left - 30;
        //                             var imageViewer = document.createElement("IMG");
        //                             $(imageViewer).attr({
        //                                 "src": imagesScale,
        //                                 "id": "NRS-" + imageCounter
        //                             }).css({
        //                                 "position": "absolute",
        //                                 "top": "0",
        //                                 "left": marginLeft + "px",
        //                                 "width": "100px",
        //                                 "height": "100px",
        //                                 "border-radius": "100%"
        //                             }).addClass("NRSItems biasa");
        //                             $("#scale-loader-image").append(imageViewer);
        //                             imageCounter++;
        //                         }
        //                     });
        //                 });
        //             }
        //         } else if(targetPage === "#tab-assesment-bidan-igd-1a") {
        //             if(sliderIGDBidan === undefined) {
        //                 $("#scale-loader-image-bidan").html("");
        //                 renderSlider("#txt_nrs_bidan").then(function (resolve, reject) {
        //                     sliderIGDBidan = resolve;

        //                     var imageCounter = 0;
        //                     $("#nrs_2 .rs-scale span").each(function(e) {
        //                         $(this).css({
        //                             "position":"relative"
        //                         });

        //                         if(e % 2 == 0) {
        //                             var imagesScale = __HOSTNAME__ + "/template/assets/images/NRS-" + imageCounter + ".png";
        //                             var marginLeft = $(this).find("ins").offset().left - $("#nrs_2 .rs-scale span").eq(0).offset().left - 30;
        //                             var imageViewer = document.createElement("IMG");
        //                             $(imageViewer).attr({
        //                                 "src": imagesScale,
        //                                 "id": "NRS-" + imageCounter
        //                             }).css({
        //                                 "position": "absolute",
        //                                 "top": "0",
        //                                 "left": marginLeft + "px",
        //                                 "width": "100px",
        //                                 "height": "100px",
        //                                 "border-radius": "100%"
        //                             }).addClass("NRSItems bidan");
        //                             $("#scale-loader-image-bidan").append(imageViewer);
        //                             imageCounter++;
        //                         }
        //                     });
        //                 });
        //             }
        //         }
        //     }
        // });

        //IGD
        // if(dataPasien.antrian.departemen === __POLI_IGD__) {

        //     console.log('aaaaa')


        //     $("body").on("click", ".NRSItems", function() {
        //         var currentIDNRS = $(this).attr("id").split("-");
        //         currentIDNRS = currentIDNRS[currentIDNRS.length - 1];

        //         //alert(currentIDNRS);

        //         if(parseInt(currentIDNRS) === 0) {
        //             mySlider.setValues(0);
        //             mySlider.destroy();
        //             mySlider = new rSlider({
        //                 target: "#txt_nrs",
        //                 values: [0,1,2,3,4,5,6,7,8,9,10]
        //             });

        //         } else {
        //             mySlider.setValues(parseInt(currentIDNRS) * 2);
        //         }

        //     });



        //     $("body #nrs_1").on("DOMSubtreeModified", ".rs-tooltip", function() {
        //         renderScale($("#nrs_1 .rs-tooltip").html(), "#nrs_1");
        //     });

        //     $("body #nrs_2").on("DOMSubtreeModified", ".rs-tooltip", function() {
        //         renderScale($("#nrs_2 .rs-tooltip").html(), "#nrs_2");
        //     });

        // }

        /*setInterval(function () {
            var d = new Date();
            $(".last-infus td:eq(0)").html(d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds());
        }, 1000);*/



        //$(".tab-igd").remove();



        /*if(dataPasien.antrian.departemen !== __POLI_IGD__) {
            $(".tab-igd").remove();
        } else {
            //$(".tab-pane").removeClass("active");
            $(".tab-igd:nth-child(1)").addClass("active");
            $("#tab-assesment-awal-igd-1").addClass("active");
            $(".tab-irm").remove();
            $(".tab-biasa").remove();
        }*/



        $(".select2").select2({});

        $("#pj_pasien").prop("disabled", true).attr({
            "disabled": "disabled"
        });

        
        $("input[name='asal_masuk_option']").on('change', function(){
        	let value = $(this).val();
        	disableLainnya('asal_masuk', value, "y");
        });


        $("input[name='riwayat_penyakit_option']").on('change', function(){
        	let value = $(this).val();
        	disableLainnya('riwayat_penyakit', value, "y");
        });

        
        $("input[name='riwayat_operasi_option']").on('change', function(){
        	let value = $(this).val();
        	disableLainnya('riwayat_operasi', value, "y");
        });


        $("input[name='riwayat_pengobatan_option']").on('change', function(){
        	let value = $(this).val();
            console.log(value);
        	disableLainnya('riwayat_pengobatan', value, "y");
        });


        $("input[name='status_hamil_option']").on('change', function(){
        	let value = $(this).val();
        	disableLainnya('status_hamil_g', value, "y");
            disableLainnya('status_hamil_a', value, "y");
            disableLainnya('status_hamil_p', value, "y");
            disableLainnya('status_hamil_h', value, "y");
        });

        if(dataPasien.asesmen_rawat == ""){
            simpanAsesmen(allData, dataPasien, $("#btnSelesai"));
        }

        function simpanAsesmen(allData, dataPasien, btnSelesai,  redirect = "N") {
            $(".inputan").each(function(){
                var value = $(this).val();

                if (value != "" && value != null){
                    $this = $(this);
                    var name = $(this).attr("id");

                    if(name !== undefined) {

                        allData[name] = value;
                    } else {
                        //
                    }
                }
            });

            $("input[type=checkbox]:not(:checked)").each(function(){
                var name = $(this).attr("id");
                allData[name] = null;
            });

            $("input[type=checkbox]:checked").each(function(){
                var name = $(this).attr("id");
                allData[name] = 1;
            });

            $("input[type=radio]:checked").each(function(){
                var value = $(this).val();
                
                if (value != ""){
                    var name = $(this).attr("name");
                    allData[name] = value;
                }
            });

            var partusList = [];

            // $("#riwayat_hamil tbody tr").each(function(e) {
            //     var tanggal_partus = $(this).find("td:eq(1)").attr("tanggal");
            //     var usia_kehamilan = $(this).find("td:eq(2)").html();
            //     var tempat_partus = $(this).find("td:eq(3)").html();
            //     var jenis_partus = $(this).find("td:eq(4)").html();
            //     var penolong = $(this).find("td:eq(5)").html();
            //     var nifas = $(this).find("td:eq(6)").html();
            //     var jenkel_anak = $(this).find("td:eq(7)").html();
            //     var bb_anak = $(this).find("td:eq(8)").html();
            //     var keadaan_sekarang = $(this).find("td:eq(9)").html();
            //     var keterangan = $(this).find("td:eq(10)").html();

            //     partusList.push({
            //         tanggal: tanggal_partus,
            //         usia: usia_kehamilan,
            //         tempat: tempat_partus,
            //         jenis: jenis_partus,
            //         penolong: penolong,
            //         nifas: nifas,
            //         jenkel_anak: jenkel_anak,
            //         bb_anak: bb_anak,
            //         keadaan_sekarang: keadaan_sekarang,
            //         keterangan: keterangan
            //     });
            // });

            allData["partus_list"] = partusList;

            // delete allData['simetris'];
            delete allData['asal_masuk_option'];
            delete allData['riwayat_penyakit_option'];
            delete allData['riwayat_operasi_option'];
            delete allData['riwayat_pengobatan_option'];
            delete allData['status_hamil_option'];
            // delete allData['riwayat_miras_option'];
            // delete allData['riwayat_obt_terlarang_option'];
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Asesmen",
                data: {
                    request : "update_asesmen_rawat",
                    dataAntrian : dataPasien.antrian,
                    dataPasien: dataPasien.pasien,
                    dataObj : allData
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response){
                    btnSelesai.removeAttr("disabled");
                    // console.clear();
                    console.log(response);
                    if(
                        response.response_package.response_result > 0 ||
                        response.response_package.asesmen.response_result > 0
                    ) {
                        if(redirect === "Y") {  
                            notification ("success", "Berhasil Simpan Data", 3000, "hasil_tambah_dev");
                            location.href = __HOSTNAME__ + "/igdv2/perawat/asesmen-detail/" + dataPasien.pasien.uid + "/" + dataPasien.antrian.kunjungan + "/" + dataPasien.antrian.penjamin;
                        }
                    } else {
                        notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                    }


                },
                error: function(response) {
                    btnSelesai.removeAttr("disabled");
                    console.log("Error : ");
                    console.log(response);
                }
            });
        }

        $("#btnSelesai").on('click', function(){
            var btnSelesai = $(this);
            btnSelesai.attr('disabled', 'disabled');

            Swal.fire({
                title: "Simpan Asesmen Rawat?",
                showDenyButton: true,
                type: 'warning',
                confirmButtonText: `Ya`,
                confirmButtonColor: `#1297fb`,
                denyButtonText: `Batal`,
                denyButtonColor: `#ff2a2a`
            }).then((result) => {
                if (result.isConfirmed) {
                    simpanAsesmen(allData, dataPasien, btnSelesai, "Y");



                    /*$(".inputan").each(function(){
                        var value = $(this).val();

                        if (value != "" && value != null){
                            $this = $(this);
                            var name = $(this).attr("id");

                            if(name !== undefined) {

                                allData[name] = value;
                            } else {
                                //
                            }
                        }
                    });

                    $("input[type=checkbox]:not(:checked)").each(function(){
                        var name = $(this).attr("id");
                        allData[name] = null;
                    });

                    $("input[type=checkbox]:checked").each(function(){
                        var name = $(this).attr("id");
                        allData[name] = 1;
                    });

                    $("input[type=radio]:checked").each(function(){
                        var value = $(this).val();
                        if (value != ""){
                            var name = $(this).attr("name");
                            allData[name] = value;
                        }
                    });

                    var partusList = [];

                    $("#riwayat_hamil tbody tr").each(function(e) {
                        var tanggal_partus = $(this).find("td:eq(1)").attr("tanggal");
                        var usia_kehamilan = $(this).find("td:eq(2)").html();
                        var tempat_partus = $(this).find("td:eq(3)").html();
                        var jenis_partus = $(this).find("td:eq(4)").html();
                        var penolong = $(this).find("td:eq(5)").html();
                        var nifas = $(this).find("td:eq(6)").html();
                        var jenkel_anak = $(this).find("td:eq(7)").html();
                        var bb_anak = $(this).find("td:eq(8)").html();
                        var keadaan_sekarang = $(this).find("td:eq(9)").html();
                        var keterangan = $(this).find("td:eq(10)").html();

                        partusList.push({
                            tanggal: tanggal_partus,
                            usia: usia_kehamilan,
                            tempat: tempat_partus,
                            jenis: jenis_partus,
                            penolong: penolong,
                            nifas: nifas,
                            jenkel_anak: jenkel_anak,
                            bb_anak: bb_anak,
                            keadaan_sekarang: keadaan_sekarang,
                            keterangan: keterangan
                        });
                    });

                    allData["partus_list"] = partusList;

                    delete allData['riwayat_merokok_option'];
                    delete allData['riwayat_miras_option'];
                    delete allData['riwayat_obt_terlarang_option'];

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Asesmen",
                        data: {
                            request : "update_asesmen_rawat",
                            dataAntrian : dataPasien.antrian,
                            dataPasien: dataPasien.pasien,
                            dataObj : allData
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        success: function(response){
                            btnSelesai.removeAttr("disabled");
                            if(response.response_package.response_result > 0) {
                                //
                            } else {
                                //notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                            }

                            location.href = __HOSTNAME__ + '/rawat_jalan/perawat';
                        },
                        error: function(response) {
                            btnSelesai.removeAttr("disabled");
                            console.log("Error : ");
                            console.log(response);
                        }
                    });*/
                } else {
                    btnSelesai.removeAttr("disabled");
                }
            });


            // /console.log(dataPasien.antrian);
        });

        

    



        $("body").on("click", ".btn-delete-infus", function () {
            var target = $(this).attr("target");
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var server_id = $(this).attr("server-id");
            var asesmen = $(this).attr("asesmen");

            var me = $(this);

            Swal.fire({
                title: "Hapus Jadwal Infus?",
                showDenyButton: true,
                type: 'warning',
                confirmButtonText: `Ya`,
                confirmButtonColor: `#1297fb`,
                denyButtonText: `Batal`,
                denyButtonColor: `#ff2a2a`
            }).then((result) => {
                if (result.isConfirmed) {
                    me.addClass("btn-warning").removeClass("btn-danger").html("<span><i class=\"fa fa-hourglass-half\"></i> Processing...</span>");
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Asesmen",
                        type: "POST",
                        data: {
                            request: "hapus_igd_infus",
                            id: server_id,
                            asesmen: asesmen,
                            dataAntrian: dataPasien.antrian
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            var data = response.response_package.response_result;

                            if(data > 0) {
                                loadPasien(uid_antrian);
                                // autoInfus("#autoInfusBidan", "bidan");
                                autoInfus("#autoInfusBiasa", "biasa");
                                me.addClass("btn-danger").removeClass("btn-warning").html("<span><i class=\"fa fa-trash-alt\"></i> Hapus</span>");
                            } else {
                                console.log(response);
                                me.addClass("btn-danger").removeClass("btn-warning").html("<span><i class=\"fa fa-trash-alt\"></i> Hapus</span>");
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            me.addClass("btn-danger").removeClass("btn-warning").html("<span><i class=\"fa fa-trash-alt\"></i> Hapus</span>");
                        }
                    });
                }
            });
        });

        // autoInfus("#autoInfusBidan", "bidan");
        autoInfus("#autoInfusBiasa", "biasa");

        $("body").on("click", ".btn-approve-infus", function () {
            var target = $(this).attr("target");
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            var me = $(this);


            var obat = $("#obat_auto_infus_" + target + "_" + id).val();
            var dosis = $("#dosis_auto_infus_" + target + "_" + id).val();
            var rute = $("#rute_auto_infus_" + target + "_" + id).val();
            var libat = $("#libat_auto_infus_" + target + "_" + id).val();

            if(
                obat !== null &&
                obat !== undefined &&
                obat !== "" &&

                dosis !== null &&
                dosis !== undefined &&
                dosis !== "" &&

                rute !== null &&
                rute !== undefined &&
                rute !== "" &&

                libat !== null &&
                libat !== undefined &&
                libat !== ""
            ) {
                me.addClass("btn-warning").removeClass("btn-success").html("<span><i class=\"fa fa-hourglass-half\"></i> Processing...</span>");
                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + "/Asesmen",
                    type: "POST",
                    data: {
                        request: "update_igd_infus",
                        obat: obat,
                        dosis: dosis,
                        rute: rute,
                        libat: libat,
                        target: target,
                        dataAntrian: dataPasien.antrian
                    },
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function (response) {
                        var data = response.response_package.response_result;
                        if(data > 0) {
                            var d = new Date();
                            /*$("#row_auto_infus_" + target + "_" + id + " td").each(function(e) {
                                if(e === 0) {
                                    $(this).html(d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds());
                                }

                                if(e === 1) {
                                    var getObat = $(this).find("select option:selected").text();
                                    $(this).html(getObat);
                                    $(this).find("DIV").css({
                                        "color": "#112b4a"
                                    });
                                }

                                if(e === 2) {
                                    var getDosis = $(this).find("input").val();
                                    $(this).html(getDosis);
                                }

                                if(e === 3) {
                                    var getRute = $(this).find("input").val();
                                    $(this).html(getRute);
                                }

                                if(e === 4) {
                                    var getLibat = $(this).find("input").val();
                                    $(this).html(getLibat);
                                }
                            });*/

                            // loadPasien(uid_antrian);
                            // autoInfus("#autoInfusBidan", "bidan");
                            autoInfus("#autoInfusBiasa", "biasa");

                            /*if(target === "bidan") {
                                autoInfus("#autoInfusBidan", target);
                            } else {
                                autoInfus("#autoInfusBiasa", target);
                            }*/

                            me.parent().html("<button class=\"btn btn-danger btn-sm btn-delete-infus\" id=\"btn_delete_infus_" + target + "_" + id + "\"><span><i class=\"fa fa-trash-alt\"></i> Hapus </span></button>");
                            //me.remove();
                        } else {
                            console.log(response);
                            me.addClass("btn-success").removeClass("btn-warning").html("<span><i class=\"fa fa-check\"></i> OK</span>");
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        me.addClass("btn-success").removeClass("btn-warning").html("<span><i class=\"fa fa-check\"></i> OK</span>");
                    }
                });
            } else {

            }
        });
    });

    function loadTermSelectBox(selector, id_term, selected = ""){
        $.ajax({
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                    for(i = 0; i < MetaData.length; i++){
                        var selection = document.createElement("OPTION");
                        if(MetaData[i].id == selected) {
                            $(selection).attr("selected", "selected");
                        }

                        $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                        $("#" + selector).append(selection);
                    }
                }

            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function disableCheckboxChild(parent, child){
        if (parent.checked == true){
            $("." + child).removeAttr("disabled");
        } else {
            $("." + child).val("").attr("disabled",true);
        }
    }

    function disableElementSelectBox(selector, value){
        let $this = $("." + selector);

        if (value == 0 || value == ""){
            $this.attr("disabled",true);

            if ($this.is(':checkbox')) {
                $this.prop('checked',false);
            }
        } else {
            $this.removeAttr("disabled");
        }
    }

    

    function disableLainnya(child_selector, value, comparison_value){
        let $this = $("." + child_selector);

        if (value == comparison_value){
            $this.removeAttr("disabled");
        } else {
            $this.attr("disabled",true);
        }
    }

    function loadPasien(params) {
        var MetaData = null;

        if (params != ""){
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Asesmen/asesmen-rawat-detail-2/" + params,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    if (response.response_package != ""){
                        MetaData = response.response_package;

                        var uidPasien = "";
                        $.each(MetaData.pasien, function(key, item){
                            if(key === "uid")
                            {
                                uidPasien = item;
                            }
                            $("#" + key).html(item).attr("uid_pasien", uidPasien);
                        });

                        $.each(MetaData.antrian, function(key, item){
                            $("#" + key).val(item);
                        });

                        if (MetaData.antrian.penjamin != __UIDPENJAMINBPJS__) {
                            $(".rujukan-bpjs").attr("hidden", true);
                        } else {
                            //
                        }

                        if (MetaData.pasien.id_jenkel == 2){
                            $(".wanita").attr("hidden",true);
                        } else {
                            $(".pria").attr("hidden",true);
                        }

                        if (MetaData.asesmen_rawat != "") {

                            $.each(MetaData.asesmen_rawat, function(key, item) {
                                $("#" + key).val(item);
                                if(item !== null || item !== "" || item != "") {
                                    $("#" + key).removeAttr("disabled").prop("disabled", false);
                                } /*else {
                                    disableLainnya('cara_masuk_lainnya', cara_masuk, "Lainnya");
                                }*/
                                checkedRadio(key, item);
                                checkedCheckbox(key, item);
                                // if(key == "riwayat_transfusi_golongan_darah") {
                                //     loadTermSelectBox("riwayat_transfusi_golongan_darah", 4, item);
                                // }
                            });

                            if ($("#asal_masuk").val() != ""){
                                let $this = $("input:radio[name='asal_masuk_option'][value='y']");
                                $this.prop('checked', true);

                                $("#asal_masuk").removeAttr("disabled");
                            } else {
                                $("#asal_masuk").attr({
                                    "disabled": "disabled"
                                });
                            }

                            if ($("#riwayat_penyakit").val() != ""){
                                let $this = $("input:radio[name='riwayat_penyakit_option'][value='y']");
                                $this.prop('checked', true);

                                $("#riwayat_penyakit").removeAttr("disabled");
                            } else {
                                $("#riwayat_penyakit").attr({
                                    "disabled": "disabled"
                                });
                            }

                            if ($("#riwayat_operasi").val() != ""){
                                let $this = $("input:radio[name='riwayat_operasi_option'][value='y']");
                                $this.prop('checked', true);

                                $("#riwayat_operasi").removeAttr("disabled");
                            } else {
                                $("#riwayat_operasi").attr({
                                    "disabled": "disabled"
                                });
                            }

                            if ($("#riwayat_pengobatan").val() != ""){
                                let $this = $("input:radio[name='riwayat_pengobatan_option'][value='y']");
                                $this.prop('checked', true);

                                $("#riwayat_pengobatan").removeAttr("disabled");
                            } else {
                                $("#riwayat_pengobatan").attr({
                                    "disabled": "disabled"
                                });
                            }

                            if (
                                $("#status_hamil_g").val() != "" || 
                                $("#status_hamil_a").val() != "" ||
                                $("#status_hamil_p").val() != "" || 
                                $("#status_hamil_h").val() != ""
                            ){
                                let $this = $("input:radio[name='status_hamil_option'][value='y']");
                                $this.prop('checked', true);

                                $("#status_hamil_g").removeAttr("disabled");
                                $("#status_hamil_a").removeAttr("disabled");
                                $("#status_hamil_p").removeAttr("disabled");
                                $("#status_hamil_h").removeAttr("disabled");
                            } else {
                                $("#status_hamil_g").attr({
                                    "disabled": "disabled"
                                });
                                $("#status_hamil_a").attr({
                                    "disabled": "disabled"
                                });
                                $("#status_hamil_p").attr({
                                    "disabled": "disabled"
                                });
                                $("#status_hamil_h").attr({
                                    "disabled": "disabled"
                                });
                            }


                            // let cara_masuk = $("input[name='cara_masuk']").val();
                            // $.each(MetaData.asesmen_rawat, function(key, item) {
                            //     $("#" + key).val(item);
                            //     if(item !== null || item !== "" || item != "") {
                            //         $("#" + key).removeAttr("disabled").prop("disabled", false);
                            //     } else {
                            //         disableLainnya('cara_masuk_lainnya', cara_masuk, "Lainnya");
                            //     }
                            //     checkedRadio(key, item);
                            //     checkedCheckbox(key, item);
                            //     // if(key == "riwayat_transfusi_golongan_darah") {
                            //     //     loadTermSelectBox("riwayat_transfusi_golongan_darah", 4, item);
                            //     // }
                            // });

                            // let program_kb = $("#program_kb").val();
                            // if (program_kb == 0 || program_kb == "") {
                            //     disableElementSelectBox('jenis-kb', program_kb);
                            // }


                            // //disableLainnya('cara_masuk_lainnya', cara_masuk, "Lainnya");

                            // let rujukan = $("input[name='rujukan']").val();
                            // disableLainnya('ket_rujukan', rujukan, 1);

                            // if ($("#riwayat_keluarga_lainnya").is(':checked')) {
                            //     $(".riwayat_keluarga_lainnya_ket").removeAttr("disabled");
                            // }

                            // if ($("#nyeri_lainnya").is(':checked')) {
                            //     $(".nyeri_lainnya_ket").removeAttr("disabled");
                            // }

                            // let bicara = $("#komunikasi_bicara").val();
                            // disableLainnya('komunikasi_bicara_lainnya', bicara, "Lainnya");

                            // let hambatan = $("#komunikasi_hambatan").val();
                            // disableLainnya('komunikasi_hambatan_lainnya', hambatan, "Lainnya");

                            // let kebutuhan = $("#komunikasi_kebutuhan_belajar").val();
                            // disableLainnya('komunikasi_kebutuhan_belajar_lainnya', kebutuhan, "Lainnya");

                            // let kaji_jam_ke_dokter = $("#kaji_resiko_ke_dokter").val();
                            // if (kaji_jam_ke_dokter == 0 || kaji_jam_ke_dokter == ""){
                            //     disableElementSelectBox('kaji_resiko_jam_dokter', program_kb);
                            // }

                            // if ($("#tatalaksana_siapkan_obat").is(':checked')) {
                            //     $(".tatalaksana_siapkan_obat_ket").removeAttr("disabled");
                            // }

                            // if ($("#tatalaksana_beri_obat").is(':checked')) {
                            //     $(".tatalaksana_beri_obat_ket").removeAttr("disabled");
                            // }

                            // if ($("#tatalaksana_konsul").is(':checked')) {
                            //     $(".tatalaksana_konsul_ket").removeAttr("disabled");
                            // }

                            // if ($("#riwayat_merokok").val() != ""){
                            //     let $this = $("input:radio[name='riwayat_merokok_option']");
                            //     $this.val("y").prop('checked', true);

                            //     $("#riwayat_merokok").removeAttr("disabled");
                            // } else {
                            //     $("#riwayat_merokok").attr({
                            //         "disabled": "disabled"
                            //     });
                            // }

                            // if ($("#riwayat_miras").val() != ""){
                            //     let $this = $("input:radio[name='riwayat_miras_option']");
                            //     $this.val("y").prop('checked', true);

                            //     $("#riwayat_miras").removeAttr("disabled");
                            // } else {
                            //     $("#riwayat_miras").attr({
                            //         "disabled": "disabled"
                            //     });
                            // }

                            // if ($("#riwayat_obt_terlarang").val() != ""){
                            //     let $this = $("input:radio[name='riwayat_obt_terlarang_option']");
                            //     $this.val("y").prop('checked', true);

                            //     $("#riwayat_obt_terlarang").removeAttr("disabled");
                            // } else {
                            //     $("#riwayat_obt_terlarang").attr({
                            //         "disabled": "disabled"
                            //     });
                            // }
                        }

                        //IGD Modul Infus
                        if(MetaData.asesmen_infus !== undefined) {
                            // $("#autoInfusBidan tbody tr").remove();
                            $("#autoInfusBiasa tbody tr").remove();
                            for(var infusKey in MetaData.asesmen_infus) {
                                if(MetaData.asesmen_infus[infusKey].igd_type === "bidan") {
                                    autoInfus("#autoInfusBidan", "bidan", {
                                        dihapus_oleh: MetaData.asesmen_infus[infusKey].dihapus_oleh,
                                        deleted_at: MetaData.asesmen_infus[infusKey].deleted_at,
                                        asesmen: MetaData.asesmen_infus[infusKey].asesmen,
                                        serverID: MetaData.asesmen_infus[infusKey].id,
                                        pukul: MetaData.asesmen_infus[infusKey].pukul,
                                        obat: MetaData.asesmen_infus[infusKey].obat,
                                        dosis: MetaData.asesmen_infus[infusKey].dosis,
                                        rute: MetaData.asesmen_infus[infusKey].rute,
                                        keputusan: MetaData.asesmen_infus[infusKey].keputusan,
                                        oleh: MetaData.asesmen_infus[infusKey].oleh
                                    });
                                } else {
                                    autoInfus("#autoInfusBiasa", "biasa", {
                                        dihapus_oleh: MetaData.asesmen_infus[infusKey].dihapus_oleh,
                                        deleted_at: MetaData.asesmen_infus[infusKey].deleted_at,
                                        asesmen: MetaData.asesmen_infus[infusKey].asesmen,
                                        serverID: MetaData.asesmen_infus[infusKey].id,
                                        pukul: MetaData.asesmen_infus[infusKey].pukul,
                                        obat: MetaData.asesmen_infus[infusKey].obat,
                                        dosis: MetaData.asesmen_infus[infusKey].dosis,
                                        rute: MetaData.asesmen_infus[infusKey].rute,
                                        keputusan: MetaData.asesmen_infus[infusKey].keputusan,
                                        oleh: MetaData.asesmen_infus[infusKey].oleh
                                    });
                                }
                            }
                            /*autoInfus("#autoInfusBidan", "bidan");
                            autoInfus("#autoInfusBiasa", "biasa");*/
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        return MetaData;
    }

    function checkedRadio(name, value) {

        var $radios = $('input:radio[name=' + name +']');
        
        if ($radios.length > 0){
            if($radios.is(':checked') === false) {
                if (value != null && value != ""){
                    $radios.filter('[value="'+ value +'"]').prop('checked', true);
                }
            }
        }
    }

    function checkedCheckbox(name, value){
        if (value == 1){
            $('input:checkbox[name='+ name +']').prop('checked', true);
        }
    }


    function autoInfus(targetTable, classify, setter = {}) {
        $(targetTable + " tbody tr").removeClass("last-infus");

        var row = document.createElement("TR");
        var containerPukul = document.createElement("TD");
        var containerObat = document.createElement("TD");
        var containerDosis = document.createElement("TD");
        var containerRute = document.createElement("TD");
        var containerLibat = document.createElement("TD");
        var containerOleh = document.createElement("TD");
        var containerAksi = document.createElement("TD");


        var Obat = document.createElement("SELECT");
        var Dosis = document.createElement("INPUT");
        var Rute = document.createElement("INPUT");
        var Libat = document.createElement("INPUT");

        $(Obat).addClass("form-control auto_infus_obat");
        $(Dosis).addClass("form-control");
        $(Rute).addClass("form-control");
        $(Libat).addClass("form-control");

        if(
            setter.obat !== undefined && setter.obat !== null
        ) {
            $(containerPukul).html(setter.pukul);
            $(containerObat).html("<span>" + setter.obat.nama + "</span>");
            $(containerDosis).html(setter.dosis);
            $(containerRute).html(setter.rute);
            $(containerLibat).html(setter.keputusan);
            $(containerOleh).html(setter.oleh.nama);
            if(setter.deleted_at !== null) {
                $(containerPukul).css({
                    "text-decoration": "line-through"
                });
                $(containerObat).append("<br /><b class=\"text-info\"><i class=\"fa fa-info-circle\"></i> Dihapus oleh : " + setter.dihapus_oleh + "</b>");
                $(containerObat).find("span").css({
                    "text-decoration": "line-through"
                });
                $(containerDosis).css({
                    "text-decoration": "line-through"
                });
                $(containerRute).css({
                    "text-decoration": "line-through"
                });
                $(containerLibat).css({
                    "text-decoration": "line-through"
                });
                $(containerOleh).css({
                    "text-decoration": "line-through"
                });
                $(containerAksi).html("<div class=\"wrap-content\"><i class=\"fa fa-trash-alt\"></i> Terhapus</div>");
            } else {
                $(containerAksi).html("<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                    "<button asesmen=\"" + setter.asesmen + "\" server-id=\"" + setter.serverID + "\" class=\"btn btn-sm btn-danger btn-delete-infus\"><span><i class=\"fa fa-trash-alt\"></i> Hapus</span></button>" +
                    "</div>");
            }
        } else {
            $(containerPukul).html(setter.pukul);
            $(containerObat).append(Obat);
            $(containerDosis).append(Dosis);
            $(containerRute).append(Rute);
            $(containerLibat).append(Libat);
            $(containerOleh).html(__MY_NAME__);
            $(containerAksi).html("<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                "<button class=\"btn btn-sm btn-success btn-approve-infus\" target=\"" + classify + "\"><span><i class=\"fa fa-check\"></i> OK</span></button>" +
                "</div>");
        }


        $(row).append(containerPukul);
        $(row).append(containerObat);
        $(row).append(containerDosis);
        $(row).append(containerRute);
        $(row).append(containerLibat);
        $(row).append(containerOleh);
        $(row).append(containerAksi);

        $(row).addClass("last-infus");
        $(targetTable + " tbody").append(row);

        $(Obat).select2({
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
                url:__HOSTAPI__ + "/Inventori/get_item_resep_obat_select2",
                // url: __HOSTAPI__ + "/Inventori/get_item_select2",
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
                                "id": item.uid,
                                "satuan_terkecil": item.satuan_terkecil.nama,
                                "data-value": item["data-value"],
                                "penjamin-list": item["penjamin"],
                                "satuan-caption": item["satuan-caption"],
                                "satuan-terkecil": item["satuan-terkecil"],
                                "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                "html": 	"<div class=\"select2_item_stock\">" +
                                    "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
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
            $(this).children("[value=\""+ data["id"] + "\"]").attr({
                "data-value": data["data-value"],
                "penjamin-list": data["penjamin-list"],
                "satuan-caption": data["satuan-caption"],
                "satuan-terkecil": data["satuan-terkecil"]
            });
        });

        rebaseInfus(targetTable, classify);
    }

    function rebaseInfus(targetTable, classify) {
        $(targetTable + " tbody tr").each(function (e) {
            var id = (e + 1);
            $(this).attr({
                "id": "row_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(0)").attr({
                "id": "pukul_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(1) select").attr({
                "id": "obat_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(2) input").attr({
                "id": "dosis_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(3) input").attr({
                "id": "rute_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(4) input").attr({
                "id": "libat_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(6) button.btn-approve-infus").attr({
                "id": "approve_auto_infus_" + classify + "_" + id
            });

            $(this).find("td:eq(6) button.btn-delete-infus").attr({
                "id": "hapus_auto_infus_" + classify + "_" + id
            });
        });
    }






</script>
