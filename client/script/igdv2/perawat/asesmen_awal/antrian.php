<script src="<?php echo __HOSTNAME__; ?>/plugins/range-slider-master/js/rSlider.min.js"></script>
<link href="<?php echo __HOSTNAME__; ?>/plugins/paginationjs/pagination.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
	$(function() {

        var d = new Date();

        $("a[data-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
            var targetPage = $(this).attr("href");
            
        });
    });
</script>