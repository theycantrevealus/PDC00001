<script type="text/javascript">
	push_socket("system", "loggedOut", "*", "User logged out", "info").then(function() {
		$.ajax({
			url:__HOSTAPI__ + "/Pegawai",
			type: "POST",
			data: {
				request: "logged_out"
			},
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			success: function(response) {
				alert();
				localStorage.removeItem("currentLoggedInState");
				location.href = __HOSTNAME__;
			},
			error: function(response) {
				console.log(response);
			}
		});
	});
</script>