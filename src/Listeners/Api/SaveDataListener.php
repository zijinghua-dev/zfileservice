<?php

namespace Zijinghua\Zfilesystem\Listeners\Api;

use Zijinghua\Zfilesystem\Events\Api\SaveDataEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Zijinghua\Zfilesystem\Repositories\FileRepository;

class SaveDataListener implements ShouldQueue
{
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function handle(SaveDataEvent $event)
    {
        $params = $event->params;
        $this->fileRepository->saveFileData($params);
    }
}
