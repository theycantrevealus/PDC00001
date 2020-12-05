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
                    <td class="wrap_content">:</td>
                    <td style="width: 20%;">
                        <b class="lab_kode"><?php echo $value['tindakan']['kode']; ?> - <?php echo $value['tindakan']['nama']; ?></b>
                    </td>

                    <td class="wrap_content">Dokter Penanggung Jawab</td>
                    <td class="wrap_content">:</td>
                    <td>
                        <select id="target_dpjp_lab_<?php echo $_POST['uid']; ?>" class="form-control target_dpjp"></select>
                    </td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="wrap_content">:</td>
                    <td>
                        <b class="lab_tanggal"><?php echo $_POST['parse_tanggal']; ?></b>
                    </td>

                    <td>Petugas</td>
                    <td class="wrap_content">:</td>
                    <td>
                        <b class="lab_petugas"><?php echo $_POST['petugas_parse']; ?>-</b>
                    </td>
                </tr>
            </table>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th class="wrap_content">No</th>
                    <th>Parameter</th>
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
                <div class="col-md-5">
                    Penyedia :<br />
                    <select id="penyedia_order_<?php echo $value['uid']; ?>" class="form-control penyedia_order_lab"></select>
                </div>
                <div class="col-md-4 text-info" style="padding-top: 20px;">
                    <i class="fa fa-info-circle"></i> Pelaksana pemeriksaan laboratorium
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success btn_verifikasi_item_lab" id="verifikasi_lab_<?php echo $_POST['uid']; ?>">
                        <i class="fa fa-check"></i> Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>