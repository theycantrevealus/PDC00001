<script src="<?php echo __HOSTNAME__; ?>/plugins/paginationjs/pagination.min.js"></script>
<link href="<?php echo __HOSTNAME__; ?>/plugins/paginationjs/pagination.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">

    $(function(){
        var status_antrian = '<?= $_GET['antrian']; ?>';

        var currentAntrianID = localStorage.getItem("currentAntrianID");

        var allData = {};

        loadTermSelectBox('panggilan', 3);
        loadTermSelectBox('suku', 6);
        loadTermSelectBox('pendidikan', 8);
        loadTermSelectBox('pekerjaan', 9);
        //loadTermSelectBox('status_suami_istri', 10);
        //loadTermSelectBox('alamat_kecamatan', 12);
        loadTermSelectBox('goldar', 4);
        loadTermSelectBox('agama', 5);
        loadTermSelectBox('warganegara', 7);
        loadTermSelectBox('status_suami_istri', 10);
        loadRadio('parent_jenkel','col-md-6', 'jenkel', 2);

        var uid_pasien = __PAGES__[2];
        //var dataPasien = loadPasien(uid_pasien);
        var dataPasien = {
            nik: ""
        };

        loadWilayah('alamat_provinsi', 'provinsi', 0, 'Provinsi');

        $("#alamat_provinsi").on('change', function(){
            var id = $(this).val();

            loadWilayah('alamat_kabupaten', 'kabupaten', id, 'Kabupaten / Kota');
            resetSelectBox('alamat_kecamatan', "Kecamatan");
            resetSelectBox('alamat_kelurahan', "Kelurahan");
        });

        $("#alamat_kabupaten").on('change', function(){
            var id = $(this).val();

            loadWilayah('alamat_kecamatan', 'kecamatan', id, 'Kecamatan');
            resetSelectBox('alamat_kelurahan', "Kelurahan");
        });

        $("#alamat_kecamatan").on('change', function(){
            var id = $(this).val();

            loadWilayah('alamat_kelurahan', 'kelurahan', id, "Kelurahan");
        });

        $("#btnSubmit").click(function() {
            var no_rm = $("#no_rm").inputmask('unmaskedvalue');
            allData.no_rm = no_rm;

            var jenkel = $("input[name='jenkel']:checked").inputmask('unmaskedvalue').replaceAll('-', '');
            allData.jenkel = jenkel;

            if(parseInt($("#warganegara option:selected").val()) === __WNI__) {
                $("#nik").attr({
                    "required": "required"
                }).addClass("required");

                $("#no_passport").removeAttr("required").removeClass("required");
            } else {
                $("#no_passport").attr({
                    "required": "required"
                }).addClass("required");

                $("#nik").removeAttr("required").removeClass("required");
            }

            var requiredItem = [];

            $(".inputan").each(function(){
                var value = $(this).val();

                if($(this).hasClass("required")) {
                    if (value == "" || value == null || value == undefined ) {
                        $("label[for=\"" + $(this).attr("id") + "\"]").addClass("text-danger");
                        requiredItem.push($(this).attr("id"));
                    } else {
                        $("label[for=\"" + $(this).attr("id") + "\"]").removeClass("text-danger");
                    }
                }

                if (value !== "" && value !== null){
                    $this = $(this);
                    if ($this.is('input') || $this.is('textarea')){
                        value = value.toUpperCase();
                    }

                    if ($this.is('select')){
                        value = parseInt(value);
                    }

                    var name = $(this).attr("name");
                    if (name === "email"){
                        value = value.toLowerCase();
                    }

                    allData[name] = value;
                }
            });

            if(requiredItem.length === 0) {
                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + "/Pasien",
                    data: {
                        request : "tambah-pasien",
                        dataObj : allData,
                        uid: uid_pasien
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "POST",
                    success: function(response) {
                        console.clear();
                        console.log(response);
                        if (status_antrian === "true") { 		//redirect to tambah kunjungan
							if (
							    response.response_package.response_unique !== "" &&
                                response.response_package.response_unique !== undefined &&
                                response.response_package.response_unique !== null
                            ){	//check returning uid
								//Set Current Pasien dan Antrian Data
								localStorage.setItem("currentPasien", response.response_package.response_unique);
								//Notif loket lain yang sedang aktif pemanggilan yang sama?? Back Log??
                                if(response.response_package.response_unique !== undefined) {
                                    location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis/tambah/' + response.response_package.response_unique;
                                }
							}
						} else {
						    if(response.response_package.response_result > 0) {
                                location.href = __HOSTNAME__ + "/pasien";
                            } else {
						        console.log(response);
                            }
						}
                    },
                    error: function(response) {
                        console.log("Error : ");
                        console.log(response);
                    }
                });
            } else {
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#" + requiredItem[0]).offset().top - 300
                }, 500);
            }

            return false;
        });

        /*$(".no_rm").on('keyup', function(){
            if (this.getAttribute && this.value.length == this.getAttribute("maxlength")) {
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];
                id = parseInt(id) + 1;

                var next = $("#rm_sub_" + id);
                next.focus();
            }
        });*/

        $("#no_rm").on('keyup', function(){
            let value = $(this).inputmask('unmaskedvalue');

            if (value.length == 6){
                if (cekNoRM(value, dataPasien.no_rm) == false){
                    $("#no_rm").addClass("is-valid").removeClass("is-invalid");
                    $("#error-no-rm").html("");
                    $("#btnSubmit").removeAttr("disabled");
                } else {
                    $("#no_rm").addClass("is-invalid");
                    $("#error-no-rm").html("No. RM tidak tersedia");
                    $("#btnSubmit").attr("disabled", true);
                }
            } else {
                $("#no_rm").addClass("is-invalid");
                $("#error-no-rm").html("No. RM harus 6 angka");
                $("#btnSubmit").attr("disabled", true);
            }
        });

        $("#nik").on('keyup', function(){
            let value = $(this).val();

            if (value.length == 16){
                if (cekNIK(value, dataPasien.nik) == false){
                    $("#nik").addClass("is-valid").removeClass("is-invalid");
                    $("#error-nik").html("");
                    $("#btnSubmit").removeAttr("disabled");
                } else {
                    $("#nik").addClass("is-invalid");
                    $("#error-nik").html("NIK tidak tersedia");
                    $("#btnSubmit").attr("disabled", true);
                }
            } else {
                $("#nik").addClass("is-invalid");
                $("#error-nik").html("NIK harus 16 angka");
                $("#btnSubmit").attr("disabled", true);
            }
        });

        $(".select2").select2({});
        $("#warganegara").val(__WNI__).trigger("change");
        $(".loader-wna").hide();
        $("#warganegara").change(function() {
            var currentChange = $("#warganegara option:selected").val();
            if(parseInt(currentChange) === __WNI__) {
                $(".loader-wni").show();
                $(".loader-wna").hide();
            } else {
                $(".loader-wni").hide();
                $(".loader-wna").show();
            }
        });

        $('#no_rm').inputmask('99-99-99');

        $('.numberonly').keypress(function(event){
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        });
    });

    function cekNoRM(no_rm, no_rm_lama) {
        var result = false;

        $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Pasien/cek-no-rm/" + no_rm,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                if (response.response_package != ""){
                    if (response.response_package.response_result > 0){
                        if (response.response_package.response_data[0].no_rm != no_rm_lama){
                            result = true;
                        }
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return result;
    }

    function cekNIK(nik, nik_lama){
        var result = false;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Pasien/cek-nik/" + nik,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                if (response.response_package != ""){
                    if (response.response_package.response_result > 0){
                        if (response.response_package.response_data[0].nik != nik_lama){
                            result = true;
                        }
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return result;
    }

    function loadTermSelectBox(selector, id_term){
        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
                var MetaData = response.response_package.response_data;

                if (MetaData !== "") {
                    if(selector === "warganegara") {
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");
                            if (MetaData[i].id === __WNI__) {
                                $(selection).attr({
                                    "selected": "selected"
                                });
                            }
                            $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                            $("#" + selector).append(selection);
                        }
                        //$("#" + selector).val(__WNI__).trigger("change");
                    } else {
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                            $("#" + selector).append(selection);
                        }
                    }
                }

            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadTermItemsRecursiveSelectbox(selector, id){
        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items-recursive/" + id,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                    for(i = 0; i < MetaData.length; i++){
                        var selection = document.createElement("OPTION");

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

    function loadRadio(selector, colclass, name, id){
        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                    var html = "";
                    for(i = 0; i < MetaData.length; i++){
                        html += "<div class='"+ colclass +"'>" +
                            "<div class='custom-control custom-radio'>" +
                            "<input type='radio' value='"+ MetaData[i].id +"' id='"+ name +"_"+ MetaData[i].id +"' name='"+ name +"' class='custom-control-input' required>" +
                            "<label class='custom-control-label' for='"+ name +"_"+ MetaData[i].id +"'>"+ MetaData[i].nama +"</label>" +
                            "</div>" +
                            "</div>";
                    }

                    $("#" + selector).html(html);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }


    /*function loadPasien(uid) {
        var dataPasien = null;

        if (uid != ""){
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Pasien/pasien-detail/" + uid,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    dataPasien = response.response_package.response_data[0];

                    $.each(dataPasien, function(key, item){
                        if(key === "no_rm") {
                            item = item.replace(/-/g, "");
                            $(".Card-number li#last-li").html(item.substr(0, 2));
                            $(".Card-number li:eq(1)").html(item.substr(2, 2));
                            $(".Card-number li#first-li").html(item.substr(4, 2));
                        }

                        if(key === "nama") {
                            $("#kartu_nama").html(item);
                        }

                        if(key === "periode") {
                            $("#kartu_daftar").html(item);
                        }

                        $("#" + key).val(item);
                    });

                    loadSelected("alamat_provinsi", 'provinsi', '', dataPasien.alamat_provinsi);
                    loadSelected("alamat_kabupaten", 'kabupaten', dataPasien.alamat_provinsi, dataPasien.alamat_kabupaten);
                    loadSelected("alamat_kecamatan", 'kecamatan', dataPasien.alamat_kabupaten, dataPasien.alamat_kecamatan);
                    loadSelected("alamat_kelurahan", 'kelurahan', dataPasien.alamat_kecamatan, dataPasien.alamat_kelurahan);

                    checkedRadio('jenkel', dataPasien['jenkel']);
                    for(var b = 0; b < dataPasien.history_penjamin.length; b++)
                    {
                        var newRowPenjamin = document.createElement("TR");

                        var newIDPenjamin = document.createElement("TD");
                        var newNamaPenjamin = document.createElement("TD");
                        var newStartPenjamin = document.createElement("TD");
                        var newEndPenjamin = document.createElement("TD");
                        var newUsedPenjamin = document.createElement("TD");
                        var newAksiPenjamin = document.createElement("TD");

                        $(newIDPenjamin).html((b + 1));
                        $(newNamaPenjamin).html(dataPasien.history_penjamin[b].penjamin_detail.nama);
                        $(newUsedPenjamin).html(dataPasien.history_penjamin[b].terdaftar);
                        $(newStartPenjamin).html(dataPasien.history_penjamin[b].valid_awal);
                        $(newEndPenjamin).html(dataPasien.history_penjamin[b].valid_akhir);

                        var metaData = JSON.parse(dataPasien.history_penjamin[b].rest_meta);
                        for(var key in metaData.response.peserta) {
                            $(newAksiPenjamin).append("<h6>" + key + " : " + metaData.response.peserta[key] + "</h6>");
                        }

                        //$(newAksiPenjamin).html("<code><pre>" + dataPasien.history_penjamin[b].rest_meta + "</pre></code>");

                        $(newRowPenjamin).append(newIDPenjamin);
                        $(newRowPenjamin).append(newNamaPenjamin);
                        $(newRowPenjamin).append(newUsedPenjamin);
                        $(newRowPenjamin).append(newStartPenjamin);
                        $(newRowPenjamin).append(newEndPenjamin);
                        $(newRowPenjamin).append(newAksiPenjamin);


                        $("#penjamin_pasien").append(newRowPenjamin);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        return dataPasien;
    }*/

    /*$("#cppt_pagination").pagination({
        dataSource: __HOSTAPI__ + "/CPPT/semua/all/" + __PAGES__[2],
        locator: 'response_package.response_data',
        totalNumberLocator: function(response) {
            return response.response_package.response_total;
        },
        pageSize: 1,
        ajax: {
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                $("#cppt_loader").html("Memuat data CPPT...");
            }
        },
        callback: function(data, pagination) {
            var dataHtml = "<ul style=\"list-style-type: none;\">";

            $.each(data, function (index, item) {
                if(item.uid !== __PAGES__[3]) {
                    dataHtml += "<li>" + load_cppt(item) + "</li>";
                }
            });

            dataHtml += "</ul>";

            $("#cppt_loader").html(dataHtml);
        }
    });*/

    function load_cppt(data) {
        var returnHTML = "";
        $.ajax({
            url: __HOSTNAME__ + "/pages/rawat_jalan/dokter/cppt-single.php",
            async:false,
            data:{
                setter:data
            },
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"POST",
            success:function(response_html) {
                returnHTML = response_html;
            },
            error: function(response_html) {
                console.log(response_html);
            }
        });
        return returnHTML;
    }

    function loadWilayah(selector, parent, id, name){

        resetSelectBox(selector, name);

        $.ajax({
            url:__HOSTAPI__ + "/Wilayah/"+ parent +"/" + id,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                    for(i = 0; i < MetaData.length; i++){
                        var selection = document.createElement("OPTION");

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

    function loadSelected(selector, parent, id, params){
        $.ajax({
            url:__HOSTAPI__ + "/Wilayah/"+ parent +"/" + id,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData !== undefined){
                    for(i = 0; i < MetaData.length; i++){
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                        if (MetaData[i].id == params) {
                            $(selection).attr("selected",true);
                            $("#" + selector).val(MetaData[i].id);
                            //$("#" + selector).trigger('change');
                        };

                        $("#" + selector).append(selection);
                    }
                }

            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    /*function loadPenjamin() {
        //$("#penjamin_pasien").DataTable();
    }*/

    function resetSelectBox(selector, name){
        $("#"+ selector +" option").remove();
        var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
        $("#" + selector).append(opti_null);
    }

    function autoSelect(selector, id, params){
        if (id == params){
            $(selector).val(id);
            $(selector).trigger('change');
        }
    }

    function checkedRadio(name, value){
        var $radios = $('input:radio[name='+ name +']');
        if($radios.is(':checked') === false) {
            $radios.filter('[value='+ value +']').prop('checked', true);
        }
    }

</script>