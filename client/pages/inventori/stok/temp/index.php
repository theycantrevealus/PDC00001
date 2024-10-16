<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Temporary Transact</li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Temporary Transact</h4>
        </div>
        <!-- <a href="<?php echo __HOSTNAME__; ?>/master/inventori/gudang/tambah" class="btn btn-info btn-sm ml-3">
			<i class="fa fa-plus"></i> Tambah Gudang
		</a> -->

    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12 card-group-row__col">
            <div class="card card-group-row__card card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Temporary Transact</h5>
                            </div>
                            <div class="card-body" id="intro_list_temporary_transact">
                                <div class="tab-pane active show fade">
                                    <table class="table table-bordered largeDataType" id="table-temp">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th class="text-right">Gudang Asal</th>
                                            <th>Gudang Tujuan</th>
                                            <th style="width: 50%;">Item</th>
                                            <th class="wrap_content">Jumlah</th>
                                            <th class="wrap_content">Satuan</th>
                                            <th class="wrap_content">Strategi</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card" id="intro_transact_process_panel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                Proses Transaksi
                                                <br /><b class="text-danger" id="warning_allow_transact_opname"></b>
                                                <br /><br />
                                            </div>
                                            <div class="col-lg-12" id="allow_transact_opname">
                                                <button class="btn btn-info">Proses Strategi</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card" id="intro_transact_detail_remark">
                                    <div class="card-body">
                                        <ol>
                                            <li>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <span class="badge badge-custom-caption badge-outline-info">AMPRAH</span>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <p>
                                                            Sistem mendeteksi stok pada gudang ini <b class="text-danger">habis</b> dan setelah penyesuaian, gudang akan membuat amprah untuk menutupi kebutuhan transaksi.<br />
                                                            <b class="text-info">Kemungkinan Setelah Opname:</b> Jika stok tersedia, sistem akan mengamprah jumlah kekurangan saja.
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <br />
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <span class="badge badge-custom-caption badge-outline-info">GENERAL</span>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <p>
                                                            Sistem mendeteksi stok pada gudang ini <b class="text-success">tersedia</b> dan setelah penyesuaian, sistem akan memotong stok seperti biasa.<br />
                                                            <b class="text-info">Kemungkinan Setelah Opname:</b> Jika stok tidak tersedia, sistem akan mengamprah jumlah kekurangan.
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <br />
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <span class="badge badge-custom-caption badge-outline-info">MUTASi</span>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <p>
                                                            Sistem <b class="text-danger">tidak menemukan</b> stok pada gudang utama (amprah) maupun gudang terkait. Sistem merekomendasikan gudang asal yang memiliki stok. Gudang tujuan harus melakukan mutasi untuk memenuhi kebutuhan stok gudang asal.
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <br />
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>