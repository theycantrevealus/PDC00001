<script type="text/javascript">

    $(function(){


        /*========================== START AREA RESUME ASKEP ============================*/
        //variabelnya: editorResumeAskepObatDibawaPulang
        ClassicEditor
			.create( document.querySelector( '.resume_askep_obat_dibawa_pulang' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Sebutkan alergi obat dan reaksi..."
			} )
			.then( editor => {
				editorResumeAskepObatDibawaPulang = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        //variabelnya: editorResumeAskepCatatan
        ClassicEditor
			.create( document.querySelector( '.resume_askep_catatan' ), {
				//plugins : [ Autosave ],
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Sebutkan alergi obat dan reaksi..."
			} )
			.then( editor => {
				editorResumeAskepCatatan = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

        /*========================== END AREA RESUME ASKEP ============================*/
    }); 


    function MyCustomUploadAdapterPlugin( editor ) {
	    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
	        var MyCust = new MyUploadAdapter( loader );
	        var dataToPush = MyCust.imageList;
	        hiJackImage(dataToPush);
	        return MyCust;
	    };
	}
</script>