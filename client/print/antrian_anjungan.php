<html>
<style type="text/css">
    @media print {
        @page {
            size: 4.9cm 9.2cm;
            page-break-inside: avoid !important;
            margin: 0 auto;
            padding:0;
        }
        html, body {
            height: 9.2cm;
            width: 4.9cm;
        }
    }
    html, body {
        height: 9.2cm;
        width: 4.9cm;
        margin: 0 auto;
        /*height: 9.2cm;
        width: 14.9cm;*/
    }
    @page {
        size: 4.9cm 9.2cm;
        margin: 0 auto;
        padding:0;
    }
</style>
<body>
<div style="margin: 0 auto; width: 100%;">
    <center>
        <img alt="icon" src="<?php echo $_POST['__HOSTNAME__']; ?>/template/assets/images/clients/logo-text-black-<?php echo $_POST['__PC_IDENT__']; ?>.png" width="200" height="60" />
        <h5>SELAMAT DATANG DI <br /><?php echo $_POST['__PC_CUSTOMER__']; ?></h5>
        <H6>NOMOR ANTRIAN ANDA</H6>
        <h1 style="font-size: 40pt; font-family: Courier; margin: 0; padding: 0"><?php echo $_POST['antrian']; ?></h1>
        <H6>TERIMA KASIH<br />ANDA TELAH MENUNGGU</H6>
        <h6>Semoga Lekas Sembuh</h6>
        <h6><?php echo date('d F Y'); ?> - <?php echo date('H:i'); ?></h6>
    </center>
</div>
</body>
</html>

