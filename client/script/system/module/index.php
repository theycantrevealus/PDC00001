<script type="text/javascript">
	$(function(){

		var PARENT = 0;
		var MODE;
		var
			selectedID,
			selectedParent,
			selectedCheckChild,
			selectedNama,
			selectedColor,
			selectedIdentifier,
			selectedKeterangan,
			selectedIcon,
			selectedShowOnMenu,
			selectedShowOrder,
			selectedMenuGroup;

		function resetState() {
			$(".isMenuPanel").hide();
			$("#txt_nama_modul").val("");
			$("#txt_lokasi_modul").val("");
			$("#txt_tampil_menu").prop("checked", false);
			$("#txt_keterangan_modul").val("");
			$("#txt_urutan_modul").val(0);
			$("#txt_icon_modul").val("");
			$("#txt_group_modul").val(0);
		}

		function reloadDataTree(setURL){
			var returnData;
			$.ajax({
				async: false,
				url: setURL,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "GET",
				success: function(response){
					returnData = response.response_package;
				}
			});
			return returnData;
		}

		var TreeData = reloadDataTree(__HOSTAPI__ + "/Modul/tree");
		console.clear();
		console.log(TreeData);

		var jsTreeBuilder = $("#module").jstree({
			"core": {
				"data":TreeData,
				"check_callback" : true
			},
			"plugins" : ["search"]
		});



		$("#module").on("select_node.jstree", function (e, data) {
			selectedID = data.node.id;
			selectedCheckChild = data.node.data.childCount;
			selectedParent = data.node.data.parent;
			selectedNama = data.node.data.nama;
			selectedColor = data.node.data.group_color;
			selectedIdentifier = data.node.data.identifier;
			selectedKeterangan = data.node.data.keterangan;
			selectedIcon = data.node.data.icon;
			selectedShowOnMenu = data.node.data.show_on_menu;
			selectedShowOrder = data.node.data.show_order;
			selectedMenuGroup = data.node.data.menu_group;

			PARENT = selectedID;
			//alert($(".jstree-container-ul li").width());
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

		$("#txt_tampil_menu").change(function(){
			if($(this).is(":checked")) {
				$(".isMenuPanel").fadeIn();
			} else {
				$(".isMenuPanel").fadeOut();
			}
		});

		$("#btn_tambah_modul").click(function(){
			PARENT = 0;
			MODE = "add";
			resetState();
			$("#modal-large").modal("show");
			$("#modal-large-title").html("Tambah Module");
		});


		$(".custom-menu li").click(function(){
			resetState();
			switch($(this).attr("data-action")) {
				case "add_pos":
					MODE = 'add';
					$("#modal-large").modal("show");
					$("#modal-large-title").html("Tambah Module");
					$("#btn_submit_modul").html("Tambah");
				break;
				case "edit_pos":
					MODE = 'edit';
					$("#modal-large").modal("show");
					$("#modal-large-title").html("Edit Module");
					$("#txt_nama_modul").val(selectedNama);
					$("#txt_color_modul").val(selectedColor);
					$("#txt_lokasi_modul").val(selectedIdentifier);
					$("#txt_tampil_menu").prop("checked", (selectedShowOnMenu == "Y") ? true : false);
					if(selectedShowOnMenu == "Y") {
						$(".isMenuPanel").fadeIn();
					} else {
						$(".isMenuPanel").hide();
					}
					$("#txt_keterangan_modul").val(selectedKeterangan);
					$("#txt_urutan_modul").val(selectedShowOrder);
					$("#txt_icon_modul").val(selectedIcon);
					$("#txt_group_modul").val(selectedMenuGroup);
					$("#btn_submit_modul").html("Edit");
				break;
				case "delete_pos":
					var conf = confirm("Hapus Modul?");
					if(conf) {
						$.ajax({
							url: __HOSTAPI__ + "/Modul/" + selectedID,
							beforeSend: function(request) {
								request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
							},
							type: "DELETE",
							success: function(resp){
								if(resp.response_package.response_result > 0) {
									jsTreeBuilder.jstree("deselect_all");
									jsTreeBuilder.jstree(true).settings.core.data = reloadDataTree(__HOSTAPI__ + "/Modul/tree");
									jsTreeBuilder.jstree(true).refresh();
									$("#modal-large").modal("hide");
								}
								console.log(resp);
							}
						});
					}
				break;
			}
			$(".custom-menu").hide(100);
		});


		$("#btn_submit_modul").click(function() {
			var getNama = $("#txt_nama_modul").val();
			var getIdentifier = $("#txt_lokasi_modul").val();
			var getShowOnMenu = ($("#txt_tampil_menu").is(":checked")) ? "Y" : "N";
			var getKeterangan = $("#txt_keterangan_modul").val();
			var getUrutanTampil = $("#txt_urutan_modul").val();
			var getIconModul = $("#txt_icon_modul").val();
			var getGroupModul = $("#txt_group_modul").val();
			var colorModule = $("#txt_color_modul").val();

			

			if(
				getNama != ""
			) {
				$.ajax({
					url:__HOSTAPI__ + "/Modul",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request:(MODE == 'add') ? "tambah_modul" : "edit_modul",
						id:(MODE == 'add') ? 0 : selectedID,
						nama:getNama,
						identifier:getIdentifier,
						colorModule: colorModule,
						show_on_menu:getShowOnMenu,
						keterangan:getKeterangan,
						show_order:getUrutanTampil,
						icon:getIconModul,
						menu_group:getGroupModul,
						parent:(MODE == "add") ? PARENT : selectedParent
					},
					type:"POST",
					success:function(resp) {
						if(resp.response_package.response_result > 0) {
							jsTreeBuilder.jstree("deselect_all");
							jsTreeBuilder.jstree(true).settings.core.data = reloadDataTree(__HOSTAPI__ + "/Modul/tree");
							jsTreeBuilder.jstree(true).refresh();
							$("#modal-large").modal("hide");
						} else {
							console.log(resp);
						}
					},
					error:function(resp) {
						console.log(resp);
					}
				});	
			}
		});





		var methodsLoader = $("#methods-loader").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Modul/methods_get",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autoNum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						let classIdentifier;
						if(row["methods_name"] == "__construct") {
							classIdentifier = "danger";
						} else if(
							row["methods_name"] == "__POST__" ||
							row["methods_name"] == "__GET__" ||
							row["methods_name"] == "__DELETE__" ||
							row["methods_name"] == "__PUT__"
						) {
							classIdentifier = "success";
						} else {
							classIdentifier = "primary";
						}

						return 	"<div class=\"text-warning\">" + ((row["excluded"]) ? "<div class=\"badge badge-danger\">perm-excluded</div>" : "<div class=\"badge badge-success\">perm-included</div>") + "&nbsp;&nbsp;<i class=\"fa fa-cube\"></i>&nbsp;&nbsp;" + row["class_name"] + "::<span class=\"text-" + classIdentifier + "\">" + row["methods_name"] + "()</span></div>" +
								"<span class=\"method-caption\"><i class=\"fa fa-pencil-alt edit-method\" id=\"edit_method_" + row["id"] + "\"></i>&nbsp;&nbsp;<b id=\"caption_method_" + row["id"] + "\">" + row["caption"] + "</b><p id=\"remark_method_" + row["id"] + "\">" + row["remark"] + "</p></span>";
					}
				}
			]
		});

		$("#reload-methods").click(function() {
			methodsLoader.ajax.url(__HOSTAPI__ + "/Modul/methods_reload").load();
			return false;
		});

		$("body").on("click", ".edit-method", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			$("#txt_nama_method").val($("#caption_method_" + id).html());
			$("#txt_keterangan_method").val($("#remark_method_" + id).html());
			$("#modal-edit-method").modal("show");
			return false;
		});









	});
</script>
<div id="modal-large" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="txt_nama_modul">Nama Modul:</label>
					<input type="text" class="form-control" id="txt_nama_modul" placeholder="Nama Modul">
				</div>
				<div class="form-group">
					<label for="txt_color_modul">Group Color:</label>
					<input type="text" class="form-control" id="txt_color_modul" placeholder="Warna pada Menu">
					<b>Ex: sidemenu-brown</b>
				</div>
				<div class="form-group">
					<label for="txt_lokasi_modul">Lokasi:</label>
					<div class="input-group input-group-merge">
						<input id="txt_lokasi_modul" type="text" class="form-control form-control-prepended" required="" placeholder="Lokasi Module">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<?php echo __HOSTNAME__; ?>/
							</div>
						</div>
					</div>
				</div>
				<div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
					<input type="checkbox" id="txt_tampil_menu" class="custom-control-input">
					<label class="custom-control-label" for="txt_tampil_menu">Yes</label>
				</div>
				<label for="txt_tampil_menu" class="mb-0">Tampilkan di Menu</label>
				<hr />
				<div class="isMenuPanel">
					<div class="form-group">
						<label for="txt_urutan_modul">Urutan Tampil:</label>
						<input type="number" class="form-control" id="txt_urutan_modul" placeholder="Urutan Modul">
					</div>
					<div class="form-group">
						<label for="txt_icon_modul">Icon:</label>
						<input type="text" class="form-control" id="txt_icon_modul" placeholder="Icon Modul">
					</div>
					<div class="form-group">
						<label for="txt_group_modul">Group:</label>
						<input type="number" class="form-control" id="txt_group_modul" placeholder="Group Modul">
					</div>
				</div>
				
				<div class="form-group">
					<label for="txt_keterangan_modul">Keterangan:</label>
					<textarea class="form-control" id="txt_keterangan_modul" placeholder="Keterangan Modul"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="btn_submit_modul">Add</button>
			</div>
		</div>
	</div>
</div>





<div id="modal-edit-method" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Akses</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="txt_nama_modul">Nama Akses:</label>
					<input type="text" class="form-control" id="txt_nama_method" placeholder="Nama Modul">
				</div>
				
				<div class="form-group">
					<label for="txt_keterangan_modul">Keterangan:</label>
					<textarea class="form-control" id="txt_keterangan_method" placeholder="Keterangan Akses"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="btn_submit_method">Save</button>
			</div>
		</div>
	</div>
</div>