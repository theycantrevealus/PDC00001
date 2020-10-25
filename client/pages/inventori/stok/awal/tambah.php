<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Inventori</li>
                    <li class="breadcrumb-item" aria-current="page">Stok</li>
                    <li class="breadcrumb-item" aria-current="page">Stok Awal</li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Stok Awal</li>
                </ol>
            </nav>
            <h4 class="m-0">Stok Awal</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
   <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Informasi Gudang</strong></p>
                <p style="font-size: 0.9rem;" class="text-muted"></p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="">Gudang</label>
                        <select class="form-control" id="gudang"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <div class="form">
                    <p><strong class="headings-color">Obat</strong><p>
                    <p style="font-size: 0.9rem;" class="text-muted">Pilih obat yang akan ditambahkan Stok Awal</p>
                    <select class="form-control" id="obat">
                        <option value="">Pilih Obat</option>
                    </select>
                
                </div>
            </div>
            <div class="col-lg-8 card-form__body card-body">
               <div class="table-responsive border-bottom">
                    <table class="table table-bordered table-striped" id="table-antrian-rawat-jalan" style="font-size: 0.9rem;">
                        <thead>
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Kode</th>
                                <th>Item</th>
                                <th>Batch</th>
                                <th>Tanggal Expired</th>
                                <th>Stok Awal</th>
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