<script type="text/javascript">
	$(function(){
		var dataObject= {}, 
            hargaPenjamin = {}, 
            tindakanPoli = [];

        var state_tindakan_uid = '';
        var tindakan, penjamin;


		/*load data tindakan*/
        tindakan = loadTindakan();
        penjamin = loadPenjamin();

        /*assign to using select2*/
        $(".tindakan").select2({});
        $(".harga").inputmask({alias: 'currency', rightAlign: true, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});

		/*========= Load Poli ========*/
		var uid = <?php echo json_encode(__PAGES__[4]); ?>;
	
		$.ajax({
			async: false,
			url:__HOSTAPI__ + "/Poli/poli-detail/" + uid,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				
				var metaData = response.response_package.response_data;
				var temp_nama = metaData[0].nama;
				var nama = temp_nama.replace('Poli ', '');

				var tindakanData = metaData[0].tindakan;

				dataObject.uid = uid;
				dataObject.nama = "Poli " + nama;

				$.each(tindakanData, function(key, item){
					var uid_tindakan = item.uid_tindakan;
					var uid_penjamin = item.uid_penjamin;
					var harga = parseFloat(item.harga);
					var nama_tindakan;

					if (uid_tindakan in hargaPenjamin){
	                    hargaPenjamin[uid_tindakan][uid_penjamin] = harga; 
	                } else {
	                    hargaPenjamin[uid_tindakan] = {[uid_penjamin]: harga}; 
	                }

	                /*===== GET NAME OF POLI =====*/

	                if (!(tindakanPoli.includes(uid_tindakan))){
	                	$.each(tindakan, function(key, item){
		                	if (item.uid == uid_tindakan){
		                		nama_tindakan = item.nama;
		                	}
		                });

	                	tindakanPoli.push(uid_tindakan);
	                	getTindakanOnLoad(uid_tindakan, nama_tindakan);
                		setNomorUrut("table-tindakan","no_urut");
	                }

					$("#tindakan option[value='"+ uid_tindakan +"']").remove();
        			$("#tindakan option[value='']").attr("selected");
				});
				
				dataObject['tindakan'] = hargaPenjamin;

				$("#txt_nama").val(nama);
			},
			error: function(response) {
				console.log(response);
			}
		});
		/*===========================*/

        $("#tindakan").on('change', function(){
            var params = [];
            params['uid'] = $(this).children("option:selected").val();
            params['nama'] = $(this).children("option:selected").html();

            if (params['uid'] != ""){
                tindakanPoli.push(params['uid']);
                getTindakan(params);

                setNomorUrut("table-tindakan","no_urut");
            }
        });

        $("#table-tindakan tbody").on('click', '.btnHapusTindakan', function(){
            var get_uid = $(this).attr("id");
            var arr_uid = get_uid.split("_");
            var uid = arr_uid[arr_uid.length - 1];

            $(this).closest("tr").remove();
            setNomorUrut("table-tindakan","no_urut");
            
            //=== deleteing from tindakanPoli array and hargaPenjamin object
            tindakanPoli = removeFromArrayByValue(tindakanPoli, uid);
            delete hargaPenjamin[uid];
            //===

            //=== Set Back option List
            delete dataObject.tindakan[uid];
            setBackTindakan(tindakan, uid);
        });


        $("#table-tindakan tbody").on('click','.linkTindakan', function() {
            var get_uid = $(this).parent().parent().attr("id");
            var arr_uid = get_uid.split("_");
            var uid = arr_uid[arr_uid.length - 1];

            var name = $(this).html();
            
            state_tindakan_uid = uid;

            $("#title-tindakan-penjamin").html("untuk <span style='color:#4a90e2;'><b>"+ name +"</b></span>");
            $("#table-penjamin").removeClass("table-not-active");
            $('#table-penjamin').find('input').each(function(){
                $(this).val("");
            });

            if (uid in hargaPenjamin){
                var metaData = hargaPenjamin[uid];
                
                $.each(metaData,function(key,item){
                    $("#harga_penjamin_" + key).val(item);
                });
            }
        });

        /*========== ON CLICK IF NOT IN TABLE ==========*/
        $(document).click(function(e) {
            if ( $(e.target).closest('table').length === 0 ) {
                $("#title-tindakan-penjamin").html("");
                $("#table-penjamin").addClass("table-not-active");
                $('#table-penjamin').find('input').each(function(){
                    $(this).val("");
                });
            }
        });
        /*===============================================*/


        /*=========== EMPTY CHECK AND ASSIGN NAME FOR POLI ============*/
        $("#txt_nama").on('keyup', function(){
            var nama = $("#txt_nama").val();

            if (nama != ""){
                dataObject.nama = "Poli " + nama;
                $(".btnNextInfo").removeAttr("disabled");
            } else {
                $(".btnNextInfo").attr("disabled",true);
            }
        });
        /*=============================================================*/


        /*=========== EMPTY CHECK AND ASSIGN PRICE OF PENJAMIN ============*/
        $(".hargaPenjamin").on('keyup', function(){
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var temp = $(this).val();

            if (temp != ""){
                var harga = temp.replace(',', '');
                harga = parseFloat(harga);

                if (state_tindakan_uid in hargaPenjamin){
                    hargaPenjamin[state_tindakan_uid][uid] = harga; 
                } else {
                    hargaPenjamin[state_tindakan_uid] = {[uid]: harga}; 
                }
            }
            
        });
        /*=============================================================*/

        /*========= BTN NEXT TINDAKAN ==========*/
        $(".btnNextTindakan").on('click', function(){
            //==== Set table head
            var html = "<tr>" +
                        "<th style='width: 10px;'>No</th>" +
                        "<th>Tindakan</th>";


            $.each(penjamin, function(key, item){
                html += "<th class='col_"+ item.uid +"'>"+ item.nama +"</th>";
            });

            html += "</tr>";

            $("#table-konfirmasi thead").html(html);
            //====

            //==== Set table content
            var no = 1;
            var html_content = ""; 
            
            //console.log(tindakan);
            $.each(tindakan, function(key, item){

                if (item.uid in hargaPenjamin){
                    html_content += "<tr>" + 
                                    "<td>"+ no +"</td>" +
                                    "<td>"+ item.nama +"</td>";

                    var parent_uid = item.uid;

                    $.each(penjamin, function(key, item){
                        if (item.uid in hargaPenjamin[parent_uid]){
                            html_content += "<td><span class='separated_comma'>Rp. "+ hargaPenjamin[parent_uid][item.uid] +"</span></td>";
                        } else {
                            html_content += "<td> - </td>";
                        }
                    });

                    no++;
                }
                html_content += "</tr>";
            });

            $("#table-konfirmasi tbody").html(html_content);
            $(".separated_comma").digits();
            //====

            
            $("#title-konfirmasi-poli").html(dataObject.nama);
            
        });
        /*======================================*/


        /*================== BTN NEXT PREV TAB WIZARD =================*/
        $(".btnNext").on('click', function(){
            var get_id = $(this).parent().parent().parent().attr('id');
            var id = get_id[get_id.length - 1];
            id = parseInt(id);
            nextTab(id);
        });

        $(".btnPrev").on('click', function(){
            var get_id = $(this).parent().parent().parent().attr('id');
            var id = get_id[get_id.length - 1];
            id = parseInt(id);
            prevTab(id);
        });
        /*=============================================================*/


        $("#btnSubmit").on('click', function(){
            dataObject.tindakan = hargaPenjamin;

            if (dataObject.nama != "") {
                $.ajax({
                    url: __HOSTAPI__ + "/Poli",
                    data: {
                        request : "edit_poli",
                        dataObject : dataObject
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "POST",
                    success: function(response){
                        location.href = __HOSTNAME__ + "/master/poli/poli";
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        });
    });

    function nextTab(id){
        $(".tabsContent").removeClass("active show");
        $(".navTabs").removeClass("active");

        var nextId = id + 1;
        $("#nav-tab-" + nextId).addClass("active");
        $("#tab-" + nextId).addClass("active show");
    }

    function prevTab(id){
        $(".tabsContent").removeClass("active show");
        $(".navTabs").removeClass("active");

        var prevId = id - 1;
        $("#nav-tab-" + prevId).addClass("active");
        $("#tab-" + prevId).addClass("active show");
    }


    /*========== FUNC FOR LOAD TINDAKAN ==========*/
    function loadTindakan(){
        var dataTindakan;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Tindakan/tindakan",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataTindakan = response.response_package.response_data;

                for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                    $("#tindakan").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        if (dataTindakan.length > 0) {
            return dataTindakan;
        } else {
            return null;
        }
    }
    /*--------------------------------------*/

    /*========== FUNC FOR LOAD PENJAMIN ==========*/
    function loadPenjamin(){
        var dataPenjamin;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Penjamin/penjamin",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPenjamin = response.response_package.response_data;

                var html = "";
                for(i = 0; i < MetaData.length; i++){
                    var no = i + 1;
                    html += "<tr>" +
                                "<td>"+ no +"</td>" +
                                "<td>"+ MetaData[i].nama +"</td>" +
                                "<td><input type='text' class='form-control numberonly harga hargaPenjamin' placeholder='' id='harga_penjamin_" + MetaData[i].uid  + "'></td>" + 
                            "</tr>";
                }

                $("#table-penjamin tbody").append(html);
            },
            error: function(response) {
                console.log(response);
            }
        });


        if (dataPenjamin.length > 0) {
            return dataPenjamin;
        } else {
            return null;
        }
    }
    /*--------------------------------------*/


    function getTindakan(params){
        var uid_tindakan = params['uid'];
        var nama_tindakan = params['nama'];

        $("#tindakan option[value='"+ uid_tindakan +"']").remove();
        $("#tindakan option[value='']").attr("selected");
    
        html = "<tr id='tindakan_" + uid_tindakan + "'>" + 
                    "<td class='no_urut'></td>" +
                    "<td><a href='#' class='linkTindakan'>"+ nama_tindakan +"</a></td>" +
                    "<td><button type='button' rel='tooltip' id='btn_tindakan_"+ uid_tindakan +"' class='btn btn-sm btn-danger btnHapusTindakan' data-toggle='tooltip' data-placement='top' title='' data-original-title='Hapus'><i class='fa fa-trash'></i></button></td>" +
                "</tr>";

        $("#table-tindakan tbody").append(html);
        $("#tindakan").val("");
        $("#tindakan").trigger("change");
    }

    /*========== FOR FIRST LOAD WHEN LOAD DATA FOR EDIT ==========*/
    function getTindakanOnLoad(uid_tindakan, nama_tindakan){

        $("#tindakan option[value='"+ uid_tindakan +"']").remove();
        $("#tindakan option[value='']").attr("selected");
    
        html = "<tr id='tindakan_" + uid_tindakan + "'>" + 
                    "<td class='no_urut'></td>" +
                    "<td><a href='#' class='linkTindakan'>"+ nama_tindakan +"</a></td>" +
                    "<td><button type='button' rel='tooltip' id='btn_tindakan_"+ uid_tindakan +"' class='btn btn-sm btn-danger btnHapusTindakan' data-toggle='tooltip' data-placement='top' title='' data-original-title='Hapus'><i class='fa fa-trash'></i></button></td>" +
                "</tr>";

        $("#table-tindakan tbody").append(html);
        $("#tindakan").val("");
        $("#tindakan").trigger("change");
    }
    /*=============================================================*/

    function setBackTindakan(arr_tindakan, uid_tindakan){
        var name_tindakan;

        for (var i = 0; i < arr_tindakan.length; i++) {
            if (arr_tindakan[i].uid == uid_tindakan){
                name_tindakan = arr_tindakan[i].nama;
                break;
            }
        }

        $("#tindakan").append("<option value='"+ uid_tindakan +"'>"+ name_tindakan +"</option>");
    }

    function setNomorUrut(table_name, no_urut_class){
        /*set dynamic serial number*/
        var rowCount = $("#"+ table_name +" tr").length;
        var table = $("#"+ table_name);
        $("."+ no_urut_class).html("");

        for (var i = 0, row; i < rowCount; i++) {
            //console.log()
            table.find('tr:eq('+ i +')').find('td:eq(0)').html(i);
        }
        /*--------*/
    }

    function removeFromArrayByValue(arr, val){
        var fill_array = $.grep(arr, function(value) {
            return value != val;
        });

        return fill_array;
    }

    $('.numberonly').keypress(function(event){
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });


    $.fn.digits = function(){ 
        return this.each(function(){ 
            $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
        })
    }


</script>