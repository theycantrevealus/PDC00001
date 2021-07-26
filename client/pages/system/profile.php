<div class="mdk-header-layout__content">
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">
            <div style="padding-bottom: calc(5.125rem / 2); position: relative; margin-bottom: 1.5rem;">
                <div style="min-height: 250px; background: url('<?php echo __HOSTNAME__; ?>/template/assets/images/profile_banner.jpg') no-repeat; background-size: cover; background-position: 0 -250px;">
                    <div class="d-flex align-items-end container-fluid page__container" style="position: absolute; left: 0; right: 0; bottom: 0;">
                        <div class="avatar avatar-xl">
                            <img src="<?php echo __HOST__; ?><?php echo $_SESSION['profile_pic']; ?>" alt="avatar" class="avatar-img rounded">
                        </div>
                        <div class="card-header card-header-tabs-basic nav flex" role="tablist">
                            <a href="#profile" class="active show" data-toggle="tab" role="tab" aria-selected="false">Profile</a>
                            <a href="#activity" data-toggle="tab" role="tab" aria-selected="true">Activity</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid page__container">
                <div class="row card-group-row">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-3">
                                <h4><?php echo $_SESSION['nama']; ?></h4>
                                <p class="text-muted">@<?php echo $_SESSION['email']; ?></p>
                                <p><?php echo $_SESSION['jabatan']['response_data'][0]['nama']; ?></p>
                            </div>
                            <div class="col-lg-9">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile">
                                        <div class="card">
                                            <div class="px-4 py-3">
                                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                                    <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Ubah Password</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="alert alert-soft-info d-flex align-items-center card-margin" role="alert">
                                                                <i class="material-icons mr-3" style="position:absolute; top: 12.5px;">error_outline</i>
                                                                <div class="row" style="padding-left: 30px">
                                                                    <div class="col-lg-12">
                                                                        <div class="text-body">
                                                                            <strong class="text-info">Perhatian.</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <p>
                                                                            Akun merupakan tanggung jawab masing-masing pemilik akun. Segala tindakan dan administrasi akun tercatat oleh sistem dan menjadi tanggung jawab penuh pemilik akun. Mohon untuk menjaga kerahasiaan akun kepada semua pihak.
                                                                        </p>
                                                                        <p>
                                                                            Jika Anda lupa password, harap hubungi pihak EDP.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label for="txt_pass_old">Password Lama</label>
                                                            <div class="input-group">
                                                                <input type="password" id="txt_pass_old" class="form-control form-control-appended" placeholder="Password Lama" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-8">
                                                            <label for="txt_pass_new">Password Baru</label>
                                                            <div class="input-group">
                                                                <input type="password" id="txt_pass_new" class="form-control form-control-appended" placeholder="Password Baru" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-8">
                                                            <label for="txt_pass_conf">Konfirmasi Password</label>
                                                            <div class="input-group">
                                                                <input type="password" id="txt_pass_conf" class="form-control form-control-appended" placeholder="Konfirmasi Password" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button type="button" class="btn btn-info" id="btnSimpan">
                                                                <i class="fa fa-save"></i> Update
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="activity">
                                        <div class="card">
                                            <div class="px-4 py-3">
                                                <div class="d-flex mb-1">
                                                    <div class="avatar avatar-sm mr-3">
                                                        <img src="assets/images/256_daniel-gaffey-1060698-unsplash.jpg" alt="Avatar" class="avatar-img rounded-circle">
                                                    </div>
                                                    <div class="flex">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <strong class="text-15pt">Sherri J. Cardenas</strong>
                                                            <small class="ml-2 text-muted">3 days ago</small>
                                                        </div>
                                                        <div>
                                                            <p>Thanks for contributing to the release of FREE Admin Vision - PRO Admin Dashboard Theme <a href="">https://www.frontted.com/themes/admin-vision...</a> 🔥</p>
                                                            <p><a href="">#themeforest</a> <a href="">#EnvatoMarket</a></p>
                                                        </div>

                                                        <div class="d-flex align-items-center">
                                                            <a href="" class="text-muted d-flex align-items-center decoration-0"><i class="material-icons mr-1" style="font-size: inherit;">favorite_border</i> 38</a>
                                                            <a href="" class="text-muted d-flex align-items-center decoration-0 ml-3"><i class="material-icons mr-1" style="font-size: inherit;">thumb_up</i> 71</a>
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
</div>