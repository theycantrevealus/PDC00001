<?php require 'form-header.php'; ?>
<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Konversi Satuan</h5>
			</div>
			<div class="card-body tab-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Satuan Besar:</label>
                            <select class="form-control" id="txt_satuan_besar"></select>
                        </div>
                        <div class="form-group">
                            <label for="txt_ratio_besar">Ratio Besar:</label>
                            <input type="number" class="form-control uppercase" id="txt_ratio_besar" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Satuan Tengah:</label>
                            <select class="form-control" id="txt_satuan_tengah"></select>
                        </div>
                        <div class="form-group">
                            <label for="txt_ratio_tengah">Ratio Tengah:</label>
                            <input type="number" class="form-control uppercase" id="txt_ratio_tengah" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Satuan Kecil:</label>
                            <select class="form-control" id="txt_satuan_kecil"></select>
                        </div>
                        <div class="form-group">
                            <label for="txt_ratio_kecil">Ratio Kecil:</label>
                            <input type="number" class="form-control uppercase" id="txt_ratio_kecil" required />
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<!-- <div class="col-md-12" style="margin-top: 50px;">
	<div class="form-group">
		<label>Varian Kemasan:</label>
		<table class="table table-bordered table-data" id="table-varian">
			<thead>
				<tr>
					<th style="width: 50px;">No</th>
					<th style="width: 50%;">Satuan</th>
					<th>Kemasan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div> -->