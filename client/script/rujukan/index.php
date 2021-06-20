<script type="text/javascript">
    $(function () {
        var RujukanList = $("#table-rujukan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Rujukan",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_all";
                },
                dataSrc:function(response) {
                    console.clear();
                    console.log(response);
                    var data = response.response_package.response_data;
                    if(data === undefined) {
                        data = [];
                    }
                    return data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            lengthMenu: [[-1], ["All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.created_at_parsed;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var selectedID = {};
                        var selectedMetaData;
                        for(var a = 0; a < row.pasien.history_penjamin.length; a++) {
                            if(row.pasien.history_penjamin[a].penjamin === row.penjamin.uid) {
                                selectedID = row.pasien.history_penjamin[a];
                                selectedMetaData = JSON.parse(row.pasien.history_penjamin[a].rest_meta);
                            }
                        }

                        return "<span id=\"pasien_" + row.uid + "\" nik=\"" + row.pasien.nik + "\" no_kartu=\"" + selectedMetaData.response.peserta.noKartu + "\">" + row.pasien.no_rm + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row.uid + "\" kontak=\"" + row.pasien.no_telp + "\">" + row.pasien.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"rujukan_" + row.uid + "\" class=\"btn btn-info btn-sm btnRujuk\" target=\"" + row.penjamin.uid + "\">" +
                            "<span><i class=\"fa fa-pencil-alt\"></i>Proses" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnRujuk", function () {
            var target = $(this).attr("target");
            if(target === __UIDPENJAMINBPJS__) {
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];

                $("#txt_bpjs_nomor").val($("#pasien_" + id).attr("no_kartu"));
                $("#txt_bpjs_nik").val($("#pasien_" + id).attr("nik"));
                $("#txt_bpjs_nama").val($("#nama_" + id).html());
                $("#txt_bpjs_telepon").val($("#nama_" + id).attr("kontak"));
                $("#txt_bpjs_rm").val($("#pasien_" + id).html());

                $("#modal-rujuk-bpjs").modal("show");
            }
        });


        $("#txt_bpjs_nomor_sep").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_sep_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.noSep,
                                id: item.noSep
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });
    });
</script>








<div id="modal-rujuk-bpjs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rujuk Baru
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
                                    <label for="">Nomor Medical Record (MR)</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                </div>

                                <div class="col-12 col-md-7 form-group">
                                    <label for="">Nomor SEP</label>
                                    <select data-width="100%" class="form-control uppercase" id="txt_bpjs_nomor_sep"></select>
                                </div>
                                <div class="col-12 col-md-4 mb-4 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_jenis_tujuan_rujukan">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_rujukan"></select>
                                </div>
                                <div class="col-12 col-md-8 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_poli"></select>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tipe_rujukan">
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <textarea class="form-control" id="txt_bpjs_catatan"></textarea>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Catatan</label>
                                    <textarea class="form-control" id="txt_bpjs_catatan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <div id="spanBtnTambahPasien" hidden> -->
                <button class="btn btn-success" id="btnProsesRujuk">
                     <i class="fa fa-plus"></i> Tambah Rujukan Baru
                </button>
                <!-- </div> -->

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>