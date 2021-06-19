<div class="mdk-header-layout__content">
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">
            <div style="padding-bottom: calc(5.125rem / 2); position: relative; margin-bottom: 1.5rem;">
                <div style="min-height: 250px; background: url('<?php echo __HOSTNAME__; ?>/template/assets/images/profile_banner.jpg') no-repeat; background-size: cover; background-position: 0 -250px;">
                    <div class="d-flex align-items-end container-fluid page__container" style="position: absolute; left: 0; right: 0; bottom: 0;">
                        <div class="avatar avatar-xl">
                            <img src="<?php echo __HOST__; ?>images/pegawai/<?php echo $_SESSION['uid']; ?>.png" alt="avatar" class="avatar-img rounded" style="border: 2px solid white;">
                        </div>
                        <div class="card-header card-header-tabs-basic nav flex" role="tablist">
                            <a href="#purchases" data-toggle="tab" role="tab" aria-selected="false">Profile</a>
                            <a href="#activity" class="active show" data-toggle="tab" role="tab" aria-selected="true">Activity</a>
                            <a href="#emails" data-toggle="tab" role="tab" aria-selected="false">Setting</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid page__container">
                <div class="row card-group-row">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-2">
                                <h1 class="h4 mb-1"><?php echo $_SESSION['nama']; ?></h1>
                                <p class="text-muted">@<?php echo $_SESSION['email']; ?></p>
                                <p><?php echo $_SESSION['jabatan']['response_data'][0]['nama']; ?></p>
                                <div class="text-muted d-flex align-items-center">
                                    <i class="material-icons mr-1">location_on</i>
                                    <div class="flex">Dracula's Castle, Transilvania</div>
                                </div>
                                <div class="text-muted d-flex align-items-center">
                                    <i class="material-icons mr-1">link</i>
                                    <div class="flex"><a href="https://www.frontted.com">frontted.com</a></div>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="activity">


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