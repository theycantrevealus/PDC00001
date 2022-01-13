<script type="text/javascript">
	$(function() {

		$('#txt_kontak').inputmask('0999 9999 9999');

		$("form").submit(function() {

			var nama = $("#txt_nama").val();
			var kontak = $("#txt_kontak").inputmask("unmaskedvalue");
			var email = $("#txt_email").val();
			var alamat = $("#txt_alamat").val();
            var jenis = $("#txt_jenis option:selected").val();

			if(nama != "" && kontak != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Supplier",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					data:{
						request: "tambah_supplier",
						nama:nama,
						kontak:kontak,
						email:email,
						alamat:alamat,
                        jenis: jenis
					},
					success:function(response) {
						location.href = __HOSTNAME__ + "/master/supplier";
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
			return false;

		});

	});
</script>