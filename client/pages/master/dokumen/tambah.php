<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/dokumen">Master Dokumen</a>
                    </li>
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
                                        <input type="text" class="form-control" id="txt_nama" placeholder="Nama Dokumen"
                                               required/>
                                    </div>
                                </div>
                                <div class="document-editor">
                                    <div class="document-editor__toolbar"></div>
                                    <div class="document-editor__editable-container">
                                        <div id="template-editor">
                                            <figure class="table"><table><tbody><tr><td><figure class="image"><img src="<?php echo __HOST__; ?>client/template/assets/images/logo-icon.png"></figure></td><td><h4 style="text-align:center;"><span class="text-small" style="font-family:'Times New Roman', Times, serif;">PEMERINTAH PROVINSI RIAU</span></h4><h4 style="text-align:center;"><span style="font-family:'Times New Roman', Times, serif;">RUMAH SAKIT UMUM DAERAH PETALA BUMI</span></h4><p style="text-align:center;"><span class="text-small">Jl. Dr. Soetomo No. 65 Telp. (0761) 23024</span><br><span class="text-small">Email : rsudpetalabumi@riau.go.id</span></p><h4 style="text-align:center;"><span class="text-tiny">PEKANBARU</span></h4></td></tr></tbody></table></figure><hr />
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
<!--<h4 style="text-align:center;">
        <span class="text-small">PEMERINTAH PROVINSI RIAU</span>
    </h4>
    <h4 style="text-align:center;">RUMAH SAKIT UMUM DAERAH PETALA BUMI</h4>
    <h4 style="text-align:center;">
        <span class="text-tiny">
            <strong>Jl. Dr. Soetomo No. 65 Telp. (0761) 23024</strong>
        </span>
        <br>
        <span class="text-tiny"><strong>Email : rsudpetalabumi@riau.go.id</strong></span>
    </h4>
    <h4 style="text-align:center;"><span class="text-tiny">PEKANBARU</span></h4>
    <hr />
    <h4 style="text-align:center;">
        <span class="text-tiny">
            <strong><u>SURAT KETERANGAN KESEHATAN</u></strong>
        </span>
    </h4>
    <p style="text-align:center;">
        <span class="text-tiny">Nomor:_________________________</span>
    </p>
    <p>
        <span class="text-small">Direktur Rumah Sakit Umum Daerah RSUD Petala Bumi Provinsi Riau dibawah ini menerangkan bahwa:</span>
    </p>
    <form>
        asd
    </form>-->
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 800px;
    }
</style>