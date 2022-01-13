<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_inap">Rawat IGD</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Rawat IGD</li>
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
                <p class="text-muted">Mohon pastikan informasi pasien cocok</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Nama Pasien</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="nama" disabled required value="MARCO DE GAMMA">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Jenis Kelamin</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="nama_jenkel" disabled required value="Laki-laki">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Nomor Rekam Medis</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="no_rm" disabled required value="121-545-441">
                    </div>
                     <div class="col-12 col-md-6 mb-3">
                        <label for="">Tanggal Lahir</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="tanggal_lahir" disabled required value="29 Juni 1995">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Detail Rawat IGD</strong></p>
                <p class="text-muted">Mohon masukkan data dengan benar<br>* Wajib diisi</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label>Dokter Utama <span class="red">*</span></label>
                        <select id="dokter_utama" class="form-control select2 inputan">
                            <option value="" disabled selected>Pilih</option>
                        </select>
                    </div>
                    <!-- <div class="col-12 col-md-6 mb-3">
                        <label>Cara Masuk <span class="red">*</span></label>
                        <select id="cara_masuk" class="form-control select2 inputan">
                            <option value="" disabled selected>Pilih</option>
                            <option value="1">Normal</option>
                            <option value="2">Melahirkan</option>
                            <option value="3">Operasi</option>
                            <option value="4">UGD</option>
                        </select>
                    </div> -->
                    <div class="col-12 col-md-6 mb-3">
                        <label>Asal Rawat IGD <span class="red">*</span></label>
                        <select id="asal_rawat_inap" disabled class="form-control select2 inputan">
                            <option value="" disabled>Pilih</option>
                            <option value="1" selected>Rawat Jalan</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Ruangan <span class="red">*</span></label>
                        <select id="ruangan" class="form-control select2 inputan">
                            <option value="" disabled selected>Pilih</option>
                        </select>
                    </div>
                     <div class="col-lg-8 card-form__body card-body">
                        <div class="form-row">
                            <button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
                            &nbsp;
                            <a href="<?php echo __HOSTNAME__; ?>/igdv2" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>