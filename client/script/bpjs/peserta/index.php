<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {

        var MODE = "ADD";
        var SEARCH = "ADD";

        $("#bynokartu_text_search_tgl").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#bynik_text_search_tgl").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrl = __BPJS_SERVICE_URL__ + "peserta/sync.sh/getpesertabynokartu";

        var getParameterNo = "0000000000000";
        var getParameterTgl = "2023-08-21";
        $("#btn_search_bynokartu").click(function() {
            $('#alert-peserta-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "peserta/sync.sh/getpesertabynokartu";
            getParameterNo = $('#bynokartu_text_search_no_kartu').val();
            getParameterTgl = $('#bynokartu_text_search_tgl').val();
            MODE = "SEARCH";
            PESERTAList.ajax.url(getUrl).load();
        });

        $("#btn_search_bynik").click(function() {
            $('#alert-peserta-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "peserta/sync.sh/getpesertabynik";
            getParameterNo = $('#bynik_text_search_nik').val();
            getParameterTgl = $('#bynik_text_search_tgl').val();
            MODE = "SEARCH";
            SEARCH = "NIK";
            PESERTAList.ajax.url(getUrl).load();
        });

        $('#alert-peserta-container').hide();

        var PESERTAList = $("#table-peserta").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax": {
                url: getUrl,
                type: "POST",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    refreshToken().then((test) => {
                        bpjs_token = test;
                    })

                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                data: function(d) {
                    if (SEARCH === "NIK") {
                        d.nik = getParameterNo;
                    } else {
                        d.nomorkartu = getParameterNo;
                    }
                    d.tglsep = getParameterTgl;
                },
                dataSrc: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        if (MODE === "SEARCH") {
                            $('#alert-peserta').text(response.metadata.message);
                            $('#alert-peserta-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-peserta-container').fadeOut();
                        return response.response;
                    }
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            aaSorting: [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [{
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.nik;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noKartu;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (row.sex === "L") ? "Laki-laki" : "Perempuan";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.hakkelas.keterangan;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.statuspeserta.keterangan;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.jenispeserta.kode;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-detail-peserta\" title=\"Detail\" tgl-layan=\"" + getParameterTgl + "\" id=\"" + row.noKartu + "\"><i class=\"fa fa-search\"></i> Detail</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btn-detail-peserta", function() {
            var no_kartu = $(this).attr("id");
            var tgl_layan = $(this).attr("tgl-layan");

            var SEPButton = $(this);
            SEPButton.html("Memuat Detail...").removeClass("btn-success").addClass("btn-warning");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "peserta/sync.sh/getpesertabynokartu",
                type: "POST",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    refreshToken().then((test) => {
                        bpjs_token = test;
                    })

                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                data: {
                    nomorkartu: no_kartu,
                    tglsep: tgl_layan,
                },
                success: function(response) {

                    var dataPeserta = response.response[0];

                    $("#nama_peserta").html(dataPeserta.nama);
                    $("#nik").html(dataPeserta.nik);
                    $("#no_kartu").html(dataPeserta.noKartu);
                    $("#jenis_kelamin").html((dataPeserta.sex === "L") ? "Laki-laki" : "Perempuan");
                    $("#tgl_lahir").html(dataPeserta.tglLahir);
                    $("#nomor_telepon").html(dataPeserta.mr.noTelepon);
                    $("#no_mr").html(dataPeserta.mr.noMR);
                    $("#status_peserta").html(dataPeserta.statuspeserta.keterangan);
                    $("#jenis_peserta").html(dataPeserta.jenispeserta.keterangan + " - " + dataPeserta.jenispeserta.kode);
                    $("#umur_saat_pelayanan").html(dataPeserta.umur.umurSaatPelayanan);
                    $("#umur_sekarang").html(dataPeserta.umur.umurSekarang);
                    $("#tgl_cetak_kartu").html(dataPeserta.tglCetakKartu);
                    $("#tgl_tat").html(dataPeserta.tglTAT);
                    $("#tgl_tmt").html(dataPeserta.tglTMT);

                    $("#hak_kelas").html(dataPeserta.hakkelas.kode + " - " + dataPeserta.hakkelas.keterangan);
                    $("#provider").html(dataPeserta.provumum.kdProvider + " - " + dataPeserta.provumum.nmProvider);
                    $("#pisa").html(dataPeserta.pisa);
                    $("#dinsos").html((dataPeserta.informasi.dinsos !== undefined && dataPeserta.informasi.dinsos !== "") ? dataPeserta.informasi.dinsos : "-");
                    $("#no_sktm").html((dataPeserta.informasi.noSKTM !== undefined && dataPeserta.informasi.noSKTM !== "") ? dataPeserta.informasi.noSKTM : "-");
                    $("#prolanis_prb").html((dataPeserta.informasi.prolanisPRB !== undefined && dataPeserta.informasi.prolanisPRB !== "") ? dataPeserta.informasi.prolanisPRB : "-");
                    $("#esep").html((dataPeserta.informasi.eSEP !== undefined && dataPeserta.informasi.eSEP !== "") ? dataPeserta.informasi.eSEP : "-");
                    $("#nm_asuransi_cob").html((dataPeserta.cob.nmAsuransi !== undefined && dataPeserta.cob.nmAsuransi !== "") ? dataPeserta.cob.nmAsuransi : "-");
                    $("#no_asuransi_cob").html((dataPeserta.cob.noAsuransi !== undefined && dataPeserta.cob.noAsuransi !== "") ? dataPeserta.cob.noAsuransi : "-");
                    $("#tgl_tat_cob").html((dataPeserta.cob.tglTAT !== undefined && dataPeserta.cob.tglTAT !== "") ? dataPeserta.cob.tglTAT : "-");
                    $("#tgl_tmt_cob").html((dataPeserta.cob.tglTMT !== undefined && dataPeserta.cob.tglTMT !== "") ? dataPeserta.cob.tglTMT : "-");

                    $("#modal-detail-peserta").modal("show");
                    SEPButton.html("<i class=\"fa fa-search\"></i> Detail").removeClass("btn-warning").addClass("btn-info");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

    });
</script>


<div id="modal-detail-peserta" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-3">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Detail Peserta BPJS</span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4 offset-sm-3" id="data_sep_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 150px;">Nama Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="nama_peserta"></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td class="wrap_content">:</td>
                                <td id="nik"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="no_kartu"></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td class="wrap_content">:</td>
                                <td id="jenis_kelamin"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_lahir"></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td class="wrap_content">:</td>
                                <td id="nomor_telepon"></td>
                            </tr>
                            <tr>
                                <td>No. Mr</td>
                                <td class="wrap_content">:</td>
                                <td id="no_mr"></td>
                            </tr>
                            <tr>
                                <td>Status Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="status_peserta"></td>
                            </tr>
                            <tr>
                                <td>Jenis Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="jenis_peserta"></td>
                            </tr>
                            <tr>
                                <td>Umur Saat Pelayanan</td>
                                <td class="wrap_content">:</td>
                                <td id="umur_saat_pelayanan"></td>
                            </tr>
                            <tr>
                                <td>Umur Sekarang</td>
                                <td class="wrap_content">:</td>
                                <td id="umur_sekarang"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Cetak Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_cetak_kartu"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TAT</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tat"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TMT</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tmt"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_sep_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;">Hak Kelas</td>
                                <td class="wrap_content">:</td>
                                <td id="hak_kelas"></td>
                            </tr>
                            <tr>
                                <td>Provider</td>
                                <td class="wrap_content">:</td>
                                <td id="provider"></td>
                            </tr>
                            <tr>
                                <td>Pisa</td>
                                <td class="wrap_content">:</td>
                                <td id="pisa"></td>
                            </tr>
                            <tr>
                                <td>Dinsos</td>
                                <td class="wrap_content">:</td>
                                <td id="dinsos"></td>
                            </tr>
                            <tr>
                                <td>No. SKTM</td>
                                <td class="wrap_content">:</td>
                                <td id="no_sktm"></td>
                            </tr>
                            <tr>
                                <td>Prolanis PRB</td>
                                <td class="wrap_content">:</td>
                                <td id="prolanis_prb"></td>
                            </tr>
                            <tr>
                                <td>eSEP</td>
                                <td class="wrap_content">:</td>
                                <td id="esep"></td>
                            </tr>
                            <tr>
                                <td>Informasi COB</td>
                            </tr>
                            <tr>
                                <td>Nama Asuransi</td>
                                <td class="wrap_content">:</td>
                                <td id="nm_asuransi_cob"></td>
                            </tr>
                            <tr>
                                <td>No. Asuransi</td>
                                <td class="wrap_content">:</td>
                                <td id="no_asuransi_cob"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TAT COB</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tat_cob"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TMT COB</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tmt_cob"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>