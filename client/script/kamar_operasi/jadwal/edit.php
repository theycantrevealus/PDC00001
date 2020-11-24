<script type="text/javascript">

    $(function(){
        let jadwalUID = __PAGES__[3];

        loadJenis();
        loadDokter();
        loadRuangan();
        loadJadwalPasien(jadwalUID);

        //submit data
        $("#form_add_jadwal").submit(function(){
            $("#btnSubmit").attr("disabled", "disabled");

            let jenis_operasi = $("#jenis_operasi").val();
            let tgl_operasi = $("#tgl_operasi").val();
            let jam_mulai = $("#jam_mulai").val();
            let jam_selesai = $("#jam_selesai").val();
            let ruang_operasi = $("#ruang_operasi").val();
            let dokter = $("#dokter").val();
            let operasi = $("#operasi").val();

            let form_data = {
                'request': 'edit_jadwal_operasi',
                'jenis_operasi' : jenis_operasi,
                'tgl_operasi' : tgl_operasi,
                'jam_mulai' : jam_mulai,
                'jam_selesai' : jam_selesai,
                'ruang_operasi' : ruang_operasi,
                'dokter' : dokter,
                'operasi' : operasi,
                'uid' : jadwalUID
            }

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/KamarOperasi",
                data: form_data,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response){
                    console.log(response);
                    if (response.response_package != null || response.response_package != undefined)
                    {
                        if (response.response_package.response_result > 0){
                            location.href = __HOSTNAME__ + '/kamar_operasi/jadwal';
                        } else {
                            alert('Gagal mengupdate jadwal');
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return false;
        });

    });

    function loadJenis()
    {

		$.ajax({
            url: __HOSTAPI__ + "/KamarOperasi/jenis_operasi",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                
                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data;

                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#jenis_operasi").append(selection);
	                }
                
                }

                $("#jenis_operasi").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });

    }

    function loadDokter()
    {
        $.ajax({
            url: __HOSTAPI__ + "/Pegawai/get_all_dokter",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                
                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data;

                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama_dokter);
	                    $("#dokter").append(selection);
	                }
                
                }

                $("#dokter").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadRuangan()
    {
        $.ajax({
            url: __HOSTAPI__ + "/Ruangan/ruangan",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){

                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data;

                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#ruang_operasi").append(selection);
	                }
                
                }

                $("#ruang_operasi").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadJadwalPasien(uid)
    {
        $.ajax({
            url: __HOSTAPI__ + `/KamarOperasi/get_jadwal_pasien_detail/${uid}`,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                
                if (response.response_package != null || response.response_package != undefined){
                    
                    let MetaData = response.response_package.response_data[0];
                    
                    $("#pasien").val(MetaData.pasien.nama);
                    $("#no_rm_pasien").val(MetaData.pasien.no_rm);
                    $("#nik_pasien").val(MetaData.pasien.nik);
                    $("#jenis_operasi").val(MetaData.jenis_operasi).trigger('change');
                    $("#tgl_operasi").val(MetaData.tgl_operasi);
                    $("#jam_mulai").val(MetaData.jam_mulai);
                    $("#jam_selesai").val(MetaData.jam_selesai);
                    $("#ruang_operasi").val(MetaData.ruang_operasi).trigger('change');
                    $("#dokter").val(MetaData.dokter).trigger('change');
                    $("#operasi").val(MetaData.operasi);
                
                }

            },
            error: function(response) {
                console.log(response);
            }
        });
    }

</script>