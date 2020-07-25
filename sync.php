<?php
$host_server = '192.168.99.240';
$port_number = '666';
$null = NULL;
$takashi = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($takashi, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($takashi, 0, $port_number);
socket_listen($takashi);

$clients = array($takashi);
$user_online = array();

while (true) {
	$change_socket = $clients;
	socket_select($change_socket, $null, $null, 0, 10);
	if (in_array($takashi, $change_socket)) {
		$new_socket = socket_accept($takashi);
		$clients[] = $new_socket;
		$header = socket_read($new_socket, 1024);
		handshake($header, $new_socket, $host_server, $port_number);
		socket_getpeername($new_socket, $ip);

		$communicate = mask(json_encode(array('sender' => 'system',
			'type' => 'info',
			'protocols' => 'userlist',
			'receiver' => "*",
			'time' => date('d M Y, h:i:s'),
			'parameter' => json_encode($user_online),
		)));

		send($communicate);
		$found_socket = array_search($takashi, $change_socket);
		unset($change_socket[$found_socket]);
	}

	foreach ($change_socket as $changed_socket) {
		while (socket_recv($changed_socket, $buffer, 1024, 0) >= 1) {

			$get_text = unmask($buffer);
			$data = json_decode($get_text);

			$type = $data->type;
			$sender = $data->sender;
			$receiver = $data->receiver;
			$protocols = $data->protocols;
			$parameter = $data->parameter;






			if ($protocols == "userlogin") {
				$sender_decode = explode("|", $sender);

				$allow_apply = false;
				if (count($user_online) == 0) {
					$allow_apply = true;
				} else {
					$allow_apply = false;
					foreach ($user_online as $key => $value) {
						if ($value["username"] == $sender_decode[0]) {
							$allow_apply = false;
							unset($user_online[$key]);
							if ($ip != $key) {
								$user_online[$ip]["username"] = $sender_decode[0];
								$user_online[$ip]["nickname"] = $sender_decode[1];
								$user_online[$ip]["id_user"] = $sender_decode[2];
								$user_online[$ip]["otoritas"] = str_replace(" ", "_", strtolower($sender_decode[3]));
								$user_online[$ip]["via"] = $sender_decode[4];
							}
							break;
						} else {
							$allow_apply = true;
						}
					}
				}
				if ($allow_apply) {
					$user_online[$ip]["username"] = $sender_decode[0];
					$user_online[$ip]["nickname"] = $sender_decode[1];
					$user_online[$ip]["id_user"] = $sender_decode[2];
					$user_online[$ip]["otoritas"] = str_replace(" ", "_", strtolower($sender_decode[3]));
					$user_online[$ip]["via"] = $sender_decode[4];
				} else {
					$protocols = "duplicate_login";
					$receiver = $sender_decode[0];
				}
				print_r($user_online);
			}



			if ($protocols == "logout") {
				foreach ($user_online as $key => $value) {
					if ($value["username"] == $sender) {
						unset($user_online[$key]);
					}
				}

				$protocols = "userlist";
				$parameter = json_encode($user_online);
			}



			
			$communicate = mask(json_encode(array('type' => $type,
				'sender' => $sender,
				'receiver' => $receiver,
				'protocols' => $protocols,
				'time' => date('d M Y, h:i:s'),
				'parameter' => $parameter,
			)));

			if (!empty($protocols)) {
				print_r(array('type' => $type,
					'sender' => $sender,
					'receiver' => $receiver,
					'protocols' => $protocols,
					'time' => date('d M Y, h:i:s'),
					'parameter' => $parameter,
				));
				send($communicate);
			}
			break 2;
		}

		$reading_socket = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($reading_socket === false) {
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);

			//unset($user_online[$ip]);
			//$communicate = mask(json_encode(array('type'=>'system', 'protocol'=>$ip.' disconnected')));
			//send($communicate);
			/*$communicate = mask(json_encode(array(	'sender'=>'system',
														'type'=>'info',
														'protocols'=>'userlist',
														'receiver'=>"*",
														'time'=>date('d M Y, h:i:s'),
														'parameter'=>json_encode($user_online)
												)));

				send($communicate);*/
		}
	}
}
socket_close($takashi);

//================================================SYNCHRONOUS UTILITY
function send($param) {
	global $clients;
	foreach ($clients as $changed_socket) {
		@socket_write($changed_socket, $param, strlen($param));
	}
	return true;
}

function unmask($text) {
	$length = ord($text[1]) & 127;
	if ($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	} elseif ($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	} else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i % 4];
	}
	return $text;
}

function mask($text) {
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);

	if ($length <= 125) {
		$header = pack('CC', $b1, $length);
	} elseif ($length > 125 && $length < 65536) {
		$header = pack('CCn', $b1, 126, $length);
	} elseif ($length >= 65536) {
		$header = pack('CCNN', $b1, 127, $length);
	}

	return $header . $text;
}

function handshake($receved_header, $client_conn, $host_server, $port_number) {
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach ($lines as $line) {
		$line = chop($line);
		if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
			$headers[$matches[1]] = $matches[2];
		}
	}

	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//hand shaking header
	$upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host_server\r\n" .
		"WebSocket-Location: ws://$host_server:$port_number/demo/shout.php\r\n" .
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn, $upgrade, strlen($upgrade));
}
?>