<?php
//var_dump("oi");die(0);
require ("assets/config.php");

// Verifica se há dados enviados pelo formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar se todos os campos obrigatórios foram preenchidos
    $camposObrigatorios = ['cnpj', 'razaoSocial', 'inscricaoEstadual', 'cnae', 'nome', 'email', 'telefone', 
    'emailFinanceiro', 'nomeFinanceiro', 'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'municipio', 
    'uf', 'pais'];
    foreach ($camposObrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            echo "Por favor, preencha todos os campos do formulário.";
            exit();
        }
    }

    // Verifica se o e-mail é válido
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Preencha um e-mail válido.";
        exit();
    }

    //Navega por dentro do objeto, colocando os conteúdos em variáveis
    $cnpj = $_POST['cnpj'];
    $razaoSocial = $_POST['razaoSocial'];
    $inscricaoEstadual = $_POST['inscricaoEstadual'];
    $cnae = $_POST['cnae'];
    $nome = $_POST['nome'];
    $email  = $_POST['email'];
    $telefone = $_POST['telefone'];
    $emailFinanceiro = $_POST['emailFinanceiro'];
    $nomeFinanceiro = $_POST['nomeFinanceiro'];
    $cep = $_POST['cep'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $municipio = $_POST['municipio'];
    $uf = $_POST['uf'];
    $pais = $_POST['pais'];

    // Verificar se um arquivo foi enviado
    if (!empty($_FILES['documento']['name'])) {
    // Verificar se o arquivo foi enviado corretamente
        if ($_FILES['documento']['error'] !== UPLOAD_ERR_OK) {
            echo "Ocorreu um erro ao enviar o arquivo.";
            exit();
        }
        // Ler o conteúdo do arquivo
        $documentoContent = file_get_contents($_FILES["documento"]["tmp_name"]);
    } else {
        $documentoContent = null; // Define o conteúdo como null se nenhum arquivo foi enviado
    }

    //Prepara os campos padronizados para enviar o e-mail
    //$EmailEnvio = $email;
    $EmailTitulo = 'CONTA ABERTA';
    $EmailMsg = 'DADOS DA NOVA CONTA ABERTA:<br><br>
               Razão Social: ' . $razaoSocial . '<br>
               CNPJ: ' . $cnpj . '<br>
               Inscrição Estadual: ' . $inscricaoEstadual . '<br>
               CNAE: ' . $cnae . '<br>
               Nome do Responsável: ' . $nome . '<br>
               E-mail do Responsável: ' . $email . '<br>
               Telefone para Contato: ' . $telefone . '<br>
               Email do Responsável Financeiro: ' . $emailFinanceiro . '<br>
               Contato no Depto Financeiro: ' . $nomeFinanceiro . '<br>
               CEP: ' . $cep . '<br>
               Logradouro: ' . $logradouro . '<br>
               Número: ' . $numero . '<br>
               Complemento: ' . $complemento . '<br>
               Bairro: ' . $bairro . '<br>
               Município: ' . $municipio . '<br>
               UF: ' . $uf . '<br>
               País: ' . $pais . '<br><br>
               Essa é uma mensagem automática enviada em ' . date('d/m/y H:i');

    // Preparar o anexo para o e-mail           
    $anexo = array(
        'nome' => $_FILES["documento"]["name"],
        'conteudo' => $documentoContent
    );
    include ("assets/mail.php");
} else {
    echo "Nenhum dado foi enviado pelo formulário.";
}