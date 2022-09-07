<script src="<?php echo __HOSTNAME__; ?>/plugins/DataTables/Responsive-2.2.5/js/dataTables.responsive.min.js"></script>
<link type="text/css" href="<?php echo __HOSTNAME__; ?>/plugins/DataTables/Responsive-2.2.5/css/responsive.dataTables.min.css" rel="stylesheet" />
<script type="text/javascript">
    $(function() {
        $("#cari_pasien").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Pasien tidak ditemukan";
                }
            },
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pasien/select2",
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
                                "id": item.uid,
                                "text": item.no_rm + " - " + item.nama
                            }
                        })
                    };
                }
            },
            placeholder: "Cari No RM / Nama Pasien",
            selectOnClose: true
        }).on("select2:select", function(e) {
            var data = e.params.data;
            CPPTData.ajax.reload();
        });

        $("#btn_clear_filter").click(function() {
            $("#cari_pasien option").remove();
            $("#cari_pasien").select2("data", null);
            $("#cari_pasien").trigger("change");
            CPPTData.ajax.reload();
        });

        // var openRows = new Array();

        // function closeOpenedRows(table, selectedRow) {
        //     $.each(openRows, function (index, openRow) {
        //         // not the selected row!
        //         if ($.data(selectedRow) !== $.data(openRow)) {
        //             var rowToCollapse = table.row(openRow);
        //             rowToCollapse.child.hide();
        //             openRow.removeClass('shown');
        //             // replace icon to expand
        //             $(openRow).find('td.control').html('<span class="glyphicon glyphicon-plus"></span>');
        //             // remove from list
        //             var index = $.inArray(selectedRow, openRows);
        //             openRows.splice(index, 1);
        //         }
        //     });
        // }

        $('#table-pasien tbody').on('click', 'td.control', function () {
            $('#table-pasien tbody tr.parent td.control sorting_1').removeAttr("style");
            $('#table-pasien tbody tr.parent').removeClass("parent");
            $('#table-pasien tbody tr.child').remove();
        });

        // $('#table-pasien tbody').on('click', 'td.control', function () {
        //     var tr = $(this).closest('tr');
        //     var row = CPPTData.row(tr);
    
        //     if (row.child.isShown()) {
        //         $(this).html('<span class="glyphicon glyphicon-plus"></span>');
        //         row.child.hide();
        //         tr.removeClass('shown');
        //     } else {
        //         closeOpenedRows(CPPTData, tr);
        //         $(this).html('<span class="glyphicon glyphicon-minus"></span>');
        //         row.child(format(row.data())).show();
        //         tr.addClass('shown');
        //         openRows.push(tr);
        //     }
        // });
        
        

        var CPPTData = $("#table-pasien").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            responsive: {
                details: {
                    type: 'column'
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }, {
                ordering: false,
                orderable: false,
                targets: 1
            }],
            "ajax":{
                url: __HOSTAPI__ + "/Pasien",
                type: "POST",
                data: function(d) {
                    d.request = "pasien_cppt";
                    d.pasien = $("#cari_pasien option:selected").val();
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = response.response_package.response_data;

                    
                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parsed + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nama_poli + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nama_dokter + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b class=\"text-info number_style\">" + row.no_rm.replaceAll("-", "") + "</b> " + row.nama_pasien;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var parsedView = "";
                        if(row.medis !== undefined && row.medis !== null) {
                            var keluhan_utama = (row.medis.keluhan_utama != '') ? row.medis.keluhan_utama : '<code>Tidak ada data</code>';
                            var keluhan_tambahan = (row.medis.keluhan_tambahan != '') ? row.medis.keluhan_tambahan : '<code>Tidak ada data</code>';
                            var diagnosa_kerja = (row.medis.diagnosa_kerja != '') ? row.medis.diagnosa_kerja : '<code>Tidak ada data</code>';
                            var diagnosa_banding = (row.medis.diagnosa_banding != '') ? row.medis.diagnosa_banding : '<code>Tidak ada data</code>';
                            var pemeriksaan_fisik = (row.medis.pemeriksaan_fisik != '') ? row.medis.pemeriksaan_fisik : '<code>Tidak ada data</code>';
                            var planning = (row.medis.planning != '') ? row.medis.planning : '<code>Tidak ada data</code>';
                            parsedView = "" +
                            "<div class=\"row card-group-row\">" +
                                "<div class=\"col-lg-6\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Keluhan Utama</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            keluhan_utama + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-6\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Keluhan Tambahan</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            keluhan_tambahan + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                            "<div class=\"row card-group-row\">" +
                                "<div class=\"col-lg-6\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Diagnosa Kerja</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            diagnosa_kerja + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-6\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Diagnosa Banding</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            diagnosa_banding + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                            "<div class=\"row card-group-row\">" +
                                "<div class=\"col-lg-6\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Pemeriksaan Fisik</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            pemeriksaan_fisik + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-6\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Planning</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            planning + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>";
                            return parsedView;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var parsedView = "";
                        if(row.rawat !== undefined && row.rawat !== null) {
                            console.log(row.rawat);
                            var kesadaran = (row.rawat.kesadaran != '') ? row.rawat.kesadaran : '<code>Tidak ada data</code>';
                            var cara_masuk = (row.rawat.cara_masuk != '') ? row.rawat.cara_masuk : '<code>Tidak ada data</code>';
                            var cara_masuk_lainnya = (row.rawat.cara_masuk_lainnya != '') ? row.rawat.cara_masuk_lainnya : '<code>Tidak ada data</code>';
                            var sikap_tubuh = (row.rawat.sikap_tubuh != '') ? row.rawat.sikap_tubuh : '<code>Tidak ada data</code>';
                            var berat_badan = (row.rawat.berat_badan != '') ? row.rawat.berat_badan : '<code>Tidak ada data</code>';
                            var tinggi_badan = (row.rawat.tinggi_badan != '') ? row.rawat.tinggi_badan : '<code>Tidak ada data</code>';




                            var tanda_vital_td = (row.rawat.tanda_vital_td != '') ? row.rawat.tanda_vital_td : '<code>Tidak ada data</code>';
                            var tanda_vital_n = (row.rawat.tanda_vital_n != '') ? row.rawat.tanda_vital_n : '<code>Tidak ada data</code>';
                            var tanda_vital_s = (row.rawat.tanda_vital_s != '') ? row.rawat.tanda_vital_s : '<code>Tidak ada data</code>';
                            var tanda_vital_rr = (row.rawat.tanda_vital_rr != '') ? row.rawat.tanda_vital_rr : '<code>Tidak ada data</code>';
                            // var kesadaran = (row.rawat.kesadaran != '') ? row.rawat.kesadaran : '<code>Tidak ada data</code>';


                            parsedView = "" +
                            "<div class=\"row card-group-row\">" +
                                "<div class=\"col-lg-8\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Kajian Keperawatan</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "<table class=\"table form-mode\">" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">Kesadaran</td><td class=\"wrap_content\">:</td><td>" + kesadaran + "</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">Sikap Tubuh</td><td class=\"wrap_content\">:</td><td>" + sikap_tubuh + "</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">Cara Masuk</td><td class=\"wrap_content\">:</td><td>" + cara_masuk + "</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">Berat Badan</td><td class=\"wrap_content\">:</td><td>" + berat_badan + "</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">Tinggi Badan</td><td class=\"wrap_content\">:</td><td>" + tinggi_badan + "</td>" +
                                                "</tr>" +
                                            "</table>" +
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-4\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Tanda Vital</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "<table class=\"table form-mode\">" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">TD</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_td + " mmHg</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">N</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_n + " x/mnt</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">S</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_s + " Celcius</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">RR</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_rr + " x/mnt</td>" +
                                                "</tr>" +
                                            "</table>" +
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                            "<div class=\"row card-group-row\">" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Riwayat Kesehatan</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "<table class=\"table form-mode\">" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">TD</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_td + " mmHg</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">N</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_n + " x/mnt</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">S</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_s + " Celcius</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td class=\"wrap_content\">RR</td><td class=\"wrap_content\">:</td><td>" + tanda_vital_rr + " x/mnt</td>" +
                                                "</tr>" +
                                            "</table>" +
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Riwayat Keluarga</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "diagnosa_banding" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                            "<div class=\"row card-group-row\">" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Riwayat Pernikahan</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "pemeriksaan_fisik" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Riwayat Menstruasi</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "planning" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Reproduksi</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "planning" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Riwayat Penyakit Ginekologi (Kebidanan)</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "planning" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Riwayat Kehamilan, Persalinan, dan Nifas yang Lalu (Kebidanan)</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "planning" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class=\"col-lg-12\">" +
                                    "<div class=\"card\">" +
                                        "<div class=\"card-header card-header-large bg-white d-flex align-items-center\">" +
                                            "<h5 class=\"card-header__title flex m-0\"><i class=\"fa fa-hashtag\"></i> Skala Nyeri</h5>" + 
                                        "</div>" +
                                        "<div class=\"card-body\">" +
                                            "planning" + 
                                        "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>";
                            return parsedView;
                        } else {
                            return "-";
                        }
                        return "<pre>" + JSON.stringify(row.rawat, undefined, 2) + "</pre>";
                    }
                }
            ]
        });
    });
</script>