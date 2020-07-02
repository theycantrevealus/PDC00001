<script type="text/javascript">
	$(function() {

		var nama, kategori, manufacture, satuan_terkecil, konversi, varian, kode_barang, keterangan;
		var basic = $("#image-uploader").croppie({
			enforceBoundary:false,
			viewport: {
				width: 220,
				height: 220
			},
		});

		//Prepare Data Initiation
		$UID = __PAGES__[3];
		$.ajax({
			url:__HOSTAPI__ + "/Inventori/item_detail/" + $UID,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				nama = response.response_package.response_data[0].nama;
				kode_barang = response.response_package.response_data[0].kode_barang;
				kategori = response.response_package.response_data[0].kategori;
				manufacture = response.response_package.response_data[0].manufacture;
				keterangan = response.response_package.response_data[0].keterangan;
				satuan_terkecil = response.response_package.response_data[0].satuan_terkecil;
				kombinasi = response.response_package.response_data[0].kombinasi;
				konversi = response.response_package.response_data[0].konversi;
				varian = response.response_package.response_data[0].varian;
				images = response.response_package.response_data[0].images;

				$("#txt_nama").val(nama);
				$("#txt_kode").val(kode_barang);
				$("#txt_keterangan").val(keterangan);
				load_kategori("#txt_kategori", selected = kategori);
				load_manufacture("#txt_manufacture", selected = manufacture);
				load_satuan("#txt_satuan_terkecil", selected = satuan_terkecil);

				basic.croppie("bind", {
					zoom: .5,
					url: (!images) ? __HOST__ + "/assets/images/inventori/unset.png" : __HOST__ + "/assets/images/inventori/" + UID + ".png"
				});

				//Auto Table Init


			},
			error: function(response) {
				console.log(response);
			}
		});



		function load_kategori(target, selected = "") {
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
						$(target).append("<option " + ((kategoriData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + kategoriData[a].uid + "\">" + kategoriData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		function load_manufacture(target, selected = "") {
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
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		function load_product(target, UID, selected = "") {
			var productData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					productData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < productData.length; a++) {
						$(target).append("<option " + ((productData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return productData;
		}

		function load_satuan(target, selected = "") {
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
						$(target).append("<option " + ((satuanData[a].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + satuanData[a].uid + "\">" + satuanData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return satuanData;
		}

		function kombinasiAuto() {
			//
		}

		function autoTable(target, tableIden, UID, columns = []) {
			$(target).find("tbody tr").removeClass("last-row");
			var nextID = $(target).find("tbody tr").length + 1;
			var rowContainer = document.createElement("TR");
			$(rowContainer).attr("id", tableIden + "_row_container_" + nextID).addClass("last-row");
			for(var cell = 0; cell < columns.length; cell++) {
				var cellContainer = document.createElement("TD");
				var uri = columns[cell].uri;
				var type = columns[cell].type;
				var inputType = columns[cell].inputType;
				var identifier = columns[cell].identifier;
				var db_col = columns[cell].db_col;

				if(type == "button") {
					var childEl = columns[cell].child;
					for(var zBut = 0; zBut < columns[cell].child.length; zBut++) {
						var cBut = document.createElement(type);
						$(cBut).addClass(columns[cell].child[zBut].class).html(columns[cell].child[zBut].caption).attr("id", columns[cell].child[zBut].identifier + nextID);
						$(cellContainer).append(cBut);
					}
				} else {
					var object_fill = document.createElement(type);
					if(cell == 0) {
						$(object_fill).html(nextID);
					}
					
					var targetData;
					if(uri != "") {
						$.ajax({
							url: uri,
							async:false,
							beforeSend: function(request) {
								request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
							},
							type:"GET",
							success:function(response) {
								targetData = response.response_package.response_data;
								//reload_satuan(object_fill, targetData);
							},
							error: function(response) {
								console.log(response);
							}
						});
					}

					if(columns[cell].data !== undefined && columns[cell].data.length > 0) {
						$(object_fill).find("option").remove();
						for(var zSel = 0; zSel < columns[cell].data.length; zSel++) {
							if(columns[cell].data[zSel].uid != UID) {
								$(object_fill).append("<option value=\"" + columns[cell].data[zSel].uid + "\">" + columns[cell].data[zSel].nama + "</option>");	
							}
						}
					} else {
					
					}

					if(type == "input") {
						if(inputType == "number") {
							$(object_fill).inputmask({
								alias: 'currency',
								rightAlign: true,
								placeholder: "0.00",
								prefix: "",
								autoGroup: false,
								digitsOptional: true
							});
						}
					}



					$(object_fill).attr("id", identifier + nextID);
					if(type == "select" || type == "input") {
						$(object_fill).addClass("form-control");
					}
					$(cellContainer).append(object_fill);
				}
				$(rowContainer).append(cellContainer);
			}
			$(target).find("tbody").append(rowContainer);

			rebase_table(target);
		}

		function rebase_table(target) {
			$(target).find("tbody tr").each(function(e) {
				var identifier = "";
				var id = $(this).attr("id").split("_");
				var getID = id[id.length - 1];
				id.splice((id.length - 1), 1);
				$(this).attr("id", id.join("_") + "_" + (e + 1));

				$(this).find("span").each(function(f) {
					if(f == 0) {
						$(this).html((e + 1));
					} else {
						//
					}
				});

				//select
				$(this).find("select").each(function(f) {
					var curSel = $(this).attr("id").split("_");
					curSel.splice((curSel.length - 1), 1);
					$(this).addClass(curSel.join("_")).attr("id", curSel.join("_") + "_" + (e + 1));	
					$(this).select2();
				});

				//button
				$(this).find("button").each(function(f) {
					var curBut = $(this).attr("id").split("_");
					curBut.splice((curBut.length - 1), 1);
					$(this).addClass(curBut.join("_")).attr("id", curBut.join("_") + "_" + (e + 1));	
				});

				//input
				$(this).find("input").each(function(f) {
					var curIn = $(this).attr("id").split("_");
					curIn.splice((curIn.length - 1), 1);
					$(this).addClass(curIn.join("_")).attr("id", curIn.join("_") + "_" + (e + 1));	
				});

				//checkSatuanKonversi(id);
			});
		}


		$("#txt_kategori").select2();
		$("#txt_manufacture").select2();
		$("#txt_satuan_terkecil").select2();

		


		$("#upload-image").change(function(){
			readURL(this, basic);
		});




		//Prepare Croppie
		

		

		/*basic.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (image) {*/





		function saveItem(__HOSTNAME__, __HOSTAPI__, MODE, stay = false) {
			//Halaman 1
			var nama = $("#txt_nama").val();
			var kategori = $("#txt_kategori").val();
			var kode = $("#txt_kode").val();
			var manufacture = $("#txt_manufacture").val();
			var keterangan = $("#txt_keterangan").val();
			var satuan_terkecil = $("#txt_satuan_terkecil").val();

			//Halaman 2
			var populate_konversi = [];
			var populate_varian = {};

			$("#table-konversi-satuan tbody tr").each(function(e) {
				if(!$("#konversi_satuan_row_container_" + (e + 1)).hasClass("last-row")) {
					var getDari = $("#dari_satuan_" + (e + 1)).val();
					var getKe = $("#ke_satuan_" + (e + 1)).val();
					var getRasio = $("#rasio_satuan_" + (e + 1)).inputmask("unmaskedvalue");
					populate_konversi.push({
						getDari:getDari,
						getKe:getKe,
						getRasio:getRasio
					});
				}
			});

			$(".varian_value_receptor").each(function() {
				var id = $(this).attr("id").split("_");
				var grouperSatuan = id[id.length - 2];
				var singleSatuan = id[id.length - 1];

				if($(this).val() != "") {
					if(!Array.isArray(populate_varian[grouperSatuan]) || populate_varian[grouperSatuan].length == 0) {
						populate_varian[grouperSatuan] = [];
					}

					if(!inArray($(this).val(), populate_varian[grouperSatuan])) {
						populate_varian[grouperSatuan].push($(this).val());
					}
				}
			});
			
			//Halaman 3
			var populate_harga = [];
		
			//Halaman 4
			var populate_lokasi = [];	

			//Halaman 5
			var populate_monitoring = [];

			/*
			
				1. Save data dapat dicicil. Paten la pokoknya 😍
				2. Minimal nama dan kode barang harus ada dulu 😠

			*/

			//if(nama != "" && kode != "") {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori",
					async:false,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request: MODE + "_item",
						uid:uid,
						save_mode:stay,
						segment_informasi:{
							nama:nama,
							kode:kode,
							kategori:kategori,
							satuan_terkecil:satuan_terkecil,
							manufacture:manufacture,
							keterangan:keterangan
						},
						segment_satuan: {
							populate_konversi:populate_konversi,
							populate_varian:populate_varian
						}
					},
					type:"POST",
					success:function(response) {
						console.log(response);
						if(stay) {
							MODE = "edit";
							$("#mode_item").html("Edit");
							uid = response.response_package.response_uid;
							if(response.response_package.response_error == 0) {
								notification ("success", "Data berhasil diproses", 3000, "hasil_tambah");	
							} else if(response.response_package.response_error > 0) {
								notification ("warning", "Terjadi kesalahan data, silahkan cek kembali data yang telah diinput", 3000, "hasil_tambah_error");
							} else {
								notification ("danger", JSON.stringify(response), 3000, "hasil_tambah_dev");
							}
						} else {
							location.href = __HOSTNAME__ + "/master/inventori";	
						}
						
					},
					error: function(response) {
						console.log(response);
					}
				});
			//}
		}


		//Prepare Saving Data
		$("#btn_save_data_stay").click(function() {
			saveItem(__HOSTNAME__, __HOSTAPI__, MODE, true);
			return false;
		});

		$("#btn_save_data").click(function(){
			saveItem(__HOSTNAME__, __HOSTAPI__, MODE);
			return false;
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

	});
</script>