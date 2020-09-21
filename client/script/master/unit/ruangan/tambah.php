<script type="text/javascript">
	$(function(){
        var ruangan_update = {},
            ruangan_delete = [],
            ruangan_add = {},
            dataObj = {};

        var no_urut = 1;
        var uid_lantai = "";

		loadLantai();

        $("#lantai").on('change', function(){
            var uid = $(this).val();

            $("#table-ruangan-baru tbody").html('');
            $("#table-ruangan-lama tbody").html('');

            if (uid != ""){
                uid_lantai = uid;

                no_urut = 1;

                var dataRuangan = loadRuangan(uid);

                if (dataRuangan != null){
                    var html = "";

                    var no = 1;
                    $.each(dataRuangan, function(key, item){
                        html += "<tr>" +
                                    "<td>"+ no +"</td>" + 
                                    "<td><input type='text' class='form-control update_ruangan' id='ruangan_"+ item.uid +
                                    "' value='"+ item.nama +"' /></td>" + 
                                    "<td><button class='btn btn-danger btn-sm btn-delete-update-ruangan' id='ruangan_"+ item.uid +"''>" + 
                                        "<i class='fa fa-trash'></i></button></td>" + 
                                "</tr>";

                        ruangan_update['ruangan_' + item.uid] = item.nama;
                    });

                    $("#table-ruangan-lama tbody").html(html);
                    setNomorUrut('table-ruangan-lama', 'no_urut_tbl_lama');
                }
            
                var html2 = "<tr class='ruangan_"+ no_urut +"'>" +
                                "<td class='no_urut_tbl_baru'>"+ no_urut +"</td>" + 
                                "<td><input type='text' class='form-control add_ruangan' id='ruangan_"+  no_urut +"' /></td>" +
                                "<td><button class='btn btn-danger btn-delete-add-ruangan' id='ruangan_delete_"+ no_urut +"'>" + 
                                    "<i class='fa fa-trash'></i></button></td>" + 
                            "</tr>";
                
                $("#table-ruangan-baru tbody").html(html2);

                no_urut++;
            }
        });

        $("#table-ruangan-baru tbody").on('focus', '.add_ruangan', function(){
            var table = $('#table-ruangan-baru')[0];
            var table_row = table.tBodies[0].rows.length;

            var row_index = $(this).parent().parent().index();

            var html = "";
            if (table_row == (parseInt(row_index) + 1)) {

                html += "<tr class='ruangan_"+ no_urut +"'>" + 
                            "<td class='no_urut_tbl_baru'></td>" +
                            "<td><input type='text' class='form-control add_ruangan' id='ruangan_"+  no_urut +"' /></td>" +
                            "<td><button class='btn btn-danger btn-delete-add-ruangan' id='ruangan_delete_"+ no_urut +"'>" + 
                                    "<i class='fa fa-trash'></i></button></td>" +
                        "</tr>";

                
                $("#table-ruangan-baru tbody").append(html);

                no_urut++;
            }

            setNomorUrut('table-ruangan-baru', 'no_urut');
        });

        /*====== BTN DELETE ======*/
        $("#table-ruangan-baru tbody").on('click', '.btn-delete-add-ruangan', function(){
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            var key = "ruangan_" + id;

            delete ruangan_add[key];

            $(this).parent().parent().remove();
            setNomorUrut('table-ruangan-baru', 'no_urut_tbl_baru');
        });

        $("#table-ruangan-lama tbody").on('click', '.btn-delete-update-ruangan', function(){
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            var key = "ruangan_" + id;

            delete ruangan_update[key];

            ruangan_delete.push(id);
            $(this).parent().parent().remove();
            setNomorUrut('table-ruangan-lama', 'no_urut_tbl_lama');
        });
        /*========================*/

        $("#table-ruangan-baru tbody").on('keyup', '.add_ruangan', function(){
            var ruangan = $(this).val();
            var key = $(this).attr("id");

            ruangan_add[key] = ruangan;
        });

        $("#table-ruangan-lama tbody").on('keyup', '.update_ruangan', function(){
            var ruangan = $(this).val();
            var key = $(this).attr("id");

            ruangan_update[key] = ruangan;
        });

        $("#btnSubmit").click(function(){
            dataObj.uid_lantai = uid_lantai;
            dataObj.update = ruangan_update;
            dataObj.add = ruangan_add;
            dataObj.delete = ruangan_delete;

            $.ajax({
                url: __HOSTAPI__ + "/Ruangan",
                type: "POST",
                data: {
                    request : 'multiple_request',
                    dataObj : dataObj
                },
                beforeSend: function(request) {
                    console.log(dataObj);
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    console.log(response);
                    //location.href = __HOSTNAME__ + "/master/unit/ruangan";
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return false;
        });
	});

	function loadLantai(){
        $.ajax({
            url:__HOSTAPI__ + "/Lantai/lantai",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                    $("#lantai").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

	function loadRuangan(params){
        var dataRuangan;

		$.ajax({
            async: false,
			url: __HOSTAPI__ + "/Ruangan/ruangan-lantai/" + params,
			type: "GET",
			beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                dataRuangan = MetaData;
            },
            error: function(response) {
                console.log(response);
            }
		})

        return (dataRuangan != "") ? dataRuangan : null;
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

</script>