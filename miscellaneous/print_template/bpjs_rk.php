<html>

<head>
    <style type='text/css' media="print">
        @page {
            size: A5 landscape;
            margin: 0;
        }

        @media print {
            @page html {
                size: A5 landscape !important;
                padding: .3cm !important;
            }
        }

        * {
            background: #fff !important;
        }

        body {
            size: A5 landscape !important;
            padding: .3cm !important;
            color: #000;
            font-family: Courier;
            text-align: center;
        }

        .header {
            padding-bottom: 1rem;
            text-align: left;
            margin-bottom: 1rem;
        }

        img.logo {
            float: left;
            padding-right: 1rem;
            max-height: 100px;
        }

        span {
            display: block;
        }

        span.title {
            font-weight: bold;
            font-size: 1.2rem;
        }

        span.alamat,
        span.telepon {
            font-size: 0.8rem;
        }

        .width-50 {
            width: 50%;
            float: left;
        }

        .both {
            clear: both;
        }

        h4.cetak {
            text-decoration: underline;
        }

        table {
            margin-bottom: 20px;
        }

        table thead tr th {
            font-size: 1.1rem;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr td.gray {
            background-color: #F2F2F2;
        }

        table.table tr td,
        table.table tr th {
            border: 1px solid #e6ecf5;
            padding: 0.3rem;
            font-size: 0.8rem;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .isian {
            font-size: 1.2rem;
        }

        td.noborder-right {
            border-right: 0 !important;
        }

        td.noborder-left {
            border-left: 0 !important;
        }

        div.footer {
            page-break-after: always;
        }

        .row {
            position: relative;
            width: 100%;
        }

        .row div {
            float: left;
        }

        .col-4 {
            width: 33.33%;
        }
    </style>

</head>

<body style="size: A5 landscape">
    <div class="header">
        <table>
            <tr>
                <td style="text-align:center; width:20%">
                    <img width="70%" src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/simrsv2/client/template/assets/images/bpjs.png" class="logo">
                </td>
                <td style="width:65%;">
                    <span class="title">
                        <h4>
                            SURAT RENCANA KONTROL <br>
                            RSUD PETALA BUMI PROVINSI RIAU
                        </h4>
                        <?php echo $_POST['__PC_CUSTOMER__']; ?>z
                    </span>
                </td>
                <td style="width:2%;"></td>
            </tr>
        </table>
        <div>
            <table>
                <tr>
                    <td style="vertical-align: top">
                        <?php echo $_POST['html_data_kiri']; ?>
                    </td>
                    <td style="vertical-align: top">
                        <?php echo $_POST['html_data_kanan']; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>