<div class="col-12">
    <div class="card">
        <div class="card-header card-header-large bg-white">
            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> <span class="lab_nomor_order"><?php echo $_POST['no_order']; ?></span></h5>
        </div>
        <div class="card-body">
            <div class="row card-group-row">
                <div class="col-lg-12 col-md-12">
                    <div class="z-0">
                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                            <li class="nav-item">
                                <a href="#tab-rad-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-lab-1" >
                                    Hasil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-rad-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-lab-2" >
                                    Lampiran
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card card-body tab-content">
                        <div class="tab-pane show fade active" id="tab-rad-1">
                            <?php
                            foreach($_POST['detail'] as $key => $value) {
                                ?>
                                <div class="card card-body">
                                    <table class="table form-mode">
                                        <tr>
                                            <td class="wrap_content">Pemeriksaan</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_kode"><?php echo $value['tindakan']['nama']; ?></b>
                                            </td>

                                            <td class="wrap_content">Petugas</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_dokter"><?php echo $_POST['petugas']['nama']; ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_tanggal"><?php echo $_POST['created_at']; ?></b>
                                            </td>
                                            <td class="wrap_content">Dokter Penanggung Jawab</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_dokter"><?php echo $_POST['dokter_radio']['nama']; ?></b>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table form-mode">
                                        <tr>
                                            <td class="wrap_content">Keterangan</td>
                                            <td class="wrap_content">:</td>
                                            <td>
                                                <?php echo $value['keterangan']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="wrap_content">Kesimpulan</td>
                                            <td class="wrap_content">:</td>
                                            <td>
                                                <?php echo $value['kesimpulan']; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php
                            } ?>
                        </div>
                        <div class="tab-pane show fade" id="tab-rad-2">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $docAuto = 1;
                                    foreach($_POST['document'] as $Dockey => $Docvalue) {
                                        ?>
                                        <tr>
                                            <td><?php echo $docAuto; ?></td>
                                            <td>
                                                <a class="lampiran_view_trigger" href="#" target="<?php echo $_POST['__HOST__'] . '/document/radiologi/' . $_POST['uid'] . '/' . $Docvalue['lampiran']; ?>">#Lampiran <?php echo $docAuto; ?> [<?php echo $Docvalue['lampiran']; ?>]</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $docAuto++;
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>