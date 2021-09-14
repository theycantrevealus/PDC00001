<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
    $(function () {
        var globalEditor;
        var currentModule;
        var currentGroup = "";
        var currentID = 0;
        var groupMode = "add";
        var imageResultPopulator = [];

        function resetFormTutorGroup() {
            $("#txt_nama_group").val("")
        }

        function refreshGroup(modul) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Tutorial/load_group/" + modul,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.response_data;
                    $("#txt_tutor_group option").remove();
                    for(var a in data) {
                        $("#txt_tutor_group").append("<option value=\"" + data[a].uid + "\">" + data[a].nama + "</option>");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#txt_tutor_group").change(function () {
            currentGroup = $("#txt_tutor_group").val();
            tutor.ajax.reload();
        });

        function resetFormTutor() {
            $("#txt_nama").val("");
            $("#txt_type").val("");
            $("#txt_target_element").val("");
            $("#txt_remark").val("");
            $("#txt_tool_pos").val("top").change();
            $("#txt_progress").removeAttr("checked").prop("checked", false);
            $("#txt_bullet").removeAttr("checked").prop("checked", false);
            $("#txt_expect_dom").val("");
            $("#txt_expect_dom_type").val("none").change();
        }

        $("#btn-tambah-group").click(function () {
            if(currentModule !== undefined && currentModule !== null) {
                groupMode = "add";
                resetFormTutorGroup();
                $("#modal-tutor-group").modal("show");
            } else {
                Swal.fire(
                    "Tutorial Management",
                    "Pilih modul terlebih dahulu",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });

        $("#btn-edit-group").click(function () {
            if(currentGroup !== "") {
                groupMode = "edit";
                resetFormTutorGroup();
                $("#modal-tutor-group").modal("show");
                $("#txt_nama_group").val($("#txt_tutor_group option:selected").text());
            } else {
                Swal.fire(
                    "Tutorial Management",
                    "Group Tutor Tidak Ditemukan",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });

        $("#btn-hapus-group").click(function () {
            if(currentGroup !== "") {
                Swal.fire({
                    title: "Hapus Group " + $("#txt_tutor_group option:selected").text() + "?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url:__HOSTAPI__ + "/Tutorial",
                            type: "POST",
                            data: {
                                request: "delete_group",
                                uid: currentGroup
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response){
                                if(response.response_package.response_result > 0) {
                                    Swal.fire(
                                        "Tutorial Management",
                                        "Berhasil dihapus",
                                        "success"
                                    ).then((result) => {
                                        refreshGroup(currentModule);
                                        currentGroup = $("#txt_tutor_group").val();
                                        tutor.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Tutorial Management",
                                        "Gagal dihapus",
                                        "error"
                                    ).then((result) => {
                                        console.log(response);
                                    });
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    "Tutorial Management",
                    "Group Tutor Tidak Ditemukan",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });

        $("#btnProsesTutorGroup").click(function () {
            var nama = $("#txt_nama_group").val();
            if(nama !== "") {
                Swal.fire({
                    title: "Data sudah benar?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(groupMode === "edit") {
                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/Tutorial",
                                type: "POST",
                                data: {
                                    request: "update_group",
                                    uid: currentGroup,
                                    nama: nama,
                                    modul: currentModule
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response){
                                    if(response.response_package.response_result > 0) {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Berhasil diproses",
                                            "success"
                                        ).then((result) => {
                                            resetFormTutorGroup();
                                            refreshGroup(currentModule);
                                            currentGroup = $("#txt_tutor_group").val();
                                            tutor.ajax.reload();
                                            $("#modal-tutor-group").modal("hide");
                                        });
                                    } else {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Gagal diproses",
                                            "error"
                                        ).then((result) => {
                                            console.log(response);
                                        });
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        } else {
                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/Tutorial",
                                type: "POST",
                                data: {
                                    request: "add_group",
                                    uid: currentGroup,
                                    nama: nama,
                                    modul: currentModule
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response){
                                    if(response.response_package.response_result > 0) {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Berhasil diproses",
                                            "success"
                                        ).then((result) => {
                                            resetFormTutorGroup();
                                            refreshGroup(currentModule);
                                            currentGroup = $("#txt_tutor_group").val();
                                            tutor.ajax.reload();
                                            $("#modal-tutor-group").modal("hide");
                                        });
                                    } else {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Gagal diproses",
                                            "error"
                                        ).then((result) => {
                                            console.log(response);
                                        });
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        }
                    }
                });
            }
        });

        $("#btn-tambah-tutorial").click(function () {
            currentID = 0;
            if(currentModule !== undefined && currentModule !== null) {
                resetFormTutor();
                $("#modal-tutor").modal("show");
            } else {
                Swal.fire(
                    "Tutorial Management",
                    "Pilih modul terlebih dahulu",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });

        $("#btnProsesTutor").click(function () {
            var nama = $("#txt_nama").val();
            var type = $("#txt_type").val();
            var target = $("#txt_target_element").val();
            var remark = $("#txt_remark").val();
            var tool_pos = $("#txt_tool_pos").val();
            var progress = ($("#txt_progress").is(":checked")) ? "Y" : "N";
            var bullet = ($("#txt_bullet").is(":checked")) ? "Y" : "N";
            var expectDOM = $("#txt_expect_dom").val();
            var expectDOMType = $("#txt_expect_dom_type").val();

            console.log({
                request: "update_tutorial",
                tutor_group: currentGroup,
                id: currentID,
                modul: currentModule,
                nama: nama,
                type: type,
                target: target,
                remark: remark,
                tool_pos: tool_pos,
                progress: progress,
                bullet: bullet,
                expectDOM: expectDOM,
                expectDOMType: expectDOMType,
            });

            if(nama !== "") {
                Swal.fire({
                    title: "Data sudah benar?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(currentID > 0) {
                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/Tutorial",
                                type: "POST",
                                data: {
                                    request: "update_tutorial",
                                    tutor_group: currentGroup,
                                    id: currentID,
                                    modul: currentModule,
                                    nama: nama,
                                    type: type,
                                    target: target,
                                    remark: remark,
                                    tool_pos: tool_pos,
                                    progress: progress,
                                    bullet: bullet,
                                    expectDOM: expectDOM,
                                    expectDOMType: expectDOMType,
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response){
                                    if(response.response_package.response_result > 0) {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Berhasil diproses",
                                            "success"
                                        ).then((result) => {
                                            tutor.ajax.reload();
                                            $("#modal-tutor").modal("hide");
                                            resetFormTutor();
                                        });
                                    } else {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Gagal diproses",
                                            "error"
                                        ).then((result) => {
                                            console.log(response);
                                        });
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        } else {
                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/Tutorial",
                                type: "POST",
                                data: {
                                    request: "add_tutorial",
                                    modul: $("#txt_module").val(),
                                    tutor_group: currentGroup,
                                    nama: nama,
                                    type: type,
                                    target: target,
                                    remark: remark,
                                    tool_pos: tool_pos,
                                    progress: progress,
                                    bullet: bullet,
                                    expectDOM: expectDOM,
                                    expectDOMType: expectDOMType,
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response){
                                    if(response.response_package.response_result > 0) {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Berhasil diproses",
                                            "success"
                                        ).then((result) => {
                                            tutor.ajax.reload();
                                            $("#modal-tutor").modal("hide");
                                            resetFormTutor();
                                        });
                                    } else {
                                        Swal.fire(
                                            "Tutorial Management",
                                            "Gagal diproses",
                                            "error"
                                        ).then((result) => {
                                            console.log(response);
                                        });
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        }
                    }
                });
            } else {
                Swal.fire(
                    "Tutorial Management",
                    "Isi nama tutorial",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });

        $("#txt_tutor_group");

        $("#txt_module").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Modul tidak ditemukan";
                }
            },
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Modul/module_select2",
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
                                text: item.nama + " [ ../" + item.identifier + " ]",
                                id: item.id
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            currentID = 0;
            currentModule = $("#txt_module").val();
            refreshGroup(currentModule);
            if($("#txt_tutor_group option").length > 0) {
                currentGroup = $("#txt_tutor_group").val();
            } else {
                currentGroup = "";
            }

            tutor.ajax.reload();
        });

        var tutor = $("#table-tutor").DataTable({
            processing: true,
            serverSide: true,
            rowReorder: {
                dataSrc: "autonum"
            },
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Tutorial",
                type: "POST",
                data: function(d){
                    d.request = "get_tutorial";
                    d.module = currentModule;
                    d.tutor_group = currentGroup;
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    return dataSet;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Step Tutorial"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\"><i class=\"fa fa-arrows-alt\"></i> " + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.judul;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.remark;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"wrap_content\">" +
                            "<button modul=\"" + row.modul + "\" id=\"edit_tutor_" + row.id + "\" class=\"btn btn-sm btn-info btnEditTutor\"><span><i class=\"fa fa-eye\"></i> Edit</span></button>" +
                            "<button modul=\"" + row.modul + "\" id=\"hapus_tutor_" + row.id + "\" class=\"btn btn-sm btn-danger btnDeleteTutor\"><span><i class=\"fa fa-trash-alt\"></i> Delete</span></button>" +
                            "</div>";
                    }
                }
            ]
        });

        tutor.on( 'row-reorder', function ( e, diff, edit ) {
            var result = [];

            for (var i = 0, ien = diff.length ; i < ien ; i++ ) {
                var rowData = tutor.row(diff[i].node).data();
                result.push({
                    "id": rowData.id,
                    "position": (diff[i].newPosition + 1)
                });
            }

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Tutorial",
                data: {
                    request: "update_position",
                    data:result
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response) {
                    if(response.response_package > 0) {
                        notification ("success", "Tutor berhasil diurutkan", 3000, "tutor_order_result");
                    } else {
                        notification ("danger", "Tutor gagal diurutkan", 3000, "tutor_order_result");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("body").on("click", ".btnDeleteTutor", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentID = id;
            currentModule = $(this).attr("modul");

            Swal.fire({
                title: "Hapus Step Tutorial?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Tutorial",
                        type: "POST",
                        data: {
                            request: "delete_tutor",
                            id: id
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Tutorial Management",
                                    "Berhasil dihapus",
                                    "success"
                                ).then((result) => {
                                    //refreshGroup(currentModule);
                                    //currentGroup = $("#txt_tutor_group").val();
                                    tutor.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Tutorial Management",
                                    "Gagal dihapus",
                                    "error"
                                ).then((result) => {
                                    console.log(response);
                                });
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btnEditTutor", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentID = id;
            currentModule = $(this).attr("modul");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Tutorial/get_detail/" + id,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    resetFormTutor();
                    var data = response.response_package.response_data[0];
                    $("#txt_nama").val(data.judul);
                    $("#txt_type").val(data.type);
                    $("#txt_target_element").val(data.element_target);
                    $("#txt_remark").val(data.remark);
                    $("#txt_tool_pos").val(data.tooltip_pos).change();
                    $("#txt_expect_dom").val(data.trigger_dom);
                    $("#txt_expect_dom_type").val(data.trigger_dom_type).change();
                    if(data.show_progress === "Y"){
                        $("#txt_progress").prop("checked", true);
                    } else {
                        $("#txt_progress").prop("checked", false);
                    }

                    if(data.show_bullet === "Y"){
                        $("#txt_bullet").prop("checked", true);
                    } else {
                        $("#txt_bullet").prop("checked", false);
                    }

                    $("#modal-tutor").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
        //introJs().addHints();











        var FolderMODE = "add";
        var FolderParent = 0;
        var FolderEditID = 0;
        var FolderEditText = "";

        var FileMODE = "add";
        var FileEditID = 0;
        var FileEditText = "";

        var EditType = "folder";



        var jsTreeBuilder = $("#documentation-tree").jstree({
            "core": {
                "data":refreshTree(),
                "check_callback" : true
            },
            "themes" : {
                "responsive": false
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder text-warning"
                },
                "file" : {
                    "icon" : "fa fa-file  text-warning"
                }
            },
            "plugins" : ["search"]
        });

        function refreshTree() {
            var data;
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Documentation/get_structure",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    data = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return data;
        }

        $("#btnAddRootFolder").click(function() {
            FolderMODE = "add";
            $("#modal-manage-folder").modal("show");
        });

        $("#btnProsesFolder").click(function () {
            var name = $("#txt_nama_folder").val();
            if(name !== "") {
                Swal.fire({
                    title: "Proses Folder?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url:__HOSTAPI__ + "/Documentation",
                            type: "POST",
                            data: {
                                request: FolderMODE + "_folder",
                                id: FolderEditID,
                                parent: FolderParent,
                                name: name
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response){
                                jsTreeBuilder.jstree("deselect_all");
                                jsTreeBuilder.jstree(true).settings.core.data = refreshTree();
                                jsTreeBuilder.jstree(true).refresh();
                                jsTreeBuilder.jstree("open_all");
                                $("#txt_nama_folder").val("");
                                $("#modal-manage-folder").modal("hide");
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    "Documentation",
                    "Nama folder tidak boleh kosong",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });


        $("#btnProsesFile").click(function () {
            var name = $("#txt_nama_file").val();
            var content = globalEditor.getData();
            //imageResultPopulator
            var parsedHTML = $.parseHTML(content);

            for(var az in imageResultPopulator) {
                $(parsedHTML).find("img:eq(" + az + ")").attr({
                    "src": __HOST__ + "images/documentation/" + imageResultPopulator
                });
            }

            var parsedContent = $('<div>').append($(parsedHTML).clone()).html();

            if(name !== "") {
                Swal.fire({
                    title: "Proses File?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url:__HOSTAPI__ + "/Documentation",
                            type: "POST",
                            data: {
                                request: FileMODE + "_file",
                                id: FileEditID,
                                folder: FolderParent,
                                name: name,
                                content: parsedContent
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response) {
                                jsTreeBuilder.jstree("deselect_all");
                                jsTreeBuilder.jstree(true).settings.core.data = refreshTree();
                                jsTreeBuilder.jstree(true).refresh();
                                jsTreeBuilder.jstree("open_all");
                                //$("#txt_nama_file").val("");
                                //$("#modal-manage-file").modal("hide");
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    "Documentation",
                    "Nama folder tidak boleh kosong",
                    "error"
                ).then((result) => {
                    //
                });
            }
        });

        $("#documentation-tree").on("select_node.jstree", function (e, data) {
            FolderParent = data.node.id;
            EditType = data.node.original.itemType;
            if(EditType === "folder") {
                FolderEditID = data.node.original.id;
                FolderEditText = data.node.original.text;

                $(".custom-menu li[data-action=\"add_file\"]").show();
            } else {
                FileEditID = data.node.original.id;
                FileEditText = data.node.original.text;

                $(".custom-menu li[data-action=\"add_file\"]").hide();
            }
            /*selectedID = data.node.id;
            selectedCheckChild = data.node.data.childCount;
            selectedParent = data.node.data.parent;
            selectedNama = data.node.data.nama;
            selectedIdentifier = data.node.data.identifier;
            selectedKeterangan = data.node.data.keterangan;
            selectedIcon = data.node.data.icon;
            selectedShowOnMenu = data.node.data.show_on_menu;
            selectedShowOrder = data.node.data.show_order;
            selectedMenuGroup = data.node.data.menu_group;

            PARENT = selectedID;*/
            $(".custom-menu").finish().toggle(100).css({
                top: (event.pageY - $(".navbar-main").height()) + "px",
                left: (event.pageX - $(".simplebar-mask").width() - $(".jstree-container-ul li").width() + 100) + "px"
            });
        });

        $("body").bind("mousedown", function (e) {
            if (!$(e.target).parents(".custom-menu").length > 0) {
                $(".custom-menu").hide(100);
            }
        });

        $(".custom-menu li").click(function() {
            $("#txt_nama_folder").val("");
            $("#txt_nama_file").val("");
            globalEditor.setData("");
            switch ($(this).attr("data-action")) {
                case "add_folder":
                    FolderMODE = "add";
                    $("#modal-manage-folder").modal("show");
                    break;
                case "add_file":
                    FileMODE = "add";
                    //$("#modal-manage-file").modal("show");
                    break;
                case "edit_pos":
                    if(EditType === "folder") {
                        FolderMODE = "edit";
                        $("#modal-manage-folder").modal("show");
                        $("#txt_nama_folder").val(FolderEditText);
                    } else {
                        FileMODE = "edit";
                        //$("#modal-manage-file").modal("show");

                        $.ajax({
                            async: false,
                            url:__HOSTAPI__ + "/Documentation/file_detail/" + FileEditID,
                            type: "GET",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response) {
                                var data = response.response_package.response_data[0];
                                $("#txt_nama_file").val(FileEditText);
                                globalEditor.setData((data.content === undefined || data.content === null) ? "" :  data.content);
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                    break;
                default:
            }
            $(".custom-menu").hide(100);
        });



















        class MyUploadAdapter {
            static loader;
            constructor( loader ) {
                // CKEditor 5's FileLoader instance.
                this.loader = loader;

                // URL where to send files.
                this.url = __HOSTAPI__ + "/Upload";

                this.imageList = [];
            }

            // Starts the upload process.
            upload() {
                return new Promise( ( resolve, reject ) => {
                    this._initRequest();
                    this._initListeners( resolve, reject );
                    this._sendRequest();
                } );
            }

            // Aborts the upload process.
            abort() {
                if ( this.xhr ) {
                    this.xhr.abort();
                }
            }

            // Example implementation using XMLHttpRequest.
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();

                xhr.open( 'POST', this.url, true );
                xhr.setRequestHeader("Authorization", 'Bearer ' + <?php echo json_encode($_SESSION["token"]); ?>);
                xhr.responseType = 'json';
            }

            // Initializes XMLHttpRequest listeners.
            _initListeners( resolve, reject ) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;

                xhr.addEventListener( 'error', () => reject( genericErrorText ) );
                xhr.addEventListener( 'abort', () => reject() );
                xhr.addEventListener( 'load', () => {
                    const response = xhr.response;

                    if ( !response || response.error ) {
                        return reject( response && response.error ? response.error.message : genericErrorText );
                    }

                    // If the upload is successful, resolve the upload promise with an object containing
                    // at least the "default" URL, pointing to the image on the server.
                    resolve( {
                        default: response.url
                    } );
                } );

                if ( xhr.upload ) {
                    xhr.upload.addEventListener( 'progress', evt => {
                        if ( evt.lengthComputable ) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    } );
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
                            data.append( 'upload', renderRes);
                            data.append( 'name', ImageName);
                            Axhr.send( data );
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



        function hiJackImage(toHi) {
            imageResultPopulator.push(toHi);
        }


        function MyCustomUploadAdapterPlugin( editor ) {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                var MyCust = new MyUploadAdapter( loader );
                var dataToPush = MyCust.imageList;
                hiJackImage(dataToPush);
                return MyCust;
            };
        }










        ClassicEditor
            .create( document.querySelector( '.editor' ), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                placeholder: "Documentation...",
                removePlugins: ['MediaEmbed']
            } )
            .then( editor => {
                editor.editing.view.change( writer => {
                    writer.setStyle( 'min-height', '1000px', editor.editing.view.document.getRoot() );
                } );
                /*if(asesmen_detail.anamnesa === undefined) {
                    editor.setData("");
                } else {
                    editor.setData(asesmen_detail.anamnesa);
                }*/
                globalEditor = editor;
                window.editor = editor;
            } )
            .catch( err => {
                //console.error( err.stack );
            } );
    });
</script>

<div id="modal-manage-folder" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Manage Folder
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="txt_nama_folder">Nama Folder</label>
                            <input type="text" class="form-control" id="txt_nama_folder" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesFolder">
                    <span>
                        <i class="fa fa-save"></i> Proses
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Close
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!--div id="modal-manage-file" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Manage File
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="txt_nama_folder">Nama File</label>
                            <input type="text" class="form-control" id="txt_nama_file" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesFile">
                    <span>
                        <i class="fa fa-save"></i> Proses
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Close
                    </span>
                </button>
            </div>
        </div>
    </div>
</div-->

<div id="modal-tutor-group" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Tutorial Group
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_nama_group">Nama</label>
                            <input type="text" class="form-control" id="txt_nama_group" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesTutorGroup">
                    <span>
                        <i class="fa fa-save"></i> Proses
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Close
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modal-tutor" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Tutorial Step
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_nama">Nama</label>
                            <input type="text" class="form-control" id="txt_nama" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="txt_type">Type</label>
                            <select class="form-control" id="txt_type">
                                <option value="B">Basic Caption</option>
                                <option value="E">Element</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txt_target_element">Target Element</label>
                            <input class="form-control" id="txt_target_element" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="txt_remark">Remark</label>
                            <textarea class="form-control" style="min-height: 300px" id="txt_remark"></textarea>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="txt_tool_pos">Tooltip Position</label>
                            <select class="form-control" id="txt_tool_pos">
                                <option value="top">Top</option>
                                <option value="right">Right</option>
                                <option value="left">Left</option>
                                <option value="bottom">Bottom</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="txt_progress">Show Progress</label>
                            <input type="checkbox" class="form-control" id="txt_progress" />
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="txt_bullet">Show Bullet</label>
                            <input type="checkbox" class="form-control" id="txt_bullet" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txt_expect_dom">Expect DOM</label>
                            <input class="form-control" id="txt_expect_dom" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="txt_expect_dom_type">Expect DOM</label>
                        <select class="form-control" id="txt_expect_dom_type">
                            <option value="none">None</option>
                            <option value="modal">Modal</option>
                            <option value="tab">Tab</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesTutor">
                    <span>
                        <i class="fa fa-save"></i> Proses
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Close
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>