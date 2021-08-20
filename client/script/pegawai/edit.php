<script type="text/javascript">
	$(function(){
		var basic = $('.profile_photo').croppie({
			enforceBoundary:false,
			viewport: {
				width: 200,
				height: 200
			}
		});

		//INIT DATA
		let targetID = __PAGES__[2];
		var accessData = reload_access_data(targetID);
		$.ajax({
			url:__HOSTAPI__ + "/Pegawai/detail/" + targetID,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(resp) {
				$("#txt_email_pegawai").val(resp.response_package.response_data[0].email);
				$("#txt_nama_pegawai").val(resp.response_package.response_data[0].nama);
				basic.croppie('bind', {
					url: __HOST__ + resp.response_package.response_data[0].profile_pic,
					points: [0, 0, 200, 200],
                    zoom: 1
				}).then(function() {
					//
				});
				reload_jabatan(resp.response_package.response_data[0].jabatan);
				reload_unit(resp.response_package.response_data[0].unit);
				render_module(resp.response_package.response_module);

				
			},
			error: function(resp) {
				console.log(resp);
			}
		});

		$("#uploadImage").change(function(){
			readURL(this, basic);
		});

		//var ModuleTable = $("#module-table").DataTable();
		$("form").submit(function(){
			basic.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (image) {
				$.ajax({
					url:__HOSTAPI__ + "/Pegawai",
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					data:{
						request:"edit_pegawai",
						image:image,
						email:$("#txt_email_pegawai").val(),
						nama:$("#txt_nama_pegawai").val(),
						jabatan:$("#txt_jabatan").val(),
						unit:$("#txt_unit").val(),
						uid:targetID
					},
					type:"POST",
					success:function(resp) {
						if(resp.response_package.response_result > 0) {
							location.href = __HOSTNAME__ + "/pegawai";
						}
					},
					error:function(resp) {
						console.log(resp);
					}
				});
			});
			return false;
		});

		function render_module(dataMeta, parent = 0) {
			//$("#module-table tbody tr").remove();
			for(var key in dataMeta) {

				var newModuleRow = document.createElement("TR");
				$(newModuleRow).attr({
					"id": "module_row_" + dataMeta[key].id
				}).addClass((dataMeta[key].parent === 0) ? "module_row_" + dataMeta[key].id : "module_row_" + dataMeta[key].parent);

				var newModuleName = document.createElement("TD");
				var newModulePages = document.createElement("TD");
				var newModuleAction = document.createElement("TD");

				$(newModuleAction).html("<div class=\"custom-control custom-checkbox-toggle custom-control-inline mr-1\"></div>").attr("is-child", 1);
				var accessSwitch = document.createElement("input");
				$(accessSwitch).attr({
					"type": "checkbox",
					"id": "module-allow-" + dataMeta[key].id
				}).addClass("module-check custom-control-input");

				if(dataMeta[key].checked) {
					$(accessSwitch).attr({
						"checked": "checked"
					});
				}

				$(newModuleAction).find(".custom-control").prepend(accessSwitch).append(
					"<label class=\"custom-control-label\" for=\"module-allow-" + dataMeta[key].id + "\">Yes</label>"
				);

				$(newModuleName).html("<span style=\"" + ((dataMeta[key].level > 1) ? "" : "font-weight: bolder") + "\" class=\"" + ((dataMeta[key].level > 1) ? "" : "text-info") + "\">" + dataMeta[key].nama + "</span>")
				$(newModulePages).html("<a href=\"" + __HOSTNAME__ + "/" + dataMeta[key].identifier + "\"><span class=\"badge badge-custom-caption badge-outline-success\"><i style=\"margin-right: 8px;\" class=\"fa fa-link\"></i>" + __HOSTNAME__ + "</span><span class=\"badge badge-custom-caption badge-outline-warning\">/" + dataMeta[key].identifier + "</span>");

				$(newModuleRow).append(newModuleName);
				$(newModuleRow).append(newModulePages);
				$(newModuleRow).append(newModuleAction);
				if(dataMeta[key].parent == 0) {
					$("#module-table tbody").append(newModuleRow);
				} else {
                    if($("tr.module_row_" + dataMeta[key].parent).length === 0) {
                        $(newModuleRow).insertAfter($("#module-table tbody tr#module_row_" + dataMeta[key].parent));
                    } else {
                        /*if(dataMeta[key].parent == 36) {
                            alert($("tr.module_row_" + dataMeta[key].parent + ":last-child").length);
                        }*/
                        $(newModuleRow).insertAfter($("tr.module_row_" + dataMeta[key].parent + ":eq(" + ($("tr.module_row_" + dataMeta[key].parent + ":last-child").length - 1) + ")"));
                    }

					var paddingSet = ($("#module_row_" + dataMeta[key].parent).css("padding-left") == undefined) ? 0 : $("#module_row_" + dataMeta[key].parent).css("padding-left");
					$(newModuleName).css({
						"padding-left": (paddingSet + (25 * parseInt(dataMeta[key].level))) + "px"
					});
				}
			}
		}


		function reload_jabatan(selected){
			var jabatanData;
			$.ajax({
				url:__HOSTAPI__ + "/Pegawai/jabatan",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(resp) {
					$("#txt_jabatan option").remove();
					jabatanData = resp.response_package.response_data;
					for(var a = 0; a < jabatanData.length; a++) {
						var newOption = document.createElement("OPTION");
						$(newOption).attr({
							"value": jabatanData[a].uid
						}).html(jabatanData[a].nama);
						if(jabatanData[a].uid == selected) {
							$(newOption).attr({
								"selected":"selected"
							});
						}
						$("#txt_jabatan").append(newOption);
					}
					$("#txt_jabatan").select2();
				}
			});
			return jabatanData;
		}

		function reload_unit(selected){
			var unitData;
			$.ajax({
				url:__HOSTAPI__ + "/Unit/get_unit",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(resp) {
					$("#txt_unit option").remove();
					unitData = resp.response_package.response_data;
					for(var a = 0; a < unitData.length; a++) {
						var newOption = document.createElement("OPTION");
						$(newOption).attr({
							"value": unitData[a].uid
						}).html(unitData[a].kode + " - " + unitData[a].nama);
						if(unitData[a].uid == selected) {
							$(newOption).attr({
								"selected":"selected"
							});
						}
						$("#txt_unit").append(newOption);
					}
					$("#txt_unit").select2();
				}
			});
			return unitData;
		}

		



		
		//Load Access Manager
		
		function reload_access_tree(accessData = []) {
			$.ajax({
				url:__HOSTAPI__ + "/Modul/methods_tree",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(resp) {
					build_access_tree(resp.response_package, accessData);
				}
			});
		}

		function reload_access_data(targetID) {
			let metaData;
			$.ajax({
				async:false,
				url:__HOSTAPI__ + "/Pegawai/akses/" + targetID,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(resp) {
					metaData = resp.response_package.response_data;
				}
			});
			return metaData;
		}


		function build_access_tree(data, dataMeta = []) {
			$("#access-table tbody").html("");
			for(var keys in data) {
				if(data[keys].length > 0) {
					var classRow = document.createElement("tr");
					var classCol = document.createElement("td");
					
					var classCaption = document.createElement("h6");
					$(classCaption).html("<i class=\"fa fa-cube\"></i> " + keys).addClass("text-warning");

					$(classCol).css({
						'width': '35%'
					}).attr("rowspan", data[keys].length + 1).append(classCaption);
					$(classRow).append(classCol);

					$("#access-table tbody").append(classRow);

					for(var met in data[keys]) {
						let classIdentifier;
						if(data[keys][met].name === "__construct") {
							classIdentifier = "danger";
						} else if(
							data[keys][met].name === "__POST__" ||
							data[keys][met].name === "__GET__" ||
							data[keys][met].name === "__DELETE__" ||
							data[keys][met].name === "__PUT__"
						) {
							classIdentifier = "success";
						} else {
							classIdentifier = "primary";
						}

						var methodRow = document.createElement("tr");
						
						var methodCol = document.createElement("td");
						$(methodCol).html(data[keys][met].name + "()").addClass("text-" + classIdentifier).attr("is-child", 1);

						var classAct = document.createElement("td");
						$(classAct).html("<div class=\"custom-control custom-checkbox-toggle custom-control-inline mr-1\"></div>").attr("is-child", 1);
						var accessSwitch = document.createElement("input");
						$(accessSwitch).attr({
							"type": "checkbox",
							"id": "access-" + data[keys][met].id
						}).addClass("access-check custom-control-input");

						$(classAct).find(".custom-control").prepend(accessSwitch).append(
							"<label class=\"custom-control-label\" for=\"access-" + data[keys][met].id + "\">Yes</label>"
						);

						$(methodRow).append(methodCol);
						$(methodRow).append(classAct);

						$("#access-table tbody").append(methodRow);
					}
				}
					
			}



			//Render Access
			for(var accessKey = 0; accessKey < dataMeta.length; accessKey++) {
				$("#access-" + dataMeta[accessKey]["akses"]).prop("checked", ((dataMeta[accessKey]["status"] == "Y") ? true : false));
			}
		}

		reload_access_tree(accessData);

		$("body").on("change", ".access-check", function() {
			var id = $(this).attr("id").split("-");
			id = id[id.length - 1];

			$.ajax({
				url:__HOSTAPI__ + "/Pegawai",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				data: {
					"request": "update_access",
					"uid": targetID,
					"access": id,
					"accessType": ($(this).is(":checked")) ? "Y" : "N"
				},
				success:function(resp) {
					accessData = reload_access_data(targetID);
					reload_access_tree(accessData);
				}
			});
		});

		$("body").on("click", ".module-check", function() {
			var id = $(this).attr("id").split("-");
			id = id[id.length - 1];
			$.ajax({
				url:__HOSTAPI__ + "/Pegawai",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"POST",
				data: {
					"request": "update_pegawai_access",
					"uid": targetID,
					"modul": id,
					"accessType": ($(this).is(":checked")) ? "Y" : "N"
				},
				success:function(resp) {
					if(resp.response_package.response_result > 0) {

						//Notify Socket
						push_socket(__ME__, "akses_update", targetID, "Akses Anda telah di update", "info").then(function() {
                            notification ("success", "Hak modul berhasil diproses", 3000, "hasil_modul_update");
                        });
					}
					console.log(resp);
				}
			});
		});


		


		function readURL(input, cropper) {
			var url = input.value;
			var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
				var reader = new FileReader();

				reader.onload = function (e) {
					
					cropper.croppie('bind', {
						url: e.target.result
					});
					//$('#imageLoader').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

	});
</script>