<?php
require_once 'config.php';
// require_once 'vendor/autoload.php';

//Só roda se receber algum conteúdo do PHP
if (file_get_contents('php://input')) {
   
   //Pega o que veio e transforma em objeto $DadosForm
   $JsonDoSite = file_get_contents('php://input');
   $DadosForm = json_decode($JsonDoSite);
    
   //Navega por dentro do objeto, colocando os conteúdos em variáveis
   $nome = $DadosForm->formulario->nome;
   $telefone = $DadosForm->formulario->telefone;
   $email = $DadosForm->formulario->email;
   $assunto = $DadosForm->formulario->assunto;
   $mensagem = $DadosForm->formulario->mensagem;

   // Verificar se todos os campos foram preenchidos
   if (empty($nome) || empty($telefone) || empty($email) || empty($assunto) || empty($mensagem)) {
      echo "Por favor, preencha todos os campos do formulário.";
      exit();
   }

   //Verifica se é um e-mail válido
   if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
      echo "Preencha um e-mail válido";
      exit();
   }

   //Prepara os campos padronizados para enviar o e-mail
   $EmailEnvio = $email; 
   $EmailTitulo = 'Nova conta de cliente criada no site';
   $EmailMsg = 'Nova conta de cliente criada no site:<br><br>
               Nome: '.$nome.'<br>
               Telefone: '.$telefone.'<br>
               E-mail: '.$email.'<br>
               Assunto: '.$assunto.'<br>
               Mensagem: '.$mensagem.'<br>
               Essa mensagem foi enviada em '. date('d/m/y H:i'); 
   //Esse form não manda anexo, mas outros mandam
   $anexo = NULL;
      
   include 'mail.php';

}

  
?>