<script type="text/javascript">
    $(function () {
        $("#btnResepStokLog").click(function () {
            $.ajax({
                url: __HOSTAPI__ + "/Inventori",
                data: {
                    request: "reset_stok_log",
                },
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function (response) {
                    $("#resepStatus").html("<code>" + JSON.stringify(response) +  "</code>");
                },
                error: function (response) {
                    console.clear();
                    console.log(response);
                }
            });
        });
    });
</script>