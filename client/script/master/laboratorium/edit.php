<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
	$(function() {

		var UID = __PAGES__[3];
		var MODE = "edit";
		var labData;
		let editorKeterangan;
		$(".lab-tab-status").hide();
		var selectedKategori = [];
		var selectedLokasi = [];
		var selectedPenjamin = [];
		
		$.ajax({
			url:__HOSTAPI__ + "/Laboratorium/lab_detail/" + UID,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				labData = response.response_package.response_data[0];

				//Init Data
				$("#txt_kode_laboratorium").val(labData.kode);
				$("#txt_nama_laboratorium").val(labData.nama);
				if(labData.naratif === 'Y') {
				    $("#txt_hasil_naratif").prop("checked", true);
				    $("#nilai-lab tbody tr").each(function () {
				        $(this).find("td:eq(4) input")
                    });
                } else {
                    $("#txt_hasil_naratif").prop("checked", false);
                }

				if(labData.spesimen !== undefined && labData.spesimen !== null) {
                    load_spesimen("#txt_spesimen_laboratorium", labData.spesimen.uid);
                }
				$("#txt_spesimen_laboratorium").select2();
				ClassicEditor
					.create( document.querySelector( '#txt_keterangan' ), {
						extraPlugins: [ MyCustomUploadAdapterPlugin ],
						placeholder: "Keterangan..."
					} )
					.then( editor => {
						editorKeterangan = editor;
						editorKeterangan.setData(labData.keterangan);
						window.editor = editor;
					} )
					.catch( err => {
						//console.error( err.stack );
					} );
				
				for(var kat in labData.kategori) {
					autoKategori(selectedKategori, labData.kategori[kat].uid);
					selectedKategori.push(labData.kategori[kat].uid);
				}
				autoKategori(selectedKategori);
				
				for(var lok in labData.lokasi) {
					autoLokasi(selectedLokasi, labData.lokasi[lok].uid);
					selectedLokasi.push(labData.lokasi[lok].uid);
				}

				autoLokasi(selectedLokasi);
				for(var nil in labData.nilai) {
					autoNilai({
						"satuan": labData.nilai[nil].satuan,
						"keterangan": labData.nilai[nil].keterangan,
						"min": labData.nilai[nil].nilai_min,
						"max": labData.nilai[nil].nilai_maks,
                        "naratif": labData.naratif
					});
				}

				autoNilai({
                    "naratif": labData.naratif
                });


				for(var pen in labData.penjamin) {
					autoPenjamin(selectedPenjamin, labData.penjamin[pen].uid_penjamin, labData.penjamin[pen].harga);
					selectedPenjamin.push(labData.penjamin[pen].uid_penjamin);
				}
				autoPenjamin(selectedPenjamin);
			},
			error: function(response) {
				console.log(response);
			}
		});




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



		function load_spesimen(target, selected = "") {
			var spesimenData;
			$.ajax({
				url:__HOSTAPI__ + "/Laboratorium/spesimen",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					spesimenData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < spesimenData.length; a++) {
						$(target).append("<option " + ((spesimenData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + spesimenData[a].uid + "\">" + spesimenData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return spesimenData;
		}

		







		
		





		function load_kategori(target, selected = "") {
			var kategoriData;
			$.ajax({
				url:__HOSTAPI__ + "/Laboratorium/kategori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					kategoriData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});

			return kategoriData;
		}

		
		
		function autoKategori(selectedKategori = [], currentKategori = "") {
			var newRowKategori = document.createElement("TR");
			var newCellKategoriID = document.createElement("TD");
			var newCellKategoriItem = document.createElement("TD");
			var newCellKategoriAksi = document.createElement("TD");

			var newKategoriItem = document.createElement("SELECT");
			$(newCellKategoriItem).append(newKategoriItem);
			$(newKategoriItem).addClass("form-control kategori_selection");
			var kategoriData = load_kategori();
			$(newKategoriItem).append("<option value=\"none\">Pilih Kategori</option>");
			for(var katKey in kategoriData) {
				$(newKategoriItem).append("<option " + ((kategoriData[katKey].uid == currentKategori) ? "selected=\"selected\"" : "") + " value=\"" + kategoriData[katKey].uid + "\">" + kategoriData[katKey].nama + "</option>");
			}
			$(newKategoriItem).select2();
			var newKategoriDelete = document.createElement("BUTTON");
			$(newCellKategoriAksi).append(newKategoriDelete);
			$(newKategoriDelete).addClass("btn btn-danger btn-sm btn-delete-kategori").html("<i class=\"fa fa-ban\"></i>");

			$(newRowKategori).append(newCellKategoriID);
			$(newRowKategori).append(newCellKategoriItem);
			$(newRowKategori).append(newCellKategoriAksi);
			$("#kategori-lab tbody").append(newRowKategori);
			rebaseKategori();
		}

		function rebaseKategori(){
			$("#kategori-lab tbody tr").each(function(e) {
				var id = (e + 1);

				$(this).attr({
					"id": "row_kategori_lab_" + id
				}).removeClass("last-kategori");

				$(this).find("td:eq(0)").html(id);
				$(this).find("td:eq(1) select").attr({
					"id": "kategori_lab_" + id
				});
				$(this).find("td:eq(2) button").attr({
					"id": "delete_kategori_lab_" + id
				});
			});
			$("#kategori-lab tbody tr:last-child").addClass("last-kategori");
		}

		$("body").on("click", ".btn-delete-kategori", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(!$("#row_kategori_lab_" + id).hasClass("last-kategori")) {
				$("#row_kategori_lab_" + id).remove();
				rebaseKategori();
			}
		});

		$("body").on("change", ".kategori_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var current = $(this);
			if(current.val() != "none") {
				$(".kategori_selection").each(function() {
					var checkID = $(this).attr("id").split("_");
					checkID = checkID[checkID.length - 1];

					if($(this).find("option:selected").text() == current.find("option:selected").text() && checkID != id) {
						$("#row_kategori_lab_" + checkID).remove();
					}
				});;
				selectedKategori.push($(this).val());
				if($("#row_kategori_lab_" + id).hasClass("last-kategori")) {
					autoKategori(selectedKategori);	
				}
				
				rebaseKategori();
			}
		});























		function load_lokasi(target, selected = "") {
			var lokasiData;
			$.ajax({
				url:__HOSTAPI__ + "/Laboratorium/lokasi",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					lokasiData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});

			return lokasiData;
		}

		
		
		function autoLokasi(selectedLokasi = [], currentLokasi = "") {
			var newRowLokasi = document.createElement("TR");
			var newCellLokasiID = document.createElement("TD");
			var newCellLokasiItem = document.createElement("TD");
			var newCellLokasiAksi = document.createElement("TD");

			var newLokasiItem = document.createElement("SELECT");
			$(newCellLokasiItem).append(newLokasiItem);
			$(newLokasiItem).addClass("form-control lokasi_selection");
			var lokasiData = load_lokasi();
			$(newLokasiItem).append("<option value=\"none\">Pilih Lokasi</option>");
			for(var katKey in lokasiData) {
				$(newLokasiItem).append("<option " + ((lokasiData[katKey].uid == currentLokasi) ? "selected=\"selected\"" : "") + " value=\"" + lokasiData[katKey].uid + "\">" + lokasiData[katKey].nama + "</option>");
			}
			$(newLokasiItem).select2();
			var newLokasiDelete = document.createElement("BUTTON");
			$(newCellLokasiAksi).append(newLokasiDelete);
			$(newLokasiDelete).addClass("btn btn-danger btn-sm btn-delete-lokasi").html("<i class=\"fa fa-ban\"></i>");

			$(newRowLokasi).append(newCellLokasiID);
			$(newRowLokasi).append(newCellLokasiItem);
			$(newRowLokasi).append(newCellLokasiAksi);
			$("#lokasi-lab tbody").append(newRowLokasi);
			rebaseLokasi();
		}

		function rebaseLokasi(){
			$("#lokasi-lab tbody tr").each(function(e) {
				var id = (e + 1);

				$(this).attr({
					"id": "row_lokasi_lab_" + id
				}).removeClass("last-lokasi");

				$(this).find("td:eq(0)").html(id);
				$(this).find("td:eq(1) select").attr({
					"id": "lokasi_lab_" + id
				});
				$(this).find("td:eq(2) button").attr({
					"id": "delete_lokasi_lab_" + id
				});
			});
			$("#lokasi-lab tbody tr:last-child").addClass("last-lokasi");
		}

		$("body").on("click", ".btn-delete-lokasi", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(!$("#row_lokasi_lab_" + id).hasClass("last-lokasi")) {
				$("#row_lokasi_lab_" + id).remove();
				rebaseLokasi();
			}
		});

		$("body").on("change", ".lokasi_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var current = $(this);
			if(current.val() != "none") {
				$(".lokasi_selection").each(function() {
					var checkID = $(this).attr("id").split("_");
					checkID = checkID[checkID.length - 1];

					if($(this).find("option:selected").text() == current.find("option:selected").text() && checkID != id) {
						$("#row_lokasi_lab_" + checkID).remove();
					}
				});;
				selectedLokasi.push($(this).val());
				if($("#row_lokasi_lab_" + id).hasClass("last-lokasi")) {
					autoLokasi(selectedLokasi);	
				}
				
				rebaseLokasi();
			}
		});


		function autoNilai(setterNilai = {}) {
			var min = ((setterNilai.min === undefined) ? 0 : setterNilai.min);
			var max = ((setterNilai.max === undefined) ? 0 : setterNilai.max);
			var satuan = ((setterNilai.satuan === undefined) ? "-" : setterNilai.satuan);
			var keterangan = ((setterNilai.keterangan === undefined) ? "" : setterNilai.keterangan);
			var naratif = ((setterNilai.naratif === undefined) ? "N" : setterNilai.naratif);

			var newRowNilai = document.createElement("TR");
			var newCellNilaiID = document.createElement("TD");
			var newCellNilaiMax = document.createElement("TD");
			var newCellNilaiMin = document.createElement("TD");
			var newCellNilaiSatuan = document.createElement("TD");
			var newCellNilaiKeterangan = document.createElement("TD");
			var newCellNilaiAksi = document.createElement("TD");

			var newNilaiMin = document.createElement("INPUT");
			$(newCellNilaiMin).append(newNilaiMin);
			/*$(newNilaiMin).val(min).addClass("form-control nilai_min_selection").inputmask({
				alias: 'decimal',
				rightAlign: true,
				placeholder: "0.00",
				prefix: "",
				autoGroup: false,
				digitsOptional: true
			});*/

			var newNilaiMax = document.createElement("INPUT");
			$(newCellNilaiMax).append(newNilaiMax);
			/*$(newNilaiMax).val(max).addClass("form-control nilai_max_selection").inputmask({
				alias: 'decimal',
				rightAlign: true,
				placeholder: "0.00",
				prefix: "",
				autoGroup: false,
				digitsOptional: true
			});*/
			
			var newNilaiSatuan = document.createElement("INPUT");
			$(newNilaiSatuan).addClass("form-control");
			$(newCellNilaiSatuan).append(newNilaiSatuan);
			$(newNilaiSatuan).val(satuan).addClass("form-control nilai_satuan_selection");

            var newNilaiKeterangan = document.createElement("INPUT");
            /*if(naratif === 'Y') {
                newNilaiKeterangan = document.createElement("TEXTAREA");
            } else {
                newNilaiKeterangan = document.createElement("INPUT");
            }*/

            $(newNilaiKeterangan).addClass("form-control").attr({
                "placeholder": "Nama nilai pengujian"
            });

			$(newCellNilaiKeterangan).append(newNilaiKeterangan);
			$(newNilaiKeterangan).val(keterangan).addClass("form-control nilai_keterangan_selection");

			var newNilaiDelete = document.createElement("BUTTON");
			$(newCellNilaiAksi).append(newNilaiDelete);
			$(newNilaiDelete).addClass("btn btn-danger btn-sm btn-delete-nilai").html("<i class=\"fa fa-ban\"></i>");

			$(newRowNilai).append(newCellNilaiID);
			$(newRowNilai).append(newCellNilaiMin);
			$(newRowNilai).append(newCellNilaiMax);
			$(newRowNilai).append(newCellNilaiSatuan);
			$(newRowNilai).append(newCellNilaiKeterangan);
			$(newRowNilai).append(newCellNilaiAksi);
			$("#nilai-lab tbody").append(newRowNilai);
			rebaseNilai();
		}

		$("#txt_hasil_naratif").change(function () {
            /*$("#nilai-lab tbody tr").each(function () {

            });*/

            var thisValue = ($(this).is(":checked")) ? "Y" : "N";
            $.ajax({
                url:__HOSTAPI__ + "/Laboratorium",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: {
                    request: "update_naratif",
                    uid: UID,
                    target_value: thisValue
                },
                success:function(response) {
                    //
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

		function rebaseNilai(){
			$("#nilai-lab tbody tr").each(function(e) {
				var id = (e + 1);

				$(this).attr({
					"id": "row_nilai_lab_" + id
				}).removeClass("last-nilai");

				$(this).find("td:eq(0)").html(id);
				$(this).find("td:eq(1) input").attr({
					"id": "nilai_min_lab_" + id
				});
				$(this).find("td:eq(2) input").attr({
					"id": "nilai_max_lab_" + id
				});
				$(this).find("td:eq(3) input").attr({
					"id": "nilai_satuan_lab_" + id
				});
				$(this).find("td:eq(4) input").attr({
					"id": "nilai_keterangan_lab_" + id
				});
				$(this).find("td:eq(5) button").attr({
					"id": "delete_nilai_lab_" + id
				});
			});
			$("#nilai-lab tbody tr:last-child").addClass("last-nilai");
		}

		$("body").on("click", ".btn-delete-nilai", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(!$("#row_nilai_lab_" + id).hasClass("last-nilai")) {
				$("#row_nilai_lab_" + id).remove();
				rebaseNilai();
			}
		});

		$("body").on("keyup", ".nilai_max_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkNilaiAllow(id);
		});

		$("body").on("keyup", ".nilai_min_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkNilaiAllow(id);
		});

		$("body").on("keyup", ".nilai_satuan_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkNilaiAllow(id);
		});

		$("body").on("keyup", ".nilai_keterangan_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkNilaiAllow(id);
		});

		function checkNilaiAllow(id) {
			if(
				/*parseFloat($("#nilai_min_lab_" + id).val()) > 0 &&
				parseFloat($("#nilai_max_lab_" + id).val()) > 0 &&
				$("#nilai_satuan_lab_" + id).val() != "" &&*/
				$("#nilai_keterangan_lab_" + id).val() != "" &&
				$("#row_nilai_lab_" + id).hasClass("last-nilai")
			) {
				autoNilai();
			}
		}




















		function load_penjamin(target, selected = "") {
			var penjaminData;
			$.ajax({
				url:__HOSTAPI__ + "/Penjamin/penjamin",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					penjaminData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});

			return penjaminData;
		}
		
		function autoPenjamin(selectedPenjamin = [], currentPenjamin = "", currentHarga = 0) {
			var newRowPenjamin = document.createElement("TR");
			var newCellPenjaminID = document.createElement("TD");
			var newCellPenjaminItem = document.createElement("TD");
			var newCellPenjaminHarga = document.createElement("TD");
			var newCellPenjaminAksi = document.createElement("TD");

			var newPenjaminItem = document.createElement("SELECT");
			$(newCellPenjaminItem).append(newPenjaminItem);
			$(newPenjaminItem).addClass("form-control penjamin_selection");
			var penjaminData = load_penjamin();
			$(newPenjaminItem).append("<option value=\"none\">Pilih Penjamin</option>");
			for(var katKey in penjaminData) {
				$(newPenjaminItem).append("<option " + ((penjaminData[katKey].uid == currentPenjamin) ? "selected=\"selected\"" : "") + " value=\"" + penjaminData[katKey].uid + "\">" + penjaminData[katKey].nama + "</option>");
			}
			$(newPenjaminItem).select2();

			var newPenjaminHarga = document.createElement("INPUT");
			$(newCellPenjaminHarga).append(newPenjaminHarga);
			$(newPenjaminHarga).val(currentHarga).addClass("form-control").inputmask({
				alias: 'currency',
				rightAlign: true,
				placeholder: "0.00",
				prefix: "",
				autoGroup: false,
				digitsOptional: true
			});

			var newPenjaminDelete = document.createElement("BUTTON");
			$(newCellPenjaminAksi).append(newPenjaminDelete);
			$(newPenjaminDelete).addClass("btn btn-danger btn-sm btn-delete-penjamin").html("<i class=\"fa fa-ban\"></i>");

			$(newRowPenjamin).append(newCellPenjaminID);
			$(newRowPenjamin).append(newCellPenjaminItem);
			$(newRowPenjamin).append(newCellPenjaminHarga);
			$(newRowPenjamin).append(newCellPenjaminAksi);
			$("#penjamin-lab tbody").append(newRowPenjamin);
			rebasePenjamin();
		}

		function rebasePenjamin(){
			$("#penjamin-lab tbody tr").each(function(e) {
				var id = (e + 1);

				$(this).attr({
					"id": "row_penjamin_lab_" + id
				}).removeClass("last-penjamin");

				$(this).find("td:eq(0)").html(id);
				$(this).find("td:eq(1) select").attr({
					"id": "penjamin_lab_" + id
				});
				$(this).find("td:eq(2) button").attr({
					"id": "delete_penjamin_lab_" + id
				});
			});
			$("#penjamin-lab tbody tr:last-child").addClass("last-penjamin");
		}

		$("body").on("click", ".btn-delete-penjamin", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if(!$("#row_penjamin_lab_" + id).hasClass("last-penjamin")) {
				$("#row_penjamin_lab_" + id).remove();
				rebasePenjamin();
			}
		});

		$("body").on("change", ".penjamin_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			var current = $(this);
			if(current.val() != "none") {
				$(".penjamin_selection").each(function() {
					var checkID = $(this).attr("id").split("_");
					checkID = checkID[checkID.length - 1];

					if($(this).find("option:selected").text() == current.find("option:selected").text() && checkID != id) {
						$("#row_penjamin_lab_" + checkID).remove();
					}
				});;
				selectedPenjamin.push($(this).val());
				if($("#row_penjamin_lab_" + id).hasClass("last-penjamin")) {
					autoPenjamin(selectedPenjamin);	
				}
				
				rebasePenjamin();
			}
		});

		$("body").on("click", "#btnSubmit", function(){
			//Halaman 1
			var kode = $("#txt_kode_laboratorium").val();
			var nama = $("#txt_nama_laboratorium").val();
			var spesimen = $("#txt_spesimen_laboratorium").val();
			var keterangan = editorKeterangan.getData();
            var naratif = $("#txt_hasil_naratif").is(":checked");


			//Halaman 2
			var kategori = [];
			$("#kategori-lab tbody tr").each(function() {
				var kategoriData = $(this).find("td:eq(1) select").val();
				if(kategori.indexOf(kategoriData) < 0 && !$(this).hasClass("last-kategori")) {
					kategori.push(kategoriData);
				}
			});

			/*var lokasi = [];
			$("#lokasi-lab tbody tr").each(function() {
				var lokasiData = $(this).find("td:eq(1) select").val();
				if(lokasi.indexOf(lokasiData) < 0 && !$(this).hasClass("last-lokasi")) {
					lokasi.push(lokasiData);
				}
			});*/

			var nilai = [];
			$("#nilai-lab tbody tr").each(function() {
                /*var nilaiDataMin = $(this).find("td:eq(1) input").inputmask("unmaskedvalue");
                var nilaiDataMax = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");*/
                var nilaiDataMin = $(this).find("td:eq(1) input").val();
                var nilaiDataMax = $(this).find("td:eq(2) input").val();
				var nilaiDataSatuan = $(this).find("td:eq(3) input").val();
				var nilaiDataKeterangan = $(this).find("td:eq(4) input").val();

				if(!$(this).hasClass("last-nilai")) {
					nilai.push({
						"min" : nilaiDataMin,
						"max" : nilaiDataMax,
						"satuan": nilaiDataSatuan,
						"keterangan": nilaiDataKeterangan
					});
				}
			});

			/*var penjamin = [];
			$("#penjamin-lab tbody tr").each(function() {
				var nilaiPenjamin = $(this).find("td:eq(1) select").val();
				var nilaiPenjaminHarga = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
				if(!$(this).hasClass("last-penjamin")) {
					penjamin.push({
						"penjamin": nilaiPenjamin,
						"harga": nilaiPenjaminHarga
					});
				}
			});*/



			if(nama != "") {
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Laboratorium",
					data: {
						request: MODE + "_lab",
						uid: UID,
						kode: kode,
						nama: nama,
						spesimen: spesimen,
						keterangan: keterangan,
                        naratif: ((naratif) ? "Y" : "N"),
						kategori: kategori,
						//lokasi: lokasi,
						nilai: nilai,
						//penjamin: penjamin
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
                        console.log(response);
					    if(response.response_package.response_result > 0)
                        {
                            Swal.fire(
                                'Laboratorium',
                                'Data berhasil di update',
                                'success'
                            ).then((result) => {
                                location.href = __HOSTNAME__ + "/master/laboratorium";
                            });
                        }
					},
					error: function(response) {
						console.log(response);
					}
				});
			}

			return false;
		});
	});
</script>