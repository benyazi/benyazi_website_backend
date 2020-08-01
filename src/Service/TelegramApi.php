<?php
namespace App\Service;

use GuzzleHttp\Client;

class TelegramApi
{
    /** @var Client */
    private $client;
    /** @var string */
    private $currentBotToken;

    public function __construct()
    {
    }

    /**
     * @param string $botToken
     * @return string
     */
    private function getBaseUriForBot(string $botToken)
    {
        return 'https://api.telegram.org/bot' . $botToken . '/';
    }
    /**
     * @param string $botToken
     * @return $this
     */
    public function setClientForBot(string $botToken)
    {
        $this->currentBotToken = $botToken;
        $this->client = new Client([
            'base_uri' => $this->getBaseUriForBot($botToken)
        ]);
        return $this;
    }

    private function request($method, $url, $data = [])
    {
        $response = $this->client->request($method, $url, $data);
        if($response->getStatusCode() === 200) {
            $content = json_decode($response->getBody()->getContents(), true);
            if($content['ok'] && @$content['result']) {
                return $content['result'];
            }
        }
        return [];
    }

    /**
     * @return array
     */
    public function getUpdates()
    {
        return $this->request('POST', 'getUpdates');
    }

    public function downloadFile($fileId, $fileName)
    {
        $resp = $this->request('POST', 'getFile', [
            'json' => [
                'file_id' => $fileId
            ]
        ]);
        if(isset($resp['file_path'])) {
            $url = 'https://api.telegram.org/file/bot' . $this->currentBotToken . '/' . $resp['file_path'];
            $fileName = '' . $fileName;
            return file_put_contents($fileName, file_get_contents($url));
        }
        return false;
    }
}