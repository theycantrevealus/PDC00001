<script type="text/javascript">
    $(function () {
        var UID = __PAGES__[4];
        loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3], UID);


        $("#filter_date").change(function() {
            loadCPPT(getDateRange("#filter_date")[0], getDateRange("#filter_date")[1], __PAGES__[3]);
        });
    });
</script>