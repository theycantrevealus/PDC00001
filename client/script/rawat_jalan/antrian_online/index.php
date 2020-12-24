<script text="text/javascript">

    $(function(){

        var pasienList = $("#tabelAntrian").DataTable({
            //"processing": true,
            //"serverSide": true,
            "ajax":{
                "data":{
                    request: "get-reservation-data",
                },
                "dataSrc": "",
                "type":"POST",
                "url": "https://appsehat.rsudbintan.com/api/simrs.api.php",
            },
            "iDisplayLength": 50,
            aaSorting: [[3, "asc"]],
            "columnDefs":[
                {"targets":0, "searchable": true, "orderable": false,"className":"dt-body-left"}
            ],
            "columns" : [
                { 
                    "data": null,"sortable": false, render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["kode"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["nama_pasien"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["tanggal"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["nohp"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["penjamin"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["poli"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class='badge badge-" + row['warna_status'] + "'>" + row["status"] + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return '<div class="btn-group" role="group" aria-label="">' +
                                    `<button data-noktp="${row['noktp']}" data-id="${row['idreservasi']}" data-poli="${row['uid_poli']}" class="btn btn-sm btn-warning btn-xs btn_proses"><i class="fa fa-sign-in-alt"></i></button>` +
                                    //'<button type="button" rel="tooltip" class="btn btn-xs btn-danger btnPendaftaran" data-original-title="Hapus" title="Hapus" data-toggle="tooltip" data-placement="top" id="' + row['id'] + '"><i class="fa fa-trash"></i></button>' +
                                    `<button type="button" rel="tooltip" class="btn btn-sm btn-xs btn-danger btnHapus" data-original-title="Hapus" title="Hapus" data-toggle="tooltip" data-placement="top" data-id="${row['idreservasi']}"><i class="fa fa-trash"></i></button>` +
                                '</div>';
                    }
                }
            ]
        });

        $("#tabelAntrian tbody").on('click', '.btn_proses', function(){
            
            let noktp = $(this).data("noktp");
            let poli = $(this).data("poli");
            let idreservasi = $(this).data("id");

            if (noktp != "" && poli != "" && idreservasi != ""){

                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + `/AntrianOnline/cek_pasien/${noktp}`,
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "GET",
                    success: function(response){
                        
                        if (response.response_package != "" && response.response_package != undefined) {

                            if (response.response_package.response_result > 0){
                                
                                //console.log(response.response_package.response_data[0].uid);
                                location.href = `${__HOSTNAME__}/rawat_jalan/antrian_online/tambah_antrian/${idreservasi}/${response.response_package.response_data[0].uid}`;

                            } else {
                                
                                $("#btnLanjutkan").attr("data-idreservasi", idreservasi);
                                $("#label_nik").text(noktp);
                                $("#form_validasi").modal("show");
                                
                            }
                        
                        }
                        
                    },
                    error: function(response) {
                        console.clear();
                        console.log(response);
                    }
                });
            }

        });


        $("#btnLanjutkan").click(function(){

            let idreservasi = $(this).data("idreservasi");
            
            if (idreservasi != ""){
                location.href = __HOSTNAME__ + `/rawat_jalan/antrian_online/tambah_pasien/${idreservasi}`;
            }

        });
    });

</script>


<div id="form_validasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Konfirmasi Data Pasien<span class="title-term"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<p>
                        Pasien dengan NIK : <b> <span id="label_nik"></span> </b> merupakan pasien baru.
					    <br />Harap tambahkan data pasien terlebih dahulu sebelum menambahkan ke Antrian.
                    </p>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button data-noktp="" type="button" class="btn btn-primary" id="btnLanjutkan">Lanjutkan</button>
			</div>
		</div>
	</div>
</div>