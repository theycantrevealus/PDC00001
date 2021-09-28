<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
	$(function() {
	    //load-kandungan-obat
		var MODE = "edit";
		var UID = __PAGES__[3];
		var selectedDariSatuanList = [];
		var invData;
		var editorKeterangan;

		/*ClassicEditor.create(document.querySelector("#txt_keterangan"), {
            extraPlugins: [ MyCustomUploadAdapterPlugin ],
            placeholder: "Keterangan Produk..."
        }).then(editor => {
            editorKeterangan = editor;
            window.editor = editor;
        })
        .catch( err => {
            //console.error( err.stack );
        });*/

		var imageResultPopulator = [];
		var selectedKategoriObat = [];

		$.ajax({
			url:__HOSTAPI__ + "/Inventori/item_detail/" + UID,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
			    console.clear();
			    console.log(response);
			    if(response.response_package.response_data !== undefined) {
					invData = response.response_package.response_data[0];
				}

				$("#txt_nama").val(invData.nama);
				$(".label_nama").html(invData.nama.toUpperCase());

				if(invData.kode_barang == undefined) {
                    $("#txt_kode").val("-");
                    $(".label_kode").html("-");
                } else {
                    $("#txt_kode").val(invData.kode_barang);
                    $(".label_kode").html(invData.kode_barang.toUpperCase());
                }

				load_kategori("#txt_kategori", invData.kategori);
				console.log(invData.kandungan);
				for(var kk = 0; kk < invData.kandungan.length; kk++) {
				    autoKandungan({
                        kandungan: invData.kandungan[kk].kandungan,
                        keterangan: invData.kandungan[kk].keterangan
                    });
                }
				autoKandungan();

				$(".label_kategori").html($("#txt_kategori").find("option:selected").text().toUpperCase());
				load_manufacture("#txt_manufacture", invData.manufacture);
				load_satuan("#txt_satuan_terkecil", invData.satuan_terkecil);
				if(selectedDariSatuanList.indexOf($("#txt_satuan_terkecil").val()) < 0) {
					selectedDariSatuanList.push($("#txt_satuan_terkecil").val());
				}

				for(var a = 0; a < invData.konversi.length; a++) {
					autoSatuan(selectedDariSatuanList, {
						dari:invData.konversi[a].dari_satuan,
						ke:invData.konversi[a].ke_satuan,
						rasio:parseFloat(invData.konversi[a].rasio)
					});	
				}
				autoSatuan(selectedDariSatuanList);

				var hargaList = {};
				for(var b = 0; b < invData.penjamin.length; b++) {
					if(hargaList[invData.penjamin[b].penjamin] == undefined) {
						hargaList[invData.penjamin[b].penjamin] = {
							profit:invData.penjamin[b].profit,
							profit_type:invData.penjamin[b].profit_type
						};
					}
				}
				autoHarga(hargaList);

				for(var c = 0; c < invData.kategori_obat.length; c++) {
					if(selectedKategoriObat.indexOf(invData.kategori_obat[c].kategori) < 0) {
						selectedKategoriObat.push(invData.kategori_obat[c].kategori);
					}
				}
				load_kategori_obat(selectedKategoriObat);
				$(".load-kategori-obat-badge").html("");
				for(var b = 0; b < selectedKategoriObat.length; b++) {
					$(".load-kategori-obat-badge").append("<span style=\"margin:5px;\" class=\"badge badge-custom-caption badge-outline-success\"><i class=\"fa fa-tag\"></i>&nbsp;&nbsp;" + $("#label_kategori_obat_" + selectedKategoriObat[b]).html() + "</span>");
				}

				autoGudang(invData.lokasi, invData.monitoring);

				/*ClassicEditor.create(document.querySelector("#txt_keterangan"), {
					extraPlugins: [ MyCustomUploadAdapterPlugin ],
					placeholder: "Keterangan Produk..."
				}).then(editor => {
					editor.setData(invData.keterangan);
					editorKeterangan = editor;
					window.editor = editor;
				})
				.catch( err => {
					//console.error( err.stack );
				});*/
                ClassicEditor.create(document.querySelector("#txt_keterangan"), {
                    extraPlugins: [ MyCustomUploadAdapterPlugin ],
                    placeholder: "Keterangan Produk..."
                }).then(editor => {
                    editorKeterangan = editor;
                    editorKeterangan.setData(invData.keterangan);
                    window.editor = editor;
                })
                .catch( err => {
                    //console.error( err.stack );
                });

			}
		});

		$(".inv-tab-status").hide();

		var nama = $("#txt_nama").val();
		var kode = $("#txt_kode").val();
		var kategori = $("#txt_kategori").val();
		var manufacture = $("#txt_manufacture").val();
		

		$("#txt_kategori").select2();
		$("#txt_manufacture").select2();
		$("#txt_satuan_terkecil").select2();
		

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
		        hiJackImage( dataToPush );
		        return MyCust;
		    };
		}

		
		function hiJackImage(toHi) {
			imageResultPopulator.push(toHi);
		}

		//==========================================================CROPPER
		var targetCropper = $("#image-uploader");
		var basic = targetCropper.croppie({
			enforceBoundary:false,
			viewport: {
				width: 220,
				height: 220
			},
		});
		if(invData.image != undefined) {
			if(invData.image == false) {
				basic.croppie("bind", {
					zoom: 1,
					url: __HOST__ + "/assets/images/inventori/unset.png"
				});
			} else {
				basic.croppie("bind", {
					zoom: 1,
					url: __HOST__ + "/images/produk/" + UID + ".png"
				});
			}
		} else {
			basic.croppie("bind", {
				zoom: 1,
				url: __HOST__ + "/assets/images/inventori/unset.png"
			});
		}

		$("#upload-image").change(function() {
			readURL(this, basic);
		});

		function readURL(input, cropper) {
			var url = input.value;
			var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
				var reader = new FileReader();

				reader.onload = function (e) {
					cropper.croppie('bind', {
						url: e.target.result
					});
					//$('#imageLoader').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
			else{
				//$('#img').attr('src', '/assets/no_preview.png');
			}
		}

		function load_satuan(target, selected = "", selectedData = []) {
			var satuanData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/satuan",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					satuanData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < satuanData.length; a++) {
						if(selectedData.indexOf(satuanData[a].uid) < 0) {
							$(target).append("<option " + ((satuanData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + satuanData[a].uid + "\">" + satuanData[a].nama + "</option>");
						} else {
							$(target).append("<option " + ((satuanData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + satuanData[a].uid + "\">" + satuanData[a].nama + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return satuanData;
		}

		function load_kategori_obat(checkedData = []) {
			var kategoriObatData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/kategori_obat",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					kategoriObatData = response.response_package.response_data;
					render_kategori_obat(kategoriObatData, checkedData);
				},
				error: function(response) {
					console.log(response);
				}
			});
			return kategoriObatData;
		}

		function load_kategori(target, selected = "") {
			var kategoriData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/kategori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					kategoriData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < kategoriData.length; a++) {
						$(target).append("<option " + ((kategoriData[a].uid == selected) ? "selected=\"selected\"" : "") +  " value=\"" + kategoriData[a].uid + "\">" + kategoriData[a].nama + "</option>");
					}
					$(".label_kategori").html($(target).find("option:selected").text().toUpperCase());
				},
				error: function(response) {
					console.log(response);
				}
			});
			return kategoriData;
		}

		function load_manufacture(target, selected = "") {
			var manufactureData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/manufacture",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					manufactureData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < manufactureData.length; a++) {
						$(target).append("<option " + ((manufactureData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + manufactureData[a].uid + "\">" + manufactureData[a].nama + "</option>");
					}
					$(".label_manufacture").html($(target).find("option:selected").text().toUpperCase());
				},
				error: function(response) {
					console.log(response);
				}
			});
			return manufactureData;
		}

		function render_kategori_obat(data, checked = []) {
			for(var key in data) {
				var newList =
				"<li style=\"margin-bottom: 10px;\">" +
					"<div class=\"custom-control custom-checkbox-toggle custom-control-inline mr-1\">" +
						"<input type=\"checkbox\" " + ((checked.indexOf(data[key].uid) > -1) ? "checked=\"checked\"" : "") + " id=\"kategori_obat_" + data[key].uid + "\" class=\"custom-control-input kategori_obat_selection\">" +
						"<label class=\"custom-control-label\" for=\"kategori_obat_" + data[key].uid + "\">Yes</label>" +
					"</div>" +
					"<label id=\"label_kategori_obat_" + data[key].uid + "\" for=\"kategori_obat_" + data[key].uid + "\" class=\"mb-0\">" + data[key].nama + "</label>" +
				"</li>";
				$("#load-kategori-obat").append(newList);
			}
		}

		function autoSatuan(selectedDariSatuanList, selected = {}) {
			$("#table-konversi-satuan tbody tr").removeClass("last-satuan");
			var newRowSatuan = document.createElement("TR");
			var newCellSatuanID = document.createElement("TD");
			var newCellSatuanDari = document.createElement("TD");
			var newCellSatuanKe = document.createElement("TD");
			var newCellSatuanRasio = document.createElement("TD");
			var newCellSatuanAksi = document.createElement("TD");

			var newSatuanDari = document.createElement("SELECT");
			$(newCellSatuanDari).append(newSatuanDari);
			load_satuan(newSatuanDari, selected.dari, selectedDariSatuanList);
			$(newSatuanDari).select2().addClass("satuan_dari");
			if(selectedDariSatuanList.indexOf($(newSatuanDari).val()) < 0) {
				selectedDariSatuanList.push($(newSatuanDari).val());
			}

			var newSatuanKe = document.createElement("SELECT");
			$(newCellSatuanKe).append(newSatuanKe);
			load_satuan(newSatuanKe, $("#txt_satuan_terkecil").val());
			$(newSatuanKe).select2().attr("disabled", "disabled").addClass("satuan_ke");

			var newSatuanRasio = document.createElement("INPUT");
			$(newCellSatuanRasio).append(newSatuanRasio);
			if(selected.rasio != undefined) {
				$(newSatuanRasio).val(selected.rasio);
			}
			$(newSatuanRasio).addClass("form-control").inputmask({
				alias: 'decimal',
				rightAlign: true,
				placeholder: "0.00",
				prefix: "",
				autoGroup: false,
				digitsOptional: true
			}).addClass("form-control satuan_rasio");

			var newSatuanDelete = document.createElement("BUTTON");
			$(newCellSatuanAksi).append(newSatuanDelete);
			$(newSatuanDelete).addClass("btn btn-sm btn-danger satuan_delete").html("<i class=\"fa fa-ban\"></i>");

			$(newRowSatuan).append(newCellSatuanID);
			$(newRowSatuan).append(newCellSatuanDari);
			$(newRowSatuan).append(newCellSatuanKe);
			$(newRowSatuan).append(newCellSatuanRasio);
			$(newRowSatuan).append(newCellSatuanAksi);
			$(newRowSatuan).addClass("last-satuan");
			if($(newSatuanDari).find("option").length > 0) {
				$("#table-konversi-satuan tbody").append(newRowSatuan);	
			}
			rebaseSatuan(selectedDariSatuanList);
		}

		function rebaseSatuan(selectedDariSatuanList) {
			$("#table-konversi-satuan tbody tr").each(function(e){
				var id = (e + 1);

				$(this).attr("id", "row_satuan_" + id);
				$(this).find("td:eq(0)").html(id);
				
				$(this).find("td:eq(1) select").attr("id", "satuan_dari_" + id);
				$(this).find("td:eq(2) select").attr("id", "satuan_ke_" + id);

				$(this).find("td:eq(3) input").attr("id", "satuan_rasio_" + id);
				checkSatuan(id, selectedDariSatuanList);
				$(this).find("td:eq(4) button").attr("id", "satuan_delete_" + id);
			});
		}

		function checkSatuan(id, selectedDari = []) {
			if($("#satuan_dari_" + id).val() == $("#satuan_ke_" + id).val()) {
				$("#satuan_rasio_" + id).attr("disabled", "disabled");
			} else {
				/*if(selectedDari.indexOf($("#satuan_dari_" + id).val()) < 0) {
					$("#satuan_rasio_" + id).removeAttr("disabled");
				} else {
					$("#satuan_rasio_" + id).attr("disabled", "disabled");
				}*/
				$("#satuan_rasio_" + id).removeAttr("disabled");
			}
		}

		function autoHarga(setData = {}) {
		    var penjaminData;
			$("#table-penjamin tbody tr").remove();
			$.ajax({
				url:__HOSTAPI__ + "/Penjamin/penjamin",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					penjaminData = response.response_package.response_data;
					for(var a = 0; a < penjaminData.length; a++) {
						var newHargaRow = document.createElement("TR");
						$(newHargaRow).attr({
							"id": "penjamin_harga_" + penjaminData[a].uid
						});
						var newCellPenjaminID = document.createElement("TD");
						var newCellPenjaminName = document.createElement("TD");
						var newCellPenjaminMarginType = document.createElement("TD");
						var newCellPenjaminHarga = document.createElement("TD");


						$(newCellPenjaminID).html(a + 1);
						$(newCellPenjaminName).html(penjaminData[a].nama);

						var newPenjaminMarginType = document.createElement("SELECT");
						$(newCellPenjaminMarginType).append(newPenjaminMarginType);
						//setData[penjaminData[a].uid].profit
						var profitTypeList = [
							{"P":"Percent"},
							{"A":"Amount"}
						];
						for(var profKey in profitTypeList) {
							for(var profValue in profitTypeList[profKey]) {
								if(setData[penjaminData[a].uid] !== undefined) {
									$(newPenjaminMarginType).append("<option " + ((profValue == setData[penjaminData[a].uid].profit_type) ? "selected=\"selected\"" : "") + " value=\"" + profValue + "\">" + profitTypeList[profKey][profValue] + "</option>");
								} else {
									$(newPenjaminMarginType).append("<option value=\"" + profValue + "\">" + profitTypeList[profKey][profValue] + "</option>");
								}
							}
						}
						$(newPenjaminMarginType).addClass("form-control").select2();

						var newPenjaminHarga = document.createElement("INPUT");
						$(newCellPenjaminHarga).append(newPenjaminHarga);
						$(newPenjaminHarga).addClass("form-control").inputmask({
							alias: 'currency',
							rightAlign: true,
							placeholder: "0.00",
							prefix: "",
							autoGroup: false,
							digitsOptional: true
						}).val((setData[penjaminData[a].uid] == undefined) ? 0 : setData[penjaminData[a].uid].profit);
						//console.log(setData[penjaminData[a].uid].profit);
						$(newHargaRow).append(newCellPenjaminID);
						$(newHargaRow).append(newCellPenjaminName);
						$(newHargaRow).append(newCellPenjaminName);
						$(newHargaRow).append(newCellPenjaminMarginType);
						$(newHargaRow).append(newCellPenjaminHarga);

						$("#table-penjamin tbody").append(newHargaRow);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return penjaminData;
		}


		function autoKandungan(setter = {
		    kandungan: "",
            keterangan: ""
        }) {
            $("#load-kandungan-obat tbody tr").removeClass("last_kandungan");

            var newKandunganRow = document.createElement("TR");

            var newKandunganID = document.createElement("TD");
            var newKandunganName = document.createElement("TD");
            var newKandunganKeterangan = document.createElement("TD");
            var newKandunganAksi = document.createElement("TD");

            $(newKandunganRow).append(newKandunganID);
            $(newKandunganRow).append(newKandunganName);
            var newKandunganNameInput = document.createElement("input");
            $(newKandunganNameInput).addClass("form-control kandungan-check").attr("placeholder", "Kandungan Obat");
            $(newKandunganName).append(newKandunganNameInput);
            $(newKandunganNameInput).val(setter.kandungan);

            $(newKandunganRow).append(newKandunganKeterangan);
            var newKandunganKeteranganInput = document.createElement("input");
            $(newKandunganKeteranganInput).addClass("form-control kandungan-check").attr("placeholder", "Keterangan");
            $(newKandunganKeterangan).append(newKandunganKeteranganInput);
            $(newKandunganKeteranganInput).val(setter.keterangan);

            $(newKandunganRow).append(newKandunganAksi);
            var newKandunganDelete = document.createElement("button");
            $(newKandunganDelete).addClass("btn btn-sm btn-danger btn-delete-kandungan").html("<i class=\"fa fa-trash\"></i>");
            $(newKandunganAksi).append(newKandunganDelete);


            $(newKandunganRow).addClass("last_kandungan");

            $("#load-kandungan-obat tbody").append(newKandunganRow);

            rebaseKandungan();
        }

        $("body").on("keyup", ".kandungan-check", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(
                $("#nama_kandungan_" + id).val() !== "" &&
                $("#row_kandungan_" + id).hasClass("last_kandungan")
            ) {
                autoKandungan();
            }
        });

		$("body").on("click", ".btn-delete-kandungan", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(!$("#row_kandungan_" + id).hasClass("last_kandungan")) {
                $("#row_kandungan_" + id).remove();
                rebaseKandungan();
            }
        });

        function rebaseKandungan() {
            $("#load-kandungan-obat tbody tr").each(function(e) {
                var currentID = (e + 1);
                $(this).attr("id", "row_kandungan_" + currentID);
                $(this).find("td:eq(0)").html(currentID);
                $(this).find("td:eq(1) input").attr("id", "nama_kandungan_" + currentID);
                $(this).find("td:eq(2) input").attr("id", "keterangan_kandungan_" + currentID);
                $(this).find("td:eq(3) button").attr("id", "delete_kandungan_" + currentID);
            });
        }



		function autoGudang(lokasi, monitoring) {
			var gudangData;
			$("#table-lokasi-gudang tbody tr").remove();
			$("#table-monitoring tbody tr").remove();
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					gudangData = response.response_package.response_data;
					for(var a = 0; a < gudangData.length; a++) {
						var newGudangRow = document.createElement("TR");
						$(newGudangRow).attr({
							"id": "gudang_" + gudangData[a].uid
						});
						var newCellGudangID = document.createElement("TD");
						var newCellGudangName = document.createElement("TD");
						var newCellGudangLokasi= document.createElement("TD");

						$(newCellGudangID).html((a + 1));
						$(newCellGudangName).html(gudangData[a].nama);

						var newGudangLokasi = document.createElement("INPUT");
						$(newCellGudangLokasi).append(newGudangLokasi);
						$(newGudangLokasi).addClass("form-control").val("");
						for(var b = 0; b < lokasi.length; b++) {
							if(gudangData[a].uid == lokasi[b].gudang) {
								$(newGudangLokasi).val(lokasi[b].rak);
							}	
						}
						

						$(newGudangRow).append(newCellGudangID);
						$(newGudangRow).append(newCellGudangName);
						$(newGudangRow).append(newCellGudangLokasi);

						$("#table-lokasi-gudang tbody").append(newGudangRow);

						//==============================MONITORING
						var newMonitoringRow = document.createElement("TR");
						$(newMonitoringRow).attr({
							"id": "monitoring_row_" + gudangData[a].uid
						});

						var newMonitoringCellGudangID = document.createElement("TD");
						var newMonitoringCellGudangName = document.createElement("TD");
						var newMonitoringCellGudangMinimum = document.createElement("TD");
						var newMonitoringCellGudangMaximum = document.createElement("TD");
						var newMonitoringCellGudangSatuan = document.createElement("TD");

						$(newMonitoringCellGudangID).html((a + 1));
						$(newMonitoringCellGudangName).html(gudangData[a].nama);

						var nilaiMin = 0;
                        var nilaiMax = 0;
                        if(monitoring.length > 0) {
                            if(gudangData[a].uid == monitoring[a].gudang) {
                                nilaiMin = parseFloat(monitoring[a].min);
                                nilaiMax = parseFloat(monitoring[a].max);
                            }
                        }
						
						var newMonitoringMinimum = document.createElement("INPUT");
						$(newMonitoringCellGudangMinimum).append(newMonitoringMinimum);
						$(newMonitoringMinimum).addClass("form-control").inputmask({
							alias: 'decimal',
							rightAlign: true,
							placeholder: "0.00",
							prefix: "",
							autoGroup: false,
							digitsOptional: true
						}).val(nilaiMin);

						var newMonitoringMaximum = document.createElement("INPUT");
						$(newMonitoringCellGudangMaximum).append(newMonitoringMaximum);
						$(newMonitoringMaximum).addClass("form-control").inputmask({
							alias: 'decimal',
							rightAlign: true,
							placeholder: "0.00",
							prefix: "",
							autoGroup: false,
							digitsOptional: true
						}).val(nilaiMax);

						$(newMonitoringCellGudangSatuan).html($("#txt_satuan_terkecil").find("option:selected").text());

						$(newMonitoringRow).append(newMonitoringCellGudangID);
						$(newMonitoringRow).append(newMonitoringCellGudangName);
						$(newMonitoringRow).append(newMonitoringCellGudangMinimum);
						$(newMonitoringRow).append(newMonitoringCellGudangMaximum);
						$(newMonitoringRow).append(newMonitoringCellGudangSatuan);
						$("#table-monitoring tbody").append(newMonitoringRow);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
		}
		

		//==========================================================DASAR
		function saveDasar() {
			var nama = $("#txt_nama").val();
			var kode = $("#txt_kode").val();
			var kategori = $("#txt_kategori").val();
			var manufacture = $("#txt_manufacture").val();
			var keterangan = editorKeterangan.getData();

			console.log(nama);
			console.log(kode);
			console.log(kategori);
			console.log(manufacture);
			console.log(keterangan);
		}

		

		$("body").on("change", ".kategori_obat_selection", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			if($(this).is(":checked")) {
				if(selectedKategoriObat.indexOf(id) < 0) {
					selectedKategoriObat.push(id)
				}
			} else {
				selectedKategoriObat.splice(selectedKategoriObat.indexOf(id), 1);
			}
			$(".load-kategori-obat-badge").html("");
			for(var b = 0; b < selectedKategoriObat.length; b++) {
				$(".load-kategori-obat-badge").append("<div style=\"margin:5px;\" class=\"badge badge-info\"><i class=\"fa fa-tag\"></i>&nbsp;&nbsp;" + $("#label_kategori_obat_" + selectedKategoriObat[b]).html() + "</div>");
			}
		});

		$("body").on("keyup", "#txt_kode", function() {
			$(".label_kode").html($(this).val().toUpperCase());
		});

		$("body").on("keyup", "#txt_nama", function() {
			$(".label_nama").html($(this).val().toUpperCase());
		});

		$("body").on("change", "#txt_manufacture", function() {
			$(".label_manufacture").html($(this).find("option:selected").text().toUpperCase());
		});

		$("body").on("change", "#txt_kategori", function() {
			$(".label_kategori").html($(this).find("option:selected").text().toUpperCase());
		});

		$("body").on("keyup", ".satuan_rasio", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			if($("#row_satuan_" + id).hasClass("last-satuan") && parseFloat($(this).inputmask("unmaskedvalue")) > 0) {
				autoSatuan(selectedDariSatuanList);
			}
		});

		$("body").on("change", ".satuan_dari", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkSatuan(id, selectedDariSatuanList);
			if(selectedDariSatuanList.indexOf($(this).val()) < 0) {
				if($(this).val() != null) {
					selectedDariSatuanList.push($(this).val());		
				}
			}
			
		});

		$("body").on("change", ".satuan_ke", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			checkSatuan(id, selectedDariSatuanList);
		});

		var settedImage;
		var currentTab;
		
		$('body').on('mouseover', 'a[data-toggle="tab"]', function (e) {
			basic.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (image) {
				settedImage = image;
			});
		});

		$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
			currentTab = $(this).attr("href");
		});


		$("body").on("click", ".saveData", function(){
			var nama = $("#txt_nama").val();
			var kode = $("#txt_kode").val();
			if(nama != "" && kode != "") {
				$(".action-panel").attr({
					"disabled": "disabled"
				});
				if(currentTab == "#tab-informasi" || currentTab == "#info-dasar-1" || currentTab == undefined) {
					basic.croppie('result', {
						type: 'canvas',
						size: 'viewport'
					}).then(function (image) {
						var kategori = $("#txt_kategori").val();
						var manufacture = $("#txt_manufacture").val();
						var keterangan = editorKeterangan.getData();
						var satuan_terkecil = $("#txt_satuan_terkecil").val();
						var listKategoriObat = selectedKategoriObat;
						
						var satuanKonversi = [];
						//Satuan
						$("#table-konversi-satuan tbody tr").each(function(){
							var dari = $(this).find("td:eq(1) select").val();
							var ke = $(this).find("td:eq(2) select").val();
							var rasio = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
							if(parseFloat(rasio) > 0 && dari != ke) {
								satuanKonversi.push({
									dari:dari,
									ke:ke,
									rasio: parseFloat(rasio)
								});
							}
						});

						var penjaminList = [];
						//Penjamin
						$("#table-penjamin tbody tr").each(function(){
							var id = $(this).attr("id").split("_");
							id = id[id.length - 1];
							var marginType = $(this).find("td:eq(2) select").val();
							var marginValue = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
							if(parseFloat(marginValue) > 0) {
								penjaminList.push({
									penjamin:id,
									marginType:marginType,
									marginValue:marginValue
								});
							}
						});

						var gudangMeta = [];
						//Rak Gudang
						$("#table-lokasi-gudang tbody tr").each(function(){
							var id = $(this).attr("id").split("_");
							id = id[id.length - 1];
							var lokasiRak = $(this).find("td:eq(2) input").val();
							gudangMeta.push({
								"gudang": id,
								"lokasi": lokasiRak
							});
						});

						var monitoring = [];
						//Monitoring
						$("#table-monitoring tbody tr").each(function(){
							var id = $(this).attr("id").split("_");
							id = id[id.length - 1];

							var min = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
							var max = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");

							if(parseFloat(min) > 0 && parseFloat(max) > 0) {
								monitoring.push({
									gudang:id,
									min:min,
									max:max
								});
							}
						});

						var kandungan = [];
						$("#load-kandungan-obat tbody tr").each(function() {
						    if(
						        !$(this).hasClass("last_kandungan") &&
                                $(this).find("td:eq(1) input").val() !== ""
                            ) {
						        kandungan.push({
                                    kandungan: $(this).find("td:eq(1) input").val(),
                                    keterangan: $(this).find("td:eq(2) input").val()
                                });
                            }
                        });

                        console.log("Image Mode");
                        console.log(monitoring);
						$.ajax({
							url:__HOSTAPI__ + "/Inventori",
							async:false,
							beforeSend: function(request) {
								request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
							},
							data:{
								request:"edit_item",
								uid:UID,
								kode:kode,
								nama:nama,
								image:image,
								kategori:kategori,
								keterangan:keterangan,
								manufacture:manufacture,
								satuan_terkecil:satuan_terkecil,
								listKategoriObat:listKategoriObat,
								satuanKonversi:satuanKonversi,
                                kandungan: kandungan,
								penjaminList:penjaminList,
								gudangMeta:gudangMeta,
								monitoring:monitoring
							},
							type:"POST",
							success:function(response) {
								if(response.response_package == 0) {
									notification ("success", "Data berhasil diproses", 3000, "hasil_tambah");
								} else {
									console.log(response);
								}
								$(".action-panel").removeAttr("disabled");
							},
							error: function(response) {
								$(".action-panel").removeAttr("disabled");
								console.clear();
								console.log(response);
							}
						});




					});
				} else {
					var kategori = $("#txt_kategori").val();
					var manufacture = $("#txt_manufacture").val();
					var keterangan = editorKeterangan.getData();
					var satuan_terkecil = $("#txt_satuan_terkecil").val();
					var listKategoriObat = selectedKategoriObat;
					
					var satuanKonversi = [];
					//Satuan
					$("#table-konversi-satuan tbody tr").each(function() {
						var dari = $(this).find("td:eq(1) select").val();
						var ke = $(this).find("td:eq(2) select").val();
						var rasio = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
						if(parseFloat(rasio) > 0 && dari != ke) {
							satuanKonversi.push({
								dari:dari,
								ke:ke,
								rasio: parseFloat(rasio)
							});
						}
					});

					var penjaminList = [];
					//Penjamin
					$("#table-penjamin tbody tr").each(function() {
						var id = $(this).attr("id").split("_");
						id = id[id.length - 1];
						var marginType = $(this).find("td:eq(2) select").val();
						var marginValue = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
						if(parseFloat(marginValue) > 0) {
							penjaminList.push({
								penjamin:id,
								marginType:marginType,
								marginValue:marginValue
							});
						}
					});

					var gudangMeta = [];
					//Rak Gudang
					$("#table-lokasi-gudang tbody tr").each(function(){
						var id = $(this).attr("id").split("_");
						id = id[id.length - 1];
						var lokasiRak = $(this).find("td:eq(2) input").val();
						gudangMeta.push({
							"gudang": id,
							"lokasi": lokasiRak
						});
					});

					var monitoring = [];
					//Monitoring
					$("#table-monitoring tbody tr").each(function() {
						var id = $(this).attr("id").split("_");
						id = id[id.length - 1];

						var min = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
						var max = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");

						if(parseFloat(min) > 0 && parseFloat(max) > 0) {
							monitoring.push({
								gudang:id,
								min:min,
								max:max
							});
						}
					});

                    var kandungan = [];
                    $("#load-kandungan-obat tbody tr").each(function() {
                        if(
                            !$(this).hasClass("last_kandungan") &&
                            $(this).find("td:eq(1) input").val() !== ""
                        ) {
                            kandungan.push({
                                kandungan: $(this).find("td:eq(1) input").val(),
                                keterangan: $(this).find("td:eq(2) input").val()
                            });
                        }
                    });
                    console.log("Non Image Mode");
					console.log(monitoring);
					$.ajax({
						url:__HOSTAPI__ + "/Inventori",
						async:false,
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						data:{
							request:"edit_item",
							uid:UID,
							kode:kode,
							nama:nama,
							image:settedImage,
							kategori:kategori,
							keterangan:keterangan,
							manufacture:manufacture,
							satuan_terkecil:satuan_terkecil,
							listKategoriObat:listKategoriObat,
							satuanKonversi:satuanKonversi,
                            kandungan: kandungan,
							penjaminList:penjaminList,
							gudangMeta:gudangMeta,
							monitoring:monitoring
						},
						type:"POST",
						success:function(response) {
							console.log(response);
							if(response.response_package == 0) {
								notification ("success", "Data berhasil diproses", 3000, "hasil_tambah");
							} else {
								notification ("danger", "Data gagal diproses", 3000, "hasil_tambah");
							}
							$(".action-panel").removeAttr("disabled");
						},
						error: function(response) {
							$(".action-panel").removeAttr("disabled");
							console.clear();
							console.log(response);
						}
					});
				}
			}
			$(".action-panel").removeAttr("disabled");
		});

	});
</script>