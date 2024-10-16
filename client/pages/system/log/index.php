<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">System Log</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header card-header-large bg-white">
            <div class="card card-form d-flex flex-column flex-sm-row">
                <div class="card-form__body card-body-form-group flex bg-white">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="filter_name">Search</label>
                                <input id="filter_name" type="text" class="form-control" placeholder="Enter keyword">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="actionType">Operation</label><br>
                                <select id="actionType" class="custom-select">
                                    <option value="all">Any</option>
                                    <option value="I">Insert</option>
                                    <option value="U">Update</option>
                                    <option value="D">Delete</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="issuer">Issuer</label><br>
                                <select id="issuer" class="custom-select">
                                    <option value="all">Any</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="filter_date">Daterange</label>
                                <input id="filter_date" type="hidden" class="form-control flatpickr-input" placeholder="Select date ..." value="13/03/2018 to 20/03/2018" data-toggle="flatpickr" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header card-header-large bg-white">
            <div class="card card-form d-flex flex-column flex-sm-row">
                <div class="card-form__body card-body-form-group flex bg-white">
                    <div class="row">
                        <div class="col-lg-9"></div>
                        <div class="col-lg-1">
                            <i id="graph-log-pie" class="fa fa-chart-pie icon-button text-purple"></i>
                        </div>
                        <div class="col-lg-1">
                            <i id="graph-log-bar" class="fa fa-chart-bar icon-button text-purple"></i>
                        </div>
                        <div class="col-lg-1">
                            <i id="graph-log-line" class="fa fa-chart-line icon-button text-purple"></i>
                        </div>
                        <div class="col-lg-12">
                            <div class="log-graph">
                                <canvas id="myChart" width="400" height="50"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped largeDataType" id="table-log">
                <thead class="thead-dark">
                <tr>
                    <th class="wrap_content">No</th>
                    <th class="wrap_content">Logged</th>
                    <th class="wrap_content">Pegawai</th>
                    <th class="wrap_content">Module</th>
                    <th style="width: 200px">Recent</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>