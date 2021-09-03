<script type="text/javascript">

    $(function(){


		/*====================== START HALAMAN kaji_awal.php =======================*/
        //variabelnya: editorKeluhanUtama
        ClassicEditor
			.create( document.querySelector( '.txt_keluhan_utama' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Keluhan Utama..."
			} )
			.then( editor => {
				editorKeluhanUtama = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        
         //variabelnya: editorRiwayatSekarang
        ClassicEditor
			.create( document.querySelector( '.txt_riwayat_sekarang' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Riwayat Penyakit Sekarang..."
			} )
			.then( editor => {
				editorRiwayatSekarang = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        
        //variabelnya: editorSakitTerdahulu
        ClassicEditor
			.create( document.querySelector( '.txt_riwayat_sakit_terdahulu' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Riwayat Penyakit Terdahulu..."
			} )
			.then( editor => {
				editorSakitTerdahulu = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        
        //variabelnya: editorRiwayatPengobatan
        ClassicEditor
			.create( document.querySelector( '.txt_riwayat_pengobatan' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Riwayat Pengobatan..."
			} )
			.then( editor => {
				editorRiwayatPengobatan = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorSakitTerdahulu
        ClassicEditor
			.create( document.querySelector( '.txt_riwayat_sakit_keluarga' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Riwayat Penyakit Keluarga..."
			} )
			.then( editor => {
				editorSakitTerdahulu = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorRiwayatPengobatan
        ClassicEditor
			.create( document.querySelector( '.txt_riwayat_pekerjaan_sosial_ekonomi' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Riwayat Pekerjaan, Sosial Ekonomi, Kejiwaan, dan Kebiasaan..."
			} )
			.then( editor => {
				editorRiwayatPengobatan = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

		/*====================== END HALAMAN kaji_awal.php =======================*/

		/*====================== START HALAMAN multi_organ.php =======================*/
        //variabelnya: editorMultiOrganKepalaLeher
        ClassicEditor
			.create( document.querySelector( '.txt_multi_organ_kepala_leher' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Kepala dan Leher..."
			} )
			.then( editor => {
				editorMultiOrganKepalaLeher = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );


        //variabelnya: editorMultiOrganParu
        ClassicEditor
			.create( document.querySelector( '.txt_multi_organ_paru' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Paru-paru..."
			} )
			.then( editor => {
				editorMultiOrganParu = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorMultiOrganJantung
        ClassicEditor
			.create( document.querySelector( '.txt_multi_organ_jantung' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Jantung..."
			} )
			.then( editor => {
				editorMultiOrganJantung = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorMultiOrganPerutPinggang
        ClassicEditor
			.create( document.querySelector( '.txt_perut_multi_organ_perut_pinggang' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Jantung..."
			} )
			.then( editor => {
				editorMultiOrganPerutPinggang = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorMultiOrganAnggotaGerak
        ClassicEditor
			.create( document.querySelector( '.txt_multi_organ_anggota_gerak' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Anggota Gerak..."
			} )
			.then( editor => {
				editorMultiOrganAnggotaGerak = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorMultiOrganGenitaliaAnus
        ClassicEditor
			.create( document.querySelector( '.txt_multi_organ_genitalia_anus' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Genatalia dan Anus..."
			} )
			.then( editor => {
				editorMultiOrganGenitaliaAnus = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

		/*====================== END HALAMAN multi_organ.php =======================*/
			
		/*====================== START HALAMAN rekonsiliasi_obat.php =======================*/

		$("input[type=\"radio\"][name=\"rekon_obat_penggunaan_obat\"]").change(function() {
			let idRadBtn = $(this).attr("id").split("_");
			console.log(idRadBtn);
			idRadBtn = idRadBtn[idRadBtn.length - 1];
			
			if(idRadBtn == "2") {
				$("#rekon_obat_penggunaan_obat_list").removeAttr("disabled");
			} else {
				$("#rekon_obat_penggunaan_obat_list").attr("disabled", "disabled").val("");
			}
		});

		//tambah baris ke table riwayat alergi obat
		var no_urut_riwayat_alergi_obat = 1;
		$("#btnTambahRiwayatObat").click(function(){
			let html = "";

			html = "<tr>\
					<td class='no_urut_riwayat_obat'></td>\
					<td><input class='form-control txt_riwayat_obat_alergi' id='txt_riwayat_obat_alergi_"+ no_urut_riwayat_alergi_obat +"' /></td>\
					<td>\
						<select class='form-control txt_derajat_alergi' id='txt_derajat_alergi_"+ no_urut_riwayat_alergi_obat +"'>\
							<option value='R'>Ringan (R)</option>\
							<option value='S'>Sedang (S)</option>\
							<option value='B'>Berat (B)</option>\
						</select>\
					</td>\
					<td><textarea class='form-control txt_reaksi_alergi' id='txt_reaksi_alergi_"+ no_urut_riwayat_alergi_obat +"'></textarea></td>\
					<td><button class='btn btn-danger btn-sm btnDeleteRiwayatAlergiObat'><i class='fa fa-trash'></i></button></td>\
				</tr>";

			$("#list-rekon-obat-alergi-obat").append(html);
			
			setNomorUrut('list-rekon-obat-alergi-obat', 'no_urut_riwayat_obat');
			no_urut_riwayat_alergi_obat++;
		});

		//hapus baris dari table riwayat alergi obat
		$("#list-rekon-obat-alergi-obat").on('click', '.btnDeleteRiwayatAlergiObat', function(){
			$(this).parent().parent().remove();
			setNomorUrut('list-rekon-obat-alergi-obat', 'no_urut_riwayat_obat');
		});


		//tambah baris ke table riwayat obat digunakan
		var no_urut_obat_digunakan = 1;
		$("#btnTambahObatDigunakan").click(function(){
			let html = "";

			html = "<tr>\
					<td class='no_urut_obat_digunakan'></td>\
					<td><input class='form-control txt_nama_obat_digunakan' id='txt_nama_obat_digunakan_"+ no_urut_obat_digunakan +"' /></td>\
					<td><input class='form-control txt_frekuensi_obat_digunakan' id='txt_frekuensi_obat_digunakan_"+ no_urut_obat_digunakan +"' /></td>\
					<td><input class='form-control txt_waktu_beri_terakhir_obat_digunakan' id='txt_waktu_beri_terakhir_obat_digunakan_"+ no_urut_obat_digunakan +"' /></td>\
					<td>\
						<select class='form-control txt_tindak_lanjut_obat_digunakan' id='txt_tindak_lanjut_obat_digunakan_"+ no_urut_obat_digunakan +"'>\
							<option value='1'>Lanjut Aturan Pakai Lama</option>\
							<option value='2'>Lanjut Aturan Pakai Berubah</option>\
							<option value='0'>Stop</option>\
						</select>\
					</td>\
					<td><textarea class='form-control txt_ubah_aturan_pakai_obat_digunakan' id='txt_ubah_aturan_pakai_obat_digunakan_"+ no_urut_obat_digunakan +"'></textarea></td>\
					<td><button class='btn btn-danger btn-sm btnDeleteObatDigunakan'><i class='fa fa-trash'></i></button></td>\
				</tr>";

			$("#list-rekon-obat-dipergunakan").append(html);
			
			setNomorUrut('list-rekon-obat-dipergunakan', 'no_urut_obat_digunakan');
			no_urut_obat_digunakan++;
		});

		//hapus baris dari table riwayat obat digunakan
		$("#list-rekon-obat-dipergunakan").on('click', '.btnDeleteObatDigunakan', function(){
			$(this).parent().parent().remove();
			setNomorUrut('list-rekon-obat-dipergunakan', 'no_urut_obat_digunakan');
		});

		/*====================== END HALAMAN rekonsiliasi_obat.php =======================*/
	});


	/*==================== UNIVERSAL FUNCTION =====================*/
	function setNomorUrut(table_name, no_urut_class){
		/*set dynamic serial number*/
		var rowCount = $("#"+ table_name +" tr").length;
		var table = $("#"+ table_name);
		$("."+ no_urut_class).html("");

		for (var i = 0, row; i < rowCount; i++) {
			//console.log()
			table.find('tr:eq('+ i +')').find('td:eq(0)').html(i);
		}
		/*--------*/
	}

    function MyCustomUploadAdapterPlugin( editor ) {
	    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
	        var MyCust = new MyUploadAdapter( loader );
	        var dataToPush = MyCust.imageList;
	        hiJackImage(dataToPush);
	        return MyCust;
	    };
	}

	

</script>