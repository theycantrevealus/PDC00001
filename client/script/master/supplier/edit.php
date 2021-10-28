<script type="text/javascript">
	$(function() {
		var uid = <?php echo json_encode(__PAGES__[3]); ?>;

		$.ajax({
			url:__HOSTAPI__ + "/Supplier/detail/" + uid,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
                var jenis = response.response_package.supplier_type;
                var nama = response.response_package.nama;
				var kontak = response.response_package.kontak;
				var email = response.response_package.email;
				var alamat = response.response_package.alamat;

				$("#txt_nama").val(nama);
				$("#txt_kontak").val(kontak);
				$("#txt_email").val(email);
				$("#txt_alamat").val(alamat);

                $("#txt_jenis").val(jenis);

				$('#txt_kontak').inputmask('0999 9999 9999');
			},
			error: function(response) {
				console.log(response);
			}
		});

		

		$("form").submit(function() {

			var nama = $("#txt_nama").val();
			var kontak = $("#txt_kontak").inputmask("unmaskedvalue");
			var email = $("#txt_email").val();
            var jenis = $("#txt_jenis option:selected").val();
			var alamat = $("#txt_alamat").val();

			if(nama != "" && kontak != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Supplier",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					data:{
						request: "edit_supplier",
						uid:uid,
						nama:nama,
						kontak:kontak,
						email:email,
						alamat:alamat,
                        jenis:jenis
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