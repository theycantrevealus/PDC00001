<script type="text/javascript">

    $(function(){
        $("html,body").css({
			"overflow": "hidden"
		});

        $('.carousel:eq(0)').carousel({
			interval: 5000
		});

		$('.carousel:eq(1)').carousel({
			interval: 7000
		});


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
			"pageLength": 100,
			"searching": true,
			"bPaginate": false,
    		"bLengthChange": false,
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
                { 
					"data": null,"sortable": false, 
			    	render: function (data, type, row, meta) {
			            return meta.row + meta.settings._iDisplayStart + 1;
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
							return '<span class="badge badge-info">Akan dilaksakan</span>';
						} else if (status == 'P') {
							return '<span class="badge badge-warning">Sedang proses</span>';
						} else if (status == 'D') {
							return '<span class="badge badge-success">Sudah selesai</span>';
						}
						
					}
				}
			]
		});

    });

</script>