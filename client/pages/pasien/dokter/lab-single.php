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
                                <a href="#tab-lab-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-lab-1" >
                                    Hasil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-lab-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-lab-2" >
                                    Lampiran
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card card-body tab-content">
                        <div class="tab-pane show fade active" id="tab-lab-1">
                            <?php
                            foreach($_POST['detail'] as $key => $value) {
                                ?>
                                <div class="card-body">
                                    <table class="table form-mode">
                                        <tr>
                                            <td style="width: 20%" class="wrap_content">Kode</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_kode"><?php echo $value['tindakan']['kode']; ?> - <?php echo $value['tindakan']['nama']; ?></b>
                                            </td>

                                            <td class="wrap_content">Dokter Penanggung Jawab</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_dokter"><?php echo $_POST['dr_penanggung_jawab']['nama']; ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_tanggal"><?php echo $_POST['parse_tanggal']; ?></b>
                                            </td>

                                            <td>Petugas</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_petugas"><?php echo $_POST['petugas_parse']; ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jam Sampling</td>
                                            <td>:</td>
                                            <td>
                                                <b class="lab_sampling"><?php echo $_POST['sampling']; ?></b>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Parameter</th>
                                            <th width="10%">Nilai Rujukan</th>
                                            <th width="10%">Satuan</th>
                                            <th width="10%">Hasil</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $auto = 1;
                                        foreach ($value['hasil'] as $HKey => $HValue) {
                                            ?>
                                            <tr>
                                                <td><?php echo $auto; ?></td>
                                                <td><?php echo $HValue['lab_nilai']['keterangan']; ?></td>
                                                <td><?php echo $HValue['lab_nilai']['nilai_min']; ?> - <?php echo $HValue['lab_nilai']['nilai_maks']; ?></td>
                                                <td><?php echo $HValue['lab_nilai']['satuan']; ?></td>
                                                <td><?php echo $HValue['nilai']; ?></td>
                                            </tr>
                                            <?php
                                            $auto++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        Kesan :<br />
                                        <p><?php echo (isset($_POST['kesan'])) ? $_POST['kesan'] : '-' ?></p>
                                    </div>
                                    <div class="col-md-12">
                                        Anjuran :<br />
                                        <p><?php echo (isset($_POST['anjuran'])) ? $_POST['anjuran'] : '-' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show fade" id="tab-lab-2">
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
                                            <a class="lampiran_view_trigger" href="#" target="<?php echo $_POST['__HOST__'] . '/document/laboratorium/' . $_POST['uid'] . '/' . $Docvalue['lampiran']; ?>">#Lampiran <?php echo $docAuto; ?> [<?php echo $Docvalue['lampiran']; ?>]</a>
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