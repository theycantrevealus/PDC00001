<script type="text/javascript">
    $(function () {
        $("#range_sep").change(function() {
            SEPList.ajax.reload();
        });

        $("#jenis_pelayanan").select2().on("select2:select", function(e) {
            SEPList.ajax.reload();
        });

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
                },
                dataSrc: function (response) {
                    var data = response.response_package.response_data;
                    if (data === undefined) {
                        data = [];
                    }
                    return data;
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
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btn-edit-sep\" no_sep=\"" + row.sep_no + "\" id=\"sep_edit_" + row.uid + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusSEP\" id=\"hapus_" + row.sep_no + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btn-edit-sep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/BPJS/get_sep_detail/" + id,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function (response) {
                    /*
                    no_kartu: $("#txt_bpjs_nomor").val(),
                    ppk_pelayanan: $("#txt_bpjs_faskes").val(),
                    kelas_rawat: $("#txt_bpjs_kelas_rawat").val(),
                    no_mr: $("#txt_bpjs_rm").val().replace(new RegExp(/-/g),""),
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

                            console.log(penjaminList[pKey]);
                        }
                    }
                    $("#modal-sep").modal("show");
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
                            console.log(response);
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