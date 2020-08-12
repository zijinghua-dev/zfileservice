<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Format\BaseModel;
use Zijinghua\Zfilesystem\Repositories\FileRepository;
use Exception;
use Zijinghua\Zfilesystem\Repositories\ConfigRepository;

class ConfigService
{
    /** config reqpository */
    protected $configRepository;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }
    /**
     * get config list
     *
     * @return void
     */
    public function getList()
    {
        return $this->configRepository->getList();
    }

    public function store($params)
    {
        return '';
    }
}