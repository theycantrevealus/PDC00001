<script type="text/javascript">
	$(function() {
		var poliList = <?php echo json_encode($_SESSION['poli']['response_data'][0]['poli']['response_data']); ?>;
		var myPoliAvail = [];

		if(poliList.length > 1) {
			$("#change-poli").show();
			$("#current-poli").addClass("handy");
		} else {
			$("#change-poli").hide();
			$("#current-poli").removeClass("handy");
		}

		var myPoli = [];



		function load_poli_info() {
			//
		}

		/*$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Antrian/antrian",
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type: "GET",
			success: function(response){
				console.log(response);
			},
			error: function(response) {
				console.clear();
				console.log(response);
			}
		});*/

		var params;

		var tableAntrian = $("#table-antrian-rawat-jalan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-medis",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var data = response.response_package.response_data;
				    var poliList = [];
				    var my_poliData = __POLI__.response_data;
				    for(var a in my_poliData) {
				        var poliGet = my_poliData[a].poli.response_data;
				        for(var b in poliGet) {
                            if(myPoli.indexOf(poliGet[b].nama) < 0) {
                                myPoli.push(poliGet[b].nama);
                            }

                            if(myPoliAvail.indexOf(poliGet[b].uid) < 0) {
                                myPoliAvail.push(poliGet[b].uid);
                            }
                        }
                    }
				    var parsedData = [];
                    for(var key in data) {
                        if(data[key].uid_poli !== __POLI_INAP__ && data[key].uid_poli !== __POLI_IGD__ && myPoliAvail.indexOf(data[key].uid_poli) >= 0) {
                            parsedData.push(data[key]);
                        }
                    }
				    /*for(var key in data) {
				        if(data[key].uid_poli !== __POLI_INAP__ && data[key].uid_poli !== __POLI_IGD__) {
				            parsedData.push(data[key]);
                        }

				        if(myPoli.indexOf(data[key].departemen) < 0) {
                            myPoli.push(data[key].departemen);
                        }

                    }*/

                    $("#current-poli").html(myPoli.join(", "));
					$("#jlh-antrian").html(parsedData.length);
					return parsedData;
				}
			},
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

			    if(parseInt(aData.prioritas) === __PRIORITY_HIGH__) {
                    $("td", nRow).addClass("bg-danger-custom");
                }
            },
			autoWidth: false,
			"bInfo" : false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["waktu_masuk"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["no_rm"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["pasien"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["departemen"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["dokter"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["penjamin"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["user_resepsionis"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						console.log(row);
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a pasien=\"" + row.uid_pasien + "\" tindakan=\"" + row.poli_detail.tindakan_konsultasi + "\" href=\"" + __HOSTNAME__ + "/rawat_jalan/dokter/antrian/" + row['uid'] + "\" class=\"btn btn-success btnDetailAntrian\">" +
										"<i class=\"fa fa-sign-out-alt\"></i> Proses Perobatan" +
									"</a>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btnDetailAntrian", function() {
		    var targetURL = $(this).attr("href");
		    var targetAntrian = targetURL.split("/");
		    targetAntrian = targetAntrian[targetAntrian.length - 1];
		    var tindakanTarget = $(this).attr("tindakan");
            var pasienTarget = $(this).attr("pasien");

		    $(this).removeClass("btn-success").addClass("btn-warning").html("<i class=\"fa fa-hourglass\"></i> Processing...");
		    //Prepare Asesmen
            push_socket(__ME__, "asesmen_berlangsung", "*", {
                "message": "Asesmen sudah berlangsung",
                "antrian": targetAntrian,
                "tindakan": tindakanTarget,
                "pasien": pasienTarget
            }, "info").then(function() {
                location.href = targetURL;
            });

		    return false;
        });

        /*Sync.onmessage = function(evt) {
            var signalData = JSON.parse(evt.data);
            var command = signalData.protocols;
            var type = signalData.type;
            var sender = signalData.sender;
            var receiver = signalData.receiver;
            var time = signalData.time;
            var parameter = signalData.parameter;

            if(command !== undefined && command !== null && command !== "") {
                protocolLib[command](command, type, parameter, sender, receiver, time);
            }
        }*/



        protocolLib = {
            antrian_poli_ubah: function (protocols, type, parameter, sender, receiver, time) {
                if(receiver === __ME__) {
                    notification ("info", "Antrian poli baru", 3000, "notif_pasien_baru");
                }
                tableAntrian.ajax.reload();
            },
            antrian_poli_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", "Antrian poli baru", 3000, "notif_pasien_baru");
                tableAntrian.ajax.reload();
            },
            retur_barhasil: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrian.ajax.reload();
            }
        };


		/*================== FORM CARI AREA ====================*/

		$('#table-list-pencarian').DataTable({	
			"bFilter": false,
			"bInfo" : false
		});

		$("#txt_cari").on('keyup', function() {
			params = $("#txt_cari").val();

			$("#table-list-pencarian tbody").html("");
			$("#pencarian-notif").attr("hidden",true);
			$("#loader-search").removeAttr("hidden");
			if (params != ""){
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
									var buttonAksi = "<td style='text-align:center;'><a href='"+ __HOSTNAME__ + "/antrian_resepsionis/tambah/"+ item.uid +"' class='btn btn-sm btn-info' data-toggle='tooltip' title='Tambah ke Antrian'><i class='fa fa-user-plus'></i></a></td>";

									if (item.berobat == true){
										buttonAksi = "<td style='text-align:center;'><span class='badge badge-warning'>Sedang Berobat</span></td>";
									}

									html += "<tr disabled>" +
												"<td>"+ item.autonum  +"</td>" +
												"<td>"+ item.no_rm +"</td>" +
												"<td>"+ item.nama +"</td>" +
												"<td>"+ item.nik +"</td>" +
												"<td>"+ item.jenkel +"</td>" +
												buttonAksi +
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
			} else {
				$("#loader-search").attr("hidden",true);

				var html = "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
				$("#table-list-pencarian tbody").html(html);
			}
			
			$("#btnTambahPasien").fadeIn("fast");
		});

		$("#btnTambahAntrian").click(function(){
			$("#btnTambahPasien").fadeOut("false");
			$("#txt_cari").val("");
			$("#table-list-pencarian tbody").html("<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>");
			$("#modal-cari").modal("show");
		});

        setTimeout(function() {

            tableAntrian.ajax.reload();

        }, 5000);
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
                            </div>
                            <div class="col-md-12" hidden id="pencarian-notif" style="color: red; font-size: 0.8rem;">
                                Mohon ketikkan kata kunci pencarian
                            </div>
                            <div class="col-md-2">
                                <div class="loader loader-lg loader-primary" id="loader-search" hidden></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12" >
                    <!-- style="height: 100px; overflow: scroll;" -->
                    <table class="table table-bordered table-striped" id="table-list-pencarian">
                        <thead>
                            <tr>
                                <th width="2%">No</th>
                                <th>No. RM</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <!-- <div id="spanBtnTambahPasien" hidden> -->
                <a href="<?= __HOSTNAME__ ?>/pasien/tambah" class="btn btn-success" id="btnTambahPasien">
                <!-- <i class="fa fa-plus"></i>  -->Tambah Pasien Baru
                </a>
                <!-- </div> -->

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
