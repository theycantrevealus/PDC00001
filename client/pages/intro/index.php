<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Introduction</li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Introduction Management</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header bg-white">
            <div class="row">
                <div class="col-lg-4">
                    Module
                    <select id="txt_module" class="form-control"></select>
                </div>
                <div class="col-lg-4">
                    Tutor
                    <select id="txt_tutor_group" class="form-control"></select>
                </div>
                <div class="col-lg-4">
                    <br />
                    <div class="col-lg-12">
                        <button class="btn btn-info" id="btn-tambah-group">
                            <span>
                                <i class="fa fa-plus-circle"></i> Tambah Group
                            </span>
                        </button>
                        <button class="btn btn-warning" id="btn-edit-group">
                            <span>
                                <i class="fa fa-pencil-alt"></i> Edit Group
                            </span>
                        </button>
                        <button class="btn btn-danger" id="btn-hapus-group">
                            <span>
                                <i class="fa fa-trash-alt"></i> Hapus Group
                            </span>
                        </button>
                    </div>

                </div>
                <div class="col-lg-12">
                    <hr />
                    <button class="btn btn-info pull-right" id="btn-tambah-tutorial">
                        <span>
                            <i class="fa fa-plus-circle"></i> Tambah Tutor
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table-tutor">
                <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th>Nama</th>
                        <th>Remark</th>
                        <th class="wrap_content">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
