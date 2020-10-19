<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/dokumen">Master Dokumen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0">Template Dokumen</h5>
                        </div>
                        <div class="card-header card-header-tabs-basic nav" role="tablist">
                            <!--<a style="width: 400px;">
                                <select class="form-control" id="filter-penjamin">
                                    <option>Pilih Penjamin</option>
                                </select>
                            </a>-->
                        </div>
                        <div class="card-body tab-content">
                            <div class="tab-pane active show fade" id="resep-biasa">
                                <div class="form-group">
                                    <label for="txt_nama">Nama Dokumen</label>
                                    <div class="search-form">
                                        <input type="text" class="form-control" id="txt_nama" placeholder="Nama Dokumen" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg">
                                        <div class="card">
                                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                                <h5 class="card-header__title flex m-0">Builder</h5>
                                            </div>
                                            <div class="card-header card-header-tabs-basic nav" role="tablist">
                                                <a href="#template-surat" class="active" data-toggle="tab" role="tab" aria-controls="template-surat" aria-selected="true">Template Surat</a>
                                                <a href="#template-input" data-toggle="tab" role="tab" aria-selected="false">Template Input</a>
                                            </div>
                                            <div class="card-body tab-content">
                                                <div class="tab-pane active show fade" id="template-surat">
                                                    <div class="document-editor">
                                                        <div class="document-editor__toolbar"></div>
                                                        <div class="document-editor__editable-container">
                                                            <div id="template-editor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane show fade" id="template-input">
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-12">
                                        <button class="btn btn-success pull-right" id="btnSubmit">
                                            <i class="fa fa-save"></i> Simpan
                                        </button>
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
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 1000px;
    }
</style>