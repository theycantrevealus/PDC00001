<script type="text/javascript">
	$(function() {
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
			$("#loket-loader").append(	"<div class=\"col-md-4 loket-small-container\">" + 
											"<div>" +
												"<center>" +
													"<h3 style=\"color: yellow\" id=\"nama_antrian_" + loketData[a].uid + "\">" + loketData[a].nama_loket + "</h3>" +
													"<h3 style=\"color: #fff\" id=\"antrian_" + loketData[a].uid + "\">0</h3>" +
												"</center>" +
											"</div>" +
										"</div>");
		}

		var globalData = {};
		var audio;
		var playlist;
		var tracks;
		var current;

		//Real Time Service
		setInterval(function(){
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
		}, 1000);
	});
</script>