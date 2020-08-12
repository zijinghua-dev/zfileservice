<?php

namespace Zijinghua\Zfilesystem\Http\Controllers;

use App\Http\Requests\Article\IndexRequest;
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
            $configs = $configService->getList($request);
        } catch (\Exception $e) {
            response($e->getMessage(), 500);
        }
        return ConfigResource::collection($configs);
    }
}