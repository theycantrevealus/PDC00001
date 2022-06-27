<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var cppt = <?php echo json_encode($_GET['cppt']); ?>;

        var currentMetaData, currentAsesmen, currentRacikanActive, targetKodeResep;
        var kunjungan, antrian, asesmen, penjamin, pasien, pasien_penjamin_uid;
        var keteranganRacikan, keteranganResep;
        var allowEdit = false;
        var alasanUbah = "";
        var totalResep = 0;
        var totalRacikan = 0;
        var alasanLib = {},
            alasanRacikanLib = {};
        var currentData = {
            resep: {},
            racikan: {}
        };

        var verifData = {
            resep: {},
            racikan: {}
        };

        var isChanged = false;

        checkGenerateResep();
        checkGenerateRacikan();


        $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").change(function() {
            $(this).parent().parent().addClass("active");
            $(".kajian_sel[value=\"y\"]").prop("checked", true);
            $(".kajian_sel[value=\"y\"]").parent().addClass("active");
            $(".kajian_sel[value=\"n\"]").prop("checked", false).removeAttr("checked");
            $(".kajian_sel[value=\"n\"]").parent().removeClass("active");
            return false;
        });

        $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").change(function() {
            $(this).parent().parent().addClass("active");
            $(".kajian_sel[value=\"n\"]").prop("checked", true);
            $(".kajian_sel[value=\"n\"]").parent().addClass("active");
            $(".kajian_sel[value=\"y\"]").prop("checked", false).removeAttr("checked");
            $(".kajian_sel[value=\"y\"]").parent().removeClass("active");
            return false;
        });

        $(".kajian_sel").change(function() {
            if ($(this).val() === "n") {
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").prop("checked", false);
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").removeAttr("checked");
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"y\"]").parent().removeClass("active");
            } else {
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").prop("checked", false);
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").removeAttr("checked");
                $("input[type=\"radio\"][name=\"kajian_all\"][value=\"n\"]").parent().removeClass("active");
            }
        });

        $("body").on("keyup", ".resep_konsumsi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_takar", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_jlh_hari", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateResep(id);
        });

        $("body").on("select2:select", ".resep-obat", function(e) {
            var data = e.params.data;
            $(this).children("[value=\"" + data["id"] + "\"]").attr({
                "data-value": data["data-value"],
                "penjamin-list": data["penjamin-list"],
                "satuan-caption": data["satuan-caption"],
                "satuan-terkecil": data["satuan-terkecil"]
            });

            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            checkGenerateResep(id);

            if ($(this).val() != "none") {
                var dataKategoriPerObat = autoKategoriObat(data['id']);
                var kategoriObatDOM = "";
                if (dataKategoriPerObat.length > 0) {
                    for (var kategoriObatKey in dataKategoriPerObat) {
                        if (
                            dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                            dataKategoriPerObat[kategoriObatKey].kategori !== null
                        ) {
                            kategoriObatDOM += "<span class=\"badge badge-info badge-custom-caption resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                        }
                    }
                }

                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />" + kategoriObatDOM);

                var penjaminAvailable = ($(this).find("option:selected").attr("penjamin-list") !== undefined) ? $(this).find("option:selected").attr("penjamin-list").split(",") : [];
                checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);

                var satuanCaption = $(this).find("option:selected").attr("satuan-caption");
                $("#resep_satuan_" + id).html(satuanCaption);
                rebaseResep();
            } else {
                $("#resep_obat_" + id).parent().find("div.penjamin-container").html("");
                $("#resep_satuan_" + id).html("");
                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />");
            }
        });

        $("body").on("change", ".aturan-pakai-resep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            checkGenerateResep(id);
        });


        $("body").on("change", ".aturan-pakai-racikan", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            checkGenerateRacikan(id);
        });



        $("body").on("click", ".resep_delete", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if (!$("#resep_row_" + id).hasClass("last-resep")) {
                $("#resep_row_" + id).remove();
            }

            rebaseResep();
            //$("#table-resep tbody tr").each(function(e));
        });

        $("#btnSelesai").click(function() {
            //$("#form-alasan-edit").modal("show");
            var resep = [];
            $("#table-resep tbody tr").each(function() {
                var obat = $(this).find("td:eq(1) select.resep-obat").val();
                var aturanPakai = $(this).find("td:eq(1) select.aturan-pakai-resep").val();
                var keteranganPerObat = $(this).find("td:eq(1) textarea").val();
                var iterasi = $(this).find("td:eq(1) input.resep-iterasi").inputmask("unmaskedvalue");
                var satuanPemakaian = $(this).find("td:eq(1) input.resep-satuan-pemakaian").val();
                /*var signaKonsumsi = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
                var signaTakar = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");*/
                var signaKonsumsi = $(this).find("td:eq(2) input").val();
                var signaTakar = $(this).find("td:eq(4) input").val();
                var signaHari = $(this).find("td:eq(5) input").inputmask("unmaskedvalue");
                //var penjamin = $(this).find("td:eq(6) select").val();
                if (
                    obat !== undefined &&
                    obat !== "none" &&
                    obat !== "" &&

                    /*parseFloat(signaKonsumsi) > 0 &&
                    parseFloat(signaTakar) > 0 &&*/
                    parseFloat(signaKonsumsi) !== "" &&
                    parseFloat(signaTakar) !== "" &&
                    parseFloat(signaHari) > 0

                ) {
                    resep.push({
                        "obat": obat,
                        "aturanPakai": parseInt(aturanPakai),
                        "keteranganPerObat": keteranganPerObat,
                        "signaKonsumsi": signaKonsumsi,
                        "signaTakar": signaTakar,
                        "signaHari": signaHari,
                        "iterasi": iterasi,
                        "satuanPemakaian": satuanPemakaian
                    });
                }
            });

            var racikan = [];
            $("#resep-racikan tbody.racikan tr.racikan-master").each(function() {
                var masterRacikanRow = $(this);
                var dataRacikan = {
                    "nama": "",
                    "item": [],
                    "keterangan": "",
                    "satuan_konsumsi": "",
                    "iterasi": 0,
                    "signaKonsumsi": 0,
                    "signaTakar": 0,
                    "signaHari": 0,
                    "aturanPakai": 0
                };

                dataRacikan.nama = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.nama_racikan").val();
                dataRacikan.aturanPakai = (masterRacikanRow.find("td.master-racikan-cell:eq(1) select").val() === "none") ? 0 : parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(1) select").val());
                dataRacikan.keterangan = masterRacikanRow.find("td.master-racikan-cell:eq(1) textarea").val();
                dataRacikan.iterasi = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.racikan_iterasi").inputmask("unmaskedvalue");
                dataRacikan.satuan_konsumsi = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.satuan_konsumsi").val();
                /*dataRacikan.signaKonsumsi = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(2) input").inputmask("unmaskedvalue"));
                dataRacikan.signaTakar = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(4) input").inputmask("unmaskedvalue"));*/
                dataRacikan.signaKonsumsi = masterRacikanRow.find("td.master-racikan-cell:eq(2) input").val();
                dataRacikan.signaTakar = masterRacikanRow.find("td.master-racikan-cell:eq(4) input").val();
                dataRacikan.signaHari = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(5) input").inputmask("unmaskedvalue"));




                masterRacikanRow.find("td:eq(1) table.komposisi-racikan tbody.komposisi-item tr.komposisi-row").each(function() {
                    var obat = $(this).find("td:eq(1)").attr("uid-obat");
                    //var qty = $(this).find("td:eq(2)").html();
                    var takaranBulat = $(this).find("td:eq(2) b").html();
                    var takaranDecimal = $(this).find("td:eq(2) sub").attr("nilaiExact");
                    var takaranDecimalText = $(this).find("td:eq(2) sub").html();
                    var takaranKekuatan = $(this).find("td:eq(2) h6").html();
                    var takaran = parseFloat(takaranBulat) + parseFloat(takaranDecimal);

                    if (obat !== undefined) {
                        dataRacikan.item.push({
                            "obat": obat,
                            "takaranBulat": takaranBulat,
                            "takaranDecimal": takaranDecimal,
                            "takaranDecimalText": takaranDecimalText,
                            "takaran": (isNaN(takaran) ? 1 : takaran),
                            "kekuatan": takaranKekuatan
                        });
                    }
                });

                if (
                    dataRacikan.nama !== "" &&
                    dataRacikan.item.length > 0 &&

                    /*dataRacikan.signaKonsumsi > 0 &&
                    dataRacikan.signaTakar > 0 &&*/
                    dataRacikan.signaKonsumsi !== "" &&
                    dataRacikan.signaTakar !== "" &&
                    dataRacikan.signaHari > 0
                ) {
                    racikan.push(dataRacikan);
                }
            });

            var keteranganResepData = keteranganResep.getData();
            var keteranganRacikanData = keteranganRacikan.getData();
            var iterasi = $("#iterasi_resep").inputmask("unmaskedvalue");
            var alergiObat = $("#alergi_obat").val();

            if (resep.length > 0 || racikan.length > 0) { // Minimal ada 1
                console.clear();
                console.log(resep);
                console.log(racikan);
                console.log(keteranganResepData);
                console.log(keteranganRacikanData);
            }
        });

        $("#btnSubmitAlasan").click(function() {
            var alasan = $("#txt_alasan_perubahan").val();
            var resep = [];
            $("#table-resep tbody tr").each(function() {
                var obat = $(this).find("td:eq(1) select.resep-obat").val();
                var aturanPakai = $(this).find("td:eq(1) select.aturan-pakai-resep").val();
                var keteranganPerObat = $(this).find("td:eq(1) textarea").val();
                var iterasi = $(this).find("td:eq(1) input.resep-iterasi").inputmask("unmaskedvalue");
                var satuanPemakaian = $(this).find("td:eq(1) input.resep-satuan-pemakaian").val();
                /*var signaKonsumsi = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
                var signaTakar = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");*/
                var signaKonsumsi = $(this).find("td:eq(2) input").val();
                var signaTakar = $(this).find("td:eq(4) input").val();
                var signaHari = $(this).find("td:eq(5) input").inputmask("unmaskedvalue");
                //var penjamin = $(this).find("td:eq(6) select").val();
                if (
                    obat !== undefined &&
                    obat !== "none" &&
                    obat !== "" &&

                    /*parseFloat(signaKonsumsi) > 0 &&
                    parseFloat(signaTakar) > 0 &&*/
                    parseFloat(signaKonsumsi) !== "" &&
                    parseFloat(signaTakar) !== "" &&
                    parseFloat(signaHari) > 0

                ) {
                    resep.push({
                        "obat": obat,
                        "aturanPakai": parseInt(aturanPakai),
                        "keteranganPerObat": keteranganPerObat,
                        "signaKonsumsi": signaKonsumsi,
                        "signaTakar": signaTakar,
                        "signaHari": signaHari,
                        "iterasi": iterasi,
                        "satuanPemakaian": satuanPemakaian
                    });
                }
            });

            var keteranganResepData = keteranganResep.getData();
            var keteranganRacikanData = keteranganRacikan.getData();

            var racikan = [];
            $("#resep-racikan tbody.racikan tr.racikan-master").each(function() {
                var masterRacikanRow = $(this);
                var dataRacikan = {
                    "nama": "",
                    "item": [],
                    "keterangan": "",
                    "iterasi": 0,
                    "signaKonsumsi": 0,
                    "signaTakar": 0,
                    "signaHari": 0,
                    "aturanPakai": 0
                };

                dataRacikan.nama = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.nama_racikan").val();
                dataRacikan.aturanPakai = (masterRacikanRow.find("td.master-racikan-cell:eq(1) select").val() === "none") ? 0 : parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(1) select").val());
                dataRacikan.keterangan = masterRacikanRow.find("td.master-racikan-cell:eq(1) textarea").val();
                dataRacikan.iterasi = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.racikan_iterasi").inputmask("unmaskedvalue");
                dataRacikan.signaKonsumsi = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(2) input").inputmask("unmaskedvalue"));
                dataRacikan.signaTakar = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(4) input").inputmask("unmaskedvalue"));
                dataRacikan.signaHari = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(5) input").inputmask("unmaskedvalue"));

                masterRacikanRow.find("td:eq(1) table.komposisi-racikan tbody.komposisi-item tr.komposisi-row").each(function() {
                    var obat = $(this).find("td:eq(1)").attr("uid-obat");
                    //var qty = $(this).find("td:eq(2)").html();
                    var takaranBulat = $(this).find("td:eq(2) b").html();
                    var takaranDecimal = $(this).find("td:eq(2) sub").attr("nilaiExact");
                    var takaranDecimalText = $(this).find("td:eq(2) sub").html();
                    var takaranKekuatan = $(this).find("td:eq(2) h6").html();
                    var takaran = parseFloat(takaranBulat) + parseFloat(takaranDecimal);

                    if (obat !== undefined) {
                        dataRacikan.item.push({
                            "obat": obat,
                            "takaranBulat": takaranBulat,
                            "takaranDecimal": takaranDecimal,
                            "takaranDecimalText": takaranDecimalText,
                            "takaran": (isNaN(takaran) ? 1 : takaran),
                            "kekuatan": takaranKekuatan
                        });
                    }
                });

                if (
                    dataRacikan.nama !== "" &&
                    dataRacikan.item.length > 0 &&

                    dataRacikan.signaKonsumsi > 0 &&
                    dataRacikan.signaTakar > 0 &&
                    dataRacikan.signaHari > 0
                ) {
                    racikan.push(dataRacikan);
                }
            });


            //Simpan Data
            Swal.fire({
                title: 'Selesai Membuat Resep?',
                text: 'Resep akan dikirimkan kepada verifikator apotek menggantikan resep sebelumnya. Segala alasan perubahan data resep akan dilaporkan',
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                    /*console.log({
                        asesmen: asesmen,
                        kunjungan: kunjungan,
                        antrian: antrian,
                        pasien: pasien,
                        penjamin: penjamin,
                        editorAlergiObat: $("#alergi_obat").val(),
                        iterasi: $("#iterasi_resep").val(),
                        charge_invoice: "Y",
                        keteranganResep: keteranganResepData,
                        keteranganRacikan: keteranganRacikanData,
                        resep: resep,
                        racikan: racikan,
                        alasan: alasan
                    });*/
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Apotek",
                        type: "POST",
                        data: {
                            request: "extend_resep",
                            asesmen: asesmen,
                            kunjungan: kunjungan,
                            antrian: antrian,
                            pasien: pasien,
                            penjamin: penjamin,
                            editorAlergiObat: $("#alergi_obat").val(),
                            iterasi: $("#iterasi_resep").val(),
                            charge_invoice: "Y",
                            keteranganResep: keteranganResepData,
                            keteranganRacikan: keteranganRacikanData,
                            resep: resep,
                            racikan: racikan,
                            alasan: alasan
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            location.href = __HOSTNAME__ + "/resep"
                        },
                        error: function(response) {
                            //
                        }
                    });
                }
            });
        });

        function populateAllKajian() {
            var populateData = {};
            $(".kajian_sel").each(function() {
                var currentName = $(this).attr("name");
                if (populateData[currentName] === undefined) {
                    //populateData[currentName] = "n";
                    populateData[currentName] = "";
                }
                if ($(this).is(':checked')) {
                    populateData[currentName] = $(this).val();
                }
            });

            return populateData;
        }

        function checkPenjaminAvail(currentPenjamin, penjaminList, targetRow) {
            if (penjaminList.length > 0) {
                if (penjaminList.indexOf(currentPenjamin) > 0) {
                    //$("#resep_obat_" + targetRow).parent().find("div.penjamin-container").html("<b class=\"badge badge-success obat-penjamin-notifier\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>");
                } else {
                    //$("#resep_obat_" + targetRow).parent().find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                }
            } else {
                //$("#resep_obat_" + targetRow).parent().find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
            }
        }

        class MyUploadAdapter {
            static loader;
            constructor(loader) {
                // CKEditor 5's FileLoader instance.
                this.loader = loader;

                // URL where to send files.
                this.url = __HOSTAPI__ + "/Upload";

                this.imageList = [];
            }

            // Starts the upload process.
            upload() {
                return new Promise((resolve, reject) => {
                    this._initRequest();
                    this._initListeners(resolve, reject);
                    this._sendRequest();
                });
            }

            // Aborts the upload process.
            abort() {
                if (this.xhr) {
                    this.xhr.abort();
                }
            }

            // Example implementation using XMLHttpRequest.
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();

                xhr.open('POST', this.url, true);
                xhr.setRequestHeader("Authorization", 'Bearer ' + <?php echo json_encode($_SESSION["admin_ciscard"]); ?>);
                xhr.responseType = 'json';
            }

            // Initializes XMLHttpRequest listeners.
            _initListeners(resolve, reject) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;

                xhr.addEventListener('error', () => reject(genericErrorText));
                xhr.addEventListener('abort', () => reject());
                xhr.addEventListener('load', () => {
                    const response = xhr.response;

                    if (!response || response.error) {
                        return reject(response && response.error ? response.error.message : genericErrorText);
                    }

                    // If the upload is successful, resolve the upload promise with an object containing
                    // at least the "default" URL, pointing to the image on the server.
                    resolve({
                        default: response.url
                    });
                });

                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', evt => {
                        if (evt.lengthComputable) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    });
                }
            }


            // Prepares the data and sends the request.
            _sendRequest() {
                const toBase64 = file => new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = error => reject(error);
                });
                var Axhr = this.xhr;

                async function doSomething(fileTarget) {
                    fileTarget.then(function(result) {
                        var ImageName = result.name;

                        toBase64(result).then(function(renderRes) {
                            const data = new FormData();
                            data.append('upload', renderRes);
                            data.append('name', ImageName);
                            Axhr.send(data);
                        });
                    });
                }

                var ImageList = this.imageList;

                this.loader.file.then(function(toAddImage) {

                    ImageList.push(toAddImage.name);

                });

                this.imageList = ImageList;

                doSomething(this.loader.file);
            }
        }


        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                var MyCust = new MyUploadAdapter(loader);
                var dataToPush = MyCust.imageList;
                hiJackImage(dataToPush);
                return MyCust;
            };
        }

        var imageResultPopulator = [];

        function hiJackImage(toHi) {
            imageResultPopulator.push(toHi);
        }

        if (__PAGES__[2] === "none") {
            //TODO: Create New Resep
        } else {
            $.ajax({
                url: __HOSTAPI__ + "/Apotek/detail_resep_2/" + __PAGES__[2],
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    var data = response.response_package[0];
                    console.clear();
                    console.log(data);

                    if (__PAGES__[2] === "none") {
                        asesmen = __PAGES__[3];
                        kunjungan = __PAGES__[4];
                        antrian = __PAGES__[5];
                        penjamin = __PAGES__[6];
                        pasien_penjamin_uid = __PAGES__[6];
                        pasien = __PAGES__[7];
                        allowEdit = true;
                        currentAsesmen = __PAGES__[3];
                        targetKodeResep = "";
                    } else {
                        asesmen = data.asesmen_uid;
                        kunjungan = data.asesmen.kunjungan;
                        antrian = data.asesmen.antrian;
                        penjamin = data.detail.penjamin.uid;
                        pasien_penjamin_uid = data.detail.penjamin.uid;
                        pasien = data.detail.pasien.uid;
                        allowEdit = data.detail.allow_edit;
                        currentAsesmen = data.asesmen.uid;
                        targetKodeResep = data.kode;



                        ClassicEditor
                            .create(document.querySelector("#txt_keterangan_resep"), {
                                extraPlugins: [MyCustomUploadAdapterPlugin],
                                placeholder: "Keterangan Resep",
                                removePlugins: ['MediaEmbed']
                            })
                            .then(editor => {
                                editor.setData(data.keterangan);
                                keteranganResep = editor;
                            })
                            .catch(err => {
                                //console.error( err.stack );
                            });

                        ClassicEditor
                            .create(document.querySelector("#txt_keterangan_resep_racikan"), {
                                extraPlugins: [MyCustomUploadAdapterPlugin],
                                placeholder: "Keterangan Racikan",
                                removePlugins: ['MediaEmbed']
                            })
                            .then(editor => {
                                editor.setData(data.keterangan_racikan);
                                keteranganRacikan = editor;
                            })
                            .catch(err => {
                                //console.error( err.stack );
                            });







                        $("#iterasi_resep").val(parseInt(data.iterasi));
                        $("#alergi_obat").val(data.alergi_obat);

                        if (data.asesmen.diagnosa_kerja !== undefined && data.asesmen.diagnosa_kerja !== "" && data.asesmen.diagnosa_kerja !== null) {
                            $("#diagnosa_utama").html(data.asesmen.diagnosa_kerja);
                            $("#no-data-diagnosa-utama").hide();
                        } else {
                            $("#no-data-diagnosa-utama").show();
                        }

                        if (data.asesmen.diagnosa_banding !== undefined && data.asesmen.diagnosa_banding !== "" && data.asesmen.diagnosa_banding !== null) {
                            $("#diagnosa_banding").html(data.asesmen.diagnosa_banding);
                            $("#no-data-diagnosa-banding").hide();
                        } else {
                            $("#no-data-diagnosa-banding").show();
                        }

                        if (data.asesmen.icd_kerja !== undefined && data.asesmen.icd_kerja !== null) {
                            var icd_kerja = data.asesmen.icd_kerja;
                            if (icd_kerja !== undefined && icd_kerja !== null) {
                                for (var icdA in icd_kerja) {
                                    if (icd_kerja[icdA] !== undefined && icd_kerja[icdA] !== null) {
                                        $("#icd_utama").append("<li>" +
                                            "<b><span class=\"text-info\">" + icd_kerja[icdA].kode + "</span> - " + icd_kerja[icdA].nama + "</b>" +
                                            "</li>");
                                    }
                                }
                            }
                        }



                        if (data.asesmen.icd_banding !== undefined && data.asesmen.icd_banding !== null) {
                            var icd_banding = data.asesmen.icd_banding;
                            if (icd_banding !== undefined && icd_banding !== null) {
                                for (var icdB in icd_banding) {
                                    if (icd_banding[icdB] !== undefined && icd_banding[icdB] !== null) {
                                        $("#icd_banding").append("<li>" +
                                            "<b><span class=\"text-info\">" + icd_banding[icdB].kode + "</span> - " + icd_banding[icdB].nama + "</b>" +
                                            "</li>");
                                    }
                                }
                            }
                        }


                        if (data.resep !== undefined) {
                            currentMetaData = data.detail;
                            if (
                                currentMetaData.departemen === undefined ||
                                currentMetaData.departemen === null
                            ) {
                                currentMetaData.departemen = {
                                    uid: __POLI_INAP__,
                                    nama: "Rawat Inap"
                                };
                            }
                            $(".nama_pasien").html((currentMetaData.pasien.panggilan_name !== null) ? currentMetaData.pasien.panggilan_name.nama + " " + currentMetaData.pasien.nama : currentMetaData.pasien.nama);
                            $(".jk_pasien").html((currentMetaData.pasien.jenkel_detail !== undefined && currentMetaData.pasien.jenkel_detail !== null) ? currentMetaData.pasien.jenkel_detail.nama : "");
                            $(".tanggal_lahir_pasien").html(currentMetaData.pasien.tanggal_lahir_parsed);
                            $(".penjamin_pasien").html(currentMetaData.penjamin.nama);
                            $(".poliklinik").html(currentMetaData.departemen.nama);
                            $(".dokter").html(currentMetaData.dokter.nama);
                            $("#copy-resep-dokter").html(currentMetaData.dokter.nama);
                            $("#copy-resep-pasien").html((currentMetaData.pasien.panggilan_name !== null) ? currentMetaData.pasien.panggilan_name.nama + " " + currentMetaData.pasien.nama : currentMetaData.pasien.nama);
                            $("#copy-resep-tanggal").html(data.created_at_parsed);

                            if (data.resep.length > 0) {

                                var resep_obat_detail = data.resep;

                                keterangan_resep = data.resep[0].keterangan;
                                keterangan_racikan = data.resep[0].keterangan_racikan;

                                for (var resepKey in resep_obat_detail) {
                                    autoResep({
                                        "obat": resep_obat_detail[resepKey].obat,
                                        "obat_detail": resep_obat_detail[resepKey].obat_detail,
                                        "aturan_pakai": resep_obat_detail[resepKey].aturan_pakai,
                                        "keterangan": resep_obat_detail[resepKey].keterangan,
                                        "signaKonsumsi": resep_obat_detail[resepKey].signa_qty,
                                        "signaTakar": resep_obat_detail[resepKey].signa_pakai,
                                        "signaHari": resep_obat_detail[resepKey].qty,
                                        "iterasi": resep_obat_detail[resepKey].iterasi,
                                        "satuan_pemakaian": resep_obat_detail[resepKey].satuan_konsumsi,
                                        "qty_roman": resep_obat_detail[resepKey].qty_roman
                                    });
                                    if (currentData.resep[resep_obat_detail[resepKey].obat] === undefined) {
                                        currentData.resep[resep_obat_detail[resepKey].obat] = {
                                            "aturan_pakai": 0,
                                            "signaKonsumsi": 0,
                                            "signaTakar": 0,
                                            "signaHari": 0
                                        };
                                    }

                                    currentData.resep[resep_obat_detail[resepKey].obat] = {
                                        "aturan_pakai": resep_obat_detail[resepKey].aturan_pakai,
                                        "signaKonsumsi": resep_obat_detail[resepKey].signa_qty,
                                        "signaTakar": resep_obat_detail[resepKey].signa_pakai,
                                        "signaHari": resep_obat_detail[resepKey].qty
                                    };
                                }
                            } else {
                                //$("#table-resep tbody").append("<tr><td colspan=\"9\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada resep</td></tr>");
                            }

                            var racikan_detail = data.racikan;
                            if (racikan_detail.length === 0) {
                                //$("#table-resep-racikan tbody.racikan").append("<tr><td colspan=\"8\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada racikan</td></tr>");
                            } else {
                                for (var racikanKey in racikan_detail) {
                                    autoRacikan({
                                        uid: racikan_detail[racikanKey].uid,
                                        nama: racikan_detail[racikanKey].kode,
                                        keterangan: racikan_detail[racikanKey].keterangan,
                                        signaKonsumsi: racikan_detail[racikanKey].signa_qty,
                                        signaTakar: racikan_detail[racikanKey].signa_pakai,
                                        signaHari: racikan_detail[racikanKey].qty,
                                        item: racikan_detail[racikanKey].item,
                                        iterasi: racikan_detail[racikanKey].iterasi,
                                        aturan_pakai: racikan_detail[racikanKey].aturan_pakai,
                                        qty_roman: racikan_detail[racikanKey].qty_roman
                                    });

                                    if (currentData.racikan[racikan_detail[racikanKey].uid] === undefined) {
                                        currentData.racikan[racikan_detail[racikanKey].uid] = {
                                            signaKonsumsi: 0,
                                            signaTakar: 0,
                                            signaHari: 0,
                                            item: [],
                                            aturan_pakai: 0
                                        };
                                    }

                                    currentData.racikan[racikan_detail[racikanKey].uid] = {
                                        signaKonsumsi: racikan_detail[racikanKey].signa_qty,
                                        signaTakar: racikan_detail[racikanKey].signa_pakai,
                                        signaHari: racikan_detail[racikanKey].qty,
                                        item: [],
                                        aturan_pakai: racikan_detail[racikanKey].aturan_pakai
                                    };

                                    var itemKomposisi = racikan_detail[racikanKey].item;

                                    for (var komposisiKey in itemKomposisi) {
                                        var penjaminObatRacikanListUID = [];
                                        var penjaminObatRacikanList = itemKomposisi[komposisiKey].obat_detail.penjamin;
                                        for (var penjaminObatKey in penjaminObatRacikanList) {
                                            if (penjaminObatRacikanListUID.indexOf(penjaminObatRacikanList[penjaminObatKey].penjamin) < 0) {
                                                penjaminObatRacikanListUID.push(penjaminObatRacikanList[penjaminObatKey].penjamin);
                                            }
                                        }

                                        itemKomposisi[komposisiKey].satuan = "<b>" + itemKomposisi[komposisiKey].takar_bulat + "</b><sub nilaiExact=\"" + itemKomposisi[komposisiKey].ratio + "\">" + itemKomposisi[komposisiKey].takar_decimal + "</sub>";
                                        itemKomposisi[komposisiKey].racikan = racikan_detail[racikanKey].uid;
                                        var totalKomposisi = autoKomposisi((parseInt(racikanKey) + 1), itemKomposisi[komposisiKey], racikan_detail[racikanKey].qty);

                                        currentData.racikan[racikan_detail[racikanKey].uid].item.push({
                                            obat: itemKomposisi[komposisiKey].obat,
                                            kekuatan: itemKomposisi[komposisiKey].kekuatan,
                                            jumlah: racikan_detail[racikanKey].qty
                                        });
                                    }
                                }
                                totalRacikan = calculate_racikan();
                            }


                            if (racikan_detail.length > 0) {
                                //autoRacikan();
                            }

                            $("#total_biaya_obat").html("Rp. " + number_format((totalResep + totalRacikan), 2, ".", ","));
                        }
                    }


                    if (cppt === "true") {
                        $("#btnCancel").attr({
                            href: __HOSTNAME__ + "/pasien/dokter/view/" + pasien + "/" + antrian
                        });
                    } else {
                        $("#btnCancel").attr({
                            href: __HOSTNAME__ + "/resep"
                        });
                    }



                    // if (allowEdit) {
                    //     $("#btnSelesai").show();
                    // } else {
                    //     $("#btnSelesai").hide();
                    // }




                    // if (allowEdit) {
                    //     autoResep();
                    //     autoRacikan();
                    // }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }




        function checkGenerateResep(id = 0) {
            if ($(".last-resep").length == 0) {
                autoResep();
            } else {
                var obat = $("#resep_obat_" + id).val();
                var jlh_hari = $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue");
                var signa_konsumsi = $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue");
                var signa_hari = $("#resep_signa_takar_" + id).inputmask("unmaskedvalue");
                var aturanPakai = $("#resep_aturan_pakai_" + id).val();
                if (
                    parseFloat(jlh_hari) > 0 &&
                    parseFloat(signa_konsumsi) > 0 &&
                    parseFloat(signa_hari) > 0 &&
                    obat != null &&
                    $("#resep_row_" + id).hasClass("last-resep")
                    //&& parseInt(aturanPakai) > 0
                ) {
                    autoResep();
                } else {
                    if (aturanPakai === "none") {
                        //notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Aturan pakai harus diisi", 1000, "aturan_pakai_" + id, "#resep_aturan_pakai_" + id);
                    }
                }
            }
        }








        function autoResep(setter = {
            "obat": "",
            "obat_detail": {},
            "aturan_pakai": 0,
            "keterangan": "",
            "signaKonsumsi": 0,
            "signaTakar": 0,
            "signaHari": 0,
            "pasien_penjamin_uid": "",
            "satuan_pemakaian": ""
        }) {
            $("#table-resep tbody tr").removeClass("last-resep");
            var newRowResep = document.createElement("TR");
            $(newRowResep).addClass("last-resep");
            var newCellResepID = document.createElement("TD");
            var newCellResepObat = document.createElement("TD");
            var newCellResepJlh = document.createElement("TD");
            var newCellResepSatuan = document.createElement("TD");
            var newCellResepSigna1 = document.createElement("TD");
            var newCellResepSigna2 = document.createElement("TD");
            var newCellResepSigna3 = document.createElement("TD");
            var newCellResepPenjamin = document.createElement("TD");
            var newCellResepAksi = document.createElement("TD");

            var newObat = document.createElement("SELECT");
            $(newCellResepObat).append(newObat);

            $(newCellResepObat).append(
                "<div class=\"row\" style=\"padding-top: 5px;\">" +
                "<div class=\"col-md-12\"><br /></div>" +
                "<div style=\"position: relative\" class=\"col-md-12 penjamin-container text-right\"></div>" +
                "<!--div class=\"col-md-7 aturan-pakai-container\"><span>Aturan Pakai</span></div-->" +
                "<div class=\"col-md-12 kategori-obat-container\"><!--span>Kategori Obat</span><br /--></div>" +
                "<div class=\"col-md-12\"><br /></div>" +
                "<div class=\"col-md-6 iterasi-container\"><span>Iterasi</span><br /></div><div class=\"col-md-6 satuan-pemakaian-container\"><span>Satuan Pemakaian</span><br /></div>" +
                "<div class=\"col-md-12\"><br /></div>" +
                "<div style=\"position: relative; padding-top: 5px;\" class=\"col-md-12 keterangan-container\"></div>" +
                "</div>");
            var newAturanPakai = document.createElement("SELECT");
            var dataAturanPakai = autoAturanPakai();
            //$(newCellResepObat).find("div.aturan-pakai-container").append(newAturanPakai);

            $(newAturanPakai).addClass("form-control aturan-pakai-resep");
            $(newAturanPakai).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            for (var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakai).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }

            var newSatuanPemakaian = document.createElement("INPUT");
            $(newSatuanPemakaian).addClass("form-control resep-satuan-pemakaian").val(setter.satuan_pemakaian).attr({
                "placeholder": "Ex : PULV, PUFF"
            });
            $(newCellResepObat).find("div.satuan-pemakaian-container").append(newSatuanPemakaian);

            var newIterasi = document.createElement("INPUT");
            $(newIterasi).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).addClass("form-control resep-iterasi").attr({
                "placeholder": "0"
            }).val((setter.iterasi == 0) ? "" : setter.iterasi);
            $(newCellResepObat).find("div.iterasi-container").append(newIterasi);


            var keteranganPerObat = document.createElement("TEXTAREA");
            $(newCellResepObat).find("div.keterangan-container").append("<span>Keterangan / Aturan Pemakaian</span>").append(keteranganPerObat);
            $(keteranganPerObat).addClass("form-control").attr({
                "placeholder": "Keterangan per Obat"
            }).val(setter.keterangan);

            var itemData = [];
            var parsedItemData = [];
            var obatNavigator = [];
            for (var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for (var penjaminKey in penjaminListData) {
                    if (penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }

                obatNavigator.push(itemData[dataKey].uid);
                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: "<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            $(newCellResepSatuan).html((setter.obat_detail !== undefined && setter.obat_detail.satuan_terkecil_info !== undefined) ? setter.obat_detail.satuan_terkecil_info.nama : "");

            $(newObat).addClass("form-control resep-obat").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function() {
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type": "application/json",
                    },
                    url: __HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function(term) {
                        return {
                            search: term.term,
                            penjamin: pasien_penjamin_uid
                        };
                    },
                    cache: true,
                    processResults: function(response) {
                        console.clear();
                        console.log(response);
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                    "html": "<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
                placeholder: "Pilih Obat",
                selectOnClose: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            }).on("select2:select", function(e) {
                var data = e.params.data;
                var identifier = $(this).attr("id").split("_");
                identifier = identifier[identifier.length - 1];


                $(this).children("[value=\"" + data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });

                checkGenerateResep(data["id"]);

                //============KATEGORI OBAT

                if (setter.obat !== "") {
                    if ($(newObat).val() != "none") {
                        var dataKategoriPerObat = autoKategoriObat(setter.obat);
                        var kategoriObatDOM = "";
                        if (dataKategoriPerObat.length > 0) {
                            for (var kategoriObatKey in dataKategoriPerObat) {
                                if (
                                    dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                                    dataKategoriPerObat[kategoriObatKey].kategori !== null
                                ) {
                                    kategoriObatDOM += "<span class=\"badge badge-info badge-custom-caption resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                                }
                            }
                            $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                        }
                    }

                    var penjaminAvailable = [];
                    if (data["penjamin-list"] !== undefined) {
                        var penjaminString = data["penjamin-list"] + "";
                        penjaminAvailable = penjaminString.split(",");
                    }

                    if (penjaminAvailable.length > 0) {
                        if (penjaminAvailable.indexOf(setter.pasien_penjamin_uid) > 0) {
                            //$(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-success obat-penjamin-notifier\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>");
                        } else {
                            //$(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                        }
                    } else {
                        //$(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                    }
                }
                $("#resep_satuan_" + identifier).html(data.satuan_terkecil);
            });

            if (setter.obat != "") {
                $(newObat).append("<option title=\"" + setter.obat_detail.nama + "\" value=\"" + setter.obat + "\" penjamin-list=\"" + setter.obat_detail.penjamin.join(",") + "\">" + setter.obat_detail.nama + "</option>");
                $(newObat).select2("data", {
                    id: setter.obat,
                    text: setter.obat_detail.nama
                });
                $(newObat).trigger("change");

                if ($(newObat).val() != "none") {
                    var dataKategoriPerObat = autoKategoriObat(setter.obat);
                    var kategoriObatDOM = "";
                    if (dataKategoriPerObat.length > 0) {
                        for (var kategoriObatKey in dataKategoriPerObat) {
                            if (
                                dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                                dataKategoriPerObat[kategoriObatKey].kategori !== null
                            ) {
                                kategoriObatDOM += "<span class=\"badge badge-info badge-custom-caption resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                            }
                        }
                        $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                    }
                }

                /*var penjaminAvailable = [];
                if(data["penjamin-list"] !== undefined) {
                    var penjaminString = data["penjamin-list"] + "";
                    penjaminAvailable = penjaminString.split(",");
                }

                if(penjaminAvailable.length > 0) {
                    if(penjaminAvailable.indexOf(setter.pasien_penjamin_uid) > 0) {
                        $(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-success obat-penjamin-notifier\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>");
                    } else {
                        $(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                    }
                } else {
                    $(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                }*/

                //$(newCellResepSatuan).html(data["satuan-caption"]);
            }



            var newJumlah = document.createElement("INPUT");
            $(newCellResepJlh).append(newJumlah);
            $(newJumlah).addClass("form-control resep_jlh_hari").inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).attr({
                "placeholder": "0"
            }).val((setter.signaHari == 0) ? "" : setter.signaHari);

            var newKonsumsi = document.createElement("INPUT");
            $(newCellResepSigna1).append(newKonsumsi);
            $(newKonsumsi).addClass("form-control resep_konsumsi text-right").attr({
                    "placeholder": "0"
                })
                /*.inputmask({
                                alias: 'decimal',
                                rightAlign: true,
                                placeholder: "0.00",
                                prefix: "",
                                autoGroup: false,
                                digitsOptional: true
                            })*/
                .val((setter.signaKonsumsi == 0) ? "" : setter.signaKonsumsi);

            $(newCellResepSigna2).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newTakar = document.createElement("INPUT");
            $(newCellResepSigna3).append(newTakar);
            $(newTakar).addClass("form-control resep_takar text-right").attr({
                    "placeholder": "0"
                })
                /*.inputmask({
                                alias: 'decimal',
                                rightAlign: true,
                                placeholder: "0.00",
                                prefix: "",
                                autoGroup: false,
                                digitsOptional: true
                            })*/
                .val((setter.signaTakar == 0) ? "" : setter.signaTakar);


            var newDeleteResep = document.createElement("BUTTON");
            $(newCellResepAksi).append(newDeleteResep);
            $(newDeleteResep).addClass("btn btn-sm btn-danger resep_delete").html("<i class=\"fa fa-ban\"></i>");

            $(newRowResep).append(newCellResepID);
            $(newRowResep).append(newCellResepObat);
            $(newRowResep).append(newCellResepSigna1);
            $(newRowResep).append(newCellResepSigna2);
            $(newRowResep).append(newCellResepSigna3);
            $(newRowResep).append(newCellResepJlh);
            $(newRowResep).append(newCellResepSatuan);
            $(newRowResep).append(newCellResepAksi);
            $("#table-resep").append(newRowResep);

            rebaseResep();
        }

        function rebaseResep() {
            $("#table-resep tbody tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "resep_row_" + id
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");

                $(this).find("td:eq(1) select.resep-obat").attr({
                    "id": "resep_obat_" + id
                });

                $(this).find("td:eq(1) select:eq(1)").attr({
                    "id": "resep_aturan_pakai_" + id
                });

                $(this).find("td:eq(1) input.resep-iterasi").attr({
                    "id": "resep_iterasi_" + id
                });

                $(this).find("td:eq(1) input.resep-satuan-pemakaian").attr({
                    "id": "resep_satuan_pemakaian_" + id
                });

                //load_product_resep($(this).find("td:eq(1) select.resep-obat"), "");
                if ($(this).find("td:eq(1) select.resep-obat").val() != "none") {
                    /*var penjaminAvailable = $(this).find("td:eq(1) select option:selected").attr("penjamin-list").split(",");
                    checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);*/
                }

                $(this).find("td:eq(2) input:eq(0)").attr({
                    "id": "resep_signa_konsumsi_" + id
                });
                $(this).find("td:eq(4) input:eq(0)").attr({
                    "id": "resep_signa_takar_" + id
                });
                $(this).find("td:eq(5) input").attr({
                    "id": "resep_jlh_hari_" + id
                });
                $(this).find("td:eq(6)").attr({
                    "id": "resep_satuan_" + id
                });
                $(this).find("td:eq(7) button").attr({
                    "id": "resep_delete_" + id
                });
            });
        }

        function calculate_racikan() {
            var totalRacikan = 0;
            $("#table-resep-racikan > tbody.racikan > tr").each(function(e) {
                var id = (e + 1);
                var batchHarga = $("#racikan_harga_" + id).attr("harga");
                //totalRacikan += parseFloat(batchHarga);

                var currentPriceRacikan = 0;

                $("#komposisi_" + id + " tbody tr").each(function(f) {
                    var harga = $(this).find("td:eq(1) ol").attr("harga");
                    var qty = parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));

                    if (qty < 1 || isNaN(qty)) {
                        currentPriceRacikan += 0
                    } else {
                        currentPriceRacikan += (qty * harga)
                    }
                });

                $("#racikan_harga_" + id).attr({
                    "harga": currentPriceRacikan
                }).html(number_format(currentPriceRacikan, 2, ".", ","));

                totalRacikan += currentPriceRacikan;
            });

            $("#total_resep_racikan").html(number_format(totalRacikan, 2, ".", ",")).attr({
                "harga": totalRacikan
            });

            return totalRacikan;
        }

        function calculate_resep() {
            var totalResep = 0;
            $("#table-resep tbody tr").each(function(e) {
                var id = (e + 1);
                var batchHarga = $("#harga_obat_" + id).attr("harga");
                totalResep += parseFloat(batchHarga);
            });

            $("#total_resep_biasa").html(number_format(totalResep, 2, ".", ",")).attr({
                "harga": totalResep
            });

            return totalResep;
        }



        function autoAturanPakai() {
            var dataAturanPakai;
            $.ajax({
                url: __HOSTAPI__ + "/Terminologi/terminologi-items/15",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    dataAturanPakai = response.response_package.response_data;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return dataAturanPakai;

        }

        function autoKategoriObat(obat) {
            var kategoriObat;
            $.ajax({
                url: __HOSTAPI__ + "/Inventori/kategori_per_obat/" + obat,
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    kategoriObat = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return kategoriObat;
        }








        function autoRacikan(setter = {
            "nama": "",
            "keterangan": "",
            "signaKonsumsi": "",
            "signaTakar": "",
            "signaHari": "",
            "aturan_pakai": "",
            "iterasi": 0,
            "item": []
        }) {
            $("#table-resep-racikan tbody.racikan tr").removeClass("last-racikan");
            var newRacikanRow = document.createElement("TR");
            $(newRacikanRow).addClass("last-racikan racikan-master");

            var newRacikanCellID = document.createElement("TD");
            var newRacikanCellNama = document.createElement("TD");
            var newRacikanCellSignaA = document.createElement("TD");
            var newRacikanCellSignaX = document.createElement("TD");
            var newRacikanCellSignaB = document.createElement("TD");
            var newRacikanCellJlh = document.createElement("TD");
            var newRacikanCellAksi = document.createElement("TD");

            $(newRacikanCellID).addClass("master-racikan-cell");
            $(newRacikanCellNama).addClass("master-racikan-cell");
            $(newRacikanCellSignaA).addClass("master-racikan-cell");
            $(newRacikanCellSignaX).addClass("master-racikan-cell");
            $(newRacikanCellSignaB).addClass("master-racikan-cell");
            $(newRacikanCellJlh).addClass("master-racikan-cell");
            $(newRacikanCellAksi).addClass("master-racikan-cell");

            var newRacikanNama = document.createElement("INPUT");
            $(newRacikanCellNama).append(newRacikanNama);
            $(newRacikanNama).addClass("form-control nama_racikan").css({
                "margin-bottom": "20px"
            }).attr({
                "placeholder": "Nama Racikan"
            }).val(setter.nama);

            $(newRacikanCellNama).append(
                "<h6 style=\"padding-bottom: 10px;\">" +
                "Komposisi:" +
                "<button style=\"margin-left: 20px;\" class=\"btn btn-sm btn-info tambahKomposisi\"" +
                "<i class=\"fa fa-plus\"></i> Tambah" +
                "</button>" +
                "</h6>" +
                "<table class=\"table table-bordered komposisi-racikan largeDataType\" style=\"margin-top: 10px;\">" +
                "<thead class=\"thead-dark\">" +
                "<tr>" +
                "<th class=\"wrap_content\">No</th>" +
                "<th>Obat</th>" +
                /*"<th class=\"wrap_content\">@</th>" +*/
                /*"<th>Takaran</th>" +*/
                "<th>Kekuatan</th>" +
                "<th class=\"wrap_content\">Aksi</th>" +
                "<tr>" +
                "</thead>" +
                "<tbody class=\"komposisi-item\"></tbody>" +
                "</table>"
            );

            var newAturanPakaiRacikan = document.createElement("SELECT");

            var dataAturanPakai = autoAturanPakai();

            $(newAturanPakaiRacikan).addClass("form-control aturan-pakai-racikan");



            var newKeteranganRacikan = document.createElement("TEXTAREA");
            //$(newRacikanCellNama).append("<br /><span>Aturan Pakai</span>").append(newAturanPakaiRacikan).append("<br /><br /><span>Keterangan</span>").append(newKeteranganRacikan);
            var satuanKonsumsi = document.createElement("INPUT");
            $(satuanKonsumsi).addClass("form-control satuan_konsumsi").attr({
                "placeholder": "Ex : PULV, PUFF"
            });
            $(newRacikanCellNama).append("<br /><span>Satuan Pemakaian</span>").append(satuanKonsumsi).append("<br /><br /><span>Keterangan / Aturan Pakai</span>").append(newKeteranganRacikan);
            $(newAturanPakaiRacikan).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            for (var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakaiRacikan).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }
            $(newKeteranganRacikan).addClass("form-control").attr({
                "placeholder": "Keterangan racikan"
            }).val(setter.keterangan);

            var newIterasiRacikan = document.createElement("INPUT");
            $(newIterasiRacikan).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).addClass("form-control racikan_iterasi").attr({
                "placeholder": "0"
            }).val((parseInt(setter.iterasi) === 0) ? "" : parseInt(setter.iterasi));

            $(newRacikanCellNama).append("<br /><span>Iterasi</span>").append(newIterasiRacikan);



            /*var newRacikanObat = document.createElement("SELECT");
            var newObatTakar = document.createElement("INPUT");
            $(newRacikanCellObat).append(newRacikanObat);
            var addAnother = load_product_resep(newRacikanObat, "");
            $(newRacikanCellObat).append("<br /><b>Takaran</b>");
            $(newRacikanCellObat).append(newObatTakar);
            $(newRacikanObat).addClass("form-control").select2();
            $(newObatTakar).addClass("form-control");*/

            var newRacikanSignaA = document.createElement("INPUT");
            $(newRacikanCellSignaA).append(newRacikanSignaA);
            $(newRacikanSignaA).addClass("form-control racikan_signa_a").attr({
                "placeholder": "0"
            }).val(setter.signaKonsumsi)
            /*.inputmask({
                            alias: 'decimal',
                            rightAlign: true,
                            placeholder: "0.00",
                            prefix: "",
                            autoGroup: false,
                            digitsOptional: true
                        })*/
            ;

            $(newRacikanCellSignaX).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newRacikanSignaB = document.createElement("INPUT");
            $(newRacikanCellSignaB).append(newRacikanSignaB);
            $(newRacikanSignaB).addClass("form-control racikan_signa_b").attr({
                "placeholder": "0"
            }).val(setter.signaTakar)
            /*.inputmask({
                            alias: 'decimal',
                            rightAlign: true,
                            placeholder: "0.00",
                            prefix: "",
                            autoGroup: false,
                            digitsOptional: true
                        })*/
            ;

            var newRacikanJlh = document.createElement("INPUT");
            $(newRacikanCellJlh).append(newRacikanJlh);
            $(newRacikanJlh).addClass("form-control racikan_signa_jlh").attr({
                "placeholder": "0"
            }).val(setter.signaHari).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });

            var newRacikanDelete = document.createElement("BUTTON");
            $(newRacikanCellAksi).append(newRacikanDelete);
            $(newRacikanDelete).addClass("btn btn-danger btn-sm btn-delete-racikan").html("<i class=\"fa fa-ban\"></i>");

            $(newRacikanRow).append(newRacikanCellID);
            $(newRacikanRow).append(newRacikanCellNama);
            $(newRacikanRow).append(newRacikanCellSignaA);
            $(newRacikanRow).append(newRacikanCellSignaX);
            $(newRacikanRow).append(newRacikanCellSignaB);
            $(newRacikanRow).append(newRacikanCellJlh);
            $(newRacikanRow).append(newRacikanCellAksi);

            $("#table-resep-racikan tbody.racikan").append(newRacikanRow);
            rebaseRacikan();
        }

        function rebaseRacikan() {
            $("#table-resep-racikan > tbody.racikan > tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "row_racikan_" + id
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");

                $(this).find("td:eq(1) input.nama_racikan").attr({
                    "id": "racikan_nama_" + id
                }).val("Racikan " + id);

                if ($(this).find("td:eq(1) input.nama_racikan") == "") {
                    $(this).find("td:eq(1) input.nama_racikan").val("RACIKAN " + id);
                }

                $(this).find("td:eq(1) table").attr({
                    "id": "komposisi_" + id
                });

                $(this).find("td:eq(1) button.tambahKomposisi").attr({
                    "id": "tambah_komposisi_" + id
                });

                $(this).find("td:eq(1) select.aturan-pakai-racikan").attr({
                    "id": "aturan_pakai_racikan_" + id
                });

                $(this).find("td:eq(1) input.racikan_iterasi").attr({
                    "id": "iterasi_racikan_" + id
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "racikan_signaA_" + id
                });

                $(this).find("td:eq(4) input").attr({
                    "id": "racikan_signaB_" + id
                });

                $(this).find("td:eq(5) input").attr({
                    "id": "racikan_jumlah_" + id
                });

                $(this).find("td:eq(6) button").attr({
                    "id": "racikan_delete_" + id
                });
            });
        }


        function autoKomposisi(id, setter = {}) {
            if (setter.obat != undefined || $("#komposisi_" + id + " tbody tr").length == 0 || $("#komposisi_" + id + " tbody tr:last-child td:eq(1)").html() != "") {
                var newKomposisiRow = document.createElement("TR");
                $(newKomposisiRow).addClass("komposisi-row");

                var newKomposisiCellID = document.createElement("TD");
                var newKomposisiCellObat = document.createElement("TD");
                //\var newKomposisiCellJumlah = document.createElement("TD");
                var newKomposisiCellSatuan = document.createElement("TD");
                var newKomposisiCellAksi = document.createElement("TD");



                var newKomposisiEdit = document.createElement("BUTTON");
                $(newKomposisiEdit).addClass("btn btn-sm btn-info btn_edit_komposisi").html("<i class=\"fa fa-pencil-alt\"></i>");

                var newKomposisiDelete = document.createElement("BUTTON");
                $(newKomposisiDelete).addClass("btn btn-sm btn-danger btn_delete_komposisi").html("<i class=\"fa fa-ban\"></i>");

                $(newKomposisiCellAksi).append("<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"></div>");
                $(newKomposisiCellAksi).find("div").append(newKomposisiEdit);
                $(newKomposisiCellAksi).find("div").append(newKomposisiDelete);

                $(newKomposisiRow).append(newKomposisiCellID);
                $(newKomposisiRow).append(newKomposisiCellObat);
                //$(newKomposisiRow).append(newKomposisiCellJumlah);
                $(newKomposisiRow).append(newKomposisiCellSatuan);
                $(newKomposisiRow).append(newKomposisiCellAksi);

                $("#komposisi_" + id + " tbody").append(newKomposisiRow);

                /*if($("#komposisi_" + id + " tbody tr").length == 1) {
                    //autoModal
                    prepareModal(id);
                }*/
                if (setter.obat != undefined) {
                    $(newKomposisiCellObat).attr({
                        "uid-obat": setter.obat
                    }).html(setter.obat_detail.nama.toUpperCase());

                    //$(newKomposisiCellJumlah).html(setter.ratio);
                    $(newKomposisiCellSatuan).html("<h6>" + setter.kekuatan + "</h6>");
                } else {
                    prepareModal(id);
                }

                rebaseKomposisi(id);
            }
        }

        function rebaseKomposisi(id) {
            $("#komposisi_" + id + " tbody tr").each(function(e) {
                var cid = (e + 1);

                $(this).attr({
                    "id": "single_komposisi_" + cid
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + cid + "</h5>");
                $(this).find("td:eq(1)").attr({
                    "id": "obat_komposisi_" + id + "_" + cid
                });
                /*$(this).find("td:eq(2)").attr({
                    "id": "jlh_komposisi_" + id + "_" + cid
                });*/
                $(this).find("td:eq(2)").attr({
                    "id": "takar_komposisi_" + id + "_" + cid
                });
                $(this).find("td:eq(3) button:eq(0)").attr({
                    "id": "button_edit_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(3) button:eq(1)").attr({
                    "id": "button_delete_komposisi_" + id + "_" + cid
                });
            });
        }

        function load_product_resep(target, selectedData = "", appendData = true) {
            var selected = [];
            var productData = [];

            $(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function() {
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type": "application/json",
                    },
                    url: __HOSTAPI__ + "/Inventori/get_item_select2/" + $(".select2-search__field").val(),
                    type: "GET",
                    data: function(term) {
                        return {
                            search: term.term
                        };
                    },
                    cache: true,
                    processResults: function(response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.uid,
                                    satuan_terkecil: item.satuan_terkecil.nama
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                if (data.satuan_terkecil != undefined) {
                    $(this).children("[value=\"" + data.id + "\"]").attr({
                        "satuan-caption": data.satuan_terkecil
                    });
                } else {
                    return false;
                }
            });

            return {
                allow: true,
                data: []
            };
        }

        function prepareModal(id, setData = {
            obat: "",
            jlh: "",
            takarBulat: 1,
            takarDesimal: "",
            kekuatan: ""
        }) {
            $("#form-editor-racikan").modal("show");
            $("#modal-large-title").html($("#racikan_nama_" + id).val());

            //$("#txt_racikan_jlh").val(setData.jlh);
            //$("#txt_racikan_takar").val(setData.takar);
            $("#txt_racikan_takar").val(setData.takarDesimal);
            $("#txt_racikan_takar_bulat").val(setData.takarBulat);
            $("#txt_racikan_kekuatan").val(setData.kekuatan);

            var modalProduct = load_product_resep("#txt_racikan_obat", setData.obat, false);
            var itemData = modalProduct.data;
            var parsedItemData = [];
            for (var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for (var penjaminKey in penjaminListData) {
                    if (penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }


                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: "<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            $("#txt_racikan_obat").addClass("form-control").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function() {
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type": "application/json",
                    },
                    url: __HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function(term) {
                        return {
                            search: term.term
                        };
                    },
                    cache: true,
                    processResults: function(response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                    "html": "<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
                selectOnClose: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            }).val(setData.obat).trigger("change").on("select2:select", function(e) {
                var data = e.params.data;
                $(this).children("[value=\"" + data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });
            });

            if (setData.obat != "") {
                $("#txt_racikan_obat").append("<option title=\"" + setData.obat_nama + "\" value=\"" + setData.obat + "\">" + setData.obat_nama + "</option>");
                $("#txt_racikan_obat").select2("data", {
                    id: setData.obat,
                    text: setData.obat_nama
                });
                $("#txt_racikan_obat").trigger("change");
            }
        }

        $("#txt_racikan_obat").select2();
        /*$("#txt_racikan_jlh").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });*/

        var currentRacikID = 1;
        var currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length;
        var komposisiMode = "add";

        $("body").on("click", ".btn_edit_komposisi", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];


            prepareModal(Pid, {
                obat: $("#obat_komposisi_" + Pid + "_" + thisID).attr("uid-obat"),
                obat_nama: $("#obat_komposisi_" + Pid + "_" + thisID).text(),
                takarBulat: $("#takar_komposisi_" + Pid + "_" + thisID).find("b").html(),
                takarDesimal: $("#takar_komposisi_" + Pid + "_" + thisID).find("sub").html(),
                kekuatan: $("#takar_komposisi_" + Pid + "_" + thisID).find("h6").html()
            });

            currentKomposisiID = thisID;
            currentRacikID = Pid;
        });

        $("body").on("click", ".btn-delete-racikan", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            $("#row_racikan_" + thisID).remove();
            rebaseRacikan();
        });

        $("body").on("click", ".btn_delete_komposisi", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];

            $("#single_komposisi_" + thisID).remove();
            rebaseKomposisi(Pid);
            return false;
        });

        $("body").on("click", ".tambahKomposisi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentRacikID = id;
            var currentRacikanUID = $(this).attr("uid-racikan");
            currentRacikanActive = currentRacikanUID;
            currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length + 1;

            autoKomposisi(id);
        });

        $("body").on("click", "#btnSubmitKomposisi", function() {
            var infoPenjamin = "";
            if ($("#txt_racikan_obat").find("option:selected").attr("penjamin-list") !== undefined) {
                var penjaminCheck = $("#txt_racikan_obat").find("option:selected").attr("penjamin-list").split(",");
                if (penjaminCheck.length > 0) {
                    if (penjaminCheck.indexOf(pasien_penjamin_uid) > 0) {
                        //infoPenjamin = "<b class=\"badge badge-success pull-rigth\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>";
                    } else {
                        //infoPenjamin = "<b class=\"badge badge-danger pull-rigth\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>";
                    }
                } else {
                    //infoPenjamin = "<b class=\"badge badge-danger pull-rigth\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>";
                }

                $("#obat_komposisi_" + currentRacikID + "_" + currentKomposisiID)
                    .html($("#txt_racikan_obat").find("option:selected").text() + infoPenjamin)
                    .attr({
                        "uid-obat": $("#txt_racikan_obat").val()
                    });
            }
            //autoRacikan();
            checkGenerateRacikan(currentRacikID);
            //$("#jlh_komposisi_" + currentRacikID + "_" + currentKomposisiID).html($("#txt_racikan_jlh").val());
            $("#takar_komposisi_" + currentRacikID + "_" + currentKomposisiID).html("<b style=\"font-size: 15pt; display: none\">" + $("#txt_racikan_takar_bulat").val() + "</b><sub nilaiExact=\"" + eval($("#txt_racikan_takar").val()) + "\">" + $("#txt_racikan_takar").val() + "</sub><h6>" + $("#txt_racikan_kekuatan").val() + "</h6>");
            //if($("#txt_racikan_jlh").val() != "" && $("#txt_racikan_takar").val()) {
            $("#form-editor-racikan").modal("hide");
            //}
        });

        $("body").on("keyup", ".racikan_signa_a", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });

        $("body").on("keyup", ".racikan_signa_b", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });

        $("body").on("keyup", ".racikan_signa_jlh", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });



        $("body").on("click", ".resep_delete", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if (!$("#resep_row_" + id).hasClass("last-resep")) {
                $("#resep_row_" + id).remove();
            }

            totalResep = rebaseResep();
            //$("#table-resep tbody tr").each(function(e));
        });

        function CheckVerifRacikan(newData, id, data, oldData, alasanLib = {}) {
            if (data.uid === undefined) {
                console.log("False idenfier");
            } else {
                var oldRacikan = oldData.racikan;
                var itemNew = [];
                $("#komposisi_" + id + " tbody tr").each(function(e) {
                    var komposisiID = (e + 1);
                    itemNew.push({
                        obat: $(this).find("td:eq(1) h6").attr("uid-obat"),
                        kekuatan: $("#takar_komposisi_" + id + "_" + komposisiID).html(),
                        jumlah: $("#jlh_komposisi_" + id + "_" + komposisiID).inputmask("unmaskedvalue")
                    });
                });

                if (newData.racikan[data.uid] === undefined) {
                    newData.racikan[data.uid] = {
                        signaKonsumsi: $("#racikan_signaA_" + id).inputmask("unmaskedvalue"),
                        signaTakar: $("#racikan_signaB_" + id).inputmask("unmaskedvalue"),
                        signaHari: $("#racikan_jumlah_" + id).inputmask("unmaskedvalue"),
                        item: [],
                        aturan_pakai: $("#racikan_aturan_pakai_" + id + " option:selected").val()
                    };
                } else {
                    newData.racikan[data.uid] = {
                        signaKonsumsi: $("#racikan_signaA_" + id).inputmask("unmaskedvalue"),
                        signaTakar: $("#racikan_signaB_" + id).inputmask("unmaskedvalue"),
                        signaHari: $("#racikan_jumlah_" + id).inputmask("unmaskedvalue"),
                        item: itemNew,
                        aturan_pakai: $("#racikan_aturan_pakai_" + id + " option:selected").val()
                    };
                }



                var isSame = true;
                if (oldRacikan[data.uid] === undefined) {
                    //createRacikanChangeReason(id, alasanLib);
                    isSame = false;
                } else {
                    if (
                        parseFloat(oldRacikan[data.uid].signaKonsumsi) === parseFloat($("#racikan_signaA_" + id).inputmask("unmaskedvalue")) &&
                        parseFloat(oldRacikan[data.uid].signaTakar) === parseFloat($("#racikan_signaB_" + id).inputmask("unmaskedvalue")) &&
                        parseFloat(oldRacikan[data.uid].signaHari) === parseFloat($("#racikan_jumlah_" + id).inputmask("unmaskedvalue")) &&
                        parseFloat(oldRacikan[data.uid].aturan_pakai) === parseFloat($("#racikan_aturan_pakai_" + id).inputmask("unmaskedvalue"))
                    ) {
                        if (newData.racikan[data.uid].item.length !== oldRacikan[data.uid].item.length) {
                            isSame = false;
                        } else {
                            var dataCheckNew = {};

                            for (var b in newData.racikan[data.uid].item) {
                                if (dataCheckNew[newData.racikan[data.uid].item[b].obat] === undefined) {
                                    dataCheckNew[newData.racikan[data.uid].item[b].obat] = {
                                        kekuatan: newData.racikan[data.uid].item[b].kekuatan,
                                        jumlah: newData.racikan[data.uid].item[b].jumlah
                                    };
                                }
                            }

                            if (Object.keys(dataCheckNew).length === 0 && dataCheckNew.constructor === Object) {
                                isSame = true;
                                /*$("#alasan_racikan_" + id).animate({
                                    "left": "50px",
                                    "opacity": "0"
                                }, function() {
                                    $("#alasan_racikan_" + id).remove();
                                });*/
                            } else {
                                for (var c in oldRacikan[data.uid].item) {
                                    if (dataCheckNew[oldRacikan[data.uid].item[c].obat] === undefined) {
                                        isSame = false;
                                        break;
                                    } else {
                                        if (
                                            dataCheckNew[oldRacikan[data.uid].item[c].obat].kekuatan === oldRacikan[data.uid].item[c].kekuatan &&
                                            parseFloat(dataCheckNew[oldRacikan[data.uid].item[c].obat].jumlah) === parseFloat(oldRacikan[data.uid].item[c].jumlah)
                                        ) {
                                            isSame = true;
                                        } else {
                                            isSame = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        isSame = false;
                    }
                }

                if (isSame) {
                    $("#alasan_racikan_" + id).animate({
                        "left": "50px",
                        "opacity": "0"
                    }, function() {
                        $("#alasan_racikan_" + id).remove();
                    });
                } else {
                    createRacikanChangeReason(id, alasanLib);
                }
            }
            calculate_racikan();
            return newData;
        }

        function CheckVerifResep(newData, id, data, alasanLib = {}) {
            if (newData.resep[data.id] === undefined) {
                newData.resep[data.id] = {
                    "aturan_pakai": $("#resep_obat_aturan_pakai_" + id + " option:selected").val(),
                    /*"signaKonsumsi": $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue"),
                    "signaTakar": $("#resep_signa_takar_" + id).inputmask("unmaskedvalue"),*/
                    "signaKonsumsi": $("#resep_signa_konsumsi_" + id).val(),
                    "signaTakar": $("#resep_signa_takar_" + id).val(),
                    "signaHari": $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")
                };
            } else {
                newData.resep[data.id] = {
                    "aturan_pakai": $("#resep_obat_aturan_pakai_" + id + " option:selected").val(),
                    /*"signaKonsumsi": $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue"),
                    "signaTakar": $("#resep_signa_takar_" + id).inputmask("unmaskedvalue"),*/
                    "signaKonsumsi": $("#resep_signa_konsumsi_" + id).val(),
                    "signaTakar": $("#resep_signa_takar_" + id).val(),
                    "signaHari": $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")
                };
            }

            //Compare New and Old Data
            //console.log($("#resep_obat_" + id).attr("old-data") + " /// " + data.id);
            if ($("#resep_obat_" + id).attr("old-data") !== data.id) {
                createResepChangeReason(id, alasanLib);
            } else {
                /*console.log("Compare aturan pakai : " + (parseFloat($("#resep_obat_aturan_pakai_" + id + " option:selected").val()) === parseFloat($("#resep_obat_aturan_pakai_" + id).attr("old-data"))));
                console.log("Compare konsumsi : " + (parseFloat($("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_konsumsi_" + id).attr("old-data"))));
                console.log("Compare takar : " + (parseFloat($("#resep_signa_takar_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_takar_" + id).attr("old-data"))));
                console.log("Compare hari : " + (parseFloat($("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_jlh_hari_" + id).attr("old-data"))));*/
                if (
                    parseFloat($("#resep_obat_aturan_pakai_" + id + " option:selected").val()) === parseFloat($("#resep_obat_aturan_pakai_" + id).attr("old-data")) &&
                    /*parseFloat($("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_konsumsi_" + id).attr("old-data")) &&
                    parseFloat($("#resep_signa_takar_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_takar_" + id).attr("old-data")) &&*/
                    $("#resep_signa_konsumsi_" + id).val() === $("#resep_signa_konsumsi_" + id).attr("old-data") &&
                    $("#resep_signa_takar_" + id).val() === $("#resep_signa_takar_" + id).attr("old-data") &&
                    parseFloat($("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_jlh_hari_" + id).attr("old-data"))
                ) {
                    $("#alasan_" + id).animate({
                        "left": "50px",
                        "opacity": "0"
                    }, function() {
                        $("#alasan_" + id).remove();
                    });
                } else {
                    createResepChangeReason(id, alasanLib);
                }
            }

            return newData;
        }

        function checkGenerateRacikan(id = 0) {
            if ($(".last-racikan").length === 0) {
                autoRacikan();
            } else {
                var obat = $("#racikan_nama_" + id).val();
                var komposisi = $("#komposisi_" + id + " tbody tr").length;
                var jlh_obat = $("#racikan_jumlah_" + id).inputmask("unmaskedvalue");
                var signa_konsumsi = $("#racikan_signaA_" + id).inputmask("unmaskedvalue");
                var signa_hari = $("#racikan_signaB_" + id).inputmask("unmaskedvalue");
                var aturanPakai = $("#aturan_pakai_racikan_" + id).val();

                if (
                    parseFloat(jlh_obat) > 0 &&
                    parseFloat(signa_konsumsi) > 0 &&
                    parseFloat(signa_hari) > 0 &&
                    $("#row_racikan_" + id).hasClass("last-racikan") &&
                    //aturanPakai !== "none" &&
                    komposisi > 0
                ) {
                    if (obat === "") {
                        $("#racikan_nama_" + id).val("Racikan " + id);
                    }
                    autoRacikan();
                } else {
                    if (aturanPakai === "none") {
                        //notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Aturan pakai harus diisi", 1000, "aturan_pakai_racikan_" + id, "#aturan_pakai_racikan_" + id);
                    }

                    if (komposisi === 0) {
                        notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Komposisi racikan belum diisi", 1000, "komposisi_" + id, "#komposisi_" + id);
                    }

                    if (signa_hari === 0 || signa_konsumsi === 0) {
                        notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Signa belum diisi", 1000, "racikan_signaA_" + id, "#racikan_signaA_" + id, "top");
                    }
                }
            }
        }

        function CheckVerifResep(newData, id, data, alasanLib = {}) {
            //console.clear();
            if (newData.resep[data.id] === undefined) {
                newData.resep[data.id] = {
                    "aturan_pakai": $("#resep_obat_aturan_pakai_" + id + " option:selected").val(),
                    "signaKonsumsi": $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue"),
                    "signaTakar": $("#resep_signa_takar_" + id).inputmask("unmaskedvalue"),
                    "signaHari": $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")
                };
            } else {
                newData.resep[data.id] = {
                    "aturan_pakai": $("#resep_obat_aturan_pakai_" + id + " option:selected").val(),
                    "signaKonsumsi": $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue"),
                    "signaTakar": $("#resep_signa_takar_" + id).inputmask("unmaskedvalue"),
                    "signaHari": $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")
                };
            }

            //Compare New and Old Data
            //console.log($("#resep_obat_" + id).attr("old-data") + " /// " + data.id);
            if ($("#resep_obat_" + id).attr("old-data") !== data.id) {
                createResepChangeReason(id, alasanLib);
            } else {
                /*console.log("Compare aturan pakai : " + (parseFloat($("#resep_obat_aturan_pakai_" + id + " option:selected").val()) === parseFloat($("#resep_obat_aturan_pakai_" + id).attr("old-data"))));
                console.log("Compare konsumsi : " + (parseFloat($("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_konsumsi_" + id).attr("old-data"))));
                console.log("Compare takar : " + (parseFloat($("#resep_signa_takar_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_takar_" + id).attr("old-data"))));
                console.log("Compare hari : " + (parseFloat($("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_jlh_hari_" + id).attr("old-data"))));*/
                if (
                    parseFloat($("#resep_obat_aturan_pakai_" + id + " option:selected").val()) === parseFloat($("#resep_obat_aturan_pakai_" + id).attr("old-data")) &&
                    parseFloat($("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_konsumsi_" + id).attr("old-data")) &&
                    parseFloat($("#resep_signa_takar_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_signa_takar_" + id).attr("old-data")) &&
                    parseFloat($("#resep_jlh_hari_" + id).inputmask("unmaskedvalue")) === parseFloat($("#resep_jlh_hari_" + id).attr("old-data"))
                ) {
                    $("#alasan_" + id).animate({
                        "left": "50px",
                        "opacity": "0"
                    }, function() {
                        $("#alasan_" + id).remove();
                    });
                } else {
                    createResepChangeReason(id, alasanLib);
                }
            }

            return newData;
        }

        function createResepChangeReason(autonum, alasanLib = {}) {
            if ($("#alasan_" + autonum).length === 0) {
                var reasonText = document.createElement("TEXTAREA");
                var totalWidth = 0;
                for (var a = 2; a <= 7; a++) {
                    totalWidth += $("#resep_row_" + autonum + " td:eq(" + a + ")").width();
                }

                $(reasonText).css({
                    "position": "absolute",
                    "bottom": "150px",
                    "left": "1rem",
                    "right": "1rem",
                    "top": "auto",
                    "width": totalWidth + "px",
                    "height": $("#resep_row_" + autonum + " td:eq(1) textarea").height() + "px",
                    "resize": "none"
                }).addClass("resep-reason form-control").attr({
                    "id": "alasan_" + autonum,
                    "placeholder": "Alasan Ubah Resep (WAJIB)"
                }).val((alasanLib["alasan_" + autonum] !== undefined) ? alasanLib["alasan_" + autonum].text : "");
                $("#resep_row_" + autonum + " td:eq(2)").append(reasonText);
            }
        }

    });
</script>

<div id="form-alasan-edit" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Perubahan/Penambahan Resep</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <strong>Alasan pembuatan resep:</strong>
                <textarea class="form-control" id="txt_alasan_perubahan" placeholder="Alasan Pembuatan Resep"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-primary" id="btnSubmitAlasan">
                    <span>
                        <i class="fa fa-save"></i> Simpan
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>



<div id="form-editor-racikan" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Komposisi Obat</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label for="txt_racikan_obat">Obat:</label>
                    <select class="form-control" id="txt_racikan_obat"></select>
                </div>
                <!-- <div class="form-group col-md-6">
                    <label for="txt_racikan_jlh">Jumlah:</label>
                    <input type="text" class="form-control" id="txt_racikan_jlh" />
                </div> -->
                <div class="form-group col-md-12">
                    <div class="kolom_kekuatan">
                        <label for="txt_racikan_kekuatan">Kekuatan:</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="txt_racikan_kekuatan" placeholder="0" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="kolom_takar" style="display: none">
                        <label for="txt_racikan_takar">Takar:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" value="1" class="form-control" id="txt_racikan_takar_bulat" placeholder="0" />
                            </div>
                            <div class="col-md-1">
                                <i class="fa fa-plus" style="margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="txt_racikan_takar" placeholder="a/b" />
                            </div>
                            <div class="col-md-3">
                                <small>Cth:<br />2 + 1/2</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group col-md-12">
                    <label for="txt_racikan_satuan">Satuan:</label>
                    <select class="form-control" id="txt_racikan_satuan"></select>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitKomposisi">Simpan</button>
            </div>
        </div>
    </div>
</div>