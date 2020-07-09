<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/antrian_resepsionis">Antrian</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Antrian</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
   <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Informasi Pasien</strong></p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Nama Pasien</label>
                        <input type="text" autocomplete="off" class="form-control uppercase inputan" id="nama" disabled required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Jenis Kelamin</label>
                        <input type="text" autocomplete="off" class="form-control uppercase inputan" id="nama_jenkel" disabled required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Nomor Rekam Medis</label>
                        <input type="text" autocomplete="off" class="form-control uppercase inputan" id="no_rm" disabled required>
                    </div>
                     <div class="col-12 col-md-6 mb-3">
                        <label for="">Tanggal Lahir</label>
                        <input type="text" autocomplete="off" class="form-control uppercase inputan" id="tanggal_lahir" disabled required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Detail Kunjungan</strong></p>
                <p class="text-muted">Mohon masukkan data dengan benar<br>* Wajib diisi</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label>Penjamin <span class="red">*</span></label>
                        <select id="penjamin" class="form-control">
                            <option value="">Pilih Penjamin</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Prioritas <span class="red">*</span></label>
                        <select id="prioritas" class="form-control">
                            <option value="">Pilih Prioritas</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Pemeriksaan <span class="red">*</span></label>
                        <select id="prioritas" class="form-control">
                            <option value="">Pilih Pemeriksaan</option>
                        </select>
                    </div>
                     <div class="col-12 col-md-6 mb-3">
                        <label>Dokter <span class="red">*</span></label>
                        <select id="dokter" class="form-control">
                            <option value="">Pilih Dokter</option>
                        </select>
                    </div>
                     <div class="col-lg-8 card-form__body card-body">
                        <div class="form-row">
                            <button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
                            &nbsp;
                            <a href="<?php echo __HOSTNAME__; ?>/antrian_resepsionis/" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>