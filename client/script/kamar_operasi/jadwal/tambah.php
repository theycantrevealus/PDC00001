<script type="text/javascript">

    $(function(){

        loadJenis();
        loadDokter();
        loadRuangan();

        $(".pasien").select2({
            minimumInputLength: 3,
            placeholder: 'Ketik No. RM, NIK atau nama Pasien',
            ajax: {
                url: function (params){
                    return __HOSTAPI__ + `/KamarOperasi/get_pasien/${params.term}`;
                },
                headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
                type: "GET",
                dataType: 'json',
                processResults: function (data, page) {
                    console.log(data);
                    return {
                        results: data['response_package']['response_data']
                    }
                }
            }
        });

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Penjamin/penjamin",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData !== undefined && MetaData !== null){
                    for(var i in MetaData){
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                        $("#penjamin").append(selection);
                    }

                    $("#penjamin").select2();
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
        
        $('#pasien').on('select2:select', function (e) {
            let data = e.params.data;
            $("#nik_pasien").val(data.nik).attr({
                "kunjungan": data.uid_kunjungan
            });
            $("#no_rm_pasien").val(data.no_rm);
        });

        //submit data
        $("#form_add_jadwal").submit(function(){
            $("#btnSubmit").attr("disabled");

            let pasien = $("#pasien").val();
            let jenis_operasi = $("#jenis_operasi").val();
            let tgl_operasi = $("#tgl_operasi").val();
            let jam_mulai = $("#jam_mulai").val();
            let jam_selesai = $("#jam_selesai").val();
            let ruang_operasi = $("#ruang_operasi").val();
            let dokter = $("#dokter").val();
            let penjamin = $("#penjamin").val();
            let operasi = $("#operasi").val();
            let kunjungan = $("#nik_pasien").attr("kunjungan");

            let form_data = {
                'request': 'add_jadwal_operasi',
                'pasien' : pasien,
                'penjamin': penjamin,
                'jenis_operasi' : jenis_operasi,
                'tgl_operasi' : tgl_operasi,
                'jam_mulai' : jam_mulai,
                'jam_selesai' : jam_selesai,
                'ruang_operasi' : ruang_operasi,
                'dokter' : dokter,
                'kunjungan': kunjungan,
                'operasi' : operasi
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
                    if (response.response_package != null || response.response_package != undefined)
                    {
                        if (response.response_package.response_result > 0){
                            location.href = __HOSTNAME__ + '/kamar_operasi/jadwal';
                        } else {
                            alert('Gagal menambahkan jadwal');
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
                console.log(response);
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
                console.log(response);
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
                console.log(response);
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

</script>