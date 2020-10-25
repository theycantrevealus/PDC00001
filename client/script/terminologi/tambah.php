<script type="text/javascript">
	$(function(){
		var usage_items = {},
			dataObj = {};

		var no_urut = 1;

		var html_on_load = "<tr class='usage_"+ no_urut +"'>" +
				                "<td class='no_urut_term'>"+ no_urut +"</td>" + 
				                "<td><input type='text' autocomplete='off' class='form-control term_usage' id='usage_"+  no_urut +"' /></td>" +
				                "<td><button class='btn btn-sm btn-danger btn-delete-usage' id='usage_delete_"+ no_urut +"'>" + 
				                    "<i class='fa fa-trash'></i></button></td>" + 
				            "</tr>";

		$("#table-term-usage tbody").html(html_on_load);
		no_urut++;

		/*$("#table-term-usage").dataTable({
			"paging": true
		});*/

		$("#table-term-usage tbody").on('focus', '.term_usage', function(){
            var table = $('#table-term-usage')[0];
            var table_row = table.tBodies[0].rows.length;

            var row_index = $(this).parent().parent().index();

            var html = "";
            if (table_row == (parseInt(row_index) + 1)) {

                html += "<tr class='usage_"+ no_urut +"'>" + 
                            "<td class='no_urut_term'></td>" +
                            "<td><input type='text' autocomplete='off' class='form-control term_usage' id='usage_"+  no_urut +"' /></td>" +
                            "<td><button class='btn btn-danger btn-sm btn-delete-usage' id='usage_delete_"+ no_urut +"'>" + 
                                    "<i class='fa fa-trash'></i></button></td>" +
                        "</tr>";

                
                $("#table-term-usage tbody").append(html);
                no_urut++;
            }

            setNomorUrut('table-term-usage', 'no_urut_term');
        });

		$("#table-term-usage tbody").on('keyup', '.term_usage', function(){
            var usage = $(this).val();
            var key = $(this).attr("id");

            usage_items[key] = usage;
        });

		$("#table-term-usage tbody").on('click', '.btn-delete-usage', function(){
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            var key = "usage_" + id;

            delete usage_items[key];

            $(this).parent().parent().remove();
            setNomorUrut('table-term-usage', 'no_urut_term');
        });


        $("#btnSubmit").click(function(){
        	var term_name = $("#txt_nama_term").val();

        	if (term_name != ""){
        		dataObj.term_name = term_name;
	            dataObj.term_usage = usage_items;

	           $.ajax({
	                url: __HOSTAPI__ + "/Terminologi",
	                type: "POST",
	                data: {
	                    request : 'tambah-terminologi',
	                    dataObj : dataObj
	                },
	                beforeSend: function(request) {
	                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	                },
	                success: function(response){
	                    location.href = __HOSTNAME__ + "/terminologi";
	                },
	                error: function(response) {
	                    console.log(response);
	                }
	            });
				
        	}

            return false;
        });

	});

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