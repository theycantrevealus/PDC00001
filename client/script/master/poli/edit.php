<script type="text/javascript">
	var uid = <?php echo json_encode(__PAGES__[3]); ?>;
	$(function(){
		var dataObject= {}, 
			hargaPenjamin = {}, 
			tindakanPoli = [];

		var state_tindakan_uid = '';
		var tindakan, penjamin;


		/*load data tindakan*/
		tindakan = loadTindakan();
		penjamin = loadPenjamin();

		/*assign to using select2*/
		$(".tindakan").select2({});
		$(".harga").inputmask({alias: 'currency', rightAlign: true, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});

		/*========= Load Poli ========*/
		
	
		$.ajax({
			async: false,
			url:__HOSTAPI__ + "/Poli/poli-detail/" + uid,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				
				var metaData = response.response_package.response_data;
				var temp_nama = metaData[0].nama;
				var nama = temp_nama.replace('Poli ', '');

				var tindakanData = metaData[0].tindakan;
				console.log(tindakanData);

				dataObject.uid = uid;
				dataObject.nama = "Poliklinik " + nama;

				$.each(tindakanData, function(key, item){
					var uid_tindakan = item.uid_tindakan;
					var uid_penjamin = item.uid_penjamin;
					var harga = parseFloat(item.harga);
					var nama_tindakan;

					if (uid_tindakan in hargaPenjamin){
						hargaPenjamin[uid_tindakan][uid_penjamin] = harga; 
					} else {
						hargaPenjamin[uid_tindakan] = {[uid_penjamin]: harga}; 
					}

					/*===== GET NAME OF POLI =====*/

					if (!(tindakanPoli.includes(uid_tindakan))){
						$.each(tindakan, function(key, item){
							if (item.uid == uid_tindakan){
								nama_tindakan = item.nama;
							}
						});

						tindakanPoli.push(uid_tindakan);
						getTindakanOnLoad(uid_tindakan, nama_tindakan);
						setNomorUrut("table-tindakan","no_urut");
					}

					$("#tindakan option[value='"+ uid_tindakan +"']").remove();
					$("#tindakan option[value='']").attr("selected");
				});
				
				dataObject['tindakan'] = hargaPenjamin;

				$("#txt_nama").val(nama);
			},
			error: function(response) {
				console.log(response);
			}
		});
		/*===========================*/

		$("#tindakan").on('change', function(){
			var params = [];
			params['uid'] = $(this).children("option:selected").val();
			params['nama'] = $(this).children("option:selected").html();

			if (params['uid'] != ""){
				tindakanPoli.push(params['uid']);
				getTindakan(params);

				setNomorUrut("table-tindakan","no_urut");
			}
		});

		$("#table-tindakan tbody").on('click', '.btnHapusTindakan', function(){
			var get_uid = $(this).attr("id");
			var arr_uid = get_uid.split("_");
			var uid = arr_uid[arr_uid.length - 1];

			$(this).closest("tr").remove();
			setNomorUrut("table-tindakan","no_urut");
			
			//=== deleteing from tindakanPoli array and hargaPenjamin object
			tindakanPoli = removeFromArrayByValue(tindakanPoli, uid);
			delete hargaPenjamin[uid];
			//===

			//=== Set Back option List
			delete dataObject.tindakan[uid];
			setBackTindakan(tindakan, uid);
		});


		$("#table-tindakan tbody").on('click','.linkTindakan', function() {
			var get_uid = $(this).parent().parent().attr("id");
			var arr_uid = get_uid.split("_");
			var uid = arr_uid[arr_uid.length - 1];

			var name = $(this).html();
			
			state_tindakan_uid = uid;

			$("#title-tindakan-penjamin").html("untuk <span style='color:#4a90e2;'><b>"+ name +"</b></span>");
			$("#table-penjamin").removeClass("table-not-active");
			$('#table-penjamin').find('input').each(function(){
				$(this).val("");
			});

			if (uid in hargaPenjamin){
				var metaData = hargaPenjamin[uid];
				
				$.each(metaData,function(key,item){
					$("#harga_penjamin_" + key).val(item);
				});
			}
		});

		/*========== ON CLICK IF NOT IN TABLE ==========*/
		$(document).click(function(e) {
			if ( $(e.target).closest('table').length === 0 ) {
				$("#title-tindakan-penjamin").html("");
				$("#table-penjamin").addClass("table-not-active");
				$('#table-penjamin').find('input').each(function(){
					$(this).val("");
				});
			}
		});
		/*===============================================*/


		/*=========== EMPTY CHECK AND ASSIGN NAME FOR POLI ============*/
		$("#txt_nama").on('keyup', function(){
			var nama = $("#txt_nama").val();

			if (nama != ""){
				dataObject.nama = "Poli " + nama;
				$(".btnNextInfo").removeAttr("disabled");
			} else {
				$(".btnNextInfo").attr("disabled",true);
			}
		});
		/*=============================================================*/


		/*=========== EMPTY CHECK AND ASSIGN PRICE OF PENJAMIN ============*/
		$(".hargaPenjamin").on('keyup', function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var temp = $(this).val();

			if (temp != ""){
				var harga = temp.replace(',', '');
				harga = parseFloat(harga);

				if (state_tindakan_uid in hargaPenjamin){
					hargaPenjamin[state_tindakan_uid][uid] = harga; 
				} else {
					hargaPenjamin[state_tindakan_uid] = {[uid]: harga}; 
				}
			}
			
		});
		/*=============================================================*/
		function findKey(item, key, value) {
			var found;
			for(var a in item) {
				if(item[a][key] == value) {
					found = item[a];
				}
			}
			return found;
		}
		/*========= BTN NEXT TINDAKAN ==========*/
		$(".btnNextTindakan").on('click', function(){
			//==== Set table head
			
			$("#table-konfirmasi tbody").html("");
			var itemCounter = 1;
			for(var hargaKey in hargaPenjamin) {
				var tindakanCounter = 1;
				var namaTindakan = findKey(tindakan, "uid", hargaKey);
				var totalPenjamin = 0;
				for(var penjaminKey in hargaPenjamin[hargaKey]) {
					totalPenjamin++;
				}
				for(var penjaminKey in hargaPenjamin[hargaKey]) {
					var namaPenjamin = findKey(penjamin, "uid", penjaminKey);

					if(tindakanCounter == 1) {
						$("#table-konfirmasi tbody").append(
							"<tr>" +
								"<td rowspan=\"" + totalPenjamin + "\">" + itemCounter + "</td>" +
								"<td rowspan=\"" + totalPenjamin + "\">" + namaTindakan.nama + "</td>" +
								"<td>" + namaPenjamin.nama + "</td>" +
								"<td class=\"text-right\">" + number_format (hargaPenjamin[hargaKey][penjaminKey], 2, ".", ",") + "</td>" +
							"</tr>"
						);
					} else {
						$("#table-konfirmasi tbody").append(
							"<tr>" +
								"<td>" + namaPenjamin.nama + "</td>" +
								"<td class=\"text-right\">" + number_format (hargaPenjamin[hargaKey][penjaminKey], 2, ".", ",") + "</td>" +
							"</tr>"
						);
					}
					tindakanCounter++;
				}
				itemCounter++;
			}
			$("#title-konfirmasi-poli").html(dataObject.nama);
			
		});
		/*======================================*/


		/*================== BTN NEXT PREV TAB WIZARD =================*/
		$(".btnNext").on('click', function(){
			var get_id = $(this).parent().parent().parent().attr('id');
			var id = get_id[get_id.length - 1];
			id = parseInt(id);
			nextTab(id);
		});

		$(".btnPrev").on('click', function(){
			var get_id = $(this).parent().parent().parent().attr('id');
			var id = get_id[get_id.length - 1];
			id = parseInt(id);
			prevTab(id);
		});
		/*=============================================================*/


		$("#btnSubmit").on('click', function(){
			dataObject.tindakan = hargaPenjamin;
			console.log(hargaPenjamin);
			
			if (dataObject.nama != "") {
				$.ajax({
					url: __HOSTAPI__ + "/Poli",
					data: {
						request : "edit_poli",
						dataObject : dataObject
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						//console.log(response);
						location.href = __HOSTNAME__ + "/master/poli";
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
	});

	function nextTab(id){
		$(".tabsContent").removeClass("active show");
		$(".navTabs").removeClass("active");

		var nextId = id + 1;
		$("#nav-tab-" + nextId).addClass("active");
		$("#tab-" + nextId).addClass("active show");
	}

	function prevTab(id){
		$(".tabsContent").removeClass("active show");
		$(".navTabs").removeClass("active");

		var prevId = id - 1;
		$("#nav-tab-" + prevId).addClass("active");
		$("#tab-" + prevId).addClass("active show");
	}


	/*========== FUNC FOR LOAD TINDAKAN ==========*/
	function loadTindakan(){
		var dataTindakan;

		$.ajax({
			async: false,
			url:__HOSTAPI__ + "/Tindakan/tindakan",
			type: "GET",
			 beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			success: function(response){
				dataTindakan = response.response_package.response_data;

				for(i = 0; i < dataTindakan.length; i++){
					var selection = document.createElement("OPTION");

					$(selection).attr("value", dataTindakan[i].uid).html(dataTindakan[i].nama);
					$("#tindakan").append(selection);
				}
			},
			error: function(response) {
				console.log(response);
			}
		});

		if (dataTindakan.length > 0) {
			return dataTindakan;
		} else {
			return null;
		}
	}
	/*--------------------------------------*/

	/*========== FUNC FOR LOAD PENJAMIN ==========*/
	function loadPenjamin(){
		var dataPenjamin;

		$.ajax({
			async: false,
			url:__HOSTAPI__ + "/Penjamin/penjamin",
			type: "GET",
			 beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			success: function(response){
				var MetaData = dataPenjamin = response.response_package.response_data;

				var html = "";
				for(i = 0; i < MetaData.length; i++){
					var no = i + 1;
					html += "<tr>" +
								"<td>"+ no +"</td>" +
								"<td>"+ MetaData[i].nama +"</td>" +
								"<td><input type='text' class='form-control numberonly harga hargaPenjamin' placeholder='' id='harga_penjamin_" + MetaData[i].uid  + "'></td>" + 
							"</tr>";
				}

				$("#table-penjamin tbody").append(html);
			},
			error: function(response) {
				console.log(response);
			}
		});


		if (dataPenjamin.length > 0) {
			return dataPenjamin;
		} else {
			return null;
		}
	}
	/*--------------------------------------*/
	var listDokter = loadSetDokter(uid);
	function autoDokter(data) {
		var newRow = document.createElement("TR");
		var newCellDokterID = document.createElement("TD");
		var newCellDokterNama = document.createElement("TD");
		var newCellDokterAksi = document.createElement("TD");

		$(newCellDokterNama).html(data.dokterName).attr({
			"dokter-value": data.dokterUID
		});

		var newDeleteDokter = document.createElement("BUTTON");
		$(newDeleteDokter).addClass("btn btn-danger btn-sm btn_remove_dokter").html("<i class=\"fa fa-ban\"></i>");
		$(newCellDokterAksi).append(newDeleteDokter);

		$(newRow).append(newCellDokterID);
		$(newRow).append(newCellDokterNama);
		$(newRow).append(newCellDokterAksi);
		$("#poli-list-dokter tbody").append(newRow);
		rebaseDokter();
	}

	function rebaseDokter() {
		//var populateListedDokter = [];
		$("#poli-list-dokter tbody tr").each(function(e) {
			var id = (e + 1);
			$(this).attr({
				"id": "row_dokter_" + id
			})

			$(this).find("td:eq(0)").html(id);
			
			$(this).find("td:eq(1)").attr({
				"id": "dokter_set_" + id
			});

			/*if(populateListedDokter.indexOf($(this).find("td:eq(1)").attr("dokter-value")) < 0) {
				populateListedDokter.push($(this).find("td:eq(1)").attr("dokter-value"));
			}*/

			$(this).find("td:eq(2) button").attr({
				"id": "delete_dokter_" + id
			});
		});
	}

	//txt_set_dokter

	function loadDokter(target, uid, selected = []) {
		var dokterData;
		$.ajax({
			url:__HOSTAPI__ + "/Poli/poli-avail-dokter/" + uid,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				dokterData = response.response_package.response_data;
				$(target).find("option").remove();
				for(var a = 0; a < dokterData.length; a++) {
					if(selected.indexOf(dokterData[a].uid) < 0) {
						$(target).append("<option value=\"" + dokterData[a].uid + "\">" + dokterData[a].nama_dokter + "</option>");
					}
				}
				$(target).select2();
			},
			error: function(response) {
				console.log(response);
			}
		});
		return dokterData;
	}

	function loadSetDokter(uid) {
		var dokterData = [];
		$.ajax({
			url:__HOSTAPI__ + "/Poli/poli-set-dokter/" + uid,
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type:"GET",
			success:function(response) {
				var dat = response.response_package.response_data;
				for(var a = 0; a < dat.length; a++) {
					dokterData.push(dat[a].dokter);
					autoDokter({
						dokterUID: dat[a].dokter,
						dokterName: dat[a].nama
					});
				}
			},
			error: function(response) {
				console.log(response);
			}
		});
		return dokterData;
	}
	

	$("body").on("click", ".btn_remove_dokter", function() {
		var id = $(this).attr("id").split("_");
		id = id[id.length - 1];
		/*alert($("#dokter_set_" + id).attr("dokter-value"));
		alert(uid);*/
		$.ajax({
			url:__HOSTAPI__ + "/Poli",
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			data:{
				request: "poli_dokter_buang",
				dokter: $("#dokter_set_" + id).attr("dokter-value"),
				poli: uid
			},
			type:"POST",
			success:function(response) {
				console.log(response);
				if(response.response_package.response_result > 0) {
					$("#row_dokter_" + id).remove();
					listDokter.splice(listDokter.indexOf($("#dokter_set_" + id).attr("dokter-value")), 1);
					loadDokter("#txt_set_dokter", uid, listDokter);
					notification ("success", "Data tersimpan", 2000, "save_dokter");
					rebaseDokter();
				}
			},
			error: function(response) {
				console.log(response);
			}
		});
		
		return false;
	});

	$("#btn_tambah_dokter").click(function() {
		var dokterSelected = $("#txt_set_dokter").val();
		var dokterSelectedText = $("#txt_set_dokter option:selected").text();
				
		$.ajax({
			url:__HOSTAPI__ + "/Poli",
			async:false,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			data:{
				request: "poli_dokter",
				dokter: dokterSelected,
				poli: uid
			},
			type:"POST",
			success:function(response) {
				console.log(response);
				if(response.response_package.response_result > 0) {
					listDokter.push(dokterSelected);
					autoDokter({
						dokterUID: dokterSelected,
						dokterName: dokterSelectedText
					});
					loadDokter("#txt_set_dokter", uid, listDokter);
					notification ("success", "Data tersimpan", 2000, "save_dokter");
				}
			},
			error: function(response) {
				console.log(response);
			}
		});
		
		return false;
	});

	loadDokter("#txt_set_dokter", uid);
	

	function getTindakan(params){
		var uid_tindakan = params['uid'];
		var nama_tindakan = params['nama'];

		$("#tindakan option[value='"+ uid_tindakan +"']").remove();
		$("#tindakan option[value='']").attr("selected");
	
		html = "<tr id='tindakan_" + uid_tindakan + "'>" + 
					"<td class='no_urut'></td>" +
					"<td><a href='#' class='linkTindakan'>"+ nama_tindakan +"</a></td>" +
					"<td><button type='button' rel='tooltip' id='btn_tindakan_"+ uid_tindakan +"' class='btn btn-sm btn-danger btnHapusTindakan' data-toggle='tooltip' data-placement='top' title='' data-original-title='Hapus'><i class='fa fa-trash'></i></button></td>" +
				"</tr>";

		$("#table-tindakan tbody").append(html);
		$("#tindakan").val("");
		$("#tindakan").trigger("change");
	}

	/*========== FOR FIRST LOAD WHEN LOAD DATA FOR EDIT ==========*/
	function getTindakanOnLoad(uid_tindakan, nama_tindakan){

		$("#tindakan option[value='"+ uid_tindakan +"']").remove();
		$("#tindakan option[value='']").attr("selected");
	
		html = "<tr id='tindakan_" + uid_tindakan + "'>" + 
					"<td class='no_urut'></td>" +
					"<td><a href='#' class='linkTindakan'>"+ nama_tindakan +"</a></td>" +
					"<td><button type='button' rel='tooltip' id='btn_tindakan_"+ uid_tindakan +"' class='btn btn-sm btn-danger btnHapusTindakan' data-toggle='tooltip' data-placement='top' title='' data-original-title='Hapus'><i class='fa fa-trash'></i></button></td>" +
				"</tr>";

		$("#table-tindakan tbody").append(html);
		$("#tindakan").val("");
		$("#tindakan").trigger("change");
	}
	/*=============================================================*/

	function setBackTindakan(arr_tindakan, uid_tindakan){
		var name_tindakan;

		for (var i = 0; i < arr_tindakan.length; i++) {
			if (arr_tindakan[i].uid == uid_tindakan){
				name_tindakan = arr_tindakan[i].nama;
				break;
			}
		}

		$("#tindakan").append("<option value='"+ uid_tindakan +"'>"+ name_tindakan +"</option>");
	}

	function setNomorUrut(table_name, no_urut_class){
		/*set dynamic serial number*/
		var rowCount = $("#"+ table_name +" tr").length;
		var table = $("#"+ table_name);
		$("."+ no_urut_class).html("");

		for (var i = 0, row; i < rowCount; i++) {
			//console.log()
			table.find('tr:eq('+ i +')').find('td:eq(0)').html(i);
		}
		/*--------*/
	}

	function removeFromArrayByValue(arr, val){
		var fill_array = $.grep(arr, function(value) {
			return value != val;
		});

		return fill_array;
	}

	$('.numberonly').keypress(function(event){
		if (event.which < 48 || event.which > 57) {
			event.preventDefault();
		}
	});


	$.fn.digits = function(){ 
		return this.each(function(){ 
			$(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
		})
	}


</script>