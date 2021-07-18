<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pasien</li>
                </ol>
            </nav>
            <h4 class="m-0">Data Pasien</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="card">
        <div class="card-header card-header-large bg-white d-flex align-items-center">
            <h5 class="card-header__title flex m-0">CPPT</h5>
        </div>
        <div class="card-body tab-content">
            <div class="tab-pane active show fade" id="resep-biasa">
                <div class="row">
                    <div class="col-md-12">


                        <div class="card card-form d-flex flex-column flex-sm-row">
                            <div class="card-form__body card-body-form-group flex">
                                <div class="row">
                                    <div class="col-sm-auto">
                                        <div class="form-group" style="width: 200px;">
                                            <label for="filter_date">Dari - Sampai</label>
                                            <input id="filter_date" type="text" class="form-control" placeholder="Select date ..." value="13/03/2018 to 20/03/2018" data-toggle="flatpickr" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary">refresh</i></button>
                        </div>


                        <div id="cppt_pagination">

                        </div>
                        <div id="cppt_loader">

                        </div>


                        <div class="mt-4">
                            <ul class="pagination justify-content-center" id="pagin">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>