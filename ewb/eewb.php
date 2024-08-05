<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DOC_ROOT', __DIR__ . '/');
$assetsPath = DOC_ROOT . '../assets/';

require_once ($assetsPath . 'libraries/barcode_inc.php');
require_once ($assetsPath . 'libraries/barcode.php');
use Dompdf\Dompdf;

$data = json_decode(file_get_contents('php://input'), true);

$pdf_path = '';

if (isset($data)) {
    $sql = "SELECT ewb FROM contador";
    $result = $conn->query($sql);
    $ewb = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ewb = $row["ewb"];
        $ewb += 1;

        // Atualizar o valor do contador no banco de dados
        $updateSql = "UPDATE contador SET ewb = $ewb";
        if ($conn->query($updateSql) !== TRUE) {
            echo json_encode(array('error' => 'Erro ao atualizar o contador: ' . $conn->error));
            exit();
        }
    } else {
        echo json_encode(array('error' => 'Nenhum contador encontrado'));
        exit();
    }

    $formulario = $data;

    file_put_contents('php://stderr', print_r($formulario, true));

    if (isset($formulario)) {

        $check = isset($formulario['extra']) ? implode(' ', $formulario['extra']) : '';

        $barcode = $ewb;
        $filepath = $assetsPath . "temp/img/barcode/" . $barcode . ".png";
        $barcode_img = $assetsPath . "temp/img/barcode/" . $barcode . ".png";
        $img_mx = $assetsPath . "img/logo.jpg";
        $text = $ewb;
        $size = "25";
        $orientation = "horizontal";
        $code_type = "Codabar";
        $print = true;
        $sizefactor = "1";

        barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);

        // Converter imagens para base64
        $logoData = base64_encode(file_get_contents($img_mx));
        $barcodeData = base64_encode(file_get_contents($barcode_img));

        $logoBase64 = 'data:image/jpeg;base64,' . $logoData;
        $barcodeBase64 = 'data:image/png;base64,' . $barcodeData;

        $html = "";

        $html .= "<!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
            <title>e-wb</title>
            <style>
            @page{
                table {
                    margin: 0px;
                }
                }html, body *{
                        border: 0px;
                        margin-top: 1px;
                    margin-left: 6px;
                    margin-right: 6px;
                    padding-left: 6px;
                    font-family: 'Trebuchet MS' !important;
                    font-size: 14px;
                }tr.caixa td {
                    border: 1px solid black;
                }
                img {
                    vertical-align: text-top;
                }
                footer{
                    position:absolute;
                    width:100%;
                    bottom:0px;
                    }
            </style>
        </head>
        <body>
        <center>
        <table style='width: 750px; border: 0px solid black;'>
        <tbody>
        <tr>
        <td colspan='2' style='width: 350px;'align='left'><img src='" . $logoBase64 . "' width='240px'></td>
        <td colspan='2' style='width: 350px;'align='right' ><br><img src='" . $barcodeBase64 . "' width='200px' height='90px' /></td>
        </tr>
        <tr>
        <th style='width: 100%;border: 1px solid black' colspan='4'><center>GUIA DE EMBARQUE ELETRÔNICA / E-WB " . $ewb . "</center></th>
        </tr>
        <tr>
        <td colspan='2' style='width: 50%;'><br><b>Informações do remetente/Shipper</b><br></td>
        <td colspan='2' style='width: 50%;'><br><b>Informações da carga/Shipment information</b><br></td>
        </tr>
        <tr>
        <td colspan='2'>Conta/Account: " . $formulario['cnpjpagador'] . "</td>
        <td>Data/Date</td>
        <td>: " . date("d/M/Y H:i") . "</td>
        </tr>
        <tr>
        <td colspan='2'>" . $formulario['nome'] . "</td>
        <td>Volumes/Pieces</td>
        <td>: " . $formulario['quantidade'] . "</td>
        </tr>
        <tr>
        <td colspan='2'>" . $formulario['razaoSocial'] . "</td>
        <td>Peso/Weight</td>
        <td>: " . $formulario['peso'] . "</td>
        </tr>
        <tr>
        <td colspan='2'>" . $formulario['endereco'] . "</td>
        <td>Medidas/Dimensions(cm)</td>
        <td>: " . $formulario['altura'] . " X " . $formulario['largura'] . " X " . $formulario['profundidade'] . "</td>
        </tr>
        <tr>
        <td colspan='2'></td>
        <td>Conteúdo/Content</td>
        <td>: " . $formulario['conteudo'] . " </td>
        </tr>
        <tr>
        <td>" . $formulario['municipio'] . ", " . $formulario['uf'] . "</td>
        <td>" . $formulario['cep'] . "</td>
        <td>Valor/Value</td>
        <td>: " . $formulario['valor'] . "</td>
        </tr>
        <tr>
        <td colspan='2'>" . $formulario['pais'] . "</td>
        <td>NF/Invoice #</td>
        <td>: " . $formulario['notaFiscal'] . "</td>
        </tr>
        <tr>
        <td colspan='2'>&nbsp;</td>
        <td>Serviço/Service</td>
        <td>: " . $formulario['remessa'] . " " . $formulario['servico'] . " " . $check . "</td>
        </tr>
        <tr>
        <td colspan='2'>Tel/Phone: " . $formulario['telefone'] . "</td>
        <td>Instruções/Specials</td>
        <td>: " . $formulario['obs'] . "</td>
        </tr>
        <tr>
        <td colspan='2'>CPF/CNPJ/Tax ID: " . $formulario['cnpj'] . "</td>
        <td>Pagamento/Payment</td>
        <td>: Remetente</td>
        </tr>
        <tr>
        <td colspan='4'><br><b style='font-size: 14px;'>Informações do Destinatário/<i style='font-size: 14px;'>Consignee</i></b><br></td>
        </tr>
        <tr>
        <td style='font-size: 16px;' colspan='1'>Nome /<i style='font-size: 16px;'>Name:</i></td>
        <td style='font-size: 16px;' colspan='3'>" . $formulario['nomeRecebedor'] . "</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td style='font-size: 16px;' colspan='1'>Empresa /<i style='font-size: 16px;'>Company:</i></td>
        <td style='font-size: 16px;' colspan='3'>" . $formulario['razaoSocialDestino'] . "</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td style='font-size: 16px;' colspan='1'>End /<i style='font-size: 16px;'>Address:</i></td>
        <td style='font-size: 16px;' colspan='3'>" . $formulario['enderecoDestino'] . ", " . $formulario['numeroDestino'] . " - " . $formulario['complementoDestino'] . "</td>
        </tr>
        <tr>
        <td colspan='1'>&nbsp;</i></td>
        <td style='font-size: 16px;' colspan='2'>" . $formulario['bairroDestino'] . "/ " . $formulario['municipioDestino'] . "/ " . $formulario['ufDestino'] . "  " . $formulario['cepDestino'] . "</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td style='font-size: 16px;' colspan='1'>País /<i style='font-size: 16px;'>Country:</i></td>
        <td style='font-size: 16px;' colspan='3'>" . $formulario['paisDestino'] . "</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='1' style='font-size: 16px;'>Tel /<i style='font-size: 16px;'>Phone:</i></td>
        <td colspan='3' style='font-size: 16px;'>" . $formulario['telefoneDestino'] . "</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='1' style='font-size: 16px;'>CPF/CNPJ /<i style='font-size: 16px;'>Tax ID:</i></td>
        <td colspan='3' style='font-size: 16px;'>" . $formulario['docRecebedor'] . "</td>
        </tr>
        <tr>
        <td colspan='2' style='font-size: 12px;'>&nbsp;--</td>
        <td colspan='2' align='right' style='font-size: 12px;'>&nbsp;--</td>
        </tr>
        <tr>
        <td style='width: 100%;font-size: 14px;' colspan='4'>O remetente declara ter conhecimento dos Termos e Condições de Transporte que regulamentam os serviços
        Messenger, com os quais concorda no ato da emissão desta guia. Tais Termos e Condições e, quando aplicável, a Convenção de Varsóvia e/ou Montreal, limitam e/ou excluem a
        responsabilidade da Messenger por perda, dano ou atraso na entrega. Este envio não contém dinheiro, jóias, cartões de crédito/alimentação/refeição ou materiais perigosos. 
        A Messenger não transporta cartas e cartões postais.<br><br></th>
        </td>
        <tr>
        <th style='width: 90%;border: 1px solid black' colspan='2'><center>Remetente / <i>Shipper</i></center></th>
        <th style='width: 100%;border: 1px solid black' colspan='2'><center>Mensageiro / <i>Courier</i></center></th>
        </tr>
        <tr>
        <th style='width: 90%;border: 1px solid black' colspan='2' align='left'><br>&nbsp;Data / <i>Date:</i> ____/____/____&nbsp;&nbsp;&nbsp;&nbsp; Hora:________<br><br><br>&nbsp;Assinatura:<br>&nbsp;</th>
        <th style='width: 90%;border: 1px solid black' colspan='2' align='left'><br>&nbsp;Data / <i>Date:</i> ____/____/____&nbsp;&nbsp;&nbsp;&nbsp; Hora:________<br><br><br>&nbsp;Assinatura:<br>&nbsp;</th>
        </tr>
        <tr>
        <td style='width: 164px;'>&nbsp;</td>
        <td style='width: 103px;'>&nbsp;</td>
        </tr>
        <tr>
        <th style='width: 100%;border: 1px solid black' colspan='4'><center>Informações da entrega / <i>Delivery information</i></center></th>
        </tr>
        <tr>
        <br>
        <th style='width: 100%;border: 1px solid black' align='left'  colspan='4'><br>&nbsp;Data / <i>Date:</i>  ____/____/____  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nome / <i>Delivered to:</i> ________________________  <br><br><br>&nbsp;Hora / <i>Time:</i> _____________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Assinatura / <i>Sign:</i> ________________________<br>&nbsp;</th>
        </tr>
        </tbody>
        </table>
        </center>
        </body>
        </html>";

        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->set_paper('a4', 'portrait');
        $dompdf->render();
        $pdf = $dompdf->output(); // Cria o pdf
        $pdf_path = $assetsPath . "pdf/" . $ewb . ".pdf";
        file_put_contents($pdf_path, $pdf);

        // Gerar a URL pública do PDF
        $pdf_url = "https://api.upsilan.com.br/assets/pdf/" . $ewb . ".pdf";


    }

    // Gerar resposta JSON com o número do documento e a URL para download
    $response = array(
        'numeroDocumento' => $ewb,
        'documentoUrl' => $pdf_url // URL pública do PDF
    );


    // Verificar se o cliente quer gerar coleta
    if (isset($formulario['coleta']) && $formulario['coleta'] === 'SIM') {
        $coletaData = array(
            'numeroDocumento' => $ewb,
            'nome' => $formulario['nome'],
            'cnpjpagador' => $formulario['cnpjpagador'],
            'razaoSocial' => $formulario['razaoSocial'],
            'cnpj' => $formulario['cnpj'],
            'email' => $formulario['email'],
            'telefone' => $formulario['telefone'],
            'cep' => $formulario['cep'],
            'endereco' => $formulario['endereco'],
            'numero' => $formulario['numero'],
            'complemento' => $formulario['complemento'],
            'bairro' => $formulario['bairro'],
            'municipio' => $formulario['municipio'],
            'uf' => $formulario['uf'],
            'pais' => $formulario['pais'],
            'nomeRecebedor' => $formulario['nomeRecebedor'],
            'razaoSocialDestino' => $formulario['razaoSocialDestino'],
            'docRecebedor' => $formulario['docRecebedor'],
            'emailDestino' => $formulario['emailDestino'],
            'telefoneDestino' => $formulario['telefoneDestino'],
            'cepDestino' => $formulario['cepDestino'],
            'enderecoDestino' => $formulario['enderecoDestino'],
            'numeroDestino' => $formulario['numeroDestino'],
            'complementoDestino' => $formulario['complementoDestino'],
            'bairroDestino' => $formulario['bairroDestino'],
            'municipioDestino' => $formulario['municipioDestino'],
            'ufDestino' => $formulario['ufDestino'],
            'paisDestino' => $formulario['paisDestino'],
            'remessa' => $formulario['remessa'],
            'peso' => $formulario['peso'],
            'altura' => $formulario['altura'],
            'largura' => $formulario['largura'],
            'profundidade' => $formulario['profundidade'],
            'notaFiscal' => $formulario['notaFiscal'],
            'quantidade' => $formulario['quantidade'],
            'valor' => $formulario['valor'],
            'conteudo' => $formulario['conteudo'],
            'obs' => $formulario['obs'],
            'servico' => $formulario['servico'],
            'retornoDeProtocolo' => $formulario['retornoDeProtocolo'],
            'nextFlyOut' => $formulario['nextFlyOut'],
            'deliveryDutyPaid' => $formulario['deliveryDutyPaid'],
            'coleta' => $formulario['coleta'],
            'data' => $formulario['data'],
            'hora1' => $formulario['hora1'],
            'hora2' => $formulario['hora2']
        );

        $coletaJson = json_encode($coletaData);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(array('error' => 'Erro ao codificar JSON: ' . json_last_error_msg()));
            exit();
        }

        $ch = curl_init('https://api.upsilan.com.br/form_coleta/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $coletaJson);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode(array('error' => 'Erro cURL: ' . curl_error($ch)));
            curl_close($ch);
            exit();
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            echo json_encode(array('error' => 'Erro ao enviar solicitação de coleta. HTTP Status Code: ' . $http_code));
            exit();
        }

        $resultData = json_decode($result, true);

        if ($resultData !== null) {
            if ($resultData['status'] == 1) {
                $id = $resultData['data'][0]['id'];
                $response = array(
                    'documentoUrl' => $pdf_url,
                    'numeroDocumento' => $ewb,
                    'numeroColeta' => $id
                );
            } else {
                $response = array(
                    'documentoUrl' => $pdf_url,
                    'numeroDocumento' => $ewb,
                );
            }
        } else {
            echo json_encode(array('error' => 'Nenhum dado recebido'));
        }
    }
    echo json_encode($response);
}