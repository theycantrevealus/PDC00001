<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/supplier">Master Supplier</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah</li>
				</ol>
			</nav>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-12 col-md-12 card-group-row__col">
			<div class="card card-body">
				<form>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_no_ktp">Nama Supplier:</label>
								<input type="text" class="form-control uppercase" id="txt_nama" placeholder="Nama Supplier" required>
							</div>
						</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="txt_no_ktp">Jenis Supplier:</label>
                                <select class="form-control" id="txt_jenis">
                                    <option value="A">Default</option>
                                    <option value="B">Rumah Sakit</option>
                                </select>
                            </div>
                        </div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label for="txt_no_ktp">Kontak:</label>
								<input type="text" class="form-control" id="txt_kontak" placeholder="+62xxxxxxxx" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label for="txt_no_ktp">Email:</label>
								<input type="email" class="form-control" id="txt_email" placeholder="example@domain.com">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="txt_no_ktp">Alamat:</label>
								<textarea class="form-control" id="txt_alamat" placeholder="Alamat Supplier"></textarea>
							</div>
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Tambah Supplier</button>
					<a href="<?php echo __HOSTNAME__; ?>/master/supplier" class="btn btn-danger">Batal</a>
				</form>
			</div>
		</div>
	</div>
</div>
