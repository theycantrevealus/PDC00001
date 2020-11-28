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

        var dataSetAll = [];
        var dataSetSelector = [];
        var selectedData = {};

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
                    dataSetAll = dataSet
                    for(var key in dataSet) {
                        dataSetSelector.push(dataSet[key].uid);
                    }

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
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var selectionKey = dataSetSelector.indexOf(id);
            selectedData = dataSetAll[selectionKey];

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

            $.ajax({
                url:__HOSTAPI__ + "/Asesmen/antrian-detail-record/" + antrian,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var data = response.response_package.response_data[0];

                    var icd10Kerja = "<ol type=\"1\">";
                    var icd10Banding = "<ol type=\"1\">";

                    for(var icdKerjaKey in data.icd10_kerja) {
                        icd10Kerja += "<li>" + data.icd10_kerja[icdKerjaKey].nama + "</li>";
                    }

                    for(var icd10BandingKey in data.icd10_banding) {
                        icd10Banding += "<li>" + data.icd10_banding[icd10BandingKey].nama + "</li>";
                    }

                    icd10Kerja += "</ol>";
                    icd10Banding += "</ol>";

                    $(".txt_keluhan_utama").html(data.keluhan_utama);
                    $(".txt_keluhan_tambahan").html(data.keluhan_tambahan);
                    $(".txt_pemeriksaan_fisik").html(data.pemeriksaan_fisik);
                    $(".txt_diagnosa_kerja").html(icd10Kerja + data.diagnosa_kerja);
                    $(".txt_diagnosa_banding").html(icd10Banding + data.diagnosa_banding);
                    $(".txt_planning").html(data.planning);
                    $("#tanggal_periksa").html(data.tanggal_parsed);

                    //Parse Resep
                    var resepData = data.resep[0].resep_detail;
                    $(".resepDokterCPPT tbody tr").remove();
                    for(var resepKey in resepData) {
                        var newResepRow = document.createElement("TR");

                        var resepID = document.createElement("TD");
                        var resepObat = document.createElement("TD");
                        var resepSigna = document.createElement("TD");
                        var resepJlh = document.createElement("TD");

                        $(resepID).html((parseInt(resepKey) + 1));
                        $(resepObat).html(resepData[resepKey].obat_detail.nama);
                        $(resepSigna).html(resepData[resepKey].signa_pakai + " &times; " + resepData[resepKey].signa_qty);
                        $(resepJlh).html(resepData[resepKey].qty);

                        $(newResepRow).append(resepID);
                        $(newResepRow).append(resepObat);
                        $(newResepRow).append(resepSigna);
                        $(newResepRow).append(resepJlh);

                        $(".resepDokterCPPT tbody").append(newResepRow);
                    }

                    var racikanData = data.racikan;
                    $(".racikanDokterCPPT tbody tr").remove();
                    for(var racikanKey in racikanData) {
                        var itemRacikan = racikanData[racikanKey].item;

                        console.log(racikanData[racikanKey]);

                        var newRacikanRow = document.createElement("TR");

                        var racikanID = document.createElement("TD");
                        var racikanNama = document.createElement("TD");
                        var racikanKomposisi = document.createElement("TD");
                        var racikanSigna = document.createElement("TD");
                        var racikanJlh = document.createElement("TD");

                        $(racikanID).html((parseInt(racikanKey) + 1));
                        $(racikanNama).html(racikanData[racikanKey].kode);

                        var komposisi = "<ol type=\"1\">";
                        for(var itemKey in itemRacikan) {
                            komposisi += "<li>" + itemRacikan[itemKey].obat_detail.nama + " <b class=\"text-info\">" + itemRacikan[itemKey].kekuatan  + "</b></li>";
                        }
                        komposisi += "</ol>";
                        $(racikanKomposisi).html(komposisi);
                        $(racikanSigna).html(racikanData[racikanKey].signa_pakai + " &times; " + racikanData[racikanKey].signa_qty);
                        $(racikanJlh).html(racikanData[racikanKey].qty);

                        $(newRacikanRow).append(racikanID);
                        $(newRacikanRow).append(racikanNama);
                        $(newRacikanRow).append(racikanKomposisi);
                        $(newRacikanRow).append(racikanSigna);
                        $(newRacikanRow).append(racikanJlh);

                        $(".racikanDokterCPPT tbody").append(newRacikanRow);

                    }

                    //Parse Laboratorium
                    for(var labKey in selectedData.lab_order) {
                        var LabBuild = load_laboratorium(selectedData.lab_order[labKey]);
                        $(".lab_loader").append(LabBuild);
                    }

                    //Parse Radiologi

                    $("#modal-detail-asesmen").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#range_pasien").change(function() {
            pasienTable.ajax.reload();
        });

        function load_laboratorium(data) {
            var listPetugas = [];
            for(petugasKey in data.petugas) {
                if(data.petugas[petugasKey] !== null) {
                    listPetugas.push(data.petugas[petugasKey].nama);
                }
            }

            data['petugas_parse'] = listPetugas.join(",");

            var returnHTML = "";
            $.ajax({
                url: __HOSTNAME__ + "/pages/pasien/dokter/lab-single.php",
                async:false,
                data: data,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                success:function(response_html) {
                    returnHTML = response_html;
                },
                error: function(response_html) {
                    console.log(response_html);
                }
            });
            return returnHTML;
        }
    });
</script>



<div id="modal-detail-asesmen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                                    <div class="card-body">
                                        <p class="text-dark-gray d-flex align-items-center mt-3">
                                            <i class="material-icons icon-muted mr-2">event</i>
                                            <strong id="tanggal_periksa"></strong>
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
                                                                        <p class="txt_pemeriksaan_fisik">

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
                                                                        <p class="txt_planning">

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
                                                            <div class="col-6">
                                                                <div class="segmen_resep">
                                                                    <div class="d-flex align-items-center">
                                                                        <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Resep Dokter</strong></a>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered resepDokterCPPT largeDataType">
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
                                                            <div class="col-6">
                                                                <div class="segmen_resep">
                                                                    <div class="d-flex align-items-center">
                                                                        <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Resep Apotek</strong></a>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered resepApotekCPPT largeDataType">
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
                                                            <div class="col-6">
                                                                <div class="segmen_racikan">
                                                                    <div class="d-flex align-items-center">
                                                                        <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Racikan Dokter</strong></a>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered racikanDokterCPPT largeDataType">
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
                                                            <div class="col-6">
                                                                <div class="segmen_racikan">
                                                                    <div class="d-flex align-items-center">
                                                                        <a href="#" class="text-body"><strong class="text-15pt mr-2"><i class="fa fa-hashtag"></i> Racikan Apotek</strong></a>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered racikanApotekCPPT largeDataType">
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
                                </div>
                                <div class="tab-pane show fade" id="tab-poli-2">
                                    <div class="row lab_loader"></div>
                                </div>
                                <div class="tab-pane show fade" id="tab-poli-3">
                                    <div class="card">
                                        <div class="card-header card-header-large bg-white">
                                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Radiologi</h5>
                                        </div>
                                        <div class="card-body">

                                        </div>
                                    </div>
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