<html>
    <head>
        <style type='text/css'>

            @page {
                size: auto;
                margin: 0;
            }

            @media print {
                @page {
                    size:A4;
                    padding:0.3cm;
                }

                table {
                    page-break-inside: avoid;
                }
            }

            body{
                padding: 1cm;
                color: #000;
                font-family: Courier;
                text-align:center;
            }

            .header{
                padding-bottom:1rem;
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
                        <span class="title"><b>PEMERINTAH PROVINSI RIAU</b></span>
                        <span class="title"><?php echo $_POST['__PC_CUSTOMER__']; ?></span>
                        <br />
                        <span class="alamat"><?php echo $_POST['__PC_CUSTOMER_ADDRESS__']; ?></span>
                        <span class="telepon">Telp. <?php echo $_POST['__PC_CUSTOMER_CONTACT__']; ?></span>
                    </td>
                    <td style="width:2%;"></td>
                </tr>
            </table>
            <hr />
            <table class="form-mode">
                <tr>
                    <td>Kode Resep</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['kode'] ?></td>

                    <td>Tanggal Resep</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['tanggal_resep'] ?></td>
                </tr>
                <tr>
                    <td>No MR</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['no_mr'] ?></td>

                    <td>Jenis Pasien</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['jenis_pasien'] ?></td>
                </tr>
                <tr>
                    <td>Nama Pasien</td>
                    <td class="wrap_content">:</td>
                    <td><b><?php echo $_POST['nama_pasien'] ?></b></td>

                    <td>Unit</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['departemen'] ?></td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['tanggal_lahir'] ?></td>

                    <td>Nama Dokter</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['dokter'] ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['jenis_kelamin'] ?></td>

                    <td>Cara Bayar</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['penjamin'] ?></td>
                </tr>
                <tr>
                    <td>Alergi Obat</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['alergi'] ?></td>

                    <td>Nomor SEP</td>
                    <td class="wrap_content">:</td>
                    <td><?php echo $_POST['sep'] ?></td>
                </tr>
            </table>
        </div>
        <div>
            <hr />
            <center style="padding: 1% 0 1% 0; font-size: 1.5rem;"><b>RESEP DOKTER</b></center>
            <div>
                <h5>Obat/BHP</h5>
                <table class="table table-bordered largeDataType">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th style="width: 20%">Nama Obat/BHP</th>
                        <th class="wrap_content">Satuan</th>
                        <th class="wrap_content">Kuantitas</th>
                        <th class="wrap_content">Aturan Pakai</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $autonum = 1;
                    foreach ($_POST['resep_dokter'] as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $autonum; ?></td>
                            <td><span class="wrap_content"><?php echo $value['obat']; ?></span></td>
                            <td><?php echo $value['satuan']; ?></td>
                            <td><?php echo $value['kuantitas']; ?></td>
                            <td><?php echo $value['signa']; ?></td>
                            <td><?php echo $value['keterangan']; ?></td>
                        </tr>
                        <?php
                        $autonum++;
                    }
                    ?>
                    </tbody>
                </table>
                <br />
                <h5>Racikan</h5>
                <table class="table table-bordered largeDataType">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th style="width: 20%">Nama Racikan</th>
                        <th class="wrap_content">Kuantitas</th>
                        <th class="wrap_content">Aturan Pakai</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $autonum = 1;
                    foreach ($_POST['racikan_dokter'] as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $autonum; ?></td>
                            <td><span class="wrap_content"><?php echo $value['racikan']; ?></span></td>
                            <td><?php echo $value['kuantitas']; ?></td>
                            <td><?php echo $value['signa']; ?></td>
                            <td><?php echo $value['keterangan']; ?></td>
                        </tr>
                        <?php
                        $autonum++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <hr />
            <hr />
            <center style="padding: 1% 0 1% 0; font-size: 1.5rem;"><b>BILLING APOTEK</b></center>
            <div>
                <h5>Obat/BHP</h5>
                <table class="table table-bordered largeDataType">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th>Nama Obat/BHP</th>
                        <th class="wrap_content">Satuan</th>
                        <th class="wrap_content">Kuantitas</th>
                        <th class="wrap_content">Aturan Pakai</th>
                        <th>Keterangan</th>
                        <th colspan="2" style="max-width: 15%; width: 15%">Harga</th>
                        <th colspan="2" style="max-width: 15%; width: 15%">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $autonum = 1;
                    foreach ($_POST['resep_apotek'] as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $autonum; ?></td>
                            <td><?php echo $value['obat']; ?></td>
                            <td><?php echo $value['satuan']; ?></td>
                            <td><?php echo $value['kuantitas']; ?></td>
                            <td><?php echo $value['signa']; ?></td>
                            <td><?php echo $value['keterangan']; ?></td>
                            <td style="border-right: solid 1px #fff !important">Rp.</td>
                            <td><?php echo $value['harga']; ?></td>
                            <td style="border-right: solid 1px #fff !important">Rp.</td>
                            <td><?php echo $value['subtotal']; ?></td>
                        </tr>
                        <?php
                        $autonum++;
                    }
                    ?>
                    </tbody>
                </table>
                <br />
                <h5>Racikan</h5>
                <table class="table table-bordered largeDataType">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th>Nama Racikan</th>
                        <th class="wrap_content">Kuantitas</th>
                        <th class="wrap_content">Aturan Pakai</th>
                        <th>Keterangan</th>
                        <th colspan="2" style="max-width: 15%; width: 15%">Harga</th>
                        <th colspan="2" style="max-width: 15%; width: 15%">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $autonum = 1;
                    foreach ($_POST['racikan_apotek'] as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $autonum; ?></td>
                            <td><?php echo $value['obat']; ?></td>
                            <td><?php echo $value['kuantitas']; ?></td>
                            <td><?php echo $value['signa']; ?></td>
                            <td><?php echo $value['keterangan']; ?></td>
                            <td style="border-right: solid 1px #fff !important">Rp.</td>
                            <td class="number_style"><?php echo $value['harga']; ?></td>
                            <td style="border-right: solid 1px #fff !important">Rp.</td>
                            <td class="number_style"><?php echo $value['subtotal']; ?></td>
                        </tr>
                        <?php
                        $autonum++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <table class="table table-bordered largeDataType">
                <tr>
                    <td class="wrap_content"><b class="wrap_content">Total Bayar</b></td>
                    <td><?php echo $_POST['total_bayar']; ?></td>
                </tr>
                <tr>
                    <td class="wrap_content"><b class="wrap_content">Terbilang</b></td>
                    <td class="text-right">
                        <h5><i><?php echo $_POST['terbilang']; ?> Rupiah</i></h5>
                    </td>
                </tr>
            </table>
            <span>Tanggal Cetak:<br /><b><?php echo date('d F Y, H:i'); ?></b></span>
        </div>
    </body>
</html>