<script type="text/javascript">
    $(function () {
        function getDateRange(target) {
            var rangeKwitansi = $(target).val().split(" to ");
            if(rangeKwitansi.length > 1) {
                return rangeKwitansi;
            } else {
                return [rangeKwitansi, rangeKwitansi];
            }
        }

        var SEPListUntrack = $("#table-sep-untrack").DataTable({
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            "ajax":{
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_sep_log_untrack";
                },
                dataSrc:function(response) {
                    var dataSet = response.response_package.response_data;
                    if(dataSet === undefined) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    return dataSet;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                }
            ]
        });

        var SEPList = $("#table-sep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            "ajax":{
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_sep_log";
                    d.kartu = "";
                    d.from = getDateRange("#range_sep")[0];
                    d.to = getDateRange("#range_sep")[1];
                },
                dataSrc:function(response) {
                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    return dataSet;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b>NIK: " + row.pasien.nik + "</b><br /><h5><span class=\"text-info number_style\" antrian=\"" + row.antrian + "\" id=\"rm_" + row.uid + "\">(" + row.pasien.no_rm + ")</span> - " + row.pasien.nama + "</h5><b><i class=\"fa fa-phone\"></i> " + row.pasien.no_telp + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"text-info\">" + row.asal_rujukan_ppk + "</span> - " + row.asal_rujukan_nama + "<br /><b>No. " + row.asal_rujukan_nomor + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.sep_no;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.poli_tujuan;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"sep_edit_" + row.sep_no + "\" uid=\"" + row.uid + "\" class=\"btn btn-info btn-sm btn-edit-sep\">" +
                                "<i class=\"fa fa-edit\"></i> Edit" +
                            "</button>" +
                            "<button id=\"sep_cetak_" + row.sep_no + "\" uid=\"" + row.uid + "\" class=\"btn btn-success btn-sm btn-cetak-sep\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<button id=\"sep_delete_" + row.sep_no + "\" uid=\"" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-sep\">" +
                                "<i class=\"fa fa-trash\"></i> Hapus" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#range_sep").change(function() {
            SEPList.ajax.reload();
        });

        $("body").on("click", ".btn-cetak-sep", function() {
            $("#modal-sep-cetak").modal("show");
        });








        //INIT BPJS

        $(".sep").select2();
        $("#txt_bpjs_tanggal_rujukan").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true,
            beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }
        });

        $("#txt_bpjs_laka_tanggal").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true
        }).datepicker("setDate", new Date());



        $(".laka_lantas_suplesi_container").hide();
        $(".laka_lantas_container").hide();


        $("input[type=\"radio\"][name=\"txt_bpjs_laka\"]").change(function() {
            if(parseInt($(this).val()) === 1) {
                $(".laka_lantas_container").fadeIn();
            } else {
                $(".laka_lantas_container").fadeOut();
            }
        });

        $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]").change(function() {
            if(parseInt($(this).val()) === 1) {
                $(".laka_lantas_suplesi_container").fadeIn();
            } else {
                $(".laka_lantas_suplesi_container").fadeOut();
            }
        });

        var selectedLakaPenjamin = [];
        var selectedListRujukan = [];

        $("input[type=\"checkbox\"][name=\"txt_bpjs_laka_penjamin\"]").change(function() {
            var selectedvalue = $(this).val();
            if($(this).is(":checked")) {
                if(selectedLakaPenjamin.indexOf(selectedvalue) < 0)
                {
                    selectedLakaPenjamin.push(selectedvalue);
                }
            } else {
                selectedLakaPenjamin.splice(selectedLakaPenjamin.indexOf(selectedvalue), 1);
            }
        });

        loadKelasRawat();

        loadSpesialistik("#txt_bpjs_dpjp_spesialistik");


        $("#txt_bpjs_laka_suplesi_provinsi").select2({
            dropdownParent: $("#group_provinsi")
        });

        $("#txt_bpjs_dpjp_spesialistik").select2({
            dropdownParent: $("#group_spesialistik")
        });

        $("#txt_bpjs_laka_suplesi_kabupaten").select2({
            dropdownParent: $("#group_kabupaten")
        });

        $("#txt_bpjs_laka_suplesi_kecamatan").select2({
            dropdownParent: $("#group_kecamatan")
        });

        $("#txt_bpjs_nomor_rujukan").select2({
            autoclose: true,
            dropdownParent: $("#group_nomor_rujukan")
        });

        $("#txt_bpjs_dpjp").select2({
            dropdownParent: $("#group_dpjp")
        });

        $("#txt_bpjs_kelas_rawat").select2({
            dropdownParent: $("#group_kelas_rawat")
        });

        $("#txt_bpjs_asal_rujukan").select2({disabled:"readonly"});

        $("#txt_bpjs_jenis_asal_rujukan").select2({disabled:"readonly"});

        $("#txt_bpjs_jenis_asal_rujukan").change(function() {
            loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
        });

        $("#txt_bpjs_dpjp_spesialistik").change(function() {
            loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
        });

        $("#txt_bpjs_nomor_rujukan").change(function() {
            loadInformasiRujukan(selectedListRujukan[$(this).find("option:selected").index()]);
        });

        $("#txt_bpjs_laka_suplesi_provinsi").on("change", function () {
            loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val());
        });

        $("#txt_bpjs_laka_suplesi_kabupaten").on("change", function () {
            loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val());
        });

        $("#txt_bpjs_diagnosa_awal").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#group_diagnosa"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_diagnosa",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.diagnosa;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_poli_tujuan").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Poli tidak ditemukan";
                }
            },
            dropdownParent: $("#group_poli"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_poli",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.poli;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        function loadKelasRawat(){
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_kelas_rawat_select2",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $("#txt_bpjs_kelas_rawat option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $("#txt_bpjs_kelas_rawat").append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadProvinsi(target, selected = "") {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_provinsi",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        if(parseInt(data[a].kode) === parseInt(selected)) {
                            $(selection).attr("selected", "selected");
                        }
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadKabupaten(target, provinsi, selected = "") {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_kabupaten/" + provinsi,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        if(parseInt(data[a].kode) === parseInt(selected)) {
                            $(selection).attr("selected", "selected");
                        }
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadKecamatan(target, kabupaten, selected = "") {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_kecamatan/" + kabupaten,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        if(parseInt(data[a].kode) === parseInt(selected)) {
                            $(selection).attr("selected", "selected");
                        }
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadSpesialistik(target) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_spesialistik",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadDPJP(target, jenis, spesialistik) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_dpjp/" + jenis + "/" + spesialistik,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].kode + " - " + data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadInformasiRujukan(data) {
            $("#txt_bpjs_rujuk_perujuk").html(data.provPerujuk.kode + " - " + data.provPerujuk.nama);
            $("#txt_bpjs_rujuk_tanggal").html(data.tglKunjungan);
            $("#txt_bpjs_rujuk_poli").html(data.poliRujukan.kode + " - " + data.poliRujukan.nama);
            $("#txt_bpjs_rujuk_diagnosa").html(data.diagnosa.kode + " - " + data.diagnosa.nama);
            $("#txt_bpjs_rujuk_keluhan").html((data.keluhan === "") ? "-" : data.keluhan);
            $("#txt_bpjs_rujuk_hak_kelas").html(data.peserta.hakKelas.kode + " - " + data.peserta.hakKelas.keterangan);
            $("#txt_bpjs_rujuk_jenis_peserta").html(data.peserta.jenisPeserta.kode + " - " + data.peserta.jenisPeserta.keterangan);

            $("#txt_bpjs_poli_tujuan").append("<option value=\"" + data.poliRujukan.kode + "\">" + data.poliRujukan.nama + "</option>").val(data.poliRujukan.kode).trigger("change");
            $("#txt_bpjs_diagnosa_awal").append("<option value=\"" + data.diagnosa.kode + "\">" + data.diagnosa.kode + " - " + data.diagnosa.nama + "</option>").val(data.diagnosa.kode).trigger("change");


            var queryDate = data.tglKunjungan,
                dateParts = queryDate.match(/(\d+)/g)
            realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

            $("#txt_bpjs_tanggal_rujukan").datepicker("setDate", realDate);
            $("#txt_bpjs_jenis_asal_rujukan").val(data.provPerujuk.jenis).trigger("change");
            $("#txt_bpjs_asal_rujukan").html("<option value=\"" + data.provPerujuk.info.kode + "\">" + data.provPerujuk.info.kode + " - " + data.provPerujuk.info.nama + "</option>");
        }


        var selectedSEP = "";
        var selectedSEPAntrian = "";
        var selectedSEPUID = "";

        $("body").on("click", ".btn-edit-sep", function () {
            var SEPButton = $(this);
            var targetSEP = $(this).attr("id").split("_");
            targetSEP = targetSEP[targetSEP.length - 1];
            var uid = $(this).attr("uid");
            selectedSEPUID = uid;
            selectedSEP = targetSEP;
            selectedSEPAntrian = $("#rm_" + uid).attr("antrian");

            $("#panel_rujukan_result").hide();
            $("#rujukan_loading").show();

            if(selectedSEP !== "" && selectedSEP !== null) {
                $("#modal-sep-edit").modal("show");
                var RM = $("#rm_" + uid).html();

                $("#txt_bpjs_rm").val(RM);
                alert(selectedSEPAntrian);
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Asesmen/antrian-detail/" + selectedSEPAntrian,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response) {
                        var data = response.response_package.response_data[0];
                        selectedSEPAntriamMeta = data;

                        if(data.pasien_detail === undefined) {
                            Swal.fire(
                                "BPJS",
                                "Data SEP tidak terdeteksi",
                                "warning"
                            ).then((result) => {
                                $("#modal-sep-edit").modal("hide");
                            });
                        } else {
                            var diagnosa_kerja = data.diagnosa_kerja;
                            var diagnosa_banding = data.diagnosa_banding;
                            var icd10_kerja = data.icd10_kerja;
                            var icd10_banding = data.icd10_banding;

                            $("#txt_bpjs_internal_dk").html(diagnosa_kerja);
                            $("#txt_bpjs_internal_db").html(diagnosa_banding);
                            $("#txt_bpjs_nama").val(data.pasien_detail.nama);
                            $("#txt_bpjs_nik").val(data.pasien_detail.nik);
                            $("#txt_bpjs_telepon").val(data.pasien_detail.no_telp);

                            for(var pKey in data.pasien_detail.history_penjamin) {
                                if(data.pasien_detail.history_penjamin[pKey].penjamin === __UIDPENJAMINBPJS__)
                                {
                                    var metaDataBPJS = JSON.parse(data.pasien_detail.history_penjamin[pKey].rest_meta);
                                    selectedSEPNoKartu = metaDataBPJS.response.peserta.noKartu;
                                    $("#txt_bpjs_nomor").val(metaDataBPJS.response.peserta.noKartu);
                                }
                            }

                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/BPJS/get_rujukan_list/" + $("#txt_bpjs_nomor").val(),
                                type: "GET",
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response) {
                                    $("#txt_bpjs_nomor_rujukan " + " option").remove();
                                    $("#txt_bpjs_nomor_rujukan").select2("destroy");
                                    $("#txt_bpjs_nomor_rujukan").select2(/*{disabled:"readonly"}*/);
                                    $("#txt_bpjs_nomor_rujukan").select2("val", "");
                                    if(response.response_package.content.response !== null) {
                                        $("#panel-rujukan").show();
                                        var data = response.response_package.content.response.rujukan;
                                        selectedListRujukan = data;



                                        if(data.length > 0) {
                                            isRujukan = true;
                                            for(var a = 0; a < data.length; a++) {
                                                if(parseInt(data[a].pelayanan.kode) === 2) {
                                                    var selection = document.createElement("OPTION");

                                                    $(selection).attr("value", data[a].noKunjungan.toUpperCase()).html(data[a].noKunjungan.toUpperCase());
                                                    $("#txt_bpjs_nomor_rujukan").append(selection);
                                                }
                                            }

                                            $(".informasi_rujukan").show();
                                            $("#btnProsesSEP").show();
                                            loadInformasiRujukan(selectedListRujukan[0]);
                                            loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_asal_rujukan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                                        } else {
                                            isRujukan = false;
                                            $(".informasi_rujukan").hide();
                                            $("#btnProsesSEP").hide();
                                        }
                                    } else {
                                        isRujukan = false
                                        $(".informasi_rujukan").hide();
                                        $("#panel-rujukan").hide();
                                        $("#btnProsesSEP").hide();
                                    }
                                    $("#rujukan_loading").hide();
                                    $("#panel_rujukan_result").fadeIn();
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });


                            for(var dKey in icd10_kerja)
                            {
                                $("#txt_bpjs_internal_icdk").append("<li>" + icd10_kerja[dKey].nama + "</li>");
                            }

                            for(var dKey in icd10_banding)
                            {
                                $("#txt_bpjs_internal_icdb").append("<li>" + icd10_banding[dKey].nama + "</li>");
                            }



                            //Load SEP Detail
                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/BPJS/get_sep_detail/" + uid,
                                type: "GET",
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response){
                                    $("#txt_bpjs_poli_tujuan").append("<option value=\"" + response.response_package.response_data[0].poli_tujuan_detail.kode + "\">" + response.response_package.response_data[0].poli_tujuan_detail.nama + "</option>").val(response.response_package.response_data[0].poli_tujuan_detail.kode).trigger("change")/*.select2({
                                    disabled:"readonly"
                                })*/;

                                    $("input[name=\"txt_bpjs_poli_eksekutif\"][value=\"" + response.response_package.response_data[0].poli_eksekutif + "\"]").prop("checked", true);
                                    $("#txt_bpjs_catatan").val(response.response_package.response_data[0].catatan);
                                    $("#txt_bpjs_skdp").val(response.response_package.response_data[0].skdp);
                                    $("input[name=\"txt_bpjs_cob\"][value=\"" + response.response_package.response_data[0].pasien_cob + "\"]").prop("checked", true);
                                    $("input[name=\"txt_bpjs_katarak\"][value=\"" + response.response_package.response_data[0].pasien_katarak + "\"]").prop("checked", true);
                                    $("input[name=\"txt_bpjs_laka\"][value=\"" + response.response_package.response_data[0].laka_lantas + "\"]").prop("checked", true).trigger("change");
                                    var lakaPenjamin = response.response_package.response_data[0].laka_lantas_penjamin.split(",");
                                    for(var laka_pKey in lakaPenjamin) {
                                        $("input[name=\"txt_bpjs_laka_penjamin\"][value=\"" + lakaPenjamin[laka_pKey] + "\"]").prop("checked", true);
                                    }

                                    if(response.response_package.response_data[0].laka_lantas_tanggal !== null) {
                                        var lakaTanggal = response.response_package.response_data[0].laka_lantas_tanggal, datePartsLaka = lakaTanggal.match(/(\d+)/g)
                                        realDateLaka = new Date(datePartsLaka[0], datePartsLaka[1] - 1, datePartsLaka[2]);
                                        $("#txt_bpjs_laka_tanggal").datepicker("setDate", realDateLaka);
                                    }

                                    $("#txt_bpjs_laka_keterangan").val(response.response_package.response_data[0].laka_lantas_keterangan);
                                    $("input[name=\"txt_bpjs_laka_suplesi\"][value=\"" + response.response_package.response_data[0].laka_lantas_suplesi + "\"]").prop("checked", true).trigger("change");
                                    $("#txt_bpjs_laka_suplesi_nomor").val(response.response_package.response_data[0].laka_lantas_suplesi_sep);

                                    loadProvinsi("#txt_bpjs_laka_suplesi_provinsi", response.response_package.response_data[0].laka_lantas_provinsi);
                                    loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val(), response.response_package.response_data[0].laka_lantas_kabupaten);
                                    loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val(), response.response_package.response_data[0].laka_lantas_kecamatan);

                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });

                            SEPButton.html("Daftar SEP").removeClass("btn-warning").addClass("btn-info");
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        });


        $("#btnProsesSEP").click(function() {
            Swal.fire({
                title: "Ubah Data SEP",
                text: "Apakah data sudah benar?",
                showDenyButton: true,
                confirmButtonText: "Sudah. Update",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    var tanggal_rujukan = new Date($("#txt_bpjs_tanggal_rujukan").datepicker("getDate"));
                    var parse_tanggal_rujukan =  tanggal_rujukan.getFullYear() + "-" + str_pad(2, tanggal_rujukan.getMonth()+1) + "-" + str_pad(2, tanggal_rujukan.getDate());


                    var tanggal_laka = new Date($("#txt_bpjs_laka_tanggal").datepicker("getDate"));
                    var parse_tanggal_laka =  tanggal_laka.getFullYear() + "-" + str_pad(2, tanggal_laka.getMonth()+1) + "-" + str_pad(2, tanggal_laka.getDate());

                    var dataSetSEP = {
                        request: "sep_update",
                        uid: selectedSEPUID,
                        sep: selectedSEP,
                        no_kartu: $("#txt_bpjs_nomor").val(),
                        ppk_pelayanan: $("#txt_bpjs_faskes").val(),
                        kelas_rawat: $("#txt_bpjs_kelas_rawat").val(),
                        no_mr: $("#txt_bpjs_rm").val().replace(new RegExp(/-/g),""),
                        asal_rujukan: $("#txt_bpjs_jenis_asal_rujukan").val(),
                        ppk_rujukan: $("#txt_bpjs_asal_rujukan").val(),
                        tgl_rujukan: parse_tanggal_rujukan,
                        no_rujukan: $("#txt_bpjs_nomor_rujukan").val(),
                        catatan: $("#txt_bpjs_catatan").val(),
                        diagnosa_awal: $("#txt_bpjs_diagnosa_awal").val(),
                        poli: $("#txt_bpjs_poli_tujuan").val(),
                        eksekutif: $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val(),
                        cob: $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val(),
                        katarak: $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val(),

                        laka_lantas: $("input[type=\"radio\"][name=\"txt_bpjs_laka\"]:checked").val(),
                        laka_lantas_penjamin: selectedLakaPenjamin.join(","),
                        laka_lantas_tanggal_kejadian: parse_tanggal_laka,
                        laka_lantas_keterangan: $("#txt_bpjs_laka_keterangan").val(),
                        laka_lantas_suplesi: $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                        laka_lantas_suplesi_nomor: $("#txt_bpjs_laka_suplesi_nomor").val(),
                        laka_lantas_suplesi_provinsi: $("#txt_bpjs_laka_suplesi_provinsi").val(),
                        laka_lantas_suplesi_kabupaten: $("#txt_bpjs_laka_suplesi_kabupaten").val(),
                        laka_lantas_suplesi_kecamatan: $("#txt_bpjs_laka_suplesi_kecamatan").val(),

                        skdp: $("#txt_bpjs_skdp").val(),
                        dpjp: $("#txt_bpjs_dpjp").val(),
                        telepon: $("#txt_bpjs_telepon").val()
                    };

                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/BPJS",
                        type: "POST",
                        data: dataSetSEP,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response.response_package.content.metaData.code === "201") {
                                Swal.fire(
                                    "Gagal Update SEP",
                                    response.response_package.content.metaData.message,
                                    "warning"
                                ).then((result) => {
                                });
                            } else {
                                Swal.fire(
                                    "BPJS",
                                    "SEP berhasil diubah!",
                                    "success"
                                ).then((result) => {
                                    $("#modal-sep-edit").modal("hide");
                                });
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {
                }
            });
            return false;
        });










        $("body").on("click", ".btn-delete-sep", function() {
            var targetSEP = $(this).attr("id").split("_");
            targetSEP = targetSEP[targetSEP.length - 1];

            var uid = $(this).attr("uid");

            Swal.fire({
                title: "Hapus SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya. Hapus",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/BPJS/" + targetSEP + "/" + uid,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"DELETE",
                        success:function(response) {
                            if(parseInt(response.response_package.content.metaData.code) === 200) {
                                Swal.fire(
                                    "BPJS",
                                    "SEP Berhasil dihapus",
                                    "success"
                                ).then((result) => {
                                    SEPList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Gagal Hapus SEP",
                                    response.response_package.content.metaData.message,
                                    "error"
                                ).then((result) => {
                                    //location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                });
                            }
                        },
                        error: function(response) {
                            notification ("danger", "SEP gagal dihapus", 3000, "hasil_sep");
                        }
                    });
                } else if (result.isDenied) {
                    //
                }
            });
        });
    });
</script>



<div id="modal-sep-edit" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-5 mb-5 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">Kontak</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_telepon" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-6">
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Nomor Medical Rahecord (MR)</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                        </div>

                                        <div class="col-12 col-md-7 form-group">
                                            <label for="">Tanggal SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly value="<?php echo date('d F Y'); ?>">
                                        </div>
                                        <div class="col-12 col-md-9 form-group">
                                            <label for="">Faskes</label>
                                            <select class="form-control sep" id="txt_bpjs_faskes">
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD KAB. BINTAN - KAB. BINTAN (KEPRI)</option>
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD PETALA BUMI - KOTA PEKAN BARU</option>
                                            </select>
                                        </div>


                                        <div class="col-12 col-md-8 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="2">Rawat Jalan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                            <label for="">Kelas Rawat</label>
                                            <select class="form-control" id="txt_bpjs_kelas_rawat"></select>
                                        </div>
                                    </div>

                                    <div class="col-6" id="panel-rujukan">
                                        <h6 id="rujukan_loading" class="text-center">Memuat Data SEP...</h6>
                                        <div id="panel_rujukan_result">
                                            <div class="col-12 col-md-6 mb-4 form-group" id="group_nomor_rujukan">
                                                <label for="">Nomor Rujukan</label>
                                                <select data-width="100%" class="form-control uppercase" id="txt_bpjs_nomor_rujukan"></select>
                                                <!--<input type="text" class="form-control uppercase" id="txt_bpjs_nomor_rujukan" />-->
                                            </div>
                                            <div class="col-12 col-md-4 mb-4 form-group">
                                                <label for="">Jenis Asal Rujukan</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_jenis_asal_rujukan">
                                                    <option value="1">Puskesmas</option>
                                                    <option value="2">Rumah Sakit</option>
                                                </select>
                                            </div>
                                            <div class="col-12 form-group">
                                                <label for="">Asal Rujukan</label>
                                                <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                            </div>
                                            <div class="col-12 col-md-5 mb-4 form-group">
                                                <label for="">Tanggal Rujukan</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan" readonly>
                                            </div>

                                            <div class="informasi_rujukan">
                                                <table class="table form-mode">
                                                    <tr>
                                                        <td>Perujuk</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_perujuk"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tanggal Kunjungan</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_tanggal"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Poli</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_poli"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Diagnosa</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_diagnosa"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Keluhan</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_keluhan"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hak Kelas</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_hak_kelas"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jenis Peserta</td>
                                                        <td>:</td>
                                                        <td id="txt_bpjs_rujuk_jenis_peserta"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Perobatan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Tujuan</label>
                                                <select class="form-control" id="txt_bpjs_poli_tujuan"></select>
                                            </div>
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Eksekutif</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                <label for="">Diagnosa Awal</label>
                                                <select class="form-control sep" id="txt_bpjs_diagnosa_awal"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Catatan</label>
                                                <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_catatan" style="min-height: 200px"></textarea>
                                            </div>
                                            <div class="col-12 col-md-6 mb-4 form-group">
                                                <label for="">Nomor SKDP</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_skdp" />
                                            </div>
                                            <div class="col-12 col-md-8 mb-8 form-group" id="group_spesialistik">
                                                <label for="">Spesialistik DPJP</label>
                                                <select class="form-control" id="txt_bpjs_dpjp_spesialistik"></select>
                                            </div>
                                            <div class="col-12 col-md-9 mb-9 form-group" id="group_dpjp">
                                                <label for="">Kode DPJP</label>
                                                <select class="form-control sep" id="txt_bpjs_dpjp"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">COB</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Katarak</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="alert alert-info">
                                                <div class="col-12 col-md-8 mb-4 form-group">
                                                    <b for="">Poli Tujuan</b>
                                                    <blockquote style="padding-left: 25px;">
                                                        <h6 id="txt_bpjs_internal_poli"></h6>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Kerja</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdk"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_dk"></p>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Banding</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdb"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_db"></p>
                                                    </blockquote>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Jaminan Laka Lantas</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_laka" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_laka" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="laka_lantas_container">
                                                <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                    <label for="">Penjamin Laka Lantas</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="1" />
                                                                <label class="form-check-label">
                                                                    Jasa Raharja
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="2" />
                                                                <label class="form-check-label">
                                                                    BPJS Ketenagakerjaan
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="3" />
                                                                <label class="form-check-label">
                                                                    TASPEN PT
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="4" />
                                                                <label class="form-check-label">
                                                                    ASABRI PT
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 mb-4 form-group">
                                                    <label for="">Tanggal Kejadian</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_tanggal">
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <label for="">Keterangan</label>
                                                    <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_laka_keterangan" style="min-height: 200px"></textarea>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <label for="">Suplesi</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="0" checked/>
                                                                <label class="form-check-label">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="1" />
                                                                <label class="form-check-label">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="laka_lantas_suplesi_container">
                                                    <div class="col-12 col-md-6 mb-4 form-group">
                                                        <label for="">Nomor SEP Suplesi</label>
                                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_suplesi_nomor" />
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_provinsi">
                                                        <label for="">Provinsi Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_provinsi"></select>
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_kabupaten">
                                                        <label for="">Kabupaten Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kabupaten"></select>
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_kecamatan">
                                                        <label for="">Kecamatan Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kecamatan"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="modal-sep-cetak" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Surat Eligibilitas Peserta</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <table class="table form-mode">
                            <tr>
                                <td>No. SEP</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Nama Peserta</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sub/Spesialis</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Faskes Penunjuk</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Diagnosa Awal</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Catatan</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table form-mode">
                            <tr>
                                <td>Peserta</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>COB</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Jenis Rawat</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Kelas Rawat</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Penjamin</td>
                                <td class="wrap_content">:</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <small>
                            <b>
                                *Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br />
                                *SEP bukan sebagai bukti penjaminan peserta
                            </b>
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-print"></i> Cetak
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>