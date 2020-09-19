<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/terminologi">Terminologi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Terminologi</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card dashboard-area-tabs" id="dashboard-area-tabs">
                <div class="card-header card-header-large bg-white">
                    <h4 class="card-header__title">Terminologi</h4>
                </div>
                <div class="card-body text-muted">
                	<div class="row">
                		<div class="col-md-6">
                			<form>
	                    		<div class="col-md-12">
		                            <div class="form-group">
		                                <div class="row">
		                                    <div class="col-md-4">  
		                                        <label for="txt_nama_term">Terminologi</label>
		                                    </div>
		                                    <div class="col-md-8">
		                                        <input type="text" autocomplete="off" class="form-control" id="txt_nama_term">
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="col-md-12">
		                            <table class="table table-bordered table-striped" id="table-term-usage">
		                                <thead>
		                                    <tr>
		                                        <th style="width: 10%">No</th>
		                                        <th>Terminologi Usage</th>
		                                        <th>Aksi</th>
		                                    </tr>
		                                </thead>
		                                <tbody>
		                                    
		                                </tbody>
		                            </table>
		                        </div>
		                    </form>
                    	</div>
                    	<div class="col-md-6">
                    		<button class="btn btn-sm btn-info" id="tambah-item">
								<i class="fa fa-plus"></i>Tambah</span>
							</button>
                    	</div>
	                </div>
                </div>
                 <div class="card-footer  bg-white">
                    <button class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>