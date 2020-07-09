<script type="text/javascript">
	$(function(){
		//INIT DATA
		let targetID = <?php echo json_encode($targetID); ?>;
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
			}
		});


		$("form").submit(function(){
			$.ajax({
				url:__HOSTAPI__ + "/Pegawai",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				data:{
					request:"edit_pegawai",
					nama:$("#txt_nama_pegawai").val(),
					uid:targetID
				},
				type:"POST",
				success:function(resp) {
					if(resp.response_package.response_result > 0) {
						location.href = __HOSTNAME__ + "/pegawai";
					} else {
						alert(resp.response_package.response_message);
					}
				},
				error:function(resp) {
					console.log(resp);
				}
			});	
			return false;
		});



		
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
						if(data[keys][met].name == "__construct") {
							classIdentifier = "danger";
						} else if(
							data[keys][met].name == "__POST__" ||
							data[keys][met].name == "__GET__" ||
							data[keys][met].name == "__DELETE__" ||
							data[keys][met].name == "__PUT__"
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


		var ModuleTable = $("#module-table").DataTable();
	});
</script>