<?php

namespace Zijinghua\Zfilesystem\Listeners\Api;

use Zijinghua\Zfilesystem\Events\Api\UploadEvent;
use Zijinghua\Zfilesystem\Http\Services\FileService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadListener implements ShouldQueue
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function handle(UploadEvent $event)
    {
        $params = $event->params;
        $this->fileService->uploadToOss($params->get('upload_file'), $params->get('save_path'));
    }
}
