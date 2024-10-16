<script type="text/javascript">
    $(function() {

        var listRI = $("#table-visit-dokter").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inap",
                type: "POST",
                data: function(d) {
                    d.request = "get_visit_dokter";
                    d.jenis_pelayanan = "visite";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.log(response);
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        var data = response.response_package.response_data;
                        var autonum = 1;
                        for(var key in data) {
                            data[key].autonum = autonum;
                            returnedData.push(data[key]);
                            autonum++;
                            // if(
                            //     data[key].pasien !== null && data[key].pasien !== undefined &&
                            //     //data[key].dokter.uid === __ME__ &&
                            //     data[key].nurse_station !== null
                            // ) {
                            //     data[key].autonum = autonum;
                            //     returnedData.push(data[key]);
                            //     autonum++;
                            // }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Pasien"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parse + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b kunjungan=\"" + row.kunjungan + "\" data=\"" + row.pasien.uid + "\" id=\"pasien_" + row.id + "\" class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + row.pasien.nama;
                        // return "<span class=\"wrap_content\">" + row.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.dokter_rujuk !== null && row.dokter_rujuk !== undefined 
                        && row.dokter.uid !== row.dokter_rujuk.uid){
                            return "<span class=\"wrap_content\">" + row.dokter_rujuk.nama + "</span>";
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.jenis_layanan + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.penjamin.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.keterangan !== ""){
                            return "<span class=\"wrap_content\">" + row.keterangan + "</span>";
                        }else {
                            return "-";
                        }
                        
                    }
                }
            ]
        });

        var listRIKonsultasi = $("#table-konsultasi-dokter").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inap",
                type: "POST",
                data: function(d) {
                    d.request = "get_visit_dokter";
                    d.jenis_pelayanan = "konsultasi";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.log(response);
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        var data = response.response_package.response_data;
                        var autonum = 1;
                        for(var key in data) {
                            data[key].autonum = autonum;
                            returnedData.push(data[key]);
                            autonum++;
                            // if(
                            //     data[key].pasien !== null && data[key].pasien !== undefined &&
                            //     //data[key].dokter.uid === __ME__ &&
                            //     data[key].nurse_station !== null
                            // ) {
                            //     data[key].autonum = autonum;
                            //     returnedData.push(data[key]);
                            //     autonum++;
                            // }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Pasien"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parse + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b kunjungan=\"" + row.kunjungan + "\" data=\"" + row.pasien.uid + "\" id=\"pasien_" + row.id + "\" class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + row.pasien.nama;
                        // return "<span class=\"wrap_content\">" + row.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.dokter.nama !== null && row.dokter.nama !== undefined){
                            return "<span class=\"wrap_content\">" + row.dokter.nama + "</span>";
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.jenis_layanan + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.penjamin.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.keterangan !== ""){
                            return "<span class=\"wrap_content\">" + row.keterangan + "</span>";
                        }else {
                            return "-";
                        }
                        
                    }
                },
                // __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + antrian + "/" + pasien + "/" + kunjungan + "/" + penjamin + "/" + inap
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        if(row.antrian.waktu_keluar === null || row.antrian.waktu_keluar === undefined ){
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + row.antrian.uid + "/"+ row.pasien.uid +"/"+ row.kunjungan+"/"+row.penjamin.uid+"/"+row.inap.uid+"\" class=\"btn btn-success btnDetailAntrian\">" +
                            "<i class=\"fa fa-sign-out-alt\"></i> Proses" +
                            "</a>" +
                            "</div>";
                        }else {
                            return "";
                        }
                    }
                }
            ]
        });

        $("#txt_jenis_pelayanan").on('change',function(){

            if($(this).val() === 'Konsultasi'){
                $('#konsultasi_dokter').show();
            }else  {
                $('#konsultasi_dokter').hide();
            }
        })

        $("#btnTambahVisit").click(function() {
            $("#pasien_saya").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function() {
                        return "Pasien tidak ditemukan";
                    }
                },
                placeholder: "Cari Pasien",
                ajax: {
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type": "application/json",
                    },
                    url: __HOSTAPI__ + "/Pasien/asesmen_visit_dokter",
                    type: "GET",
                    data: function(term) {
                        return {
                            search: term.term
                        };
                    },
                    cache: true,
                    processResults: function(response) {
                        var data = response.response_package.response_data;
                        // console.clear();
                        console.log(response);
                        return {
                            results: $.map(data, function(item) {

                                return {
                                    text: item.nama,
                                    id: item.pasien,
                                    inap: item.uid,
                                    kunjungan: item.kunjungan,
                                    antrian: item.antrian,
                                    penjamin: item.penjamin
                                }
                            })
                        };
                    }
                }
            }).on("select2:select", function(e) {
                var data = e.params.data;
                $("#pasien_saya option:selected").attr({
                    inap: data.inap,
                    kunjungan: data.kunjungan,
                    antrian: data.antrian,
                    penjamin: data.penjamin
                })
            });

            $("#dokter").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function() {
                        return "Dokter tidak ditemukan";
                    }
                },
                placeholder: "Cari Dokter",
                ajax: {
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type": "application/json",
                    },
                    url: __HOSTAPI__ + "/Pegawai/get_all_dokter_select2",
                    type: "GET",
                    data: function(term) {
                        return {
                            search: term.term
                        };
                    },
                    cache: true,
                    processResults: function(response) {
                        var data = response.response_package.response_data;
                        // console.clear();
                        console.log(response);
                        return {
                            results: $.map(data, function(item) {

                                return {
                                    text: item.nama_dokter,
                                    id: item.uid
                                }
                            })
                        };
                    }
                }
            }).on("select2:select", function(e) {
                var data = e.params.data;
                $("#dokter option:selected").attr({
                    dokter: data.id
                })
            });


            $("#form-tambah-visit").modal("show");
        });

        $("#btnSubmitVisit").click(function(){
            var jenis_pelayanan = $("#txt_jenis_pelayanan").val();
            var keterangan = $("#txt_ket").val();
            var pasien = $("#pasien_saya").val();
            if(jenis_pelayanan === 'Konsultasi'){
                var dokter = $("#dokter").val();
            }else{
                var dokter = null;
            }
            var inap = $("#pasien_saya option:selected").attr("inap");
            var kunjungan = $("#pasien_saya option:selected").attr("kunjungan");
            var penjamin = $("#pasien_saya option:selected").attr("penjamin");

            var formData = {
                request: "tambah_asesmen_2",
                penjamin: penjamin,
                kunjungan: kunjungan,
                pasien: pasien,
                dokter: dokter,
                jenis_layanan: jenis_pelayanan,
                keterangan:keterangan,
                poli: __POLI_INAP__
            };


            $.ajax({
                url: __HOSTAPI__ + "/Inap",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: formData,
                success: function(response) {
                   console.log(response.response_package.response_values[0]);
                    
                    if(response.response_package.response_values[0] !== null &&
                        response.response_package.response_values[0] !== undefined
                    
                    ){
                        var antrian = response.response_package.response_values[0];
                        var formDataVisit = {
                            request: "tambah_asesmen_visit",
                            antrian: antrian,
                            penjamin: penjamin,
                            kunjungan: kunjungan,
                            pasien: pasien,
                            dokter: dokter,
                            jenis_layanan: jenis_pelayanan,
                            keterangan:keterangan,
                            poli: __POLI_INAP__
                        };
                        
                        $.ajax({
                            url: __HOSTAPI__ + "/Inap",
                            async: false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            data: formDataVisit,
                            success: function(response) {
                                console.log(response);
                                if(jenis_pelayanan === 'Visite'){
                                    location.href = __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + antrian + "/" + pasien + "/" + kunjungan + "/" + penjamin + "/" + inap;
                                }else {
                                    $("#form-tambah-visit").modal("hide");
                                    listRI.ajax.reload();
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });

                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
      
        });


    });
</script>

<div id="form-tambah-visit" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Visit Dokter</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <strong>Pasien</strong>
                        <select id="pasien_saya" class="form-control"></select>
                        <br /><br />
                    </div>
                    
                    <div class="col-lg-12">
                        <strong>Jenis Pelayanan</strong>
                        <select id="txt_jenis_pelayanan" class="form-control">
                            <option value="Visite" selected>Visite</option>
                            <option value="Konsultasi">Konsultasi</option>
                        </select>
                        <br />
                    </div>

                    <div style="display:none" id="konsultasi_dokter" class="col-lg-12">
                        <strong>Konsultasi Dengan Dokter</strong>
                        <select id="dokter" class="form-control"></select>
                        <br /><br/>
                    </div>
                    <div class="col-lg-12">
                        <strong>Keterangan</strong>
                        <textarea style="min-height: 200px;" class="form-control" id="txt_ket" placeholder="Keterangan"></textarea>
                        <br />
                    </div>
                    <!-- <div class="col-lg-12">
                        <div class="alert alert-soft-warning d-flex align-items-center card-margin" role="alert">
                            <i class="material-icons mr-3">error_outline</i>
                            <div class="text-body"><strong>Entry Resep.</strong> Resep baru akan ditambahkan ke asesmen terakhir pasien. Keterangan penambahan resep wajib diisi</div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-primary" id="btnSubmitVisit">
                    <span>
                        <i class="fa fa-plus"></i> Tambahkan
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>