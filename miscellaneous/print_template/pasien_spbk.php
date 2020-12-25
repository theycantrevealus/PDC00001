<html>
<head>
    <style type='text/css'>
        @page { size:A4; margin: 1cm }

        body{
            font-family:Times;
            font-size:11px;
        }

        div.box-header{
            text-align:center;
            padding-bottom:10px;
        }

        div.box-body{
            font-size:12px;
            clear:both;
            margin-top:10px;
        }

        span.title{
            margin:0;
            font-size:14px;
            font-weight:bold;
            display:block;
        }

        span.subtitle{
            margin:0;
            font-size:12px;
            font-weight:bold;
            display:block;
        }

        span.alamat{
            margin:0;
            font-size:12px;
            display:block;
        }

        span.telepon{
            font-size:12px;
            display:block;
        }

        .alignleft {
            float: left;
            width:500px;
            text-align:left;
        }
        .aligncenter {
            float: left;
            text-align:left;
        }
        .alignright {
            float: left;
            text-align:right;
        }


         table{
             font-size:11px;
         }

        table thead tr th, table tbody tr td{
            font-size:11px;
        }

        .text-center{
            text-align:center;
        }


        .column {
            float: left;
            width: 33.33%;
            text-align:center;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        .alignleft {
            float: left;
            width:50%;
            text-align:left;
        }
        .alignright {
            float: left;
            text-align:right;
            width:50%;
        }

         .alignright70 {
             float: left;
             width:30%;
             text-align:left;
         }
        .alignright30 {
            float: left;
            width:70%;
        }
    </style>

    <script>
        function myFunction() {
            window.print();
            setTimeout(window.close, 0);
        }
    </script>
</head>

<body>
<div class="box-header">
    <div class="text-center">
        <span class="title">SURAT BUKTI PELAYANAN KESEHATAN (SPBK)<br>RUMAH SAKIT UMUM DAERAH KAB. BINTAN</span>
    </div>
</div>
<hr>
<div class="box-body">
    <div class="alignright30">
        <table style="width:100%;" >
            <tr>
                <td width="10px">1.</td><td width="150px">Nama Pasien</td><td width="20px">:</td>
                <td><?php echo $_POST['nama'];?></td>
            </tr>
            <tr>
                <td>2.</td><td>No. Rekam Medis</td><td>:</td><td><?php echo $_POST['no_rm'];?></td>
            </tr>
            <tr>
                <td>3.</td><td>Tanggal Lahir</td><td>:</td><td><?php echo $_POST['tanggal_lahir'];?></td>
            </tr>
            <tr>
                <td>4.</td><td>Jenis Kelamin</td><td>:</td><td><?php echo $_POST['nama_jenkel'];?></td>
            </tr>
            <tr>
                <td>5.</td><td>Tanggal Masuk RS</td><td>:</td><td><?php echo $_POST['tanggal'];?></td>
            </tr>
            <tr>
                <td>6.</td><td>No. HP/Telp</td><td>:</td><td><?php echo $_POST['no_handphone'];?></td>
            </tr>
        </table>
    </div>
    <div class="alignright70">
        <table>
            <tr>
                <td width="10px"><input type="checkbox"></td><td width="150px">Kunjungan Awal</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td><td>Kunjungan Lanjutan</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td><td>Observasi</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td><td>Post Operasi</td>
            </tr>
            <tr>
                <td></td><td>Berat Lahir: &nbsp; &nbsp; &nbsp; &nbsp; gram</td>
            </tr>
        </table>
    </div>
    <br>
    <table style="width:100%;border-collapse:collapse;" >
        <tbody>
        <tr>
            <td style='border:1px solid #000; padding:4px 4px; text-align:left; width:100%; height:100px; vertical-align:top'>ANAMNESA :</td>
        </tr>
        </tbody>
    </table>
    <br><br>
    <table style="width:100%;border-collapse:collapse;" >
        <thead>
        <tr>
            <th style="border:1px solid #000; padding:6px 4px; text-align:center;">POLIKLINIK/PENUNJANG</th>
            <th style="border:1px solid #000; padding:6px 4px; text-align:center;">DIAGNOSA</th>
            <th style="border:1px solid #000; padding:6px 4px; text-align:center;">ICD-X</th>
            <th style="border:1px solid #000; padding:6px 4px; text-align:center;">TINDAKAN PEMERIKSAAN</th>
            <th style="border:1px solid #000; padding:6px 4px; text-align:center;">TINDAKAN TTD & NAMA JELAS<br /> <br />DOKTER PETUGAS</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0;$i<5;$i++){
            ?>
            <tr>
                <td style='border:1px solid #000; padding:6px 4px; text-align:center'>&nbsp;</td>
                <td style='border:1px solid #000; padding:6px 4px;'>&nbsp;</td>
                <td style='border:1px solid #000; padding:6px 4px;'>&nbsp;</td>
                <td style='border:1px solid #000; padding:6px 4px;'>&nbsp;</td>
                <td style='border:1px solid #000; padding:6px 4px;'>&nbsp;</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <br><br>
    <table style="width:100%;border-collapse:collapse;" cellpadding="8">
        <thead>
        <tr>
            <th style="border:1px solid #000; padding:8px 4px; text-align:center; width:70%">DIAGNOSA</th>
            <th style="border:1px solid #000; padding:8px 4px; text-align:center;">KODE ICD-X</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>DIAGNOSA UTAMA &nbsp; &nbsp; &nbsp; : </td>
            <td style='border:1px solid #000; padding:8px 4px;'></td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>DIAGNOSA TAMBAHAN : </td>
            <td style='border:1px solid #000; padding:8px 4px;'></td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
            <td style='border:1px solid #000; padding:5px 4px;'>&nbsp;</td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px; font-weight:bold; text-align:center;'>TINDAKAN / PROSEDUR</td>
            <td style='border:1px solid #000; padding:8px 4px; font-weight:bold; text-align:center;'>KODE ICD-9 CM</td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>TINDAKAN UTAMA &nbsp; &nbsp; &nbsp; : </td>
            <td style='border:1px solid #000; padding:8px 4px;'></td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>TINDAKAN TAMBAHAN : </td>
            <td style='border:1px solid #000; padding:8px 4px;'></td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
        </tr>
        <tr>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
            <td style='border:1px solid #000; padding:8px 4px;'>&nbsp;</td>
        </tr>
        </tbody>
    </table>
</div>
<div class="box-footer">
    <div class="alignleft">
        &nbsp;
    </div>
    <div class="alignright">
        <center>
            <br>
            <br><b>Dokter Penanggung Jawab</b>
            <br><br><br><br><br>
            <span class="footer-ttd">


						<!--<b><u><?php echo $_POST['nama_dokter'];?></u></b><br>-->
						_____________________________________
						</span>
        </center>
    </div>
</div>
</body>
</html>