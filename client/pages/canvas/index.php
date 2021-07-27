<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ATS Lokalis Tester</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card-header bg-white">
            </div>
            <div class="card card-group-row__card card-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="<?php echo __HOSTNAME__; ?>/template/assets/images/form/lokalis.png" width="550" height="500" />
                        <canvas style="position: absolute; border: solid 1px red; left: 0;" id="myCanvas" width="550" height="500"></canvas>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered largeDataType" id="lokalis_value">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Keterangan</th>
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
</div>