<?php
require ("assets/config.php");

function getBrudamToken()
{
    $urlToken = "https://messenger.brudam.com.br/api/v1/acesso/auth/login";
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
        error_log("Erro ao conectar na API");
        return null;
    } else {
        // Verifica se o token foi retornado com sucesso
        if (isset($tokenData->data->access_key)) {
            return $tokenData->data->access_key;
        } else {
            error_log("Erro ao obter o token de autenticação.");
            return null;
        }
    }
}

//Só roda se receber algum conteúdo do PHP
if (file_get_contents('php://input')) {

    //Pega o que veio e transforma em objeto $DadosForm
    $JsonDoSite = file_get_contents('php://input');
    $DadosForm = json_decode($JsonDoSite);

    //var_dump($DadosForm);die(0);
    // Acessar diretamente os valores de formValues
    if (isset($DadosForm->formValues)) {
        $formValues = $DadosForm->formValues;
    } else {
        $formValues = $DadosForm;
    }

    //Navega por dentro do objeto, colocando os conteúdos em variáveis
    $nome = $formValues->nome;
    $cnpjpagador = $formValues->cnpjpagador;
    $email = $formValues->email;
    $telefone = $formValues->telefone;
    $remessa = $formValues->remessa;
    $peso = $formValues->peso;
    $altura = $formValues->altura;
    $largura = $formValues->largura;
    $profundidade = $formValues->profundidade;
    $notaFiscal = $formValues->notaFiscal;
    $quantidade = $formValues->quantidade;
    $valor = $formValues->valor;
    $conteudo = $formValues->conteudo;
    $cep = $formValues->cep;
    $endereco = $formValues->endereco;
    $numero = $formValues->numero;
    $complemento = $formValues->complemento;
    $bairro = $formValues->bairro;
    $municipio = $formValues->municipio;
    $uf = $formValues->uf;
    $pais = $formValues->pais;
    $data = $formValues->data;
    $hora1 = $formValues->hora1;
    $hora2 = $formValues->hora2;
    $nomeRecebedor= $formValues->nomeRecebedor;
    $docRecebedor= $formValues->docRecebedor;
    $cepDestino = $formValues->cepDestino;
    $enderecoDestino = $formValues->enderecoDestino;
    $numeroDestino = $formValues->numeroDestino;
    $complementoDestino = $formValues->complementoDestino;
    $bairroDestino = $formValues->bairroDestino;
    $municipioDestino = $formValues->municipioDestino;
    $ufDestino = $formValues->ufDestino;
    $paisDestino = $formValues->paisDestino;
    $obs = $formValues->obs;
    $anexo = NULL;


    // Verificar se todos os campos foram preenchidos
    if (
        empty($nome) ||
        empty($cnpjpagador) ||
        empty($email) ||
        empty($telefone) ||
        empty($remessa) ||
        empty($quantidade) ||
        empty($conteudo) ||

        empty($cep) ||
        empty($endereco) ||
        empty($numero) ||
        empty($complemento) ||
        empty($bairro) ||
        empty($municipio) ||
        empty($uf) ||
        empty($pais) ||
        empty($data) ||

        empty($hora1) ||
        empty($hora2) ||

        empty($nomeRecebedor) ||
        empty($docRecebedor) ||
        empty($cepDestino) ||
        empty($enderecoDestino) ||
        empty($numeroDestino) ||
        empty($complementoDestino) ||
        empty($bairroDestino) ||
        empty($municipioDestino) ||
        empty($ufDestino) ||
        empty($paisDestino) ||

        empty($obs)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos do formulário.']);
        exit();
    }

    //Verifica se é um e-mail válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Preencha um e-mail válido']);
        exit();
    }

    // Obter o token de acesso
    $token = getBrudamToken();
    if (!$token) {
        echo json_encode(['status' => 'error', 'message' => 'Falha na autenticação com a API da Brudam']);
        exit();
    }

    // Construir o payload para a API da Brudam
    $payload = [
        "documentos" => [
            [
                "coleta" => [
                    "toma" => 0,
                    "nDocEmit" => $cnpjpagador,
                    "dEmi" => $data,
                    "xSoli" => $nome,
                    "telefoneSoli" => $telefone,
                    "hIni" => $hora1,
                    "hFim" => $hora2,
                    "rSeg" => 0,
                    "cSeg" => "string",
                    "dColeta" => $data,
                    "dColetaLim" => $data,
                    "cServ" => "string",
                    "cAut" => "string",
                    "carga" => [
                        "pBru" => (float) $peso,
                        "pCub" => 0,
                        "qVol" => (int) $quantidade,
                        "vTot" => (float) $valor,
                    ],
                    "volumes" => [
                        [
                            "dCom" => (float) $profundidade,
                            "dLar" => (float) $largura,
                            "dAlt" => (float) $altura,
                            "qVol" => (int) $quantidade,
                            "pBru" => (float) $peso,
                            "pCub" => 0,
                            "cEtiq" => $conteudo,
                        ]
                    ],
                    "cStatus" => 1
                ],
                "compl" => [
                    "respEntrega" => [
                        "tpResp" => 0,
                        "nDoc" => 0
                    ],
                    "respColeta" => [
                        "tpResp" => 0,
                        "nDoc" => 0
                    ]
                ],
                "toma" => [
                    "nDoc" => $cnpjpagador,
                    "IE" => "string",
                    "xNome" => $nome,
                    "xFant" => "string",
                    "nFone" => $telefone,
                    "ISUF" => "string",
                    "xLgr" => 0,
                    "nro" => 0,
                    "xCpl" => 0,
                    "xBairro" => 0,
                    "cMun" => 0,
                    "CEP" => 0,
                    "cPais" => 0,
                    "email" => $email
                ],
                "rem" => [
                    "nDoc" => $cnpjpagador,
                    "IE" => 0,
                    "xNome" => 0,
                    "xFant" => 0,
                    "nFone" => 0,
                    "ISUF" => 0,
                    "xLgr" => 0,
                    "nro" => 0,
                    "xCpl" => 0,
                    "xBairro" => 0,
                    "cMun" => 0,
                    "CEP" => 0,
                    "cPais" => 0,
                    "email" => 0
                ],
                "exped" => [
                    "nDoc" => $cnpjpagador,
                    "IE" => 0,
                    "xNome" => $nome,
                    "xFant" => 0,
                    "nFone" => $telefone,
                    "ISUF" => 0,
                    "xLgr" => $endereco,
                    "nro" => $numero,
                    "xCpl" => $complemento,
                    "xBairro" => $bairro,
                    "cMun" => $municipio,
                    "CEP" => $cep,
                    "cPais" => $pais,
                    "email" => $email
                ],
                "receb" => [
                    "nDoc" => $docRecebedor,
                    "IE" => 0,
                    "xNome" => $nomeRecebedor,
                    "xFant" => 0,
                    "nFone" => 0,
                    "ISUF" => 0,
                    "xLgr" => $enderecoDestino,
                    "nro" => $numeroDestino,
                    "xCpl" => $complementoDestino,
                    "xBairro" => $bairroDestino,
                    "cMun" => $municipioDestino,
                    "CEP" => $cepDestino,
                    "cPais" => $paisDestino,
                    "email" => $email
                ],
                "dest" => [
                    "nDoc" => $docRecebedor,
                    "IE" => 0,
                    "xNome" => $nomeRecebedor,
                    "xFant" => 0,
                    "nFone" => 0,
                    "ISUF" => 0,
                    "xLgr" => $enderecoDestino,
                    "nro" => $numeroDestino,
                    "xCpl" => $complementoDestino,
                    "xBairro" => $bairroDestino,
                    "cMun" => $municipioDestino,
                    "CEP" => $cepDestino,
                    "cPais" => $paisDestino,
                    "email" => $email
                ],
                "documentos" => [
                    [
                        "nPed" => 0,
                        "serie" => 0,
                        "nDoc" => $notaFiscal,
                        "dEmi" => $data,
                        "vBC" => 0,
                        "vICMS" => 0,
                        "vBCST" => 0,
                        "vST" => 0,
                        "vProd" => 0,
                        "vNF" => $valor,
                        "nCFOP" => 0,
                        "pBru" => $peso,
                        "qVol" => $quantidade,
                        "PIN" => 0,
                        "chave" => 0,
                        "tpDoc" => "00",
                        "xEsp" => $conteudo,
                        "xNat" => 0
                    ]
                ]
            ]
        ]
    ];

    // Enviar o payload para a API da Brudam usando cURL com o token de autenticação
    $ch = curl_init('https://messenger.brudam.com.br/api/v1/operacional/emissao/coleta');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POST, true);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    //var_dump($payload);die(0);

    $response = curl_exec($ch);

    // Captura de erros do cURL
    if (curl_errno($ch)) {
        error_log('Erro cURL: ' . curl_error($ch));
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Adicionar logs para depuração
    error_log("Request payload: " . json_encode($payload));
    error_log("Response: " . $response);
    error_log("HTTP Status Code: " . $http_code);

    header('Content-Type: application/json'); // Garantir que o tipo de conteúdo é JSON  .  

    if ($http_code === 200) {
        $apiResponse = json_decode($response, true);
        if (isset($apiResponse['status']) && $apiResponse['status'] == 1) {
            echo json_encode($apiResponse);
        } else {
            echo json_encode(['status' => 'error', 'response' => $apiResponse]);
        }
    } else {
        echo json_encode(['status' => 'error', 'response' => json_decode($response)]);
    }

    $EmailEnvio = $email;
    $EmailTitulo = 'SOLICITAÇÃO DE COLETA';
    $EmailMsg = 'DADOS DO SOLICITANTE:<br><br>
               Nome: ' . $nome . '<br>
               CNPJ: ' . $cnpjpagador . '<br>
               Telefone: ' . $telefone . '<br>
               E-mail: ' . $email . '<br><br>

               DADOS DA CARGA <br><br>
               Tipo de Remessa: ' . $remessa . '<br>
               Peso: ' . $peso . '<br>
               Altura: ' . $altura . '<br>
               Largura: ' . $largura . '<br>
               Profundidade: ' . $profundidade . '<br>
               Nota Fiscal: ' . $notaFiscal . '<br>
               Quantidade de Volumes: ' . $quantidade . '<br>
               Valor da Mercadoria: ' . $valor . '<br>
               Descrição do Conteúdo: ' . $conteudo . '<br><br>

               DADOS DO TRANSPORTE <br><br>
               CEP de Origem: ' . $cep . '<br>
               Endereço: ' . $endereco . ', ' . $numero . ', ' . $complemento . ', ' . $bairro . ' - ' . $municipio . ' - ' . $uf . '/' . $pais . '<br>

               Data da Coleta: ' . $data . '<br><br>

               HORA DA COLETA<br><br>
               De: ' . $hora1 . '<br>
               Até: ' . $hora2 . '<br><br>

               ENDEREÇO DE DESTINO <br><br>
               Nome do Recebedor: ' . $nomeRecebedor . '<br>
               Documento do Recebedor: ' . $docRecebedor . '<br>
               CEP de Destino: ' . $cepDestino . '<br>
               Endereço: ' . $enderecoDestino . ', ' . $numeroDestino . ', ' . $complementoDestino . ', ' . $bairroDestino . ' - ' . $municipioDestino . ' - ' . $ufDestino . '/' . $paisDestino . '<br>

               Observações sobre a carga/manuseio/transporte: ' . $obs . '<br><br>
               Essa é uma mensagem automática enviada em ' . date('d/m/y H:i');

    include ("assets/mail.php");
}
?>