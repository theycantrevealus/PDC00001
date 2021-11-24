<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/resepsionis">Antrian</a></li>
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
                <p class="text-muted">Mohon pastikan informasi pasien cocok</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Nama Pasien</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="nama" disabled required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Jenis Kelamin</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="nama_jenkel" disabled required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Nomor Rekam Medis</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="no_rm" disabled required>
                    </div>
                     <div class="col-12 col-md-6 mb-3">
                        <label for="">Tanggal Lahir</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="tanggal_lahir" disabled required>
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
                        <label>Pembayaran <span class="red">*</span></label>
                        <select id="penjamin" class="form-control select2 inputan" required>
                            <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Prioritas <span class="red">*</span></label>
                        <select id="prioritas" class="form-control select2 inputan" required>
                            <option value="" disabled selected>Pilih Prioritas</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Poliklinik <span class="red">*</span></label>
                        <select id="departemen" class="form-control select2 inputan" required>
                            <option value="" disabled selected>Pilih Poliklinik</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label>Dokter <span class="red">*</span></label>
                        <select id="dokter" class="form-control select2 inputan" required>
                            <option value="" disabled selected>Pilih Dokter</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3 poli_lain">
                        <label>Penanggung Jawab Pasien</label>
                        <input type="" name="pj_pasien" id="pj_pasien" maxlength="100" class="form-control inputan" required value="">
                    </div>
                    <div class="col-12 col-md-6 mb-3 poli_lain">
                        <label>Informasi didapat dari</label>
                        <input type="" name="info_didapat_dari" id="info_didapat_dari" maxlength="100" class="form-control inputan" required value="">
                    </div>

                    <div class="col-12 col-md-6 mb-3 poli_igd">
                        <label>Cara Datang</label>
                        <select id="cara_datang" class="form-control select2 inputan" required>
                            <option value="" disabled selected>Pilih Cara Datang</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3 poli_igd">
                        <label>Ranjang <span class="red">*</span></label>
                        <select id="bangsal" class="form-control select2 inputan" required>
                            <option value="" disabled selected>Pilih Ranjang</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-12 mb-3 poli_igd">
                        <label>Keterangan</label>
                        <textarea name="keterangan_cara_datang" id="keterangan_cara_datang" maxlength="100" class="form-control inputan" value="" style="min-height: 150px"></textarea>
                    </div>

                    <div class="col-lg-12 card-form__body card-body">
                        <div class="form-row">
                            <button type="submit" class="btn btn-success" id="btnSubmit">Simpan Data</button>
                            &nbsp;
                            <a href="<?php echo __HOSTNAME__; ?>/rawat_jalan/resepsionis" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>