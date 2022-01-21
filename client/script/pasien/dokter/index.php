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
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
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
                    //console.log(dataSet);

                    /*var dataResponse = [];
                    if(dataSet == undefined) {
                        dataSet = [];
                    }



                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;*/
                    dataSetAll = dataSet
                    for(var key in dataSet) {
                        if(dataSet[key].poli === null || dataSet[key].poli === undefined) {
                            dataSet[key].poli = {
                                uid: __POLI_INAP__,
                                nama: "Rawat Inap"
                            }
                        }
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
                        return "<span class=\"wrap_content\"><h6 class=\"text-info\">" + row.pasien.no_rm + "</h6>" + row.pasien.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.poli.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.tanggal_kunjungan + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var listTunjang = "";
                        if(row.lab_order.length > 0) {
                            for(var labKey in row.lab_order) {
                                if(row.lab_order[labKey].no_order !== null) {
                                    listTunjang += "<div style=\"margin: 1px;\" class=\"badge badge-outline-" + ((row.lab_order[labKey].status === "D") ? "success" : "info") + " badge-custom-caption\"><i class=\"fa fa-tag\"></i>&nbsp;&nbsp;" + row.lab_order[labKey].no_order + "</div>";
                                }
                            }
                        }

                        if(row.rad_order.length > 0) {
                            for(var radKey in row.rad_order) {
                                if(row.rad_order[radKey].no_order !== null) {
                                    listTunjang += "<div style=\"margin: 1px;\"  class=\"badge badge-outline-" + ((row.rad_order[radKey].selesai === true) ? "success" : "purple") + " badge-custom-caption\"><i class=\"fa fa-tag\"></i>&nbsp;&nbsp;" + row.rad_order[radKey].no_order + "</div>";
                                }
                            }
                        }
                        return listTunjang + "<br /><br />";
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

                        //return 	"<button lab=\"" + isLab + "\" rad=\"" + isRad + "\" antrian=\"" + row.antrian + "\" class=\"btn btn-info btn-sm btnDetailPemeriksaan\" id=\"detail_" + row.uid + "\"><i class=\"fa fa-eye\"></i> Detail</button>";
                        return 	"<div class=\"btn-group wrap_content\">" +
                            "<a href=\"" + __HOSTNAME__ + "/pasien/dokter/view/" + row.pasien.uid + "/" + row.antrian + "\" class=\"btn btn-info btn-sm btnDetailPemeriksaan\"><i class=\"fa fa-eye\"></i> Detail</a>" +
                            "</div>";
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
                    console.log(data);

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
                    if(data.resep !== null) {
                        if(data.resep[0] !== undefined) {
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

                            var resepApotekData = data.resep_apotek;

                            $(".resepApotekCPPT tbody tr").remove();
                            for(var resepApotekKey in resepApotekData) {
                                var newResepApotikRow = document.createElement("TR");

                                var resepApotikID = document.createElement("TD");
                                var resepApotikObat = document.createElement("TD");
                                var resepApotikSigna = document.createElement("TD");
                                var resepApotikJlh = document.createElement("TD");

                                $(resepApotikID).html((parseInt(resepApotekKey) + 1));
                                $(resepApotikObat).html(resepApotekData[resepApotekKey].obat_detail.nama);
                                $(resepApotikSigna).html(resepApotekData[resepApotekKey].signa_pakai + " &times; " + resepApotekData[resepApotekKey].signa_qty);
                                $(resepApotikJlh).html(resepApotekData[resepApotekKey].qty);

                                $(newResepApotikRow).append(resepApotikID);
                                $(newResepApotikRow).append(resepApotikObat);
                                $(newResepApotikRow).append(resepApotikSigna);
                                $(newResepApotikRow).append(resepApotikJlh);

                                $(".resepApotekCPPT tbody").append(newResepApotikRow);
                            }

                            var racikanApotekData = data.racikan_apotek;
                            $(".racikanApotekCPPT tbody tr").remove();
                            for(var racikanApotekKey in racikanApotekData) {
                                var itemApotekRacikan = racikanApotekData[racikanApotekKey].item;

                                var newRacikanApotekRow = document.createElement("TR");

                                var racikanApotekID = document.createElement("TD");
                                var racikanApotekNama = document.createElement("TD");
                                var racikanApotekKomposisi = document.createElement("TD");
                                var racikanApotekSigna = document.createElement("TD");
                                var racikanApotekJlh = document.createElement("TD");

                                $(racikanApotekID).html((parseInt(racikanApotekKey) + 1));
                                $(racikanApotekNama).html(racikanData[racikanApotekKey].kode);

                                var komposisiApotek = "<ol type=\"1\">";
                                for(var itemApotekKey in itemApotekRacikan) {
                                    komposisiApotek += "<li>" + itemApotekRacikan[itemApotekKey].obat_detail.nama + " (" + itemApotekRacikan[itemApotekKey].jumlah + ") <b class=\"text-info\">" + itemApotekRacikan[itemApotekKey].kekuatan  + "</b></li>";
                                }
                                komposisiApotek += "</ol>";
                                $(racikanApotekKomposisi).html(komposisiApotek);
                                $(racikanApotekSigna).html(racikanApotekData[racikanApotekKey].signa_pakai + " &times; " + racikanApotekData[racikanApotekKey].signa_qty);
                                $(racikanApotekJlh).html(racikanApotekData[racikanApotekKey].jumlah);

                                $(newRacikanApotekRow).append(racikanApotekID);
                                $(newRacikanApotekRow).append(racikanApotekNama);
                                $(newRacikanApotekRow).append(racikanApotekKomposisi);
                                $(newRacikanApotekRow).append(racikanApotekSigna);
                                $(newRacikanApotekRow).append(racikanApotekJlh);

                                $(".racikanApotekCPPT tbody").append(newRacikanApotekRow);
                            }
                        }
                    }


                    //Parse Laboratorium
                    for(var labKey in selectedData.lab_order) {
                        var LabBuild = load_laboratorium(selectedData.lab_order[labKey]);
                        $(".lab_loader").html(LabBuild);
                    }

                    /*console.clear();
                    console.log(selectedData.rad_order);*/

                    //Parse Radiologi
                    for(var radKey in selectedData.rad_order) {
                        selectedData.rad_order[radKey]['__HOST__'] = __HOST__;
                        var RadBuild = load_radiologi(selectedData.rad_order[radKey]);
                        $(".rad_loader").html(RadBuild);
                    }


                    $("#modal-detail-asesmen").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        var file;

        $("body").on("click", ".lampiran_view_trigger", function() {
            var target = $(this).attr("target");
            $("#modal-lampiran-viewer").modal("show");

            var request = new XMLHttpRequest();
            request.open('GET', target, true);
            request.responseType = 'blob';
            request.onload = function() {
                var reader = new FileReader();
                reader.readAsDataURL(request.response);
                reader.onload =  function(e){
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
                    //fileReader.readAsArrayBuffer(file);
                    fileReader.readAsArrayBuffer(request.response);
                };
            };
            request.send();

            return false;
        });

        $("#modal-lampiran-viewer").on("shown.bs.modal", function () {

            /*var fileReader = new FileReader();
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
            fileReader.readAsArrayBuffer(file);*/
        });

        $("#range_pasien").change(function() {
            pasienTable.ajax.reload();
        });

        function load_radiologi(data) {
            /*console.clear();
            console.log(data);*/
            var returnHTML = "";
            $.ajax({
                url: __HOSTNAME__ + "/pages/pasien/dokter/rad-single.php",
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

        function load_laboratorium(data) {
            console.log(data);

            data.sampling = data.tanggal_sampling;

            data.dr_penanggung_jawab = {
                nama: data.detail[0].dpjp_detail.nama
            };
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


<div id="modal-lampiran-viewer" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index: 2048;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Lampiran Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas style="width: 100%; border: solid 1px #ccc;" id="pdfViewer"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>




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
                                    <div class="row rad_loader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>