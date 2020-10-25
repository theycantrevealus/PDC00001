<script type="text/javascript">
	$(function(){
		var dataObj = {};
		dataObj.penjamin = {};

		loadJenis();
		var penjamin = loadPenjamin();


		$(".harga").inputmask({alias: 'currency', rightAlign: true, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});

        $('.harga').each(function(){
                var key = $(this).attr("id").split("_");
                key = key[key.length - 1];
                
                var value = $(this).val();
                if (value == "" || value == 0){
                    dataObj['penjamin'][key] = 0;
                }
            });

		$(".harga").on('keyup', function(){
			var value = $(this).inputmask("unmaskedvalue");
            var key = $(this).attr("id").split("_");
            key = key[key.length - 1];

			if (value != "" || value == 0){
				dataObj['penjamin'][key] = parseInt(value);
			}
		});

		$("#btnSubmit").click(function(){
			var nama = $("#nama").val();
			var jenis = $("#jenis").val();

			if (nama != "" && jenis != ""){
				dataObj.nama = nama;
				dataObj.jenis = jenis;

				$.ajax({
                    url: __HOSTAPI__ + "/Radiologi",
                    data: {
                        request : "tambah-tindakan",
                        dataObj : dataObj
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "POST",
                    success: function(response){
                        //console.log(response);
                        location.href = __HOSTNAME__ + "/master/radiologi/layanan";
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
			}

            return false;
        });
	});

	function loadJenis(){
		$.ajax({
            async: false,
			url: __HOSTAPI__ + "/Radiologi/jenis",
			type: "GET",
			beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#jenis").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
		})
	}

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
                                "<td><input type='text' class='form-control harga tindakan' placeholder='' id='harga_penjamin_" + MetaData[i].uid  + "' value='0'></td>" + 
                            "</tr>";
                }

                $("#table-penjamin tbody").html(html);
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataPenjamin;
    }

</script>