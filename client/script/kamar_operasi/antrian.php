<script type="text/javascript">
  $(function() {
    var jadwalUID = __PAGES__[2];
    var dataJadwal = {}

    loadDokter();
    loadPerawat();

    console.log(jadwalUID);
    loadJadwalPasien(jadwalUID);

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

  $('#btnSelesai').on('click',function(){

        let kunjungan = dataJadwal.kunjungan;
        let pasien = dataJadwal.pasien.uid;
        let dokter = dataJadwal.dokter;
        let jadwal = dataJadwal.uid;
        let operator = $("#operator").val();
        let asisten = $("#asisten").val();
        let instrumen = $("#instrumen").val();
        let macam_pembedahan = $("#macam_pembedahan").val();
        let urgensi = $("#urgensi").val();
        let luka_operasi = $("#luka_operasi").val();
        let diagnosa_pra_bedah = $("#diagnosa_pra_bedah").val();
        let tindakan_bedah = $("#tindakan_bedah").val();
        let diagnosa_pasca_bedah = $("#diagnosa_pasca_bedah").val();
        let ahli_bius = $("#ahli_bius").val();
        let cara_bius = $("#cara_bius").val();
        let posisi_pasien = $("#posisi_pasien").val();
        let no_implant = $("#no_implant").val();
        let mulai = $("#mulai").val();
        let selesai = $("#selesai").val();
        let lama_jam = $("#lama_jam").val();
        let lama_menit = $("#lama_menit").val();
        let ok = $("#ok").val();
        let komplikasi = $("#komplikasi").val();
        let perdarahan = $("#perdarahan").val();
        let jaringan_patologi = $("#jaringan_patologi").val();
        let asal_jaringan = $("#asal_jaringan").val();

        //Installasi Bedah Sentral
        let operator_1 = $("#operator_1").val();
        let ket_operator_1 = $("#ket_operator_1").val();
        let operator_2 = $("#operator_2").val();
        let ket_operator_2 = $("#ket_operator_2").val();
        let dokter_anestesi = $("#dokter_anestesi").val();
        let ket_dokter_anestesi = $("#ket_dokter_anestesi").val();
        let dokter_anak = $("#dokter_anestesi").val();
        let ket_dokter_anak = $("#ket_dokter_anestesi").val();
        let penata_anestesi = $("#penata_anestesi").val();
        let ket_penata_anestesi = $("#ket_penata_anestesi").val();
        let perawat_ok_1 = $("#perawat_ok_1").val();
        let ket_perawat_ok_1 = $("#ket_perawat_ok_1").val();
        let perawat_ok_2 = $("#perawat_ok_2").val();
        let ket_perawat_ok_2 = $("#ket_perawat_ok_2").val();
        let perawat_ok_3 = $("#perawat_ok_3").val();
        let ket_perawat_ok_3 = $("#ket_perawat_ok_3").val();
        let perawat_ok_4 = $("#perawat_ok_4").val();
        let ket_perawat_ok_4 = $("#ket_perawat_ok_4").val();

        let form_data = {
            'request': 'add_laporan_bedah',
            'kunjungan': kunjungan,
            'pasien' : pasien,
            'dokter' : dokter,
            'jadwal' : jadwal,
            'operator': operator,
            'asisten': asisten,
            'instrumen' : instrumen,
            'macam_pembedahan' : macam_pembedahan,
            'urgensi' : urgensi,
            'luka_operasi' : luka_operasi,
            'diagnosa_pra_bedah': diagnosa_pra_bedah,
            'tindakan_bedah':tindakan_bedah,
            'diagnosa_pasca_bedah':diagnosa_pasca_bedah,
            'ahli_bius': ahli_bius,
            'cara_bius': cara_bius,
            'posisi_pasien' : posisi_pasien,
            'no_implant' : no_implant,
            'mulai' : mulai,
            'selesai' : selesai,
            'lama_jam': lama_jam,
            'lama_menit': lama_menit,
            'ok': ok,
            'komplikasi': komplikasi,
            'perdarahan': perdarahan,
            'jaringan_patologi': jaringan_patologi,
            'asal_jaringan': asal_jaringan,
            'operator_1': operator_1,
            'ket_operator_1': ket_operator_1,
            'operator_2': operator_2,
            'ket_operator_2': ket_operator_2,
            'dokter_anestesi': dokter_anestesi,
            'ket_dokter_anestesi': ket_dokter_anestesi,
            'dokter_anak' : dokter_anak,
            'ket_dokter_anak' : ket_dokter_anak,
            'penata_anestesi' : penata_anestesi,
            'ket_penata_anestesi' : ket_penata_anestesi,
            'perawat_ok_1' : perawat_ok_1,
            'ket_perawat_ok_1' : ket_perawat_ok_1,
            'perawat_ok_2' : perawat_ok_2,
            'ket_perawat_ok_2' : ket_perawat_ok_2,
            'perawat_ok_3' : perawat_ok_3,
            'ket_perawat_ok_3' : ket_perawat_ok_3,
            'perawat_ok_4' : perawat_ok_4,
            'ket_perawat_ok_4' : ket_perawat_ok_4
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
                    // console.log(response);
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

</script>