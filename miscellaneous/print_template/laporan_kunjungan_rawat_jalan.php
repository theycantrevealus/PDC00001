<html>
<head>
    <style type='text/css'>

        @page {
            size: A4 landscape;  
        }

        @media print {
            @page {
                size: A4 landscape;
                padding:0.3cm;
            }

            html {
                overflow: hidden;
            }

            .pagebreak { page-break-before: always; }
        }

        body{
            width: 90%;
            margin: 0 auto;
            overflow: hidden;
            padding: 1cm;
            color: #000;
            font-family: "Arial", sans-serif;
            text-align:center;
            page-break-after: auto;
        }

        .header{
            padding-bottom: 30px;
            text-align:left;
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

        table.status {
            width: 30%;
            margin-top: 20px;
        }

        table.status tr td {
            font-size: 10pt;
            padding: 5px;
            border: solid 1px #808080;
            text-align: left;
        }

        table.data {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.6rem;
        }

        tr td.gray{
            background-color:#F2F2F2;
        }

        table.table tr td, table.table tr th{
            padding:0.1rem;
            font-size: 8pt !important;
        }

        table.table thead tr th {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        table.table, table.table th , table.table td{
            border: 1px solid #000;
            border-collapse: collapse;
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
            <img class="navbar-brand-icon mb-2" src="<?php echo $_POST['__HOSTNAME__']; ?>/template/assets/images/clients/logo-icon-petala2.png" width="50" alt="<?php echo $_POST['__PC_CUSTOMER__']; ?>">
            </td>
            <td style="width:45%;">
                <span class="title"><b><?php echo $_POST['__PC_CUSTOMER__']; ?></b></span>
                <span class="alamat"><?php echo $_POST['__PC_CUSTOMER_ADDRESS__']; ?></span>
                <span class="telepon">Telp. <?php echo $_POST['__PC_CUSTOMER_CONTACT__']; ?></span>
            </td>
            <td style="width:2%;"></td>
        </tr>
    </table>
    <center>
        <b>
            <br /><br />
            <span><?php echo $_POST['__JUDUL__']; ?></span>
            <small><?php echo date('d F Y', strtotime($_POST['__PERIODE_AWAL__'])); ?> - <?php echo date('d F Y', strtotime($_POST['__PERIODE_AKHIR__'])); ?></small>
        </b>
    </center>
</div>
<div>
    <table class="table border-bottom mb-5 data">
        <thead class="thead-dark">
        <tr>
            <th width="1">NO</th>
            <th>TGL MSK</th>
            <th>TGL KLR</th>
            <th>NO. RM</th>
            <th>PASIEN</th>
            <th>JK</th>
            <th>POLIKLINIK</th>
            <th>Jaminan</th>
            
        </tr>
        </thead>
        <tbody>
        <?php

        $dataBuild = array();
        
        $dataBuild = array();
        // foreach ($_POST['data'] as $datKey => $datValue) {
        //     if(!isset($dataBuild[$datValue['penjamin']['uid']])) {
        //         $dataBuild[$datValue['penjamin']['uid']] = array(
        //             'nama' => $datValue['penjamin']['nama'],
        //             'data' => array()
        //         );
        //     }
        //     array_push($dataBuild[$datValue['penjamin']['uid']]['data'], $datValue);
        // }


        // foreach ($dataBuild as $parseKey => $parseValue) {
            foreach ($_POST['data'] as $itemKey => $itemValue) {
                ?>
                <tr>
                    <td><?php echo $itemValue['autonum']; ?></td>
                    <td><?php echo $itemValue['waktu_masuk']; ?></td>
                    <td><?php echo $itemValue['waktu_keluar']; ?></td>
                    <td><?php echo $itemValue['pasien']['no_rm']; ?></td>
                    <td><?php echo $itemValue['pasien']['panggilan_name']['nama'] . ' ' . $itemValue['pasien']['nama']; ?></td>
                    <td><?php echo $itemValue['pasien']['jenkel_detail']['nama']; ?></td>
                    <td><?php echo $itemValue['nama_departemen'] ?></td>
                    <td><?php echo $itemValue['penjamin']['nama']; ?></td>
                    
                </tr>
                <?php
            }
            ?>
            <!-- <tr>
                <td colspan="2"></td>
                <td style="border-top: solid 1px #000;">Jumlah Pasien : <?php echo count($parseValue['data']); ?></td>
                <td colspan="3"></td>
            </tr> -->
        <?php
        // }
        ?>
        </tbody>
    </table>
    <!-- <table class="status">
        <tr>
            <td>Count of Queue Status</td>
            <td></td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>Total</td>
        </tr>
        <?php
        foreach ($dataBuild as $parseKey => $parseValue) {
            ?>
            <tr>
                <td><?php echo $parseValue['nama']; ?></td>
                <td><?php echo count($parseValue['data']); ?></td>
            </tr>
        <?php
        }
        ?>
    </table> -->
</div>
</body>
</html>