<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/inventori">Master Inventori</a></li>
					<li class="breadcrumb-item active" aria-current="page">Jabatan</li>
				</ol>
			</nav>
			<h4 class="m-0">Jabatan</h4>
		</div>
		<button class="btn btn-sm btn-info" id="tambah-jabatan">
			<i class="fa fa-plus"></i> Tambah
		</button>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12">
			<div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="table-jabatan">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th style="width: 20%">Nama</th>
                            <th>Unit</th>
                            <th class="wrap_content">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>