<script type="text/javascript">
	$(function() {
		Sync.onmessage = function(evt) {
			var signalData = JSON.parse(evt.data);
			var command = signalData.protocols;
			var type = signalData.type;
			var sender = signalData.sender;
			var receiver = signalData.receiver;
			var time = signalData.time;
			var parameter = signalData.parameter;

			if(command !== undefined && command !== null && command !== "") {
				if(sender !== __ME__) {
					if(Array.isArray(receiver)) {
						if(receiver.indexOf(receiver) >= 0) {
							protocolLib[command](command, type, parameter, sender, receiver, time);	
						}
					} else {
						if(receiver == "*") {
							protocolLib[command](command, type, parameter, sender, receiver, time);		
						}
					}
				}
			}
		}

		var protocolLib = {
			userlist: function(protocols, type, parameter, sender, receiver, time) {
				newMessage("<span class=\"message-caption text-danger\">" + protocols + "</span>" + parameter, "<span class=\"text-" + type + "\">" + sender + "</span>");
			},
			userlogin: function(protocols, type, parameter, sender, receiver, time) {
				newMessage("Hello", "System");
			},
			anjungan_kunjungan_baru: function(protocols, type, parameter, sender, receiver, time) {
				newMessage("<span class=\"message-caption text-danger\">" + protocols + "</span>" + parameter, sender);
			},
			anjungan_kunjungan_panggil: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			kasir_daftar_baru: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			kasir_verif_bayar: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			apotek_resep_baru: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			apotek_resep_proses: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			apotek_resep_selesai: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			poli_antrian_baru: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			poli_asesmen_rawat_selesai: function(protocols, type, parameter, sender, receiver, time) {
				//
			}
		};

		function newMessage(content, sender) {
			var packageContainer = document.createElement("DIV");
			$(packageContainer).addClass("package-bundle");

			var packageRow = document.createElement("DIV");
			$(packageRow).addClass("row");

			var senderCell = document.createElement("DIV");
			$(senderCell).addClass("col-lg-1 text-right").html("<b class=\"sender-caption\">" + sender + "</b>");

			var messageCell = document.createElement("DIV");
			$(messageCell).addClass("col-lg-11").html(content);

			$(packageRow).append(senderCell);
			$(packageRow).append(messageCell);
			$(packageContainer).append(packageRow);

			$(".sync-loader").append(packageRow);
		}
	});
</script>