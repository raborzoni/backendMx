<?php
require("assets/config.php");

if (file_get_contents('php://input')) {
    // Pega o que veio e transforma em objeto $DadosForm
    $JsonDoSite = file_get_contents('php://input');
    $DadosForm = json_decode($JsonDoSite);

    $rastreamento = $DadosForm->formulario->rastreamento;

    // Número de rastreamento a ser verificado
    $numeroRastreamento = $rastreamento;

    // URL do endpoint para obter o token de autenticação
    $urlToken = "https://messenger.brudam.com.br/api/v1/acesso/auth/login";

    // Usuário e senha para autenticação
    $usuario = "03ae8e64b03ca264233a3d6b6c4c0257";
    $senha = "92166f9c4281c1d2861fb69d2584e6e540279b5f982e70907d74874efe3faabd";

    // Parâmetros da requisição POST
    $data = array(
        'usuario' => $usuario,
        'senha' => $senha
    );

    // Configura as opções da requisição
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );

    // Cria um contexto de requisição
    $context = stream_context_create($options);

    // Realiza a requisição para obter o token
    $resultToken = file_get_contents($urlToken, false, $context);

    // Decodifica o resultado como um objeto JSON
    $tokenData = json_decode($resultToken);

    // Verifica se a requisição foi bem-sucedida
    if ($tokenData->status === FALSE) {
        echo "Erro ao conectar na API";
    } else {
        // Verifica se o token foi retornado com sucesso
        if (isset($tokenData->data->access_key)) {
            $token = $tokenData->data->access_key;

            // URLs dos endpoints para buscar informações de rastreamento
            $urlEndpoint1 = "https://messenger.brudam.com.br/api/v1/tracking/ocorrencias/aut_frete?frete=";
            $urlEndpoint2 = "https://messenger.brudam.com.br/api/v1/tracking/ocorrencias/minuta?codigo=";

            // Monta a URL completa com o número de rastreamento para o Endpoint 1
            $url1 = $urlEndpoint1 . $numeroRastreamento;

            // Monta a URL completa com o número de rastreamento para o Endpoint 2
            $url2 = $urlEndpoint2 . $numeroRastreamento;

            //var_dump($url2);die(0);
            // Configura as opções da requisição para o Endpoint 1
            $options1 = [
                'http' => [
                    'header' => "Authorization: Bearer " . $token,
                    'method' => 'GET',
                ],
            ];

            //var_dump($numeroRastreamento);die(0);
            // Configura as opções da requisição para o Endpoint 2
            $options2 = [
                'http' => [
                    'header' => "Authorization: Bearer " . $token,
                    'method' => 'GET',
                ],
            ];
            
            // Cria um contexto de requisição para o Endpoint 1
            $context1 = stream_context_create($options1);

            // Cria um contexto de requisição para o Endpoint 2
            $context2 = stream_context_create($options2);

            // Faz a requisição HTTP para o Endpoint 1
            $result1 = file_get_contents($url1, false, $context1);
    
            // Decodifica o resultado como um objeto JSON
            $APIData = json_decode($result1);

            // Verifica se a requisição foi bem-sucedida
            if ($APIData->data[0]->status == FALSE) {
            
            // Faz a requisição HTTP para o Endpoint 2 somente se a requisição para o Endpoint 1 não retornou dados
                $result2 = file_get_contents($url2, false, $context2);

                // Transforma os dados em JSON
                $dadosRastreamento = json_decode($result2);

                // Verifica se a requisição foi bem-sucedida
                if ($dadosRastreamento->status === FALSE) {
                    echo "Erro ao conectar à API externa.";
                } else {
                    // Decodifica o resultado como um objeto JSON
                    header("Content-Type: application/json");
                    echo json_encode($dadosRastreamento);
                    exit();
                }
            } else {
                // Transforma os dados em JSON
                $dadosRastreamento = json_decode($result1);

                // Verifica se a requisição foi bem-sucedida
                if ($dadosRastreamento->status === FALSE) {
                    echo "Erro ao conectar à API externa.";
                } else {
                    // Decodifica o resultado como um objeto JSON
                    header("Content-Type: application/json");
                    echo json_encode($dadosRastreamento);
                    exit();
                }
            }
        } else {
            echo "Erro ao obter o token de autenticação.";
        }
    }
}
?>


        // Verifica se o token foi retornado com sucesso
        if (isset($tokenData->data->access_key)) {
            $token = $tokenData->data->access_key;

            // URL do endpoint para buscar informações de rastreamento
            $urlEndpoint = "https://messenger.brudam.com.br/api/v1/tracking/ocorrencias/pedido";

            // Monta a URL completa com o número de rastreamento
            $url = $urlEndpoint . "?pedidos=" . $numeroRastreamento;

            // Configura as opções da requisição
            $options = [
                'http' => [
                    'header' => "Authorization: Bearer " . $token,
                    'method' => 'GET',
                ],
            ];

            // Cria um contexto de requisição
            $context = stream_context_create($options);

            // Faz a requisição HTTP para a API
            $result = file_get_contents($url, false, $context);

            // Transforma os dados em Json
            $dadosRastreamento = json_decode($result);

            // Verifica se a requisição foi bem-sucedida
            if ($dadosRastreamento->status === FALSE) {
                echo "Erro ao conectar à API externa.";
            } else {
                // Decodifica o resultado como um objeto JSON

                header("Content-Type: application/json");
                echo json_encode($dadosRastreamento);
                exit();
            }
        } else {
            echo "Erro ao obter o token de autenticação.";
        }