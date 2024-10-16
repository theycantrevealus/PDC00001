<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Absen Harian</li>
                </ol>
            </nav>
            <h4 class="m-0">Absen Harian</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
            <div class="card-group">
            	<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-2">
							<i class="material-icons icon-muted icon-30pt">account_circle</i>
						</div>
						<div class="col-md-10">
							<b><?php echo $_SESSION['nama']; ?></b>
							<br />
							<span>-</span>
							<br />
							<span>-</span>
						</div>
					</div>
				</div>
				<div class="card card-body">
				
                        <div class="col-md-12">
                            <h6 class="mb-3"><span style="color:#308AF3" class="time"></span><br/><br/> <span class="tanggalSekarang"></span></h6>
                            <button class="btn btn-success" id="btnAbsenMasuk">
								ABSEN MASUK
							</button>
                            <button class="btn btn-danger" id="btnAbsenKeluar">
								ABSEN KELUAR
							</button>
                        </div>
				
				</div>
			</div>
        	<div class="card">
                <div class="card-header card-header-large bg-white row">
                    <div class="col-md-7">
                        <h5 class="card-header__title flex m-0">Riwayat Absen</h5>
                    </div>
                    
                    <div class="col-md-5">
                                <table class="form-mode table">
                                    <tr>
                                        <td>Tanggal</td>
                                        <td class="wrap_content">:</td>
                                        <td>
                                            <input id="range_absen" type="text" class="form-control" placeholder="Flatpickr range example" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $day->format('Y-m-1'); ?> to <?php echo $day->format('Y-m-d'); ?>" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive border-bottom">
                        <table class="table table-bordered table-striped largeDataType" id="table-absen-harian">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width:10px">#</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Keterangan</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>