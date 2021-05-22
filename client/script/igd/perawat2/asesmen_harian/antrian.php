<script type="text/javascript">

    $(function(){

        /*========================== START AREA PENGKAJIAN KULIT ============================*/
        //variabelnya: editorDiagnosisPerawat
        ClassicEditor
			.create( document.querySelector( '.txt_kaji_kulit_daftar_diagnosis_perawat' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Diagnosis perawat..."
			} )
			.then( editor => {
				editorDiagnosisPerawat = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );
        

         /*========================== END AREA PENGKAJIAN KULIT ============================*/
		

        
        /*========================== START AREA PEMERIKSAAN TANDA VITAL ============================*/
        $("input[name='vital_status_alergi']").on('change', function(){
        	let value = $(this).val();

        	hiddenRadioChild('tab_alergi', value, 1);
        });
		

        //variabelnya: editorAlergiObat
        ClassicEditor
			.create( document.querySelector( '.txt_vital_alergi_obat' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Sebutkan alergi obat dan reaksi..."
			} )
			.then( editor => {
				editorAlergiObat = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorAlergiMakanan
        ClassicEditor
			.create( document.querySelector( '.txt_vital_alergi_makanan' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Sebutkan alergi makanan dan reaksi..."
			} )
			.then( editor => {
				editorAlergiMakanan = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorAlergiLainnya
        ClassicEditor
			.create( document.querySelector( '.txt_vital_alergi_lainnya' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Sebutkan alergi lainnya dan reaksi..."
			} )
			.then( editor => {
				editorAlergiLainnya = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );
        

        $("input[name='vital_alergi_jam_ke_dokter_apoteker_dietisen']").on('change', function(){
        	let value = $(this).val();

        	disableRadioChild('vital_alergi_jam_ke_dokter_apoteker_dietisen', value, 1);
        });
        /*========================== END AREA PEMERIKSAAN TANDA VITAL ============================*/


        /*========================== START AREA SKRINNING FUNGSIONAL ============================*/
        $("input[name='skrining_tergantung_total']").on('change', function(){
        	let value = $(this).val();

        	disableRadioChild('skrining_tergantung_total_ke_dokter', value, 1);
        });


        /*========================== END AREA SKRINNING FUNGSIONAL ============================*/


        /*========================== START AREA PENGKAJIAN NYERI ============================*/
        //variabelnya: editorDiagnosisKejiNyeri
        ClassicEditor
			.create( document.querySelector( '.kaji_nyeri_diagnosa' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Diagnosis pengkajian nyeri..."
			} )
			.then( editor => {
				editorDiagnosisKejiNyeri = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );
        

         /*========================== END AREA PENGKAJIAN NYERI ============================*/
    });

	function hiddenRadioChild(child_selector, value, comparison_value){
		let $this = $("." + child_selector);

		if (value == comparison_value){
    		$this.removeAttr("hidden");
    	} else {
    		$this.attr("hidden",true);
    	}
	}

    function disableRadioChild(child_selector, value, comparison_value){
		let $this = $("." + child_selector);

		if (value == comparison_value){
    		$this.removeAttr("disabled");
    	} else {
    		$this.attr("disabled",true);
    	}
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