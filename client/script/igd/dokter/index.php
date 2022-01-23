<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function() {
	    var selectedKunjungan = "", selectedPenjamin = "", selected_waktu_masuk = "";

        $("#btnInap").click(function() {
            loadPenjamin("inap", __PAGES__[5]);
            //loadPoli("igd");
            //loadKamar("inap");
            //loadBangsal("inap", $("#inap_kamar").val());
            $("#form-inap").modal("show");
        });

        $("#inap_dokter").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Dokter tidak ditemukan";
                }
            },
            placeholder:"Cari Dokter",
            dropdownParent: $("#form-inap"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pegawai/get_all_dokter_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.response_data;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_dokter,
                                id: item.uid
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;

        });

        $("#btnProsesInap").click(function() {
            Swal.fire({
                title: "Daftar untuk rawat inap?",
                text: "Arahkan pasien / keluarga pasien untuk menyelesaikan administrasi inap",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Inap",
                        type: "POST",
                        data: {
                            request: "tambah_inap",
                            pasien: __PAGES__[3],
                            penjamin: $("#inap_penjamin").val(),
                            dokter: $("#inap_dokter").val(),
                            kunjungan: __PAGES__[4],
                            keterangan: $("#inap_keterangan").val(),
                            asal: "igd",
                            poli_asal: __POLI_IGD__
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            if(response.response_package.response_result > 0) {
                                location.href = __HOSTNAME__ + "/igd";
                            } else {
                                console.log(response);
                            }
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                }
            });
        });


        loadCPPT("2021-01-01", "2021-08-01", __PAGES__[3], currentCPPTStep, "");

        function resetSelectBox(selector, name){
            $("#"+ selector +" option").remove();
            var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
            $("#" + selector).append(opti_null);
        }

        function loadPenjamin(target_ui, selected = "") {
            var dataPenjamin = null;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Penjamin/penjamin",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");
                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if(MetaData[i].uid === selected) {
                                $(selection).attr("selected", "selected");
                            }
                            $("#" + target_ui + "_penjamin").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPenjamin;
        }

		/*var tableAntrian= $("#table-antrian-rawat-jalan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-medis/igd",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var filteredData = [];
				    var data = response.response_package.response_data;

				    for(var a = 0; a < data.length; a++) {
				        if(
				            data[a].uid_pasien === __PAGES__[3] &&
                            data[a].uid_kunjungan === __PAGES__[4] &&
                            data[a].uid_poli === __POLI_IGD__
                        ) {
				            filteredData.push(data[a]);
                        }
                    }

				    if(filteredData.length > 0) {
				        selectedKunjungan = filteredData[0].uid_kunjungan;
				        selectedPenjamin = filteredData[0].uid_penjamin;
                        selected_waktu_masuk = filteredData[0].waktu_masuk;
                        //console.log(filteredData[0].pasien_detail);
				        $("#target_pasien").html(filteredData[0].pasien);
				        $("#rm_pasien").html(filteredData[0].no_rm);
                        $("#nama_pasien").html((filteredData[0].pasien_detail.panggilan_name === null) ? filteredData[0].pasien_detail.nama : filteredData[0].pasien_detail.panggilan_name.nama + " " +  filteredData[0].pasien_detail.nama);
                        $("#jenkel_pasien").html(filteredData[0].pasien_detail.jenkel_detail.nama);
                        $("#tempat_lahir_pasien").html(filteredData[0].pasien_detail.tempat_lahir);
                        $("#alamat_pasien").html(filteredData[0].pasien_detail.alamat);
                        $("#usia_pasien").html(filteredData[0].pasien_detail.usia);
                        $("#tanggal_lahir_pasien").html(filteredData[0].pasien_detail.tanggal_lahir_parsed);
                    } else {
				        //Pasien Detail
                        $.ajax({
                            url: __HOSTAPI__ + "/Pasien/pasien-detail/" + __PAGES__[3],
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"GET",
                            success:function(response) {
                                var pasienData = response.response_package.response_data;
                                $("#target_pasien").html(pasienData[0].nama);
                                $("#rm_pasien").html(pasienData[0].no_rm);
                                $("#nama_pasien").html((pasienData[0].panggilan_name === null) ? pasienData[0].nama : pasienData[0].panggilan_name.nama + " " +  pasienData[0].nama);
                                $("#usia_pasien").html(pasienData[0].usia);
                                $("#jenkel_pasien").html(pasienData[0].jenkel_detail.nama);
                                $("#tanggal_lahir_pasien").html(pasienData[0].tanggal_lahir_parsed);
                                $("#tempat_lahir_pasien").html(pasienData[0].tempat_lahir);
                                $("#alamat_pasien").html(pasienData[0].alamat);
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
				    console.log(filteredData);
				    return filteredData;
				}
			},
			autoWidth: false,
			"bInfo" : false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.waktu_masuk;
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter;
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/igd/dokter/antrian/" + row.uid + "/" + row.uid_pasien + "/" + row.uid_kunjungan + "\" class=\"btn btn-success btn-sm\">" +
										"<span><i class=\"fa fa-eye\"></i>Detail</span>" +
									"</a>" +
								"</div>";
					}
				}
			]
		});*/

        // function loadCPPT(from, to, pasien) {
        //     $("#cppt_loader").html("");
        //     $.ajax({
        //         async: false,
        //         url: __HOSTAPI__ + "/Pasien/pasien-detail/" + pasien,
        //         type: "GET",
        //         beforeSend: function (request) {
        //             request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        //         },
        //         success: function (response) {
        //             var pasienData = response.response_package.response_data;
        //             $("#target_pasien").html(pasienData[0].nama);
        //             $("#rm_pasien").html(pasienData[0].no_rm).attr({
        //                 "uid": pasienData[0].uid
        //             });
        //             $("#nama_pasien").html((pasienData[0].panggilan_name === null) ? pasienData[0].nama : pasienData[0].panggilan_name.nama + " " +  pasienData[0].nama);
        //             $("#usia_pasien").html(pasienData[0].usia);
        //             $("#jenkel_pasien").html(pasienData[0].jenkel_detail.nama);
        //             $("#tanggal_lahir_pasien").html(pasienData[0].tanggal_lahir_parsed);
        //             $("#tempat_lahir_pasien").html(pasienData[0].tempat_lahir);
        //             $("#alamat_pasien").html(pasienData[0].alamat);
        //         },
        //         error: function (response) {
        //             console.log(response);
        //         }
        //     });
        //     $.ajax({
        //         url: __HOSTAPI__ + "/CPPT",
        //         async:false,
        //         beforeSend: function(request) {
        //             request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        //         },
        //         type:"POST",
        //         data: {
        //             request: "group_tanggal",
        //             pasien: pasien,
        //             from: from,
        //             to: to
        //         },
        //         success:function(response) {
        //             var data = response.response_package;
        //             for(var a in data) {
        //                 $.ajax({
        //                     url: __HOSTNAME__ + "/pages/pasien/cppt-grouper.php",
        //                     async:false,
        //                     beforeSend: function(request) {
        //                         request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        //                     },
        //                     type:"POST",
        //                     data: {
        //                         group_tanggal_caption: data[a].parsed,
        //                         group_tanggal_name: a
        //                     },
        //                     success:function(responseGrouper) {
        //                         $("#cppt_loader").append(responseGrouper);
        //                         var listData = data[a].data;
        //                         for(var b in listData) {
        //                             var currentData = listData[b].data[0];
        //                             //if(currentData.uid !== UID) {
        //                             $.ajax({
        //                                 url: __HOSTNAME__ + "/pages/pasien/cppt-single.php",
        //                                 async:false,
        //                                 beforeSend: function(request) {
        //                                     request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        //                                 },
        //                                 type:"POST",
        //                                 data: {
        //                                     __HOST__: __HOST__,
        //                                     __ME__: __ME__,
        //                                     group_tanggal_name: a,
        //                                     waktu_masuk: listData[b].parsed,
        //                                     waktu_masuk_name: listData[b].parsed.replaceAll(":", "_"),
        //                                     departemen: currentData.departemen.nama,
        //                                     dokter_uid: currentData.dokter.uid,
        //                                     dokter: currentData.dokter.nama,
        //                                     dokter_pic: __HOST__ + currentData.dokter.profile_pic,
        //                                     icd10_kerja: currentData.asesmen.icd10_kerja,
        //                                     icd10_banding: currentData.asesmen.icd10_banding,
        //                                     keluhan_utama:currentData.asesmen.keluhan_utama,
        //                                     keluhan_tambahan:currentData.asesmen.keluhan_tambahan,
        //                                     diagnosa_kerja:currentData.asesmen.diagnosa_kerja,
        //                                     diagnosa_banding:currentData.asesmen.diagnosa_banding,
        //                                     pemeriksaan_fisik:currentData.asesmen.pemeriksaan_fisik,
        //                                     planning:currentData.asesmen.planning,
        //                                     tindakan: currentData.asesmen.tindakan,
        //                                     resep: currentData.asesmen.resep,
        //                                     racikan: currentData.asesmen.racikan,
        //                                     laboratorium: currentData.asesmen.laboratorium,
        //                                     radiologi: currentData.asesmen.radiologi
        //                                 },
        //                                 success:function(responseSingle) {
        //                                     $("#group_cppt_" + a).append(responseSingle);
        //                                 },
        //                                 error: function(responseSingleError) {
        //                                     console.log(responseSingleError);
        //                                 }
        //                             });
        //                             //}
        //                         }
        //                     },
        //                     error: function(responseGrouperError) {
        //                         console.log(responseGrouperError);
        //                     }
        //                 });
        //             }
        //         },
        //         error: function(response) {
        //             console.log(response);
        //         }
        //     });
        // }

		$("#btnTambahAsesmen").click(function() {
		    $(this).attr({
                "disabled": "disabled"
            }).removeClass("btn-info").addClass("btn-warning").html("<i class=\"fa fa-sync\"></i> Menambahkan Asesmen");

            var formData = {
                request: "tambah_asesmen",
                penjamin: __PAGES__[5],
                kunjungan: __PAGES__[4],
                pasien: __PAGES__[3],
                poli: __POLI_IGD__
            };

            $.ajax({
                url: __HOSTAPI__ + "/Inap",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: formData,
                success:function(response) {
                    location.href = __HOSTNAME__ + "/igd/dokter/antrian/" + response.response_package.response_values[0] + "/" + __PAGES__[3] + "/" + __PAGES__[4];
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });



		$(".print_manager").click(function() {
		    var targetSurat = $(this).attr("id");
		    $("#target-judul-cetak").html("CETAK " + targetSurat.toUpperCase() + " PASIEN");
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/pasien_" + targetSurat + ".php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    pc_customer: __PC_CUSTOMER__,
                    no_rm:$("#rm_pasien").html(),
                    pasien: "An. " + $("#nama_pasien").html(),
                    tanggal_lahir: $("#tanggal_lahir_pasien").html(),
                    usia: $("#usia_pasien").html() + " tahun",
                    dokter: __MY_NAME__,
                    waktu_masuk: selected_waktu_masuk,
                    alamat: $("#alamat_pasien").html(),
                    tempat_lahir: $("#tempat_lahir_pasien").html()
                },
                success: function (response) {
                    //$("#dokumen-viewer").html(response);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "igd",
                        afterPrint: function() {
                            $("#cetak").modal("hide");
                            $("#dokumen-viewer").html("");
                        }
                    });
                }
            });
        });
	});
</script>

<div id="form-inap" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pindah Rawat Inap</h5>
            </div>
            <div class="modal-body" id="inap-container">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="inap_penjamin" class="form-control select2 inputan_inap" required disabled>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="inap_dokter" class="form-control select2 inputan_inap"></select>
                                </div>
                                <!--div class="col-12 col-md-6 mb-3">
                                    <label>Kamar <span class="red">*</span></label>
                                    <select id="inap_kamar" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Kamar</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Ranjang <span class="red">*</span></label>
                                    <select id="inap_bed" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Ranjang</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3" id="group_inap_tanggal_masuk">
                                    <label>Tanggal Masuk <span class="red">*</span></label>
                                    <input type="date" id="inap_tanggal_masuk" class="form-control input-group" required />
                                </div-->
                                <div class="col-12 col-md-12 mb-12">
                                    <label>Keterangan <span class="red">*</span></label>
                                    <textarea type="text" id="inap_keterangan" class="form-control" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesInap">
                    <span>
                        <i class="fa fa-check"></i> Pindah Rawat Inap
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>