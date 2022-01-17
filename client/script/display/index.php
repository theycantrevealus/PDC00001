<script type="text/javascript">
	$(function() {
		$("html,body").css({
			"overflow": "hidden"
		});
		function load_loket() {
			var loketData;
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan/all_loket",
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					loketData = response.response_package.response_data;
				},
				error: function(response) {
					console.log(response);
				}
			});
			return loketData;
		}

		var loketData = load_loket();

		for(var a = 0; a < loketData.length; a++) {
			$("#loket-loader").append(	"<div class=\"col-md-6 loket-small-container\">" + 
											"<div>" +
												"<center>" +
													"<h3 style=\"color: yellow\" id=\"nama_antrian_" + loketData[a].uid + "\">" + loketData[a].nama_loket + "</h3>" +
													"<h3 style=\"color: #fff\" id=\"antrian_" + loketData[a].uid + "\">0</h3>" +
												"</center>" +
											"</div>" +
										"</div>");
		}

		/*for(var b = 0; b <=6; b++) {
			var singleSlide = document.createElement("DIV");
			$(singleSlide).addClass("carousel-item");
			if(b == 0) {
				$(singleSlide).addClass("active");
			}

			var newImage = document.createElement("IMG");
			$(newImage).addClass("d-block w-100")
				.attr("src", __HOST__ + "/images/slideshow/" + (b + 1) + ".jpg")
				.css({
					"height": "350px"
				});

			var caption = document.createElement("DIV");
			$(caption).addClass("carousel-caption d-none d-md-block");
			$(caption).append("<h5></h5>");
			$(caption).append("<p></p>");

			$(singleSlide).append(newImage);
			$(singleSlide).append(caption);

			$("#carousel-slider .carousel-inner").append(singleSlide);
		}*/

		$.ajax({
			url: __HOSTAPI__ + "/Aplicares/get-ruangan-terdaftar-bpjs",
			type: "GET",
			headers:{
				Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
			},
			success: function(response) {
				var data = response.response_package;
				var ruanganMeta = {};
				for(var key in data) {

				    if(data[key] !== null) {
				        if(data[key].detailRuangan !== undefined) {
                            if(ruanganMeta[data[key].kodekelas] === undefined) {
                                ruanganMeta[data[key].kodekelas] = []
                            }

                            ruanganMeta[data[key].kodekelas].push({
                                "nama": data[key].nama,
                                "uid_ruangan": data[key].uid_ruangan,
                                "kode_ruangan": data[key].koderuang,
                                "kodekelas": data[key].kodekelas,
                                "kapasitas": data[key].kapasitas,
                                "tersedia": data[key].tersedia,
                                "tersediapria": data[key].tersediapria,
                                "tersediawanita": data[key].tersediawanita,
                                "tersediapriawanita": data[key].tersediapriawanita,
                                "nama_kelas": data[key].detailRuangan.kelas.nama
                            });
                        }
                    }
				}
				var auto = 1;
				
				for(var kKey in ruanganMeta) {
					var singleSlide = document.createElement("DIV");
					$(singleSlide).addClass("carousel-item").css({
						"padding": "0px !important",
						"height": "200px",
						"vertical-align": "top",
						"background": "rgba(0, 0, 0, .3)"
					});
					if(auto == 1) {
						$(singleSlide).addClass("active");
					}
					
					var caption = document.createElement("DIV");
					$(caption).css({
						"padding": "10px",
						"color": "#fff",
						"font-size": "14pt"
					});
					$(caption).append("<h5 class=\"text-center\" style=\"font-weight: bolder; font-size: 20pt; color: #fff\">(" + kKey + ") " + ruanganMeta[kKey][0].nama_kelas + "</h5>");
					var ruanganList = "";
					for(var ab = 0; ab < ruanganMeta[kKey].length; ab++) {
						ruanganList += "<tr>" +
											"<td style=\"color: #fff; font-size: 16pt; font-weight: bolder\">(" + ruanganMeta[kKey][ab].kode_ruangan + ") " + ruanganMeta[kKey][ab].nama + "</td>" +
											"<td style=\"color: #fff; font-size: 16pt; font-weight: bolder\">" + ruanganMeta[kKey][ab].kapasitas + "</td>" +
											"<td style=\"color: #fff; font-size: 16pt; font-weight: bolder\">" + ruanganMeta[kKey][ab].tersedia + "</td>" +
										"</tr>";
					}
					$(caption).append("<p><table class=\"table\">" +
						"<tr>" +
							"<th style=\"color: #fff; font-size: 16pt; font-weight: bolder\">Ruangan</th>" +
							"<th style=\"color: #fff; font-size: 16pt; font-weight: bolder\">Kapasitas</th>" +
							"<th style=\"color: #fff; font-size: 16pt; font-weight: bolder\">Tersedia</th>" +
						"</tr>" +
						"<thead></thead><tbody>" + ruanganList + "</tbody></table></p>");

					//$(singleSlide).append(newImage);
					$(singleSlide).append(caption);

					$("#info-kamar .carousel-inner").append(singleSlide);
					auto++;
				}
			}
		})

		$('.carousel:eq(0)').carousel({
			interval: 5000
		});

		$('.carousel:eq(1)').carousel({
			interval: 7000
		});

		var tablePelayanan = $("#table-poli-pelayanan").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[10, 15, -1], [10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Poli",
				type: "POST",
				data: function(d){
					d.request = "get_kunjungan_per_layanan";
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
				searchPlaceholder: "Cari Kode Amprah"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.autonum;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.jumlah_pelayanan;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						var pelayanan_current = row.jumlah_pelayanan;
						var jumlah_pelayanan_total = row.jumlah_pelayanan_total;
						console.log(row.percentage);
						var progress = "<div class=\"progress\">" +
											"<div class=\"progress-bar\" style=\"width:" + row.percentage + "%\" role=\"progressbar\" aria-valuenow=\"" + row.percentage + "\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>" +
										"</div>";
						return "<span>" + row.percentage + "%</span><br />" + progress;
					}
				}
			]
		});

		



		var audio = new Audio(), i = 0;
		audio.volume = 0.5;
		audio.playbackRate = 0.1;
		audio.loop = false;
		var playlist = [];
		var currentLength = 0;

		/*Sync.onmessage = function(evt) {
			var signalData = JSON.parse(evt.data);
			var command = signalData.protocols;
			var type = signalData.type;
			var sender = signalData.sender;
			var receiver = signalData.receiver;
			var time = signalData.time;
			var parameter = signalData.parameter;

			if(command !== undefined && command !== null && command !== "") {
				if(protocolLib[command] !== undefined) {
					if(command == "anjungan_kunjungan_panggil") {
						
						
						console.clear();
						//console.log(playlist);
						if(!audio.paused && !audio.ended && 0 < audio.currentTime) {
							var listParse = protocolLib[command](command, type, parameter, sender, receiver, time, audio, playlist);
							playlist = listParse.playlist;
						} else {
							var listParse;
							if(playlist.length > 0) {
								listParse = protocolLib[command](command, type, parameter, sender, receiver, time, audio, playlist, true);
							} else {
								listParse = protocolLib[command](command, type, parameter, sender, receiver, time, audio, playlist, false);
							}

							playlist = listParse.playlist;
							audio.src = playlist[0];
							audio.play();
						}
					} else {
						protocolLib[command](command, type, parameter, sender, receiver, time);	
					}
				}
			}
		}*/

		audio.addEventListener('ended', function () {
			i++;
			if(i == playlist.length) {
				audio.pause();
				audio.currentTime = 0;
				i = 0;
				console.log("Finished");
			} else {
                console.log("Playing : " + playlist[i]);
				audio.src = playlist[i];
				audio.play();
			}
		});
		


		protocolLib = {
            anjungan_kunjungan_panggil_3: function(protocols, type, parameter, sender, receiver, time) {
                if(!audio.paused && !audio.ended && 0 < audio.currentTime) {
                    var listParse = protocolLib[command](command, type, parameter, sender, receiver, time, audio, playlist);
                    playlist = listParse.playlist;
                } else {
                    var listParse;
                    if(playlist.length > 0) {
                        listParse = protocolLib[command](command, type, parameter, sender, receiver, time, audio, playlist, true);
                    } else {
                        listParse = protocolLib[command](command, type, parameter, sender, receiver, time, audio, playlist, false);
                    }

                    playlist = listParse.playlist;
                    audio.src = playlist[0];
                    audio.play();
                }
			},
			anjungan_kunjungan_panggil: function(protocols, type, parameter, sender, receiver, time, isReset) {
			    var globalData = {};
				var tracks;
				var current;
				var commandParse = parameter;
				var getUrutParse = commandParse.nomor.split("-");
				var getHurufParse = getUrutParse[0];
				var getNomorParse = getUrutParse[1];
				var playlist = [];
                var audio = new Audio(), i = 0;
                audio.currentTime = 0;
                audio.volume = 0.5;
                audio.playbackRate = 0.1;
                audio.loop = false;



				$("#current_antrian").html(commandParse.nomor);
				$("#antrian_" + commandParse.loket).html(commandParse.nomor);

                var listParse = {
                    audio: audio,
                    playlist: playlist
                };

				$.ajax({
					async: false,
					url:__HOSTAPI__ + "/Anjungan",
					type: "POST",
					data:{
						request: "get_terbilang",
						//nomor_urut: commandParse.nomor
						nomor_urut: getNomorParse,
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					success: function(response){





						if(response.response_package != "") {
							push_socket("display", "isCalling", "*", "", "info");
							/*playlist = [
								__HOST__ + 'audio/openning.mpeg',
								__HOST__ + 'audio/antrian.mp3'
							];*/
							if(isReset) {
							    playlist = [];
							    audio.pause();
							    audio.currentTime = 0;
                                playlist.push(__HOST__ + 'audio/openning.mpeg');
                                playlist.push(__HOST__ + 'audio/antrian.mp3');
							} else {
								playlist.push(__HOST__ + 'audio/openning.mpeg');
								playlist.push(__HOST__ + 'audio/antrian.mp3');
							}
							playlist.push(__HOST__ + 'audio/' + getHurufParse.toUpperCase() + '.mp3');
							forRead = response.response_package.split(" ");
							for(var z = 0; z < forRead.length; z++) {
								playlist.push(__HOST__ + "audio/" + forRead[z] + ".MP3");
							}

							//playlist.push(__HOST__ + 'audio/di.mp3');
							playlist.push(__HOST__ + 'audio/loket.MP3');
							//playlist.push(__HOST__ + 'audio/' + ($("#nama_antrian_" + commandParse.loket).html().replace(" ", "").toLowerCase().trim()) + '.mp3');
							playlist.push(__HOST__ + 'audio/' + ($("#nama_antrian_" + commandParse.loket).html().replace("LOKET ", "").toLowerCase().trim()) + '.MP3');
							playlist.push(__HOST__ + 'audio/closing.mpeg');
							playlist = listParse.playlist;
                            audio.src = playlist[0];
                            audio.play();

                            audio.addEventListener('ended', function () {
                                i++;
                                if(i == playlist.length) {
                                    audio.pause();
                                    audio.currentTime = 0;
                                    i = 0;
									console.clear();
									push_socket("display", "doneCalling", "*", "", "info");
                                    console.log("Finished");
                                } else {
                                    console.log("Playing : " + playlist[i]);
                                    audio.src = playlist[i];
                                    audio.play();
                                }
                            });
						} else {
                            console.log(playlist);
                        }


						/*var loketStatus = response.response_package;

						for(var a in loketStatus) {
							for(var b in loketStatus[a]) {
								if(globalData[a] != loketStatus[a][b] && loketStatus[a][b] > 0) {
									playlist = [
										__HOST__ + 'audio/openning.mpeg',
										__HOST__ + 'audio/antrian.mp3'
									];
									forRead = b.split(" ");
									for(var z = 0; z < forRead.length; z++) {
										playlist.push(__HOST__ + "audio/" + forRead[z] + ".MP3");
									}
									playlist.push(__HOST__ + 'audio/di.mp3');
									playlist.push(__HOST__ + 'audio/' + ($("#nama_antrian_" + a).html().replace(" ", "").toLowerCase().trim()) + '.mp3');
									playlist.push(__HOST__ + 'audio/closing.mpeg');
									globalData[a] = loketStatus[a][b];
								} else {
									playlist = [];
								}
							}
						}*/
					},
					error: function(response) {
						console.log(response);
					}
				});








				return listParse;
			}
		};

		//Real Time Service
		/*setInterval(function(){
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan/loket_status",
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					console.clear();
					var audio = new Audio(),
					i = 0;
					var playlist = [];
					audio.addEventListener('ended', function () {
						i++;
						if(i == playlist.length) {
							audio.pause();
							audio.currentTime = 0;
						} else {
							audio.src = playlist[i];
							audio.play();
						}
					}, true);

					var loketStatus = response.response_package;
					console.log(loketStatus);
					for(var a in loketStatus) {
						$("#antrian_" + a).html("0");
						for(var b in loketStatus[a]) {
							$("#antrian_" + a).html(loketStatus[a][b]);
							if(globalData[a] != loketStatus[a][b] && loketStatus[a][b] > 0) {
								playlist = [
									__HOST__ + 'audio/openning.mpeg',
									__HOST__ + 'audio/antrian.mp3'
								];
								forRead = b.split(" ");
								for(var z = 0; z < forRead.length; z++) {
									playlist.push(__HOST__ + "audio/" + forRead[z] + ".MP3");
								}
								playlist.push(__HOST__ + 'audio/di.mp3');
								playlist.push(__HOST__ + 'audio/' + ($("#nama_antrian_" + a).html().replace(" ", "").toLowerCase().trim()) + '.mp3');
								playlist.push(__HOST__ + 'audio/closing.mpeg');
								globalData[a] = loketStatus[a][b];
							} else {
								playlist = [];
							}
						}
					}
				
					if(playlist.length > 0) {
						audio.volume = 0.3;
						audio.loop = false;
						audio.src = playlist[0];
						audio.play();
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}, 1000);*/
	});
</script>