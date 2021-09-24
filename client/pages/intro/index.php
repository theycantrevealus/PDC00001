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
        <div class="card-body">
            <div class="row card-group-row">
                <div class="col-lg-12 col-md-12">
                    <div class="z-0">
                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="tab-asesmen-dokter">
                            <li class="nav-item">
                                <a href="#tab-1" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-1" >
                                    <span class="nav-link__count">
                                        <i class="fa fa-address-book"></i>
                                    </span>
                                    How to Use
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-2" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-2" >
                                    <span class="nav-link__count">
                                        <i class="fa fa-book"></i>
                                    </span>
                                    Documentation
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card card-body tab-content">
                        <div class="tab-pane show fade" id="tab-1">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="txt_module">Module</label>
                                                <select id="txt_module" class="form-control"></select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="txt_tutor_group">Tutor</label>
                                                <select id="txt_tutor_group" class="form-control"></select>
                                            </div>
                                            <div class="col-lg-5">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <button class="btn btn-info pull-right" id="btn-tambah-tutorial">
                                        <span>
                                            <i class="fa fa-plus-circle"></i> Tambah Step
                                        </span>
                                    </button>
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
                        <div class="tab-pane active" id="tab-2">
                            <div class="row">
                                <div class="col-lg-2" style="overflow-x: scroll">
                                    <h6>Documentation<hr /><small id="btnAddRootFolder" style="cursor: pointer; cursor: hand"><i class="fa fa-plus-circle"></i> Add Parent Folder</small></h6>
                                    <hr />
                                    <div id="documentation-tree"></div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-10">
                                                    <label for="txt_nama_file">File Title</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="txt_nama_file" placeholder="Documentation Title" />
                                                </div>
                                                <div class="col-lg-2">
                                                    <br />
                                                    <button class="btn btn-info" id="btnProsesFile">
                                                        <span>
                                                            <i class="fa fa-save"></i> Save
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="col-lg-12">
                                                    <br />
                                                    <div class="editor">

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
            </div>
        </div>
    </div>
</div>

<div class="panel-simulator">
    <ul class='custom-menu'>
        <li data-action="add_folder">
            <div>
                <b>
                    <i class="fa fa-folder"></i>
                </b>
                <span>Add Folder Child</span>
            </div>
        </li>
        <li data-action="add_file">
            <div>
                <b>
                    <i class="fa fa-file"></i>
                </b>
                <span>Add File</span>
            </div>
        </li>
        <li data-action="edit_pos">
            <div>
                <b>
                    <i class="fa fa-edit"></i>
                </b>
                <span>Edit</span>
            </div>
        </li>
        <li data-action="delete_pos">
            <div>
                <i class="fa fa-trash"></i>
                <span>Delete</span>
            </div>
        </li>
    </ul>
</div>
