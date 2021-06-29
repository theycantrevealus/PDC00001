<script type="text/javascript">
	var uid = <?php echo json_encode(__PAGES__[3]); ?>;
	$(function(){
		var tindakanLibrary = {};
		var dokterLibrary = {};
		var perawatLibrary = {};
		var selectedTindakan = [];
		var selectedDokter = [];
		var selectedPerawat = [];

		var tindakanTable = $("#table-tindakan").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[15, 50, -1], [15, 50, "All"]],
			serverMethod: "POST",
			ajax:{
				url: __HOSTAPI__ + "/Poli",
				type: "POST",
				data: function(d){
					d.request = "get_poli_tindakan_back_end";
					d.poli = uid;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
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
				searchPlaceholder: "Cari Tindakan"
			},
			columns : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row.uid_tindakan + "\">" + row.nama_tindakan + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"poli_delete_" + row.uid_tindakan + "\" class=\"btn btn-danger btn-sm btn-delete-poli\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});




		var dokterTable = $("#poli-list-dokter").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[15, 50, -1], [15, 50, "All"]],
			serverMethod: "POST",
			ajax:{
				url: __HOSTAPI__ + "/Poli",
				type: "POST",
				data: function(d){
					d.request = "get_poli_dokter_back_end";
					d.poli = uid;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
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
				searchPlaceholder: "Cari Dokter"
			},
			columns : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row.dokter + "\">" + row.nama_dokter + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"dokter_delete_" + row.dokter + "\" class=\"btn btn-danger btn-sm btn-delete-dokter\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});



		var perawatTable = $("#poli-list-perawat").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[15, 50, -1], [15, 50, "All"]],
			serverMethod: "POST",
			ajax:{
				url: __HOSTAPI__ + "/Poli",
				type: "POST",
				data: function(d){
					d.request = "get_poli_perawat_back_end";
					d.poli = uid;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
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
				searchPlaceholder: "Cari Perawat"
			},
			columns : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row.perawat + "\">" + row.nama_perawat + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"dokter_delete_" + row.perawat + "\" class=\"btn btn-danger btn-sm btn-delete-perawat\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});



		$.ajax({
			async: false,
			url:__HOSTAPI__ + "/Poli/poli-detail/" + uid,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				var metaData = response.response_package.response_data[0];
				$("#tindakan_konsultasi").select2();
				$("#tindakan").select2();
				$("#txt_set_dokter").select2();
				$("#txt_set_perawat").select2();


                $("#txt_bpjs_poli").select2({
                    minimumInputLength: 2,
                    "language": {
                        "noResults": function(){
                            return "Faskes tidak ditemukan";
                        }
                    },
                    ajax: {
                        dataType: "json",
                        headers:{
                            "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                            "Content-Type" : "application/json",
                        },
                        url:__HOSTAPI__ + "/BPJS/get_poli",
                        type: "GET",
                        data: function (term) {
                            return {
                                search:term.term
                            };
                        },
                        cache: true,
                        processResults: function (response) {
                            var data = response.response_package.content.response.poli;
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.kode
                                    }
                                })
                            };
                        }
                    }
                }).addClass("form-control").on("select2:select", function(e) {
                    //
                });
                if(metaData.kode_bpjs !== null || metaData.kode_bpjs !== undefined) {
                    $("#txt_bpjs_poli").append($("<option selected='selected'></option>").val(metaData.kode_bpjs).text(metaData.kode_bpjs + " - " + metaData.nama_bpjs)).trigger("change");
                }


				load_tindakan("#tindakan_konsultasi", metaData.tindakan_konsultasi);
				//load_dokter("#txt_set_dokter", metaData.tindakan_konsultasi);

				
				for(var a = 0; a < metaData.tindakan.length; a++) {
					if(selectedTindakan.indexOf(metaData.tindakan[a].uid_tindakan) < 0) {
						selectedTindakan.push(metaData.tindakan[a].uid_tindakan);

						metaData.tindakan[a].autonum = (a + 1);
					}

					if(tindakanLibrary[metaData.tindakan[a].uid_tindakan] == undefined) {
						tindakanLibrary[metaData.tindakan[a].uid_tindakan] = {};
					}

					tindakanLibrary[metaData.tindakan[a].uid_tindakan] = metaData.tindakan[a];
				}


				for(var a = 0; a < metaData.dokter.length; a++) {
					if(selectedDokter.indexOf(metaData.dokter[a].dokter) < 0) {
						selectedDokter.push(metaData.dokter[a].dokter);
						metaData.dokter[a].autonum = (a + 1);
					}

					if(dokterLibrary[metaData.dokter[a].dokter] == undefined) {
						dokterLibrary[metaData.dokter[a].dokter] = {};
					}

					dokterLibrary[metaData.dokter[a].dokter] = metaData.dokter[a];
				}

				for(var a = 0; a < metaData.perawat.length; a++) {
					if(selectedPerawat.indexOf(metaData.perawat[a].perawat) < 0) {
						selectedPerawat.push(metaData.perawat[a].perawat);
						metaData.perawat[a].autonum = (a + 1);
					}

					if(perawatLibrary[metaData.perawat[a].perawat] == undefined) {
						perawatLibrary[metaData.perawat[a].perawat] = {};
					}

					perawatLibrary[metaData.perawat[a].perawat] = metaData.perawat[a];
				}

				load_tindakan("#tindakan", "none", selectedTindakan);
				load_dokter("#txt_set_dokter", "none", selectedDokter);
				load_perawat("#txt_set_perawat", "none", selectedPerawat);
				
				

				var nama = metaData.nama;

				$("#txt_nama").val(nama);
			},
			error: function(response) {
				console.log(response);
			}
		});








		function load_tindakan(target, selected = "", selectedData = []) {
			var tindakanData;
			$.ajax({
				url:__HOSTAPI__ + "/Tindakan",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					tindakanData = response.response_package.response_data;
					$(target).find("option").remove();
					$(target).append("<option class=\"text-muted\" value=\"none\">Pilih Tindakan</option>");
					for(var a = 0; a < tindakanData.length; a++) {
						tindakanData[a].autonum = (a + 1);
						if(selectedData.indexOf(tindakanData[a].uid) < 0) {
							$(target).append("<option " + ((tindakanData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + tindakanData[a].uid + "\">" + tindakanData[a].nama + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return tindakanData;
		}



		function load_dokter(target, selected = "", selectedData = []) {
			var dokterData;
			$.ajax({
				url:__HOSTAPI__ + "/Poli/poli-avail-dokter",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					dokterData = response.response_package.response_data;
					$(target).find("option").remove();
					$(target).append("<option class=\"text-muted\" value=\"none\">Pilih Dokter</option>");
					for(var a = 0; a < dokterData.length; a++) {
						dokterData[a].autonum = (a + 1);
						if(selectedData.indexOf(dokterData[a].uid) < 0) {
							$(target).append("<option " + ((dokterData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + dokterData[a].uid + "\">" + dokterData[a].nama_dokter + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return dokterData;
		}


		function load_perawat(target, selected = "", selectedData = []) {
			var perawatData;
			$.ajax({
				url:__HOSTAPI__ + "/Poli/poli-avail-perawat",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					perawatData = response.response_package.response_data;
					$(target).find("option").remove();
					$(target).append("<option class=\"text-muted\" value=\"none\">Pilih Perawat / Terapis</option>");
					for(var a = 0; a < perawatData.length; a++) {
						perawatData[a].autonum = (a + 1);
						if(selectedData.indexOf(perawatData[a].uid) < 0) {
							$(target).append("<option " + ((perawatData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + perawatData[a].uid + "\">" + perawatData[a].nama_perawat + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return perawatData;
		}

		$("#tindakan").change(function() {
			var tindakan = $(this).val();
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Poli",
				data: {
					request: "add_poli_tindakan",
					poli: uid,
					tindakan: tindakan
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				success:function(response) {
					if(response.response_package.response_result > 0) {
						if(selectedTindakan.indexOf(tindakan) < 0) {
							selectedTindakan.push(tindakan);
							load_tindakan("#tindakan", "none", selectedTindakan);
						}
						notification ("success", "Tindakan ditambahkan", 3000, "hasil_tambah");
						tindakanTable.ajax.reload();
					} else {
						console.log(response);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#txt_set_dokter").change(function() {
			var dokter = $(this).val();
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Poli",
				data: {
					request: "add_dokter_tindakan",
					poli: uid,
					dokter: dokter
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				success:function(response) {
					if(response.response_package.response_result > 0) {
						if(selectedDokter.indexOf(dokter) < 0) {
							selectedDokter.push(dokter);
							load_dokter("#txt_set_dokter", "none", selectedDokter);
						}
						notification ("success", "Dokter ditambahkan", 3000, "hasil_tambah");
						dokterTable.ajax.reload();
					} else {
						console.log(response);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#txt_set_perawat").change(function() {
			var perawat = $(this).val();
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Poli",
				data: {
					request: "add_perawat_tindakan",
					poli: uid,
					perawat: perawat
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				success:function(response) {
					if(response.response_package.response_result > 0) {
						if(selectedPerawat.indexOf(perawat) < 0) {
							selectedPerawat.push(perawat);
							load_perawat("#txt_set_perawat", "none", selectedPerawat);
						}
						notification ("success", "Perawat ditambahkan", 3000, "hasil_tambah");
						perawatTable.ajax.reload();
					} else {
						console.log(response);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("body").on("click", ".btn-delete-poli", function() {
			var tindakan = $(this).attr("id").split("_");
			tindakan = tindakan[tindakan.length - 1];

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Poli",
				data: {
					request: "delete_poli_tindakan",
					poli: uid,
					tindakan: tindakan
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				success:function(response) {
					if(response.response_package.response_result > 0) {
						delete selectedTindakan[selectedTindakan.indexOf(tindakan)];
						load_tindakan("#tindakan", "none", selectedTindakan);
						notification ("success", "Tindakan dihapus", 3000, "hasil_hapus");
						tindakanTable.ajax.reload();
					} else {
						console.log(response);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("body").on("click", ".btn-delete-dokter", function() {
			var dokter = $(this).attr("id").split("_");
			dokter = dokter[dokter.length - 1];

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Poli",
				data: {
					request: "delete_poli_dokter",
					poli: uid,
					dokter: dokter
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				success:function(response) {
					if(response.response_package.response_result > 0) {
						delete selectedDokter[selectedDokter.indexOf(dokter)];
						load_dokter("#txt_set_dokter", "none", selectedDokter);
						notification ("success", "Dokter dihapus", 3000, "hasil_hapus");
						dokterTable.ajax.reload();
					} else {
						console.log(response);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("body").on("click", ".btn-delete-perawat", function() {
			var perawat = $(this).attr("id").split("_");
			perawat = perawat[perawat.length - 1];

			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Poli",
				data: {
					request: "delete_poli_perawat",
					poli: uid,
					perawat: perawat
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				success:function(response) {
					if(response.response_package.response_result > 0) {
						delete selectedPerawat[selectedPerawat.indexOf(perawat)];
						load_perawat("#txt_set_perawat", "none", selectedPerawat);
						notification ("success", "Perawat dihapus", 3000, "hasil_hapus");
						perawatTable.ajax.reload();
					} else {
						console.log(response);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var tindakan_konsultasi = $("#tindakan_konsultasi").val();
			
			if(nama != "" && tindakan_konsultasi != "none") {
				$("#btnSubmit").attr("disabled", "disabled");
				$.ajax({
					async: false,
					url:__HOSTAPI__ + "/Poli",
					data: {
						request: "edit_poli",
						uid: uid,
						nama: nama,
						tindakan_konsultasi: tindakan_konsultasi,
                        integrasi_bpjs_poli_kode: $("#txt_bpjs_poli").val(),
                        integrasi_bpjs_poli_nama: $("#txt_bpjs_poli option:selected").text()
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"POST",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							notification ("success", "Poli berhasil diupdate", 3000, "hasil_update");
							location.href = __HOSTNAME__ + "/master/poli";
						} else {
							$("#btnSubmit").removeAttr("disabled");
							console.log(response);
						}
					},
					error: function(response) {
						$("#btnSubmit").removeAttr("disabled");
						console.log(response);
					}
				});
			}
		});
	});
</script>