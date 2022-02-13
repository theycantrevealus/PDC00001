<?php
    $day=new DateTime('last day of this month');
    $yesterday = new DateTime(date('Y-m-d')); // For today/now, don't pass an arg.
    $yesterday->modify("-1 day");

    $tomorrow = new DateTime(date('Y-m-d'));
    $tomorrow->modify("+1 day");
?>
<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BPJS - Referensi</li>
                </ol>
            </nav>
            <h4>BPJS - Referensi</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-referensi-bpjs">
                    <li class="nav-item">
                        <a href="#tab-poli-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								01
							</span>
                            Diagnosa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
							<span class="nav-link__count">
								02
							</span>
                            Poli
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								03
							</span>
                            Faskes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-4" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								04
							</span>
                            Dokter DPJP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-5" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								05
							</span>
                            Stastistika
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-6" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								06 <i class="fa fa-check-circle"></i>
							</span>
                            Procedure
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-7" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								07 <i class="fa fa-check-circle"></i>
							</span>
                            Kelas Rawat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-8" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								08 <i class="fa fa-check-circle"></i>
							</span>
                            Dokter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-9" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								09 <i class="fa fa-check-circle"></i>
							</span>
                            Spesialistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-10" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								10 <i class="fa fa-check-circle"></i>
							</span>
                            Ruang Rawat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-11" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								11 <i class="fa fa-check-circle"></i>
							</span>
                            Cara Keluar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-poli-12" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								12 <i class="fa fa-check-circle"></i>
							</span>
                            Pasca Pulang
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="tab-poli-1">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Diagnosa</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_diagnosa">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th style="width: 200px;">Kode</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-2">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Poli</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_poli">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-3">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Faskes</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    Jenis:
                                    <select id="bpjs_jenis_fakses" class="form-control">
                                        <option value="1">Faskes Tingkat I</option>
                                        <option value="2">Faskes Tingkat II / RS</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <br />
                                    <table class="table table-bordered largeDataType" id="bpjs_table_fakses">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 200px;">Kode</th>
                                            <th>Nama</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-4">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Dokter DPJP</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    Jenis:
                                    <select id="bpjs_jenis_fakses_dpjp" class="form-control">
                                        <option value="1">Faskes Tingkat I</option>
                                        <option value="2">Faskes Tingkat II / RS</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    Tanggal Pelayanan:
                                    <div style="border: solid 1px #DBDFE4 !important;">
                                        <input id="range_dpjp" type="text" class="form-control" placeholder="Filter Tanggal" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo $yesterday->format("Y-m-d"); ?> to <?php echo $tomorrow->format("Y-m-d"); ?>" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <br />
                                    <table class="table table-bordered largeDataType" id="bpjs_table_dpjp">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 200px;">Kode</th>
                                            <th>Nama</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-5">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Statistika</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 table-responsive">
                                    <table class="table table-bordered largeDataType" id="bpjs_table_provinsi">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th class="wrap_content">Kode</th>
                                            <th>Nama</th>
                                            <th class="wrap_content"></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-4 table-responsive">
                                    <table class="table table-bordered largeDataType" id="bpjs_table_kabupaten">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th class="wrap_content">Kode</th>
                                            <th>Nama</th>
                                            <th class="wrap_content"></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-4 table-responsive">
                                    <table class="table table-bordered largeDataType" id="bpjs_table_kecamatan">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th class="wrap_content">Kode</th>
                                            <th>Nama</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-6">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Procedure</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_procedure">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-7">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Kelas Rawat</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_kelas_rawat">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-8">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Dokter</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_dokter">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-9">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Spesialistik</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_spesialistik">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-10">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Ruang Rawat</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_ruang_rawat">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-11">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Cara Keluar</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_cara_keluar">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="tab-poli-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Pasca Pulang</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered largeDataType" id="bpjs_table_pasca_pulang">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th style="width: 200px;">Kode</th>
                                    <th>Nama</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>