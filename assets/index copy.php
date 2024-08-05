<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// var_dump($_POST); die(0);    
// echo '<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">';

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';


// Create a Router
$router = new \Bramus\Router\Router();

// Custom 404 Handler
//$router->set404(function() {
//	header('HTTP/1.1 404 Not Found');
//	echo '404, não encontrado!';
//});


// Static route: / (homepage)
$router->get('/', function() {
	echo '<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">';
	echo '<div class="w3-display-container w3-blue" style="height:100%;">
			<div class="w3-display-middle">
			<h1>API DE TESTES</h1><p>Ramon, te amo!!!<p>
			</div>
			</div>';
});

// Dynamic route: /hello/name

$router->get('/hello/(\w+)', function($name) {
	echo 'Hello ' . htmlentities($name);
});



$router->get('/bemvindo/', function() {
	echo '<h1>Seja Bem vindo a nossa página!</h1> ';
    echo '<h2>Clique aqui para logar com o Twitter</h2>
    <a href="https://api.twitter.com/oauth/request_token"  class="w3-button w3-blue">Vem de TT</a>';
});

$router->get('/twitter', function() {
	include 'twitter.php';
});

$router->post('/mail', function() {
	include 'mail.php';
});

$router->get('/callback/', function() {
	require 'callback.php';
});

$router->post('/contato/', function() {
	
	$MsgPostSucesso = "Mensagem enviada com sucesso!"; 
	//Trata os dados que vieram do postman
	$items = array();
	$items[] = $MsgPostSucesso;

	foreach($_POST as $key => $value) {
	  $items[] = "O item '$key' veio como '$value'";
	}

	//Trata os dados que vieram do front
	$dadosForm = array();
	// unserialize($_POST, $dadosForm);	

	foreach($dadosForm as $key => $value) {
	  $items[] = "O item '$key' veio como '$value'";
	}	

	header("Content-Type: application/json");
	echo json_encode($items);
	exit();

	//echo "Você conseguiu fazer um post bem sucedido, parabéns!<br>";
	//var_dump($_POST);
});

//var_dump($router);die(0);

$router->run();
