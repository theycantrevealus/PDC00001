<script type="text/javascript">
  $(function() {
    var asesmenUID = __PAGES__[3];
    var dataJadwal = {}

    loadDokter();
    loadPerawat();

    console.log(asesmenUID);
    loadAsesmen(asesmenUID)
    //loadJadwalPasien(jadwalUID);

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
	                    $(".dokter").append(selection);
	                }
                
                }

                $(".dokter").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadPerawat()
    {
        $.ajax({
            url: __HOSTAPI__ + "/Pegawai/get_all_perawat_select2",
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
	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama_perawat);
	                    $(".perawat").append(selection);
	                }
                
                }

                $(".perawat").select2({});
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
  });

  function loadJadwalPasien(uid) {
        $.ajax({
            async: false,
            url: __HOSTAPI__ + `/KamarOperasi/get_jadwal_pasien_detail/${uid}`,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
                console.log(response);
                if (response.response_package != null || response.response_package != undefined) {
                    
                    let MetaData = response.response_package.response_data[0];

                    dataJadwal = MetaData;
                    
                    $("#pasien").val(MetaData.pasien.nama);
                    $("#no_rm_pasien").val(MetaData.pasien.no_rm);
                    $("#nik_pasien").val(MetaData.pasien.nik);
                    $("#jenis_operasi").val(MetaData.jenis_operasi).trigger('change');
                    $("#tgl_operasi").val(MetaData.tgl_operasi);
                    $("#jam_mulai").val(MetaData.jam_mulai);
                    $("#jam_selesai").val(MetaData.jam_selesai);
                    $("#ruang_operasi").val(MetaData.ruang_operasi).trigger('change');
                    $("#penjamin").val(MetaData.penjamin);
                    $("#dokter").val(MetaData.dokter).trigger('change');
                    $("#operasi").val(MetaData.operasi);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });


  }

  function loadAsesmen(uid) {
        $.ajax({
            async: false,
            url: __HOSTAPI__ + `/KamarOperasi/get_asesmen_detail/${uid}`,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response) {
                console.log(response);
                if (response.response_package != null || response.response_package != undefined) {
                    
                    let MetaData = response.response_package.response_data[0];

                    $("#pasien_nama").val(MetaData.pasien.nama);
                    $("#no_rm_pasien").val(MetaData.pasien.no_rm);
                    $("#nik_pasien").val(MetaData.pasien.nik);
                    $("#penjamin").val(MetaData.jadwal.penjamin);
                    $("#dokter").val(MetaData.dokter).trigger('change');

                    for(data in MetaData){
                        $('#'+data).val(MetaData[data]);
                    }

                    $('#dokter_anestesi option[value="'+MetaData.dokter_anestesi+'"]').attr('selected','selected');
                
                }
            },
            error: function(response) {
                console.log(response);
            }
        });


  }

</script>