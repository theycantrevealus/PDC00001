<script type="text/javascript">
	$(function() {
		var poliList = <?php echo json_encode($_SESSION['poli']['response_data'][0]['poli']['response_data']); ?>;
		
		if(poliList.length > 1) {
			$("#change-poli").show();
			$("#current-poli").addClass("handy");
		} else {
			$("#change-poli").hide();
			$("#current-poli").removeClass("handy");
		}

		$("#current-poli").prepend(poliList[0]['nama']);

		function load_poli_info() {
			//
		}

		var tindakanMeta = generateTindakan(poliList[0].tindakan);
		var usedTindakan = [];

		function generateTindakan(poliList, selected = []) {
			var tindakanMeta = {};
			$("#txt_tindakan option").remove();
			for(var key in poliList) {
				if(tindakanMeta[poliList[key].uid_tindakan] === undefined) {
					tindakanMeta[poliList[key].uid_tindakan] = [];	
					tindakanMeta[poliList[key].uid_tindakan].nama = poliList[key].tindakan.nama;
				}

				if(poliList[key].penjamin != undefined){
					tindakanMeta[poliList[key].uid_tindakan].push({
						uid: poliList[key].uid_penjamin,
						nama: poliList[key].penjamin.nama
					});
				}
			}

			for(var key in tindakanMeta) {
				if(selected.indexOf(key) < 0 && tindakanMeta[key].nama != undefined) {
					$("#txt_tindakan").append(
						"<option value=\"" + key + "\">" + tindakanMeta[key].nama + "</option>"
					);
				}
			}
			return tindakanMeta;
		}

		$("#txt_tindakan").select2();

		$("#btnTambahTindakan").click(function(){
			autoTindakan(tindakanMeta, {
				uid: $("#txt_tindakan").val(),
				nama: $("#txt_tindakan option:selected").text()
			});
			
			if(usedTindakan.indexOf($("#txt_tindakan").val()) < 0) {
				usedTindakan.push($("#txt_tindakan").val());
				tindakanMeta = generateTindakan(poliList[0].tindakan, usedTindakan);
			}
			
			return false;
		});

		$("body").on("click", ".btnDeleteTindakan", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			$("#row_tindakan_" + id).remove();
			usedTindakan.splice((id - 1), 1);
			tindakanMeta = generateTindakan(poliList[0].tindakan, usedTindakan);
			return false;
		});

		function autoTindakan(penjaminMeta, setTindakan) {
			var newRowTindakan = document.createElement("TR");
			var newCellTindakanID = document.createElement("TD");
			var newCellTindakanTindakan = document.createElement("TD");
			var newCellTindakanPenjamin = document.createElement("TD");
			var newCellTindakanAksi = document.createElement("TD");

			$(newCellTindakanTindakan).html(setTindakan.nama).attr({
				"set-tindakan": setTindakan.uid
			});

			var newPenjamin = document.createElement("SELECT");
			for(var a = 0; a < penjaminMeta[setTindakan.uid].length; a++) {
				$(newPenjamin).append("<option value=\"" + penjaminMeta[setTindakan.uid][a].uid + "\">" + penjaminMeta[setTindakan.uid][a].nama + "</option>");
			}
			$(newCellTindakanPenjamin).append(newPenjamin);
			$(newPenjamin).addClass("form-control").select2();
			

			var newPenjaminDelete = document.createElement("BUTTON");
			$(newPenjaminDelete).addClass("btn btn-sm btn-danger btnDeleteTindakan").html("<i class=\"fa fa-ban\"></i>");
			$(newCellTindakanAksi).append(newPenjaminDelete);

			$(newRowTindakan).append(newCellTindakanID);
			$(newRowTindakan).append(newCellTindakanTindakan);
			$(newRowTindakan).append(newCellTindakanPenjamin);
			$(newRowTindakan).append(newCellTindakanAksi);

			$("#table-tindakan").append(newRowTindakan);
			rebaseTindakan();
		}

		function rebaseTindakan() {
			$("#table-tindakan tbody tr").each(function(e) {
				var id = (e + 1);
				$(this).attr({
					"id": "row_tindakan_" + id
				});

				$(this).find("td:eq(0)").html(id);
				$(this).find("td:eq(3) button").attr({
					"id": "delete_tindakan_" + id
				});
			});
		}

		function load_icd_10(target) {
			var icd10Data;
			$.ajax({
				url:__HOSTAPI__ + "/Icd/icd10",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					icd10Data = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < icd10Data.length; a++) {
						$(target).append("<option value=\"" + icd10Data[a].uid + "\">" + icd10Data[a].kode + " - " + icd10Data[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return icd10Data;
		}


		load_icd_10("#txt_icd_10_kerja");
		load_icd_10("#txt_icd_10_banding");

		$("#txt_icd_10_kerja").select2();
		$("#txt_icd_10_banding").select2();



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



		//Init
		let editorKeluhanUtamaData, editorKeluhanTambahanData, editorPeriksaFisikData, editorKerja, editorBanding;

		ClassicEditor
			.create( document.querySelector( '#txt_keluhan_utama' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Keluhan Utama..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorKeluhanUtamaData = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

		ClassicEditor
			.create( document.querySelector( '#txt_keluhan_tambahan' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Keluhan Tambahan..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorKeluhanTambahanData = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

		ClassicEditor
			.create( document.querySelector( '#txt_pemeriksaan_fisik' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Pemeriksaan Fisik..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorPeriksaFisikData = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

		ClassicEditor
			.create( document.querySelector( '#txt_diagnosa_kerja' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Pemeriksaan Fisik..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorKerja = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );

		ClassicEditor
			.create( document.querySelector( '#txt_diagnosa_banding' ), {
				extraPlugins: [ MyCustomUploadAdapterPlugin ],
				placeholder: "Pemeriksaan Fisik..."
				/*ckfinder: {
					uploadUrl: __HOSTFRONT__ + "/api/Upload"
				}*/
			} )
			.then( editor => {
				editorBanding = editor;
				window.editor = editor;
			} )
			.catch( err => {
				//console.error( err.stack );
			} );


		function autoResep() {
			var newRowResep = document.createElement();
		}
	});
</script>