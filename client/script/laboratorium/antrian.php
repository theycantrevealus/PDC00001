<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
	$(function(){
		var uid_order = __PAGES__[2];
		var order_data, tindakanID; 
		var nilaiItemTindakan = {};
		var fileList = [];
		var deletedDocList = [];	//for save all file uploaded
		var file;					//for upload file

        loadPasien(uid_order);
        if(__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) {
            $("#tanggal_sampling").attr("disabled", "disabled");
        } else {
            $("#tanggal_sampling").removeAttr("disabled");
        }
        loadLabOrderItem(uid_order);
		loadLampiran(uid_order);
        var form_data = new FormData();

        if(__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) {
            $(".tab-dokter").show();
            $("#btnSelesai").show();
        } else {
            $(".tab-dokter").hide();
            $("#btnSelesai").hide();
        }

        var mode_selesai = "N";

		$("#btnSelesai").click(function (e) {
            e.preventDefault();

            if(__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) {
                if(
                    $("#kesan").val() !== "" && $("#anjuran").val() !== ""
                ) {
                    Swal.fire({
                        title: 'Selesai pemeriksaan laboratorium?',
                        text: 'Setelah selesai, pemeriksaan sudah tidak dapat diedit lagi dan langsung menjadi riwayat pemeriksaan',
                        showDenyButton: true,
                        //showCancelButton: true,
                        confirmButtonText: `Ya`,
                        denyButtonText: `Belum`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            mode_selesai = "Y";
                            form_data.append("selesai", "Y");
                            $("#formHasilLab").submit();
                        } else if (result.isDenied) {
                            //
                        }
                    });
                } else {
                    Swal.fire(
                        "Pemeriksaan Gagal Disimpan!",
                        "Kesan Dan Anjuran Harus Diisi",
                        "error"
                    ).then((result) => {
                        //location.href = __HOSTNAME__ + "/laboratorium/proses";
                    });
                }
            } else {
                Swal.fire({
                    title: 'Selesai pemeriksaan laboratorium?',
                    text: 'Setelah selesai, pemeriksaan sudah tidak dapat diedit lagi dan langsung menjadi riwayat pemeriksaan',
                    showDenyButton: true,
                    //showCancelButton: true,
                    confirmButtonText: `Ya`,
                    denyButtonText: `Belum`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        mode_selesai = "Y";
                        form_data.append("selesai", "Y");
                        $("#formHasilLab").submit();
                    } else if (result.isDenied) {
                        //
                    }
                });
            }


            return false;
        });

        $("#btnSimpan").click(function (e) {
            e.preventDefault();
            if(__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) {
                if($("#kesan").val() !== "" && $("#anjuran").val() !== "") {
                    Swal.fire({
                        title: 'Simpan data pemeriksaan laboratorium?',
                        text: 'Simpan dahulu hasil pemeriksaan untuk di-edit lagi',
                        showDenyButton: true,
                        //showCancelButton: true,
                        confirmButtonText: `Ya`,
                        denyButtonText: `Belum`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            mode_selesai = "N";
                            form_data.append("selesai", "N");
                            $("#formHasilLab").submit();
                        } else if (result.isDenied) {
                            //
                        }
                    });
                } else {
                    Swal.fire(
                        "Pemeriksaan Gagal Disimpan!",
                        "Kesan Dan Anjuran Harus Diisi",
                        "error"
                    ).then((result) => {
                        //location.href = __HOSTNAME__ + "/laboratorium/proses";
                    });
                }
            } else {
                Swal.fire({
                    title: 'Simpan data pemeriksaan laboratorium?',
                    text: 'Simpan dahulu hasil pemeriksaan untuk di-edit lagi',
                    showDenyButton: true,
                    //showCancelButton: true,
                    confirmButtonText: `Ya`,
                    denyButtonText: `Belum`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        mode_selesai = "N";
                        form_data.append("selesai", "N");
                        $("#formHasilLab").submit();
                        push_socket(__ME__, "labor_petugas_selesai", "*", "Pemeriksaan Laboratorium Telah Diisi oleh Petugas Laboratorium", "info").then(function () {

                        });
                    } else if (result.isDenied) {
                        //
                    }
                });
            }


            return false;
        });

		$("#formHasilLab").submit(function() {
            $(".inputItemTindakan").each(function(){
                let get_id = $(this).attr("id").split("_");
                let id_nilai = get_id[get_id.length - 1];
                let uid_tindakan = get_id[get_id.length - 2];
                let nilai = $(this).val();


                if (uid_tindakan in nilaiItemTindakan){
                    nilaiItemTindakan[uid_tindakan][id_nilai] = nilai;
                } else {
                    nilaiItemTindakan[uid_tindakan] = {[id_nilai]: nilai};
                }
            });


            form_data.append("request", "update-hasil-lab");
            form_data.append("uid_order", uid_order);
            form_data.append("kesan", $("#kesan").val());
            form_data.append("anjuran", $("#anjuran").val());
            form_data.append('data_nilai', JSON.stringify(nilaiItemTindakan));
            form_data.append("tanggal_sampling", $("#tanggal_sampling").val());


            for(var i = 0; i < fileList.length; i++) {
                form_data.append("fileList[]", fileList[i]);
            }

            for (var i = 0; i < deletedDocList.length; i++){
                form_data.append("deletedDocList[]", deletedDocList[i]);
            }

            // for (var value of form_data.values()) {
            //    console.log(value);
            // }	`

            // let form_data = {
            // 	request : 'update-hasil-lab',
            // 	uid_order : uid_order,
            // 	data_nilai : nilaiItemTindakan
            // }

            //console.log(form_data);

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Laboratorium",
                processData: false,
                contentType: false,
                data: form_data,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response){
                    let order_detail = 0;
                    let response_upload = 0;
                    let response_delete_doc = 0;

                    if (response.response_package.order_detail !== undefined &&
                        response.response_package.order_detail !== ''
                    ){
                        order_detail = response.response_package.order_detail.response_result;
                    }

                    if (response.response_package.response_upload !== undefined &&
                        response.response_package.response_upload !== ''
                    ){
                        response_upload = response.response_package.response_upload.response_result;
                        if (response_upload > 0){
                            fileList = [];
                            $("#labor-lampiran-table tbody").empty();
                            loadLampiran(uid_order);
                        }
                    }

                    if (response.response_package.response_delete_doc !== undefined &&
                        response.response_package.response_delete_doc !== ''
                    ){
                        response_delete_doc = response.response_package.response_delete_doc.response_result;
                        if (response_delete_doc > 0){
                            deletedDocList = [];
                        }
                    }

                    if (order_detail > 0 || response_upload > 0 || response_delete_doc > 0) {
                        push_socket(__ME__, ((mode_selesai == "Y") ? "antrian_laboratorium_selesai" : "antrian_laboratorium_simpan"), "*", "Pemeriksaan Laboratorium Selesai", "warning").then(function() {
                            Swal.fire(
                                'Pemeriksaan Berhasil Disimpan!',
                                response.response_package.response_message,
                                'success'
                            ).then((result) => {
                                location.href = __HOSTNAME__ + "/laboratorium/proses";
                            });
                        });
                    } else {
                        notification ("danger", "Data Gagal Disimpan", 3000, "hasil_tambah_dev");
                    }
                },
                error: function(response) {
                    notification ("danger", "Data Gagal Disimpan", 3000, "hasil_tambah_dev");
                    console.log("Error : ");
                    console.log(response);
                }
            });
			return false;
		});

		$("#form-upload-lampiran").on("shown.bs.modal", function () {
			if (file.type == "application/pdf" && file != undefined) {
				var fileReader = new FileReader();
				fileReader.onload = function() {
					var pdfData = new Uint8Array(this.result);
					// Using DocumentInitParameters object to load binary data.
					var loadingTask = pdfjsLib.getDocument({
						data: pdfData
					});
					loadingTask.promise.then(function(pdf) {
						// Fetch the first page
						var pageNumber = 1;
						pdf.getPage(pageNumber).then(function(page) {
							var scale = 1.5;
							var viewport = page.getViewport({
								scale: scale
							});
							// Prepare canvas using PDF page dimensions
							var canvas = $("#pdfViewer")[0];
							var context = canvas.getContext('2d');
							canvas.height = viewport.height;
							canvas.width = viewport.width;
							// Render PDF page into canvas context
							var renderContext = {
								canvasContext: context,
								viewport: viewport
							};
							var renderTask = page.render(renderContext);
							renderTask.promise.then(function() {
								//$("#btnSubmit").removeAttr("disabled").html("Terima SK").removeClass("btn-warning").addClass("btn-primary");
							});
						});
					}, function(reason) {
						// PDF loading error
						console.error(reason);
					});
				};
				fileReader.readAsArrayBuffer(file);
			}
		});


		$("#add_file").change(function(e) {

			file = e.target.files[0];
			var fileSize = file.size / 1000000;
			if(fileSize <= __MAX_UPLOAD_FILE_SIZE__) {
                $("#form-upload-lampiran").modal("show");
            } else {
                Swal.fire(
                    "Upload dokumen Laboratorium",
                    "File tidak boleh melebihi 5MB. Harap kompresi file atau turunkan resolusi scan dokumen",
                    "warning"
                ).then((result) => {
                    //
                });
            }
		});

		$("#btnSubmitLampiran").click(function() {
			autoDocument(file);
			fileList.push(file);
			//check_page_2();
			$("#form-upload-lampiran").modal("hide");
		});

		$("body").on("click", ".delete_document", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			fileList.splice((id - 1), 1);
			$("#document_" + id).hide();
			rebaseLampiran();
			return false;
		});

		$("#labor-lampiran-table tbody").on('click', '.delete_document_registered', function(){
			var id = $(this).data("id").split("_");
			id = id[id.length - 1];

			deletedDocList.push(id);
			$(this).parent().parent().remove();
			rebaseLampiran();
			return false;
		});
	});

	function loadPasien(uid_order){		//uid_lab_order
		if (uid_order != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Laboratorium/get-data-pasien-antrian/" + uid_order,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
                    var MetaData = response.response_package;
                    if (Object.size(MetaData) > 0) {
                        if (MetaData.pasien !== "") {
	                	    $("#tanggal_sampling").val(MetaData.laboratorium.tanggal_sampling);
                            $("#kesan").val(MetaData.laboratorium.kesan);
                            $("#anjuran").val(MetaData.laboratorium.anjuran);
	                		$("#no_rm").html(MetaData.pasien.no_rm).attr({
                                "penjamin": MetaData.antrian.penjamin
                            });
	                		$("#tanggal_lahir").html(MetaData.pasien.tanggal_lahir_parsed);
	                		$("#panggilan").html(MetaData.pasien.panggilan_name.nama);
	                		$("#nama").html(MetaData.pasien.nama);
	                		$("#jenkel").html(MetaData.pasien.jenkel_detail.nama);
	                	}
	                }
	            },
	            error: function(response) {
	                console.log(response);
	            }
			});
		}
	}

	function loadLabOrderItem(params){	        //params = id lab_order_detail
		let dataItem;

		if (params != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-order-detail-item/" + params,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response) {
                    let html = "";
                    if (response.response_package.response_result > 0) {
                        var allow_save = [];
                        dataItem = response.response_package.response_data;

                        var autonumLab = 1;
                        $.each(dataItem, function(key, item) {
                            if(item.allow) {
                                allow_save.push(1);
                            } else {
                                allow_save.push(0);
                            }

                            if((item.invoice !== null && item.invoice !== undefined) || $("#no_rm").attr("penjamin") === __UIDPENJAMINBPJS__) {
                                html = "<div class=\"card\"><div class=\"card-header bg-white\">" +
                                    "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> " + (autonumLab) + ". "+ item.nama + " <strong class=\"pull-right badge badge-custom-caption badge-outline-info\"><i class=\"material-icons\">verified_user</i><b>&nbsp;" + item.mitra.nama + "</b></strong></h5>" +
                                    "</div><div class=\"card-body\">" +
                                    "<div class=\"row\">" +
                                    "<div class=\"col-12\">" +
                                    "Tanggal Ambil Sample : <b class=\"" + ((!item.allow) ? "text-danger" : "text-success") + "\">" + ((!item.allow) ? "<i class=\"fa fa-ban\"></i>" : "<i class=\"fa fa-check\"></i>") + " " + item.tgl_ambil_sample_parse + "</b><hr />" +
                                    "</div>" +
                                    "<div class=\"col-12\">" +
                                    "<table class=\"table table-bordered table-striped largeDataType\">" +
                                    "<thead class=\"thead-dark\">" +
                                    "<tr>" +
                                    "<th class=\"wrap_content\">No</th>" +
                                    "<th>Item</th>" +
                                    "<th>Nilai</th>" +
                                    "<th class=\"wrap_content\">Satuan</td>" +
                                    "<th class=\"wrap_content\">Nilai Min.</td>" +
                                    "<th class=\"wrap_content\">Nilai Maks.</td>" +
                                    "</tr>" +
                                    "</thead>" +
                                    "<tbody>";
                                var requestedItem = item.request_item.split(",").map(function(intItem) {
                                    return parseInt(intItem, 10);
                                });

                                if (item.nilai_item.length > 0){
                                    let nomor = 1;
                                    $.each(item.nilai_item, function(key, items){
                                        let nilai = items.nilai;

                                        if (nilai == null){
                                            nilai = "";
                                        }

                                        var naratifMode =  "";

                                        if(item.naratif === 'N' || item.naratif === undefined || item.naratif === null) {
                                            naratifMode = "<input " + ((!item.allow) ? "disabled=\"disabled\"" : ((__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) ? "disabled=\"disabled\"" : "")) + " id=\"nilai_" + items.uid_tindakan + "_" + items.id_lab_nilai + "\" value=\"" + nilai + "\" class=\"form-control inputItemTindakan\" />";
                                        } else {
                                            naratifMode = "<textarea " + ((!item.allow) ? "disabled=\"disabled\"" : ((__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) ? "disabled=\"disabled\"" : "")) + " id=\"nilai_" + items.uid_tindakan + "_" + items.id_lab_nilai + "\" class=\"form-control inputItemTindakan\">" + nilai + "</textarea>";
                                        }
                                        // id untuk input nilai formatnya: nilai_<uid tindakan>_<id nilai lab>
                                        if(requestedItem.indexOf(items.id_lab_nilai) < 0) {
                                            /*html += "<tr class=\"strikethrough\">" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td>" + items.keterangan + "</td>" +
                                                "<td><input id=\"nilai_" + items.uid_tindakan + "_" + items.id_lab_nilai + " value=\"" + nilai + "\" class=\"form-control inputItemTindakan\" /></td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>" +
                                                "</tr>";*/
                                        } else {
                                            html += "<tr>" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td style=\"width: 40%;\">" + items.keterangan + "</td>" +
                                                "<td>" + naratifMode + "</td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>" +
                                                "</tr>";
                                            nomor++;
                                        }
                                    });
                                }

                                html += "</tbody></table></div></div></div>";

                                console.log(html);
                                $("#hasil_pemeriksaan").append(html);
                                autonumLab++;
                            }
                        });

                        if(allow_save.indexOf(1) < 0) {
                            $("#btnSelesai").attr({
                                "disabled": "disabled"
                            });

                            $("#btnSimpan").attr({
                                "disabled": "disabled"
                            });
                        } else {
                            $("#btnSelesai").removeAttr("disabled");

                            $("#btnSimpan").removeAttr("disabled");
                        }
                    }
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}

		//return dataItem;
	}

	function loadLampiran(uid_order) {
		let dataItem;

		if (uid_order != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-lampiran/" + uid_order,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	            	if (response.response_package != ""){
	            		dataItem = response.response_package.response_data;
	            		let baseUrl = __HOST__ + '/document/laboratorium/' + uid_order + '/';

            			/*var pdfjsLib = window['pdfjs-dist/build/pdf'];
						pdfjsLib.GlobalWorkerOptions.workerSrc = __HOSTNAME__ + '/plugins/pdfjs/build/pdf.worker.js';
						var loadingTask;*/

	            		$(dataItem).each(function(key, item){
	        				loadLampiranCanvas(baseUrl + item.lampiran, item.id);
	            		});
	            	}
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}

		return dataItem;
	}

	function loadLampiranCanvas(doc_url, id){
		var newDocRow = document.createElement("TR");

		var newDocCellNum = document.createElement("TD");
		var newDocCellDoc = document.createElement("TD");
		$(newDocCellDoc).addClass("text-center");
		var newDocCellAct = document.createElement("TD");

		var newDocument = document.createElement("CANVAS");

		$(newDocument)
			.css({
				"width": "75%"
			})
			.attr('id', 'pdfViewer_' + id);

		$(newDocCellDoc).append(newDocument);
		if (doc_url != undefined) {
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({
				url: doc_url
			});
			loadingTask.promise.then(function(pdf) {
				// Fetch the first page
				var pageNumber = 1;
				pdf.getPage(pageNumber).then(function(page) {
					var scale = 1.5;
					var viewport = page.getViewport({
						scale: scale
					});
					// Prepare canvas using PDF page dimensions
					var canvas = $(newDocument)[0];
					var context = canvas.getContext('2d');
					canvas.height = viewport.height;
					canvas.width = viewport.width;
					// Render PDF page into canvas context
					var renderContext = {
						canvasContext: context,
						viewport: viewport
					};
					var renderTask = page.render(renderContext);
					renderTask.promise.then(function() {
						//
					});
				});
			}, function(reason) {
				console.error(reason);
			});
		}

		var newDeleteDoc = document.createElement("button");
		$(newDeleteDoc)
			.addClass("btn btn-sm btn-danger delete_document_registered")
			.html("<span style=\"display: block;\"><i class=\"fa fa-trash\"></i></span>")
			.attr('type', 'button')
			.data('id', 'lampiran_' + id);

		$(newDocCellAct).append(newDeleteDoc);

		$(newDocRow).append(newDocCellNum);
		$(newDocRow).append(newDocCellDoc);
		$(newDocRow).append(newDocCellAct);

		$("#labor-lampiran-table").append(newDocRow);
		rebaseLampiran();
	}

	//fungsi untuk tanda ceklis tab lampiran
	/*function check_page_2() {
		if($("#po_document_table tbody tr").length > 0) {
			$("#status-dokumen").fadeIn();
		} else {
			$("#status-dokumen").fadeOut();
		}
	}*/

	function autoDocument(file) {
		var newDocRow = document.createElement("TR");

		var newDocCellNum = document.createElement("TD");
		var newDocCellDoc = document.createElement("TD");
		$(newDocCellDoc).addClass("text-center");
		var newDocCellAct = document.createElement("TD");

		var newDocument = document.createElement("CANVAS");
		$(newDocument).css({
			"width": "75%"
		});
		$(newDocCellDoc).append(newDocument);
		if (file.type == "application/pdf" && file != undefined) {
			var fileReader = new FileReader();
			fileReader.onload = function() {
				var pdfData = new Uint8Array(this.result);
				// Using DocumentInitParameters object to load binary data.
				var loadingTask = pdfjsLib.getDocument({
					data: pdfData
				});
				loadingTask.promise.then(function(pdf) {
					// Fetch the first page
					var pageNumber = 1;
					pdf.getPage(pageNumber).then(function(page) {
						var scale = 1.5;
						var viewport = page.getViewport({
							scale: scale
						});
						// Prepare canvas using PDF page dimensions
						var canvas = $(newDocument)[0];
						var context = canvas.getContext('2d');
						canvas.height = viewport.height;
						canvas.width = viewport.width;
						// Render PDF page into canvas context
						var renderContext = {
							canvasContext: context,
							viewport: viewport
						};
						var renderTask = page.render(renderContext);
						renderTask.promise.then(function() {
							//
						});
					});
				}, function(reason) {
					console.error(reason);
				});
			};
			fileReader.readAsArrayBuffer(file);
		}

		var newDeleteDoc = document.createElement("button");
		$(newDeleteDoc).addClass("btn btn-sm btn-danger delete_document").html("<span style=\"display: block;\"><i class=\"fa fa-trash\"></i></span>").attr('type', 'button');
		$(newDocCellAct).append(newDeleteDoc);

		$(newDocRow).append(newDocCellNum);
		$(newDocRow).append(newDocCellDoc);
		$(newDocRow).append(newDocCellAct);

		$("#labor-lampiran-table").append(newDocRow);
		rebaseLampiran();
	}

	function rebaseLampiran() {
		$("#labor-lampiran-table tbody tr").each(function(e) {
			var id = (e + 1);
			$(this).attr({
				"id": "document_" + id
			});
			$(this).find("td:eq(0)").html((e + 1));
			$(this).find("td:eq(2) button").attr({
				"id": "delete_document_" + id
			});
		});
	}

	//fungsi untuk editor textarea
	function MyCustomUploadAdapterPlugin( editor ) {
	    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
	        var MyCust = new MyUploadAdapter( loader );
	        var dataToPush = MyCust.imageList;
	        hiJackImage(dataToPush);
	        return MyCust;
	    };
    }
    
    //function for count object size
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };
</script>

<div id="form-upload-lampiran" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Upload Lampiran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<canvas style="width: 100%; border: solid 1px #ccc;" id="pdfViewer"></canvas>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmitLampiran">Submit</button>
			</div>
		</div>
	</div>
</div>