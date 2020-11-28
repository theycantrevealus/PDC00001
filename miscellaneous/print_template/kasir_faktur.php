<html>
<head>
    <style type='text/css'>
        @media print {
            @page {
                size:A4;
                padding:0.3cm;
            }
        }
        body{
            margin:auto 0px;
            color: #000;
            font-family: Courier;
            text-align:center;
        }

        .header{
            padding-bottom:1rem;
            border-bottom:1px dashed #F2F2F2;
            text-align:left;
            margin-bottom:1rem;
        }

        img.logo{
            float:left;
            padding-right: 1rem;
            max-height:100px;
        }

        span{
            display:block;
        }

        span.title{
            font-weight: bold;
            font-size:1.2rem;
        }
        span.alamat, span.telepon{
            font-size:0.8rem;
        }

        .width-50{
            width:50%;
            float:left;
        }

        .both{
            clear:both;
        }

        h4.cetak{
            text-decoration:underline;
        }

        table {
            margin-bottom: 20px;
        }

        table thead tr th{
            font-size:1.1rem;
        }

        table{
            border-collapse: collapse;
            width:100%;
        }

        tr td.gray{
            background-color:#F2F2F2;
        }

        table.table tr td, table.table tr th{
            border: 1px solid #e6ecf5;
            padding:0.3rem;
            font-size:0.8rem;
        }

        .text-left{
            text-align:left;
        }
        .text-center{
            text-align:center;
        }
        .text-right{
            text-align:right;
        }

        .isian{
            font-size: 1.2rem;
        }

        td.noborder-right{
            border-right:0 !important;
        }
        td.noborder-left{
            border-left:0 !important;
        }

        div.footer{
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
<body>
<div class="header">
    <table>
        <tr>
            <td style="text-align:center; width:5%">
                <img src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/simrsv2/client/template/assets/images/logo-icon.png" class="logo">
            </td>
            <td style="width:45%;">
                <span class="title"><b>PEMERINTAH KABUPATEN BINTAN</b></span>
                <span class="title">DINAS KESEHATAN</span>
                <span class="title"><?php echo $_POST['__PC_CUSTOMER__']; ?></span>
                <span class="alamat"><?php echo $_POST['__PC_CUSTOMER_ADDRESS__']; ?></span>
                <span class="telepon">Telp. <?php echo $_POST['__PC_CUSTOMER_CONTACT__']; ?></span>
            </td>
            <td style="width:2%;"></td>
        </tr>
    </table>
    ========================================================================================================
    <center style="padding: 1% 0 1% 0; font-size: 1.5rem;"><b>KWINTANSI</b></center>
    --------------------------------------------------------------------------------------------------------
    <div class="middle" style="">
        <?php echo($_POST['kwitansi_data']); ?>
    </div>
</div>
</body>
</html>