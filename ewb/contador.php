<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("barcode_inc.php");
require_once("barcode.php");

function incrementCounter() {
	$counterFile = 'ewb/contador.txt';

	// Verifica se o arquivo existe
	if (file_exists($counterFile)) {
		// Lê o valor atual do contador
		$currentValue = intval(file_get_contents($counterFile));
	} else {
		// Se o arquivo não existir, cria-o com o valor inicial (zero)
		$currentValue = 0;
		file_put_contents($counterFile, $currentValue);
	}

	// Incrementa o valor do contador
	$currentValue++;

	// Atualiza o arquivo com o novo valor do contador
	file_put_contents($counterFile, $currentValue);

	// Retorna o valor atualizado do contador
	return $currentValue;
}

// Exemplo de uso da função
$contador = incrementCounter();
echo "Nro da E-WB armazenada: " . $contador. "<br>";

$filepath = "ewb/barcode_img/".$contador.".png";
$a = barcode( $filepath, $contador, '25', "horizontal", "Codabar", TRUE, 1);
echo "<img src='https://api.upsilan.com.br/".$filepath."' width='200px' height='90px' />";
