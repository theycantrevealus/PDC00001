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

        table.head tr td{
            font-size:1.1rem;
        }

        table{
            border-collapse: collapse;
            width:100%;
        }

        tr td.gray{
            background-color:#F2F2F2;
        }

        table.border-style tr td, table.border-style tr th{
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

        /* .terbilang{
            margin-top:1rem;
            padding:0.6rem;
            border:1px dashed #F2F2F2;
            font-size:0.8rem;
        } */

        td.noborder-right{
            border-right:0 !important;
        }
        td.noborder-left{
            border-left:0 !important;
        }

        div.footer{
            page-break-after: always;
        }
    </style>

</head>
<body>
<div class="header">
    <table>
        <tr>
            <td style="text-align:center; width:5%">
                <img src="../client/template/assets/images/logo-icon.png" class="logo">
            </td>
            <td style="width:45%;">
                <span class="title"><b>PEMERINTAH KABUPATEN BINTAN</b></span>
                <span class="title">DINAS KESEHATAN</span>
                <span class="title"><?php echo __PC_CUSTOMER__;?></span>
                <span class="alamat"><?php echo $_POST['alamat'];?></span>
                <span class="telepon">Telp. <?php echo $_POST['no_telepon'];?></span>
            </td>
            <td style="width:2%;"></td>
            <td style="border: 1px solid #000000; padding : 1% 1% 1% 2%">
                <table class="head">
                    <tr><td>No. RM</td><td>:</td><td><?php echo $_POST['no_rm'];?></td></tr>
                    <tr><td>Nama Pasien</td><td>:</td><td><?php echo $_POST['nama_panggilan'].". ".$_POST['nama_pasien'];?></td></tr>
                    <tr><td>Tanggal Lahir</td><td>:</td><td><?php echo $_POST['tanggal_lahir'];?></td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>
========================================================================================================
<span style="padding: 1% 0 1% 0; font-size: 1.5rem;"><b>DATA SOSIAL PASIEN</b></span>
--------------------------------------------------------------------------------------------------------
<div class="middle" style="">
    <table class="isian" cellpadding="8" style="/*border: 1px solid #000000; */padding-left: 10%;">
        <thead>
        <th style="width:25%;"></th>
        <th></th>
        <th></th>
        </thead>
        <tbody>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><b><?= $_POST['nama']; ?></b></td>
        </tr>
        <tr>
            <td>Nama Ayah</td>
            <td>:</td>
            <td width="20"><b><?= $_POST['ayah']; ?></b></td>
        </tr>
        <tr>
            <td>Nama Ibu</td>
            <td>:</td>
            <td width="20"><b><?= $_POST['ibu']; ?></b></td>
        </tr>
        <tr>
            <td>Nama Suami / Istri</td>
            <td>:</td>
            <td width="20"><b><?= $_POST['suami_istri']; ?></b></td>
        </tr>
        <tr>
            <td>Alamat Lengkap</td>
            <td>:</td>
            <td rowspan=""><b><?= $_POST['alamat'] . $_POST['kel'] . $_POST['kec'] . $_POST['kab'] . $_POST['prov']; ?></b></td>
        </tr>
        <!-- <tr>
            <td>&nbsp;</td>
        </tr> -->
        <tr>
            <td>Tempat / Tanggal Lahir</td>
            <td>:</td>
            <td><b><?= $_POST['tempat_lahir'] . " / " . $_POST['tanggal_lahir']; ?></b></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td><b><?= $_POST['nama_jenkel']; ?></b></td>
        </tr>
        <tr>
            <td>Status Perkawinan</td>
            <td>:</td>
            <td><b><?= $_POST['status_kawin']; ?></b></td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td><b><?= $_POST['agama'];?></b></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td><b><?= $_POST['pekerjaan']; ?></b></td>
        </tr>
        <tr>
            <td>Kewarganegaraan</td>
            <td>:</td>
            <td><b><?= $_POST['warga_negara']; ?></b></td>
        </tr>
        <tr>
            <td>Suku</td>
            <td>:</td>
            <td><b><?= $_POST['suku']; ?></b></td>
        </tr>
        <tr>
            <td>No. Telp / HP</td>
            <td>:</td>
            <td><b><?= $_POST['no_handphone']; ?></b></td>
        </tr>
        <tr>
            <td>Kartu Identitas</td>
            <td>:</td>
            <td><b><?= $_POST['identitas']; ?></b></td>
        </tr>
        <tr>
            <td>Riwayat Alergi Obat</td>
            <td>:</td>
            <td rowspan="2"><span style="border: 1px solid #000000"><textarea cols="100" rows="5"></textarea></span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Pembiayaan</td>
            <td>:</td>
            <td rowspan="2">
                <div style="inline-block">
                    <div style="col-sm-4">
                        [ ] Pribadi
                    </div>
                    <div style="col-sm-4">
                        [ ] Jaminan Asuransi ________________
                    </div>
                    <div style="col-sm-4">
                        [ ] Jaminan KK / KTP
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <br />
    <span style="font-size:11pt; text-align:left; color: red;"><b>* Jika ada perubahan data sosial akan saya informasikan kepada pihak RS pada kunjungan berikutnya.</b></span>
    <span style="text-align:center;">------------------------------------------------------------------------------------------</span>
    <span style="font-size:15pt; text-align:right; padding-top: 1%;"><b>Kijang, <?= $_POST['waktu2']; ?> </b></span>
    <br />
    <br />
    <table style="text-align: center;" class="isian">
        <tbody>
        <tr>
            <td>
                <b>Petugas Pendaftaran, <br/> RSUD Kab. Bintan</b>
            </td>
            <td>
            </td>
            <td>
                <b>Pasien / Penanggung Jawab</b>
            </td>
        </tr>
        <tr>
            <td>
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                <b>(..................................)</b>
            </td>
            <td>
            </td>
            <td>
                <b>(..................................)</b>
            </td>
        </tr>
        <tr>
            <td>
                <b>Nama dan Tanda Tangan</b>
            </td>
            <td>
            </td>
            <td>
                <b>Nama dan Tanda Tangan</b>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="footer"></div>
</body>
</html>