<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="<?php echo __HOSTNAME__; ?>/master/radiologi/layanan">Layanan Radiologi</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah</li>
				</ol>
			</nav>
			<h4 class="m-0">Tambah Layanan</h4>
		</div>
	</div>
</div>

<div class="container-fluid page__container">
<form>
	<div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Informasi Tindakan</strong></p>
                <!-- <p class="text-muted">Mohon masukkan data dengan benar <br>* Wajib diisi</p> -->
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3 form-group">
						<label for="">Nama Layanan / Tindakan:</label>
						<input type="text" name="nama" id="nama" class="form-control">
                    </div>
                     <div class="col-12 col-md-6 mb-3 form-group">
						<label for="">Jenis Layanan:</label>
						<select class="form-control" id="jenis" nama="jenis">
							<option value="">Pilih</option>
						</select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Informasi Harga per Penjamin</strong></p>
                <!-- <p class="text-muted">Mohon masukkan data dengan benar <br>* Wajib diisi</p> -->
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                   <table class="table table-bordered" id="table-penjamin">
                        <thead>
                            <tr>
                                <th style="width: 10px;">No</th>
                                <th>Penjamin</th>
                                <th width="50%">Harga</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.8rem;">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Konfirmasi</strong></p>
                <p class="text-muted">Harap konfirmasi kembali data yang telah di masukkan</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <!-- <div class="col-12 col-md-4 mb-3"> -->
						<button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
						&nbsp;
                    <!-- </div>
                    <div class="col-12 col-md-4 mb-3"> -->
                    	<a href="<?php echo __HOSTNAME__; ?>/master/radiologi/layanan" class="btn btn-danger">Batal</a>
                   <!--  </div> -->
                </div>
            </div>
        </div>
    </div>
</form>
</div>