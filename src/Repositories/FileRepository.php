<?php

namespace Zijinghua\Zfilesystem\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FileRepository
{
    /** @var Client  */
    protected $client;
    /** @var string base uri */
    protected $baseUri;

    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
        $this->baseUri = config('zfilesystem.file.file_sevice.host') .
            config('zfilesystem.file.file_sevice.api');
    }

    /**
     * 获取文件数据
     * @param $fileMd5
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetch(array $params)
    {
        $uri = $this->baseUri . '/show';
        return $this->httpRequest($uri, $params);
    }
    /**
     * 保存文件数据
     * @param $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function saveFileData($params)
    {
        $uri = $uri = $this->baseUri . '/store';
        return $this->httpRequest($uri, $params);
    }

    /**
     * @param $method
     * @param $uri
     * @param null $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpRequest($uri, $params = null)
    {
        $response = $this->postData($uri, $params);
        return $response;
    }
    /**
     * @param $uri
     * @param $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function postData($uri, $params)
    {
        try {
            $params = array_merge($params, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
            ]);
            $formData = [
                'form_params' => $params
            ];
            $response = $this->client->request('POST', $uri, $formData);
            $responseDecode = json_decode($response->getBody()->__toString(), true);
            return $responseDecode;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        } catch (ClientException $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
