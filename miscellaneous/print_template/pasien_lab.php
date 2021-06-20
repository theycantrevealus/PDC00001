<html>
<head>
    <style type='text/css'>
        @media print {
            @page {
                size:8.2cm 3.4cm;
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
        }
        .border{
            border:1px solid #F2F2F2;
            padding:5px;
        }
    </style>
</head>
<body>
<?php
for($i=0;$i<4;$i++){
    ?>
    <div class="border">
        <table style='width:100%;border-collapse:collapse; background:#fff; font-size:0.7rem;'>
            <tr>
                <td><?php echo $_POST['nama_panggilan'].". ".$_POST['nama'];?></td>
                <td><?php echo DateToIndo2($_POST['tanggal_lahir']);?></td>
            </tr>
            <tr>
                <td><?php echo $_POST['tanggal'];?></td>
                <td><?php echo $_POST['usia'];?></td>
            </tr>
        </table>
        <br>

    </div>
    <?php
}
?>
</body>
</html>