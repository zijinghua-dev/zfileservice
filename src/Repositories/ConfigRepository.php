<?php

namespace Zijinghua\Zfilesystem\Repositories;

use Zijinghua\Zfilesystem\Models\Config;

class ConfigRepository
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config->newQuery();
    }

    /**
     * get config list
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getList()
    {
        try {
            $config = $this->config
                ->orderByDesc('created_at')
                ->get();
            return $config;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), 500);
        }
    }
}
