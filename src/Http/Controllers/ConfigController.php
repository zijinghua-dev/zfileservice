<?php

namespace Zijinghua\Zfilesystem\Http\Controllers;

use Zijinghua\Zfilesystem\Http\Requests\Config\IndexRequest;
use Zijinghua\Zfilesystem\Http\Services\ConfigService;
use Zijinghua\Zfilesystem\Http\Resources\ConfigResource;

class ConfigController
{
    /**
     * 获取配置列表
     *
     * @param Request $request
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function index(IndexRequest $request, ConfigService $configService)
    {
        try {
            $result = $configService->getList($request);
            return $result->response();
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
