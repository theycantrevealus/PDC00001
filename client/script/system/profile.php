<script type="text/javascript">
    $(function () {
        $("#btnSimpan").click(function () {
            var pass_old = $("#txt_pass_old").val();
            var pass_new = $("#txt_pass_new").val();
            var pass_conf = $("#txt_pass_conf").val();

            if(pass_new !== pass_conf) {
                Swal.fire(
                    "Ubah Password",
                    "Harap ketikkan password yang sama pada kolom konfirmasi",
                    "error"
                ).then((result) => {
                    $("#txt_pass_conf").val("").focus();
                });
            } else {
                Swal.fire({
                    title: 'Ubah Password?',
                    text: 'Password baru akan diaplikasi pada login selanjutnya',
                    showDenyButton: true,
                    confirmButtonText: `Ya`,
                    denyButtonText: `Batal`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url: __HOSTAPI__ + "/Pegawai",
                            data: {
                                request: "edit_password",
                                old: pass_old,
                                new: pass_new
                            },
                            beforeSend: function (request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            success: function (response) {
                                var parser = response.response_package.response_result;
                                if(parser > 0) {
                                    Swal.fire(
                                        "Ubah Password",
                                        response.response_package.response_message,
                                        "success"
                                    ).then((result) => {
                                        //location.href = __HOSTNAME__  + "/system/logout";
                                        location.href = __HOSTNAME__;
                                    });
                                } else {
                                    Swal.fire(
                                        "Ubah Password",
                                        response.response_package.response_message,
                                        "error"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function (response) {
                                //
                            }
                        });
                    }
                });
            }
        });
    });
</script>