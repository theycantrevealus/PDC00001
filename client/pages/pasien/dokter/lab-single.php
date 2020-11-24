<div class="col-12">
    <div class="card">
        <div class="card-header card-header-large bg-white">
            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> <span class="lab_nomor_order"><?php echo $_POST['no_order']; ?></span></h5>
        </div>
        <?php
            foreach($_POST['detail'] as $key => $value) {
        ?>
        <div class="card-body">
            <table class="table form-mode">
                <tr>
                    <td class="wrap_content">Kode</td>
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
    </div>
</div>