<?php

namespace Zijinghua\Zfilesystem\http\Controllers;

use Zijinghua\Zfilesystem\Http\Requests\UploadRequest;
use Zijinghua\Zfilesystem\Http\Resources\FileResource;
use Zijinghua\Zfilesystem\Http\Services\FileService;

class FileController
{
    /**
     * 获取图片
     * @param FileService $fileService
     * @param $uuid
     * @return FileResource
     */
    public function show(FileService $fileService, $uuid)
    {
        $result = $fileService->getFile($uuid);

        return new FileResource($result);
    }
    /**
     * 上传文件
     * @param UploadRequest $request
     * @param FileService $fileService
     * @return FileResource
     * @throws \Exception
     */
    public function upload(UploadRequest $request, FileService $fileService)
    {
        $result = $fileService->upload($request);

        return new FileResource($result);
    }
}