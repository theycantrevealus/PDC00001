<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Stok</li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Manajemen Stok</h4>
        </div>

    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12 card-group-row__col">
            <div class="card card-group-row__card card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Manajemen Stok</h5>
                            </div>
                            <div class="card-body tab-content">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <button class="btn btn-warning" id="btnResepStokLog">
                                            <span>
                                                <i class="fa fa-recycle"></i> Reset Stok Log
                                            </span>
                                        </button>
                                    </div>
                                    <div class="col-lg-3" id="resepStatus">
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