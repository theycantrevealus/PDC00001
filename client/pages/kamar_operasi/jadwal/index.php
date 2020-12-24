<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Kamar Operasi</li>
				</ol>
			</nav>
			<h2 class="m-0">Jadwal Operasi</h2>
        </div>
        <a class="btn btn-info ml-3" href="<?php echo __HOSTNAME__; ?>/kamar_operasi/jadwal/tambah">Tambah</a>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="table-responsive border-bottom" data-toggle="lists">
					<table class="table table-bordered table-striped" id="table_jadwal_operasi">
						<thead class="thead-dark">
							<tr>
                                <th width="2%">No</th>
                                <th width="10%">Pasien</th>
                                <th width="10%">Jenis Operasi</th>
                                <th width="10%">Operasi</th>
								<th width="10%">Dokter</th>
								<th width="10%">Ruangan</th>
                                <th width="10%">Tanggal</th>
								<th width="10%">Jam Mulai</th>
								<th width="10%">Jam Selesai</th>
								<th width="10%">Status</th>
                                <th width="20%">Aksi</th>
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