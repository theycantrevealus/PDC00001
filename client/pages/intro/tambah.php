<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/intro">Introduction</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Introduction Management</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-header__title flex m-0">
                Tambah Intro
            </h5>
        </div>
        <div class="card-body">
            <?php require 'form.php'; ?>
        </div>
    </div>
</div>
