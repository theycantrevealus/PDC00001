<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <b>Pengkajian Medis (diisi oleh dokter)</b>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <b>Status Lokalis</b>
                        <div class="lokalis" style="position: relative">
                            <div class="row">
                                <div class="col-md-6" style="padding: 20px;">
                                    <img src="<?php echo __HOSTNAME__; ?>/template/assets/images/form/lokalis.png" width="550" height="500" style="opacity: .5" />
                                    <canvas style="position: absolute; border: solid 1px #808080; left: 0; left: 20px; top 20px;" id="myCanvas" width="550" height="500"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered largeDataType" id="lokalis_value">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Keterangan</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="selection-list table-child">
                                    <li>A: Abrasi</li>
                                    <li>C: Combustio</li>
                                    <li>V: Vulnus</li>
                                    <li>D: Deformitas</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="selection-list table-child">
                                    <li>U: Uikus</li>
                                    <li>H: Hematorna</li>
                                    <li>L: Lain-lain</li>
                                    <li>N: Nyeri</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <b>Pemeriksaan Penunjang</b>
                        <div class="form-group">
                            EKG
                            <textarea type="text" class="form-control" id="igd_ekg"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>