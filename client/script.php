<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/jquery.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/jquery.nicescroll/jquery.nicescroll.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/popper.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/bootstrap.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/simplebar.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/dom-factory.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/material-design-kit.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/toggle-check-all.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/check-selected-row.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/dropdown.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/sidebar-mini.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/app.js"></script>
<!-- <script src="<?php echo __HOSTNAME__; ?>/template/assets/js/app-settings.js"></script> -->
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/flatpickr/flatpickr.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/flatpickr.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/settings.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/Chart.min.js"></script>
<!-- <script src="<?php echo __HOSTNAME__; ?>/template/assets/js/charts.js"></script> -->
<!-- <script src="<?php echo __HOSTNAME__; ?>/template/assets/js/page.dashboard.js"></script> -->
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/vector-maps.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/inputmask/dist/jquery.inputmask.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/inputmask/dist/inputmask.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/jstree/jstree.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/croppie-master/croppie.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/moment/moment.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<!-- <script src="<?php echo __HOSTNAME__; ?>/plugins/jquery-ui-1.12.1/ui/i18n/jquery-ui-i18n.min.js"></script> -->
<script src="<?php echo __HOSTNAME__; ?>/plugins/jquery-ui-1.12.1/ui/i18n/datepicker-id.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/vendor/select2/select2.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/template/assets/js/select2.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/pdfjs/build/pdf.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/DataTables/datatables.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/socketio/socket.io.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/DataTables/datatables.rowgroup.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/swal/dist/sweetalert2.all.min.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/intro/intro.js"></script>
<script type="text/javascript">
    async function refreshToken() {
        return new Promise(async (resolve, reject) => {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}authentification/sync.sh`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-username", "vclaim");
                    request.setRequestHeader("x-password", "vcl$im2022");
                },
                success: function(response) {
                    resolve(response.response.token);
                },
                error: function(error) {
                    reject(error);
                }
            });
        })
    }

    refreshToken().then((test) => {
        bpjs_token = test;
    })

</script>