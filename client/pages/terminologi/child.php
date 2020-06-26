<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/terminologi">Terminologi Manager</a></li>
					<li class="breadcrumb-item active" aria-current="page">Terminologi Item Manager</li>
				</ol>
			</nav>
			<h1 class="m-0">Terminologi Item</h1>
		</div>
		<a href="<?php echo __HOSTNAME__; ?>/terminologi/tambah_child/<?php echo __PAGES__[2]; ?>" class="btn btn-info ml-3">Tambah Terminologi Item</a>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
				<div class="table-responsive border-bottom" data-toggle="lists">
					<table class="table mb-0 thead-border-top-0" id="table-terminologiItem">
						<thead>
							<tr>
								<th style="width: 20px;">No</th>
								<th>Item</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>