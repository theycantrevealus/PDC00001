<p class="text-dark-gray d-flex align-items-center mt-3">
    Tanggal Asesmen :
	<i class="material-icons icon-muted mr-2">event</i>
	<strong><?php echo date('d F Y', strtotime($_POST['setter']['created_at'])); ?></strong>
</p>





<div class="row projects-item mb-1">
	<div class="col-1">
		<br />
		<div class="text-dark-gray">Subjective</div>
	</div>
	<div class="col-11">
		<div class="card">
			<div class="card-header card-header-large bg-white">
				<div class="row">
					<div class="col-6">
						<div class="segmen_keluhan_utama">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Keluhan Utama</strong></a>
							</div>
                            <div class="card-body">
                                <p class="txt_keluhan_utama">
                                    <?php
                                        echo $_POST['setter']['asesmen_detail']['keluhan_utama'];
                                    ?>
                                </p>
                            </div>
						</div>
					</div>
                    <div class="col-6">
                        <div class="segmen_keluhan_tambahan">
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Keluhan Tambahan</strong></a>
                            </div>
                            <div class="card-body">
                                <p class="txt_keluhan_tambahan">
                                    <?php
                                    echo $_POST['setter']['asesmen_detail']['keluhan_tambahan'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>





<div class="row projects-item mb-1">
    <div class="col-1">
        <br />
        <div class="text-dark-gray">Objective</div>
    </div>
    <div class="col-11">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <div class="row">
                    <div class="col-12">
                        <div class="segmen_keluhan_utama">
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Pemeriksaan Fisik</strong></a>
                            </div>
                            <div class="card-body">
                                <p class="txt_keluhan_utama">
                                    <?php
                                    echo $_POST['setter']['asesmen_detail']['pemeriksaan_fisik'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row projects-item mb-1">
    <div class="col-1">
        <br />
        <div class="text-dark-gray">Asesmen</div>
    </div>
    <div class="col-11">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <div class="row">
                    <div class="col-6">
                        <div class="segmen_diagnosa_utama">
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Diagnosa Kerja</strong></a>
                            </div>
                            <div class="card-body">
                                <p class="txt_diagnosa_kerja">
                                    <?php
                                    echo $_POST['setter']['asesmen_detail']['diagnosa_kerja'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="segmen_diagnosa_banding">
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Diagnosa Banding</strong></a>
                            </div>
                            <div class="card-body">
                                <p class="txt_diagnosa_banding">
                                    <?php
                                    echo $_POST['setter']['asesmen_detail']['diagnosa_banding'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row projects-item mb-1">
    <div class="col-1">
        <br />
        <div class="text-dark-gray">Planning</div>
    </div>
    <div class="col-11">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <div class="row">
                    <div class="col-6">
                        <div class="segmen_keluhan_utama">
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Planning</strong></a>
                            </div>
                            <div class="card-body">
                                <p class="txt_keluhan_utama">
                                    <?php
                                    echo $_POST['setter']['asesmen_detail']['pemeriksaan_fisik'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>














<div class="row projects-item mb-1">
	<div class="col-1">
		<br />
		<div class="text-dark-gray">Resep & Racikan</div>
	</div>
	<div class="col-11">
		<div class="card">
			<div class="card-header card-header-large bg-white">
				<div class="row">
					<div class="col-12">
						<div class="segmen_resep">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Resep</strong></a>
							</div>
						</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <b>Resep Dokter</b>
                                    <table class="table table-bordered largeDataType" id="resep_dokter">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Obat</th>
                                            <th>Signa</th>
                                            <th>Jlh</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <b>Resep Apotek</b>
                                    <table class="table table-bordered largeDataType" id="resep_apotek">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Obat</th>
                                            <th>Signa</th>
                                            <th>Jlh</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
			<div class="card-header card-header-large bg-white">
				<div class="row">
					<div class="col-12">
						<div class="segmen_racikan">
							<div class="d-flex align-items-center">
								<a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Racikan</strong></a>
							</div>
						</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <b>Racikan Dokter</b>
                                    <table class="table table-bordered largeDataType" id="racikan_dokter">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Racikan</th>
                                            <th>Komposisi</th>
                                            <th>Signa</th>
                                            <th>Jlh</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <b>Racikan Apotek</b>
                                    <table class="table table-bordered largeDataType" id="racikan_apotek">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Racikan</th>
                                            <th>Komposisi</th>
                                            <th>Signa</th>
                                            <th>Jlh</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>







<div class="row projects-item mb-1">
    <div class="col-1">
        <br />
        <div class="text-dark-gray">Tindakan Penunjang</div>
    </div>
    <div class="col-11">
        <div class="card">
            <div class="card-header card-header-large bg-white">
                <div class="row">
                    <div class="col-12">
                        <div class="segmen_tindakan_penunjang">
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Tindakan Penunjang</strong></a>
                            </div>
                        </div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>