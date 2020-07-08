<script type="text/javascript">
	$(function(){
		var params;

		$('#table-list-pencarian').DataTable({	
			"bFilter": false,
			"bInfo" : false
		});

		$("#btnCari").on('click', function(){
			params = $("#txt_cari").val();

			$("#loader-search").removeAttr("hidden");
			setTimeout(function(){
				$.ajax({
					async: false,
					url:__HOSTAPI__ + "/Antrian/cari-pasien/" + params,
					type: "GET",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					success: function(response){
						var MetaData = dataTindakan = response.response_package.response_data;

						var html = "";
						if (MetaData != ""){
							$.each(MetaData, function(key, item){
								html += "<tr>" +
											"<td>"+ item.autonum  +"</td>" +
											"<td>"+ item.no_rm +"</td>" +
											"<td>"+ item.nama +"</td>" +
											"<td>"+ item.nik +"</td>" +
											"<td>"+ item.jenkel +"</td>" +
											"<td><a href='"+ __HOSTNAME__ + "/antrian_resepsionis/tambah/"+ item.uid +"' class='btn btn-sm btn-info' data-toggle='tooltip' title='Tambah ke Antrian'><i class='material-icons'>queue</i></a></td>" +
										"</tr>";
							});
						} else {
							html += "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
						}
						
						$("#table-list-pencarian tbody").html(html);
						$("#loader-search").attr("hidden",true);
					},
					error: function(response) {
						console.log(response);
					}
				});

			}, 250);
		});

		$("#btnTambahAntrian").click(function(){
			$("#table-list-pencarian tbody").html("<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>");
			$("#modal-cari").modal("show");
		});
	});

</script>

<script src="<?= __HOSTNAME__ ?>/template/assets/vendor/toastr.min.js"></script>
<script src="<?= __HOSTNAME__ ?>/template/assets/js/toastr.js"></script>

<div id="modal-cari" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal-large-title">Tambah Antrian</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group col-md-6">
						<div class="col-md-6">
							<div class="row">
								
								<label for="txt_cari">Cari Pasien</label>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="search-form form-control-rounded search-form--light input-group-lg col-md-10">
									<input type="text" class="form-control" placeholder="Nama / NIK / No. RM" id="txt_cari">
									<button class="btn" type="button" id="btnCari" role="button"><i class="material-icons">search</i></button>
								</div>
								<div class="col-md-2">
									<div class="loader loader-lg loader-primary" id="loader-search" hidden></div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-12">
						<table class="table table-bordered table-striped" id="table-list-pencarian">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>No. RM</th>
									<th>NIK</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<!-- <tr>
									<td>No</td>
									<td>RM</td>
									<td>NIK</td>
									<td>Pasien</td>
									<td>Jenkel</td>
									<td>Aksi</td>
								</tr> -->
								
							</tbody>
						</table>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div> 
		</div> 
	</div> 
