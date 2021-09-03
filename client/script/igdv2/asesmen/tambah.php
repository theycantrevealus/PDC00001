<script type="text/javascript">
	
	$(function(){
		var no_urut = 1;
		loadObat();


		$("#btnSimpanObat").click(function(){
			let obatUID = $("#obat").val();
			let namaObat = $("#obat option:selected").html();
			let jumlahObat = $("#jumlah").val();

			html = "<tr style='text-size: 0.8rem'>" + 
						"<td>"+ namaObat + "</td>" +
						"<td>"+ jumlahObat +"</td>" +
						"<td><button type='button' class='btn btn-sm btn-danger btn-delete-obat'><i class='fa fa-trash'></i></button></td>" +
					"</tr>";

			
			$("#list-obat tbody").append(html);

			return false;
		});


		$("#btnSimpanTindakan").click(function(){
			let tindakanUID = $("#tindakan").val();
			let namaTindakan = $("#tindakan option:selected").html();

			html = "<tr style='text-size: 0.8rem'>" +
						"<td>"+ namaTindakan +"</td>" +
						"<td><button type='button' class='btn btn-sm btn-danger btn-delete-tindakan'><i class='fa fa-trash'></i></button></td>" +
					"</tr>";

			
			$("#list-tindakan tbody").append(html);

			return false;
		});

		$("#list-obat tbody").on('click', '.btn-delete-obat', function(){
			$(this).parent().parent().remove();
		});

		$("#list-tindakan tbody").on('click', '.btn-delete-tindakan', function(){
			$(this).parent().parent().remove();
		});

		$(".select2").select2({});
	});

	function loadObat(){
		$.ajax({
    		async: false,
            url:__HOSTAPI__ + "/Inventori",
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
	                    $("#obat").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
    	})
	}
</script>