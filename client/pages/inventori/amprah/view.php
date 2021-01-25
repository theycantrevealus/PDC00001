<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/inventori/amprah">Amprah</a></li>
					<li class="breadcrumb-item active" aria-current="page" id="mode_item">View</li>
				</ol>
			</nav>
			<h4>Amprah - Detail</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row">
        <div class="col-lg col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">Detail Amprah</h5>
                </div>
                <div class="card-body">
                    <div class="row" id="hasil-amprah">
                        <div class="col-6">
                            <table class="table form-mode">
                                <tr>
                                    <td class="wrap_content">Kode Amprah</td>
                                    <td class="wrap_content">:</td>
                                    <td>
                                        <b id="verif_kode"></b>
                                    </td>

                                    <td class="wrap_content">Unit Pengamprah</td>
                                    <td class="wrap_content">:</td>
                                    <td id="verif_unit"></td>
                                </tr>
                                <tr>
                                    <td class="wrap_content">Nama Pengamprah</td>
                                    <td class="wrap_content">:</td>
                                    <td id="verif_nama"></td>

                                    <td class="wrap_content">Tanggal Amprah</td>
                                    <td class="wrap_content">:</td>
                                    <td id="verif_tanggal"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12">
                            <table id="table-verifikasi" class="table table-bordered">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="text-align: left">Item</th>
                                    <th class="wrap_content">Satuan</th>
                                    <th class="wrap_content">Permintaan</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <b>Keterangan</b>
                            <br />
                            <p id="verif_keterangan"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a href="<?php echo __HOSTNAME__; ?>/inventori/amprah" class="btn btn-danger">
                                <i class="fa fa-ban"></i> Kembali
                            </a>
                            <button class="btn btn-info" id="btnCetak">
                                <i class="fa fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>