<?php
require("assets/config.php");
// require_once 'vendor/autoload.php';

//Só roda se receber algum conteúdo do PHP
if (file_get_contents('php://input')) {

    //Pega o que veio e transforma em objeto $DadosForm
    $JsonDoSite = file_get_contents('php://input');
    $DadosForm = json_decode($JsonDoSite);

    // Acessar diretamente os valores de formValues
    $formValues = $DadosForm->formValues;

    //Navega por dentro do objeto, colocando os conteúdos em variáveis
    //Dados do Solicitante
    $nome = $formValues->nome;
    $numeroConta = $formValues->numeroConta;
    $email = $formValues->email;
    $telefone = $formValues->telefone;

    //Dados da Carga
    $embalagem = $formValues->embalagem;
    $peso = $formValues->peso;
    $altura = $formValues->altura;
    $largura = $formValues->largura;
    $profundidade = $formValues->profundidade;
    $notaFiscal = $formValues->notaFiscal;
    $quantidade = $formValues->quantidade;
    $valor = $formValues->valor;
    $conteudo = $formValues->conteudo;

    //Dados de Transporte - Origem
    $cepRemetente = $formValues->cepRemetente;
    $enderecoRemetente = $formValues->enderecoRemetente;
    $numeroRemetente = $formValues->numeroRemetente;
    $complementoRemetente = $formValues->complementoRemetente;
    $bairroRemetente = $formValues->bairroRemetente;
    $cidadeRemetente = $formValues->cidadeRemetente;
    $estadoRemetente = $formValues->estadoRemetente;
    $paisRemetente = $formValues->paisRemetente;
    $remetente = $formValues->remetente;

    //Dados de Transporte - Destino
    $cepDestinatario = $formValues->cepDestinatario;
    $enderecoDestinatario = $formValues->enderecoDestinatario;
    $numeroDestinatario = $formValues->numeroDestinatario;
    $complementoDestinatario = $formValues->complementoDestinatario;
    $bairroDestinatario = $formValues->bairroDestinatario;
    $cidadeDestinatario = $formValues->cidadeDestinatario;
    $estadoDestinatario = $formValues->estadoDestinatario;
    $paisDestinatario = $formValues->paisDestinatario;
    $destinatario = $formValues->destinatario;

    //Coleta - Coleta
    $dataColeta = $formValues->dataColeta;
    $hora1 = $formValues->hora1;
    $hora2 = $formValues->hora2;

    //Coleta - Entrega
    $dataEntrega = $formValues->dataEntrega;
    $horaEntrega1 = $formValues->horaEntrega1;
    $horaEntrega2 = $formValues->horaEntrega2;
    $obs = $formValues->obs;


    // Verificar se todos os campos foram preenchidos
    if (
        empty($nome) || 
        empty($numeroConta) || 
        empty($email) || 
        empty($telefone) ||

        empty($embalagem) || 
        empty($notaFiscal) || 
        empty($quantidade) || 
        empty($valor) ||
        empty($conteudo) || 

        empty($cepRemetente) || 
        empty($enderecoRemetente) || 
        empty($numeroRemetente) ||
        empty($complementoRemetente) ||
        empty($bairroRemetente) ||
        empty($cidadeRemetente) || 
        empty($estadoRemetente) || 
        empty($paisRemetente) || 
        empty($remetente) ||
        
        empty($cepDestinatario) || 
        empty($enderecoDestinatario) || 
        empty($numeroDestinatario) ||
        empty($complementoDestinatario) || 
        empty($bairroDestinatario) ||
        empty($cidadeDestinatario) ||
        empty($estadoDestinatario) || 
        empty($paisDestinatario) || 
        empty($destinatario) || 

        empty($dataColeta) || 
        empty($hora1) ||
        empty($hora2) || 

        empty($dataEntrega) || 
        empty($horaEntrega1) || 
        empty($horaEntrega2) 
    ) {
        echo "Por favor, preencha todos os campos do formulário.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Preencha um e-mail válido";
        exit();
    }

    //Prepara os campos padronizados para enviar o e-mail
    $EmailEnvio = $email;
    $EmailTitulo = 'NOVA COTAÇÃO';
    $EmailMsg = 'DADOS DO SOLICITANTE:<br><br>
               Nome: ' . $nome . '<br>
               CNPJ/CPF ou Conta Pagadora: ' . $numeroConta . '<br>
               Telefone: ' . $telefone . '<br>
               E-mail: ' . $email . '<br><br>

               DADOS DA CARGA <br><br>
               Tipo de Embalagem: ' . $embalagem . '<br>
               Peso: ' . $peso . '<br>
               Altura: ' . $altura . '<br>
               Largura: ' . $largura . '<br>
               Profundidade: ' . $profundidade . '<br>
               Nota Fiscal: ' . $notaFiscal . '<br>
               Quantidade de Volumes: ' . $quantidade . '<br>
               Valor da Mercadoria: ' . $valor . '<br>
               Descrição do Conteúdo: ' . $conteudo . '<br><br>

               DADOS DO TRANSPORTE <br><br>
               CEP: ' . $cepRemetente . '<br>
               Endereço de Origem: ' . $enderecoRemetente . '<br>
               Número: ' . $numeroRemetente . '<br>
               Complemento: ' . $complementoRemetente . '<br>
               Bairro: ' . $bairroRemetente . '<br>
               Cidade: ' . $cidadeRemetente . '<br>
               Estado: ' . $estadoRemetente . '<br>
               Pais: ' . $paisRemetente . '<br>
               CPF/ CNPJ do Remetente: ' . $remetente . '<br><br>

               DESTINO <br><br>
               CEP: ' . $cepDestinatario . '<br>
               Endereço de Destino: ' . $enderecoDestinatario . '<br>
               Número: ' . $numeroDestinatario . '<br>
               Complemento: ' . $complementoDestinatario . '<br>
               Bairro: ' . $bairroDestinatario . '<br>
               Cidade: ' . $cidadeDestinatario . '<br>
               Estado: ' . $estadoDestinatario . '<br>
               Pais: ' . $paisDestinatario . '<br>
               CPF/ CNPJ do Destinatário: ' . $destinatario . '<br><br>

               HORA DA COLETA <br><br>
               Data da Coleta: ' . $dataColeta . '<br>
               De: ' . $hora1 . '<br>
               Até: ' . $hora2 . '<br><br>
               
               ENTREGA <br><br>
               Data da Entrega: ' . $dataEntrega . '<br>
               De: ' . $horaEntrega1 . '<br>
               Até: ' . $horaEntrega2 . '<br>
               Observações sobre a carga/manuseio/transporte: ' . $obs . '<br><br>
               Essa é uma mensagem automática enviada em ' . date('d/m/y H:i');

    include ("assets/mail.php");
}
?>