<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function checkForUpdates()
{
    $client = new Client();

    $daytonaCnpj = '00580312000111';
    $brudamApiUrl = 'https://messenger.brudam.com.br/api/v1/tracking/ocorrencias/aut_frete';
    $minutaApiUrl = 'https://messenger.brudam.com.br/api/v1/operacional/consulta/minuta/';
    $daytonaApiUrl = 'https://ocorrenciasdaytona.azurewebsites.net/api/Ocorrencia';

    try {
        $response = $client->get($brudamApiUrl, [
            'query' => ['cnpj' => $daytonaCnpj]
        ]);

        if ($response->getStatusCode() == 1) {
            $data = json_decode($response->getBody(), true);

            // Verifica se há novas ocorrências
            if (isset($data['ocorrencias']) && !empty($data['ocorrencias'])) {
                foreach ($data['ocorrencias'] as $ocorrencia) {
                    $minutaId = $ocorrencia['data'][0]['dados'][0]['numero'];

                    // Chamada ao endpoint para obter cidade, estado e pais
                    $minutaResponse = $client->get($minutaApiUrl . $minutaId);
                    $minutaData = json_decode($minutaResponse->getBody(), true);

                    $cidade = $minutaData['data'][0]['dest'][0]['cMun'] ?? '';
                    $estado = $minutaData['data'][0]['dest'][0]['estado'] ?? '';
                    $pais = $minutaData['data'][0]['dest'][0]['cPais'] ?? '';

                    $payload = [
                        'hawb' => $ocorrencia['data'][0]['documento'],
                        'codigoocorrencia' => $ocorrencia['data'][0]['dados'][0]['id'],
                        'dataocorrencia' => $ocorrencia['data'][0]['dados'][0]['data'],
                        'cidade' => $cidade,
                        'estado' => $estado,
                        'pais' => $pais
                    ];

                    // Envia o POST para a API da Daytona
                    $client->post($daytonaApiUrl, [
                        'json' => $payload
                    ]);
                }
            }
        } else {
            echo "Erro na requisição: " . $response->getStatusCode();
        }
    } catch (RequestException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

// Agendamento usando cron
checkForUpdates();
?>