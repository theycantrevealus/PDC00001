<script type="text/javascript">
	$(function() {
		var poliList = <?php echo json_encode($_SESSION['poli']['response_data'][0]['poli']['response_data']); ?>;

		if(poliList.length > 1) {
			$("#change-poli").show();
			$("#current-poli").addClass("handy");
		} else {
			$("#change-poli").hide();
			$("#current-poli").removeClass("handy");
		}

		$("#current-poli").prepend(poliList[0]['nama']);

		function load_poli_info() {
			//
		}
	});
</script>