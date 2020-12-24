<?php
	$context = stream_context_create();

	// local_cert must be in PEM format
	if(!file_exists('api/pdk.pem')) {
		$certificateData = array(
			"countryName" => "ID",
			"stateOrProvinceName" => "Sumatera Utara",
			"localityName" => "Medan",
			"organizationName" => "PondokCoder",
			"organizationalUnitName" => "pondokcoder",
			"commonName" => "tanaka",
			"emailAddress" => "tanaka@pondokcoder.com"
		);

		// Generate certificate
		$privateKey = openssl_pkey_new();
		$certificate = openssl_csr_new($certificateData, $privateKey);
		$certificate = openssl_csr_sign($certificate, null, $privateKey, 365);

		// Generate PEM file
		$pem_passphrase = 'abracadabra'; // empty for no passphrase
		$pem = array();
		openssl_x509_export($certificate, $pem[0]);
		openssl_pkey_export($privateKey, $pem[1], $pem_passphrase);
		$pem = implode($pem);

		// Save PEM file
		$pemfile = 'api/pdk.pem';
		file_put_contents($pemfile, $pem);
	}
	//stream_context_set_option($context, 'ssl', 'local_cert', json_decode(file_get_contents('api/pdk.pem', true)));
	stream_context_set_option($context, 'ssl', 'local_cert', 'api/pdk.pem');
	//stream_context_set_option($context, 'ssl', 'cafile', json_decode(file_get_contents('api/pdk.pem')));

	// Pass Phrase (password) of private key
	stream_context_set_option($context, 'ssl', 'passphrase', 'abracadabra');
	stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
	stream_context_set_option($context, 'ssl', 'verify_peer', false);
	stream_context_set_option($context, 'ssl', 'verify_peer_name', false);

	// Create the server socket
	$socket = stream_socket_server(
		'ssl://192.168.100.132:666',
		$errno,
		$errstr,
		STREAM_SERVER_BIND|STREAM_SERVER_LISTEN,
		$context
	);
	while(true)
	{
		$buffer = '';
		$client = stream_socket_accept($socket);
		if($client) {
			// Read until double CRLF
			while( !preg_match('/\r?\n\r?\n/', $buffer) )
				$buffer .= fread($client, 2046); 
			// Respond to client
			fwrite($client,  "200 OK HTTP/1.1\r\n"
							 . "Connection: close\r\n"
							 . "Content-Type: text/html\r\n"
							 . "\r\n"
							 . "Hello World! " . microtime(true)
							 . "\n<pre>{$buffer}</pre>");
			fclose($client);
		}
	}
?>