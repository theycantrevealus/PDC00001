<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Dokumen Pasien</h5>
            </div>
            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#dokumen_request" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Permintaan</a>
                <a href="#dokumen_history" data-toggle="tab" role="tab" aria-selected="false">History</a>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active show fade" id="dokumen_request">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="table-dokumen">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Dokumen</th>
                                        <th class="wrap_content">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="dokumen_history">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <table class="table table-bordered" id="table-dokumen-history">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Dokumen</th>
                                        <th>Tanggal</th>
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
</div>