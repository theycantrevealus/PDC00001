<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/setting">Setting</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Socket</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h5 class="card-header__title flex m-0">System Setting</h5>
                    <button class="btn btn-info" id="btnTambahSetting">
                        <span>
                            <i class="fa fa-plus"></i> Tambah Setting
                        </span>
                    </button>
                    <button class="btn btn-info" id="btnManageGroup">
                        <span>
                            <i class="fa fa-plus"></i> Manage Group
                        </span>
                    </button>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active show fade" id="list-resep">
                        <table class="table form-mode" id="setting-loader"></table>
                    </div>
                    <div class="tab-pane active show fade" id="list-revisi">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>