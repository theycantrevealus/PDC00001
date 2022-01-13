<?php
    if(file_exists('../../api/vendor/autoload.php')) {
        include '../../api/vendor/autoload.php';
        ?>
        <html>
        <head>
            <style type='text/css'>
                @media print {
                    @page {
                        size: 8cm 5cm ;
                        margin :0;
                        padding:0;
                    }
                }
                body{
                    margin:auto 0px !important;
                    color: #000 !important;
                    font-family: Arial !important;
                    font-size: 0.7rem !important;
                }
                #barcode{
                    width: 100% !important;
                    font-size: 14px !important;
                    font-weight: 100 !important;
                    text-align: center !important;
                    right: 0px;
                }

                .border{
                    border:1px solid #F2F2F2 !important;
                    padding:5px !important;
                }

                .body{
                    margin-top:1.69cm !important;
                }

                .left{
                    margin-left:1.5cm !important;
                    margin-bottom:0.2cm !important;
                }

                .left1{
                    margin-left:0.43cm !important;
                    float:left !important;
                }

                .left2{
                    margin-left:0.45cm !important;
                    float:left !important;
                }

                .left3{
                    margin-left:1.2cm !important;
                }

                .left4{
                    margin-left:5.9cm !important;
                    font-size:0.8rem !important;
                    font-weight:bold !important;
                }
                .clear{
                    clear:both !important;
                }
            </style>
        </head>
        <body>
        <div class="body">
            <div class="left" style="margin-top:1.69cm !important;"><b><?php echo $_POST['nama']; ?></b></div>
            <div class="left"><?php echo $_POST['alamat']; ?></div>
            <div class="left1">TTL</div><div class="left2">:</div><div class="left3"> <?php echo $_POST['tempat_lahir'] . ", " . $_POST['tanggal_lahir'];?></div>
            <div  class="clear"></div>
            <br><br>
            <div class="left1">
                <?php
                Sentry\init(['dsn' => 'https://9754244694444cccaf869914e1e4f5a3@o412931.ingest.sentry.io/5294475' ]);
                //$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                //echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($_POST['no_rm'], $generator::TYPE_CODE_128, 25, 350)) . '" width="100px !important">';
                ?>
            </div>
            <div class="clear"></div>

            <div class="left4"><?php echo $_POST['no_rm'];?></div>
        </div>

        </body>
        </html>
        <?php
    } else {
        echo 'File tidak ditemukan';
    }
?>