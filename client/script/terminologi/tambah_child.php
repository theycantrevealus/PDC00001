<script type="text/javascript">
	$(function(){
		$("form").submit(function(){
			var namaTerminologiItem = $("#txt_nama_terminologi_item").val();
			if(namaTerminologiItem != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request:"tambah_terminologi_item",
						nama:namaTerminologiItem,
						terminologi:__PAGES__[2]
					},
					type:"POST",
					success:function(resp) {
						if(resp.response_package.response_result > 0) {
							location.href = __HOSTNAME__ + "/terminologi/child/" + __PAGES__[2];
						}
					},
					error:function(resp) {
						console.log(resp);
					}
				});	
			} else {

			}
		});
	});
</script>