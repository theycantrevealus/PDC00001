<script type="text/javascript">
	$(function(){

		var parent = 0;

		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Terminologi/child_detail/" + __PAGES__[2],
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type: "GET",
			success: function(response){
				parent = response.response_package.response_data[0].terminologi;
				alert(parent);
				$("#txt_nama_terminologi_item").val(response.response_package.response_data[0].nama);
			}
		});


		$("form").submit(function(){
			var namaTerminologiItem = $("#txt_nama_terminologi_item").val();
			if(namaTerminologiItem != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request:"edit_terminologi_item",
						nama:namaTerminologiItem,
						terminologi:parent,
						id:__PAGES__[2]
					},
					type:"POST",
					success:function(resp) {
						if(resp.response_package.response_result > 0) {
							location.href = __HOSTNAME__ + "/terminologi/child/" + parent;
						}
					},
					error:function(resp) {
						console.log(resp);
					}
				});	
			} else {

			}
		});
		return false;
	});
</script>