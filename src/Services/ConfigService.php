<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Format\BaseModel;
use Zijinghua\Zbasement\Http\Services\BaseService;
use Zijinghua\Zfilesystem\Repositories\FileRepository;
use Exception;
use Zijinghua\Zfilesystem\Repositories\ConfigRepository;

class ConfigService extends BaseService
{
    /** config reqpository */
    protected $configRepository;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }
    /**
     * get config list
     * @return mixed
     * @throws Exception
     */
    public function getList()
    {
        $response = $this->configRepository->getList();
        $messageResponse = $this->messageResponse(
            $this->getSlug(),
            'index.submit.success',
            $response->toArray(),
            null,
            null
        );
        return $messageResponse;
    }
}
