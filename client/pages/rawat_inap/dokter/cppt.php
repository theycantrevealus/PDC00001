<div class="row">
	<div class="col-lg">
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
											<div class="form-group">
												<label for="filter_name">Search</label>
												<input id="filter_name" type="text" class="form-control" placeholder="Enter keyword">
											</div>
										</div>
										<div class="col-sm-auto">
											<div class="form-group">
												<label for="filter_poli">Poliklinik</label><br>
												<select id="filter_poli" class="form-control" style="width: 300px;"></select>
											</div>
										</div>
										<div class="col-sm-auto">
											<div class="form-group">
												<label for="filter_dokter">Dokter</label><br>
												<select id="filter_dokter" class="form-control" style="width: 350px;"></select>
											</div>
										</div>
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

								<!--<ul class="pagination justify-content-center">

									<li class="page-item disabled">
										<a class="page-link" href="#" aria-label="Previous">
											<span aria-hidden="true" class="material-icons">first_page</span>
											<span class="sr-only">First</span>
										</a>
									</li>

									<li class="page-item disabled">
										<a class="page-link" href="#" aria-label="Previous">
											<span aria-hidden="true" class="material-icons">chevron_left</span>
											<span class="sr-only">Prev</span>
										</a>
									</li>

									<li class="page-item active">
										<a class="page-link" href="#" aria-label="1">
											<span>1</span>
										</a>
									</li>

									<li class="page-item">
										<a class="page-link" href="#" aria-label="2">
											<span>2</span>
										</a>
									</li>

									<li class="page-item">
										<a class="page-link" href="#" aria-label="3">
											<span>3</span>
										</a>
									</li>

									<li class="page-item">
										<a class="page-link" href="#" aria-label="4">
											<span>4</span>
										</a>
									</li>

									<li class="page-item">
										<a class="page-link" href="#" aria-label="Next">
											<span class="sr-only">Next</span>
											<span aria-hidden="true" class="material-icons">chevron_right</span>
										</a>
									</li>

									<li class="page-item">
										<a class="page-link" href="#" aria-label="Next">
											<span class="sr-only">Last</span>
											<span aria-hidden="true" class="material-icons">last_page</span>
										</a>
									</li>
								</ul>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>