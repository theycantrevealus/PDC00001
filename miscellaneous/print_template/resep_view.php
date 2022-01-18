<?php
    $databaseObatResep = array();
    $databaseObatRacikan = array();
    //TODO : Harga Full Charge
?>
<div id="target-cetak-resep">
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
        <tr>
            <td>Verifikator</td>
            <td class="wrap_content">:</td>
            <td><?php echo $_POST['verifikator'] ?></td>

            <td></td>
            <td class="wrap_content">:</td>
            <td></td>
        </tr>
    </table>
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
            </tr>
            </thead>
            <tbody>
            <?php
            $autonum = 1;
            foreach ($_POST['resep_dokter'] as $key => $value) {
                if(!isset($databaseObatResep[$value['uid_obat']])) {
                    $databaseObatResep[$value['uid_obat']] = array(
                        'kuantitas' => 0,
                        'harga' => 0
                    );
                }

                $databaseObat[$value['uid_obat']]['qty'] = $value['kuantitas'];
                $databaseObat[$value['uid_obat']]['harga'] = $value['harga'];
                ?>
                <tr>
                    <td>
                        <h5 class="autonum">
                            <?php echo $autonum; ?>
                        </h5>
                    </td>
                    <td>
                        <span class="wrap_content"><h5 class="text-info"><?php echo $value['obat']; ?> <b class="text-warning">[<?php echo $value['satuan_konsumsi']; ?>]</b></h5></span>
                        <?php
                        if(!empty($value['keterangan'])) {
                            ?>
                            <br />
                            <b>Keterangan:</b>
                            <p><?php echo nl2br(trim($value['keterangan'])); ?></p>
                            <?php
                        }
                        ?>
                    </td>
                    <td><?php echo $value['satuan']; ?></td>
                    <td><?php echo $value['kuantitas']; ?></td>
                    <td><?php echo $value['signa']; ?></td>
                </tr>
                <?php
                $autonum++;
            }
            ?>
            </tbody>
        </table>
        <br />
        <strong>Keterangan Resep Dokter :</strong>
        <p><?php echo (isset($_POST['keterangan_resep']) && !empty($_POST['keterangan_resep']) && $_POST['keterangan_resep'] !== '') ? $_POST['keterangan_resep'] : '-'; ?></p>
        <h5>Racikan</h5>
        <table class="table table-bordered largeDataType">
            <thead class="thead-dark">
            <tr>
                <th class="wrap_content">No</th>
                <th style="width: 20%">Nama Racikan</th>
                <th class="wrap_content">Kuantitas</th>
                <th class="wrap_content">Aturan Pakai</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $autonum = 1;
            foreach ($_POST['racikan_dokter'] as $key => $value) {
                ?>
                <tr>
                    <td>
                        <h5 class="autonum">
                            <?php echo $autonum; ?>
                        </h5>
                    </td>
                    <td>
                        <span class="wrap_content">
                            <h5 class="text-info"><?php echo $value['racikan']; ?> <b class="text-warning">[<?php echo $value['satuan_konsumsi']; ?>]</h5>
                        </span>
                        <?php
                            foreach ($value['item'] as $ItemKey => $ItemValue) {
                                if(!isset($databaseObatRacikan[$ItemValue['uid_obat']])) {
                                    $databaseObatRacikan[$ItemValue['obat']] = array(
                                        'kuantitas' => 0,
                                        'harga' => 0
                                    );
                                }
                
                                $databaseObatRacikan[$ItemValue['obat']]['kuantitas'] = $ItemValue['kuantitas'];
                                $databaseObatRacikan[$ItemValue['obat']]['harga'] = $ItemValue['harga'];

                                ?>
                                <li>
                                    <?php echo $ItemValue['detail']['nama']; ?> <b class="text-info">(<?php echo $ItemValue['kekuatan'] ?>)</b>
                                </li>
                                <?php
                            }
                            if(!empty($value['keterangan'])) {
                                ?>
                                <br />
                                <b>Keterangan:</b>
                                <p><?php echo nl2br(trim($value['keterangan'])); ?></p>
                                <br />
                                <?php
                            }
                        ?>
                    </td>
                    <td><?php echo $value['kuantitas']; ?></td>
                    <td><?php echo $value['signa']; ?></td>
                </tr>
                <?php
                $autonum++;
            }
            ?>
            </tbody>
        </table>
    </div>
    <br />
    <strong>Keterangan Racikan Dokter :</strong>
    <p><?php echo (isset($_POST['keterangan_racikan']) && !empty($_POST['keterangan_racikan']) && $_POST['keterangan_racikan'] !== '') ? $_POST['keterangan_racikan'] : '-'; ?></p>
    <br />
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
                    <td>
                        <h5 class="autonum">
                            <?php echo $autonum; ?>
                        </h5>
                    </td>
                    <td style="width: 50%">
                        <?php echo $value['obat']; ?>
                    </td>
                    <td><?php echo $value['satuan']; ?></td>
                    <td><?php echo $value['kuantitas']; ?></td>
                    <td class="text-center"><?php echo $value['signa']; ?></td>
                    <td style="border-right: solid 1px #fff !important">Rp.</td>
                    <td><?php echo $value['harga']; ?></td>
                    <td style="border-right: solid 1px #fff !important">Rp.</td>
                    <td><?php echo $value['subtotal']; ?></td>
                </tr>
                <?php
                    if(
                        !empty($value['keterangan']) && trim($value['keterangan']) !== '-' &&
                        !empty($value['alasan_ubah']) && trim($value['alasan_ubah']) !== '-'
                    ) {
                ?>
                <tr>
                    <td></td>
                    <td colspan="8" class="text-mode" style="padding-bottom: 20px;">
                        <?php
                        if(!empty($value['keterangan']) && trim($value['keterangan']) !== '-') {
                            ?>
                            <b>Keterangan:</b>
                            <p>
                                <?php echo nl2br(trim($value['keterangan'])); ?>
                            </p>
                            <br />
                            <?php
                        }
                        if(!empty($value['alasan_ubah']) && trim($value['alasan_ubah']) !== '-') {
                            ?>
                            <b style="color: #b32323">Alasan Ubah:</b>
                            <p style="color: #b32323">
                                <?php echo $value['alasan_ubah']; ?>
                            </p>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                    }
                $autonum++;
            }
            ?>
            </tbody>
        </table>
        <?php
        if(count($_POST['racikan_apotek']) > 0) {
            ?>
            <br />
            <h5>Racikan</h5>
            <table class="table table-bordered largeDataType">
                <thead class="thead-dark">
                <tr>
                    <th class="wrap_content">No</th>
                    <th style="max-width: 50%; width: 50%">Nama Racikan</th>
                    <th class="wrap_content">Kuantitas</th>
                    <th colspan="2" style="max-width: 15%; width: 10%">Harga</th>
                    <th colspan="2" style="max-width: 15%; width: 10%">Subtotal</th>
                    <th class="wrap_content">Aturan Pakai</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $autonum = 1;
                foreach ($_POST['racikan_apotek'] as $key => $value) {
                    $detailRacikanApotek = $value['detail'];
                    ?>
                    <tr>
                        <td rowspan="<?php echo count($detailRacikanApotek) + 1; ?>">
                            <h5 class="autonum">
                                <?php echo $autonum; ?>
                            </h5>
                        </td>
                        <td>
                            <h5 class="text-info"><?php echo $value['kode']; ?></h5>
                        </td>
                        <td><?php echo $value['kuantitas']; ?></td>
                        <td colspan="4"></td>
                        <td class="text-center" rowspan="<?php echo count($detailRacikanApotek) + 1; ?>"><span class="wrap_content"><?php echo $value['signa']; ?></span></td>
                    </tr>
                    <?php
                    for($a = 0; $a < count($detailRacikanApotek); $a++) {
                        ?>
                        <tr>
                            <td style="padding-left: 50px;"><strong><i class="fa fa-capsules"></i> <?php echo $detailRacikanApotek[$a]['obat']; ?></strong></td>
                            <td><?php echo $detailRacikanApotek[$a]['kuantitas']; ?></td>
                            <td style="border-right: solid 1px #fff !important">Rp.</td>
                            <td class="number_style"><?php echo $detailRacikanApotek[$a]['harga']; ?></td>
                            <td style="border-right: solid 1px #fff !important">Rp.</td>
                            <td class="number_style"><?php echo $detailRacikanApotek[$a]['subtotal']; ?></td>
                        </tr>
                        <?php
                    }
                    $autonum++;
                }
                ?>
                <?php
                    if(
                        !empty($value['keterangan']) && trim($value['keterangan']) !== '-' &&
                        !empty($value['alasan_ubah']) && trim($value['alasan_ubah']) !== '-'
                    ) {
                ?>
                <tr>
                    <td></td>
                    <td colspan="6" class="text-mode" style="padding-bottom: 20px;">
                        <?php
                            if(!empty($value['keterangan'])) {
                                ?>
                                <b>Keterangan:</b>
                                <p style="white-space: no-wrap">
                                    <?php echo nl2br(trim($value['keterangan'])); ?>
                                </p>
                                <br />
                                <?php
                            }
                            if(!empty($value['alasan_ubah']) && trim($value['alasan_ubah']) !== '-') {
                                ?>
                                <b style="color: #b32323">Alasan Ubah:</b>
                                <p style="color: #b32323">
                                    <?php echo (isset($value['alasan_ubah']) && !empty($value['alasan_ubah']) && $value['alasan_ubah'] !== '') ? $value['alasan_ubah'] : '-'; ?>
                                </p>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
            <?php
        }
        ?>
        <br />
        <strong>Alasan Ubah Keseluruhan :</strong>
        <p><?php echo (!empty($_POST['alasan_ubah']) && isset($_POST['alasan_ubah']) && $_POST['alasan_ubah'] !== '') ? $_POST['alasan_ubah'] : '-'; ?></p>
        <br /><br />
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
</div>