<script type="text/javascript">
    $(function() {
        function getDateRange(target) {
            var rangeKwitansi = $(target).val().split(" to ");
            if(rangeKwitansi.length > 1) {
                return rangeKwitansi;
            } else {
                return [rangeKwitansi, rangeKwitansi];
            }
        }

        var pasienTable = $("#table-pasien").DataTable({
            processing: true,
            lengthMenu: [[10, 15, -1], [10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Asesmen",
                type: "POST",
                data: function(d){
                    d.request = "pasien_saya";
                    d.from = getDateRange("#range_pasien")[0];
                    d.to = getDateRange("#range_pasien")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var dataSet = response.response_package.response_data;
                    /*var dataResponse = [];
                    if(dataSet == undefined) {
                        dataSet = [];
                    }



                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;*/
                    return dataSet;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Kwitansi"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pasien.nama
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.tanggal_kunjungan;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var listTunjang = "";
                        if(row.lab_order.length > 0) {
                            for(var labKey in row.lab_order) {
                                if(row.lab_order[labKey].no_order !== null) {
                                    listTunjang += "<div class=\"badge badge-" + ((row.lab_order[labKey].status === "D") ? "success" : "info") + " badge-custom\"><i class=\"fa fa-tag\"></i>&nbsp;&nbsp;" + row.lab_order[labKey].no_order + "</div>";
                                }
                            }
                        }

                        if(row.rad_order.length > 0) {
                            for(var radKey in row.rad_order) {
                                if(row.rad_order[radKey].no_order !== null) {
                                    listTunjang += "<div class=\"badge badge-" + ((row.rad_order[radKey].selesai === true) ? "success" : "purple") + " badge-custom\"><i class=\"fa fa-tag\"></i>&nbsp;&nbsp;" + row.rad_order[radKey].no_order + "</div>";
                                }
                            }
                        }
                        return listTunjang;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var isRad = 0;
                        var isLab = 0;
                        if(row.lab_order.length > 0) {
                            isLab = 1
                        }

                        if(row.rad_order.length > 0) {
                            isRad = 1
                        }
                        return 	"<button lab=\"" + isLab + "\" rad=\"" + isRad + "\" antrian=\"" + row.antrian + "\" class=\"btn btn-info btn-sm btnDetailPemeriksaan\" id=\"detail_" + row.uid + "\"><i class=\"fa fa-eye\"></i></button>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnDetailPemeriksaan", function () {
            var antrian = $(this).attr("antrian");
            var lab = $(this).attr("lab");
            var rad = $(this).attr("rad");

            if(parseInt(lab) > 0) {
                $("a[href=\"#tab-poli-2\"]").show();
            } else {
                $("a[href=\"#tab-poli-2\"]").hide();
            }

            if(parseInt(rad) > 0) {
                $("a[href=\"#tab-poli-3\"]").show();
            } else {
                $("a[href=\"#tab-poli-3\"]").hide();
            }

            //Get Detail Asesmen

            $("#modal-detail-asesmen").modal("show");
        });

        $("#range_pasien").change(function() {
            pasienTable.ajax.reload();
        });
    });
</script>



<div id="modal-detail-asesmen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Detail Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <div class="row card-group-row">
                        <div class="col-lg-12 col-md-12">
                            <div class="z-0">
                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                    <li class="nav-item">
                                        <a href="#tab-poli-1" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                            <span class="nav-link__count">
                                                <i class="fa fa-address-book"></i>
                                            </span>
                                            CPPT
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tab-poli-2" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                            <span class="nav-link__count">
                                                <i class="fa fa-flask"></i>
                                            </span>
                                            Hasil Laboratorium
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tab-poli-3" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-poli-1" >
                                            <span class="nav-link__count">
                                                <i class="fa fa-life-ring"></i>
                                            </span>
                                            Hasil Radiologi
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card card-body tab-content">
                                <div class="tab-pane show fade active" id="tab-poli-1">
                                    <p class="text-dark-gray d-flex align-items-center mt-3">
                                        <i class="material-icons icon-muted mr-2">event</i>
                                        <strong>Jam</strong>
                                    </p>
                                    <div class="row projects-item mb-1">
                                        <div class="col-1">
                                            <br />
                                            <div class="text-dark-gray">Subjective</div>
                                        </div>
                                        <div class="col-11">
                                            <div class="card">
                                                <div class="card-header card-header-large bg-white">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="segmen_keluhan_utama">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Keluhan Utama</strong></a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="txt_keluhan_utama">

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="segmen_keluhan_tambahan">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Keluhan Tambahan</strong></a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="txt_keluhan_tambahan">

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row projects-item mb-1">
                                        <div class="col-1">
                                            <br />
                                            <div class="text-dark-gray">Objective</div>
                                        </div>
                                        <div class="col-11">
                                            <div class="card">
                                                <div class="card-header card-header-large bg-white">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="segmen_keluhan_utama">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Pemeriksaan Fisik</strong></a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="txt_keluhan_utama">

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row projects-item mb-1">
                                        <div class="col-1">
                                            <br />
                                            <div class="text-dark-gray">Asesmen</div>
                                        </div>
                                        <div class="col-11">
                                            <div class="card">
                                                <div class="card-header card-header-large bg-white">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="segmen_diagnosa_utama">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Diagnosa Kerja</strong></a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="txt_diagnosa_kerja">

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="segmen_diagnosa_banding">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Diagnosa Banding</strong></a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="txt_diagnosa_banding">

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row projects-item mb-1">
                                        <div class="col-1">
                                            <br />
                                            <div class="text-dark-gray">Planning</div>
                                        </div>
                                        <div class="col-11">
                                            <div class="card">
                                                <div class="card-header card-header-large bg-white">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="segmen_keluhan_utama">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Planning</strong></a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="txt_keluhan_utama">

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row projects-item mb-1">
                                        <div class="col-1">
                                            <br />
                                            <div class="text-dark-gray">Resep & Racikan</div>
                                        </div>
                                        <div class="col-11">
                                            <div class="card">
                                                <div class="card-header card-header-large bg-white">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="segmen_resep">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Resep</strong></a>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <table class="table table-bordered">
                                                                    <thead class="thead-dark">
                                                                    <tr>
                                                                        <th class="wrap_content">No</th>
                                                                        <th>Obat</th>
                                                                        <th>Signa</th>
                                                                        <th>Jlh</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-header card-header-large bg-white">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="segmen_racikan">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Racikan</strong></a>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <table class="table table-bordered">
                                                                    <thead class="thead-dark">
                                                                    <tr>
                                                                        <th class="wrap_content">No</th>
                                                                        <th>Racikan</th>
                                                                        <th>Komposisi</th>
                                                                        <th>Signa</th>
                                                                        <th>Jlh</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane show fade" id="tab-poli-2">
                                    Lab
                                </div>
                                <div class="tab-pane show fade" id="tab-poli-3">
                                    Rad
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>