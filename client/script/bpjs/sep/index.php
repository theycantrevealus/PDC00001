<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function () {
        $("#range_sep").change(function() {
            if(
                !Array.isArray(getDateRange("#range_sep")[0]) &&
                !Array.isArray(getDateRange("#range_sep")[1])
            ) {
                SEPList.ajax.reload();
            }
        });

        $("#jenis_pelayanan").select2().on("select2:select", function(e) {
            SEPList.ajax.reload();
        });

        var refreshData = 'N';

        $("#btn_sync_bpjs").click(function() {
            refreshData = 'Y';
            SEPList.ajax.reload(function () {
                refreshData = 'N';
            });
        });

        function switchSEPParam(refreshData = false) {
            return {

            }
        }

        var SEPList = $("#table-sep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax": {
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function (d) {
                    d.request = "get_history_sep_local";
                    d.dari = getDateRange("#range_sep")[0];
                    d.sampai = getDateRange("#range_sep")[1];
                    d.pelayanan_jenis = $("#jenis_pelayanan").val();
                    d.sync_bpjs = refreshData;
                },
                dataSrc: function (response) {
                    var data = response.response_package.response_data;

                    if (data === undefined || data === null) {
                        return [];
                    } else {
                        return data;
                    }

                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [[-1], ["All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.sep_no;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<b class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + ((row.pasien.panggilan_name !== undefined) ? row.pasien.panggilan_name.nama : "") + " " + row.pasien.nama;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        if(row.claim !== undefined && row.claim !== null) {
                            if(row.claim.length > 0) {
                                return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                    "<button class=\"btn btn-info btn-sm btn-edit-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_edit_" + row.uid + "\">" +
                                    "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                                    "</button>" +
                                    "<button class=\"btn btn-success btn-sm btn-cetak-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_cetak_" + row.uid + "\">" +
                                    "<i class=\"fa fa-print\"></i> Cetak" +
                                    "</button>" +
                                    "<button class=\"btn btn-purple btn-sm btn-detail-claim\" no_sep=\"" + row.sep_no + "\" id=\"sep_buat_claim_" + row.uid + "\">" +
                                    "<i class=\"fa fa-search\"></i> Claim" +
                                    "</button>" +
                                    "<button disabled class=\"btn btn-danger btnHapusSEP\" id=\"hapus_" + row.sep_no + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                    "</div>";
                            } else {
                                return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                    "<button class=\"btn btn-info btn-sm btn-edit-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_edit_" + row.uid + "\">" +
                                    "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                                    "</button>" +
                                    "<button class=\"btn btn-success btn-sm btn-cetak-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_cetak_" + row.uid + "\">" +
                                    "<i class=\"fa fa-print\"></i> Cetak" +
                                    "</button>" +
                                    "<button class=\"btn btn-purple btn-sm btn-buat-claim\" no_sep=\"" + row.sep_no + "\" id=\"sep_buat_claim_" + row.uid + "\">" +
                                    "<i class=\"fa fa-plus-circle\"></i> Claim" +
                                    "</button>" +
                                    "<button class=\"btn btn-danger btnHapusSEP\" id=\"hapus_" + row.sep_no + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                    "</div>";
                            }
                        } else {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-info btn-sm btn-edit-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_edit_" + row.uid + "\">" +
                                "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                                "</button>" +
                                "<button class=\"btn btn-success btn-sm btn-cetak-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_cetak_" + row.uid + "\">" +
                                "<i class=\"fa fa-print\"></i> Cetak" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-buat-claim\" no_sep=\"" + row.sep_no + "\" id=\"sep_buat_claim_" + row.uid + "\">" +
                                "<i class=\"fa fa-plus-circle\"></i> Claim" +
                                "</button>" +
                                "<button class=\"btn btn-danger btnHapusSEP\" id=\"hapus_" + row.sep_no + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                "</div>";
                        }


                    }
                }
            ]
        });

        $("body").on("click", ".btn-buat-claim", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            $("#modal-sep-claim").modal("show");
        });

        $("body").on("click", ".btn-cetak-sep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var SEPButton = $(this);
            SEPButton.html("Memuat SEP...").removeClass("btn-success").addClass("btn-warning");

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/BPJS/get_sep_detail/" + id,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function (response) {

                    var dataSEP = response.response_package.response_data[0];
                    $("#sep_nomor").html(dataSEP.sep_no);
                    $("#sep_tanggal").html(dataSEP.sep_tanggal);
                    $("#sep_spesialis").html(dataSEP.poli_tujuan_detail.kode + " - " + dataSEP.poli_tujuan_detail.nama);
                    $("#sep_faskes_asal").html(dataSEP.asal_rujukan_ppk + " - " + ((dataSEP.asal_rujukan_nama !== undefined && dataSEP.asal_rujukan_nama !== null && dataSEP.asal_rujukan_nama !== "null") ? dataSEP.asal_rujukan_nama : "[TIDAK DITEMUKAN]") + "<b class=\"text-info\">[No. Rujuk: " + dataSEP.asal_rujukan_nomor + "]");
                    $("#sep_diagnosa_awal").html(dataSEP.diagnosa_nama);
                    $("#sep_catatan").html(dataSEP.catatan);
                    $("#sep_kelas_rawat").html(dataSEP.kelas_rawat.nama);
                    $("#sep_jenis_rawat").html((parseInt(dataSEP.pelayanan_jenis) === 1) ? "Rawat Inap" : "Rawat Jalan");


                    var penjaminList = dataSEP.pasien.history_penjamin;
                    for(var pKey in penjaminList) {
                        if(penjaminList[pKey].penjamin === __UIDPENJAMINBPJS__) {
                            var metaData = JSON.parse(penjaminList[pKey].rest_meta);
                            $("#sep_nomor_kartu").html(metaData.response.peserta.noKartu);
                            $("#sep_nama_peserta").html(metaData.response.peserta.nama + "<b class=\"text-info\">[" + metaData.response.peserta.mr.noMR + "]</b>");
                            $("#sep_tanggal_lahir").html(metaData.response.peserta.tglLahir);
                            $("#sep_nomor_telepon").html(metaData.response.peserta.mr.noTelepon);
                            $("#sep_peserta").html(metaData.response.peserta.jenisPeserta.keterangan);
                            if(
                                metaData.response.peserta.cob.noAsuransi !== undefined &&
                                metaData.response.peserta.cob.nmAsuransi !== undefined &&
                                metaData.response.peserta.cob.noAsuransi !== "" &&
                                metaData.response.peserta.cob.nmAsuransi !== "" &&
                                metaData.response.peserta.cob.noAsuransi !== null &&
                                metaData.response.peserta.cob.nmAsuransi !== null
                            ) {
                                $("#sep_cob").html(metaData.response.peserta.cob.noAsuransi + " - " + metaData.response.peserta.cob.nmAsuransi);
                            } else {
                                $("#sep_cob").html("-");
                            }
                        }
                    }
                    $("#modal-sep-cetak").modal("show");
                    SEPButton.html("<i class=\"fa fa-print\"></i> Cetak").removeClass("btn-warning").addClass("btn-success");
                },
                error: function (response) {
                    //
                }
            });
        });

        $("#btnCetakSEP").click(function() {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/bpjs_sep.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    html_data_kiri: $("#data_sep_cetak_kiri").html(),
                    html_data_kanan: $("#data_sep_cetak_kanan").html(),
                    html_data_bawah: $("#data_sep_cetak_bawah").html()
                },
                success: function (response) {
                    //$("#dokumen-viewer").html(response);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "Cetak SEP",
                        afterPrint: function() {
                            //
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btn-edit-sep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var SEPButton = $(this);
            SEPButton.html("Memuat SEP...").removeClass("btn-info").addClass("btn-warning");

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/BPJS/get_sep_detail/" + id,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function (response) {
                    var data = {};
                    if(
                        response.response_package.response_data !== undefined &&
                        response.response_package.response_data.length > 0
                    ) {
                        data = response.response_package.response_data[0];

                        console.clear();
                        console.log(data);
                        SEPButton.html("<i class=\"fa fa-pencil-alt\"></i> Edit").removeClass("btn-warning").addClass("btn-info");

                        //$("#txt_bpjs_nomor").val(data.sep_no);
                        //$("#txt_bpjs_faskes").val();
                        $("#txt_bpjs_rm").val().replace(new RegExp(/-/g), data.pasien.no_rm);


                        /*
                        no_kartu: ,
                        ppk_pelayanan: ,
                        kelas_rawat: $("#txt_bpjs_kelas_rawat").val(),
                        no_mr: ,
                        asal_rujukan: $("#txt_bpjs_jenis_asal_rujukan").val(),
                        ppk_rujukan: $("#txt_bpjs_asal_rujukan").val(),
                        tgl_rujukan: parse_tanggal_rujukan,
                        no_rujukan: $("#txt_bpjs_nomor_rujukan").val(),
                        catatan: $("#txt_bpjs_catatan").val(),
                        diagnosa_awal: $("#txt_bpjs_diagnosa_awal").val(),
                        diagnosa_kode: $("#txt_bpjs_diagnosa_awal option:selected").text(),
                        poli: $("#txt_bpjs_poli_tujuan").val(),
                        eksekutif: $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val(),
                        cob: $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val(),
                        katarak: $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val(),

                        laka_lantas: $("input[type=\"radio\"][name=\"txt_bpjs_laka\"]:checked").val(),
                        laka_lantas_penjamin: selectedLakaPenjamin.join(","),
                        laka_lantas_tanggal_kejadian: parse_tanggal_laka,
                        laka_lantas_keterangan: $("#txt_bpjs_laka_keterangan").val(),
                        laka_lantas_suplesi: $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                        laka_lantas_suplesi_nomor: $("#txt_bpjs_laka_suplesi_nomor").val(),
                        laka_lantas_suplesi_provinsi: $("#txt_bpjs_laka_suplesi_provinsi").val(),
                        laka_lantas_suplesi_kabupaten: $("#txt_bpjs_laka_suplesi_kabupaten").val(),
                        laka_lantas_suplesi_kecamatan: $("#txt_bpjs_laka_suplesi_kecamatan").val(),

                        skdp: $("#txt_bpjs_skdp").val(),
                        dpjp: $("#txt_bpjs_dpjp").val(),
                        telepon: $("#txt_bpjs_telepon").val()
                        * */

                        var dataSEP = response.response_package.response_data[0];

                        var penjaminList = dataSEP.pasien.history_penjamin;
                        for(var pKey in penjaminList) {
                            if(penjaminList[pKey].penjamin === __UIDPENJAMINBPJS__) {
                                //var metaData = JSON.parse(penjaminList[pKey].penjamin_detail.rest_meta);


                            }
                        }
                        $("#modal-sep").modal("show");

                    } else {

                    }
                },
                error: function (response) {
                    //
                }
            });
        });

        $("body").on("click", ".btnHapusSEP", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            Swal.fire({
                title: "Hapus SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya. Hapus",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/BPJS/SEP/" + id,
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "DELETE",
                        success: function (response) {
                            if(parseInt(response.response_package.bpjs.content.metaData.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'SEP Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    SEPList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.response_package.bpjs.content.metaData.message,
                                    'error'
                                ).then((result) => {
                                    SEPList.ajax.reload();
                                });
                            }
                        },
                        error: function (response) {
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("#claim_tanggal_masuk").datepicker({
            dateFormat: 'DD, dd MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#claim_tanggal_keluar").datepicker({
            dateFormat: 'DD, dd MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#claim_jaminan").select2();

        $("#claim_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-sep-claim"),
            ajax: {
                dataType: "json",
                headers: {
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_poli",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.poli;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });










        $("#claim_ruang_rawat").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-sep-claim"),
            ajax: {
                dataType: "json",
                headers: {
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_ruang_rawat",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.list;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });




        $("#claim_spesialistik").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-sep-claim"),
            ajax: {
                dataType: "json",
                headers: {
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_spesialistik",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.list;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#claim_cara_keluar").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Cara keluar tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-sep-claim"),
            ajax: {
                dataType: "json",
                headers: {
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_cara_keluar_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.list;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });


        var dataKondisiPulang = load_bpjs("get_spesialistik");


        function load_bpjs(targetURL) {
            var bpjsData;
            $.ajax({
                url:__HOSTAPI__ + "/BPJS/" + targetURL,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    bpjsData = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return bpjsData;
        }

        function autoICD(targetTable) {
            var newRow = document.createElement("TR");
            var newID = document.createElement("TD");
            var newDiagnosa = document.createElement("TD");
            var newAksi = document.createElement("TD");


            $(newRow).append(newID);
            $(newRow).append(newDiagnosa);
            $(newRow).append(newAksi);

            $(targetTable).append(newRow);
        }
    });
</script>

<div id="modal-sep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-5 mb-5 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">Kontak</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_telepon" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-6">
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Nomor Medical Rahecord (MR)</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                        </div>

                                        <div class="col-12 col-md-7 form-group">
                                            <label for="">Tanggal SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly value="<?php echo date('d F Y'); ?>">
                                        </div>
                                        <div class="col-12 col-md-9 form-group">
                                            <label for="">Faskes</label>
                                            <select class="form-control sep" id="txt_bpjs_faskes" disabled>
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD PETALA BUMI - KOTA PEKAN BARU</option>
                                            </select>
                                        </div>


                                        <div class="col-12 col-md-8 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="2">Rawat Jalan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                            <label for="">Kelas Rawat</label>
                                            <select class="form-control" id="txt_bpjs_kelas_rawat"></select>
                                        </div>
                                    </div>

                                    <div class="col-6" id="panel-rujukan">
                                        <div class="col-12 col-md-6 mb-4 form-group" id="group_nomor_rujukan">
                                            <label for="">Nomor Rujukan</label>
                                            <select data-width="100%" class="form-control uppercase" id="txt_bpjs_nomor_rujukan"></select>
                                            <!--<input type="text" class="form-control uppercase" id="txt_bpjs_nomor_rujukan" />-->
                                        </div>
                                        <div class="col-12 col-md-4 mb-4 form-group">
                                            <label for="">Jenis Asal Rujukan</label>
                                            <select class="form-control uppercase sep" id="txt_bpjs_jenis_asal_rujukan">
                                                <option value="1">Puskesmas</option>
                                                <option value="2">Rumah Sakit</option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Asal Rujukan</label>
                                            <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                        </div>
                                        <div class="col-12 col-md-5 mb-4 form-group">
                                            <label for="">Tanggal Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan">
                                        </div>

                                        <div class="informasi_rujukan">
                                            <table class="table form-mode">
                                                <tr>
                                                    <td>Perujuk</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_perujuk"></td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Kunjungan</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_tanggal"></td>
                                                </tr>
                                                <tr>
                                                    <td>Poli</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_poli"></td>
                                                </tr>
                                                <tr>
                                                    <td>Diagnosa</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_diagnosa"></td>
                                                </tr>
                                                <tr>
                                                    <td>Keluhan</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_keluhan"></td>
                                                </tr>
                                                <tr>
                                                    <td>Hak Kelas</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_hak_kelas"></td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Peserta</td>
                                                    <td>:</td>
                                                    <td id="txt_bpjs_rujuk_jenis_peserta"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Perobatan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Tujuan</label>
                                                <select class="form-control" id="txt_bpjs_poli_tujuan"></select>
                                            </div>
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Eksekutif</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                <label for="">Diagnosa Awal</label>
                                                <select class="form-control sep" id="txt_bpjs_diagnosa_awal"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Catatan</label>
                                                <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_catatan" style="min-height: 200px"></textarea>
                                            </div>
                                            <div class="col-12 col-md-6 mb-4 form-group">
                                                <label for="">Nomor SKDP</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_skdp" />
                                            </div>
                                            <div class="col-12 col-md-8 mb-8 form-group" id="group_spesialistik">
                                                <label for="">Spesialistik DPJP</label>
                                                <select class="form-control" id="txt_bpjs_dpjp_spesialistik"></select>
                                            </div>
                                            <div class="col-12 col-md-9 mb-9 form-group" id="group_dpjp">
                                                <label for="">Kode DPJP</label>
                                                <select class="form-control sep" id="txt_bpjs_dpjp"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">COB</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Katarak</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="alert alert-info">
                                                <div class="col-12 col-md-8 mb-4 form-group">
                                                    <b for="">Poli Tujuan</b>
                                                    <blockquote style="padding-left: 25px;">
                                                        <h6 id="txt_bpjs_internal_poli"></h6>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Kerja</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdk"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_dk"></p>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Banding</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdb"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_db"></p>
                                                    </blockquote>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Jaminan Laka Lantas</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_laka" value="0" checked/>
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_laka" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="laka_lantas_container">
                                                <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                    <label for="">Penjamin Laka Lantas</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="1" />
                                                                <label class="form-check-label">
                                                                    Jasa Raharja
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="2" />
                                                                <label class="form-check-label">
                                                                    BPJS Ketenagakerjaan
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="3" />
                                                                <label class="form-check-label">
                                                                    TASPEN PT
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="txt_bpjs_laka_penjamin" value="4" />
                                                                <label class="form-check-label">
                                                                    ASABRI PT
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 mb-4 form-group">
                                                    <label for="">Tanggal Kejadian</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_tanggal">
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <label for="">Keterangan</label>
                                                    <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_laka_keterangan" style="min-height: 200px"></textarea>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <label for="">Suplesi</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="0" checked/>
                                                                <label class="form-check-label">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="1" />
                                                                <label class="form-check-label">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="laka_lantas_suplesi_container">
                                                    <div class="col-12 col-md-6 mb-4 form-group">
                                                        <label for="">Nomor SEP Suplesi</label>
                                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_suplesi_nomor" />
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_provinsi">
                                                        <label for="">Provinsi Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_provinsi"></select>
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_kabupaten">
                                                        <label for="">Kabupaten Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kabupaten"></select>
                                                    </div>
                                                    <div class="col-12 col-md-8 mb-4 form-group" id="group_kecamatan">
                                                        <label for="">Kecamatan Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kecamatan"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<div id="modal-sep-claim" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Lembar Pengajuan Klaim</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 col-md-6 form-group">
                        <label for="">Tanggal Masuk</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="claim_tanggal_masuk" />
                    </div>
                    <div class="col-6 col-md-6 form-group">
                        <label for="">Tanggal Keluar</label>
                        <input type="text" autocomplete="off" class="form-control uppercase" id="claim_tanggal_keluar" />
                    </div>
                    <div class="col-4 col-md-6 form-group">
                        <label for="">Jaminan</label>
                        <select class="form-control" id="claim_jaminan">
                            <option value="1">JKN</option>
                        </select>
                    </div>
                    <div class="col-8 col-md-6 form-group">
                        <label for="">Poli</label>
                        <select class="form-control" id="claim_poli"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4 form-group">
                        <label for="">Ruang Rawat</label>
                        <select class="form-control" id="claim_ruang_rawat"></select>
                    </div>
                    <div class="col-4 col-md-4 form-group">
                        <label for="">Kelas Rawat</label>
                        <select class="form-control" id="claim_kelas_rawat"></select>
                    </div>
                    <div class="col-4 col-md-4 form-group">
                        <label for="">Spesialistik</label>
                        <select class="form-control" id="claim_spesialistik"></select>
                    </div>
                    <div class="col-6 col-md-4 form-group">
                        <label for="">Cara Keluar</label>
                        <select class="form-control" id="claim_cara_keluar"></select>
                    </div>
                    <div class="col-6 col-md-4 form-group">
                        <label for="">Kondisi Pulang</label>
                        <select class="form-control" id="claim_kondisi_pulang"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6">
                        <h5>Diagnosa</h5>
                        <table class="table table-bordered largeDataType" id="claim_diagnosa">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>Diagnosa</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-6 col-md-6">
                        <h5>Prosedur</h5>
                        <table class="table table-bordered largeDataType" id="claim_procedure">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>Procedure</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4 form-group">
                        <label for="">Rencana Tindak Lanjut</label>
                        <select class="form-control" id="claim_rencana_tl">
                            <option value="1">Diperbolehkan Pulang</option>
                            <option value="2">Pemeriksaan Penunjang</option>
                            <option value="3">Dirujuk Ke</option>
                            <option value="4">Kontrol Kembali</option>
                        </select>
                    </div>
                    <div class="col-4 col-md-4 form-group">
                        <label for="">Dirujuk Ke</label>
                        <select class="form-control" id="claim_dirujuk_ke"></select>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Kontrol Kembali</label>
                            <input type="text" id="claim_tanggal_kontrol" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="">Kontrol Poli</label>
                            <select class="form-control" id="claim_poli_kontrol"></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 form-group">
                        <label for="">DPJP</label>
                        <select class="form-control" id="claim_dpjp"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<div id="modal-sep-cetak" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Surat Eligibilitas Peserta</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6" id="data_sep_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td>No. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_tanggal"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor_kartu"></td>
                            </tr>
                            <tr>
                                <td>Nama Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nama_peserta"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_tanggal_lahir"></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor_telepon"></td>
                            </tr>
                            <tr>
                                <td>Sub/Spesialis</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_spesialis"></td>
                            </tr>
                            <tr>
                                <td>Faskes Penunjuk</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_faskes_asal"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa Awal</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_diagnosa_awal"></td>
                            </tr>
                            <tr>
                                <td>Catatan</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_catatan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6" id="data_sep_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td>Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_peserta"></td>
                            </tr>
                            <tr>
                                <td>COB</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_cob"></td>
                            </tr>
                            <tr>
                                <td>Jenis Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_jenis_rawat"></td>
                            </tr>
                            <tr>
                                <td>Kelas Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_kelas_rawat"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12" id="data_sep_cetak_bawah">
                        <small>
                            <i>
                                <ul type="*" style="margin: 0; padding: 10px;">
                                    <li>
                                        Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan
                                    </li>
                                    <li>
                                        SEP bukan sebagai bukti penjaminan peserta
                                    </li>
                                </ul>
                            </i>
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnCetakSEP">
                    <i class="fa fa-print"></i> Cetak
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>