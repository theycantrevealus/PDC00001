<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header card-header-large bg-white d-flex align-items-center">
				<h5 class="card-header__title flex m-0">Informasi Dasar</h5>
			</div>
			<div class="card-header card-header-tabs-basic nav" role="tablist">
				<a href="#info-dasar-1" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Umum</a>
				<a href="#info-dasar-2" data-toggle="tab" role="tab" aria-selected="false">Kategori Obat</a>
                <a href="#info-dasar-3" data-toggle="tab" role="tab" aria-selected="false">Kandungan Obat</a>
			</div>
			<div class="card-body tab-content">
				<div class="tab-pane show fade active" id="info-dasar-1">
					<div class="row">
						<div class="col-md-4">
							<div id="image-uploader"></div>
							<h6 class="text-center">
								<span class="custom-upload btn btn-info">
									<input type="file" name="" id="upload-image" />
									<i class="fa fa-upload"></i> Upload
								</span>
							</h6>
						</div>
						<div class="col-lg-8">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="txt_nama">Nama Item:</label>
										<input type="text" class="form-control uppercase" id="txt_nama" placeholder="Nama Item" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="txt_kategori">Kategori Item:</label>
										<select class="form-control" id="txt_kategori"></select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="txt_kode">Kode Item:</label>
										<input type="text" class="form-control uppercase" id="txt_kode" placeholder="Kode Item" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="txt_manufacture">Manufacture:</label>
										<select class="form-control" id="txt_manufacture"></select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="txt_keterangan">Keterangan:</label>
										<textarea id="txt_keterangan" class="form-control" placeholder="Keterangan Item"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane show fade" id="info-dasar-2">
					<ul id="load-kategori-obat" style="list-style-type: none;">
						
					</ul>
				</div>
                <div class="tab-pane show fade" id="info-dasar-3">
                    <table class="table table-bordered largeDataType" id="load-kandungan-obat">
                        <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th width="40%">Kandungan</th>
                                <th>Keterangan</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>