<script type="text/javascript">
    
    $(function (){

        let tableJadwal = $("#table_jadwal_operasi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/KamarOperasi/jadwal_operasi",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return "<h5 class=\"autonum\">" + (meta.row + meta.settings._iDisplayStart + 1) + "</h5>";
                	}  
    			},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['pasien'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['jenis_operasi'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['operasi'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['dokter'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['ruangan'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['tgl_operasi'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['jam_mulai'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row['jam_selesai'];
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						let status = row['status_pelaksanaan'];

						if (status == 'N') {
							return '<span class="badge badge-custom-caption badge-outline-info">Akan dilaksakan</span>';
						} else if (status == 'P') {
							return '<span class="badge badge-custom-caption badge-outline-warning">Sedang proses</span>';
						} else if (status == 'D') {
							return '<span class="badge badge-custom-caption badge-outline-success">Sudah selesai</span>';
						}
						
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						let btn = "";
						
						//BUTTON UNTUK STATUS PELAKSANAAN

						if (row['status_pelaksanaan'] == 'N') {
							//BUTTON TRANSAKSI (UNTUK EDIT DAN DELETE JADWAL)
							btn = "" +
								`<div class="btn-group col-md-12" role="group" aria-label="Basic example">` +
									`<a class="btn btn-info btn-sm btn_edit_jenis" href="${__HOSTNAME__}/kamar_operasi/jadwal/edit/${row["uid"]}" data-toggle='tooltip' title='Edit'>` +
										`<i class="fa fa-edit"></i>` +
									`</a> ` +
									`<button data-uid="${row['uid']}" class="btn btn-danger btn-sm btn_delete_jadwal" data-toggle="tooltip" title="Hapus">` +
										`<i class="fa fa-trash"></i>` +
									`</button>` +
                                    `<button class="btn btn-warning btn-sm btn_proses_jadwal" data-uid="${row["uid"]}" data-toggle='tooltip' title='Tandai Sedang Proses'>` +
                                        `<i class="fa fa-spinner"></i>` +
                                    `</button> ` +
								`</div>`;
						} else if (row['status_pelaksanaan'] == 'P'){
							btn = `<div><button data-uid="${row['uid']}" class="btn btn-success btn-sm btn_selesai_jadwal" data-toggle="tooltip" title="Tandai Selesai">` +
										`<i class="fa fa-check"></i>` +
									`</button>` +
								`</div>`;

						} else if (row['status_pelaksanaan'] == 'D') {
							btn = "";
						}

						return btn;
					}
				}
			]
		});

        $("#table_jadwal_operasi tbody").on('click', '.btn_delete_jadwal', function(){
            let uid = $(this).data("uid");


            Swal.fire({
                title: "Informasi Operasi",
                text: "Hapus jadwal operasi item?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/KamarOperasi/kamar_operasi_jadwal/" + uid,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"DELETE",
                        success:function(resp) {
                            tableJadwal.ajax.reload();
                        }
                    });
                }
            });
        });


		$("#table_jadwal_operasi tbody").on('click', '.btn_proses_jadwal', function(){
            let uid = $(this).data("uid");

			let form_data = {
				'request' : 'proses_jadwal_operasi',
				'uid' : uid
			};

            Swal.fire({
                title: "Informasi Operasi",
                text: "Proses operasi akan berlangsung?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/KamarOperasi",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: form_data,
                        success:function(resp) {
                            console.log(resp);
                            tableJadwal.ajax.reload();
                        }
                    });
                }
            });
        });


		$("#table_jadwal_operasi tbody").on('click', '.btn_selesai_jadwal', function(){
            let uid = $(this).data("uid");

			let form_data = {
				'request' : 'selesai_jadwal_operasi',
				'uid' : uid
			};

            //Todo: Masukkan penggunaan obat aktual

			var conf = confirm("Tandai operasi sudah selesai?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/KamarOperasi",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					data: form_data,
					success:function(resp) {
						tableJadwal.ajax.reload();
					}
				});
			} 
        });

    
    });

</script>
