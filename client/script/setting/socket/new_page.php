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
				protocolLib[command](command, type, parameter, sender, receiver, time);
			}
		}



		var protocolLib = {
			userlist: function(protocols, type, parameter, sender, receiver, time) {
				newMessage("<span class=\"message-caption text-danger\">[LOGGED IN]</span>", "System");
			},
			userlogin: function(protocols, type, parameter, sender, receiver, time) {
				newMessage("Hello", "System");
			},
			anjungan_kunjungan_baru: function(protocols, type, parameter, sender, receiver, time) {
				//
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
	});
</script>