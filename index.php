<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$router = new \Bramus\Router\Router();

// Rota inicial!
// Documentação https://github.com/bramus/router
$router->get('/', function () {

    echo 'Eu!';

});

$router->get('/daytonaUp', function () {
    require ("daytona/get_update_daytona.php");

});

$router->get('/conectBrudam', function () {
    include ("assets/config.php");
    require ("brudam/conectBrudam.php");
});
$router->post('/rastreamentoBrudam', function () {
    include ("assets/config.php");
    require ("brudam/rastreamentoBrudam.php");

});

$router->post('/eewb', function () {
    include ("assets/config.php");
    require ("ewb/eewb.php");

});

//Formulários
$router->post('/form_contato', function () {
    include ("assets/config.php");
    require ("forms/form_contato.php");
});

$router->post('/form_conta', function () {
    include ("assets/config.php");
    require ("forms/form_conta.php");
});

$router->post('/form_cotacao', function () {
    include ("assets/config.php");
    require ("forms/form_cotacao.php");
});

$router->post('/form_coleta', function () {
    include ("assets/config.php");
    require ("forms/form_coleta.php");
});

//Rota de teste de geração de PDF!
//Documentação https://mpdf.github.io/getting-started/html-or-php.html
$router->get('/pdf/(\w+)', function ($name) {
    $ramonemPDF = "<h1 style='color:gray'>Título do meu PDF</h1><br>
                    <p><b>" . $name . ", seu delícia</b></p>";
    $pdf = new \Mpdf\Mpdf();
    $pdf->WriteHTML($ramonemPDF);
    $pdf->Output();

});

// Coloca no Ar!
$router->run();