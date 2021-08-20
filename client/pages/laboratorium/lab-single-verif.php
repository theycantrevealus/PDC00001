<div class="col-12" id="verifikasi_lab_container_<?php echo  $_POST['uid']; ?>">
    <div class="card">
        <div class="card-header card-header-large bg-white">
            <div class="row">
                <div class="col-3">
                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> <span class="lab_nomor_order"><?php echo $_POST['no_order']; ?></span></h5>
                </div>
                <div class="col-9">
                    <table class="table form-mode">
                        <tr>
                            <td class="wrap_content">Dokter Penanggung Jawab</td>
                            <td class="wrap_content">:</td>
                            <td style="width: 40%;">
                                <select class="form-control" id="target_dpjp_lab_<?php echo $_POST['uid']; ?>"></select>
                            </td>
                        </tr>
                        <tr>
                            <td class="wrap_content">Tanggal Order</td>
                            <td class="wrap_content">:</td>
                            <td>
                                <b class="lab_tanggal"><?php echo $_POST['parse_tanggal']; ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td class="wrap_content">Total Biaya</td>
                            <td class="wrap_content">:</td>
                            <td>
                                <b class="text-danger" id="total_biaya">Rp.0.00</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body" id="labor_container_<?php echo  $_POST['uid']; ?>">
        <?php
            foreach($_POST['detail'] as $key => $value) {
                if($value['mitra'] == '') {
                    ?>
                <div asesmen="<?php echo $_POST['asesmen']; ?>" tindakan="<?php echo $value['tindakan']['uid']; ?>" target="<?php echo  $_POST['uid']; ?>" class="order_item_lab card group_<?php echo  $_POST['uid']; ?>" id="verifikasi_lab_container_<?php echo $_POST['uid']; ?>_<?php echo  $value['tindakan']['uid']; ?>">
                    <div class="card-header card-header-large bg-white">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-header__title flex m-0">
                                    <i class="fa fa-hashtag"></i> <span class="lab_nomor_order"><?php echo strtoupper($value['tindakan']['kode']); ?> - <?php echo $value['tindakan']['nama']; ?></span>
                                </h5>
                            </div>
                            <div class="col-6">
                                <b class="text-info harga_iden" id="harga_<?php echo $_POST['uid']; ?>_<?php echo $value['tindakan']['uid']; ?>"></b>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table class="table form-mode">
                            <tr>
                                <td class="wrap_content">Kode</td>
                                <td class="wrap_content">:</td>
                                <td style="width: 20%;">
                                    <b class="lab_kode"><?php echo $value['tindakan']['kode']; ?> - <?php echo $value['tindakan']['nama']; ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td class="wrap_content">Penyedia</td>
                                <td class="wrap_content">:</td>
                                <td id="container_mitra_<?php echo $_POST['uid']; ?>_<?php echo $value['tindakan']['uid']; ?>">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <select asesmen="<?php echo $_POST['asesmen']; ?>" target="<?php echo $_POST['uid']; ?>" id="penyedia_order_<?php echo $value['tindakan']['uid']; ?>" class="form-control penyedia_order_lab"></select>
                                        </div>
                                    </div>
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php //echo json_encode($_POST); ?>
                            </div>
                            <div class="col-md-3">
                                <!--button asesmen="<?php echo $_POST['asesmen']; ?>" class="btn btn-success btn_verifikasi_item_lab" id="verifikasi_lab_<?php echo $value['tindakan']['uid']; ?>" target="<?php echo $_POST['uid']; ?>" tindakan="<?php echo $value['tindakan']['uid']; ?>">
                                    <i class="fa fa-check"></i> Verifikasi
                                </button-->
                            </div>
                        </div>
                    </div>
                </div>
                    <?php
                }
            }
        ?>
        </div>
    </div>
</div>