<script type="text/javascript">
	$(function() {
		var poliList = <?php echo json_encode($_SESSION['poli']['response_data'][0]['poli']['response_data']); ?>;
		
		//Init
		let editorKeluhanUtamaData, editorKeluhanTambahanData, editorPeriksaFisikData, editorKerja, editorBanding, editorKeteranganResep, editorPlanning;
		
		var antrianData, asesmen_detail;

		var tindakanMeta = [];
		var usedTindakan = [];

		var UID = __PAGES__[3];		
		$.ajax({
			url:__HOSTAPI__ + "/Antrian/antrian-detail/" + UID,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				antrianData = response.response_package.response_data[0];
				$.ajax({
					url:__HOSTAPI__ + "/Asesmen/antrian-detail/" + UID,
					async:false,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"GET",
					success:function(response) {
						if(response.response_package.response_data[0] === undefined) {
							asesmen_detail = {};
							tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
						} else {
							asesmen_detail = response.response_package.response_data[0];
							if(asesmen_detail.tindakan !== undefined) {
								for(var tindakanKey in asesmen_detail.tindakan) {
									if(usedTindakan.indexOf(asesmen_detail.tindakan[tindakanKey].uid) < 0) {
										usedTindakan.push(asesmen_detail.tindakan[tindakanKey].uid);
										tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);

										autoTindakan(tindakanMeta, {
											uid: asesmen_detail.tindakan[tindakanKey].uid,
											nama: asesmen_detail.tindakan[tindakanKey].nama
										}, antrianData);
									}
								}
							} else {
								tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
							}
						}
						
						load_icd_10("#txt_icd_10_kerja", asesmen_detail.icd10_kerja);
						load_icd_10("#txt_icd_10_banding", asesmen_detail.icd10_banding);

						$("#txt_icd_10_kerja").select2();
						$("#txt_icd_10_banding").select2();
						
						ClassicEditor
							.create( document.querySelector( '#txt_keluhan_utama' ), {
								extraPlugins: [ MyCustomUploadAdapterPlugin ],
								placeholder: "Keluhan Utama..."
							} )
							.then( editor => {
								if(asesmen_detail.keluhan_utama === undefined) {
									editor.setData("");	
								} else {
									editor.setData(asesmen_detail.keluhan_utama);
								}
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
							} )
							.then( editor => {
								if(asesmen_detail.keluhan_tambahan === undefined) {
									editor.setData("");	
								} else {
									editor.setData(asesmen_detail.keluhan_tambahan);
								}
								editorKeluhanTambahanData = editor;
								window.editor = editor;
							} )
							.catch( err => {
								//console.error( err.stack );
							} );

						$("#txt_tekanan_darah").val(asesmen_detail.tekanan_darah);
						$("#txt_suhu").val(asesmen_detail.suhu);
						$("#txt_nadi").val(asesmen_detail.nadi);
						$("#txt_pernafasan").val(asesmen_detail.pernafasan);
						$("#txt_berat_badan").val(asesmen_detail.berat_badan);
						$("#txt_tinggi_badan").val(asesmen_detail.tinggi_badan);
						$("#txt_lingkar_lengan").val(asesmen_detail.lingkar_lengan_atas);

						ClassicEditor
							.create( document.querySelector( '#txt_pemeriksaan_fisik' ), {
								extraPlugins: [ MyCustomUploadAdapterPlugin ],
								placeholder: "Pemeriksaan Fisik..."
							} )
							.then( editor => {
								if(asesmen_detail.pemeriksaan_fisik === undefined) {
									editor.setData("");	
								} else {
									editor.setData(asesmen_detail.pemeriksaan_fisik);
								}
								editorPeriksaFisikData = editor;
								window.editor = editor;
							} )
							.catch( err => {
								//console.error( err.stack );
							} );

						ClassicEditor
							.create( document.querySelector( '#txt_diagnosa_kerja' ), {
								extraPlugins: [ MyCustomUploadAdapterPlugin ],
								placeholder: "Diagnosa Kerja..."
							} )
							.then( editor => {
								if(asesmen_detail.diagnosa_kerja === undefined) {
									editor.setData("");	
								} else {
									editor.setData(asesmen_detail.diagnosa_kerja);
								}
								editorKerja = editor;
								window.editor = editor;
							} )
							.catch( err => {
								//console.error( err.stack );
							} );

						ClassicEditor
							.create( document.querySelector( '#txt_diagnosa_banding' ), {
								extraPlugins: [ MyCustomUploadAdapterPlugin ],
							} )
							.then( editor => {
								if(asesmen_detail.diagnosa_banding === undefined) {
									editor.setData("");	
								} else {
									editor.setData(asesmen_detail.diagnosa_banding);
								}
								editorBanding = editor;
								window.editor = editor;
							} )
							.catch( err => {
								//console.error( err.stack );
							} );


						ClassicEditor
							.create( document.querySelector( '#txt_keterangan_resep' ), {
								extraPlugins: [ MyCustomUploadAdapterPlugin ],
								placeholder: "Keterangan resep..."
							} )
							.then( editor => {
								editorKeteranganResep = editor;
								window.editor = editor;
							} )
							.catch( err => {
								//console.error( err.stack );
							} );
						ClassicEditor
							.create( document.querySelector( '#txt_planning' ), {
								extraPlugins: [ MyCustomUploadAdapterPlugin ],
								placeholder: "Planning Tindakan"
							} )
							.then( editor => {
								if(asesmen_detail.planning === undefined) {
									editor.setData("");	
								} else {
									editor.setData(asesmen_detail.planning);
								}
								editorPlanning = editor;
								window.editor = editor;
							} )
							.catch( err => {
								//console.error( err.stack );
							} );
					},
					error: function(response) {
						console.log(response);
					}
				});
			},
			error: function(response) {
				console.log(response);
			}
		});
				

		if(poliList.length > 1) {
			$("#change-poli").show();
			$("#current-poli").addClass("handy");
		} else {
			$("#change-poli").hide();
			$("#current-poli").removeClass("handy");
		}

		$("#current-poli").prepend(poliList[0]['nama']);

		function generateTindakan(poliList, antrianData, selected = []) {
			var tindakanMeta = {};
			$("#txt_tindakan option").remove();
			for(var key in poliList) {
				if(tindakanMeta[poliList[key].uid_tindakan] === undefined) {
					tindakanMeta[poliList[key].uid_tindakan] = [];	
					tindakanMeta[poliList[key].uid_tindakan].nama = poliList[key].tindakan.nama;
				}

				if(poliList[key].penjamin != undefined){
					if(antrianData.penjamin == poliList[key].uid_penjamin) {
						tindakanMeta[poliList[key].uid_tindakan].push({
							uid: poliList[key].uid_penjamin,
							nama: poliList[key].penjamin.nama
						});
					}
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
			}, antrianData);
			
			if(usedTindakan.indexOf($("#txt_tindakan").val()) < 0) {
				usedTindakan.push($("#txt_tindakan").val());
				tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
			}
			
			return false;
		});

		$("body").on("click", ".btnDeleteTindakan", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			$("#row_tindakan_" + id).remove();
			usedTindakan.splice(usedTindakan.indexOf($(this).val()), 1);
			tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
			return false;
		});

		function autoTindakan(penjaminMeta, setTindakan, selectedPenjamin) {
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
				if(penjaminMeta[setTindakan.uid][a].uid == antrianData.penjamin) {
					$(newPenjamin).append("<option " + ((penjaminMeta[setTindakan.uid][a].uid == selectedPenjamin.penjamin) ? "selected=\"selected\"" : "") + " value=\"" + penjaminMeta[setTindakan.uid][a].uid + "\">" + penjaminMeta[setTindakan.uid][a].nama + "</option>");
				}
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

		function load_icd_10(target, selected = "") {
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
						$(target).append("<option " + ((icd10Data[a].id == selected) ? "selected=\"selected\"" : "") + " value=\"" + icd10Data[a].id + "\">" + icd10Data[a].kode + " - " + icd10Data[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return icd10Data;
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
			

		function load_product_penjamin(target, obat, selectedData = "") {
			var productData;
			$.ajax({
				/*url:__HOSTAPI__ + "/Penjamin/get_penjamin_obat/" + obat,*/
				url:__HOSTAPI__ + "/Penjamin/get_penjamin_obat/" + obat,
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target).find("option").remove();
					productData = response.response_package.response_data;
					for (var a = 0; a < productData.length; a++) {
						$(target).append("<option " + ((productData[a].penjamin.uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + penjaminData[a].penjamin.uid + "\">" + penjaminData[a].penjamin.nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return productData;
		}

		function load_product_resep(target, selectedData = "") {
			var selected = [];
			$("#table-resep tbody tr").each(function(){
				var getProductSelected = $(this).find("td:eq(1) select").val();
				if(selected.indexOf(getProductSelected) < 0) {
					selected.push(getProductSelected);
				}
			});

			var productData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target).find("option").remove();
					productData = response.response_package.response_data;
					console.log(productData);
					for (var a = 0; a < productData.length; a++) {
						if(selected.indexOf(productData[a].uid) < 0) {
							$(target).append("<option satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return (productData.length == selected.length);
		}

		checkGenerateResep();

		function checkGenerateResep(id = 0) {
			if($(".last-resep").length == 0) {
				autoResep();
			} else {
				var obat = $("#resep_obat_" + id).val();
				var jlh_hari = $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue");
				var signa_konsumsi = $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue");
				var signa_hari = $("#resep_signa_takar_" + id).inputmask("unmaskedvalue");

				if(
					parseFloat(jlh_hari) > 0 &&
					parseFloat(signa_konsumsi) > 0 &&
					parseFloat(signa_hari) > 0 &&
					obat != null &&
					$("#resep_row_" + id).hasClass("last-resep")
				) {
					autoResep();
				}
			}
		}

		function autoResep() {
			$("#table-resep tbody tr").removeClass("last-resep");
			var newRowResep = document.createElement("TR");
			$(newRowResep).addClass("last-resep");
			var newCellResepID = document.createElement("TD");
			var newCellResepObat = document.createElement("TD");
			var newCellResepJlh = document.createElement("TD");
			var newCellResepSatuan = document.createElement("TD");
			var newCellResepSigna1 = document.createElement("TD");
			var newCellResepSigna2 = document.createElement("TD");
			var newCellResepSigna3 = document.createElement("TD");
			var newCellResepPenjamin = document.createElement("TD");
			var newCellResepAksi = document.createElement("TD");

			var newObat = document.createElement("SELECT");
			$(newCellResepObat).append(newObat);

			var addAnother = load_product_resep(newObat, "");
			
			if(!addAnother) {
				$(newCellResepObat).append(
					"<div class=\"row\" style=\"padding-top: 20px;\">" +
						"<div class=\"col-md-7 aturan-pakai-container\"><span>Aturan Pakai</span></div>" +
						"<div class=\"col-md-5 kategori-obat-container\"><span>Kategori Obat</span></div>" +
					"</div>");
				var newAturanPakai = document.createElement("SELECT");
				$(newCellResepObat).find("div.aturan-pakai-container").append(newAturanPakai);
				$(newAturanPakai).addClass("form-control");
				$(newAturanPakai).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();

				$(newObat).addClass("form-control resep-obat").select2();

				$(newCellResepSatuan).html($(newObat).find("option:selected").attr("satuan-caption"));

				var newJumlah = document.createElement("INPUT");
				$(newCellResepJlh).append(newJumlah);
				$(newJumlah).addClass("form-control resep_jlh_hari").inputmask({
					alias: 'decimal',
					rightAlign: true,
					placeholder: "0.00",
					prefix: "",
					autoGroup: false,
					digitsOptional: true
				});

				var newKonsumsi = document.createElement("INPUT");
				$(newCellResepSigna1).append(newKonsumsi);
				$(newKonsumsi).addClass("form-control resep_konsumsi").attr({
					"placeholder": "3"
				}).inputmask({
					alias: 'decimal',
					rightAlign: true,
					placeholder: "0.00",
					prefix: "",
					autoGroup: false,
					digitsOptional: true
				});

				$(newCellResepSigna2).html("<i class=\"fa fa-times\"></i>");

				var newTakar = document.createElement("INPUT");
				$(newCellResepSigna3).append(newTakar);
				$(newTakar).addClass("form-control resep_takar").attr({
					"placeholder": "1"
				}).inputmask({
					alias: 'decimal',
					rightAlign: true,
					placeholder: "0.00",
					prefix: "",
					autoGroup: false,
					digitsOptional: true
				});

				
				var newDeleteResep = document.createElement("BUTTON");
				$(newCellResepAksi).append(newDeleteResep);
				$(newDeleteResep).addClass("btn btn-sm btn-danger resep_delete").html("<i class=\"fa fa-ban\"></i>");

				$(newRowResep).append(newCellResepID);
				$(newRowResep).append(newCellResepObat);
				$(newRowResep).append(newCellResepSigna1);
				$(newRowResep).append(newCellResepSigna2);
				$(newRowResep).append(newCellResepSigna3);
				$(newRowResep).append(newCellResepJlh);
				$(newRowResep).append(newCellResepSatuan);
				$(newRowResep).append(newCellResepAksi);
				$("#table-resep").append(newRowResep);	
				
				rebaseResep();
			}
		}

		function rebaseResep() {
			$("#table-resep tbody tr").each(function(e) {
				var id = (e + 1);

				$(this).attr({
					"id": "resep_row_" + id
				});
				$(this).find("td:eq(0)").html(id);
				$(this).find("td:eq(1) select").attr({
					"id": "resep_obat_" + id
				});
				$(this).find("td:eq(2) input:eq(0)").attr({
					"id": "resep_signa_konsumsi_" + id
				});
				$(this).find("td:eq(4) input:eq(0)").attr({
					"id": "resep_signa_takar_" + id
				});
				$(this).find("td:eq(5) input").attr({
					"id": "resep_jlh_hari_" + id
				});
				$(this).find("td:eq(6)").attr({
					"id": "resep_satuan_" + id
				});
				$(this).find("td:eq(7) buttton").attr({
					"id": "resep_delete_" + id
				});
			});
		}

		$("body").on("keyup", ".resep_konsumsi", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkGenerateResep(id);
		});

		$("body").on("keyup", ".resep_takar", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkGenerateResep(id);
		});

		$("body").on("keyup", ".resep_jlh_hari", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkGenerateResep(id);
		});

		$("body").on("change", ".resep-obat", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var satuanCaption = $(this).find("option:selected").attr("satuan-caption");
			$("#resep_satuan_" + id).html(satuanCaption);
		});







		function populateAllData() {
			//PREPARE FOR SAVE DATA
			var keluhanUtamaData = editorKeluhanUtamaData.getData();
			var keluhanTambahanData = editorKeluhanTambahanData.getData();
			var tekananDarah = $("#txt_tekanan_darah").val();
			var nadi = $("#txt_nadi").val();
			var suhu = $("#txt_suhu").val();
			var pernafasan = $("#txt_pernafasan").val();
			var beratBadan = $("#txt_berat_badan").val();
			var tinggiBadan = $("#txt_tinggi_badan").val();
			var lingkarLengan = $("#txt_lingkar_lengan").val();
			var pemeriksaanFisikData = editorPeriksaFisikData.getData();
			var icd10kerja = $("#txt_icd_10_kerja").val();
			var icd10Banding = $("#txt_icd_10_banding").val();
			var icd10KerjaData = editorKerja.getData();
			var icd10BandingData = editorBanding.getData();
			var planningData = editorPlanning.getData();

			var tindakan = [];
			$("#table-tindakan tbody tr").each(function() {
				var tindakanItem = $(this).find("td:eq(1)").attr("set-tindakan");
				var pilihanPenjamin = $(this).find("td:eq(2) select").val();
				tindakan.push({
					"item": tindakanItem,
					"itemName": $(this).find("td:eq(1)").html(),
					"penjamin": pilihanPenjamin,
					"penjaminName": $(this).find("td:eq(2) select option:selected").text()
				});
			});

			var resep = [];
			$("#table-resep tbody tr").each(function() {
				var obat = $(this).find("td:eq(1) select").val();
				var signaKonsumsi = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
				var signaTakar = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");
				var signaHari = $(this).find("td:eq(5) input").inputmask("unmaskedvalue");
				var penjamin = $(this).find("td:eq(6) select").val();

				resep.push({
					"obat": obat,
					"signaKonsumsi": signaKonsumsi,
					"signaTakar": signaTakar,
					"signaHari": signaHari,
					"penjamin": penjamin
				});
			});

			var keteranganResep = editorKeteranganResep.getData();
		}

		$("#txt_tekanan_darah").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("#txt_nadi").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("#txt_suhu").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("#txt_pernafasan").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("#txt_berat_badan").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("#txt_tinggi_badan").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("#txt_lingkar_lengan").inputmask({
			alias: 'decimal',
			rightAlign: true,
			placeholder: "0.00",
			prefix: "",
			autoGroup: false,
			digitsOptional: true
		});

		$("body").on("click", "#btnSelesai", function() {
			var kunjungan = antrianData.kunjungan;
			var antrian = UID;
			var penjamin = antrianData.penjamin;
			var pasien = antrianData.pasien;
			var poli = antrianData.departemen;

			//POLI FORM
			var keluhanUtamaData = editorKeluhanUtamaData.getData();
			var keluhanTambahanData = editorKeluhanTambahanData.getData();
			var tekananDarah = $("#txt_tekanan_darah").inputmask("unmaskedvalue");
			var nadi = $("#txt_nadi").inputmask("unmaskedvalue");
			var suhu = $("#txt_suhu").inputmask("unmaskedvalue");
			var pernafasan = $("#txt_pernafasan").inputmask("unmaskedvalue");
			var beratBadan = $("#txt_berat_badan").inputmask("unmaskedvalue");
			var tinggiBadan = $("#txt_tinggi_badan").inputmask("unmaskedvalue");
			var lingkarLengan = $("#txt_lingkar_lengan").inputmask("unmaskedvalue");
			var pemeriksaanFisikData = editorPeriksaFisikData.getData();
			var icd10Kerja = $("#txt_icd_10_kerja").val();
			var icd10Banding = $("#txt_icd_10_banding").val();
			var diagnosaKerjaData = editorKerja.getData();
			var diagnosaBandingData = editorBanding.getData();
			var planningData = editorPlanning.getData();

			var tindakan = [];
			$("#table-tindakan tbody tr").each(function() {
				var tindakanItem = $(this).find("td:eq(1)").attr("set-tindakan");
				var pilihanPenjamin = $(this).find("td:eq(2) select").val();
				tindakan.push({
					"kunjungan": kunjungan,
					"antrian": antrian,
					"pasien": pasien,
					"poli": poli,
					"item": tindakanItem,
					"itemName": $(this).find("td:eq(1)").html(),
					"penjamin": pilihanPenjamin,
					"penjaminName": $(this).find("td:eq(2) select option:selected").text()
				});
			});

			var resep = [];
			$("#table-resep tbody tr").each(function() {
				var obat = $(this).find("td:eq(1) select").val();
				var signaKonsumsi = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
				var signaTakar = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");
				var signaHari = $(this).find("td:eq(5) input").inputmask("unmaskedvalue");
				var penjamin = $(this).find("td:eq(6) select").val();

				resep.push({
					"obat": obat,
					"signaKonsumsi": signaKonsumsi,
					"signaTakar": signaTakar,
					"signaHari": signaHari,
					"penjamin": penjamin
				});
			});

			var keteranganResep = editorKeteranganResep.getData();
			var formData = {
				request: "update_asesmen_medis",
				kunjungan: kunjungan,
				antrian: antrian,
				penjamin: penjamin,
				pasien: pasien,
				poli: poli,
				//==============================
				keluhan_utama: keluhanUtamaData,
				keluhan_tambahan: keluhanTambahanData,
				tekanan_darah: parseFloat(tekananDarah),
				nadi: parseFloat(nadi),
				suhu: parseFloat(suhu),
				pernafasan: parseFloat(pernafasan),
				berat_badan: parseFloat(beratBadan),
				tinggi_badan: parseFloat(tinggiBadan),
				lingkar_lengan_atas: parseFloat(lingkarLengan),
				pemeriksaan_fisik: pemeriksaanFisikData,
				icd10_kerja: parseInt(icd10Kerja),
				diagnosa_kerja: diagnosaKerjaData,
				icd10_banding: parseInt(icd10Banding),
				diagnosa_banding: diagnosaBandingData,
				planning: planningData,
				//==============================
				tindakan:tindakan
			};

			//Validation
			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Asesmen",
				data: formData,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					console.clear();
					if(response.response_package.response_result > 0) {
						notification ("success", "Asesmen Berhasil Disimpan", 3000, "hasil_tambah_dev");
					} else {
						notification ("danger", response.response_package, 3000, "hasil_tambah_dev");
					}
				},
				error: function(response) {
					console.clear();
					console.log(response);
				}
			});
			

			return false;
		});
	});
</script>