<html>
    <head>
        <style type='text/css'>
            @media print {
                @page {
                    size:6.5cm 4.5cm;
                    margin:0;
                    padding:0;
                }
            }
            body{
                margin:auto 0px;
                color: #000;
                font-family: Times;
                font-size: 0.5rem;
                text-align:center;
                padding:5px;
            }

            hr{
                border:1px dashed #F2F2F2;
            }
        </style>
        <script type="text/javascript">
            function myFunction() {
                window.print();
                setTimeout(window.close, 0);
            }
        </script>
    </head>
    <body>
    <table style='width:100%;border-collapse:collapse; background:#fff; font-size:0.2rem; text-align: center;'>
        <tr>
            <td  rowspan="5" colspan="1"><img src="../images/logo.png" width="30px" height="30px" class="logo"></td>
            <td>INSTALASI FARMASI</td>
        </tr>
        <tr><td>RUMAH SAKIT UMUM DAERAH KABUPATEN BINTAN</td></tr>
        <tr><td><?php echo $_POST['alamat']; ?>></td></tr>
    </table>

    <hr />
    <table style='width:100%;border-collapse:collapse; background:#fff; font-size:0.3rem; text-align: left;'>
        <tr>
            <td width="20%">Nama</td>
            <td> : </td>
            <td width="50%">&nbsp; &nbsp;<?= $_POST['pasien_nama']; ?></td>
            <td width="12%">No. Resep </td>
            <td> : </td>
            <td width="20%"><?= $_POST['no_order']; ?></td>
        </tr>
        <tr>
            <td>No.RM</td>
            <td> : </td>
            <td>&nbsp; &nbsp;<?= $_POST['no_rm']; ?></td>
            <td >Tanggal</td>
            <td> : </td>
            <td><?php echo date('d F Y'); ?></td>
        </tr>
        <tr>
            <td>Tgl Lahir / Umur</td>
            <td> : </td>
            <td>&nbsp; &nbsp;<?= $_POST['tanggal_lahir'] . " / " . $_POST['usia'] ." tahun"; ?></td>
        </tr>
    </table>

    <hr />

    <table cellpadding="3" style='width:100%;border-collapse:collapse; background:#fff; font-size:0.3rem; text-align: center;'>
        <tr>
            <td width="20%"><span style="font-size: 0.6rem;"><?= $_POST['nama_signa']; ?></span></td>
        </tr>

        <tr align="center"><td><span style="font-size: 0.6rem;"><b><?= strtoupper($_POST['nama_aturanpakai']); ?></b></span></td></tr>
    </table>

    <hr />

    <table style='width:80%;border-collapse:collapse; background:#fff; font-size:0.3rem; text-align: left;'>
        <tr>
            <td width="30%">Nama / Keuangan</td>
            <td> : </td>
            <td width="50%">&nbsp; &nbsp; </td>
        </tr>
        <tr>
            <td>Tanggal Kadaluarsa</td>
            <td> : </td>
            <td>&nbsp; &nbsp; <?= $_POST['tgl_expired']; ?></td>
        </tr>
    </table>

    <hr />

    <span style="text-align: center;"><b>Semoga Lekas Sembuh</b></span>
    </body>
</html>