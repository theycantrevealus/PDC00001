<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">EMR</li>
                </ol>
            </nav>
            <h4>EMR</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-header__title flex m-0">
                EMR
                <div class="pull-right" style="width: 600px">
                    <select id="txt_pasien" class="form-control"></select>
                </div>
            </h5>
        </div>
        <div class="card-header">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table form-mode largeDataType">
                            <tr>
                                <td class="wrap_content">
                                    <img style="border-radius: 100%; width: 120px;" src="<?php echo __HOST__ ?>images/pegawai/8113652d-4cb7-e850-d487-281a1762042a.png" />
                                </td>
                                <td>
                                    <h4 class="text-primary-custom">Purwito</h4>

                                </td>
                                <td>
                                    <h3>Heart Pressure</h3>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered largeDataType table-striped" id="table-emr">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th class="wrap_content">Tanggal</th>
                            <th class="wrap_content">ICD10</th>
                            <th>Poli - Dokter</th>
                            <th class="wrap_content">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>