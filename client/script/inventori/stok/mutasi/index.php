<script type="text/javascript">
	$(function() {
		function getDateRange(target) {
			var rangeKwitansi = $(target).val().split(" to ");
			if(rangeKwitansi.length > 1) {
				return rangeKwitansi;
			} else {
				return [rangeKwitansi, rangeKwitansi];
			}
		}

		var tableAmprah = $("#table-list-amprah").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_mutasi_request";
					d.from = getDateRange("#range_amprah")[0];
					d.to = getDateRange("#range_amprah")[1];
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					console.log(response);
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Kode Amprah"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum + "<input type=\"hidden\" id=\"keterangan_" + row.uid + "\" value=\"" + row.keterangan + "\" />";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tanggal_" + row.uid + "\">" + row.tanggal + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<b id=\"kode_" + row.uid + "\">" + row.kode + "</b>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"unit_asal_" + row.uid + "\">" + row.dari.nama + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"unit_tujuan_" + row.uid + "\">" + row.ke.nama + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"oleh_" + row.uid + "\">" + row.pegawai.nama + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<button class=\"btn btn-sm btn-info detail_mutasi\" id=\"mutasi_" + row.uid + "\"><i class=\"fa fa-eye\"></i></button>";
					}
				}
			]
		});

		$("#range_amprah").change(function() {
			tableAmprah.ajax.reload();
		});

		$("body").on("click", ".detail_mutasi", function() {
		    var uid = $(this).attr("id").split("_");
		    uid = uid[uid.length - 1];

            $("#kode_mutasi").html($("#kode_" + uid).html());
		    $("#unit_asal").html($("#unit_asal_" + uid).html());
            $("#unit_tujuan").html($("#unit_tujuan_" + uid).html());
            $("#pegawai_proses").html($("#oleh_" + uid).html());
            $("#tanggal_mutasi").html($("#tanggal_" + uid).html());
            $("#keterangan_tambahan").html($("#keterangan_" + uid).val());

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Inventori/get_mutasi_item/" + uid,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response){
                    var data = response.response_package.response_data;
                    $("#mutasi_detail_item tbody tr").remove();
                    for(var k in data) {
                        console.log(data[k].batch);
                        $("#mutasi_detail_item tbody").append(
                            "<tr>" +
                                "<td>" + (parseInt(k) + 1) + "</td>" +
                                "<td>" + data[k].item.nama + "</td>" +
                                "<td>" + data[k].batch.batch + "</td>" +
                                "<td>" + data[k].item.nama + "</td>" +
                                "<td class=\"number_style\">" + data[k].qty + "</td>" +
                                "<td>" + data[k].keterangan + "</td>" +
                            "</tr>"
                        );
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
		    $("#detail-mutasi").modal("show");
		    return false;
        });
	});
</script>


<div id="detail-mutasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kode_mutasi"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="form-mode">
                                            <tr>
                                                <td>Dari</td>
                                                <td class="wrap_content">:</td>
                                                <td id="unit_asal"></td>

                                                <td>Ke</td>
                                                <td class="wrap_content">:</td>
                                                <td id="unit_tujuan"></td>
                                            </tr>
                                            <tr>
                                                <td>Oleh</td>
                                                <td class="wrap_content">:</td>
                                                <td id="pegawai_proses"></td>

                                                <td>Tanggal</td>
                                                <td class="wrap_content">:</td>
                                                <td id="tanggal_mutasi"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped largeDataType" id="mutasi_detail_item">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content">No</th>
                                                <th>Item</th>
                                                <th>Batch</th>
                                                <th>Satuan</th>
                                                <th>Jumlah</th>
                                                <th>Keterangan</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white">
                                <div class="row">
                                    <div class="col-12">
                                        <b>
                                            <h6>Keterangan:</h6>
                                        </b>
                                        <p id="keterangan_tambahan"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>