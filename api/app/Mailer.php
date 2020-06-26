<?php

	namespace PondokCoder;

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	

	Class Mailer{
		public function __construct($mail_setting = array(), $parameter, $subject, $content, $content_alt, $receiver = array()){

			$mail = new PHPMailer(true);
			try {
				//Server settings
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);

				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->Host = $mail_setting["server"];
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = $mail_setting["secure_type"];
				$mail->Port = $mail_setting["port"];
				$mail->Username = $mail_setting["username"];
				$mail->Password = $mail_setting["password"];
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->DKIM_domain = __HOSTNAME__;
				$mail->DKIM_private = 'sitanggap.key';
				$mail->DKIM_selector = 'phpmailer';
				$mail->DKIM_passphrase = '';
				


				//Recipients
				$mail->setFrom($mail_setting["fromMail"], $mail_setting["fromName"]);
				foreach ($receiver as $key => $value) {
					$mail->addAddress($key, $value);
				}
				
				$mail->addReplyTo($mail_setting["replyMail"], $mail_setting["replyMail"]);
				//$mail->addCC("cc@example.com");
				//$mail->addBCC("bcc@example.com");

				// Attachments
				//$mail->addAttachment("/var/tmp/file.tar.gz");         // Add attachments
				//$mail->addAttachment("/tmp/image.jpg", "new.jpg");    // Optional name
				$body = file_get_contents("email_template/index.phtml");
				foreach($parameter as $k=>$v){
					$body = str_replace("{".strtoupper($k)."}", $v, $body);
				}
				$mail->Subject = $subject;
				$mail->Body    = $body;
				$mail->isHTML(true);
				$mail->AltBody = $content_alt;

				$mail->send();
				return "Message has been sent";
			} catch (Exception $e) {
				return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}
?>