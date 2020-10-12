<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page">Rawat Jalan</li>
					<li class="breadcrumb-item active" aria-current="page">Antrian Assesmen Awal</li>
				</ol>
			</nav>
			<h4 class="m-0">Assesmen Awal</h4>
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
							<span>Laki-laki</span>
							<br />
							<span>10 Januari 1989</span>
						</div>
					</div>
				</div>
				<div class="card card-body">
					<div class="d-flex flex-row">
						<div class="col-md-12">
							<b>Antrian</b>
							<h5 class="text-info handy" id="current-poli">
								<small><i class="fa fa-sync text-success" id="change-poli"></i></small>
							</h5>
							<b id="jlh-antrian">0</b> antrian
						</div>
					</div>
				</div>
			</div>
        	<div class="card card-body">
				<div class="table-responsive border-bottom">
					<table class="table table-bordered " id="table-antrian-perawat" style="font-size: 0.9rem;">
						<thead class="thead-dark">
							<tr>
								<th width="2%">No</th>
								<th>Waktu Masuk</th>
								<th>No. RM</th>
								<th>Pasien</th>
								<th>Poliklinik</th>
								<th>Dokter</th>
								<th>Jenis Bayar</th>
								 <th>Prioritas</th>
								<th>Aksi</th>
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
