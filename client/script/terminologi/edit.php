<script type="text/javascript">
	$(function(){


		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Terminologi/detail/" + __PAGES__[2],
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type: "GET",
			success: function(response){
				$("#txt_nama_terminologi").val(response.response_package.response_data[0].nama);
			}
		});


		$("form").submit(function(){
			var namaTerminologi = $("#txt_nama_terminologi").val();
			if(namaTerminologi != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request:"edit_terminologi",
						nama:namaTerminologi,
						id:__PAGES__[2]
					},
					type:"POST",
					success:function(resp) {
						if(resp.response_package.response_result > 0) {
							location.href = __HOSTNAME__ + "/terminologi";
						}
					},
					error:function(resp) {
						console.log(resp);
					}
				});	
			} else {

			}
			return false;
		});
	});
</script>