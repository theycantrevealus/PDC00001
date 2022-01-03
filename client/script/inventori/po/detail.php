<script type="text/javascript">
	$(function() {
		var detailDT;
		var UID = __PAGES__[3];
		$.ajax({
			url: __HOSTAPI__ + "/PO/view/" + UID,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				console.clear();
				console.log(response);
				var poInfo = response.response_package.response_data[0];
				var detailItem = response.response_package.response_data[0].item;
				var docItem = response.response_package.response_data[0].document;

				$("#keterangan-po").html(((poInfo.keterangan == "") ? "<span class=\"text-secondary\">Tidak ada keterangan</span>" : poInfo.keterangan));
				
				var discAllCaption = "";
				if(poInfo.disc_type == "P") {
					discAllCaption = poInfo.disc + "%<br />(" + number_format(poInfo.disc / 100 * poInfo.total, 2, ".", ",") + ")";
				} else if(poInfo.disc_type == "A") {
					discAllCaption = "(" + poInfo.disc + ")";
				} else {
					discAllCaption = "-";
				}
				
				$("#pegawai_name").html(poInfo.pegawai.nama);

				$("#supplier_name").html(poInfo.supplier.nama);
				$("#supplier_info").html(
					"<a href=\"mailto:" + poInfo.supplier.email + "\">" + poInfo.supplier.email + "</a><br />" + 
					"+62" + poInfo.supplier.kontak + "<br />" +
					poInfo.supplier.alamat
				);

				$("#tanggal_po").html(poInfo.tanggal_po);

				$("#disc_all").html(discAllCaption);

				$("#total_all").html(number_format(poInfo.total, 2, ".", ","));

				$("#grandTotal h5").html(number_format(poInfo.total_after_disc, 2, ".", ","));


				detailDT = $("#table-detail-po").DataTable({
					processing: true,
					serverSide: true,
					sPaginationType: "full_numbers",
					bPaginate: true,
					lengthMenu: [[20, 50, -1], [20, 50, "All"]],
					serverMethod: "POST",
					"ajax":{
						url: __HOSTAPI__ + "/PO",
						type: "POST",
						data: function(d) {
							d.request = "po_data_detail";
							d.uid = UID;
						},
						headers:{
							Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
						},
						dataSrc:function(response) {
							console.clear();
							console.log(response);
							response.draw = parseInt(response.response_package.response_draw);
							response.recordsTotal = response.response_package.recordsTotal;
							response.recordsFiltered = response.response_package.recordsFiltered;
							return response.response_package.response_data;
						}
					},
					autoWidth: false,
					aaSorting: [[0, "asc"]],
					"columnDefs":[
						{"targets":0, "className":"dt-body-left"}
					],
					language: {
						search: "",
						searchPlaceholder: "Cari Laboratorium"
					},
					"columns" : [
						{
							"data" : null, render: function(data, type, row, meta) {
								return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
							}
						},
						{
							"data" : null, render: function(data, type, row, meta) {
								return row.barang_detail.nama;
							}
						},
						{
							"data" : null, render: function(data, type, row, meta) {
								return row.qty;
							}
						},
						{
							"data" : null, render: function(data, type, row, meta) {
								return row.barang_detail.satuan_terkecil_info.nama;
							}
						},
						{
							"data" : null, render: function(data, type, row, meta) {
								return number_format(row.harga, 2, ".", ",");
							}
						},
						{
							"data" : null, render: function(data, type, row, meta) {
								return number_format(row.subtotal, 2, ".", ",");
							}
						}
					]
				});

				// for(var a = 0; a < detailItem.length; a++) {
				// 	var discCaption = "";
				// 	if(detailItem[a].disc_type == "A") {
				// 		discCaption = "(" + detailItem[a].disc + ")";
				// 	} else if(detailItem[a].disc_type == "P") {
				// 		discCaption = detailItem[a].disc + "%";
				// 	} else {
				// 		discCaption = "0";
				// 	}
				// 	$("#table-detail-po tbody").append(
				// 		"<tr>" +
				// 			"<td>" + (a + 1) + "</td>" +
				// 			"<td>" + detailItem[a].detail.nama + "</td>" +
				// 			"<td>" + detailItem[a].qty + "</td>" +
				// 			"<td>" + detailItem[a].detail.satuan_caption.nama + "</td>" +
				// 			"<td class=\"text-right\">" + 
				// 				"<b>" + number_format (detailItem[a].harga, 2, ".", ",") + "</b>" +
				// 				"<br />Diskon " + discCaption +
				// 			"</td>" +
				// 			"<td class=\"text-right\">" + number_format (detailItem[a].subtotal, 2, ".", ",") + "</td>" +
				// 		"</tr>"
				// 	);
				// }

				for(var b = 0; b < docItem.length; b++) {
					$("#po_document_table tbody").append(
						"<tr>" +
							"<td>" + (b + 1) + "</td>" +
							"<td>" + docItem[b].document_name + "</td>" +
							"<td><button class=\"btn btn-info btn-sm viewdocument\" target-document=\"" + docItem[b].document_name + "\"><i class=\"fa fa-eye\"></i></button></td>" +
						"</tr>"
					);
				}
			},
			error: function(response) {
				console.log(response);
			}
		});




		var pdfjsLib = window['pdfjs-dist/build/pdf'];
		pdfjsLib.GlobalWorkerOptions.workerSrc = __HOSTNAME__ + '/plugins/pdfjs/build/pdf.worker.js';
		var loadingTask;

		$("body").on("click", ".viewdocument", function() {
			var currentDoc = $(this).attr("target-document");
			
			loadingTask = pdfjsLib.getDocument({
				url: __HOST__ + "document/po/" + currentDoc
			});
			$("#form-upload-document").modal("show");
		});



		$('#form-upload-document').on('shown.bs.modal', function () {
			loadingTask.promise.then(function(pdf) {
				
				var pageNumber = 1;
				pdf.getPage(pageNumber).then(function(page) {
					var scale = 1.5;
					var viewport = page.getViewport({
						scale: scale
					});

					var canvas = $("#pdfViewer")[0];
					var context = canvas.getContext('2d');
					canvas.height = viewport.height;
					canvas.width = viewport.width;
					
					var renderContext = {
						canvasContext: context,
						viewport: viewport
					};
					var renderTask = page.render(renderContext);
					renderTask.promise.then(function() {
						//
					});
				});
			}, function(reason) {
				console.error(reason);
			});
		});
	});
</script>

<div id="form-upload-document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Lihat Lampiran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<canvas style="width: 100%; border: solid 1px #ccc;" id="pdfViewer"></canvas>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
