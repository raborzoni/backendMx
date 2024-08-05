<?php

//Documentação https://github.com/PHPMailer/PHPMailer
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
	//Server settings
	// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	$mail->isSMTP();                                            //Send using SMTP
	$mail->Host = 'api.upsilan.com.br';                     //Set the SMTP server to send through
	$mail->SMTPAuth = true;                                   //Enable SMTP authentication
	$mail->Username = 'contato@api.upsilan.com.br';                     //SMTP username
	$mail->Password = 'KLGW85B74X0TF5NLIU';                               //SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
	$mail->Port = 465;                                    //TCP port to connect to; 

	//Recipients
	$mail->setFrom('contato@api.upsilan.com.br', 'MESSENGER EXPRESS');
	$mail->addAddress('raborzoni@gmail.com');
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('marcos.oliveira@messenger.com.br');
	//$mail->addBCC('brunorodrigotubio@gmail.com');
	//$mail->addCC('raborzoni@gmail.com');

	if (!is_null($anexo)) {
		// Attachments
		$mail->addStringAttachment($anexo['conteudo'], $anexo['nome']);  // Add attachment from content
	}

	//Content
	$mail->isHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Subject = mb_encode_mimeheader($EmailTitulo, 'UTF-8', 'B'); // Usar o título codificado
	$mail->Body = $EmailMsg;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	$mail->send();
	//echo 'Email enviado com sucesso';
} catch (Exception $e) {
	echo "Erro ao enviar o email: {$mail->ErrorInfo}";
}
