<script type="text/javascript">
	$(function() {

		//Init
		//$('#txt_id').inputmask('9999999999999999');
		var mySlider = new rSlider({
			target: "#txt_nrs",
			values: [0,1,2,3,4,5,6,7,8,9,10]
		});

		var rangeDefiner = [
			{
				text: "Tidak",
				merge: 1
			}, {
				text: "Ringan",
				merge: 2
			}, {
				text: "Sedang",
				merge: 3
			}, {
				text: "Berat",
				merge: 3
			}, {
				text: "Berat Sekali",
				merge: 1
			}
		];

		for(var pain in rangeDefiner) {
			var scaleStepper = document.createElement("DIV");
			$(scaleStepper).css({
				"width": (10 * rangeDefiner[pain].merge) + "%"
			}).addClass("scale-stepper").html("<small class=\"text-center\">" + rangeDefiner[pain].text.toUpperCase() + "</small>");
			$("#scale-loader-define").append(scaleStepper)
		}

		for(var a = 1; a <= 10; a++) {
			var scaleStepper = document.createElement("DIV");
			$(scaleStepper).css({
				"width": "10%"
			}).addClass("scale-stepper");
			$("#scale-loader").append(scaleStepper)
		}



		class MyUploadAdapter {
			static loader;
		    constructor( loader ) {
		        // CKEditor 5's FileLoader instance.
		        this.loader = loader;

		        // URL where to send files.
		        this.url = __HOSTAPI__ + "/Upload";

		        this.imageList = [];
		    }

		    // Starts the upload process.
		    upload() {
		        return new Promise( ( resolve, reject ) => {
		            this._initRequest();
		            this._initListeners( resolve, reject );
		            this._sendRequest();
		        } );
		    }

		    // Aborts the upload process.
		    abort() {
		        if ( this.xhr ) {
		            this.xhr.abort();
		        }
		    }

		    // Example implementation using XMLHttpRequest.
		    _initRequest() {
		        const xhr = this.xhr = new XMLHttpRequest();

		        xhr.open( 'POST', this.url, true );
		        xhr.setRequestHeader("Authorization", 'Bearer ' + <?php echo json_encode($_SESSION["admin_ciscard"]); ?>);
		        xhr.responseType = 'json';
		    }

		    // Initializes XMLHttpRequest listeners.
		    _initListeners( resolve, reject ) {
		        const xhr = this.xhr;
		        const loader = this.loader;
		        const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;

		        xhr.addEventListener( 'error', () => reject( genericErrorText ) );
		        xhr.addEventListener( 'abort', () => reject() );
		        xhr.addEventListener( 'load', () => {
		            const response = xhr.response;

		            //console.log(response);

		            if ( !response || response.error ) {
		                return reject( response && response.error ? response.error.message : genericErrorText );
		            }

		            // If the upload is successful, resolve the upload promise with an object containing
		            // at least the "default" URL, pointing to the image on the server.
		            resolve( {
		                default: response.url
		            } );
		        } );

		        if ( xhr.upload ) {
		            xhr.upload.addEventListener( 'progress', evt => {
		                if ( evt.lengthComputable ) {
		                    loader.uploadTotal = evt.total;
		                    loader.uploaded = evt.loaded;
		                }
		            } );
		        }
		    }


		    // Prepares the data and sends the request.
		    _sendRequest() {
		    	const toBase64 = file => new Promise((resolve, reject) => {
				    const reader = new FileReader();
				    reader.readAsDataURL(file);
				    reader.onload = () => resolve(reader.result);
				    reader.onerror = error => reject(error);
				});
				var Axhr = this.xhr;
				
				async function doSomething(fileTarget) {
					fileTarget.then(function(result) {
						var ImageName = result.name;

						toBase64(result).then(function(renderRes) {
							const data = new FormData();
							data.append( 'upload', renderRes);
							data.append( 'name', ImageName);
							Axhr.send( data );
						});
					});
				}

				var ImageList = this.imageList;

				this.loader.file.then(function(toAddImage) {

					ImageList.push(toAddImage.name);

				});
				
				this.imageList = ImageList;

				doSomething(this.loader.file);
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

		var imageResultPopulator = [];

		function hiJackImage(toHi) {
			imageResultPopulator.push(toHi);
		}




		let editorKeluhanData, editorDiagnosaData;

		ClassicEditor
			.create( document.querySelector( '#txt_keluhan' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Edit keluhan utama ..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorKeluhanData = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );


		ClassicEditor
			.create( document.querySelector( '#txt_diagnosa' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Edit subjective(anamnesa) ..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorDiagnosaData = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );
		
	});
</script>