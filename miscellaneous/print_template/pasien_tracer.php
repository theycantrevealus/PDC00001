<html>
<head>
    <style type='text/css'>
        @media print {
            @page {
                size: 9.2cm 4.9cm ;
                margin :0;
                padding:0;
            }
        }
        body{
            margin:auto 0px;
            color: #000;
            font-family: Times;
            font-size: 1rem;
        }
        .head_card{
            width: 100%;
            font-size: 12px;
            float:right;
            text-align:center;
        }
        .content_card{
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        #norm{
            font-size: 20px;
            font-weight: bold;
        }

        #lahir{
            width: 100%;
            font-size: 14px;
            font-weight: 100;
            text-align: center;
        }
        #barcode{
            width: 100%;
            font-size: 14px;
            font-weight: 100;
            text-align: center;
            right: 0px;
        }

        .border{
            border:1px solid #F2F2F2;
            padding:5px;
        }
    </style>
</head>
<body>
<div>
    <div style="width: 100%;">
        <table class="head_card" width="800">
            <tr>
                <td><b>Tracer</b></td>
            </tr>
            <tr>
                <td><?php echo $_POST['panggilan'] . ". " . $_POST['nama']."\n";?></td>
            </tr>
            <tr>
                <td><?php echo $_POST['tanggal_lahir'].' ('.$_POST['usia'].')';?></td>
            </tr>
            <?php
            $dataCetakan = '';
            $dataCetakan .= $_POST['no_rm']."\n";
            $dataCetakan .= $_POST['nama_departemen']."\n";
            $dataCetakan .= "Nama Dokter\n".$_POST['nama_dokter']."\n";
            $dataCetakan .= "Nomor Antrian\n";
            $dataCetakan .= $_POST['no_antrian']."\n";
            ?>
            <tr>
                <td id="norm"><?php echo $_POST['no_rm'];?></td>
            </tr>
            <tr>
                <td><?php echo $_POST['$nama_departemen'];?></td>
            </tr>
            <tr>
                <td><?php echo $_POST['nama_dokter'];?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>