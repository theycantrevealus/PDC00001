<script type="text/javascript">
	$(function(){
		//INIT DATA
		

		$("form").submit(function(){
			$.ajax({
				url:__HOSTAPI__ + "/Pegawai",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				data:{
					request:"tambah_pegawai",
					nama:$("#txt_nama_pegawai").val()
				},
				type:"POST",
				success:function(resp) {
					if(resp.response_package.response_result > 0) {
						location.href = __HOSTNAME__ + "/pegawai";
					} else {
						alert(resp.response_package.response_message);
					}
				},
				error:function(resp) {
					console.log(resp);
				}
			});	
			return false;
		});
	});
</script>