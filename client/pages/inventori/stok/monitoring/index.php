<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Inventori</li>
                    <li class="breadcrumb-item active" aria-current="page">Monitoring</li>
                </ol>
            </nav>
            <h4 class="m-0">Assesmen Awal</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                    <li class="nav-item">
                        <a href="#tab-2" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-2">
                            <span class="nav-link__count">
                                <i class="fa fa-eye"></i>
                            </span>
                            Monitoring
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card card-body tab-content">
                <div class="tab-pane show fade active" id="tab-2">
                    <div class="card">
                        <div class="card-header">
                            <select class="form-control pull-right" id="txt_gudang"></select>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-striped largeDataType" id="monitoring-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Barang</th>
                                                <th class="wrap_content">Min</th>
                                                <th class="wrap_content">Maks</th>
                                                <th class="wrap_content">Aktual</th>
                                                <th class="wrap_content">Trend</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>