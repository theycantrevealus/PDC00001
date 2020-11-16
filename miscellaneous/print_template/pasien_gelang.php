<html>
    <head>
        <style type='text/css'>
            @media print {
                @page {
                    size:8cm 2.2cm;
                    margin:0;
                    padding:0;
                }
            }
            body{
                margin:auto 0px;
                color: #000;
                font-family: Times;
                font-size: 0.8rem;
                text-align:center;
                padding:5px;
            }

            hr {
                border:1px dashed #F2F2F2 !important;
            }

            table tr td {
                text-align: left !important;
            }
        </style>
    </head>
    <body>
        <b><?php echo $_POST['pc_customer']; ?></b>
        <hr>
        <table style='width:100%;border-collapse:collapse; background:#fff; font-size:0.7rem !important;'>
            <tr>
                <td width="50%"><?php echo $_POST['no_rm'];?></td>
                <td><?php echo $_POST['pasien'];?></td>
            </tr>
            <tr>
                <td><?php echo date('d F Y', strtotime($_POST['tanggal_lahir']));?></td>
                <td><?php echo $_POST['usia'];?></td>
            </tr>
            <tr>
                <td><?php echo $_POST['dokter'];?></td>
                <td>TGL : <?php echo date('d F Y', strtotime($_POST['waktu_masuk'])) ;?></td>
            </tr>
        </table>
    </body>
</html>