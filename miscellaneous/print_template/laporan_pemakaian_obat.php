<html>
<head>
    <style type='text/css'>
        @font-face {
            font-family: "GreyCLiff";
            src: url("<?php echo $_POST['__HOSTNAME__'] . '/template/assets/fonts/FontsFree-Net-greycliff-cf-bold.ttf'; ?>");
        }

        @page {
            size: auto;
            margin: 0
        }

        @media print {
            @page {
                size: legal landscape;
            }

            html, body {
                padding: 0 !important;
                background: #fff !important;
            }

            .content {
                margin: 0 !important;
                border: none;
            }

            table.constructor thead {display: table-header-group !important;}
            table.constructor tfoot {display: table-footer-group !important;}


        }

        html, body{
            position: relative;
            width: 100%;
            background: #fff !important;
        }

        .card, .card-body, .card-header {
            border: none !important;
        }

        table.table { page-break-inside:auto }
        table.table tr    { page-break-inside:avoid; page-break-after:auto }
        table.table thead { display: table-row-group; }
        table.table tfoot { display:table-row-group; }



        .grey-up {
            background: #ccc;
            font-weight: bolder;
        }

        table.constructor {
            width: 100%;
        }

        table.constructor tbody td {
            padding: 0cm 1cm;
        }

        table.form-mode tbody tr td {
            padding: 0 10px !important;
        }

        .content {
            position: relative;
            background: #fff;
            margin: -1px !important;
        }

        .content .tl {
            position: fixed;
            top: -5px; left: -5px;
        }

        .header {
            left: 1cm; top: 1cm;
            right: 1cm;
            position: fixed;
            text-align:left;
            margin-bottom:1rem;
            font-family: GreyCLiff;
        }

        .header-space {
            height: 160px;
        }

        h1.title-name {
            font-family: GreyCLiff;
            font-size: 14pt;
            text-align: center;
            letter-spacing: 2px;
        }

        .header h1, h2, h3, h4, h5, h6 {
            color: #000 !important;
            margin: 0;
        }

        .header h1 {
            font-size: 14pt;
            letter-spacing: 2px;
        }

        .header h1 small {
            color: #0199f0;
            letter-spacing: 0;
        }

        .header table {
            width: 100%;
            padding-bottom: .5cm;
            border-bottom: solid 3px #0199f0;
        }

        .header table tr td {
            vertical-align: top;
        }

        .header .header-information {
            color: #979797;
        }

        .logo-container {
            width: 10%;
        }

        img.logo{
            float:left;
            width: 4cm;
            height: 2cm;
        }

        .print-date {
            width: 100%;
            text-align: right;
        }



        .report_content {
            font-size: 8pt !important;
            page-break-after: always;
        }

        .row {
            width: 100%;
        }

        .row div {
            float: left;
        }

        .row div.col-3 {
            width: 25%;
        }

        .row div.col-12 {
            width: 100%;
        }

        span,b, p {
            font-size: 10pt;
        }

        .wrap_content {
            white-space:nowrap;
            min-width: 5px !important;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
            border: solid 1px #000 !important;
            border-width:1px !important;
        }

        table.table.table-bordered tbody tr td{
            border-bottom: solid 1px #000 !important;
            padding: 5px !important;
            border-width:1px !important;
        }

        table.table.table-bordered-full tbody tr td{
            padding: 5px;
            border: solid 1px #000 !important;
            border-width:1px !important;
        }

        table.table.table-bordered tbody tr td[colspan] {
            color: #0199f0 !important;
            padding: 20px 20px 0 20px;
            border-bottom: solid 1px #000 !important;
        }

        table.table.table-bordered tbody tr td[colspan].text-mode {
            color: #000 !important;
        }

        table.table thead.thead-dark tr th {
            padding: 5px !important;
            color: #fff;
            border-bottom: solid 1px #000 !important;
            font-size: 10pt !important;
            text-align: left !important;
        }

        table.table tfoot tr td{
            padding: 5px !important;
            border: solid 1px #000 !important;
        }

        td.special-type-padding{
            padding-bottom: 20px !important;
        }

        .number_style {
            text-align: right;
            font-family: "Courier New" !important;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .largeDataType tbody tr td {
            vertical-align: top !important;
            font-size: 10pt;
        }

        #qrcodeImage img {
            width: 128px;
            height: 128px;
            margin: 0 auto;
            text-align: center;
            background: #ccc;
        }
    </style>

</head>
<body>
<div class="content">
    <img class="tl" src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/simrsv2/client/template/assets/images/TOPLEFT.png" style="width: 250px; height: 250px;" alt="top-left" />
    <div class="header">
        <table>
            <tr>
                <td class="logo-container">
                    <img src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/simrsv2/client/template/assets/images/logo-text-white.png" class="logo">
                </td>
                <td>
                    <h1>
                        <small><?php echo (isset($_POST['__PC_CUSTOMER_GROUP__'])) ? $_POST['__PC_CUSTOMER_GROUP__'] : 'CUSTOMER GROUP NAME'; ?></small>
                        <br />
                        <?php echo (isset($_POST['__PC_CUSTOMER__'])) ? $_POST['__PC_CUSTOMER__'] : 'CUSTOMER COMPANY FULL NAME'; ?>
                    </h1>
                    <small class="header-information">
                        <?php echo (isset($_POST['__PC_CUSTOMER_ADDRESS__'])) ? $_POST['__PC_CUSTOMER_ADDRESS__'] : 'CUSTOMER ADDRESS'; ?><br />
                        Telp. <?php echo (isset($_POST['__PC_CUSTOMER_CONTACT__'])) ? $_POST['__PC_CUSTOMER_CONTACT__'] : '085261510202'; ?>
                    </small>
                </td>
                <td style="width:2%;"></td>
            </tr>
        </table>
    </div>
    <?php
    if(isset($_POST['data_laporan'])) {
        ?>

        <table class="constructor">
            <thead>
            <tr>
                <td>
                    <div class="header-space"></div>
                </td>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>
                    <div class="report_content">
                        <div class="print-date">
                            <span>Tanggal Cetak:<br /><b><?php echo date('d F Y, H:i'); ?></b></span>
                        </div>
                        <h1>
                            <?php echo $_POST['__JUDUL__']; ?>
                            <br />
                            <small><?php echo date('d F Y', strtotime($_POST['__PERIODE_AWAL__'])); ?> - <?php echo date('d F Y', strtotime($_POST['__PERIODE_AKHIR__'])); ?></small>
                        </h1>

                        <table class="table border-bottom mb-5 data table-bordered largeDataType">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Obat/BHP</th>
                                <?php
                                foreach($_POST['__PENJAMIN__'] as $key => $value) {
                                    ?>
                                    <th><?php echo $value['nama']; ?></th>
                                    <?php
                                }
                                ?>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $autonum = 1;
                            foreach ($_POST['data_laporan'] as $parseKey => $parseValue) {
                                ?>
                                <tr>
                                    <td><?php echo $autonum; ?></td>
                                    <td><?php echo $parseValue['obat']['nama']; ?></td>
                                    <?php
                                    foreach($_POST['__PENJAMIN__'] as $key => $value) {
                                        ?>
                                        <td><?php echo $_POST['data'][$parseKey][$value['uid']]; ?></td>
                                        <?php
                                    }
                                    ?>
                                    <td class="number_style"><?php echo $parseValue['total']; ?></td>
                                </tr>
                                <?php
                                $autonum++;
                            }
                            ?>
                            </tbody>
                            <pre>
                                <?php
                                foreach ($_POST['data'] as $parseKey => $parseValue) {
                                    print_r($parseValue['autonum']);
                                }

                                ?>
                            </pre>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    } else {
        ?>
        <table class="constructor">
            <thead>
            <tr>
                <td>
                    <div class="header-space"></div>
                </td>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>
                    <div class="report_content">
                        <table class="table table-bordered table-striped largeDataType" id="invoice_detail_history">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content"></th>
                                <th class="wrap_content">No</th>
                                <th>Item</th>
                                <th class="wrap_content">Jlh</th>
                                <th class="number_style" style="max-width: 200px; width: 200px">Harga</th>
                                <th class="number_style" style="max-width: 200px; width: 200px">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="wrap_content" colspan="2"></td>
                                <td class="bg-info wrap_content" colspan="4" style="color: #fff">TINDAKAN</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">1</td>
                                <td style="">JAHITAN LUKA 1-3</td>
                                <td class="number_style" style="">1</td>
                                <td class="number_style text-right" style="">150,000.00</td>
                                <td class="number_style text-right" style="">150,000.00</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">2</td>
                                <td style="">JAHITAN LUKA 4-6</td>
                                <td class="number_style" style="">1</td>
                                <td class="number_style text-right" style="">160,000.00</td>
                                <td class="number_style text-right" style="">160,000.00</td>
                            </tr>
                            <tr>
                                <td class="wrap_content" colspan="2"></td>
                                <td class="bg-info wrap_content" colspan="4" style="color: #fff">OBAT</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">1</td>
                                <td style="">DUVADILAN TABLET 20 MG</td>
                                <td class="number_style" style="">3</td>
                                <td class="number_style text-right" style="">6,531.25</td>
                                <td class="number_style text-right" style="">19,593.75</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">2</td>
                                <td style="">PARASETAMOL TABLET 500 MG</td>
                                <td class="number_style" style="">5</td>
                                <td class="number_style text-right" style="">125.00</td>
                                <td class="number_style text-right" style="">625.00</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">3</td>
                                <td style="">FENOBARBITAL TABLET 30 MG</td>
                                <td class="number_style" style="">6</td>
                                <td class="number_style text-right" style="">225.00</td>
                                <td class="number_style text-right" style="">1,350.00</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">4</td>
                                <td style="">METFORMIN TABLET 850 MG</td>
                                <td class="number_style" style="">6</td>
                                <td class="number_style text-right" style="">217.50</td>
                                <td class="number_style text-right" style="">1,305.00</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-exclamation-circle text-warning"></i></td>
                                <td class="wrap_content">5</td>
                                <td style="">CODIPRONT KAPSUL</td>
                                <td class="number_style" style="">6</td>
                                <td class="number_style text-right" style="">9,922.50</td>
                                <td class="number_style text-right" style="">59,535.00</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4" rowspan="2" id="keterangan-faktur"></td>
                                <td class="text-right">
                                    Total
                                </td>
                                <td id="total-faktur" class="text-right">392,408.75</td>
                            </tr>
                            <!--tr>
                                <td class="text-right">Diskon</td>
                                <td id="diskon-faktur" class="text-right">

                                </td>
                            </tr-->
                            <tr>
                                <td class="text-right">
                                    Grand Total
                                </td>
                                <td id="grand-total-faktur" class="text-right">392,408.75</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>

</body>
</html>