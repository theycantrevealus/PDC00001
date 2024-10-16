<script type="text/javascript">
    
    $(function () {
        

        let tableAsesmen = $("#table_asesmen_operasi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/KamarOperasi/asesmen_operasi",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
                    var data = response.response_package.response_data;

                    // console.clear();
                    console.log(response);
                    
					return (data !== undefined && data !== null) ? data : [];
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
						return row.pasien;
					}
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.jadwal.jenis_operasi_detail.nama;
					}
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.jadwal.operasi;
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return "<span class=\"wrap_content\">" + row['dokter'] + "</span>";
					}
                },
                {
					"data" : null, render: function(data, type, row, meta) {
						return row.jadwal.penjamin;
					}
                },
                
				{
					"data" : null, render: function(data, type, row, meta) {
						let btn = "" +

									`<a class="btn btn-sm btn-info" href="${__HOSTNAME__}/kamar_operasi/asesmen/view/${row["uid"]}" data-toggle='tooltip' title='Lihat Asesmen'>` +
										`<span><i class="fa fa-eye"></i></span>` +
									`</a> `;
						
					

						return btn;
					}
				}
			]
		});

    });
</script>