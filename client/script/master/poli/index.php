<script type="text/javascript">
	$(function(){
		var penjamin = loadPenjamin();

		var MODE = "tambah", selectedUID;
		var tablePoli = $("#table-poli").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Poli",
				type: "POST",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
                data: function(d) {
                    d.request = "get_poli_backend";
                },
				dataSrc:function(response) {
				    var returnData = [];
                    var rawData = [];
				    if(
				        response === undefined ||
                        response === null ||
                        response.response_package === undefined ||
                        response.response_package === null) {
                        rawData = [];
                        response.draw = 1;
                        response.recordsTotal = 0;
                        response.recordsFiltered = 0;
                    } else {
                        rawData = response.response_package.response_data;
                        for(var polKey in rawData) {
                            if(
                                //rawData[polKey].uid !== __UIDFISIOTERAPI__ &&
                                //rawData[polKey].uid !== __POLI_INAP__ &&
                                //rawData[polKey].uid !== __POLI_IGD__ &&
                                rawData[polKey].uid !== __POLI_LAB__
                            ) {
                                returnData.push(rawData[polKey]);
                            }
                        }

                        response.draw = parseInt(response.response_package.response_draw);
                        response.recordsTotal = response.response_package.recordsTotal;
                        response.recordsFiltered = response.response_package.recordsFiltered;
                    }
					return returnData;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"konsul_" + row["uid"] + "\">" + ((row["tindakan_konsultasi"] == undefined) ? "-" : row["tindakan_konsultasi"]) + "</span>";
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return  (row.kode_bpjs === undefined || row.kode_bpjs === null) ? "<strong class=\"text-danger\"><i class=\"fa fa-exclamation-triangle\"></i> Belum Set</strong>" : "<strong class=\"text-success\"><i class=\"fa fa-check-circle\"></i> " + row.nama_bpjs + "</strong>";
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									/*"<button id=\"poli_view_" + row['uid'] + "\" class=\"btn btn-warning btn-sm btn-detail-poli\">" +
									 	"<i class=\"fa fa-list\"></i> Detail" +
									"</button>" +*/
									"<a href=\"" + __HOSTNAME__ + "/master/poli/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-poli\">" +
										"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
									"</a>" +
									"<button id=\"poli_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-poli\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-poli", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus poli item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Poli/master_poli/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tablePoli.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});



		$("#table-poli tbody").on('click', '.btn-detail-poli', function(){
            //==== Set table head
            var html = "<tr>" +
                        "<th style='width: 10px;'>No</th>" +
                        "<th>Tindakan</th>";


            $.each(penjamin, function(key, item){
                html += "<th class='col_"+ item.uid +"'>"+ item.nama +"</th>";
            });

            html += "</tr>";

            $("#table-view-tindakan thead").html(html);
            //====

            //==== Set table content
            var no = 1;
            var html_content = ""; 
            $.each(tindakan, function(key, item){

                if (item.uid in hargaPenjamin){
                    html_content += "<tr>" + 
                                    "<td>"+ no +"</td>" +
                                    "<td>"+ item.nama +"</td>";

                    var parent_uid = item.uid;

                    $.each(penjamin, function(key, item){
                        if (item.uid in hargaPenjamin[parent_uid]){
                            html_content += "<td><span class='separated_comma'>Rp. "+ hargaPenjamin[parent_uid][item.uid] +"</span></td>";
                        } else {
                            html_content += "<td> - </td>";
                        }
                    });

                    no++;
                }
                html_content += "</tr>";
            });

            $("#table-konfirmasi tbody").html(html_content);
            $(".separated_comma").digits();
            //====

            
            $("#title-konfirmasi-poli").html(dataObject.nama);
            
        });
	});
	

	/*========== FUNC FOR LOAD PENJAMIN ==========*/
    function loadPenjamin(){
        var dataPenjamin;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Penjamin/penjamin",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPenjamin = response.response_package.response_data;
            },
            error: function(response) {
                console.log(response);
            }
        });


        if (dataPenjamin.length > 0) {
            return dataPenjamin;
        } else {
            return null;
        }
    }
    /*--------------------------------------*/
</script>


<div id="view-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tindakan dari : <span id="title-tindakan"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered" id="table-view-tindakan">
					<thead>
						
					</thead>

					<tbody>
						
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>