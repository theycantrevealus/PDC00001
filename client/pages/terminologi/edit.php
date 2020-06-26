<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/terminologi">Terminologi Manager</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit Terminologi</li>
				</ol>
			</nav>
			<h1 class="m-0">Edit Terminologi</h1>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-form card-group-row__card">
				<div class="row no-gutters">
					<div class="col-lg-4 card-body">
						<p><strong class="headings-color">Pengubahan Terminologi</strong></p>
						<p class="text-muted">
							Petunjuk pengisian data terminologi item
						</p>
					</div>
					<div class="col-lg-8 card-form__body card-body">
						<form>
							<div class="form-group">
								<label for="txt_nama_terminologi">Nama:</label>
								<input type="text" required="required" class="form-control" id="txt_nama_terminologi" placeholder="Nama Terminologi .." />
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
							<a href="<?php echo __HOSTNAME__; ?>/terminologi/child/<?php echo __PAGES__[2]; ?>" class="btn btn-danger">Kembali</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>