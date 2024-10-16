<script type="text/javascript">
    $(function() {
		var dataHis = $("#table-his").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
			serverMethod: "POST",
            "order": [[ 1, "desc" ]],
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "lend_data";
					d.hist = "hist";
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var dataSet = response.response_package.response_data;
					var dataResponse = [];
					if(dataSet == undefined) {
						dataSet = [];
					}

                    console.log(dataSet);

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Nomor Peminjaman"
			},
			"columns" : [
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return row.kode;
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return (row.penerima !== null && row.penerima !== undefined) ? row.penerima.nama : "-";
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return row.created_at_parsed;
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return row.diajukan.nama;
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return (row.disetujui !== undefined && row.disetujui !== null && row.disetujui.nama !== undefined) ? row.disetujui.nama : "-";
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
						// var allowDel = (row.diajukan.uid === __ME__) ? "<button class=\"btn btn-danger btn-sm btnDelete\" id=\"delete_" + row.uid + "\">" +
						// 					"<span><i class=\"fa fa-times-circle\"></i>Delete</span>" +
						// 				"</button>" : "";

						// if(__MY_PRIVILEGES__.response_data[0].uid == __UIDKEPALAGUDANG__) {
						// 	return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                        //         "<a class=\"btn btn-sm btn-info\" href=\"" + __HOSTNAME__ + "/inventori/pinjam/detail/" + row.uid + "\">" +
                        //         "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                        //         "</a>" +
						// 		allowDel +
                        //         "<button class=\"btn btn-success btn-sm btnApprove\" id=\"approve_" + row.uid + "\">" +
                        //             "<span><i class=\"fa fa-check-circle\"></i>Approve</span>" +
                        //         "</button>" +
                        //     "</div>";
						// } else {
						// 	return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                        //         "<a class=\"btn btn-sm btn-info\" href=\"" + __HOSTNAME__ + "/inventori/pinjam/detail/" + row.uid + "\">" +
                        //         "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                        //         "</a>" +
						// 		allowDel +
                        //     "</div>";
						// }

						return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<a class=\"btn btn-sm btn-info\" href=\"" + __HOSTNAME__ + "/inventori/pinjam/detail/" + row.uid + "\">" +
                                "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                                "</a>" +
                            "</div>";
                	}
    			}
            ]
        });

        var dataList = $("#table-pinjam").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
			serverMethod: "POST",
            "order": [[ 1, "desc" ]],
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "lend_data";
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var dataSet = response.response_package.response_data;
					var dataResponse = [];
					if(dataSet == undefined) {
						dataSet = [];
					}

                    console.log(dataSet);

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Nomor Peminjaman"
			},
			"columns" : [
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return row.kode;
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return (row.penerima !== null && row.penerima !== undefined) ? row.penerima.nama : "-";
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return row.created_at_parsed;
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return row.diajukan.nama;
                	}
    			},
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
						var allowDel = (row.diajukan.uid === __ME__) ? "<button class=\"btn btn-danger btn-sm btnDelete\" id=\"delete_" + row.uid + "\">" +
											"<span><i class=\"fa fa-times-circle\"></i>Delete</span>" +
										"</button>" : "";

						if(__MY_PRIVILEGES__.response_data[0].uid == __UIDKEPALAGUDANG__) {
							return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<a class=\"btn btn-sm btn-info\" href=\"" + __HOSTNAME__ + "/inventori/pinjam/detail/" + row.uid + "\">" +
                                "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                                "</a>" +
								allowDel +
                                "<button class=\"btn btn-success btn-sm btnApprove\" id=\"approve_" + row.uid + "\">" +
                                    "<span><i class=\"fa fa-check-circle\"></i>Approve</span>" +
                                "</button>" +
                            "</div>";
						} else {
							return 	"<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<a class=\"btn btn-sm btn-info\" href=\"" + __HOSTNAME__ + "/inventori/pinjam/detail/" + row.uid + "\">" +
                                "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                                "</a>" +
								allowDel +
                            "</div>";
						}
                	}
    			}
            ]
        });

		$("body").on("click", ".btnApprove", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			Swal.fire({
				title: 'Approve peminjaman obat?',
				showDenyButton: true,
				confirmButtonText: `Ya`,
				denyButtonText: `Belum`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						async: false,
						url: __HOSTAPI__ + "/Inventori",
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type: "POST",
						data: {
							request: "approve_pinjam_keluar",
							uid: id
						},
						success: function(resp) {
							console.clear();
							console.log(resp.response_package);
							if(resp.response_package.response_result > 0) {
								Swal.fire(
									'Pengajuan Peminjaman Obat',
									'Pengajuan berhasil diapprove',
									'success'
								).then((result) => {
									dataList.ajax.reload();
									dataHis.ajax.reload();
								});
							} else {
								Swal.fire(
									'Pengajuan Peminjaman Obat',
									'Pengajuan gagal diapprove',
									'error'
								).then((result) => {
									
								});
							}
							$("#btnSubmitReturn").removeAttr("disabled").addClass("btn-success").removeClass("btn-danger");
						},
						error: function(resp) {
							console.clear();
							console.log(resp);
						}
					});
				}
			});
		});

		$("body").on("click", ".btnDelete", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			Swal.fire({
				title: 'Hapus peminjaman obat?',
				showDenyButton: true,
				confirmButtonText: `Ya`,
				denyButtonText: `Belum`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						async: false,
						url: __HOSTAPI__ + "/Inventori",
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type: "POST",
						data: {
							request: "hapus_pinjam_keluar",
							uid: id
						},
						success: function(resp) {
							console.clear();
							console.log(resp.response_package);
							if(resp.response_package.response_result > 0) {
								Swal.fire(
									'Pengajuan Peminjaman Obat',
									'Pengajuan berhasil dihapus',
									'success'
								).then((result) => {
									dataList.ajax.reload();
								});
							} else {
								Swal.fire(
									'Pengajuan Peminjaman Obat',
									'Pengajuan gagal dihapus',
									'error'
								).then((result) => {
									
								});
							}
							$("#btnSubmitReturn").removeAttr("disabled").addClass("btn-success").removeClass("btn-danger");
						},
						error: function(resp) {
							console.clear();
							console.log(resp);
						}
					});
				}
			});
		});
    });
</script>