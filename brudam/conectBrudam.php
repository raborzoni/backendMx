<?php

$username = "03ae8e64b03ca264233a3d6b6c4c0257";
$password = "92166f9c4281c1d2861fb69d2584e6e540279b5f982e70907d74874efe3faabd";

$url = "https://messenger.brudam.com.br/api/v1/acesso/auth/login"; // Substitua pela URL da API externa

$ch = curl_init($url);

// Configurações da requisição
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

// Executa a requisição e captura a resposta
$response = curl_exec($ch);

// Verifica se houve algum erro na requisição
if(curl_errno($ch)){
    echo 'Erro: ' . curl_error($ch);
}

// Fecha a conexão
curl_close($ch);

// Processa a resposta
$data = json_decode($response, true); // Converte a resposta em um array associativo
if($data['success'] === true){
    echo "Autenticado com sucesso!";
}else{
    echo "Falha na autenticação!";
}
?>