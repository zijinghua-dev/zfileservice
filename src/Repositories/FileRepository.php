<?php

namespace Zijinghua\Zfilesystem\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FileRepository
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
    }

    /**
     * 获取文件数据
     * @param $fileMd5
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFileData($fileMd5)
    {
        $uri = config('zfilesystem.file.file_sevice.host') .
            config('zfilesystem.file.file_sevice.api') .
            '/' .
            $fileMd5;

        return $this->httpRequest('get', $uri);
    }
    /**
     * 保存文件数据
     * @param $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function saveFileData($params)
    {
        $uri = config('zfilesystem.file.file_sevice.host') .
            config('zfilesystem.file.file_sevice.api');
        return $this->httpRequest('post', $uri, $params);
    }

    /**
     * @param $method
     * @param $uri
     * @param null $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpRequest($method, $uri, $params = null)
    {
        switch ($method) {
            case 'post':
                $response = $this->postData($uri, $params);
                break;
            case 'get':
                $response = $this->getData($uri);
                break;
            default:
                $response = $this->postData($uri, $params);
        }
        return $response;
    }

    /**
     * @param $uri
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getData($uri)
    {
        try {
            $params = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ];
            $response = $this->client->request('GET', $uri, $params);
            $responseDecode = json_decode($response->getBody()->__toString(), true);
            return $responseDecode;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        } catch (ClientException $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
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
